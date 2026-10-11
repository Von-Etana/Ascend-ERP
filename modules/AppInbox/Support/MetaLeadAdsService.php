<?php

namespace Modules\AppInbox\Support;

use App\Models\CrmLead;
use App\Models\WebLeadCapture;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class MetaLeadAdsService
{
    /**
     * Check if the incoming payload is a Meta Lead Ads change event.
     */
    public function isLeadgenEvent(array $payload): bool
    {
        $entry = $payload['entry'][0] ?? null;
        $changes = $entry['changes'] ?? [];

        foreach ($changes as $change) {
            if (($change['field'] ?? '') === 'leadgen' && ! empty($change['value']['leadgen_id'])) {
                return true;
            }
        }

        return false;
    }

    /**
     * Ingest a Meta Lead Ads submission into the CRM and triggers automated welcome workflows.
     */
    public function handle(array $payload): ?CrmLead
    {
        $entry = $payload['entry'][0] ?? null;
        $changes = $entry['changes'] ?? [];

        foreach ($changes as $change) {
            if (($change['field'] ?? '') !== 'leadgen') {
                continue;
            }

            $value = $change['value'] ?? [];
            $leadgenId = (string) ($value['leadgen_id'] ?? '');
            $formId = (string) ($value['form_id'] ?? '');
            $pageId = (string) ($value['page_id'] ?? ($entry['id'] ?? ''));

            if ($leadgenId === '') {
                continue;
            }

            // Retrieve field answers from Meta Graph API
            $fields = $this->fetchLeadFromMeta($leadgenId, $pageId);

            $contactName = $fields['full_name'] ?? $fields['name'] ?? 'Meta Ad Lead';
            $email = $fields['email'] ?? null;
            $phone = $fields['phone_number'] ?? $fields['phone'] ?? null;
            $companyName = $fields['company_name'] ?? ($contactName.' Business');
            $systemInterest = $fields['system_interest'] ?? $fields['product_interest'] ?? 'Ascend Energy & ERP Solution';
            $budget = (float) ($fields['budget'] ?? 3500000.00);

            // 1. Create or update CrmLead
            $lead = CrmLead::query()->firstOrCreate(
                ['phone' => $phone ?: "lead_{$leadgenId}"],
                [
                    'company_name' => $companyName,
                    'contact_person' => $contactName,
                    'email' => $email,
                    'phone' => $phone,
                    'deal_value' => $budget,
                    'status' => 'new',
                    'lead_type' => 'customer',
                    'system_interest' => $systemInterest,
                    'notes' => "Captured via Meta Lead Ad. Form ID: {$formId}, Leadgen ID: {$leadgenId}",
                ]
            );

            // 2. Log in WebLeadCapture audit table
            WebLeadCapture::query()->create([
                'client_name' => $contactName,
                'phone' => $phone,
                'email' => $email,
                'company_name' => $companyName,
                'city_location' => $fields['city'] ?? 'Nigeria (Online Ad)',
                'system_interest' => $systemInterest,
                'estimated_budget_ngn' => $budget,
                'source_url' => "Meta Lead Ads (Form: {$formId})",
                'status' => 'new',
                'special_notes' => "Meta Lead ID: {$leadgenId}",
            ]);

            // 3. Dispatch automated WhatsApp / Email notification if phone is present
            if ($phone && class_exists(\Modules\AppAscend\Services\WhatsAppNotificationService::class)) {
                try {
                    $waService = app(\Modules\AppAscend\Services\WhatsAppNotificationService::class);
                    $welcomeMsg = "Hello {$contactName}! Thank you for your inquiry via our Facebook/Instagram ad. An Ascend Systems technical advisor is reviewing your request regarding '{$systemInterest}'. How can we best assist you today?";
                    $waService->sendMessage($phone, $welcomeMsg);
                } catch (Throwable $e) {
                    Log::warning('Failed sending initial WhatsApp greeting to Meta Lead: '.$e->getMessage());
                }
            }

            return $lead;
        }

        return null;
    }

    /**
     * Fetch the submitted form fields from Meta Graph API using the Page Access Token.
     */
    protected function fetchLeadFromMeta(string $leadgenId, string $pageId): array
    {
        $token = trim((string) (
            config('modules.appinbox.meta_page_access_token')
            ?: env('META_PAGE_ACCESS_TOKEN')
            ?: env('FACEBOOK_PAGE_ACCESS_TOKEN', '')
        ));

        if ($token === '') {
            return [];
        }

        try {
            $response = Http::timeout(10)
                ->get("https://graph.facebook.com/v21.0/{$leadgenId}", [
                    'access_token' => $token,
                ]);

            if (! $response->successful()) {
                Log::warning("Meta Graph API error fetching lead {$leadgenId}: ".$response->body());
                return [];
            }

            $data = $response->json();
            $fieldData = $data['field_data'] ?? [];
            $parsed = [];

            foreach ($fieldData as $field) {
                $name = (string) ($field['name'] ?? '');
                $values = (array) ($field['values'] ?? []);
                $firstVal = trim((string) ($values[0] ?? ''));

                if ($name !== '' && $firstVal !== '') {
                    $parsed[$name] = $firstVal;
                }
            }

            return $parsed;
        } catch (Throwable $e) {
            Log::error("Exception fetching Meta Lead {$leadgenId}: ".$e->getMessage());
            return [];
        }
    }
}
