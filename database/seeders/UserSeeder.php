<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get role IDs
        $superadminRoleId = Role::where('name', 'superadmin')->first()->id;
        $adminRoleId = Role::where('name', 'admin')->first()->id;
        $managerRoleId = Role::where('name', 'manager')->first()->id;
        $staffRoleId = Role::where('name', 'staff')->first()->id;
        $normalUserRoleId = Role::where('name', 'normal user')->first()->id;
        
        // Create a superadmin user (first user has no creator)
        $superadmin = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@example.com',
            'password' => Hash::make('password'),
            'role_id' => $superadminRoleId,
        ]);
        
        // Create an admin user (created by superadmin)
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role_id' => $adminRoleId,
            'created_by' => $superadmin->id,
        ]);
        
        // Create a manager user (created by admin)
        $manager = User::create([
            'name' => 'Manager User',
            'email' => 'manager@example.com',
            'password' => Hash::make('password'),
            'role_id' => $managerRoleId,
            'created_by' => $admin->id,
        ]);
        
        // Create two staff users (created by manager)
        $staff1 = User::create([
            'name' => 'Staff User 1',
            'email' => 'staff1@example.com',
            'password' => Hash::make('password'),
            'role_id' => $staffRoleId,
            'created_by' => $manager->id,
        ]);
        
        $staff2 = User::create([
            'name' => 'Staff User 2',
            'email' => 'staff2@example.com',
            'password' => Hash::make('password'),
            'role_id' => $staffRoleId,
            'created_by' => $manager->id,
        ]);
        
        // Create two normal users (created by manager)
        User::create([
            'name' => 'Normal User 1',
            'email' => 'user1@example.com',
            'password' => Hash::make('password'),
            'role_id' => $normalUserRoleId,
            'created_by' => $manager->id,
        ]);
        
        User::create([
            'name' => 'Normal User 2',
            'email' => 'user2@example.com',
            'password' => Hash::make('password'),
            'role_id' => $normalUserRoleId,
            'created_by' => $manager->id,
        ]);
    }
}
