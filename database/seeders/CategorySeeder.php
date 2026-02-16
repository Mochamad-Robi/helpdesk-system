<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'category_name' => 'Hardware',
                'description' => 'Masalah terkait perangkat keras (PC, Laptop, Printer, dll)',
                'icon' => '🖥️',
                'order_index' => 1,
                'is_active' => true,
            ],
            [
                'category_name' => 'Software',
                'description' => 'Masalah terkait aplikasi dan software',
                'icon' => '💻',
                'order_index' => 2,
                'is_active' => true,
            ],
            [
                'category_name' => 'Network',
                'description' => 'Masalah terkait jaringan dan konektivitas',
                'icon' => '🌐',
                'order_index' => 3,
                'is_active' => true,
            ],
            [
                'category_name' => 'DMS',
                'description' => 'Masalah terkait Dealer Management System',
                'icon' => '📊',
                'order_index' => 4,
                'is_active' => true,
            ],
            [
                'category_name' => 'Email & Account',
                'description' => 'Masalah terkait email dan akun akses',
                'icon' => '📧',
                'order_index' => 5,
                'is_active' => true,
            ],
            [
                'category_name' => 'Lain-lain',
                'description' => 'Masalah lain yang tidak termasuk kategori di atas',
                'icon' => '📝',
                'order_index' => 99,
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        $this->command->info('✅ Categories seeded successfully!');
    }
}