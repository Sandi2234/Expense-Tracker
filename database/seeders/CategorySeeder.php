<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Makanan & Minuman', 'color' => '#EF4444'], // Merah
            ['name' => 'Transportasi', 'color' => '#3B82F6'],     // Biru
            ['name' => 'Kopi & Hiburan', 'color' => '#F59E0B'],   // Kuning
            ['name' => 'Gaji & Bonus', 'color' => '#10B981'],     // Hijau
            ['name' => 'Belanja', 'color' => '#8B5CF6'],          // Ungu
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(
                ['slug' => Str::slug($cat['name'])],
                ['name' => $cat['name'], 'color' => $cat['color'], 'user_id' => null]
            );
        }
    }
}