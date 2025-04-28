<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call seeders in the correct order to respect dependencies
        $this->call([
            RoleSeeder::class,        // First seed roles
            UserSeeder::class,        // Then seed users (which depend on roles)
            CategorySeeder::class,    // Seed categories for products
            ShopSeeder::class,        // Seed shops (which depend on users)
            ShopStaffSeeder::class,   // Assign staff to shops
            ProductSeeder::class,     // Seed products (which depend on shops and categories)
            ActivityLogSeeder::class, // Finally seed activity logs (which depend on all the above)
        ]);
    }
}
