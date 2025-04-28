<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Shop;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get shops
        $approvedShops = Shop::where('status', 'approved')->get();
        
        // Skip if no shops are available
        if ($approvedShops->count() === 0) {
            return;
        }
        
        // Get categories
        $electronicsCategory = Category::where('name', 'Electronics')->first();
        $smartphonesCategory = Category::where('name', 'Smartphones')->first();
        $laptopsCategory = Category::where('name', 'Laptops')->first();
        
        $clothingCategory = Category::where('name', 'Clothing')->first();
        $menCategory = Category::where('name', 'Men')->first();
        $womenCategory = Category::where('name', 'Women')->first();
        
        // Create products for the first shop (Electronics)
        $electronicsShop = $approvedShops[0];
        
        // Smartphone products
        Product::create([
            'name' => 'Premium Smartphone X1',
            'description' => 'High-end smartphone with advanced features',
            'price' => 999.99,
            'category_id' => $smartphonesCategory->id,
            'shop_id' => $electronicsShop->id,
            'is_global' => true,
            'is_active' => true,
            'status' => 'approved',
        ]);
        
        Product::create([
            'name' => 'Budget Smartphone Y2',
            'description' => 'Affordable smartphone with great value',
            'price' => 299.99,
            'category_id' => $smartphonesCategory->id,
            'shop_id' => $electronicsShop->id,
            'is_global' => false,
            'is_active' => true,
            'status' => 'approved',
        ]);
        
        // Laptop products
        Product::create([
            'name' => 'UltraBook Pro',
            'description' => 'Thin and powerful laptop for professionals',
            'price' => 1299.99,
            'category_id' => $laptopsCategory->id,
            'shop_id' => $electronicsShop->id,
            'is_global' => true,
            'is_active' => true,
            'status' => 'approved',
        ]);
        
        Product::create([
            'name' => 'Gaming Laptop Extreme',
            'description' => 'High-performance gaming laptop',
            'price' => 1599.99,
            'category_id' => $laptopsCategory->id,
            'shop_id' => $electronicsShop->id,
            'is_global' => false,
            'global_suggestion' => true,
            'is_active' => true,
            'status' => 'pending',
        ]);
        
        // Create products for the second shop (Fashion)
        if ($approvedShops->count() > 1) {
            $fashionShop = $approvedShops[1];
            
            // Men's clothing
            Product::create([
                'name' => 'Classic Men\'s Suit',
                'description' => 'Elegant suit for formal occasions',
                'price' => 299.99,
                'category_id' => $menCategory->id,
                'shop_id' => $fashionShop->id,
                'is_global' => false,
                'is_active' => true,
                'status' => 'approved',
            ]);
            
            Product::create([
                'name' => 'Casual Men\'s Jeans',
                'description' => 'Comfortable jeans for everyday wear',
                'price' => 79.99,
                'category_id' => $menCategory->id,
                'shop_id' => $fashionShop->id,
                'is_global' => true,
                'is_active' => true,
                'status' => 'approved',
            ]);
            
            // Women's clothing
            Product::create([
                'name' => 'Designer Dress',
                'description' => 'Elegant dress for special occasions',
                'price' => 199.99,
                'category_id' => $womenCategory->id,
                'shop_id' => $fashionShop->id,
                'is_global' => false,
                'is_active' => true,
                'status' => 'approved',
            ]);
            
            Product::create([
                'name' => 'Summer Collection',
                'description' => 'Light and comfortable summer wear',
                'price' => 129.99,
                'category_id' => $womenCategory->id,
                'shop_id' => $fashionShop->id,
                'is_global' => false,
                'global_suggestion' => true,
                'is_active' => true,
                'status' => 'rejected',
                'rejection_reason' => 'Product images not clear enough',
            ]);
        }
    }
}
