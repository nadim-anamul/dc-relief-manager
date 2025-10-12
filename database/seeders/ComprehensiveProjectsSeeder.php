<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\EconomicYear;
use App\Models\ReliefType;
use Illuminate\Database\Seeder;

class ComprehensiveProjectsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creating comprehensive projects for all economic years...');

        $economicYears = EconomicYear::all();
        $reliefTypes = ReliefType::all();

        if ($economicYears->isEmpty() || $reliefTypes->isEmpty()) {
            $this->command->warn('Economic Years or Relief Types not found. Please run other seeders first.');
            return;
        }

        $createdCount = 0;

        foreach ($economicYears as $year) {
            $this->command->info("Creating projects for economic year: {$year->name}");
            
            $projectsForYear = $this->generateProjectsForYear($year, $reliefTypes);
            
            foreach ($projectsForYear as $projectData) {
                Project::firstOrCreate(
                    [
                        'name' => $projectData['name'],
                        'economic_year_id' => $projectData['economic_year_id'],
                        'relief_type_id' => $projectData['relief_type_id'],
                    ],
                    $projectData
                );
                $createdCount++;
            }
        }

        $this->command->info("Created {$createdCount} comprehensive projects across all economic years.");
    }

    private function generateProjectsForYear($year, $reliefTypes)
    {
        $projects = [];
        
        // Different number of projects based on year
        $numProjects = $this->getProjectCountForYear($year);
        
        // Core relief types that should have projects every year
        $coreReliefTypes = ['Food Assistance for Destitute', 'Cash Assistance', 'Grain Assistance', 'Winter Clothing Assistance', 'Corrugated Iron Sheet Assistance', 'Housing Assistance', 'District Commissioner Assistance'];
        
        // Create projects for core relief types
        foreach ($coreReliefTypes as $coreType) {
            $reliefType = $reliefTypes->where('name', $coreType)->first();
            if ($reliefType) {
                $projects[] = $this->generateProjectData($year, $reliefType, true);
            }
        }
        
        // For current and previous years, create 5 projects per relief type
        if ($year->is_current || $year->end_date < now()) {
            foreach ($coreReliefTypes as $coreType) {
                $reliefType = $reliefTypes->where('name', $coreType)->first();
                if ($reliefType) {
                    // Create 5 projects for each relief type
                    for ($i = 0; $i < 5; $i++) {
                        $projects[] = $this->generateProjectData($year, $reliefType, true);
                    }
                }
            }
        } else {
            // For future years, create 3 projects per relief type
            foreach ($coreReliefTypes as $coreType) {
                $reliefType = $reliefTypes->where('name', $coreType)->first();
                if ($reliefType) {
                    // Create 3 projects for each relief type
                    for ($i = 0; $i < 3; $i++) {
                        $projects[] = $this->generateProjectData($year, $reliefType, true);
                    }
                }
            }
        }
        
        return $projects;
    }

    private function getProjectCountForYear($year)
    {
        // Current year: 5 projects per relief type (7 types = 35 projects)
        if ($year->is_current) {
            return 35;
        }
        // Previous year: 5 projects per relief type (7 types = 35 projects)
        elseif ($year->end_date < now()) {
            return 35;
        }
        // Future years: fewer projects (3 per type = 21 projects)
        else {
            return 21;
        }
    }

    private function generateProjectData($year, $reliefType, $isCore = false)
    {
        $projectNames = $this->getProjectNamesForReliefType($reliefType->name);
        $projectName = $projectNames[array_rand($projectNames)];
        
        // Generate budget based on year and relief type
        $allocatedAmount = $this->generateBudgetForProject($year, $reliefType, $isCore);
        
        // Available amount depends on year status
        $availableAmount = $this->generateAvailableAmount($year, $allocatedAmount);
        
        return [
            'name' => $projectName . ' ' . $year->name,
            'remarks' => $this->generateProjectRemarks($reliefType, $year),
            'economic_year_id' => $year->id,
            'relief_type_id' => $reliefType->id,
            'allocated_amount' => $allocatedAmount,
            'available_amount' => $availableAmount,
        ];
    }

    private function getProjectNamesForReliefType($reliefTypeName)
    {
        return match($reliefTypeName) {
            'Food Assistance for Destitute' => [
                'দুঃস্থ পরিবারের জরুরি খাদ্য সহায়তা প্রকল্প',
                'অসহায় পরিবারের খাদ্য নিরাপত্তা প্রকল্প',
                'দরিদ্র পরিবারের পুষ্টি সহায়তা প্রকল্প',
                'বেকার পরিবারের খাদ্য সহায়তা প্রকল্প',
                'দুঃস্থদের খাদ্য সহায়তা প্রকল্প'
            ],
            'Cash Assistance' => [
                'দুঃস্থ পরিবারের জরুরি নগদ সহায়তা প্রকল্প',
                'অসহায় মহিলাদের নগদ সহায়তা প্রকল্প',
                'বেকার যুবকদের নগদ সহায়তা প্রকল্প',
                'প্রতিবন্ধীদের নগদ সহায়তা প্রকল্প',
                'নগদ অর্থ সহায়তা প্রকল্প'
            ],
            'Grain Assistance' => [
                'কৃষক পরিবারের খাদ্যশস্য সহায়তা প্রকল্প',
                'খরা কবলিত কৃষকদের খাদ্যশস্য সহায়তা',
                'দরিদ্র কৃষকদের বীজ সহায়তা প্রকল্প',
                'নতুন কৃষকদের খাদ্যশস্য সহায়তা প্রকল্প',
                'খাদ্যশস্য সহায়তা প্রকল্প'
            ],
            'Winter Clothing Assistance' => [
                'দরিদ্র পরিবারের শীতকালীন সহায়তা প্রকল্প',
                'বৃদ্ধদের শীতকালীন সহায়তা প্রকল্প',
                'শিশুদের শীতকালীন সহায়তা প্রকল্প',
                'অসহায় পরিবারের শীতকালীন সহায়তা',
                'শীতবস্ত্র সহায়তা প্রকল্প'
            ],
            'Corrugated Iron Sheet Assistance' => [
                'গৃহহীন পরিবারের ঢেউটিন সহায়তা প্রকল্প',
                'বন্যা কবলিত পরিবারের ঢেউটিন সহায়তা',
                'ঘূর্ণিঝড় কবলিত পরিবারের ঢেউটিন সহায়তা',
                'দরিদ্র পরিবারের ঢেউটিন সহায়তা প্রকল্প',
                'ঢেউটিন সহায়তা প্রকল্প'
            ],
            'Housing Assistance' => [
                'অসহায় পরিবারের আবাসন সহায়তা প্রকল্প',
                'দরিদ্র পরিবারের গৃহ নির্মাণ সহায়তা',
                'বেকার পরিবারের আবাসন সহায়তা প্রকল্প',
                'প্রতিবন্ধী পরিবারের গৃহ সহায়তা প্রকল্প',
                'গৃহবাবদ সহায়তা প্রকল্প'
            ],
            'District Commissioner Assistance' => [
                'জেলা প্রশাসকের বিশেষ সহায়তা প্রকল্প',
                'জেলা প্রশাসকের জরুরি ত্রাণ তহবিল',
                'জেলা প্রশাসকের সামাজিক সহায়তা প্রকল্প',
                'জেলা প্রশাসকের মানবিক সহায়তা প্রকল্প',
                'জেলা প্রশাসকের সাহায্য প্রকল্প'
            ],
            default => [
                'সামাজিক সহায়তা প্রকল্প',
                'কল্যাণমূলক প্রকল্প',
                'সামাজিক উন্নয়ন প্রকল্প',
                'মানবিক সহায়তা',
                'সামাজিক সুরক্ষা'
            ]
        };
    }

    private function generateBudgetForProject($year, $reliefType, $isCore)
    {
        $baseAmount = match($reliefType->name) {
            'Food Assistance for Destitute' => 8000000,
            'Cash Assistance' => 5000000,
            'Grain Assistance' => 7000000,
            'Winter Clothing Assistance' => 3000000,
            'Corrugated Iron Sheet Assistance' => 10000000,
            'Housing Assistance' => 12000000,
            'District Commissioner Assistance' => 15000000,
            default => 5000000
        };
        
        // Core projects get higher budgets
        if ($isCore) {
            $baseAmount *= 1.5;
        }
        
        // Current year gets higher budgets
        if ($year->is_current) {
            $baseAmount *= 1.2;
        }
        // Past years get lower budgets
        elseif ($year->end_date < now()) {
            $baseAmount *= 0.8;
        }
        
        // Add some variation
        $variation = rand(80, 120) / 100;
        
        return round($baseAmount * $variation, 2);
    }

    private function generateAvailableAmount($year, $allocatedAmount)
    {
        // Current year: 60-90% available
        if ($year->is_current) {
            $percentage = rand(60, 90) / 100;
        }
        // Past years: 0-30% available (mostly used up)
        elseif ($year->end_date < now()) {
            $percentage = rand(0, 30) / 100;
        }
        // Future years: 100% available
        else {
            $percentage = 1.0;
        }
        
        return round($allocatedAmount * $percentage, 2);
    }

    private function generateProjectRemarks($reliefType, $year)
    {
        $remarks = [
            'Food Assistance for Destitute' => 'দুঃস্থ ও দরিদ্র পরিবারগুলোর জন্য খাদ্য সহায়তা প্রদান।',
            'Cash Assistance' => 'দরিদ্র ও অসহায় পরিবারগুলোর জন্য নগদ সহায়তা প্রদান।',
            'Grain Assistance' => 'কৃষক পরিবারগুলোর জন্য খাদ্যশস্য সহায়তা প্রদান।',
            'Winter Clothing Assistance' => 'শীতকালে দরিদ্র পরিবারগুলোর জন্য শীতবস্ত্র ও কম্বল প্রদান।',
            'Corrugated Iron Sheet Assistance' => 'আবাসন নির্মাণের জন্য ঢেউটিন সহায়তা প্রদান।',
            'Housing Assistance' => 'গৃহহীন পরিবারগুলোর জন্য আবাসন সহায়তা প্রদান।',
            'District Commissioner Assistance' => 'জেলা প্রশাসকের বিশেষ সহায়তা প্রদান।',
        ];
        
        $baseRemark = $remarks[$reliefType->name] ?? 'সামাজিক কল্যাণমূলক প্রকল্প।';
        
        if ($year->is_current) {
            return $baseRemark . ' বর্তমান অর্থবছরের জন্য কার্যকর।';
        } elseif ($year->end_date < now()) {
            return $baseRemark . ' ' . $year->name . ' অর্থবছরে সম্পন্ন।';
        } else {
            return $baseRemark . ' ' . $year->name . ' অর্থবছরের জন্য পরিকল্পিত।';
        }
    }
}
