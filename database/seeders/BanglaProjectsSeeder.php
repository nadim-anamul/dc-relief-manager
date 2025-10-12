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

        // Create comprehensive relief projects for each relief type (4 projects per type)
        $projects = [
            // দুঃস্থদের খাদ্য সহায়তা (Food Assistance for Destitute) - 4 projects
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
            [
                'name' => 'দরিদ্র পরিবারের পুষ্টি সহায়তা প্রকল্প',
                'relief_type_id' => $foodAssistanceType->id,
                'allocated_amount' => 5500000.00,
                'available_amount' => 4000000.00,
                'remarks' => 'দরিদ্র পরিবারগুলোর পুষ্টি উন্নয়নের জন্য খাদ্য সহায়তা প্রদানের প্রকল্প।',
                'created_by' => $adminUser->id,
            ],
            [
                'name' => 'বেকার পরিবারের খাদ্য সহায়তা প্রকল্প',
                'relief_type_id' => $foodAssistanceType->id,
                'allocated_amount' => 7000000.00,
                'available_amount' => 5500000.00,
                'remarks' => 'বেকার পরিবারগুলোর জন্য খাদ্য সহায়তা প্রদানের প্রকল্প।',
                'created_by' => $adminUser->id,
            ],

            // নগদ অর্থ সহায়তা (Cash Assistance) - 4 projects
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
            [
                'name' => 'বেকার যুবকদের নগদ সহায়তা প্রকল্প',
                'relief_type_id' => $cashAssistanceType->id,
                'allocated_amount' => 7500000.00,
                'available_amount' => 5500000.00,
                'remarks' => 'বেকার যুবকদের ক্ষুদ্র ব্যবসা শুরু করার জন্য নগদ সহায়তা প্রদানের প্রকল্প।',
                'created_by' => $adminUser->id,
            ],
            [
                'name' => 'প্রতিবন্ধীদের নগদ সহায়তা প্রকল্প',
                'relief_type_id' => $cashAssistanceType->id,
                'allocated_amount' => 6000000.00,
                'available_amount' => 4500000.00,
                'remarks' => 'প্রতিবন্ধী ব্যক্তিদের জন্য নগদ সহায়তা প্রদানের প্রকল্প।',
                'created_by' => $adminUser->id,
            ],

            // খাদ্যশস্য সহায়তা (Grain Assistance) - 4 projects
            [
                'name' => 'কৃষক পরিবারের খাদ্যশস্য সহায়তা প্রকল্প',
                'relief_type_id' => $grainAssistanceType->id,
                'allocated_amount' => 7000000.00,
                'available_amount' => 5500000.00,
                'remarks' => 'কৃষক পরিবারগুলোর জন্য খাদ্যশস্য সহায়তা প্রদানের প্রকল্প। প্রতিটি পরিবারকে ৫০ কেজি চাল দেওয়া হবে।',
                'created_by' => $adminUser->id,
            ],
            [
                'name' => 'খরা কবলিত কৃষকদের খাদ্যশস্য সহায়তা',
                'relief_type_id' => $grainAssistanceType->id,
                'allocated_amount' => 6000000.00,
                'available_amount' => 4500000.00,
                'remarks' => 'খরা কবলিত কৃষক পরিবারগুলোর জন্য খাদ্যশস্য সহায়তা প্রদানের প্রকল্প।',
                'created_by' => $adminUser->id,
            ],
            [
                'name' => 'দরিদ্র কৃষকদের বীজ সহায়তা প্রকল্প',
                'relief_type_id' => $grainAssistanceType->id,
                'allocated_amount' => 5500000.00,
                'available_amount' => 4000000.00,
                'remarks' => 'দরিদ্র কৃষকদের বীজ ক্রয়ের জন্য খাদ্যশস্য সহায়তা প্রদানের প্রকল্প।',
                'created_by' => $adminUser->id,
            ],
            [
                'name' => 'নতুন কৃষকদের খাদ্যশস্য সহায়তা প্রকল্প',
                'relief_type_id' => $grainAssistanceType->id,
                'allocated_amount' => 6500000.00,
                'available_amount' => 5000000.00,
                'remarks' => 'নতুন কৃষকদের জন্য খাদ্যশস্য সহায়তা প্রদানের প্রকল্প।',
                'created_by' => $adminUser->id,
            ],

            // শীতবস্ত্র সহায়তা (Winter Clothing Assistance) - 4 projects
            [
                'name' => 'দরিদ্র পরিবারের শীতকালীন সহায়তা প্রকল্প',
                'relief_type_id' => $winterClothingType->id,
                'allocated_amount' => 6000000.00,
                'available_amount' => 4500000.00,
                'remarks' => 'দরিদ্র পরিবারগুলোর জন্য শীতকালীন পোশাক ও কম্বল প্রদানের প্রকল্প। প্রতিটি পরিবারকে ২টি কম্বল দেওয়া হবে।',
                'created_by' => $adminUser->id,
            ],
            [
                'name' => 'বৃদ্ধদের শীতকালীন সহায়তা প্রকল্প',
                'relief_type_id' => $winterClothingType->id,
                'allocated_amount' => 4500000.00,
                'available_amount' => 3500000.00,
                'remarks' => 'বৃদ্ধদের জন্য শীতকালীন পোশাক ও গরম কাপড় প্রদানের প্রকল্প।',
                'created_by' => $adminUser->id,
            ],
            [
                'name' => 'শিশুদের শীতকালীন সহায়তা প্রকল্প',
                'relief_type_id' => $winterClothingType->id,
                'allocated_amount' => 5000000.00,
                'available_amount' => 4000000.00,
                'remarks' => 'শিশুদের জন্য শীতকালীন পোশাক ও কম্বল প্রদানের প্রকল্প।',
                'created_by' => $adminUser->id,
            ],
            [
                'name' => 'অসহায় পরিবারের শীতকালীন সহায়তা',
                'relief_type_id' => $winterClothingType->id,
                'allocated_amount' => 5500000.00,
                'available_amount' => 4000000.00,
                'remarks' => 'অসহায় পরিবারগুলোর জন্য শীতকালীন সহায়তা প্রদানের প্রকল্প।',
                'created_by' => $adminUser->id,
            ],

            // ঢেউটিন সহায়তা (Corrugated Iron Sheet Assistance) - 4 projects
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
            [
                'name' => 'ঘূর্ণিঝড় কবলিত পরিবারের ঢেউটিন সহায়তা',
                'relief_type_id' => $corrugatedIronType->id,
                'allocated_amount' => 9500000.00,
                'available_amount' => 7500000.00,
                'remarks' => 'ঘূর্ণিঝড় কবলিত পরিবারগুলোর ঘর মেরামতের জন্য ঢেউটিন সহায়তা প্রদানের প্রকল্প।',
                'created_by' => $adminUser->id,
            ],
            [
                'name' => 'দরিদ্র পরিবারের ঢেউটিন সহায়তা প্রকল্প',
                'relief_type_id' => $corrugatedIronType->id,
                'allocated_amount' => 11000000.00,
                'available_amount' => 9000000.00,
                'remarks' => 'দরিদ্র পরিবারগুলোর ঘর নির্মাণের জন্য ঢেউটিন সহায়তা প্রদানের প্রকল্প।',
                'created_by' => $adminUser->id,
            ],

            // গৃহবাবদ সহায়তা (Housing Assistance) - 4 projects
            [
                'name' => 'অসহায় পরিবারের আবাসন সহায়তা প্রকল্প',
                'relief_type_id' => $housingAssistanceType->id,
                'allocated_amount' => 15000000.00,
                'available_amount' => 12000000.00,
                'remarks' => 'অসহায় পরিবারগুলোর আবাসনের জন্য ঘর নির্মাণ সামগ্রী প্রদানের প্রকল্প। সিমেন্ট, বালি, ইট সহায়তা দেওয়া হবে।',
                'created_by' => $adminUser->id,
            ],
            [
                'name' => 'দরিদ্র পরিবারের গৃহ নির্মাণ সহায়তা',
                'relief_type_id' => $housingAssistanceType->id,
                'allocated_amount' => 13000000.00,
                'available_amount' => 10000000.00,
                'remarks' => 'দরিদ্র পরিবারগুলোর গৃহ নির্মাণের জন্য নির্মাণ সামগ্রী সহায়তা প্রদানের প্রকল্প।',
                'created_by' => $adminUser->id,
            ],
            [
                'name' => 'বেকার পরিবারের আবাসন সহায়তা প্রকল্প',
                'relief_type_id' => $housingAssistanceType->id,
                'allocated_amount' => 14000000.00,
                'available_amount' => 11000000.00,
                'remarks' => 'বেকার পরিবারগুলোর আবাসনের জন্য সহায়তা প্রদানের প্রকল্প।',
                'created_by' => $adminUser->id,
            ],
            [
                'name' => 'প্রতিবন্ধী পরিবারের গৃহ সহায়তা প্রকল্প',
                'relief_type_id' => $housingAssistanceType->id,
                'allocated_amount' => 12000000.00,
                'available_amount' => 9000000.00,
                'remarks' => 'প্রতিবন্ধী পরিবারগুলোর গৃহ নির্মাণের জন্য সহায়তা প্রদানের প্রকল্প।',
                'created_by' => $adminUser->id,
            ],

            // জেলা প্রশাসকের সাহায্য (District Commissioner Assistance) - 4 projects
            [
                'name' => 'জেলা প্রশাসকের বিশেষ সহায়তা প্রকল্প',
                'relief_type_id' => $dcAssistanceType->id,
                'allocated_amount' => 20000000.00,
                'available_amount' => 18000000.00,
                'remarks' => 'জেলা প্রশাসকের বিশেষ সহায়তা প্রকল্প। বিভিন্ন ধরনের জরুরি সহায়তা প্রদান করা হবে।',
                'created_by' => $adminUser->id,
            ],
            [
                'name' => 'জেলা প্রশাসকের জরুরি ত্রাণ তহবিল',
                'relief_type_id' => $dcAssistanceType->id,
                'allocated_amount' => 18000000.00,
                'available_amount' => 15000000.00,
                'remarks' => 'জরুরি পরিস্থিতিতে দ্রুত সাড়া প্রদানের জন্য জেলা প্রশাসকের ত্রাণ তহবিল।',
                'created_by' => $adminUser->id,
            ],
            [
                'name' => 'জেলা প্রশাসকের সামাজিক সহায়তা প্রকল্প',
                'relief_type_id' => $dcAssistanceType->id,
                'allocated_amount' => 16000000.00,
                'available_amount' => 13000000.00,
                'remarks' => 'সামাজিক কল্যাণের জন্য জেলা প্রশাসকের বিশেষ সহায়তা প্রকল্প।',
                'created_by' => $adminUser->id,
            ],
            [
                'name' => 'জেলা প্রশাসকের মানবিক সহায়তা প্রকল্প',
                'relief_type_id' => $dcAssistanceType->id,
                'allocated_amount' => 17000000.00,
                'available_amount' => 14000000.00,
                'remarks' => 'মানবিক সহায়তার জন্য জেলা প্রশাসকের বিশেষ প্রকল্প।',
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
        $this->command->info('Projects include: দুঃস্থদের খাদ্য সহায়তা (৪টি), নগদ অর্থ সহায়তা (৪টি), খাদ্যশস্য সহায়তা (৪টি), শীতবস্ত্র সহায়তা (৪টি), ঢেউটিন সহায়তা (৪টি), গৃহবাবদ সহায়তা (৪টি), জেলা প্রশাসকের সাহায্য (৪টি)');
        $this->command->info('All projects are designed for the current economic year with realistic budget allocations.');
    }
}
