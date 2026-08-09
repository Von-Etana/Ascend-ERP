<?php

namespace Modules\AppAscend\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppNotificationService
{
    protected string $token;
    protected string $phoneNumberId;
    protected string $baseUrl = 'https://graph.facebook.com/v20.0';

    public function __construct()
    {
        $this->token = (string) config('services.whatsapp.token', env('META_ACCESS_TOKEN', ''));
        $this->phoneNumberId = (string) config('services.whatsapp.phone_number_id', env('META_WHATSAPP_PHONE_NUMBER_ID', ''));
    }

    public function sendReceiptNotification(string $recipientPhone, string $receiptNumber, float $amount, string $customerName = 'Valued Client'): array
    {
        $cleanPhone = preg_replace('/[^0-9]/', '', $recipientPhone);
        if (!str_starts_with($cleanPhone, '234') && str_starts_with($cleanPhone, '0')) {
            $cleanPhone = '234' . substr($cleanPhone, 1);
        }

        $messageBody = "🧾 *Ascend POS e-Receipt Notification*\n\n"
            . "Hello {$customerName},\n"
            . "Thank you for your purchase with Ascend Systems Nigeria!\n\n"
            . "• *Receipt No:* #{$receiptNumber}\n"
            . "• *Amount Paid:* ₦" . number_format($amount, 2) . "\n"
            . "• *Date:* " . now()->format('M d, Y h:i A') . "\n\n"
            . "View digital receipt & invoice copy: https://ascend.ng/portal/receipts/{$receiptNumber}\n\n"
            . "_Ascend AI ERP — Enterprise Operations_";

        return $this->dispatchMessage($cleanPhone, $messageBody);
    }

    public function sendInvoiceNotification(string $recipientPhone, string $invoiceNumber, float $amount, string $paystackUrl, string $clientName = 'Client'): array
    {
        $cleanPhone = preg_replace('/[^0-9]/', '', $recipientPhone);
        if (!str_starts_with($cleanPhone, '234') && str_starts_with($cleanPhone, '0')) {
            $cleanPhone = '234' . substr($cleanPhone, 1);
        }

        $messageBody = "💳 *Ascend Invoice & Payment Link Notice*\n\n"
            . "Dear {$clientName},\n"
            . "An invoice has been generated for your account.\n\n"
            . "• *Invoice No:* #{$invoiceNumber}\n"
            . "• *Total Amount:* ₦" . number_format($amount, 2) . "\n"
            . "• *Due Date:* " . now()->addDays(14)->format('M d, Y') . "\n\n"
            . "⚡ *Pay Online via Paystack (NGN):*\n"
            . "{$paystackUrl}\n\n"
            . "Thank you for doing business with us!";

        return $this->dispatchMessage($cleanPhone, $messageBody);
    }

    protected function dispatchMessage(string $toPhone, string $body): array
    {
        if (empty($this->token) || empty($this->phoneNumberId)) {
            Log::info("WhatsApp API payload prepared for {$toPhone}: {$body}");
            return [
                'success' => true,
                'status' => 'simulated',
                'message' => "Simulated WhatsApp API dispatch to +{$toPhone}",
                'payload' => $body,
            ];
        }

        try {
            $response = Http::withToken($this->token)
                ->post("{$this->baseUrl}/{$this->phoneNumberId}/messages", [
                    'messaging_product' => 'whatsapp',
                    'recipient_type' => 'individual',
                    'to' => $toPhone,
                    'type' => 'text',
                    'text' => [
                        'preview_url' => true,
                        'body' => $body,
                    ],
                ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'status' => 'delivered',
                    'message' => "WhatsApp message delivered to +{$toPhone}",
                    'data' => $response->json(),
                ];
            }

            return [
                'success' => false,
                'status' => 'error',
                'message' => "Meta WhatsApp API Error: " . $response->body(),
            ];
        } catch (Exception $e) {
            Log::error("WhatsApp Dispatch Exception: " . $e->getMessage());
            return [
                'success' => false,
                'status' => 'exception',
                'message' => $e->getMessage(),
            ];
        }
    }
}
