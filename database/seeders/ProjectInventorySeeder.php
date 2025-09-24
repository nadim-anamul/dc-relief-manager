<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\ReliefItem;
use App\Models\Inventory;
use App\Models\EconomicYear;
use App\Models\ReliefType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectInventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get existing data
        $economicYear = EconomicYear::first();
        $reliefTypes = ReliefType::all();
        $reliefItems = ReliefItem::all();

        if (!$economicYear || $reliefTypes->isEmpty() || $reliefItems->isEmpty()) {
            $this->command->warn('Required data not found. Please run other seeders first.');
            return;
        }

        // Create sample projects with inventory
        $projects = [
            [
                'name' => 'Flood Relief Program 2024',
                'relief_type_id' => $reliefTypes->where('name', 'Flood Relief')->first()?->id ?? $reliefTypes->first()->id,
                'allocated_amount' => 5000000.00,
                'available_amount' => 5000000.00,
                'remarks' => 'Comprehensive flood relief program for affected areas',
                'inventory_items' => [
                    ['item' => 'Rice', 'current_stock' => 100.5, 'unit_price' => 45000.00],
                    ['item' => 'Wheat', 'current_stock' => 50.2, 'unit_price' => 42000.00],
                    ['item' => 'Cash Relief', 'current_stock' => 2000000.00, 'unit_price' => 1.00],
                    ['item' => 'Blanket', 'current_stock' => 5000, 'unit_price' => 800.00],
                    ['item' => 'Tarpaulin', 'current_stock' => 2000, 'unit_price' => 1200.00],
                    ['item' => 'Medicine', 'current_stock' => 100, 'unit_price' => 5000.00],
                ]
            ],
            [
                'name' => 'Cyclone Preparedness Initiative',
                'relief_type_id' => $reliefTypes->where('name', 'Cyclone Relief')->first()?->id ?? $reliefTypes->first()->id,
                'allocated_amount' => 3000000.00,
                'available_amount' => 3000000.00,
                'remarks' => 'Pre-cyclone preparedness and post-cyclone relief',
                'inventory_items' => [
                    ['item' => 'Rice', 'current_stock' => 75.8, 'unit_price' => 46000.00],
                    ['item' => 'Lentils', 'current_stock' => 5000, 'unit_price' => 120.00],
                    ['item' => 'Cooking Oil', 'current_stock' => 2000, 'unit_price' => 180.00],
                    ['item' => 'Cash Relief', 'current_stock' => 1500000.00, 'unit_price' => 1.00],
                    ['item' => 'Tent', 'current_stock' => 500, 'unit_price' => 15000.00],
                    ['item' => 'First Aid Kit', 'current_stock' => 200, 'unit_price' => 2500.00],
                ]
            ],
            [
                'name' => 'Drought Relief Support',
                'relief_type_id' => $reliefTypes->where('name', 'Drought Relief')->first()?->id ?? $reliefTypes->first()->id,
                'allocated_amount' => 2500000.00,
                'available_amount' => 2500000.00,
                'remarks' => 'Support for drought-affected agricultural communities',
                'inventory_items' => [
                    ['item' => 'Rice', 'current_stock' => 60.3, 'unit_price' => 47000.00],
                    ['item' => 'Wheat', 'current_stock' => 40.1, 'unit_price' => 43000.00],
                    ['item' => 'Sugar', 'current_stock' => 3000, 'unit_price' => 85.00],
                    ['item' => 'Salt', 'current_stock' => 2000, 'unit_price' => 25.00],
                    ['item' => 'Cash Relief', 'current_stock' => 1000000.00, 'unit_price' => 1.00],
                    ['item' => 'Water Purification Tablets', 'current_stock' => 50, 'unit_price' => 2000.00],
                ]
            ],
            [
                'name' => 'Winter Relief Program',
                'relief_type_id' => $reliefTypes->where('name', 'Winter Relief')->first()?->id ?? $reliefTypes->first()->id,
                'allocated_amount' => 1800000.00,
                'available_amount' => 1800000.00,
                'remarks' => 'Winter clothing and heating support',
                'inventory_items' => [
                    ['item' => 'Blanket', 'current_stock' => 3000, 'unit_price' => 850.00],
                    ['item' => 'Clothing', 'current_stock' => 2000, 'unit_price' => 1200.00],
                    ['item' => 'Cash Relief', 'current_stock' => 800000.00, 'unit_price' => 1.00],
                    ['item' => 'Medicine', 'current_stock' => 75, 'unit_price' => 4800.00],
                ]
            ],
            [
                'name' => 'Emergency Response Fund',
                'relief_type_id' => $reliefTypes->where('name', 'Emergency Relief')->first()?->id ?? $reliefTypes->first()->id,
                'allocated_amount' => 4000000.00,
                'available_amount' => 4000000.00,
                'remarks' => 'General emergency response and quick relief',
                'inventory_items' => [
                    ['item' => 'Rice', 'current_stock' => 80.0, 'unit_price' => 45000.00],
                    ['item' => 'Cash Relief', 'current_stock' => 2500000.00, 'unit_price' => 1.00],
                    ['item' => 'Tarpaulin', 'current_stock' => 1500, 'unit_price' => 1250.00],
                    ['item' => 'First Aid Kit', 'current_stock' => 150, 'unit_price' => 2200.00],
                    ['item' => 'Hygiene Kit', 'current_stock' => 300, 'unit_price' => 1500.00],
                ]
            ]
        ];

        foreach ($projects as $projectData) {
            // Create project
            $project = Project::create([
                'name' => $projectData['name'],
                'economic_year_id' => $economicYear->id,
                'relief_type_id' => $projectData['relief_type_id'],
                'allocated_amount' => $projectData['allocated_amount'],
                'available_amount' => $projectData['available_amount'],
                'remarks' => $projectData['remarks'],
            ]);

            // Create inventory for each item
            foreach ($projectData['inventory_items'] as $inventoryData) {
                $reliefItem = $reliefItems->where('name', $inventoryData['item'])->first();
                
                if ($reliefItem) {
                    Inventory::create([
                        'relief_item_id' => $reliefItem->id,
                        'project_id' => $project->id,
                        'current_stock' => $inventoryData['current_stock'],
                        'reserved_stock' => rand(0, (int)($inventoryData['current_stock'] * 0.3)), // 0-30% reserved
                        'total_received' => $inventoryData['current_stock'] + rand(10, 50), // More than current stock
                        'total_distributed' => rand(5, 25), // Some already distributed
                        'unit_price' => $inventoryData['unit_price'],
                        'last_updated' => now()->subDays(rand(1, 30)),
                        'notes' => 'Initial inventory setup for ' . $project->name,
                    ]);
                }
            }
        }

        $this->command->info('Created ' . count($projects) . ' projects with inventory data.');
    }
}
