<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DealerBranch;

class DealerBranchSeeder extends Seeder
{
    public function run(): void
    {
        $branches = [
            [
                'branch_name' => 'Jakarta Selatan',
                'branch_code' => 'JKT-SLT',
                'address' => 'Jl. Fatmawati No. 123, Jakarta Selatan',
                'phone' => '021-7654321',
                'pic_name' => 'Budi Santoso',
                'pic_email' => 'budi.santoso@dealer.com',
                'is_active' => true,
            ],
            [
                'branch_name' => 'Jakarta Pusat',
                'branch_code' => 'JKT-PST',
                'address' => 'Jl. Thamrin No. 45, Jakarta Pusat',
                'phone' => '021-1234567',
                'pic_name' => 'Siti Nurhaliza',
                'pic_email' => 'siti.nurhaliza@dealer.com',
                'is_active' => true,
            ],
            [
                'branch_name' => 'Bandung',
                'branch_code' => 'BDG',
                'address' => 'Jl. Dago No. 78, Bandung',
                'phone' => '022-9876543',
                'pic_name' => 'Andi Wijaya',
                'pic_email' => 'andi.wijaya@dealer.com',
                'is_active' => true,
            ],
            [
                'branch_name' => 'Surabaya',
                'branch_code' => 'SBY',
                'address' => 'Jl. Basuki Rahmat No. 90, Surabaya',
                'phone' => '031-5551234',
                'pic_name' => 'Dewi Lestari',
                'pic_email' => 'dewi.lestari@dealer.com',
                'is_active' => true,
            ],
            [
                'branch_name' => 'Tangerang',
                'branch_code' => 'TNG',
                'address' => 'Jl. BSD Boulevard No. 12, Tangerang',
                'phone' => '021-5554321',
                'pic_name' => 'Rudi Hartono',
                'pic_email' => 'rudi.hartono@dealer.com',
                'is_active' => true,
            ],
            [
                'branch_name' => 'Bekasi',
                'branch_code' => 'BKS',
                'address' => 'Jl. Ahmad Yani No. 56, Bekasi',
                'phone' => '021-8887654',
                'pic_name' => 'Linda Sari',
                'pic_email' => 'linda.sari@dealer.com',
                'is_active' => true,
            ],
            [
                'branch_name' => 'Semarang',
                'branch_code' => 'SMG',
                'address' => 'Jl. Pandanaran No. 34, Semarang',
                'phone' => '024-7778899',
                'pic_name' => 'Hendra Gunawan',
                'pic_email' => 'hendra.gunawan@dealer.com',
                'is_active' => true,
            ],
            [
                'branch_name' => 'Yogyakarta',
                'branch_code' => 'YGY',
                'address' => 'Jl. Kaliurang KM 5, Yogyakarta',
                'phone' => '0274-123456',
                'pic_name' => 'Putri Rahayu',
                'pic_email' => 'putri.rahayu@dealer.com',
                'is_active' => true,
            ],
        ];

        foreach ($branches as $branch) {
            DealerBranch::create($branch);
        }

        $this->command->info('✅ Dealer branches seeded successfully!');
    }
}