<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('invoices') && ! Schema::hasColumn('invoices', 'category')) {
            Schema::table('invoices', function (Blueprint $table) {
                $table->string('category')->nullable()->default('Solar & Inverter Systems')->after('client_name');
            });
        }

        if (Schema::hasTable('price_quotes') && ! Schema::hasColumn('price_quotes', 'category')) {
            Schema::table('price_quotes', function (Blueprint $table) {
                $table->string('category')->nullable()->default('Solar & Inverter Systems')->after('client_name');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('invoices') && Schema::hasColumn('invoices', 'category')) {
            Schema::table('invoices', function (Blueprint $table) {
                $table->dropColumn('category');
            });
        }

        if (Schema::hasTable('price_quotes') && Schema::hasColumn('price_quotes', 'category')) {
            Schema::table('price_quotes', function (Blueprint $table) {
                $table->dropColumn('category');
            });
        }
    }
};
