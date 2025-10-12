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
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class BanglaApplicationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creating comprehensive Bangla relief applications...');

        // Get required data
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

        if ($projects->isEmpty() || $reliefItems->isEmpty() || $organizationTypes->isEmpty() || 
            $zillas->isEmpty() || $upazilas->isEmpty() || $unions->isEmpty() || $wards->isEmpty() || $users->isEmpty()) {
            $this->command->warn('Required data not found. Please run other seeders first.');
            return;
        }

        $createdCount = 0;

        // Create comprehensive relief applications
        $applications = [
            // Flood Relief Applications
            [
                'organization_name' => 'বগুড়া বন্যা ত্রাণ কমিটি',
                'organization_name_en' => 'Bogura Flood Relief Committee',
                'organization_type_id' => $organizationTypes->where('name', 'Community Organization')->first()->id,
                'date' => Carbon::now()->subDays(15),
                'zilla_id' => $zillas->first()->id,
                'upazila_id' => $upazilas->first()->id,
                'union_id' => $unions->where('upazila_id', $upazilas->first()->id)->first()->id,
                'ward_id' => $wards->where('union_id', $unions->where('upazila_id', $upazilas->first()->id)->first()->id)->first()->id,
                'subject' => 'বন্যা কবলিত পরিবারগুলোর জরুরি খাদ্য সহায়তা',
                'subject_en' => 'Emergency Food Assistance for Flood Affected Families',
                'relief_type_id' => $reliefTypes->where('name', 'Food Assistance for Destitute')->first()->id ?? $reliefTypes->first()->id,
                'project_id' => $projects->where('name', 'like', '%বন্যা%')->first()->id ?? $projects->first()->id,
                'applicant_name' => 'মোঃ আব্দুল হক',
                'applicant_name_en' => 'Md. Abdul Haque',
                'applicant_designation' => 'সভাপতি',
                'applicant_designation_en' => 'President',
                'applicant_phone' => '01712345678',
                'applicant_address' => 'গ্রাম: চরপাড়া, পোস্ট: সারিয়াকান্দি, জেলা: বগুড়া',
                'organization_address' => 'অফিস: সারিয়াকান্দি উপজেলা, বগুড়া',
                'amount_requested' => 500000.00,
                'details' => 'আমাদের এলাকায় প্রায় ২০০টি পরিবার বন্যার কবলে পড়েছে। তাদের জরুরি খাদ্য সহায়তা প্রয়োজন। বিশেষ করে শিশু ও বৃদ্ধদের জন্য চাল, ডাল ও অন্যান্য খাদ্য সামগ্রী প্রয়োজন।',
                'details_en' => 'About 200 families in our area are affected by floods. They need emergency food assistance. Especially rice, lentils and other food items are needed for children and elderly.',
                'status' => 'approved',
                'approved_amount' => 450000.00,
                'admin_remarks' => 'বন্যা কবলিত এলাকার জন্য অনুমোদিত। বাজেট সীমাবদ্ধতার কারণে কিছুটা কম পরিমাণে অনুমোদন দেওয়া হয়েছে।',
                'created_by' => $users->first()->id,
                'approved_by' => User::whereHas('roles', function($q) { $q->where('name', 'district-admin'); })->first()->id,
                'approved_at' => Carbon::now()->subDays(10),
            ],
            [
                'organization_name' => 'শিবগঞ্জ উন্নয়ন সংস্থা',
                'organization_name_en' => 'Shibganj Development Organization',
                'organization_type_id' => $organizationTypes->where('name', 'NGO')->first()->id,
                'date' => Carbon::now()->subDays(20),
                'zilla_id' => $zillas->first()->id,
                'upazila_id' => $upazilas->first()->id,
                'union_id' => $unions->first()->id,
                'ward_id' => $wards->first()->id,
                'subject' => 'চরাঞ্চলের বন্যা কবলিত পরিবারদের আশ্রয় সহায়তা',
                'subject_en' => 'Shelter Assistance for Char Area Flood Affected Families',
                'relief_type_id' => $reliefTypes->where('name', 'Corrugated Iron Sheet Assistance')->first()->id ?? $reliefTypes->skip(1)->first()->id,
                'project_id' => $projects->where('name', 'like', '%আশ্রয়%')->first()->id ?? $projects->skip(1)->first()->id,
                'applicant_name' => 'ফাতেমা খাতুন',
                'applicant_name_en' => 'Fatema Khatun',
                'applicant_designation' => 'নির্বাহী পরিচালক',
                'applicant_designation_en' => 'Executive Director',
                'applicant_phone' => '01787654321',
                'applicant_address' => 'গ্রাম: নদীর চর, পোস্ট: শিবগঞ্জ, জেলা: বগুড়া',
                'organization_address' => 'অফিস: শিবগঞ্জ উপজেলা, বগুড়া',
                'amount_requested' => 300000.00,
                'details' => 'চরাঞ্চলে বন্যার কবলে পড়া পরিবারগুলোর জন্য ঢেউটিন ও অন্যান্য আশ্রয় সামগ্রী প্রয়োজন। প্রায় ১৫০টি পরিবার আশ্রয়হীন হয়ে পড়েছে।',
                'details_en' => 'Corrugated iron sheets and other shelter materials are needed for families affected by floods in char areas. About 150 families have become homeless.',
                'status' => 'pending',
                'created_by' => $users->skip(1)->first()->id,
            ],

            // Cyclone Relief Applications
            [
                'organization_name' => 'ঘূর্ণিঝড় প্রস্তুতি সমিতি',
                'organization_name_en' => 'Cyclone Preparedness Society',
                'organization_type_id' => $organizationTypes->where('name', 'Community Organization')->first()->id,
                'date' => Carbon::now()->subDays(25),
                'zilla_id' => $zillas->first()->id,
                'upazila_id' => $upazilas->first()->id,
                'union_id' => $unions->first()->id,
                'ward_id' => $wards->first()->id,
                'subject' => 'ঘূর্ণিঝড় প্রস্তুতি সামগ্রী ও জরুরি ত্রাণ',
                'subject_en' => 'Cyclone Preparedness Materials and Emergency Relief',
                'relief_type_id' => $reliefTypes->where('name', 'Cash Assistance')->first()->id ?? $reliefTypes->skip(2)->first()->id,
                'project_id' => $projects->where('name', 'like', '%ঘূর্ণিঝড়%')->first()->id ?? $projects->skip(2)->first()->id,
                'applicant_name' => 'মোঃ শহিদুল ইসলাম',
                'applicant_name_en' => 'Md. Shahidul Islam',
                'applicant_designation' => 'সভাপতি',
                'applicant_designation_en' => 'President',
                'applicant_phone' => '01723456789',
                'applicant_address' => 'গ্রাম: নদীর পাড়, পোস্ট: সারিয়াকান্দি, জেলা: বগুড়া',
                'organization_address' => 'অফিস: সারিয়াকান্দি উপজেলা, বগুড়া',
                'amount_requested' => 400000.00,
                'details' => 'ঘূর্ণিঝড়ের পূর্বে প্রস্তুতি ও পরবর্তীতে পুনর্বাসনের জন্য নগদ সহায়তা প্রয়োজন। বিশেষ করে চরাঞ্চলের পরিবারগুলো ঝুঁকিতে রয়েছে।',
                'details_en' => 'Cash assistance is needed for cyclone preparedness and post-cyclone rehabilitation. Especially families in char areas are at risk.',
                'status' => 'approved',
                'approved_amount' => 350000.00,
                'admin_remarks' => 'ঘূর্ণিঝড় প্রস্তুতির জন্য গুরুত্বপূর্ণ প্রকল্প হিসেবে অনুমোদিত।',
                'created_by' => $users->skip(2)->first()->id,
                'approved_by' => User::whereHas('roles', function($q) { $q->where('name', 'district-admin'); })->first()->id,
                'approved_at' => Carbon::now()->subDays(18),
            ],

            // Drought Relief Applications
            [
                'organization_name' => 'বগুড়া কৃষক সমিতি',
                'organization_name_en' => 'Bogura Farmers Association',
                'organization_type_id' => $organizationTypes->where('name', 'Community Organization')->first()->id,
                'date' => Carbon::now()->subDays(30),
                'zilla_id' => $zillas->first()->id,
                'upazila_id' => $upazilas->first()->id,
                'union_id' => $unions->first()->id,
                'ward_id' => $wards->first()->id,
                'subject' => 'খরা কবলিত কৃষকদের বীজ সহায়তা',
                'subject_en' => 'Seed Assistance for Drought Affected Farmers',
                'relief_type_id' => $reliefTypes->where('name', 'Cash Assistance')->first()->id ?? $reliefTypes->skip(3)->first()->id,
                'project_id' => $projects->where('name', 'like', '%খরা%')->first()->id ?? $projects->skip(3)->first()->id,
                'applicant_name' => 'মোঃ করিম উদ্দিন',
                'applicant_name_en' => 'Md. Karim Uddin',
                'applicant_designation' => 'সভাপতি',
                'applicant_designation_en' => 'President',
                'applicant_phone' => '01734567890',
                'applicant_address' => 'গ্রাম: কৃষি এলাকা, পোস্ট: ধুনট, জেলা: বগুড়া',
                'organization_address' => 'অফিস: ধুনট উপজেলা, বগুড়া',
                'amount_requested' => 250000.00,
                'details' => 'খরা কবলিত কৃষকদের জন্য বীজ ক্রয়ের নগদ সহায়তা প্রয়োজন। প্রায় ১০০ জন কৃষক ক্ষতিগ্রস্ত হয়েছে।',
                'details_en' => 'Cash assistance is needed for seed purchase for drought affected farmers. About 100 farmers have been affected.',
                'status' => 'pending',
                'created_by' => $users->skip(3)->first()->id,
            ],

            // Winter Relief Applications
            [
                'organization_name' => 'শীতকালীন ত্রাণ কমিটি',
                'organization_name_en' => 'Winter Relief Committee',
                'organization_type_id' => $organizationTypes->where('name', 'Social Welfare Organization')->first()->id,
                'date' => Carbon::now()->subDays(35),
                'zilla_id' => $zillas->first()->id,
                'upazila_id' => $upazilas->first()->id,
                'union_id' => $unions->first()->id,
                'ward_id' => $wards->first()->id,
                'subject' => 'দরিদ্র পরিবারদের শীতকালীন কম্বল সহায়তা',
                'subject_en' => 'Winter Blanket Assistance for Poor Families',
                'relief_type_id' => $reliefTypes->where('name', 'Winter Clothing Assistance')->first()->id ?? $reliefTypes->skip(4)->first()->id,
                'project_id' => $projects->where('name', 'like', '%শীতকালীন%')->first()->id ?? $projects->skip(4)->first()->id,
                'applicant_name' => 'রোকসানা বেগম',
                'applicant_name_en' => 'Roksana Begum',
                'applicant_designation' => 'সচিব',
                'applicant_designation_en' => 'Secretary',
                'applicant_phone' => '01745678901',
                'applicant_address' => 'গ্রাম: শহর এলাকা, পোস্ট: বগুড়া সদর, জেলা: বগুড়া',
                'organization_address' => 'অফিস: বগুড়া সদর উপজেলা, বগুড়া',
                'amount_requested' => 200000.00,
                'details' => 'শীতকালে দরিদ্র পরিবারগুলোকে কম্বল প্রদানের জন্য সহায়তা প্রয়োজন। বিশেষ করে শিশু ও বৃদ্ধদের অগ্রাধিকার দেওয়া হবে।',
                'details_en' => 'Assistance is needed to provide blankets to poor families during winter. Priority will be given to children and elderly.',
                'status' => 'approved',
                'approved_amount' => 180000.00,
                'admin_remarks' => 'শীতকালীন ত্রাণ হিসেবে অনুমোদিত।',
                'created_by' => $users->skip(4)->first()->id,
                'approved_by' => User::whereHas('roles', function($q) { $q->where('name', 'district-admin'); })->first()->id,
                'approved_at' => Carbon::now()->subDays(25),
            ],

            // Medical Relief Applications
            [
                'organization_name' => 'ইসলামিক রিলিফ কমিটি',
                'organization_name_en' => 'Islamic Relief Committee',
                'organization_type_id' => $organizationTypes->where('name', 'Religious Organization')->first()->id,
                'date' => Carbon::now()->subDays(40),
                'zilla_id' => $zillas->first()->id,
                'upazila_id' => $upazilas->first()->id,
                'union_id' => $unions->first()->id,
                'ward_id' => $wards->first()->id,
                'subject' => 'দরিদ্র পরিবারদের জরুরি চিকিৎসা সহায়তা',
                'subject_en' => 'Emergency Medical Assistance for Poor Families',
                'relief_type_id' => $reliefTypes->where('name', 'Cash Assistance')->first()->id ?? $reliefTypes->skip(5)->first()->id,
                'project_id' => $projects->where('name', 'like', '%চিকিৎসা%')->first()->id ?? $projects->skip(5)->first()->id,
                'applicant_name' => 'মুফতি আব্দুল হাই',
                'applicant_name_en' => 'Mufti Abdul Hai',
                'applicant_designation' => 'সভাপতি',
                'applicant_designation_en' => 'President',
                'applicant_phone' => '01756789012',
                'applicant_address' => 'গ্রাম: মসজিদ পাড়া, পোস্ট: গাবতলী, জেলা: বগুড়া',
                'organization_address' => 'অফিস: গাবতলী উপজেলা, বগুড়া',
                'amount_requested' => 150000.00,
                'details' => 'দরিদ্র পরিবারগুলোর জরুরি চিকিৎসা ব্যয়ের জন্য নগদ সহায়তা প্রয়োজন। বিশেষ করে শিশু ও বৃদ্ধদের চিকিৎসা ব্যয়।',
                'details_en' => 'Cash assistance is needed for emergency medical expenses of poor families. Especially medical expenses for children and elderly.',
                'status' => 'rejected',
                'admin_remarks' => 'বাজেট সীমাবদ্ধতার কারণে বর্তমানে অনুমোদন দেওয়া সম্ভব নয়। পরবর্তীতে বিবেচনা করা হবে।',
                'created_by' => $users->skip(5)->first()->id,
                'rejected_by' => User::whereHas('roles', function($q) { $q->where('name', 'district-admin'); })->first()->id,
                'rejected_at' => Carbon::now()->subDays(30),
            ],

            // Educational Relief Applications
            [
                'organization_name' => 'তরুণ সমাজ উন্নয়ন সংগঠন',
                'organization_name_en' => 'Youth Social Development Organization',
                'organization_type_id' => $organizationTypes->where('name', 'Community Organization')->first()->id,
                'date' => Carbon::now()->subDays(45),
                'zilla_id' => $zillas->first()->id,
                'upazila_id' => $upazilas->first()->id,
                'union_id' => $unions->first()->id,
                'ward_id' => $wards->first()->id,
                'subject' => 'দরিদ্র শিক্ষার্থীদের শিক্ষা সহায়তা',
                'subject_en' => 'Educational Assistance for Poor Students',
                'relief_type_id' => $reliefTypes->where('name', 'Cash Assistance')->first()->id ?? $reliefTypes->skip(6)->first()->id,
                'project_id' => $projects->where('name', 'like', '%শিক্ষা%')->first()->id ?? $projects->skip(6)->first()->id,
                'applicant_name' => 'মোঃ সাকিব আহমেদ',
                'applicant_name_en' => 'Md. Sakib Ahmed',
                'applicant_designation' => 'সভাপতি',
                'applicant_designation_en' => 'President',
                'applicant_phone' => '01767890123',
                'applicant_address' => 'গ্রাম: যুব পাড়া, পোস্ট: নন্দীগ্রাম, জেলা: বগুড়া',
                'organization_address' => 'অফিস: নন্দীগ্রাম উপজেলা, বগুড়া',
                'amount_requested' => 100000.00,
                'details' => 'দরিদ্র পরিবারের শিক্ষার্থীদের স্কুল ফি, বই-খাতা ও অন্যান্য শিক্ষা সামগ্রী ক্রয়ের জন্য সহায়তা প্রয়োজন।',
                'details_en' => 'Assistance is needed for school fees, books, notebooks and other educational materials for students from poor families.',
                'status' => 'pending',
                'created_by' => $users->skip(6)->first()->id,
            ]
        ];

        foreach ($applications as $applicationData) {
            $application = ReliefApplication::create([
                'organization_name' => $applicationData['organization_name'],
                'organization_type_id' => $applicationData['organization_type_id'],
                'date' => $applicationData['date'],
                'zilla_id' => $applicationData['zilla_id'],
                'upazila_id' => $applicationData['upazila_id'],
                'union_id' => $applicationData['union_id'],
                'ward_id' => $applicationData['ward_id'],
                'subject' => $applicationData['subject'],
                'relief_type_id' => $applicationData['relief_type_id'],
                'project_id' => $applicationData['project_id'],
                'applicant_name' => $applicationData['applicant_name'],
                'applicant_designation' => $applicationData['applicant_designation'],
                'applicant_phone' => $applicationData['applicant_phone'],
                'applicant_address' => $applicationData['applicant_address'],
                'organization_address' => $applicationData['organization_address'],
                'amount_requested' => $applicationData['amount_requested'],
                'details' => $applicationData['details'],
                'status' => $applicationData['status'],
                'created_by' => $applicationData['created_by'],
                'created_at' => $applicationData['date'],
            ]);

            // Add approved/rejected data if applicable
            if (isset($applicationData['approved_amount'])) {
                $application->update([
                    'approved_amount' => $applicationData['approved_amount'],
                    'admin_remarks' => $applicationData['admin_remarks'],
                    'approved_by' => $applicationData['approved_by'],
                    'approved_at' => $applicationData['approved_at'],
                ]);
            }

            if (isset($applicationData['admin_remarks']) && $applicationData['status'] === 'rejected') {
                $application->update([
                    'admin_remarks' => $applicationData['admin_remarks'],
                    'rejected_by' => $applicationData['rejected_by'],
                    'rejected_at' => $applicationData['rejected_at'],
                ]);
            }

            // Add relief items for non-cash applications
            if ($applicationData['relief_type_id'] !== $reliefTypes->where('name', 'Cash Assistance')->first()?->id) {
                $this->addReliefItems($application, $reliefItems);
            }

            $createdCount++;
        }

        // Create additional applications with various statuses
        $this->createAdditionalApplications($projects, $reliefItems, $organizationTypes, $zillas, $upazilas, $unions, $wards, $users, $reliefTypes, $createdCount);

        $this->command->info("Created comprehensive Bangla relief applications with realistic data.");
        $this->command->info('Applications include flood relief, cyclone relief, drought relief, winter relief, medical relief, and educational relief.');
        $this->command->info('Applications have mixed statuses: approved, pending, and rejected with realistic amounts and details.');
    }

    private function addReliefItems($application, $reliefItems)
    {
        // Add relief items based on relief type
        $reliefType = $application->reliefType;
        
        if ($reliefType && $reliefType->name !== 'Cash') {
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
            'Rice', 'Wheat', 'Lentils', 'Cooking Oil' => 'food',
            'Corrugated Iron Sheet Bundle', 'Winter Clothes', 'Blanket' => 'shelter',
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

    private function createAdditionalApplications($projects, $reliefItems, $organizationTypes, $zillas, $upazilas, $unions, $wards, $users, $reliefTypes, &$createdCount)
    {
        // Create additional applications for testing various scenarios
        $additionalApplications = [
            [
                'organization_name' => 'বগুড়া মহিলা সমিতি',
                'organization_type_id' => $organizationTypes->where('name', 'Community Organization')->first()->id,
                'subject' => 'অসহায় মহিলাদের আয় বৃদ্ধি সহায়তা',
                'relief_type_id' => $reliefTypes->where('name', 'Cash Assistance')->first()->id,
                'applicant_name' => 'সালেহা খাতুন',
                'amount_requested' => 75000.00,
                'status' => 'approved',
                'approved_amount' => 60000.00,
            ],
            [
                'organization_name' => 'প্রশিকা উন্নয়ন সংস্থা',
                'organization_type_id' => $organizationTypes->where('name', 'NGO')->first()->id,
                'subject' => 'বৃদ্ধদের সামাজিক সহায়তা',
                'relief_type_id' => $reliefTypes->where('name', 'Cash Assistance')->first()->id,
                'applicant_name' => 'মোঃ হাসান আলী',
                'amount_requested' => 120000.00,
                'status' => 'pending',
            ],
            [
                'organization_name' => 'গ্রামীণ ব্যাংক',
                'organization_type_id' => $organizationTypes->where('name', 'NGO')->first()->id,
                'subject' => 'ক্ষুদ্র ব্যবসা সহায়তা',
                'relief_type_id' => $reliefTypes->where('name', 'Cash Assistance')->first()->id,
                'applicant_name' => 'শাহানা পারভীন',
                'amount_requested' => 200000.00,
                'status' => 'rejected',
                'admin_remarks' => 'প্রস্তাবিত প্রকল্পের সাথে সামঞ্জস্যপূর্ণ নয়।',
            ]
        ];

        foreach ($additionalApplications as $appData) {
            $upazila = $upazilas->random();
            $union = $unions->where('upazila_id', $upazila->id)->first() ?? $unions->random();
            $ward = $wards->where('union_id', $union->id)->first() ?? $wards->random();
            $project = $projects->random();
            $user = $users->random();

            $application = ReliefApplication::create([
                'organization_name' => $appData['organization_name'],
                'organization_type_id' => $appData['organization_type_id'],
                'date' => Carbon::now()->subDays(rand(1, 50)),
                'zilla_id' => $zillas->first()->id,
                'upazila_id' => $upazila->id,
                'union_id' => $union->id,
                'ward_id' => $ward->id,
                'subject' => $appData['subject'],
                'relief_type_id' => $appData['relief_type_id'],
                'project_id' => $project->id,
                'applicant_name' => $appData['applicant_name'],
                'applicant_designation' => 'সভাপতি',
                'applicant_phone' => '017' . rand(10000000, 99999999),
                'applicant_address' => 'গ্রাম: ' . fake()->streetName() . ', পোস্ট: ' . $upazila->name_bn . ', জেলা: বগুড়া',
                'organization_address' => 'অফিস: ' . $upazila->name_bn . ' উপজেলা, বগুড়া',
                'amount_requested' => $appData['amount_requested'],
                'details' => 'এই প্রকল্পের মাধ্যমে দরিদ্র পরিবারগুলোকে সহায়তা প্রদান করা হবে।',
                'status' => $appData['status'],
                'created_by' => $user->id,
            ]);

            if (isset($appData['approved_amount'])) {
                $application->update([
                    'approved_amount' => $appData['approved_amount'],
                    'admin_remarks' => 'অনুমোদিত',
                    'approved_by' => User::whereHas('roles', function($q) { $q->where('name', 'district-admin'); })->first()->id,
                    'approved_at' => Carbon::now()->subDays(rand(1, 30)),
                ]);
            }

            if (isset($appData['admin_remarks']) && $appData['status'] === 'rejected') {
                $application->update([
                    'admin_remarks' => $appData['admin_remarks'],
                    'rejected_by' => User::whereHas('roles', function($q) { $q->where('name', 'district-admin'); })->first()->id,
                    'rejected_at' => Carbon::now()->subDays(rand(1, 30)),
                ]);
            }

            $createdCount++;
        }
    }
}
