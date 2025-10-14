<?php

namespace App\Console\Commands;

use App\Models\EconomicYear;
use Illuminate\Console\Command;

class UpdateCurrentEconomicYearCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'economic-year:update-current 
                           {--force : Force update even if already current}
                           {--dry-run : Show what would be updated without making changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the current economic year based on today\'s date';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking current economic year...');
        
        $today = now()->format('Y-m-d');
        $this->line("Today's date: {$today}");
        
        // Get current economic year before update
        $currentBefore = EconomicYear::where('is_current', true)->first();
        if ($currentBefore) {
            $this->line("Current economic year before update: {$currentBefore->name} ({$currentBefore->start_date} to {$currentBefore->end_date})");
        } else {
            $this->warn('No current economic year found before update');
        }
        
        if ($this->option('dry-run')) {
            $this->info('DRY RUN MODE - No changes will be made');
            
            // Find what would be the current year
            $wouldBeCurrent = EconomicYear::where('is_active', true)
                ->where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->orderBy('start_date', 'desc')
                ->first();
                
            if ($wouldBeCurrent) {
                $this->info("Would set current economic year to: {$wouldBeCurrent->name} ({$wouldBeCurrent->start_date} to {$wouldBeCurrent->end_date})");
            } else {
                $this->warn('No suitable economic year found for today\'s date');
            }
            
            return 0;
        }
        
        try {
            $updatedYear = EconomicYear::updateCurrentYear();
            
            if ($updatedYear) {
                $this->info("✅ Successfully updated current economic year to: {$updatedYear->name}");
                $this->line("   Period: {$updatedYear->start_date} to {$updatedYear->end_date}");
                
                if ($currentBefore && $currentBefore->id !== $updatedYear->id) {
                    $this->info("   Changed from: {$currentBefore->name}");
                } else {
                    $this->line("   No change needed - already current");
                }
            } else {
                $this->error('❌ Failed to update current economic year - no suitable year found');
                return 1;
            }
            
        } catch (\Exception $e) {
            $this->error("❌ Error updating current economic year: {$e->getMessage()}");
            return 1;
        }
        
        return 0;
    }
}
