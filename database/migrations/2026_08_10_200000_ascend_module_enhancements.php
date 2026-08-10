<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Salary Records table
        if (! Schema::hasTable('salary_records')) {
            Schema::create('salary_records', function (Blueprint $table) {
                $table->id();
                $table->string('employee_name');
                $table->string('employee_id')->nullable();
                $table->string('department')->nullable();
                $table->string('role')->nullable();
                $table->decimal('gross_salary', 14, 2)->default(0);
                $table->decimal('paye_tax', 14, 2)->default(0);
                $table->decimal('pension', 14, 2)->default(0);
                $table->decimal('nhf', 14, 2)->default(0);
                $table->decimal('other_deductions', 14, 2)->default(0);
                $table->decimal('net_salary', 14, 2)->default(0);
                $table->string('pay_period'); // e.g. '2026-08'
                $table->date('payment_date')->nullable();
                $table->enum('status', ['pending', 'paid', 'cancelled'])->default('pending');
                $table->string('bank_name')->nullable();
                $table->string('account_number')->nullable();
                $table->text('notes')->nullable();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->timestamps();
            });
        }

        // Extend invoices table with items JSON
        if (Schema::hasTable('invoices')) {
            $cols = Schema::getColumnListing('invoices');
            if (! in_array('items', $cols)) {
                Schema::table('invoices', function (Blueprint $table) {
                    $table->json('items')->nullable();
                });
            }
        }

        // Extend pos_receipts table with items JSON
        if (Schema::hasTable('pos_receipts')) {
            $cols = Schema::getColumnListing('pos_receipts');
            if (! in_array('items', $cols)) {
                Schema::table('pos_receipts', function (Blueprint $table) {
                    $table->json('items')->nullable();
                });
            }
        }

        // Extend expenses table
        if (Schema::hasTable('expenses')) {
            $cols = Schema::getColumnListing('expenses');
            Schema::table('expenses', function (Blueprint $table) use ($cols) {
                if (! in_array('receipt_path', $cols)) {
                    $table->string('receipt_path')->nullable();
                }
                if (! in_array('receipt_original_name', $cols)) {
                    $table->string('receipt_original_name')->nullable();
                }
                if (! in_array('approval_status', $cols)) {
                    $table->enum('approval_status', ['pending', 'approved', 'rejected'])->default('pending');
                }
                if (! in_array('approved_by', $cols)) {
                    $table->unsignedBigInteger('approved_by')->nullable();
                }
                if (! in_array('approved_at', $cols)) {
                    $table->timestamp('approved_at')->nullable();
                }
                if (! in_array('reference', $cols)) {
                    $table->string('reference')->nullable();
                }
            });
        }

        // AI Finance Insights cache table
        if (! Schema::hasTable('ai_finance_insights')) {
            Schema::create('ai_finance_insights', function (Blueprint $table) {
                $table->id();
                $table->string('insight_type'); // 'burn_rate', 'anomaly', 'forecast', 'recommendation'
                $table->string('title');
                $table->text('body');
                $table->string('severity')->default('info'); // info, warning, critical
                $table->decimal('metric_value', 16, 2)->nullable();
                $table->string('metric_label')->nullable();
                $table->json('metadata')->nullable();
                $table->boolean('is_read')->default(false);
                $table->unsignedBigInteger('user_id')->nullable();
                $table->timestamps();
            });
        }

        // Email Templates table
        if (! Schema::hasTable('email_templates')) {
            Schema::create('email_templates', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('category')->default('General');
                $table->string('subject')->nullable();
                $table->longText('body')->nullable();
                $table->string('cta_text')->nullable();
                $table->string('cta_url')->nullable();
                $table->string('footer')->nullable();
                $table->string('status')->default('draft');
                $table->json('stats')->nullable(); // open_rate, click_rate
                $table->unsignedBigInteger('user_id')->nullable();
                $table->timestamps();
            });
        } else {
            $existingCols = Schema::getColumnListing('email_templates');
            Schema::table('email_templates', function (Blueprint $table) use ($existingCols) {
                if (! in_array('status', $existingCols)) {
                    $table->string('status')->default('draft')->nullable();
                }
                if (! in_array('stats', $existingCols)) {
                    $table->json('stats')->nullable();
                }
                if (! in_array('user_id', $existingCols)) {
                    $table->unsignedBigInteger('user_id')->nullable();
                }
                if (! in_array('body', $existingCols)) {
                    $table->longText('body')->nullable();
                }
                if (! in_array('cta_text', $existingCols)) {
                    $table->string('cta_text')->nullable();
                }
                if (! in_array('cta_url', $existingCols)) {
                    $table->string('cta_url')->nullable();
                }
                if (! in_array('footer', $existingCols)) {
                    $table->string('footer')->nullable();
                }
            });
        }

        // WhatsApp Templates table
        if (! Schema::hasTable('whatsapp_templates')) {
            Schema::create('whatsapp_templates', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('category'); // TRANSACTIONAL, MARKETING, OTP
                $table->text('body');
                $table->json('variables')->nullable(); // ['{{customer_name}}', '{{amount}}']
                $table->enum('status', ['approved', 'pending', 'rejected'])->default('pending');
                $table->unsignedBigInteger('user_id')->nullable();
                $table->timestamps();
            });
        }

        // WhatsApp Broadcasts table
        if (! Schema::hasTable('whatsapp_broadcasts')) {
            Schema::create('whatsapp_broadcasts', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->text('message');
                $table->string('segment')->nullable();
                $table->integer('total_recipients')->default(0);
                $table->integer('delivered')->default(0);
                $table->integer('read')->default(0);
                $table->enum('status', ['draft', 'scheduled', 'sent', 'failed'])->default('draft');
                $table->timestamp('scheduled_at')->nullable();
                $table->timestamp('sent_at')->nullable();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->timestamps();
            });
        }

        // Ads Accounts table
        if (! Schema::hasTable('ads_accounts')) {
            Schema::create('ads_accounts', function (Blueprint $table) {
                $table->id();
                $table->string('platform'); // meta, google, tiktok, linkedin
                $table->string('account_name');
                $table->string('account_id')->nullable();
                $table->decimal('total_spend', 14, 2)->default(0);
                $table->decimal('roas', 8, 2)->default(0);
                $table->decimal('ctr', 8, 4)->default(0);
                $table->decimal('cpc', 10, 2)->default(0);
                $table->integer('impressions')->default(0);
                $table->integer('clicks')->default(0);
                $table->integer('conversions')->default(0);
                $table->boolean('is_active')->default(true);
                $table->json('metadata')->nullable();
                $table->timestamp('last_synced_at')->nullable();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('salary_records');
        Schema::dropIfExists('ai_finance_insights');
        Schema::dropIfExists('email_templates');
        Schema::dropIfExists('whatsapp_templates');
        Schema::dropIfExists('whatsapp_broadcasts');
        Schema::dropIfExists('ads_accounts');
    }
};
