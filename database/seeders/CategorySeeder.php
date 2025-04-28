<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create parent categories
        $electronics = Category::create([
            'name' => 'Electronics',
            'description' => 'Electronic devices and accessories',
            'is_active' => true,
        ]);

        $clothing = Category::create([
            'name' => 'Clothing',
            'description' => 'Clothing and accessories',
            'is_active' => true,
        ]);

        $furniture = Category::create([
            'name' => 'Furniture',
            'description' => 'Home and office furniture',
            'is_active' => true,
        ]);

        $groceries = Category::create([
            'name' => 'Groceries',
            'description' => 'Food and household items',
            'is_active' => true,
        ]);

        // Create subcategories for Electronics
        Category::create([
            'name' => 'Smartphones',
            'description' => 'Mobile phones and accessories',
            'parent_id' => $electronics->id,
            'is_active' => true,
        ]);

        Category::create([
            'name' => 'Laptops',
            'description' => 'Laptops and computer accessories',
            'parent_id' => $electronics->id,
            'is_active' => true,
        ]);

        Category::create([
            'name' => 'Audio',
            'description' => 'Headphones, speakers, and audio equipment',
            'parent_id' => $electronics->id,
            'is_active' => true,
        ]);

        // Create subcategories for Clothing
        Category::create([
            'name' => 'Men',
            'description' => 'Men\'s clothing and accessories',
            'parent_id' => $clothing->id,
            'is_active' => true,
        ]);

        Category::create([
            'name' => 'Women',
            'description' => 'Women\'s clothing and accessories',
            'parent_id' => $clothing->id,
            'is_active' => true,
        ]);

        Category::create([
            'name' => 'Kids',
            'description' => 'Children\'s clothing and accessories',
            'parent_id' => $clothing->id,
            'is_active' => true,
        ]);

        // Create subcategories for Furniture
        Category::create([
            'name' => 'Living Room',
            'description' => 'Sofas, coffee tables, and living room furniture',
            'parent_id' => $furniture->id,
            'is_active' => true,
        ]);

        Category::create([
            'name' => 'Office',
            'description' => 'Office desks, chairs, and office furniture',
            'parent_id' => $furniture->id,
            'is_active' => true,
        ]);

        // Create subcategories for Groceries
        Category::create([
            'name' => 'Fresh Food',
            'description' => 'Fresh produce, meats, and dairy',
            'parent_id' => $groceries->id,
            'is_active' => true,
        ]);

        Category::create([
            'name' => 'Household',
            'description' => 'Cleaning and household products',
            'parent_id' => $groceries->id,
            'is_active' => true,
        ]);
    }
}
