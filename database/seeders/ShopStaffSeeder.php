<?php

namespace Database\Seeders;

use App\Models\Shop;
use App\Models\User;
use Illuminate\Database\Seeder;

class ShopStaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get staff users
        $staffUsers = User::whereHas('role', function($query) {
            $query->where('name', 'staff');
        })->get();
        
        // Get approved shops
        $approvedShops = Shop::where('status', 'approved')->get();
        
        // Assign first staff user to the first shop
        if ($staffUsers->count() > 0 && $approvedShops->count() > 0) {
            $approvedShops[0]->staff()->attach($staffUsers[0]->id, [
                'role' => 'staff',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
        // Assign second staff user to both shops (if they exist)
        if ($staffUsers->count() > 1) {
            foreach ($approvedShops as $shop) {
                $shop->staff()->attach($staffUsers[1]->id, [
                    'role' => 'staff',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
