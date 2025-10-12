<?php

namespace Database\Seeders;

use App\Models\ReliefApplication;
use App\Models\ReliefApplicationItem;
use App\Models\ReliefItem;
use App\Models\Project;
use App\Models\OrganizationType;
use App\Models\Zilla;
use App\Models\Upazila;
use App\Models\Union;
use App\Models\Ward;
use App\Models\User;
use App\Models\ReliefType;
use App\Models\EconomicYear;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ComprehensiveApplicationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creating comprehensive relief applications for all economic years...');

        // Get all economic years
        $economicYears = EconomicYear::all();
        $projects = Project::all();
        $reliefItems = ReliefItem::all();
        $organizationTypes = OrganizationType::all();
        $zillas = Zilla::all();
        $upazilas = Upazila::all();
        $unions = Union::all();
        $wards = Ward::all();
        $users = User::whereHas('roles', function($q) {
            $q->whereIn('name', ['data-entry', 'user']);
        })->get();
        $reliefTypes = ReliefType::all();

        if ($economicYears->isEmpty() || $projects->isEmpty() || $reliefItems->isEmpty() || 
            $organizationTypes->isEmpty() || $zillas->isEmpty() || $upazilas->isEmpty() || 
            $unions->isEmpty() || $wards->isEmpty() || $users->isEmpty()) {
            $this->command->warn('Required data not found. Please run other seeders first.');
            return;
        }

        $createdCount = 0;

        // Create applications for each economic year (only current and previous years, not future)
        foreach ($economicYears as $year) {
            // Skip future years - only create applications for current and previous years
            if ($year->end_date > now()) {
                continue;
            }
            
            $yearProjects = $projects->where('economic_year_id', $year->id);
            $this->command->info("Creating applications for economic year: {$year->name}");
            
            foreach ($yearProjects as $project) {
                $applicationsForProject = $this->generateApplicationsForProject(
                    $project, $year, $reliefItems, $organizationTypes, 
                    $zillas, $upazilas, $unions, $wards, $users, $reliefTypes
                );
                
                foreach ($applicationsForProject as $applicationData) {
                    $application = ReliefApplication::create($applicationData);
                    
                    // Add relief items for non-cash applications
                    $cashReliefTypeIds = $reliefTypes->whereIn('name', ['Cash Assistance', 'District Commissioner Assistance'])->pluck('id');
                    if (!$cashReliefTypeIds->contains($applicationData['relief_type_id'])) {
                        $this->addReliefItems($application, $reliefItems);
                    }
                    
                    $createdCount++;
                }
            }
        }

        $this->command->info("Created {$createdCount} comprehensive relief applications across all economic years.");
    }

    private function generateApplicationsForProject($project, $year, $reliefItems, $organizationTypes, 
                                                   $zillas, $upazilas, $unions, $wards, $users, $reliefTypes)
    {
        $applications = [];
        $reliefType = $project->reliefType;
        
        // Generate different number of applications based on project type and year
        $numApplications = $this->getApplicationCountForProject($project, $year);
        
        for ($i = 0; $i < $numApplications; $i++) {
            $upazila = $upazilas->random();
            $union = $unions->where('upazila_id', $upazila->id)->first() ?? $unions->random();
            $ward = $wards->where('union_id', $union->id)->first() ?? $wards->random();
            $organizationType = $organizationTypes->random();
            $user = $users->random();
            
            // Generate application date within the economic year
            $applicationDate = $this->generateDateInYear($year);
            
            // Determine status based on year and application date
            $status = $this->determineApplicationStatus($year, $applicationDate);
            
            // Generate realistic amounts based on relief type
            $amountRequested = $this->generateAmountRequested($reliefType);
            $approvedAmount = $this->generateApprovedAmount($status, $amountRequested, $reliefType);
            
            $applicationData = [
                'organization_name' => $this->generateOrganizationName($organizationType, $upazila),
                'organization_type_id' => $organizationType->id,
                'date' => $applicationDate,
                'zilla_id' => $zillas->first()->id,
                'upazila_id' => $upazila->id,
                'union_id' => $union->id,
                'ward_id' => $ward->id,
                'subject' => $this->generateSubject($reliefType, $upazila),
                'relief_type_id' => $reliefType->id,
                'project_id' => $project->id,
                'applicant_name' => $this->generateApplicantName(),
                'applicant_designation' => $this->generateDesignation(),
                'applicant_phone' => $this->generatePhoneNumber(),
                'applicant_address' => $this->generateAddress($upazila, $union),
                'organization_address' => $this->generateOrganizationAddress($upazila),
                'amount_requested' => $amountRequested,
                'details' => $this->generateDetails($reliefType, $upazila),
                'status' => $status,
                'created_by' => $user->id,
                'created_at' => $applicationDate,
            ];
            
            // Add approval/rejection data if applicable
            if ($status === 'approved') {
                $applicationData['approved_amount'] = $approvedAmount;
                $applicationData['admin_remarks'] = $this->generateApprovalRemarks();
                $applicationData['approved_by'] = User::whereHas('roles', function($q) { 
                    $q->where('name', 'district-admin'); 
                })->first()->id;
                $applicationData['approved_at'] = $applicationDate->copy()->addDays(rand(1, 30));
            } elseif ($status === 'rejected') {
                $applicationData['admin_remarks'] = $this->generateRejectionRemarks();
                $applicationData['rejected_by'] = User::whereHas('roles', function($q) { 
                    $q->where('name', 'district-admin'); 
                })->first()->id;
                $applicationData['rejected_at'] = $applicationDate->copy()->addDays(rand(1, 15));
            }
            
            $applications[] = $applicationData;
        }
        
        return $applications;
    }

    private function getApplicationCountForProject($project, $year)
    {
        // More applications for current year (around 200 total for current year)
        if ($year->is_current) {
            // 7 relief types * 5 projects * ~6 applications = ~210 applications for current year
            return rand(5, 8);
        } else {
            // Previous years: moderate applications
            return rand(8, 15);
        }
    }

    private function generateDateInYear($year)
    {
        $startDate = Carbon::parse($year->start_date);
        $endDate = Carbon::parse($year->end_date);
        
        // Don't generate dates in the future or beyond MySQL's date limit (2038-01-19)
        $maxDate = Carbon::parse('2038-01-19');
        if ($endDate->isFuture() || $endDate->gt($maxDate)) {
            $endDate = now();
        }
        
        // Ensure start date is not beyond max date
        if ($startDate->gt($maxDate)) {
            $startDate = $maxDate->copy()->subYear();
        }
        
        return Carbon::createFromTimestamp(rand($startDate->timestamp, $endDate->timestamp));
    }

    private function determineApplicationStatus($year, $applicationDate)
    {
        // Current year: mix of all statuses
        if ($year->is_current) {
            $statuses = ['pending' => 30, 'approved' => 50, 'rejected' => 20];
        }
        // Past years: mostly approved/rejected, few pending
        else {
            $statuses = ['pending' => 5, 'approved' => 70, 'rejected' => 25];
        }
        
        $rand = rand(1, 100);
        $cumulative = 0;
        
        foreach ($statuses as $status => $percentage) {
            $cumulative += $percentage;
            if ($rand <= $cumulative) {
                return $status;
            }
        }
        
        return 'pending';
    }

    private function generateAmountRequested($reliefType)
    {
        return match($reliefType->name) {
            'Food Assistance for Destitute' => rand(50000, 500000),
            'Cash Assistance' => rand(20000, 200000),
            'Grain Assistance' => rand(40000, 400000),
            'Winter Clothing Assistance' => rand(30000, 300000),
            'Corrugated Iron Sheet Assistance' => rand(40000, 400000),
            'Housing Assistance' => rand(60000, 600000),
            'District Commissioner Assistance' => rand(80000, 800000),
            default => rand(25000, 250000)
        };
    }

    private function generateApprovedAmount($status, $requested, $reliefType)
    {
        if ($status !== 'approved') {
            return null;
        }
        
        // Approved amount is usually 70-100% of requested
        $percentage = rand(70, 100) / 100;
        return round($requested * $percentage, 2);
    }

    private function generateOrganizationName($organizationType, $upazila)
    {
        $orgNames = [
            'Madrasa' => ['আল-আমিন মাদ্রাসা', 'দারুল উলুম মাদ্রাসা', 'ইসলামিক একাডেমি', 'আল-জামিয়া মাদ্রাসা'],
            'Mosque' => ['কেন্দ্রীয় জামে মসজিদ', 'নতুন মসজিদ', 'বড় মসজিদ', 'জামে মসজিদ'],
            'Temple' => ['শ্রী শ্রী কালী মন্দির', 'হিন্দু ধর্মীয় মন্দির', 'রাধা কৃষ্ণ মন্দির', 'শিব মন্দির'],
            'NGO' => ['ব্র্যাক', 'গ্রামীণ ব্যাংক', 'প্রশিকা', 'আশা', 'কারিতাস'],
            'Old Home' => ['বৃদ্ধাশ্রম', 'সিনিয়র সিটিজেন হোম', 'বৃদ্ধ নিবাস', 'এজড কেয়ার সেন্টার'],
            'Educational Institute' => ['প্রাথমিক বিদ্যালয়', 'মাধ্যমিক বিদ্যালয়', 'কলেজ', 'বিদ্যালয়'],
            'Charitable Foundation' => ['মানবিক ফাউন্ডেশন', 'সামাজিক উন্নয়ন ফাউন্ডেশন', 'দাতব্য ফাউন্ডেশন'],
            'Social Welfare Organization' => ['সামাজিক কল্যাণ সংস্থা', 'মানবিক সহায়তা ফাউন্ডেশন'],
            'Community Organization' => ['সমাজ কল্যাণ সমিতি', 'নারী উন্নয়ন সংস্থা', 'যুব সংগঠন', 'কৃষক সমিতি'],
            'Religious Organization' => ['ইসলামিক রিলিফ কমিটি', 'হিন্দু কল্যাণ ট্রাস্ট', 'খ্রিস্টান সেবা সমিতি'],
        ];
        
        $typeName = $organizationType->name;
        $names = $orgNames[$typeName] ?? ['সামাজিক সংগঠন'];
        $baseName = $names[array_rand($names)];
        
        return $baseName . ' - ' . $upazila->name_bn;
    }

    private function generateSubject($reliefType, $upazila)
    {
        $subjects = [
            'Food Assistance for Destitute' => 'দুঃস্থদের খাদ্য সহায়তা',
            'Cash Assistance' => 'জরুরি নগদ সহায়তা',
            'Grain Assistance' => 'খাদ্যশস্য সহায়তা',
            'Winter Clothing Assistance' => 'শীতকালীন সহায়তা',
            'Corrugated Iron Sheet Assistance' => 'ঢেউটিন সহায়তা',
            'Housing Assistance' => 'গৃহবাবদ সহায়তা',
            'District Commissioner Assistance' => 'জেলা প্রশাসকের সাহায্য',
        ];
        
        $baseSubject = $subjects[$reliefType->name] ?? 'ত্রাণ সহায়তা';
        return $upazila->name_bn . ' এলাকার ' . $baseSubject;
    }

    private function generateApplicantName()
    {
        $names = [
            'মোঃ আব্দুল হক', 'মোঃ করিম উদ্দিন', 'মোঃ শহিদুল ইসলাম', 'মোঃ নজরুল ইসলাম',
            'মোঃ হাসান আলী', 'মোঃ রফিকুল ইসলাম', 'মোঃ জাহিদুল ইসলাম', 'মোঃ আনোয়ার হোসেন',
            'ফাতেমা খাতুন', 'রোকসানা বেগম', 'সালেহা খাতুন', 'শাহানা পারভীন',
            'রেহানা বেগম', 'নাসিরা খাতুন', 'ডাঃ রোকেয়া খাতুন', 'মুফতি আব্দুল হাই'
        ];
        
        return $names[array_rand($names)];
    }

    private function generateDesignation()
    {
        $designations = [
            'সভাপতি', 'সচিব', 'নির্বাহী পরিচালক', 'সহ-সভাপতি', 'কোষাধ্যক্ষ',
            'সদস্য সচিব', 'প্রকল্প সমন্বয়কারী', 'ক্ষেত্র সমন্বয়কারী'
        ];
        
        return $designations[array_rand($designations)];
    }

    private function generatePhoneNumber()
    {
        return '017' . rand(10000000, 99999999);
    }

    private function generateAddress($upazila, $union)
    {
        return 'গ্রাম: ' . fake()->streetName() . ', পোস্ট: ' . $union->name_bn . ', উপজেলা: ' . $upazila->name_bn . ', জেলা: বগুড়া';
    }

    private function generateOrganizationAddress($upazila)
    {
        return 'অফিস: ' . $upazila->name_bn . ' উপজেলা, বগুড়া';
    }

    private function generateDetails($reliefType, $upazila)
    {
        $details = [
            'Food Assistance for Destitute' => 'আমাদের এলাকার দুঃস্থ ও দরিদ্র পরিবারগুলোকে জরুরি খাদ্য সহায়তা প্রয়োজন। চাল, ডাল ও অন্যান্য খাদ্য সামগ্রী প্রদানের জন্য।',
            'Cash Assistance' => 'আমাদের এলাকার দরিদ্র পরিবারগুলোকে জরুরি নগদ সহায়তা প্রয়োজন। বিশেষ করে বেকার যুবক ও অসহায় মহিলাদের জন্য।',
            'Grain Assistance' => 'কৃষক পরিবারগুলোকে খাদ্যশস্য সহায়তা প্রয়োজন। বিশেষ করে দরিদ্র কৃষকদের জন্য চাল ও বীজ সহায়তা।',
            'Winter Clothing Assistance' => 'শীতকালে দরিদ্র পরিবারগুলোকে শীতবস্ত্র ও কম্বল প্রদানের জন্য সহায়তা প্রয়োজন।',
            'Corrugated Iron Sheet Assistance' => 'আবাসন নির্মাণের জন্য ঢেউটিন সহায়তা প্রয়োজন। বিশেষ করে গৃহহীন পরিবারদের জন্য।',
            'Housing Assistance' => 'গৃহহীন পরিবারগুলোকে আবাসন সহায়তা প্রয়োজন। ঘর নির্মাণ সামগ্রী প্রদানের জন্য সহায়তা প্রয়োজন।',
            'District Commissioner Assistance' => 'জেলা প্রশাসকের বিশেষ সহায়তা প্রয়োজন। বিভিন্ন ধরনের জরুরি সহায়তা প্রদানের জন্য।',
        ];
        
        $baseDetail = $details[$reliefType->name] ?? 'ত্রাণ সহায়তা প্রয়োজন।';
        return $upazila->name_bn . ' উপজেলায় ' . $baseDetail;
    }

    private function generateApprovalRemarks()
    {
        $remarks = [
            'অনুমোদিত - প্রকল্পের সাথে সামঞ্জস্যপূর্ণ',
            'অনুমোদিত - বাজেট সীমার মধ্যে',
            'অনুমোদিত - জরুরি প্রয়োজনের জন্য',
            'অনুমোদিত - দরিদ্র পরিবারের জন্য',
            'অনুমোদিত - সামাজিক কল্যাণের জন্য'
        ];
        
        return $remarks[array_rand($remarks)];
    }

    private function generateRejectionRemarks()
    {
        $remarks = [
            'প্রত্যাখ্যাত - বাজেট সীমাবদ্ধতা',
            'প্রত্যাখ্যাত - প্রকল্পের সাথে সামঞ্জস্যপূর্ণ নয়',
            'প্রত্যাখ্যাত - প্রয়োজনীয় কাগজপত্র অসম্পূর্ণ',
            'প্রত্যাখ্যাত - এলাকায় অন্য প্রকল্প চলমান',
            'প্রত্যাখ্যাত - অগ্রাধিকার ভিত্তিতে বিবেচনা করা হবে'
        ];
        
        return $remarks[array_rand($remarks)];
    }

    private function addReliefItems($application, $reliefItems)
    {
        $reliefType = $application->reliefType;
        
        if ($reliefType && !in_array($reliefType->name, ['Cash Assistance', 'District Commissioner Assistance'])) {
            $relevantItems = $reliefItems->where('type', $this->getItemTypeFromReliefType($reliefType->name));
            
            if ($relevantItems->count() > 0) {
                $item = $relevantItems->random();
                $quantityRequested = rand(10, 100);
                $quantityApproved = ($application->status === 'approved') ? rand(5, $quantityRequested) : 0;
                $unitPrice = $this->getUnitPriceForItem($item->type);

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

    private function getItemTypeFromReliefType($reliefTypeName)
    {
        return match($reliefTypeName) {
            'Food Assistance for Destitute', 'Grain Assistance' => 'food',
            'Winter Clothing Assistance', 'Corrugated Iron Sheet Assistance', 'Housing Assistance' => 'shelter',
            'Cash Assistance', 'District Commissioner Assistance' => 'other',
            default => 'food'
        };
    }

    private function getUnitPriceForItem($itemType)
    {
        return match($itemType) {
            'food' => rand(20, 150),
            'medical' => rand(500, 2000),
            'shelter' => rand(300, 5000),
            'other' => rand(100, 1000),
            default => rand(50, 500)
        };
    }
}
