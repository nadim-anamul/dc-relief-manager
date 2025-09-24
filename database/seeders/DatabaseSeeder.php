<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create default users if they don't exist
        if (!User::where('email', 'test@example.com')->exists()) {
            User::factory()->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);
        }

        if (!User::where('email', 'admin@dcrelief.com')->exists()) {
            User::create([
                'name' => 'Admin User',
                'email' => 'admin@dcrelief.com',
                'password' => Hash::make('admin123'),
                'email_verified_at' => now(),
            ]);
        }

        // Seed administrative divisions
        $this->call([
            ZillaSeeder::class,
            UpazilaSeeder::class,
            UnionSeeder::class,
            WardSeeder::class,
        ]);

        // Seed system data
        $this->call([
            RolePermissionSeeder::class, // Add roles and permissions first
            UserSeeder::class, // Add users with proper roles
            EconomicYearSeeder::class,
            ReliefTypeSeeder::class,
            ReliefItemSeeder::class,
            OrganizationTypeSeeder::class,
            ProjectInventorySeeder::class,
            ReliefApplicationSeeder::class,
            ComprehensiveDataSeeder::class, // Add comprehensive test data
        ]);
    }
}
