<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class FeaturedProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Mark some random existing products as featured
        $productCount = Product::count();

        if ($productCount > 0) {
            // Select 25% of products (or at least 5) to be featured
            $featuredCount = max(5, ceil($productCount * 0.25));

            // Get random product IDs
            $randomProductIds = Product::inRandomOrder()
                                    ->limit($featuredCount)
                                    ->pluck('id')
                                    ->toArray();

            // Update these products to be featured
            Product::whereIn('id', $randomProductIds)
                ->update(['is_featured' => true]);

            $this->command->info($featuredCount . ' products marked as featured.');
        } else {
            $this->command->warn('No products found to mark as featured.');
        }
    }
}
