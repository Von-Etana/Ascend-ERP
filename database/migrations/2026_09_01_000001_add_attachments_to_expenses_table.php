<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('expenses') && ! Schema::hasColumn('expenses', 'attachments')) {
            Schema::table('expenses', function (Blueprint $table) {
                $table->json('attachments')->nullable()->after('description');
            });
        }

        if (Schema::hasTable('invoices') && ! Schema::hasColumn('invoices', 'paid_amount')) {
            Schema::table('invoices', function (Blueprint $table) {
                $table->decimal('paid_amount', 15, 2)->default(0.00)->after('total');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('expenses') && Schema::hasColumn('expenses', 'attachments')) {
            Schema::table('expenses', function (Blueprint $table) {
                $table->dropColumn('attachments');
            });
        }

        if (Schema::hasTable('invoices') && Schema::hasColumn('invoices', 'paid_amount')) {
            Schema::table('invoices', function (Blueprint $table) {
                $table->dropColumn('paid_amount');
            });
        }
    }
};
