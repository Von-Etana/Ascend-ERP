<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Modules\AdminUser\Models\AdminRole;
use Modules\AdminUser\Models\User;
use Illuminate\Support\Str;

class RoleAndUserSeeder extends Seeder
{
    public function run(): void
    {
        // Define the roles and their wildcard permissions
        $rolesData = [
            'Manager' => ['*'], // Full access to everything
            'Cashier' => ['pos.*', 'finance.view'],
            'Sales Rep' => ['crm.*', 'marketing.*', 'pos.create'],
            'HR' => ['hr.*'],
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
            
            // Ensure permissions are updated if role already existed
            $role->update(['permissions' => $permissions]);

            $uniquePassword = str_replace(' ', '', $roleName) . '@Ascend2026!';

            $email = str_replace('-', '', $slug) . '@ascendsystems.ng';
            $username = str_replace('-', '', $slug);
            
            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => "System {$roleName}",
                    'username' => $username,
                    'password' => Hash::make($uniquePassword),
                    'role_id' => $role->id,
                    'is_super_admin' => false,
                    'email_verified_at' => now(),
                ]
            );
            
            // Ensure role is assigned if user already existed
            $user->update(['role_id' => $role->id, 'password' => Hash::make($uniquePassword)]);

            $users[] = [
                'role' => $roleName,
                'email' => $email,
                'password' => $uniquePassword,
            ];
        }

        // Print output to console for verification
        echo "Roles and Users created successfully.\n\n";
        echo "Login Credentials:\n";
        foreach ($users as $u) {
            echo str_pad($u['role'], 12) . " | Email: " . str_pad($u['email'], 25) . " | Password: {$u['password']}\n";
        }
    }
}
