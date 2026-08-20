<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('crm_leads')) {
            Schema::table('crm_leads', function (Blueprint $table) {
                if (! Schema::hasColumn('crm_leads', 'lead_type')) {
                    $table->string('lead_type')->default('customer')->after('id');
                }
                if (! Schema::hasColumn('crm_leads', 'job_title')) {
                    $table->string('job_title')->nullable()->after('contact_person');
                }
                if (! Schema::hasColumn('crm_leads', 'country')) {
                    $table->string('country')->nullable()->after('city_location');
                }
                if (! Schema::hasColumn('crm_leads', 'website')) {
                    $table->string('website')->nullable()->after('email');
                }
                if (! Schema::hasColumn('crm_leads', 'monthly_sales_volume')) {
                    $table->string('monthly_sales_volume')->nullable()->after('deal_value');
                }
                if (! Schema::hasColumn('crm_leads', 'product_interest')) {
                    $table->string('product_interest')->nullable()->after('system_interest');
                }
                if (! Schema::hasColumn('crm_leads', 'customer_type')) {
                    $table->string('customer_type')->nullable()->after('monthly_sales_volume');
                }
            });
        }

        if (Schema::hasTable('web_lead_captures')) {
            Schema::table('web_lead_captures', function (Blueprint $table) {
                if (! Schema::hasColumn('web_lead_captures', 'lead_type')) {
                    $table->string('lead_type')->default('customer')->after('id');
                }
                if (! Schema::hasColumn('web_lead_captures', 'job_title')) {
                    $table->string('job_title')->nullable()->after('client_name');
                }
                if (! Schema::hasColumn('web_lead_captures', 'country')) {
                    $table->string('country')->nullable()->after('city_location');
                }
                if (! Schema::hasColumn('web_lead_captures', 'website')) {
                    $table->string('website')->nullable()->after('email');
                }
                if (! Schema::hasColumn('web_lead_captures', 'monthly_sales_volume')) {
                    $table->string('monthly_sales_volume')->nullable()->after('estimated_budget_ngn');
                }
                if (! Schema::hasColumn('web_lead_captures', 'product_interest')) {
                    $table->string('product_interest')->nullable()->after('system_interest');
                }
                if (! Schema::hasColumn('web_lead_captures', 'customer_type')) {
                    $table->string('customer_type')->nullable()->after('monthly_sales_volume');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('crm_leads')) {
            Schema::table('crm_leads', function (Blueprint $table) {
                $table->dropColumn([
                    'lead_type',
                    'job_title',
                    'country',
                    'website',
                    'monthly_sales_volume',
                    'product_interest',
                    'customer_type',
                ]);
            });
        }

        if (Schema::hasTable('web_lead_captures')) {
            Schema::table('web_lead_captures', function (Blueprint $table) {
                $table->dropColumn([
                    'lead_type',
                    'job_title',
                    'country',
                    'website',
                    'monthly_sales_volume',
                    'product_interest',
                    'customer_type',
                ]);
            });
        }
    }
};
