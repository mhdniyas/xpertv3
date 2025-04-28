<?php

namespace Database\Seeders;

use App\Models\ActivityLog;
use App\Models\Product;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Database\Seeder;

class ActivityLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get users
        $superadmin = User::whereHas('role', function($query) {
            $query->where('name', 'superadmin');
        })->first();
        
        $admin = User::whereHas('role', function($query) {
            $query->where('name', 'admin');
        })->first();
        
        $manager = User::whereHas('role', function($query) {
            $query->where('name', 'manager');
        })->first();
        
        // Get shops
        $approvedShops = Shop::where('status', 'approved')->get();
        $pendingShop = Shop::where('status', 'pending')->first();
        
        // Log user login activities
        ActivityLog::create([
            'user_id' => $superadmin->id,
            'action' => 'login',
            'model_type' => User::class,
            'model_id' => $superadmin->id,
            'description' => 'User logged in',
            'created_at' => now()->subDays(5),
            'updated_at' => now()->subDays(5),
        ]);
        
        ActivityLog::create([
            'user_id' => $admin->id,
            'action' => 'login',
            'model_type' => User::class,
            'model_id' => $admin->id,
            'description' => 'User logged in',
            'created_at' => now()->subDays(3),
            'updated_at' => now()->subDays(3),
        ]);
        
        // Log shop creation and approval activities
        if ($approvedShops->count() > 0) {
            $shop = $approvedShops[0];
            
            ActivityLog::create([
                'user_id' => $shop->owner_id,
                'action' => 'create',
                'model_type' => Shop::class,
                'model_id' => $shop->id,
                'description' => 'Shop created',
                'new_values' => [
                    'name' => $shop->name,
                    'status' => 'pending',
                ],
                'created_at' => now()->subDays(10),
                'updated_at' => now()->subDays(10),
            ]);
            
            ActivityLog::create([
                'user_id' => $superadmin->id,
                'action' => 'approve',
                'model_type' => Shop::class,
                'model_id' => $shop->id,
                'description' => 'Shop approved',
                'old_values' => [
                    'status' => 'pending',
                    'is_active' => false,
                ],
                'new_values' => [
                    'status' => 'approved',
                    'is_active' => true,
                ],
                'created_at' => now()->subDays(9),
                'updated_at' => now()->subDays(9),
            ]);
        }
        
        // Log pending shop creation
        if ($pendingShop) {
            ActivityLog::create([
                'user_id' => $pendingShop->owner_id,
                'action' => 'create',
                'model_type' => Shop::class,
                'model_id' => $pendingShop->id,
                'description' => 'Shop awaiting approval',
                'new_values' => [
                    'name' => $pendingShop->name,
                    'status' => 'pending',
                ],
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2),
            ]);
        }
        
        // Log product activities
        $products = Product::where('status', 'approved')->take(2)->get();
        
        if ($products->count() > 0) {
            foreach ($products as $index => $product) {
                ActivityLog::create([
                    'user_id' => $product->shop->owner_id,
                    'action' => 'create',
                    'model_type' => Product::class,
                    'model_id' => $product->id,
                    'description' => 'Product created',
                    'new_values' => [
                        'name' => $product->name,
                        'price' => $product->price,
                        'status' => 'pending',
                    ],
                    'created_at' => now()->subDays(8 - $index),
                    'updated_at' => now()->subDays(8 - $index),
                ]);
                
                ActivityLog::create([
                    'user_id' => $admin->id,
                    'action' => 'approve',
                    'model_type' => Product::class,
                    'model_id' => $product->id,
                    'description' => 'Product approved',
                    'old_values' => [
                        'status' => 'pending',
                    ],
                    'new_values' => [
                        'status' => 'approved',
                    ],
                    'created_at' => now()->subDays(7 - $index),
                    'updated_at' => now()->subDays(7 - $index),
                ]);
            }
        }
        
        // Log staff assignment activity
        if ($approvedShops->count() > 0) {
            $shop = $approvedShops[0];
            $staffUsers = User::whereHas('role', function($query) {
                $query->where('name', 'staff');
            })->get();
            
            if ($staffUsers->count() > 0) {
                ActivityLog::create([
                    'user_id' => $manager->id,
                    'action' => 'assign',
                    'model_type' => Shop::class,
                    'model_id' => $shop->id,
                    'description' => 'Staff assigned to shop',
                    'new_values' => [
                        'staff_id' => $staffUsers[0]->id,
                        'staff_name' => $staffUsers[0]->name,
                    ],
                    'created_at' => now()->subDays(4),
                    'updated_at' => now()->subDays(4),
                ]);
            }
        }
    }
}
