<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductRatingAndStockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $productCount = Product::count();

        if ($productCount > 0) {
            // Update all products with random rating and stock values
            $products = Product::all();

            foreach ($products as $product) {
                // Generate a random rating between 1.0 and 5.0
                $rating = round(mt_rand(10, 50) / 10, 1);

                // Generate a random stock between 0 and 50
                // ~10% of products will be out of stock (0)
                // ~20% of products will have low stock (1-5)
                // ~70% of products will have normal stock (6-50)
                $stockDistribution = mt_rand(1, 100);
                if ($stockDistribution <= 10) {
                    $stock = 0; // Out of stock
                } elseif ($stockDistribution <= 30) {
                    $stock = mt_rand(1, 5); // Low stock
                } else {
                    $stock = mt_rand(6, 50); // Normal stock
                }

                // Update the product
                $product->update([
                    'rating' => $rating,
                    'stock' => $stock
                ]);
            }

            $this->command->info("Updated rating and stock values for {$productCount} products.");
        } else {
            $this->command->warn('No products found to update.');
        }
    }
}
