<?php

namespace Modules\AppEmail\Livewire;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Title;
use Livewire\Component;
use Modules\AppEmail\Models\EmailCampaign;
use Modules\AppEmail\Services\NewsletterGenerationService;
use Modules\AppEmail\Services\ResendEmailService;

#[Title('Email Marketing - Ascend Systems')]
class EmailMarketing extends Component
{
    public string $view = 'overview';

    public string $campaignName = '';

    public string $subject = '';

    public string $body = '';

    public string $notice = '';

    public function sendTest(ResendEmailService $resend): void
    {
        $this->validate(['subject' => ['required', 'string', 'max:255'], 'body' => ['required', 'string']]);
        $email = (string) auth()->user()?->email;

        if ($email === '') {
            $this->notice = __('Add an email address to your profile before sending a test.');

            return;
        }

        $result = $resend->send(['to' => [$email], 'subject' => $this->subject, 'html' => nl2br(e($this->body))], 'ascend-test-'.auth()->id().'-'.md5($this->subject.$this->body));
        $this->notice = ($result['ok'] ?? false) ? __('Test email sent to :email.', ['email' => $email]) : __('Resend rejected the test email. Check your API key and sender domain.');
    }

    public function saveDraft(): void
    {
        $this->validate(['campaignName' => ['required', 'string', 'max:120'], 'subject' => ['required', 'string', 'max:255'], 'body' => ['required', 'string']]);
        $templateId = DB::table('email_templates')->insertGetId([
            'name' => $this->campaignName.' template', 'subject' => $this->subject,
            'html' => nl2br(e($this->body)), 'text' => $this->body, 'category' => 'newsletter',
            'is_active' => true, 'created_at' => now(), 'updated_at' => now(),
        ]);
        EmailCampaign::create([
            'template_id' => $templateId, 'created_by_user_id' => auth()->id(),
            'name' => $this->campaignName, 'subject' => $this->subject,
            'from_email' => config('modules.appemail.resend.from_email'),
            'from_name' => config('modules.appemail.resend.from_name'), 'status' => 'draft',
            'audience' => ['source' => 'leads', 'statuses' => ['new', 'contacted', 'qualified']],
        ]);
        $this->notice = __('Newsletter draft saved.');
    }

    public function generateNewsletter(NewsletterGenerationService $generator): void
    {
        $draft = $generator->generate([
            'current_campaign_name' => $this->campaignName,
            'current_subject' => $this->subject,
            'current_body' => $this->body,
        ]);

        $this->campaignName = $this->campaignName ?: (string) $draft['campaign_name'];
        $this->subject = (string) $draft['subject'];
        $this->body = (string) $draft['body'];
        $this->notice = ($draft['ok'] ?? false)
            ? __('AI newsletter draft generated. Review before sending.')
            : __('Draft generated from the fallback template. Configure AI settings for live generation.');
    }

    public function render(ResendEmailService $resend): View
    {
        return view('appemail::livewire.email-marketing', ['resendConfigured' => $resend->configured()])->layout(theme_view('layouts.app', 'app'), ['title' => __('Email Marketing - Ascend Systems')]);
    }
}
