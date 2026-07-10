<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Seed laptop parts categories used by the catalog.
     */
    public function run(): void
    {
        $categories = [
            'Keyboard',
            'Battery',
            'LCD',
            'Touch Screen',
            'DC Jack',
            'RAM',
            'SSD',
            'NVMe SSD',
            'HDD',
            'Processor',
            'Fan',
            'Hinge',
            'Speaker',
            'Palmrest',
            'Bottom Cover',
            'Top Cover',
            'Motherboard',
            'Webcam',
            'Cable',
            'Adaptor',
            'Charger',
        ];

        foreach ($categories as $sortOrder => $name) {
            Category::query()->updateOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'name' => $name,
                    'description' => "Laptop {$name} replacement parts and accessories.",
                    'is_active' => true,
                    'sort_order' => $sortOrder + 1,
                ]
            );
        }
    }
}
