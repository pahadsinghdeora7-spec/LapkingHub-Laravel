<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductImageSeeder extends Seeder
{
    public function run(): void
    {
        Product::query()->limit(10)->get()->each(function (Product $product): void {
            if ($product->images()->exists()) {
                return;
            }

            foreach (range(1, 3) as $index) {
                $product->images()->create([
                    'image_path' => "demo/products/{$product->id}/image-{$index}.webp",
                    'image_name' => "Demo gallery image {$index}",
                    'alt_text' => "{$product->name} demo image {$index}",
                    'title' => "{$product->name} gallery {$index}",
                    'sort_order' => $index,
                    'is_primary' => $index === 1,
                    'image_size' => 0,
                    'image_width' => 800,
                    'image_height' => 600,
                    'created_by' => $product->created_by,
                    'updated_by' => $product->updated_by,
                ]);
            }
        });
    }
}
