<?php

namespace App\Support\Audit;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Throwable;

class AuditEventLogger
{
    /**
     * Log a security or compliance event to the audit ledger.
     *
     * @param  string  $event  Event name (e.g. 'post.scheduled', 'agent.handoff', 'user.login')
     * @param  Model|null  $subject  Target model object
     * @param  array  $payload  Additional contextual data
     * @param  int|null  $companyId  Associated company ID
     */
    public static function log(string $event, ?Model $subject = null, array $payload = [], ?int $companyId = null): void
    {
        try {
            $user = auth()->user();

            DB::table('ascend_audit_events')->insert([
                'company_id' => $companyId ?? session('active_company_id', 1),
                'user_id' => $user?->id,
                'event' => $event,
                'subject_type' => $subject ? get_class($subject) : null,
                'subject_id' => $subject?->getKey(),
                'payload' => json_encode($payload),
                'ip_address' => request()->ip(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (Throwable) {
            // Audit failures should not crash user-facing requests
        }
    }
}
