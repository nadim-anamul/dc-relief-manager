<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\EconomicYear;
use App\Models\ReliefType;
use App\Models\User;
use Illuminate\Database\Seeder;

class BanglaProjectsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creating comprehensive Bangla relief projects...');

        // Get required data
        $currentYear = EconomicYear::where('is_current', true)->first();
        $reliefTypes = ReliefType::all();
        $adminUsers = User::whereHas('roles', function($q) {
            $q->whereIn('name', ['super-admin', 'district-admin']);
        })->get();

        if (!$currentYear || $reliefTypes->isEmpty() || $adminUsers->isEmpty()) {
            $this->command->warn('Required data not found. Please run other seeders first.');
            return;
        }

        // Get relief type IDs
        $foodAssistanceType = $reliefTypes->where('name', 'Food Assistance for Destitute')->first() ?? $reliefTypes->first();
        $cashAssistanceType = $reliefTypes->where('name', 'Cash Assistance')->first() ?? $reliefTypes->skip(1)->first();
        $grainAssistanceType = $reliefTypes->where('name', 'Grain Assistance')->first() ?? $reliefTypes->skip(2)->first();
        $winterClothingType = $reliefTypes->where('name', 'Winter Clothing Assistance')->first() ?? $reliefTypes->skip(3)->first();
        $corrugatedIronType = $reliefTypes->where('name', 'Corrugated Iron Sheet Assistance')->first() ?? $reliefTypes->skip(4)->first();
        $housingAssistanceType = $reliefTypes->where('name', 'Housing Assistance')->first() ?? $reliefTypes->skip(5)->first();
        $dcAssistanceType = $reliefTypes->where('name', 'District Commissioner Assistance')->first() ?? $reliefTypes->skip(6)->first();

        $adminUser = $adminUsers->first();

        // Create comprehensive relief projects (10 projects total - 2 projects per type for most types, 1 each for others)
        $projects = [
            // দুঃস্থদের খাদ্য সহায়তা (Food Assistance for Destitute) - 2 projects
            [
                'name' => 'দুঃস্থ পরিবারের জরুরি খাদ্য সহায়তা প্রকল্প',
                'relief_type_id' => $foodAssistanceType->id,
                'allocated_amount' => 8000000.00,
                'available_amount' => 6000000.00,
                'remarks' => 'দুঃস্থ ও দরিদ্র পরিবারগুলোর জন্য জরুরি খাদ্য সহায়তা প্রদানের প্রকল্প। প্রতিটি পরিবারকে ৩০ কেজি চাল ও ৫ কেজি ডাল দেওয়া হবে।',
                'created_by' => $adminUser->id,
            ],
            [
                'name' => 'অসহায় পরিবারের খাদ্য নিরাপত্তা প্রকল্প',
                'relief_type_id' => $foodAssistanceType->id,
                'allocated_amount' => 6500000.00,
                'available_amount' => 5000000.00,
                'remarks' => 'অসহায় পরিবারগুলোর খাদ্য নিরাপত্তা নিশ্চিত করার জন্য খাদ্য সামগ্রী প্রদানের প্রকল্প।',
                'created_by' => $adminUser->id,
            ],

            // নগদ অর্থ সহায়তা (Cash Assistance) - 2 projects
            [
                'name' => 'দুঃস্থ পরিবারের জরুরি নগদ সহায়তা প্রকল্প',
                'relief_type_id' => $cashAssistanceType->id,
                'allocated_amount' => 10000000.00,
                'available_amount' => 8000000.00,
                'remarks' => 'দুঃস্থ পরিবারগুলোর জরুরি প্রয়োজনের জন্য নগদ সহায়তা প্রদানের প্রকল্প। প্রতিটি পরিবারকে ১০,০০০ থেকে ১৫,০০০ টাকা দেওয়া হবে।',
                'created_by' => $adminUser->id,
            ],
            [
                'name' => 'অসহায় মহিলাদের নগদ সহায়তা প্রকল্প',
                'relief_type_id' => $cashAssistanceType->id,
                'allocated_amount' => 8000000.00,
                'available_amount' => 6000000.00,
                'remarks' => 'অসহায় মহিলাদের আয় বৃদ্ধির জন্য নগদ সহায়তা প্রদানের প্রকল্প।',
                'created_by' => $adminUser->id,
            ],

            // খাদ্যশস্য সহায়তা (Grain Assistance) - 1 project
            [
                'name' => 'কৃষক পরিবারের খাদ্যশস্য সহায়তা প্রকল্প',
                'relief_type_id' => $grainAssistanceType->id,
                'allocated_amount' => 7000000.00,
                'available_amount' => 5500000.00,
                'remarks' => 'কৃষক পরিবারগুলোর জন্য খাদ্যশস্য সহায়তা প্রদানের প্রকল্প। প্রতিটি পরিবারকে ৫০ কেজি চাল দেওয়া হবে।',
                'created_by' => $adminUser->id,
            ],

            // শীতবস্ত্র সহায়তা (Winter Clothing Assistance) - 1 project
            [
                'name' => 'দরিদ্র পরিবারের শীতকালীন সহায়তা প্রকল্প',
                'relief_type_id' => $winterClothingType->id,
                'allocated_amount' => 6000000.00,
                'available_amount' => 4500000.00,
                'remarks' => 'দরিদ্র পরিবারগুলোর জন্য শীতকালীন পোশাক ও কম্বল প্রদানের প্রকল্প। প্রতিটি পরিবারকে ২টি কম্বল দেওয়া হবে।',
                'created_by' => $adminUser->id,
            ],

            // ঢেউটিন সহায়তা (Corrugated Iron Sheet Assistance) - 2 projects
            [
                'name' => 'গৃহহীন পরিবারের ঢেউটিন সহায়তা প্রকল্প',
                'relief_type_id' => $corrugatedIronType->id,
                'allocated_amount' => 12000000.00,
                'available_amount' => 10000000.00,
                'remarks' => 'গৃহহীন পরিবারগুলোর আবাসনের জন্য ঢেউটিন প্রদানের প্রকল্প। প্রতিটি পরিবারকে ২০টি ঢেউটিন দেওয়া হবে।',
                'created_by' => $adminUser->id,
            ],
            [
                'name' => 'বন্যা কবলিত পরিবারের ঢেউটিন সহায়তা',
                'relief_type_id' => $corrugatedIronType->id,
                'allocated_amount' => 10000000.00,
                'available_amount' => 8000000.00,
                'remarks' => 'বন্যা কবলিত পরিবারগুলোর ঘর পুনর্নির্মাণের জন্য ঢেউটিন সহায়তা প্রদানের প্রকল্প।',
                'created_by' => $adminUser->id,
            ],

            // গৃহবাবদ সহায়তা (Housing Assistance) - 1 project
            [
                'name' => 'অসহায় পরিবারের আবাসন সহায়তা প্রকল্প',
                'relief_type_id' => $housingAssistanceType->id,
                'allocated_amount' => 15000000.00,
                'available_amount' => 12000000.00,
                'remarks' => 'অসহায় পরিবারগুলোর আবাসনের জন্য ঘর নির্মাণ সামগ্রী প্রদানের প্রকল্প। সিমেন্ট, বালি, ইট সহায়তা দেওয়া হবে।',
                'created_by' => $adminUser->id,
            ],

            // জেলা প্রশাসকের সাহায্য (District Commissioner Assistance) - 1 project
            [
                'name' => 'জেলা প্রশাসকের বিশেষ সহায়তা প্রকল্প',
                'relief_type_id' => $dcAssistanceType->id,
                'allocated_amount' => 20000000.00,
                'available_amount' => 18000000.00,
                'remarks' => 'জেলা প্রশাসকের বিশেষ সহায়তা প্রকল্প। বিভিন্ন ধরনের জরুরি সহায়তা প্রদান করা হবে।',
                'created_by' => $adminUser->id,
            ]
        ];

        $createdCount = 0;

        foreach ($projects as $projectData) {
            $project = Project::create([
                'name' => $projectData['name'],
                'economic_year_id' => $currentYear->id,
                'relief_type_id' => $projectData['relief_type_id'],
                'allocated_amount' => $projectData['allocated_amount'],
                'available_amount' => $projectData['available_amount'],
                'remarks' => $projectData['remarks'],
                'created_by' => $projectData['created_by'],
                'updated_by' => $projectData['created_by'],
            ]);

            $createdCount++;
        }

        $this->command->info("Created {$createdCount} comprehensive Bangla relief projects.");
        $this->command->info('Projects include: দুঃস্থদের খাদ্য সহায়তা (২টি), নগদ অর্থ সহায়তা (২টি), খাদ্যশস্য সহায়তা (১টি), শীতবস্ত্র সহায়তা (১টি), ঢেউটিন সহায়তা (২টি), গৃহবাবদ সহায়তা (১টি), জেলা প্রশাসকের সাহায্য (১টি)');
        $this->command->info('All projects are designed for the current economic year with realistic budget allocations.');
    }
}
