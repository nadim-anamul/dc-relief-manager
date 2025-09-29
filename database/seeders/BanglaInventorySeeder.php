<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\ReliefItem;
use App\Models\Inventory;
use App\Models\EconomicYear;
use Illuminate\Database\Seeder;

class BanglaInventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creating comprehensive Bangla inventory data...');

        // Get existing data
        $currentYear = EconomicYear::where('is_current', true)->first();
        $reliefItems = ReliefItem::all();
        $projects = Project::where('economic_year_id', $currentYear->id)->get();

        if (!$currentYear || $reliefItems->isEmpty() || $projects->isEmpty()) {
            $this->command->warn('Required data not found. Please run other seeders first.');
            return;
        }

        $createdCount = 0;

        foreach ($projects as $project) {
            // Get relief items based on project's relief type
            $relevantItems = $this->getRelevantItemsForProject($project, $reliefItems);
            
            foreach ($relevantItems as $item) {
                $inventory = Inventory::firstOrCreate(
                    [
                        'project_id' => $project->id,
                        'relief_item_id' => $item->id,
                    ],
                    [
                        'project_id' => $project->id,
                        'relief_item_id' => $item->id,
                        'current_stock' => $this->generateStockAmount($item, $project),
                        'reserved_stock' => $this->generateReservedStock($item, $project),
                        'total_received' => $this->generateTotalReceived($item, $project),
                        'total_distributed' => $this->generateTotalDistributed($item, $project),
                        'unit_price' => $this->generateUnitPrice($item),
                        'last_updated' => now()->subDays(rand(1, 30)),
                        'notes' => $this->generateInventoryNotes($item, $project),
                    ]
                );

                $createdCount++;
            }
        }

        $this->command->info("Created {$createdCount} comprehensive Bangla inventory records.");
        $this->command->info('Inventory data includes current stock, reserved stock, total received, total distributed, unit prices, and notes for all relief items in current year projects.');
    }

    private function getRelevantItemsForProject($project, $reliefItems)
    {
        // Get relief items based on project's relief type
        $reliefTypeName = $project->reliefType->name ?? '';
        
        return match($reliefTypeName) {
            'Cash Relief', 'নগদ ত্রাণ' => $reliefItems->where('type', 'monetary'),
            'Food Relief', 'খাদ্য ত্রাণ' => $reliefItems->where('type', 'food'),
            'Medical Relief', 'চিকিৎসা ত্রাণ' => $reliefItems->where('type', 'medical'),
            'Shelter Relief', 'আশ্রয় ত্রাণ' => $reliefItems->where('type', 'shelter'),
            'Educational Relief', 'শিক্ষা ত্রাণ' => $reliefItems->where('type', 'other'),
            'Winter Relief', 'শীতকালীন ত্রাণ' => $reliefItems->where('type', 'shelter'),
            default => $reliefItems->take(rand(3, 8))
        };
    }

    private function generateStockAmount($item, $project)
    {
        return match($item->type) {
            'food' => rand(50, 500), // kg
            'medical' => rand(10, 100), // packages/boxes
            'shelter' => rand(20, 200), // pieces
            'monetary' => rand(100000, 2000000), // BDT
            'other' => rand(25, 250), // pieces/sets
            default => rand(10, 100)
        };
    }

    private function generateReservedStock($item, $project)
    {
        $currentStock = $this->generateStockAmount($item, $project);
        return rand(0, (int)($currentStock * 0.3)); // 0-30% of current stock
    }

    private function generateTotalReceived($item, $project)
    {
        $currentStock = $this->generateStockAmount($item, $project);
        return $currentStock + rand(50, 200); // More than current stock
    }

    private function generateTotalDistributed($item, $project)
    {
        $totalReceived = $this->generateTotalReceived($item, $project);
        $currentStock = $this->generateStockAmount($item, $project);
        return $totalReceived - $currentStock; // Difference between received and current
    }

    private function generateUnitPrice($item)
    {
        return match($item->type) {
            'food' => rand(20, 150),
            'medical' => rand(500, 2000),
            'shelter' => rand(300, 5000),
            'monetary' => 1.00, // BDT per BDT
            'other' => rand(100, 1000),
            default => rand(50, 500)
        };
    }

    private function generateInventoryNotes($item, $project)
    {
        $projectName = $project->name;
        $itemName = $item->name_bn ?? $item->name;
        
        $notes = [
            "{$projectName} প্রকল্পের জন্য {$itemName} এর ইনভেন্টরি",
            "{$itemName} এর বর্তমান স্টক ও বিতরণ তথ্য",
            "{$projectName} এর জন্য {$itemName} সরবরাহের তথ্য",
            "{$itemName} এর স্টক ম্যানেজমেন্ট তথ্য",
        ];
        
        return $notes[array_rand($notes)];
    }
}
