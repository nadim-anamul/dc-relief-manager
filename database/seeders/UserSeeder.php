<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create users with different roles
        $users = [
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@dcrelief.com',
                'password' => Hash::make('password123'),
                'role' => 'super-admin',
            ],
            [
                'name' => 'District Admin',
                'email' => 'districtadmin@dcrelief.com',
                'password' => Hash::make('password123'),
                'role' => 'district-admin',
            ],
            [
                'name' => 'Data Entry User',
                'email' => 'dataentry@dcrelief.com',
                'password' => Hash::make('password123'),
                'role' => 'data-entry',
            ],
            [
                'name' => 'Viewer User',
                'email' => 'viewer@dcrelief.com',
                'password' => Hash::make('password123'),
                'role' => 'viewer',
            ],
        ];

        foreach ($users as $userData) {
            $role = $userData['role'];
            unset($userData['role']);
            
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                $userData
            );
            
            // Assign role if not already assigned
            $roleModel = Role::where('name', $role)->first();
            if ($roleModel && !$user->hasRole($roleModel->name)) {
                $user->assignRole($roleModel->name);
            }
        }
    }
}