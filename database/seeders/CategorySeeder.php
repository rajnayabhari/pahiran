<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Men', 'slug' => 'men', 'is_active' => true],
            ['name' => 'Women', 'slug' => 'women', 'is_active' => true],
            ['name' => 'Kids', 'slug' => 'kids', 'is_active' => true],
            ['name' => 'Accessories', 'slug' => 'accessories', 'is_active' => true],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }

        $this->command->info('Clothing categories seeded successfully!');
    }
}
