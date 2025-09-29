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
        $this->command->info('Starting comprehensive Bangla data seeding...');

        // Create default users if they don't exist (for backward compatibility)
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

        // Seed comprehensive Bangla data in proper order
        $this->call([
            // 1. First create roles and permissions
            RolePermissionSeeder::class,
            
            // 2. Create complete Bogura administrative structure
            BoguraCompleteSeeder::class,
            
            // 3. Create comprehensive Bangla economic years
            BanglaEconomicYearsSeeder::class,
            
            // 4. Create comprehensive Bangla organization types
            BanglaOrganizationTypesSeeder::class,
            
            // 5. Create comprehensive Bangla relief types
            BanglaReliefTypesSeeder::class,
            
            // 6. Create comprehensive Bangla relief items
            BanglaReliefItemsSeeder::class,
            
            // 7. Create comprehensive Bangla users with roles
            BanglaUsersSeeder::class,
            
            // 8. Create comprehensive Bangla relief projects
            BanglaProjectsSeeder::class,
            
            // 9. Create comprehensive Bangla relief applications
            BanglaApplicationsSeeder::class,
            
            // 10. Create comprehensive Bangla inventory data
            BanglaInventorySeeder::class,
            
            // 11. Create comprehensive projects for all economic years
            ComprehensiveProjectsSeeder::class,
            
            // 12. Create comprehensive applications for all economic years
            ComprehensiveApplicationsSeeder::class,
        ]);

        $this->command->info('✅ Comprehensive Bangla data seeding completed successfully!');
        $this->command->info('');
        $this->command->info('🔑 Login Credentials:');
        $this->command->info('Super Admin: superadmin@bogura.gov.bd / password123');
        $this->command->info('District Admin: dc@bogura.gov.bd / password123');
        $this->command->info('Data Entry: dataentry1@bogura.gov.bd / password123');
        $this->command->info('Viewer: viewer1@bogura.gov.bd / password123');
        $this->command->info('');
        $this->command->info('📊 Data Summary:');
        $this->command->info('• Complete Bogura administrative structure (1 Zilla, 12 Upazilas, 100+ Unions, 900+ Wards)');
        $this->command->info('• 25+ Bangla users with realistic names and proper roles');
        $this->command->info('• 20+ Bangla organization types');
        $this->command->info('• 20+ Bangla relief types');
        $this->command->info('• 50+ Bangla relief items');
        $this->command->info('• 50+ relief projects across all economic years with realistic budgets');
        $this->command->info('• 200+ relief applications with diverse statuses and scenarios');
        $this->command->info('• Complete role-based access control system');
        $this->command->info('• Comprehensive data for dashboard analytics and testing');
        $this->command->info('');
        $this->command->info('🎯 Ready for comprehensive testing of all features!');
    }
}