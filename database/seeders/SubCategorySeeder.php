<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\User;

class SubCategorySeeder extends Seeder
{
    public function run(): void
    {
        // Get helpdesk specialists
        $helpdeskNetwork = User::where('email', 'budi.helpdesk@helpdesk.com')->first();
        $helpdeskSoftware = User::where('email', 'dewi.helpdesk@helpdesk.com')->first();
        $helpdeskHardware = User::where('email', 'andi.helpdesk@helpdesk.com')->first();
        $helpdeskDMS = User::where('email', 'rini.helpdesk@helpdesk.com')->first();

        // Get categories
        $categoryHardware = Category::where('category_name', 'Hardware')->first();
        $categorySoftware = Category::where('category_name', 'Software')->first();
        $categoryNetwork = Category::where('category_name', 'Network')->first();
        $categoryDMS = Category::where('category_name', 'DMS')->first();
        $categoryEmail = Category::where('category_name', 'Email & Account')->first();
        $categoryOther = Category::where('category_name', 'Lain-lain')->first();

        // ===== HARDWARE SUB-CATEGORIES =====
        $hardwareSubCats = [
            [
                'sub_category_name' => 'PC/Laptop Rusak',
                'priority' => 'high',
                'sla_minutes' => 30,
                'default_specialist_id' => $helpdeskHardware->id,
                'description' => 'PC atau Laptop tidak bisa menyala, hang, atau error berat',
            ],
            [
                'sub_category_name' => 'Printer Bermasalah',
                'priority' => 'medium',
                'sla_minutes' => 120,
                'default_specialist_id' => $helpdeskHardware->id,
                'description' => 'Printer tidak bisa print, paper jam, atau kualitas print buruk',
            ],
            [
                'sub_category_name' => 'Mouse/Keyboard Rusak',
                'priority' => 'low',
                'sla_minutes' => 1440,
                'default_specialist_id' => $helpdeskHardware->id,
                'description' => 'Mouse atau keyboard tidak berfungsi',
            ],
            [
                'sub_category_name' => 'Monitor Bermasalah',
                'priority' => 'medium',
                'sla_minutes' => 120,
                'default_specialist_id' => $helpdeskHardware->id,
                'description' => 'Monitor blank, bergaris, atau tidak tampil',
            ],
            [
                'sub_category_name' => 'Scanner Error',
                'priority' => 'medium',
                'sla_minutes' => 240,
                'default_specialist_id' => $helpdeskHardware->id,
                'description' => 'Scanner tidak bisa scan atau terdeteksi',
            ],
        ];

        foreach ($hardwareSubCats as $subCat) {
            SubCategory::create(array_merge($subCat, ['category_id' => $categoryHardware->id]));
        }

        // ===== SOFTWARE SUB-CATEGORIES =====
        $softwareSubCats = [
            [
                'sub_category_name' => 'Aplikasi Error/Crash',
                'priority' => 'high',
                'sla_minutes' => 60,
                'default_specialist_id' => $helpdeskSoftware->id,
                'description' => 'Aplikasi tidak bisa dibuka atau sering crash',
            ],
            [
                'sub_category_name' => 'Email Issue',
                'priority' => 'medium',
                'sla_minutes' => 240,
                'default_specialist_id' => $helpdeskSoftware->id,
                'description' => 'Tidak bisa kirim/terima email',
            ],
            [
                'sub_category_name' => 'Install Aplikasi',
                'priority' => 'low',
                'sla_minutes' => 1440,
                'default_specialist_id' => $helpdeskSoftware->id,
                'description' => 'Request instalasi aplikasi baru',
            ],
            [
                'sub_category_name' => 'Microsoft Office Error',
                'priority' => 'medium',
                'sla_minutes' => 120,
                'default_specialist_id' => $helpdeskSoftware->id,
                'description' => 'Word, Excel, PowerPoint bermasalah',
            ],
            [
                'sub_category_name' => 'Antivirus Issue',
                'priority' => 'medium',
                'sla_minutes' => 180,
                'default_specialist_id' => $helpdeskSoftware->id,
                'description' => 'Antivirus tidak update atau terdeteksi virus',
            ],
        ];

        foreach ($softwareSubCats as $subCat) {
            SubCategory::create(array_merge($subCat, ['category_id' => $categorySoftware->id]));
        }

        // ===== NETWORK SUB-CATEGORIES =====
        $networkSubCats = [
            [
                'sub_category_name' => 'Internet Down',
                'priority' => 'high',
                'sla_minutes' => 30,
                'default_specialist_id' => $helpdeskNetwork->id,
                'description' => 'Internet mati total di cabang',
            ],
            [
                'sub_category_name' => 'Koneksi Lambat',
                'priority' => 'medium',
                'sla_minutes' => 120,
                'default_specialist_id' => $helpdeskNetwork->id,
                'description' => 'Internet sangat lambat',
            ],
            [
                'sub_category_name' => 'WiFi Issue',
                'priority' => 'medium',
                'sla_minutes' => 240,
                'default_specialist_id' => $helpdeskNetwork->id,
                'description' => 'WiFi tidak terdeteksi atau tidak bisa connect',
            ],
            [
                'sub_category_name' => 'VPN Error',
                'priority' => 'high',
                'sla_minutes' => 60,
                'default_specialist_id' => $helpdeskNetwork->id,
                'description' => 'Tidak bisa connect ke VPN',
            ],
            [
                'sub_category_name' => 'LAN/Kabel Network',
                'priority' => 'medium',
                'sla_minutes' => 180,
                'default_specialist_id' => $helpdeskNetwork->id,
                'description' => 'Kabel network bermasalah atau port tidak berfungsi',
            ],
        ];

        foreach ($networkSubCats as $subCat) {
            SubCategory::create(array_merge($subCat, ['category_id' => $categoryNetwork->id]));
        }

        // ===== DMS SUB-CATEGORIES =====
        $dmsSubCats = [
            [
                'sub_category_name' => 'DMS Down/Error',
                'priority' => 'high',
                'sla_minutes' => 30,
                'default_specialist_id' => $helpdeskDMS->id,
                'description' => 'DMS tidak bisa diakses atau error critical',
            ],
            [
                'sub_category_name' => 'DMS Lambat',
                'priority' => 'medium',
                'sla_minutes' => 120,
                'default_specialist_id' => $helpdeskDMS->id,
                'description' => 'DMS sangat lambat saat digunakan',
            ],
            [
                'sub_category_name' => 'Data DMS Tidak Sinkron',
                'priority' => 'high',
                'sla_minutes' => 60,
                'default_specialist_id' => $helpdeskDMS->id,
                'description' => 'Data di DMS tidak update atau hilang',
            ],
            [
                'sub_category_name' => 'Laporan DMS Error',
                'priority' => 'medium',
                'sla_minutes' => 240,
                'default_specialist_id' => $helpdeskDMS->id,
                'description' => 'Tidak bisa generate laporan atau print',
            ],
            [
                'sub_category_name' => 'Akses DMS Bermasalah',
                'priority' => 'medium',
                'sla_minutes' => 120,
                'default_specialist_id' => $helpdeskDMS->id,
                'description' => 'User tidak bisa login atau access denied',
            ],
        ];

        foreach ($dmsSubCats as $subCat) {
            SubCategory::create(array_merge($subCat, ['category_id' => $categoryDMS->id]));
        }

        // ===== EMAIL & ACCOUNT SUB-CATEGORIES =====
        $emailSubCats = [
            [
                'sub_category_name' => 'Lupa Password',
                'priority' => 'medium',
                'sla_minutes' => 120,
                'default_specialist_id' => $helpdeskSoftware->id,
                'description' => 'User lupa password akun',
            ],
            [
                'sub_category_name' => 'Reset Password',
                'priority' => 'medium',
                'sla_minutes' => 60,
                'default_specialist_id' => $helpdeskSoftware->id,
                'description' => 'Request reset password',
            ],
            [
                'sub_category_name' => 'Buat User Baru',
                'priority' => 'low',
                'sla_minutes' => 1440,
                'default_specialist_id' => $helpdeskSoftware->id,
                'description' => 'Request pembuatan user/akun baru',
            ],
            [
                'sub_category_name' => 'Email Tidak Bisa Login',
                'priority' => 'high',
                'sla_minutes' => 60,
                'default_specialist_id' => $helpdeskSoftware->id,
                'description' => 'Tidak bisa login ke email',
            ],
        ];

        foreach ($emailSubCats as $subCat) {
            SubCategory::create(array_merge($subCat, ['category_id' => $categoryEmail->id]));
        }

        // ===== LAIN-LAIN SUB-CATEGORIES =====
        $otherSubCats = [
            [
                'sub_category_name' => 'Request Bantuan Umum',
                'priority' => 'low',
                'sla_minutes' => 1440,
                'default_specialist_id' => $helpdeskSoftware->id,
                'description' => 'Bantuan IT umum lainnya',
            ],
            [
                'sub_category_name' => 'Konsultasi IT',
                'priority' => 'low',
                'sla_minutes' => 2880,
                'default_specialist_id' => $helpdeskSoftware->id,
                'description' => 'Konsultasi terkait IT',
            ],
        ];

        foreach ($otherSubCats as $subCat) {
            SubCategory::create(array_merge($subCat, ['category_id' => $categoryOther->id]));
        }

        $this->command->info('✅ Sub-categories seeded successfully!');
    }
}