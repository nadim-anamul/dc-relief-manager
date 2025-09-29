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
        $coreReliefTypes = ['Cash Relief', 'Food Relief', 'Medical Relief', 'Shelter Relief', 'Emergency Relief'];
        
        // Create projects for core relief types
        foreach ($coreReliefTypes as $coreType) {
            $reliefType = $reliefTypes->where('name', $coreType)->first();
            if ($reliefType) {
                $projects[] = $this->generateProjectData($year, $reliefType, true);
            }
        }
        
        // Add additional projects for other relief types
        $additionalTypes = $reliefTypes->whereNotIn('name', $coreReliefTypes)->random($numProjects - count($coreReliefTypes));
        
        foreach ($additionalTypes as $reliefType) {
            $projects[] = $this->generateProjectData($year, $reliefType, false);
        }
        
        return $projects;
    }

    private function getProjectCountForYear($year)
    {
        // Current year: more projects
        if ($year->is_current) {
            return rand(12, 18);
        }
        // Past years: moderate number
        elseif ($year->end_date < now()) {
            return rand(8, 12);
        }
        // Future years: fewer projects
        else {
            return rand(5, 8);
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
            'Cash Relief' => [
                'নগদ সহায়তা প্রকল্প',
                'জরুরি নগদ ত্রাণ',
                'দরিদ্র পরিবার সহায়তা',
                'বেকার যুবক সহায়তা',
                'অসহায় মহিলা সহায়তা'
            ],
            'Food Relief' => [
                'খাদ্য নিরাপত্তা প্রকল্প',
                'জরুরি খাদ্য ত্রাণ',
                'দরিদ্র পরিবার খাদ্য সহায়তা',
                'খাদ্য সংকট মোকাবেলা',
                'পুষ্টি সহায়তা প্রকল্প'
            ],
            'Medical Relief' => [
                'চিকিৎসা সহায়তা প্রকল্প',
                'জরুরি চিকিৎসা ত্রাণ',
                'দরিদ্র পরিবার চিকিৎসা সহায়তা',
                'ঔষধ সহায়তা প্রকল্প',
                'স্বাস্থ্য সুরক্ষা প্রকল্প'
            ],
            'Shelter Relief' => [
                'আশ্রয় সহায়তা প্রকল্প',
                'গৃহহীন পরিবার সহায়তা',
                'আবাসন সহায়তা প্রকল্প',
                'আশ্রয় সামগ্রী বিতরণ',
                'পুনর্বাসন সহায়তা'
            ],
            'Educational Relief' => [
                'শিক্ষা সহায়তা প্রকল্প',
                'দরিদ্র শিক্ষার্থী সহায়তা',
                'শিক্ষা উপকরণ বিতরণ',
                'স্কুল ফি সহায়তা',
                'শিক্ষা উন্নয়ন প্রকল্প'
            ],
            'Winter Relief' => [
                'শীতকালীন সহায়তা প্রকল্প',
                'শীতবস্ত্র বিতরণ',
                'দরিদ্র পরিবার শীতকালীন সহায়তা',
                'কম্বল বিতরণ প্রকল্প',
                'শীতকালীন ত্রাণ'
            ],
            'Flood Relief' => [
                'বন্যা ত্রাণ প্রকল্প',
                'বন্যা কবলিত এলাকা সহায়তা',
                'বন্যা পুনর্বাসন প্রকল্প',
                'জরুরি বন্যা ত্রাণ',
                'বন্যা ক্ষতি পূরণ'
            ],
            'Cyclone Relief' => [
                'ঘূর্ণিঝড় ত্রাণ প্রকল্প',
                'ঘূর্ণিঝড় কবলিত এলাকা সহায়তা',
                'ঘূর্ণিঝড় পুনর্বাসন',
                'জরুরি ঘূর্ণিঝড় ত্রাণ',
                'ঘূর্ণিঝড় ক্ষতি পূরণ'
            ],
            'Drought Relief' => [
                'খরা ত্রাণ প্রকল্প',
                'খরা কবলিত কৃষক সহায়তা',
                'কৃষি সহায়তা প্রকল্প',
                'জল সংকট মোকাবেলা',
                'খরা পুনরুদ্ধার'
            ],
            'Emergency Relief' => [
                'জরুরি ত্রাণ প্রকল্প',
                'দুর্যোগ ত্রাণ',
                'জরুরি সাড়াদান',
                'দুর্যোগ ব্যবস্থাপনা',
                'জরুরি সহায়তা'
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
            'Cash Relief' => 5000000,
            'Food Relief' => 8000000,
            'Medical Relief' => 3000000,
            'Shelter Relief' => 10000000,
            'Educational Relief' => 4000000,
            'Winter Relief' => 3000000,
            'Flood Relief' => 12000000,
            'Cyclone Relief' => 10000000,
            'Drought Relief' => 6000000,
            'Emergency Relief' => 7000000,
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
            'Cash Relief' => 'দরিদ্র ও অসহায় পরিবারগুলোর জন্য নগদ সহায়তা প্রদান।',
            'Food Relief' => 'খাদ্য সংকটে পড়া পরিবারগুলোর জন্য খাদ্য সামগ্রী সরবরাহ।',
            'Medical Relief' => 'চিকিৎসা ব্যয় বহনে অক্ষম পরিবারগুলোর জন্য চিকিৎসা সহায়তা।',
            'Shelter Relief' => 'আবাসন সংকটে পড়া পরিবারগুলোর জন্য আশ্রয় সামগ্রী প্রদান।',
            'Educational Relief' => 'দরিদ্র শিক্ষার্থীদের শিক্ষা ব্যয়ের জন্য সহায়তা প্রদান।',
            'Winter Relief' => 'শীতকালে দরিদ্র পরিবারগুলোর জন্য শীতবস্ত্র ও কম্বল প্রদান।',
            'Flood Relief' => 'বন্যা কবলিত এলাকার পরিবারগুলোর জন্য জরুরি ত্রাণ সহায়তা।',
            'Cyclone Relief' => 'ঘূর্ণিঝড় কবলিত এলাকার পরিবারগুলোর জন্য পুনর্বাসন সহায়তা।',
            'Drought Relief' => 'খরা কবলিত কৃষক পরিবারগুলোর জন্য কৃষি সহায়তা।',
            'Emergency Relief' => 'জরুরি পরিস্থিতিতে ক্ষতিগ্রস্ত পরিবারগুলোর জন্য দ্রুত সহায়তা।',
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
