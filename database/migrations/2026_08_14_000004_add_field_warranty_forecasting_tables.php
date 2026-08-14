<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Installation & Field Dispatch Table
        if (! Schema::hasTable('installation_dispatches')) {
            Schema::create('installation_dispatches', function (Blueprint $table) {
                $table->id();
                $table->string('dispatch_number');
                $table->string('client_name');
                $table->string('client_phone')->nullable();
                $table->string('location_address'); // Abuja Garki | Lagos Lekki | Kano | Port Harcourt
                $table->string('system_type'); // e.g. 5.5kVA Solar Hybrid System | 10.2kWh Battery Storage
                $table->string('engineer_name');
                $table->timestamp('scheduled_date')->nullable();
                $table->string('status')->default('scheduled'); // scheduled | in_progress | completed | signoff_pending
                $table->json('checklist_completed')->nullable(); // inverter_mounting, battery_wiring, earthing_testing, ATS_switchover
                $table->timestamps();
            });
        }

        // 2. Warranty Serial Numbers Table
        if (! Schema::hasTable('warranty_serials')) {
            Schema::create('warranty_serials', function (Blueprint $table) {
                $table->id();
                $table->string('serial_number')->unique();
                $table->string('product_name');
                $table->string('sku')->nullable();
                $table->string('client_name');
                $table->string('client_phone')->nullable();
                $table->timestamp('purchase_date')->nullable();
                $table->timestamp('warranty_expiry_date')->nullable();
                $table->string('status')->default('active'); // active | expired | claimed | replaced
                $table->integer('maintenance_alerts_sent')->default(0);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('warranty_serials');
        Schema::dropIfExists('installation_dispatches');
    }
};
