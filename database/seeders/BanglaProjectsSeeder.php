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
        $cashReliefType = $reliefTypes->where('name', 'Cash')->first() ?? $reliefTypes->first();
        $foodReliefType = $reliefTypes->where('name', 'Rice')->first() ?? $reliefTypes->skip(1)->first();
        $shelterReliefType = $reliefTypes->where('name', 'Corrugated Iron Sheet Bundle')->first() ?? $reliefTypes->skip(2)->first();
        $medicalReliefType = $reliefTypes->where('name', 'Winter Clothes')->first() ?? $reliefTypes->skip(3)->first();
        $winterReliefType = $reliefTypes->where('name', 'Blanket')->first() ?? $reliefTypes->skip(4)->first();

        $adminUser = $adminUsers->first();

        // Create comprehensive relief projects
        $projects = [
            // Flood Relief Projects
            [
                'name' => 'বন্যা কবলিত এলাকার জরুরি ত্রাণ সহায়তা প্রকল্প',
                'name_en' => 'Emergency Relief Assistance for Flood Affected Areas',
                'relief_type_id' => $cashReliefType->id,
                'allocated_amount' => 15000000.00,
                'available_amount' => 12000000.00,
                'remarks' => 'বগুড়া জেলার বন্যা কবলিত এলাকাগুলোতে জরুরি নগদ সহায়তা প্রদানের জন্য এই প্রকল্প। বিশেষ করে সারিয়াকান্দি ও শিবগঞ্জ উপজেলার চরাঞ্চলে বেশি প্রভাবিত পরিবারগুলোকে সহায়তা করা হবে।',
                'created_by' => $adminUser->id,
            ],
            [
                'name' => 'বন্যা পরবর্তী খাদ্য সহায়তা প্রকল্প',
                'name_en' => 'Post-Flood Food Assistance Project',
                'relief_type_id' => $foodReliefType->id,
                'allocated_amount' => 8000000.00,
                'available_amount' => 6000000.00,
                'remarks' => 'বন্যা পরবর্তী সময়ে ক্ষতিগ্রস্ত পরিবারগুলোকে চাল, ডাল ও অন্যান্য খাদ্য সামগ্রী প্রদানের প্রকল্প। প্রতিটি পরিবারকে ৩০ কেজি চাল ও ৫ কেজি ডাল দেওয়া হবে।',
                'created_by' => $adminUser->id,
            ],

            // Cyclone Relief Projects
            [
                'name' => 'ঘূর্ণিঝড় প্রস্তুতি ও পুনর্বাসন প্রকল্প',
                'name_en' => 'Cyclone Preparedness and Rehabilitation Project',
                'relief_type_id' => $shelterReliefType->id,
                'allocated_amount' => 12000000.00,
                'available_amount' => 10000000.00,
                'remarks' => 'ঘূর্ণিঝড়ের পূর্বে প্রস্তুতি ও পরবর্তীতে পুনর্বাসনের জন্য আশ্রয় সামগ্রী প্রদানের প্রকল্প। ঢেউটিন, তাঁবু ও অন্যান্য আশ্রয় সামগ্রী বিতরণ করা হবে।',
                'created_by' => $adminUser->id,
            ],
            [
                'name' => 'ঘূর্ণিঝড় কবলিত পরিবারের নগদ সহায়তা',
                'name_en' => 'Cash Assistance for Cyclone Affected Families',
                'relief_type_id' => $cashReliefType->id,
                'allocated_amount' => 10000000.00,
                'available_amount' => 8000000.00,
                'remarks' => 'ঘূর্ণিঝড়ে ক্ষতিগ্রস্ত পরিবারগুলোকে জরুরি নগদ সহায়তা প্রদানের প্রকল্প। প্রতিটি পরিবারকে ১০,০০০ থেকে ১৫,০০০ টাকা পর্যন্ত সহায়তা দেওয়া হবে।',
                'created_by' => $adminUser->id,
            ],

            // Drought Relief Projects
            [
                'name' => 'খরা কবলিত কৃষক পরিবারের সহায়তা প্রকল্প',
                'name_en' => 'Assistance Project for Drought Affected Farmer Families',
                'relief_type_id' => $foodReliefType->id,
                'allocated_amount' => 6000000.00,
                'available_amount' => 4500000.00,
                'remarks' => 'খরা কবলিত কৃষক পরিবারগুলোকে খাদ্য সহায়তা প্রদানের প্রকল্প। বিশেষ করে ধুনট ও গাবতলী উপজেলার কৃষক পরিবারগুলোকে অগ্রাধিকার দেওয়া হবে।',
                'created_by' => $adminUser->id,
            ],
            [
                'name' => 'খরা পরবর্তী বীজ সহায়তা প্রকল্প',
                'name_en' => 'Post-Drought Seed Assistance Project',
                'relief_type_id' => $cashReliefType->id,
                'allocated_amount' => 5000000.00,
                'available_amount' => 3500000.00,
                'remarks' => 'খরা পরবর্তী সময়ে কৃষকদের বীজ ক্রয়ের জন্য নগদ সহায়তা প্রদানের প্রকল্প। প্রতিটি কৃষক পরিবারকে ৫,০০০ টাকা বীজ সহায়তা দেওয়া হবে।',
                'created_by' => $adminUser->id,
            ],

            // Winter Relief Projects
            [
                'name' => 'শীতকালীন ত্রাণ সহায়তা প্রকল্প',
                'name_en' => 'Winter Relief Assistance Project',
                'relief_type_id' => $winterReliefType->id,
                'allocated_amount' => 7000000.00,
                'available_amount' => 5500000.00,
                'remarks' => 'শীতকালে দরিদ্র পরিবারগুলোকে কম্বল, শীতবস্ত্র ও অন্যান্য শীতকালীন সামগ্রী প্রদানের প্রকল্প। বিশেষ করে শিশু ও বৃদ্ধদের অগ্রাধিকার দেওয়া হবে।',
                'created_by' => $adminUser->id,
            ],
            [
                'name' => 'শীতকালীন স্বাস্থ্য সুরক্ষা প্রকল্প',
                'name_en' => 'Winter Health Protection Project',
                'relief_type_id' => $medicalReliefType->id,
                'allocated_amount' => 4000000.00,
                'available_amount' => 3000000.00,
                'remarks' => 'শীতকালীন রোগবালাই থেকে সুরক্ষার জন্য প্রয়োজনীয় ঔষধ ও স্বাস্থ্য সামগ্রী প্রদানের প্রকল্প।',
                'created_by' => $adminUser->id,
            ],

            // Medical Relief Projects
            [
                'name' => 'জরুরি চিকিৎসা সহায়তা প্রকল্প',
                'name_en' => 'Emergency Medical Assistance Project',
                'relief_type_id' => $medicalReliefType->id,
                'allocated_amount' => 9000000.00,
                'available_amount' => 7500000.00,
                'remarks' => 'দরিদ্র পরিবারগুলোর জরুরি চিকিৎসা ব্যয়ের জন্য নগদ সহায়তা প্রদানের প্রকল্প। প্রতিটি পরিবারকে সর্বোচ্চ ২০,০০০ টাকা পর্যন্ত সহায়তা দেওয়া হবে।',
                'created_by' => $adminUser->id,
            ],
            [
                'name' => 'প্রাথমিক স্বাস্থ্য সেবা প্রকল্প',
                'name_en' => 'Primary Healthcare Project',
                'relief_type_id' => $cashReliefType->id,
                'allocated_amount' => 5500000.00,
                'available_amount' => 4000000.00,
                'remarks' => 'দরিদ্র পরিবারগুলোর প্রাথমিক স্বাস্থ্য সেবা নিশ্চিত করার জন্য ঔষধ ক্রয়ের নগদ সহায়তা প্রদানের প্রকল্প।',
                'created_by' => $adminUser->id,
            ],

            // Educational Relief Projects
            [
                'name' => 'দরিদ্র শিক্ষার্থীদের শিক্ষা সহায়তা প্রকল্প',
                'name_en' => 'Educational Assistance Project for Poor Students',
                'relief_type_id' => $cashReliefType->id,
                'allocated_amount' => 6500000.00,
                'available_amount' => 5000000.00,
                'remarks' => 'দরিদ্র পরিবারের শিক্ষার্থীদের শিক্ষা ব্যয়ের জন্য নগদ সহায়তা প্রদানের প্রকল্প। স্কুল ফি, বই-খাতা ও অন্যান্য শিক্ষা সামগ্রী ক্রয়ের জন্য সহায়তা দেওয়া হবে।',
                'created_by' => $adminUser->id,
            ],
            [
                'name' => 'স্কুল ছাত্রছাত্রীদের পোশাক সহায়তা প্রকল্প',
                'name_en' => 'School Uniform Assistance Project for Students',
                'relief_type_id' => $cashReliefType->id,
                'allocated_amount' => 4500000.00,
                'available_amount' => 3000000.00,
                'remarks' => 'দরিদ্র পরিবারের স্কুল ছাত্রছাত্রীদের স্কুল ইউনিফর্ম ক্রয়ের জন্য নগদ সহায়তা প্রদানের প্রকল্প। প্রতিটি ছাত্রছাত্রীকে ১,৫০০ টাকা করে সহায়তা দেওয়া হবে।',
                'created_by' => $adminUser->id,
            ],

            // Special Relief Projects
            [
                'name' => 'অসহায় মহিলাদের আয় বৃদ্ধি প্রকল্প',
                'name_en' => 'Income Generation Project for Helpless Women',
                'relief_type_id' => $cashReliefType->id,
                'allocated_amount' => 8000000.00,
                'available_amount' => 6000000.00,
                'remarks' => 'অসহায় মহিলাদের আয় বৃদ্ধির জন্য ক্ষুদ্র ব্যবসা শুরু করার নগদ সহায়তা প্রদানের প্রকল্প। প্রতিটি মহিলাকে ১০,০০০ টাকা করে সহায়তা দেওয়া হবে।',
                'created_by' => $adminUser->id,
            ],
            [
                'name' => 'বৃদ্ধ ও প্রতিবন্ধীদের সামাজিক সহায়তা প্রকল্প',
                'name_en' => 'Social Assistance Project for Elderly and Disabled',
                'relief_type_id' => $cashReliefType->id,
                'allocated_amount' => 5500000.00,
                'available_amount' => 4000000.00,
                'remarks' => 'বৃদ্ধ ও প্রতিবন্ধী ব্যক্তিদের মাসিক সামাজিক সহায়তা প্রদানের প্রকল্প। প্রতিটি ব্যক্তিকে মাসিক ১,০০০ টাকা করে সহায়তা দেওয়া হবে।',
                'created_by' => $adminUser->id,
            ],

            // Emergency Response Projects
            [
                'name' => 'জরুরি সাড়া প্রদান তহবিল',
                'name_en' => 'Emergency Response Fund',
                'relief_type_id' => $cashReliefType->id,
                'allocated_amount' => 20000000.00,
                'available_amount' => 18000000.00,
                'remarks' => 'যেকোনো জরুরি পরিস্থিতিতে দ্রুত সাড়া প্রদানের জন্য সাধারণ তহবিল। এই তহবিল থেকে প্রয়োজন অনুযায়ী বিভিন্ন ধরনের সহায়তা প্রদান করা হবে।',
                'created_by' => $adminUser->id,
            ],
            [
                'name' => 'আবাসন সহায়তা প্রকল্প',
                'name_en' => 'Housing Assistance Project',
                'relief_type_id' => $shelterReliefType->id,
                'allocated_amount' => 15000000.00,
                'available_amount' => 12000000.00,
                'remarks' => 'বাড়িঘরহীন পরিবারদের আবাসনের জন্য ঘর নির্মাণ সামগ্রী প্রদানের প্রকল্প। ঢেউটিন, বালি, সিমেন্ট ও অন্যান্য নির্মাণ সামগ্রী প্রদান করা হবে।',
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
        $this->command->info('Projects include flood relief, cyclone relief, drought relief, winter relief, medical relief, educational relief, and emergency response projects.');
        $this->command->info('All projects are designed for the current economic year with realistic budget allocations.');
    }
}
