<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('web_lead_captures')) {
            Schema::table('web_lead_captures', function (Blueprint $table) {
                if (! Schema::hasColumn('web_lead_captures', 'company_name')) {
                    $table->string('company_name')->nullable()->after('client_name');
                }
                if (! Schema::hasColumn('web_lead_captures', 'property_type')) {
                    $table->string('property_type')->nullable()->after('city_location');
                }
                if (! Schema::hasColumn('web_lead_captures', 'installation_address')) {
                    $table->string('installation_address')->nullable()->after('property_type');
                }
                if (! Schema::hasColumn('web_lead_captures', 'preferred_contact_method')) {
                    $table->string('preferred_contact_method')->default('whatsapp')->after('email');
                }
                if (! Schema::hasColumn('web_lead_captures', 'daily_generator_hours')) {
                    $table->integer('daily_generator_hours')->default(0)->after('system_interest');
                }
                if (! Schema::hasColumn('web_lead_captures', 'monthly_fuel_spend_ngn')) {
                    $table->decimal('monthly_fuel_spend_ngn', 12, 2)->default(0)->after('daily_generator_hours');
                }
                if (! Schema::hasColumn('web_lead_captures', 'roof_mounting_type')) {
                    $table->string('roof_mounting_type')->nullable()->after('monthly_fuel_spend_ngn');
                }
                if (! Schema::hasColumn('web_lead_captures', 'purchasing_timeline')) {
                    $table->string('purchasing_timeline')->default('immediate')->after('estimated_budget_ngn');
                }
                if (! Schema::hasColumn('web_lead_captures', 'financing_preference')) {
                    $table->string('financing_preference')->default('outright')->after('purchasing_timeline');
                }
                if (! Schema::hasColumn('web_lead_captures', 'referral_code')) {
                    $table->string('referral_code')->nullable()->after('financing_preference');
                }
                if (! Schema::hasColumn('web_lead_captures', 'ai_lead_score')) {
                    $table->integer('ai_lead_score')->default(85)->after('referral_code');
                }
                if (! Schema::hasColumn('web_lead_captures', 'ai_qualification_summary')) {
                    $table->text('ai_qualification_summary')->nullable()->after('ai_lead_score');
                }
                if (! Schema::hasColumn('web_lead_captures', 'special_notes')) {
                    $table->text('special_notes')->nullable()->after('ai_qualification_summary');
                }
            });
        }

        if (Schema::hasTable('crm_leads')) {
            Schema::table('crm_leads', function (Blueprint $table) {
                if (! Schema::hasColumn('crm_leads', 'city_location')) {
                    $table->string('city_location')->nullable()->after('phone');
                }
                if (! Schema::hasColumn('crm_leads', 'system_interest')) {
                    $table->string('system_interest')->nullable()->after('city_location');
                }
                if (! Schema::hasColumn('crm_leads', 'ai_lead_score')) {
                    $table->integer('ai_lead_score')->default(80)->after('deal_value');
                }
                if (! Schema::hasColumn('crm_leads', 'purchasing_timeline')) {
                    $table->string('purchasing_timeline')->nullable()->after('ai_lead_score');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('web_lead_captures')) {
            Schema::table('web_lead_captures', function (Blueprint $table) {
                $table->dropColumn([
                    'company_name',
                    'property_type',
                    'installation_address',
                    'preferred_contact_method',
                    'daily_generator_hours',
                    'monthly_fuel_spend_ngn',
                    'roof_mounting_type',
                    'purchasing_timeline',
                    'financing_preference',
                    'referral_code',
                    'ai_lead_score',
                    'ai_qualification_summary',
                    'special_notes',
                ]);
            });
        }

        if (Schema::hasTable('crm_leads')) {
            Schema::table('crm_leads', function (Blueprint $table) {
                $table->dropColumn(['city_location', 'system_interest', 'ai_lead_score', 'purchasing_timeline']);
            });
        }
    }
};
