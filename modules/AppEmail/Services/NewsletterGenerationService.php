<?php

namespace Modules\AppEmail\Services;

use Modules\AdminAI\Services\AiGateway;
use Throwable;

class NewsletterGenerationService
{
    public function __construct(
        protected AiGateway $ai,
    ) {}

    public function generate(array $context = []): array
    {
        try {
            $result = $this->ai->generateStructured(
                prompt: $this->prompt($context),
                schema: [
                    'campaign_name' => 'string',
                    'subject' => 'string',
                    'body' => 'plain text newsletter body',
                ],
                options: [
                    'capability' => 'content',
                    'feature' => 'email.newsletter.generate',
                    'temperature' => 0.55,
                    'system' => 'You write concise B2B newsletters for Ascend Systems. Return valid JSON only.',
                ],
            );

            $data = (array) $result['data'];

            return [
                'ok' => true,
                'campaign_name' => trim((string) ($data['campaign_name'] ?? 'Ascend Systems Update')),
                'subject' => trim((string) ($data['subject'] ?? 'A better way to run your growing company')),
                'body' => trim((string) ($data['body'] ?? '')),
                'source' => 'ai',
            ];
        } catch (Throwable $exception) {
            return $this->fallback($exception->getMessage());
        }
    }

    protected function prompt(array $context): string
    {
        return trim(implode("\n\n", [
            'Create a useful newsletter for leads and customers of Ascend Systems.',
            'Audience: SMEs, retail branches, service businesses, marketing teams, and admin teams.',
            'Mention connected CRM, inbox, inventory, finance, marketing, automation, and AI agents naturally.',
            'Keep it practical and avoid SaaS pricing or subscription language.',
            'Include one clear call to action for an internal team to follow up.',
            'Context:',
            json_encode($context, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES),
        ]));
    }

    protected function fallback(string $error): array
    {
        return [
            'ok' => false,
            'campaign_name' => 'Ascend Systems Operations Update',
            'subject' => 'A smarter way to keep sales, finance, inventory, and marketing aligned',
            'body' => implode("\n\n", [
                'Hello,',
                'Ascend Systems now brings customer records, conversations, invoices, inventory, campaigns, tasks, and reporting into one connected workspace.',
                'This week, review your open leads, check pending inbox conversations, and make sure follow-up tasks are assigned before the next campaign goes out.',
                'Reply to this message if you want the team to prepare a tailored follow-up plan for your current pipeline.',
            ]),
            'source' => 'fallback',
            'error' => $error,
        ];
    }
}
