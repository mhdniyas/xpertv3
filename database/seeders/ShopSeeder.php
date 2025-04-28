<?php

namespace Database\Seeders;

use App\Models\Shop;
use App\Models\User;
use Illuminate\Database\Seeder;

class ShopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get users with different roles to be shop owners
        $admin = User::whereHas('role', function($query) {
            $query->where('name', 'admin');
        })->first();
        
        $manager = User::whereHas('role', function($query) {
            $query->where('name', 'manager');
        })->first();
        
        $normalUser1 = User::whereHas('role', function($query) {
            $query->where('name', 'normal user');
        })->first();
        
        $normalUser2 = User::whereHas('role', function($query) {
            $query->where('name', 'normal user');
        })->skip(1)->first();
        
        // Create approved shops for admin and manager (higher privileges)
        Shop::create([
            'name' => 'Admin\'s Electronics',
            'description' => 'Premium electronics store run by an admin',
            'address' => '123 Admin Street, City, Country',
            'phone' => '+1234567890',
            'email' => 'admins.electronics@example.com',
            'owner_id' => $admin->id,
            'status' => 'approved',
            'is_active' => true,
        ]);
        
        Shop::create([
            'name' => 'Manager\'s Fashion',
            'description' => 'Trendy fashion store run by a manager',
            'address' => '456 Manager Avenue, City, Country',
            'phone' => '+9876543210',
            'email' => 'managers.fashion@example.com',
            'owner_id' => $manager->id,
            'status' => 'approved',
            'is_active' => true,
        ]);
        
        // Create a pending shop for a normal user (needs approval)
        Shop::create([
            'name' => 'User\'s Furniture',
            'description' => 'Quality furniture store waiting for approval',
            'address' => '789 User Boulevard, City, Country',
            'phone' => '+1122334455',
            'email' => 'users.furniture@example.com',
            'owner_id' => $normalUser1->id,
            'status' => 'pending',
            'is_active' => false,
        ]);
        
        // Create a rejected shop for a normal user
        Shop::create([
            'name' => 'Rejected Groceries',
            'description' => 'Grocery store that was rejected',
            'address' => '101 Rejected Road, City, Country',
            'phone' => '+5566778899',
            'email' => 'rejected.groceries@example.com',
            'owner_id' => $normalUser2->id,
            'status' => 'rejected',
            'rejection_reason' => 'Incomplete documentation provided',
            'is_active' => false,
        ]);
    }
}
