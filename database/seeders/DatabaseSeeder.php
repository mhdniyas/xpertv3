<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed roles if they don't exist
        $this->seedRoles();

        // Check if test user exists
        if (!DB::table('users')->where('email', 'test@example.com')->exists()) {
            // Create test user directly without using Factory
            DB::table('users')->insert([
                'name' => 'Test User',
                'email' => 'test@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'remember_token' => Str::random(10),
                'role_id' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Check if superadmin exists
        if (!DB::table('users')->where('email', 'superadmin@example.com')->exists()) {
            // Create a superadmin user directly without using Factory
            DB::table('users')->insert([
                'name' => 'Super Admin',
                'email' => 'superadmin@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'remember_token' => Str::random(10),
                'role_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Seed roles if they don't exist.
     */
    private function seedRoles(): void
    {
        $roles = [
            ['id' => 1, 'name' => 'superadmin'],
            ['id' => 2, 'name' => 'admin'],
            ['id' => 3, 'name' => 'manager'],
            ['id' => 4, 'name' => 'normal user'],
        ];

        foreach ($roles as $role) {
            if (!DB::table('roles')->where('id', $role['id'])->exists()) {
                DB::table('roles')->insert([
                    'id' => $role['id'],
                    'name' => $role['name'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
