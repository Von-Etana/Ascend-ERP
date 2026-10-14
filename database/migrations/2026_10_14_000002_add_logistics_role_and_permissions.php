<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('admin_roles')) {
            $existing = DB::table('admin_roles')->where('slug', 'logistics')->first();

            $permissions = [
                'portal.inventory.*',
                'portal.waybills.*',
                'portal.pos.*',
                'logistics.view',
                'logistics.dispatch',
                'waybill.generate',
                'waybill.verify',
                'inventory.view',
                'inventory.ship',
            ];

            if ($existing) {
                DB::table('admin_roles')->where('id', $existing->id)->update([
                    'name' => 'Logistics Officer',
                    'description' => 'Oversees product dispatch, delivery waybills, gate pass clearance, consignment tracking, and warehouse shipping.',
                    'permissions' => json_encode($permissions),
                    'updated_at' => now(),
                ]);
            } else {
                DB::table('admin_roles')->insert([
                    'name' => 'Logistics Officer',
                    'slug' => 'logistics',
                    'description' => 'Oversees product dispatch, delivery waybills, gate pass clearance, consignment tracking, and warehouse shipping.',
                    'permissions' => json_encode($permissions),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('admin_roles')) {
            DB::table('admin_roles')->where('slug', 'logistics')->delete();
        }
    }
};
