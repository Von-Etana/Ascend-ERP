<?php

namespace Database\Seeders;

use App\Models\InventoryProduct;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Modules\AdminUser\Models\AdminRole;
use Modules\AdminUser\Models\User;
use Illuminate\Support\Str;

class RoleAndUserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Roles & Users
        $rolesData = [
            'Manager' => ['*'], // Full access to everything
            'Cashier' => ['pos.*', 'finance.view'],
            'Sales Rep' => ['crm.*', 'marketing.*', 'pos.create'],
            'HR' => ['hr.*'],
            'Retailer' => ['retailer.*'], // Retailer B2B ordering portal access
        ];

        $users = [];

        foreach ($rolesData as $roleName => $permissions) {
            $slug = Str::slug($roleName);

            $role = AdminRole::firstOrCreate(
                ['slug' => $slug],
                [
                    'name' => $roleName,
                    'description' => "{$roleName} role",
                    'permissions' => $permissions,
                ]
            );
            
            $role->update(['permissions' => $permissions]);

            $uniquePassword = str_replace(' ', '', $roleName) . '@Ascend2026!';
            $email = str_replace('-', '', $slug) . '@ascendsystems.ng';
            $username = str_replace('-', '', $slug);
            
            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => "Ascend {$roleName}",
                    'username' => $username,
                    'password' => Hash::make($uniquePassword),
                    'role_id' => $role->id,
                    'is_super_admin' => false,
                    'email_verified_at' => now(),
                ]
            );
            
            $user->update(['role_id' => $role->id, 'password' => Hash::make($uniquePassword)]);

            $users[] = [
                'role' => $roleName,
                'email' => $email,
                'password' => $uniquePassword,
            ];
        }

        // 2. Seed Renewable Energy, Solar, Inverter, Battery, Automation, Security & Network Products
        $solarProducts = [
            [
                'sku' => 'SLR-INV-55KW',
                'name' => 'Ascend Hybrid Solar Inverter 5.5kVA / 48V Pure Sine Wave',
                'category' => 'Inverters',
                'unit_price' => 580000.00,
                'wholesale_price' => 495000.00,
                'cost_price' => 410000.00,
                'stock_quantity' => 38,
                'reorder_level' => 8,
                'location' => 'Abuja Central Warehouse',
                'image_path' => 'https://images.unsplash.com/photo-1509391365360-2e959784a276?w=600&auto=format&fit=crop&q=80',
                'is_b2b_visible' => true,
                'specifications' => 'Dual MPPT 100A, Parallel Support up to 9 units, Wi-Fi Monitoring App',
            ],
            [
                'sku' => 'SLR-BAT-100AH',
                'name' => 'Ascend PowerWall 10.2kWh LiFePO4 Lithium Battery Storage',
                'category' => 'Batteries',
                'unit_price' => 1450000.00,
                'wholesale_price' => 1280000.00,
                'cost_price' => 1050000.00,
                'stock_quantity' => 24,
                'reorder_level' => 5,
                'location' => 'Lagos Warehouse',
                'image_path' => 'https://images.unsplash.com/photo-1558441719-670b357029bc?w=600&auto=format&fit=crop&q=80',
                'is_b2b_visible' => true,
                'specifications' => '51.2V 200Ah, 6000+ Deep Cycles @ 80% DOD, Smart BMS LCD Display',
            ],
            [
                'sku' => 'SLR-PNL-550W',
                'name' => 'Ascend Mono PERC 550W Half-Cut Solar Panel (Pallet Pack)',
                'category' => 'Solar Panels',
                'unit_price' => 115000.00,
                'wholesale_price' => 96000.00,
                'cost_price' => 78000.00,
                'stock_quantity' => 150,
                'reorder_level' => 30,
                'location' => 'Abuja Central Warehouse',
                'image_path' => 'https://images.unsplash.com/photo-1508514177221-188b1cf16e9d?w=600&auto=format&fit=crop&q=80',
                'is_b2b_visible' => true,
                'specifications' => 'Efficiency 21.3%, Anodized Aluminum Frame, IP68 Junction Box',
            ],
            [
                'sku' => 'AUT-SMT-CTRL',
                'name' => 'Ascend Smart Automation Energy Load Switch & Transfer Box',
                'category' => 'Automation',
                'unit_price' => 220000.00,
                'wholesale_price' => 185000.00,
                'cost_price' => 140000.00,
                'stock_quantity' => 45,
                'reorder_level' => 10,
                'location' => 'Abuja Central Warehouse',
                'image_path' => 'https://images.unsplash.com/photo-1558002038-1055907df827?w=600&auto=format&fit=crop&q=80',
                'is_b2b_visible' => true,
                'specifications' => 'Automatic Genset/Solar Auto Transfer Switch (ATS) with Tuya Smart IoT',
            ],
            [
                'sku' => 'SEC-4K-CAM-KIT',
                'name' => 'Ascend Solar Powered 4K Pan-Tilt PTZ Security Camera System',
                'category' => 'Security',
                'unit_price' => 185000.00,
                'wholesale_price' => 155000.00,
                'cost_price' => 118000.00,
                'stock_quantity' => 60,
                'reorder_level' => 12,
                'location' => 'Lagos Warehouse',
                'image_path' => 'https://images.unsplash.com/photo-1557862921-37829c790f19?w=600&auto=format&fit=crop&q=80',
                'is_b2b_visible' => true,
                'specifications' => 'Built-in 20W Solar Panel + 18650 Battery Pack, 4G SIM + Wi-Fi Dual Link',
            ],
            [
                'sku' => 'NET-FBR-24P',
                'name' => 'Ascend Gigabit 24-Port Managed PoE Switch & Fiber Network Hub',
                'category' => 'Network Infrastructure',
                'unit_price' => 310000.00,
                'wholesale_price' => 265000.00,
                'cost_price' => 210000.00,
                'stock_quantity' => 20,
                'reorder_level' => 5,
                'location' => 'Abuja Central Warehouse',
                'image_path' => 'https://images.unsplash.com/photo-1544197150-b99a580bb7a8?w=600&auto=format&fit=crop&q=80',
                'is_b2b_visible' => true,
                'specifications' => '400W PoE Budget, 2x 10G SFP+ Uplink Ports, Layer 2+ Enterprise Routing',
            ],
        ];

        foreach ($solarProducts as $prodData) {
            InventoryProduct::updateOrCreate(
                ['sku' => $prodData['sku']],
                $prodData
            );
        }

        echo "Roles, Users, and B2B Renewable Energy Product Catalog seeded successfully.\n\n";
    }
}
