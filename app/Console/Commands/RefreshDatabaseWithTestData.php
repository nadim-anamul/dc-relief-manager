<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class RefreshDatabaseWithTestData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:refresh-test-data {--force : Force the operation without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh the database with comprehensive test data for development and testing';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!$this->option('force')) {
            if (!$this->confirm('This will completely refresh your database. Are you sure you want to continue?')) {
                $this->info('Operation cancelled.');
                return;
            }
        }

        $this->info('🔄 Refreshing database with comprehensive test data...');
        
        // Step 1: Fresh migration
        $this->info('📊 Running fresh migrations...');
        Artisan::call('migrate:fresh', [], $this->output);
        
        // Step 2: Seed the database
        $this->info('🌱 Seeding database with comprehensive test data...');
        Artisan::call('db:seed', [], $this->output);
        
        // Step 3: Show summary
        $this->showDataSummary();
        
        $this->info('✅ Database refreshed successfully with comprehensive test data!');
        $this->info('🎯 You can now test all dashboard features with realistic data.');
    }

    /**
     * Show a summary of the created data
     */
    private function showDataSummary()
    {
        $this->info('📈 Data Summary:');
        
        $counts = [
            'Economic Years' => DB::table('economic_years')->count(),
            'Relief Types' => DB::table('relief_types')->count(),
            'Relief Items' => DB::table('relief_items')->count(),
            'Organization Types' => DB::table('organization_types')->count(),
            'Projects' => DB::table('projects')->count(),
            'Relief Applications' => DB::table('relief_applications')->count(),
            'Relief Application Items' => DB::table('relief_application_items')->count(),
            'Zillas' => DB::table('zillas')->count(),
            'Upazilas' => DB::table('upazilas')->count(),
            'Unions' => DB::table('unions')->count(),
            'Wards' => DB::table('wards')->count(),
            'Users' => DB::table('users')->count(),
        ];

        foreach ($counts as $type => $count) {
            $this->line("   • {$type}: {$count}");
        }

        // Show application status breakdown
        $this->info('📋 Relief Application Status:');
        $statusCounts = DB::table('relief_applications')
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();

        foreach ($statusCounts as $status) {
            $this->line("   • " . ucfirst($status->status) . ": {$status->count}");
        }

        // Show relief type distribution
        $this->info('🎯 Relief Type Distribution:');
        $reliefTypeCounts = DB::table('relief_applications')
            ->join('relief_types', 'relief_applications.relief_type_id', '=', 'relief_types.id')
            ->select('relief_types.name', DB::raw('count(*) as count'))
            ->groupBy('relief_types.id', 'relief_types.name')
            ->get();

        foreach ($reliefTypeCounts as $type) {
            $this->line("   • {$type->name}: {$type->count} applications");
        }

        $this->info('🚀 Dashboard is now ready with realistic data for testing!');
    }
}