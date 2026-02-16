<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\DealerBranch;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // ===== SUPER ADMIN =====
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@helpdesk.com',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
            'dealer_branch_id' => null,
            'is_active' => true,
        ]);

        // ===== ADMIN IT =====
        User::create([
            'name' => 'Admin IT',
            'email' => 'admin@helpdesk.com',
            'password' => Hash::make('password'),
            'role' => 'admin_it',
            'dealer_branch_id' => null,
            'is_active' => true,
        ]);

        // ===== HELPDESK TEAM =====
        $helpdesks = [
            [
                'name' => 'Budi - Helpdesk Network',
                'email' => 'budi.helpdesk@helpdesk.com',
                'specialty' => 'network',
            ],
            [
                'name' => 'Dewi - Helpdesk Software',
                'email' => 'dewi.helpdesk@helpdesk.com',
                'specialty' => 'software',
            ],
            [
                'name' => 'Andi - Helpdesk Hardware',
                'email' => 'andi.helpdesk@helpdesk.com',
                'specialty' => 'hardware',
            ],
            [
                'name' => 'Rini - Helpdesk DMS',
                'email' => 'rini.helpdesk@helpdesk.com',
                'specialty' => 'dms',
            ],
        ];

        foreach ($helpdesks as $helpdesk) {
            User::create([
                'name' => $helpdesk['name'],
                'email' => $helpdesk['email'],
                'password' => Hash::make('password'),
                'role' => 'helpdesk',
                'dealer_branch_id' => null,
                'is_active' => true,
            ]);
        }

        // ===== DEALER USERS =====
        // Get all branches
        $branches = DealerBranch::all();

        foreach ($branches as $index => $branch) {
            // Create 2 dealer users per branch
            User::create([
                'name' => "Dealer User 1 - {$branch->branch_code}",
                'email' => strtolower($branch->branch_code) . '.user1@dealer.com',
                'password' => Hash::make('password'),
                'role' => 'dealer',
                'dealer_branch_id' => $branch->id,
                'is_active' => true,
            ]);

            User::create([
                'name' => "Dealer User 2 - {$branch->branch_code}",
                'email' => strtolower($branch->branch_code) . '.user2@dealer.com',
                'password' => Hash::make('password'),
                'role' => 'dealer',
                'dealer_branch_id' => $branch->id,
                'is_active' => true,
            ]);
        }

        $this->command->info('✅ Users seeded successfully!');
        $this->command->warn('📧 Default password for all users: password');
    }
}