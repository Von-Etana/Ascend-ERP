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
        if (Schema::hasTable('invoices') && ! Schema::hasColumn('invoices', 'installation_address')) {
            Schema::table('invoices', function (Blueprint $table) {
                $table->text('installation_address')->nullable()->after('client_name');
            });
        }

        if (Schema::hasTable('price_quotes') && ! Schema::hasColumn('price_quotes', 'installation_address')) {
            Schema::table('price_quotes', function (Blueprint $table) {
                $table->text('installation_address')->nullable()->after('address');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('invoices') && Schema::hasColumn('invoices', 'installation_address')) {
            Schema::table('invoices', function (Blueprint $table) {
                $table->dropColumn('installation_address');
            });
        }

        if (Schema::hasTable('price_quotes') && Schema::hasColumn('price_quotes', 'installation_address')) {
            Schema::table('price_quotes', function (Blueprint $table) {
                $table->dropColumn('installation_address');
            });
        }
    }
};
