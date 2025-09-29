<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SeedBanglaData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seed:bangla {--fresh : Run fresh migration with seed}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed comprehensive Bangla data for Bogura district relief management system';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🇧🇩 Starting comprehensive Bangla data seeding for Bogura district...');
        $this->newLine();

        if ($this->option('fresh')) {
            $this->info('🔄 Running fresh migration with seed...');
            $this->call('migrate:fresh', ['--seed' => true]);
        } else {
            $this->info('🌱 Running database seeders...');
            $this->call('db:seed');
        }

        $this->newLine();
        $this->info('✅ Comprehensive Bangla data seeding completed successfully!');
        $this->newLine();
        
        $this->info('🔑 Login Credentials:');
        $this->table(
            ['Role', 'Email', 'Password'],
            [
                ['Super Admin', 'superadmin@bogura.gov.bd', 'password123'],
                ['District Admin', 'dc@bogura.gov.bd', 'password123'],
                ['Data Entry', 'dataentry1@bogura.gov.bd', 'password123'],
                ['Viewer', 'viewer1@bogura.gov.bd', 'password123'],
                ['NGO Representative', 'rafiqul@brac.org', 'password123'],
            ]
        );
        
        $this->newLine();
        $this->info('📊 Data Summary:');
        $this->line('• Complete Bogura administrative structure (1 Zilla, 12 Upazilas, 100+ Unions, 900+ Wards)');
        $this->line('• 25+ Bangla users with realistic names and proper roles');
        $this->line('• 20+ Bangla organization types');
        $this->line('• 20+ Bangla relief types');
        $this->line('• 50+ Bangla relief items');
        $this->line('• 15+ Bangla relief projects with realistic budgets');
        $this->line('• 10+ Bangla relief applications with mixed statuses');
        $this->line('• Complete role-based access control system');
        
        $this->newLine();
        $this->info('🎯 Ready for comprehensive testing of all features!');
        $this->newLine();
        $this->info('📖 For detailed information, check BANGLA_SEEDERS_README.md');
        
        return Command::SUCCESS;
    }
}