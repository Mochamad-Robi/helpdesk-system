<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Run seeders in correct order (important!)
        $this->call([
            DealerBranchSeeder::class,
            UserSeeder::class,
            CategorySeeder::class,
            SubCategorySeeder::class,
            // TicketSeeder::class, // Uncomment jika mau seed sample tickets
        ]);

        $this->command->info('');
        $this->command->info('🎉 ============================================');
        $this->command->info('🎉 ALL SEEDERS COMPLETED SUCCESSFULLY!');
        $this->command->info('🎉 ============================================');
        $this->command->info('');
        $this->command->warn('📧 Login Credentials:');
        $this->command->warn('   Super Admin: superadmin@helpdesk.com');
        $this->command->warn('   Admin IT: admin@helpdesk.com');
        $this->command->warn('   Helpdesk Network: budi.helpdesk@helpdesk.com');
        $this->command->warn('   Helpdesk Software: dewi.helpdesk@helpdesk.com');
        $this->command->warn('   Helpdesk Hardware: andi.helpdesk@helpdesk.com');
        $this->command->warn('   Helpdesk DMS: rini.helpdesk@helpdesk.com');
        $this->command->warn('   Dealer: jkt-slt.user1@dealer.com (atau lainnya)');
        $this->command->warn('   Password: password');
        $this->command->info('');
    }
}