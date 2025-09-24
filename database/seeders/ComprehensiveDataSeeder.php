<?php

namespace Database\Seeders;

use App\Models\EconomicYear;
use App\Models\OrganizationType;
use App\Models\Project;
use App\Models\ReliefApplication;
use App\Models\ReliefApplicationItem;
use App\Models\ReliefItem;
use App\Models\ReliefType;
use App\Models\User;
use App\Models\Zilla;
use App\Models\Upazila;
use App\Models\Union;
use App\Models\Ward;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class ComprehensiveDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creating comprehensive test data...');

        // Create Economic Years
        $currentYear = EconomicYear::create([
            'name' => '2024-2025',
            'start_date' => Carbon::parse('2024-07-01'),
            'end_date' => Carbon::parse('2025-06-30'),
            'is_active' => true,
        ]);

        $previousYear = EconomicYear::create([
            'name' => '2023-2024',
            'start_date' => Carbon::parse('2023-07-01'),
            'end_date' => Carbon::parse('2024-06-30'),
            'is_active' => false,
        ]);

        // Create Relief Types with proper units
        $reliefTypes = [
            [
                'name' => 'Cash Relief',
                'name_bn' => 'নগদ ত্রাণ',
                'unit' => 'Taka',
                'unit_bn' => 'টাকা',
                'color_code' => '#10B981',
                'description' => 'Direct cash assistance to beneficiaries',
                'is_active' => true,
            ],
            [
                'name' => 'Food Relief',
                'name_bn' => 'খাদ্য ত্রাণ',
                'unit' => 'KG',
                'unit_bn' => 'কেজি',
                'color_code' => '#F59E0B',
                'description' => 'Food items and essential supplies',
                'is_active' => true,
            ],
            [
                'name' => 'Medical Relief',
                'name_bn' => 'চিকিৎসা ত্রাণ',
                'unit' => 'Package',
                'unit_bn' => 'প্যাকেজ',
                'color_code' => '#EF4444',
                'description' => 'Medical supplies and health assistance',
                'is_active' => true,
            ],
            [
                'name' => 'Shelter Relief',
                'name_bn' => 'আশ্রয় ত্রাণ',
                'unit' => 'Piece',
                'unit_bn' => 'পিস',
                'color_code' => '#3B82F6',
                'description' => 'Housing and shelter materials',
                'is_active' => true,
            ],
            [
                'name' => 'Educational Relief',
                'name_bn' => 'শিক্ষা ত্রাণ',
                'unit' => 'Set',
                'unit_bn' => 'সেট',
                'color_code' => '#8B5CF6',
                'description' => 'Educational materials and supplies',
                'is_active' => true,
            ],
        ];

        $createdReliefTypes = [];
        foreach ($reliefTypes as $reliefType) {
            $createdReliefTypes[] = ReliefType::create($reliefType);
        }

        // Create Relief Items
        $reliefItems = [
            // Food Items
            [
                'name' => 'Rice',
                'name_bn' => 'চাল',
                'type' => 'food',
                'unit' => 'kg',
                'description' => 'High quality rice for food relief',
                'is_active' => true,
            ],
            [
                'name' => 'Lentils',
                'name_bn' => 'ডাল',
                'type' => 'food',
                'unit' => 'kg',
                'description' => 'Nutritious lentils for food relief',
                'is_active' => true,
            ],
            [
                'name' => 'Cooking Oil',
                'name_bn' => 'রান্নার তেল',
                'type' => 'food',
                'unit' => 'liter',
                'description' => 'Cooking oil for food preparation',
                'is_active' => true,
            ],
            [
                'name' => 'Salt',
                'name_bn' => 'লবণ',
                'type' => 'food',
                'unit' => 'kg',
                'description' => 'Iodized salt for food relief',
                'is_active' => true,
            ],
            [
                'name' => 'Sugar',
                'name_bn' => 'চিনি',
                'type' => 'food',
                'unit' => 'kg',
                'description' => 'Refined sugar for food relief',
                'is_active' => true,
            ],
            // Medical Items
            [
                'name' => 'First Aid Kit',
                'name_bn' => 'প্রাথমিক চিকিৎসা কিট',
                'type' => 'medical',
                'unit' => 'package',
                'description' => 'Essential first aid supplies',
                'is_active' => true,
            ],
            [
                'name' => 'Medicine Kit',
                'name_bn' => 'ঔষধ কিট',
                'type' => 'medical',
                'unit' => 'package',
                'description' => 'Basic medicine supplies',
                'is_active' => true,
            ],
            // Shelter Items
            [
                'name' => 'Tent',
                'name_bn' => 'তাঁবু',
                'type' => 'shelter',
                'unit' => 'piece',
                'description' => 'Emergency shelter tent',
                'is_active' => true,
            ],
            [
                'name' => 'Blanket',
                'name_bn' => 'কম্বল',
                'type' => 'shelter',
                'unit' => 'piece',
                'description' => 'Warm blanket for shelter relief',
                'is_active' => true,
            ],
            [
                'name' => 'Mosquito Net',
                'name_bn' => 'মশারি',
                'type' => 'shelter',
                'unit' => 'piece',
                'description' => 'Mosquito net for protection',
                'is_active' => true,
            ],
            // Educational Items
            [
                'name' => 'School Bag',
                'name_bn' => 'স্কুল ব্যাগ',
                'type' => 'other',
                'unit' => 'piece',
                'description' => 'School bag for students',
                'is_active' => true,
            ],
            [
                'name' => 'Notebook Set',
                'name_bn' => 'খাতা সেট',
                'type' => 'other',
                'unit' => 'set',
                'description' => 'Set of notebooks for students',
                'is_active' => true,
            ],
            // Monetary
            [
                'name' => 'Cash Relief',
                'name_bn' => 'নগদ ত্রাণ',
                'type' => 'monetary',
                'unit' => 'BDT',
                'description' => 'Direct cash assistance',
                'is_active' => true,
            ],
        ];

        $createdReliefItems = [];
        foreach ($reliefItems as $reliefItem) {
            $createdReliefItems[] = ReliefItem::create($reliefItem);
        }

        // Get existing Organization Types
        $createdOrgTypes = OrganizationType::all();

        // Create Projects for current economic year
        $projects = [
            [
                'name' => 'Emergency Food Assistance Program 2024-25',
                'remarks' => 'Comprehensive food relief program for vulnerable communities',
                'relief_type_id' => $createdReliefTypes[1]->id,
                'economic_year_id' => $currentYear->id,
                'allocated_amount' => 5000000.00,
                'available_amount' => 5000000.00,
            ],
            [
                'name' => 'Cash Transfer Initiative 2024-25',
                'remarks' => 'Direct cash assistance for families in need',
                'relief_type_id' => $createdReliefTypes[0]->id,
                'economic_year_id' => $currentYear->id,
                'allocated_amount' => 3000000.00,
                'available_amount' => 3000000.00,
            ],
            [
                'name' => 'Medical Relief Program 2024-25',
                'remarks' => 'Medical supplies and health assistance program',
                'relief_type_id' => $createdReliefTypes[2]->id,
                'economic_year_id' => $currentYear->id,
                'allocated_amount' => 2000000.00,
                'available_amount' => 2000000.00,
            ],
            [
                'name' => 'Shelter Support Program 2024-25',
                'remarks' => 'Housing and shelter materials assistance',
                'relief_type_id' => $createdReliefTypes[3]->id,
                'economic_year_id' => $currentYear->id,
                'allocated_amount' => 4000000.00,
                'available_amount' => 4000000.00,
            ],
            [
                'name' => 'Education Support Program 2024-25',
                'remarks' => 'Educational materials and supplies assistance',
                'relief_type_id' => $createdReliefTypes[4]->id,
                'economic_year_id' => $currentYear->id,
                'allocated_amount' => 1500000.00,
                'available_amount' => 1500000.00,
            ],
        ];

        $createdProjects = [];
        foreach ($projects as $project) {
            $createdProjects[] = Project::create($project);
        }

        // Get some zillas and upazilas for relief applications
        $zillas = Zilla::take(5)->get();
        $upazilas = Upazila::take(10)->get();
        $unions = Union::take(15)->get();
        $wards = Ward::take(20)->get();

        // Create Relief Applications with realistic data
        $this->command->info('Creating relief applications...');
        
        for ($i = 0; $i < 50; $i++) {
            $zilla = $zillas->random();
            $upazila = $upazilas->where('zilla_id', $zilla->id)->first() ?? $upazilas->random();
            $union = $unions->where('upazila_id', $upazila->id)->first() ?? $unions->random();
            $ward = $wards->where('union_id', $union->id)->first() ?? $wards->random();
            
            $reliefType = collect($createdReliefTypes)->random();
            $organizationType = $createdOrgTypes->random();
            $project = collect($createdProjects)->where('relief_type_id', $reliefType->id)->first();
            
            // Generate realistic amounts based on relief type
            $baseAmount = match($reliefType->unit) {
                'Taka' => rand(5000, 50000),
                'KG' => rand(20, 200),
                'Package' => rand(1, 10),
                'Piece' => rand(5, 50),
                'Set' => rand(2, 20),
                default => rand(1000, 10000)
            };
            
            $statuses = ['pending', 'approved', 'rejected'];
            $status = $statuses[array_rand($statuses)];
            $weights = [20, 60, 20]; // 20% pending, 60% approved, 20% rejected
            $status = $statuses[array_rand($statuses)];
            
            $application = ReliefApplication::create([
                'organization_name' => fake()->company(),
                'organization_type_id' => $organizationType->id,
                'date' => fake()->dateTimeBetween('-6 months', 'now'),
                'zilla_id' => $zilla->id,
                'upazila_id' => $upazila->id,
                'union_id' => $union->id,
                'ward_id' => $ward->id,
                'subject' => fake()->sentence(8),
                'relief_type_id' => $reliefType->id,
                'project_id' => $project->id,
                'applicant_name' => fake()->name(),
                'applicant_designation' => fake()->jobTitle(),
                'applicant_phone' => fake()->phoneNumber(),
                'applicant_address' => fake()->address(),
                'organization_address' => fake()->address(),
                'amount_requested' => $baseAmount,
                'details' => fake()->sentence(10),
                'admin_remarks' => fake()->sentence(8),
                'status' => $status,
                'created_at' => fake()->dateTimeBetween('-6 months', 'now'),
            ]);
            
            // Set approved amount and date if approved
            if ($status === 'approved') {
                $approvedAmount = $baseAmount * (rand(80, 100) / 100); // 80-100% of requested
                $application->update([
                    'approved_amount' => $approvedAmount,
                    'approved_at' => $application->created_at->addDays(rand(1, 30)),
                ]);
                
                // Update project available amount
                $project->decrement('available_amount', $approvedAmount);
            }
            
            // Add relief items for non-cash applications
            if ($reliefType->unit !== 'Taka' && $reliefType->unit !== 'টাকা') {
                // Get relevant items based on type
                $itemType = match($reliefType->name) {
                    'Food Relief' => 'food',
                    'Medical Relief' => 'medical',
                    'Shelter Relief' => 'shelter',
                    'Educational Relief' => 'other',
                    default => 'food'
                };
                
                $relevantItems = collect($createdReliefItems)->where('type', $itemType);
                if ($relevantItems->count() > 0) {
                    $item = $relevantItems->random();
                    $quantityRequested = rand(5, 50);
                    $quantityApproved = $status === 'approved' ? rand(5, $quantityRequested) : 0;
                    
                    // Generate a realistic unit price based on item type
                    $unitPrice = match($item->type) {
                        'food' => rand(20, 150),
                        'medical' => rand(500, 2000),
                        'shelter' => rand(300, 5000),
                        'other' => rand(100, 1000),
                        default => rand(50, 500)
                    };
                    
                    ReliefApplicationItem::create([
                        'relief_application_id' => $application->id,
                        'relief_item_id' => $item->id,
                        'quantity_requested' => $quantityRequested,
                        'quantity_approved' => $quantityApproved,
                        'unit_price' => $unitPrice,
                        'total_amount' => $quantityApproved * $unitPrice,
                    ]);
                }
            }
        }

        $this->command->info('Comprehensive test data created successfully!');
        $this->command->info('- Created ' . count($createdReliefTypes) . ' relief types');
        $this->command->info('- Created ' . count($createdReliefItems) . ' relief items');
        $this->command->info('- Created ' . count($createdOrgTypes) . ' organization types');
        $this->command->info('- Created ' . count($createdProjects) . ' projects');
        $this->command->info('- Created 50 relief applications with realistic data');
    }
}
