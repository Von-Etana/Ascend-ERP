<?php

namespace Modules\AppInbox\Support;

use Illuminate\Support\Str;

class InboxResponsePolicy
{
    public function requiresHuman(string $body, ?float $confidence = null): bool
    {
        $normalized = Str::lower($body);
        $keywords = collect((array) config('modules.appinbox.ai.handoff_keywords', []))
            ->map(fn ($keyword) => Str::lower(trim((string) $keyword)))
            ->filter()
            ->all();

        if ($confidence !== null && $confidence < (float) config('modules.appinbox.ai.confidence_threshold', 0.80)) {
            return true;
        }

        foreach ($keywords as $keyword) {
            if (Str::contains($normalized, $keyword)) {
                return true;
            }
        }

        return false;
    }
}
