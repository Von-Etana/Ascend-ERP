<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('solar_calculator_logs')) {
            Schema::create('solar_calculator_logs', function (Blueprint $table) {
                $table->id();
                $table->string('client_name')->nullable();
                $table->integer('total_wattage');
                $table->decimal('daily_kwh', 8, 2);
                $table->string('recommended_inverter');
                $table->string('recommended_battery');
                $table->string('recommended_panels');
                $table->decimal('estimated_total_ngn', 12, 2);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('solar_calculator_logs');
    }
};
