<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ReliefApplication;
use App\Models\ReliefType;
use App\Models\Project;
use App\Models\Zilla;
use App\Models\Upazila;
use App\Models\Union;
use App\Models\Ward;
use App\Models\OrganizationType;
use Carbon\Carbon;

class TestReliefApplicationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $testApplications = [
            // 1. Individual - Food Relief Application
            [
                'application_type' => 'individual',
                'applicant_name' => 'মোঃ আব্দুল হামিদ',
                'applicant_nid' => '1234567890123',
                'applicant_phone' => '01712345678',
                'applicant_address' => 'বাড়ি নং ১২৩, রোড নং ৪, বগুড়া সদর, বগুড়া',
                'organization_name' => null,
                'organization_address' => null,
                'organization_type_id' => null,
                'applicant_designation' => null,
                'relief_type_id' => 1, // খাদ্য সহায়তা
                'project_id' => 1, // দুঃস্থ পরিবারের জরুরি খাদ্য সহায়তা প্রকল্প
                'zilla_id' => 1,
                'upazila_id' => 1,
                'union_id' => 1,
                'ward_id' => 1,
                'subject' => 'দুঃস্থ পরিবারের জন্য জরুরি খাদ্য সহায়তা',
                'details' => 'আমি একজন দিনমজুর এবং আমার পরিবারে ৫ জন সদস্য আছে। করোনা মহামারির কারণে কাজ হারিয়েছি এবং পরিবারের খাদ্য নিরাপত্তা হুমকির সম্মুখীন। আমার স্ত্রী অসুস্থ এবং দুটি ছোট সন্তান আছে। সরকারের সাহায্য ছাড়া আমাদের বেঁচে থাকা কঠিন হয়ে পড়েছে।',
                'amount_requested' => 50.00, // 50 কেজি
                'date' => Carbon::now()->subDays(2),
                'status' => 'pending'
            ],

            // 2. Organization - Cash Relief Application
            [
                'application_type' => 'organization',
                'applicant_name' => 'মোঃ রফিকুল ইসলাম',
                'applicant_nid' => '2345678901234',
                'applicant_phone' => '01823456789',
                'applicant_address' => 'বাড়ি নং ৪৫৬, আদমদিঘী, বগুড়া',
                'organization_name' => 'আদমদিঘী দরিদ্র কল্যাণ সমিতি',
                'organization_address' => 'আদমদিঘী বাজার, আদমদিঘী, বগুড়া',
                'organization_type_id' => 8, // Social Welfare Organization
                'applicant_designation' => 'সভাপতি',
                'relief_type_id' => 2, // নগদ অর্থ সহায়তা
                'project_id' => 3, // দুঃস্থ পরিবারের জরুরি নগদ সহায়তা প্রকল্প
                'zilla_id' => 1,
                'upazila_id' => 2,
                'union_id' => 1,
                'ward_id' => 2,
                'subject' => 'দরিদ্র পরিবারগুলোর জন্য নগদ সহায়তা',
                'details' => 'আমাদের সমিতি আদমদিঘী এলাকায় ৫০টি দরিদ্র পরিবারের জন্য কাজ করে। এই পরিবারগুলো করোনা মহামারির কারণে চরম আর্থিক সংকটে পড়েছে। অনেক পরিবারের প্রধান আয়ের উৎস বন্ধ হয়ে গেছে। তাদের সন্তানদের শিক্ষা এবং চিকিৎসার জন্য জরুরি নগদ সহায়তা প্রয়োজন।',
                'amount_requested' => 100000.00, // ১ লক্ষ টাকা
                'date' => Carbon::now()->subDays(1),
                'status' => 'pending'
            ],

            // 3. Individual - Grain Relief Application
            [
                'application_type' => 'individual',
                'applicant_name' => 'মোছাঃ রোকসানা খাতুন',
                'applicant_nid' => '3456789012345',
                'applicant_phone' => '01934567890',
                'applicant_address' => 'গ্রাম: পাকুড়িয়া, ধুনট, বগুড়া',
                'organization_name' => null,
                'organization_address' => null,
                'organization_type_id' => null,
                'applicant_designation' => null,
                'relief_type_id' => 3, // খাদ্যশস্য সহায়তা
                'project_id' => 5, // কৃষক পরিবারের খাদ্যশস্য সহায়তা প্রকল্প
                'zilla_id' => 1,
                'upazila_id' => 3,
                'union_id' => 1,
                'ward_id' => 3,
                'subject' => 'কৃষক পরিবারের খাদ্যশস্য সহায়তা',
                'details' => 'আমি একজন বিধবা মহিলা এবং আমার পরিবারে ৩ জন সদস্য আছে। আমার ছেলে একজন দিনমজুর এবং বউ অসুস্থ। আমাদের নিজের জমি নেই এবং অন্যের জমিতে কাজ করি। এবারের বন্যার কারণে ফসল নষ্ট হয়ে গেছে এবং আমাদের খাদ্যশস্য শেষ হয়ে গেছে। সরকারের সহায়তা ছাড়া আমাদের ক্ষুধা মেটানো সম্ভব নয়।',
                'amount_requested' => 100.00, // ১০০ কেজি
                'date' => Carbon::now()->subDays(3),
                'status' => 'pending'
            ],

            // 4. Individual - Winter Clothes Relief Application
            [
                'application_type' => 'individual',
                'applicant_name' => 'মোঃ করিম উদ্দিন',
                'applicant_nid' => '4567890123456',
                'applicant_phone' => '01545678901',
                'applicant_address' => 'বাড়ি নং ৭৮৯, দুপচাঁচিয়া, বগুড়া',
                'organization_name' => null,
                'organization_address' => null,
                'organization_type_id' => null,
                'applicant_designation' => null,
                'relief_type_id' => 4, // শীতবস্ত্র সহায়তা
                'project_id' => 6, // দরিদ্র পরিবারের শীতকালীন সহায়তা প্রকল্প
                'zilla_id' => 1,
                'upazila_id' => 4,
                'union_id' => 1,
                'ward_id' => 4,
                'subject' => 'শীতকালীন শীতবস্ত্র সহায়তা',
                'details' => 'আমি একজন বৃদ্ধ এবং আমার বয়স ৬৫ বছর। আমার স্ত্রী মারা গেছেন এবং আমি একা থাকি। আমার কোন সন্তান নেই এবং আমি সরকারি ভাতার উপর নির্ভরশীল। শীতকাল আসছে এবং আমার কাছে গরম কাপড় নেই। আমার স্বাস্থ্যের কারণে ঠান্ডায় খুব কষ্ট হয়। সরকারের সহায়তায় কিছু শীতবস্ত্র পেলে আমি শীতকালটা ভালোভাবে কাটাতে পারব।',
                'amount_requested' => 5.00, // ৫ পিস
                'date' => Carbon::now()->subDays(4),
                'status' => 'pending'
            ],

            // 5. Organization - Tin Sheet Relief Application
            [
                'application_type' => 'organization',
                'applicant_name' => 'মোঃ নজরুল ইসলাম',
                'applicant_nid' => '5678901234567',
                'applicant_phone' => '01656789012',
                'applicant_address' => 'বাড়ি নং ৩২১, গাবতলী, বগুড়া',
                'organization_name' => 'গাবতলী উন্নয়ন সমিতি',
                'organization_address' => 'গাবতলী বাজার, গাবতলী, বগুড়া',
                'organization_type_id' => 9, // Community Organization
                'applicant_designation' => 'সম্পাদক',
                'relief_type_id' => 5, // ঢেউটিন সহায়তা
                'project_id' => 7, // গৃহহীন পরিবারের ঢেউটিন সহায়তা প্রকল্প
                'zilla_id' => 1,
                'upazila_id' => 5,
                'union_id' => 1,
                'ward_id' => 5,
                'subject' => 'গৃহহীন পরিবারের জন্য ঢেউটিন সহায়তা',
                'details' => 'আমাদের এলাকায় অনেক পরিবার ঘরহীন অবস্থায় আছে। তারা বিভিন্ন স্থানে অস্থায়ী আশ্রয়ে বসবাস করছে। বর্ষা মৌসুমে তাদের খুব কষ্ট হয়। আমাদের সমিতি এই পরিবারগুলোর জন্য ঢেউটিন সংগ্রহ করে তাদের অস্থায়ী আশ্রয়ের ছাদ তৈরি করতে সাহায্য করতে চায়। প্রায় ২০টি পরিবারের জন্য ঢেউটিন প্রয়োজন।',
                'amount_requested' => 40.00, // ৪০ পিস
                'date' => Carbon::now()->subDays(5),
                'status' => 'pending'
            ],

            // 6. Individual - House Relief Application
            [
                'application_type' => 'individual',
                'applicant_name' => 'মোছাঃ ফাতেমা খাতুন',
                'applicant_nid' => '6789012345678',
                'applicant_phone' => '01767890123',
                'applicant_address' => 'গ্রাম: শালবন, কাহালু, বগুড়া',
                'organization_name' => null,
                'organization_address' => null,
                'organization_type_id' => null,
                'applicant_designation' => null,
                'relief_type_id' => 6, // গৃহবাবদ সহায়তা
                'project_id' => 9, // অসহায় পরিবারের আবাসন সহায়তা প্রকল্প
                'zilla_id' => 1,
                'upazila_id' => 6,
                'union_id' => 1,
                'ward_id' => 6,
                'subject' => 'অসহায় পরিবারের আবাসন সহায়তা',
                'details' => 'আমি একজন বিধবা মহিলা এবং আমার ৩টি ছোট সন্তান আছে। আমাদের বাড়িটি প্রায় ভেঙে পড়ার উপক্রম। বর্ষায় ছাদ থেকে পানি পড়ে এবং দেয়ালে ফাটল ধরেছে। আমার ছেলে-মেয়েদের নিরাপদ আবাসনের জন্য নতুন ঘর প্রয়োজন। আমি দিনমজুরের কাজ করি এবং এত টাকা জমাতে পারি না। সরকারের সহায়তা পেলে আমরা নিরাপদ ঘরে থাকতে পারব।',
                'amount_requested' => 1.00, // ১ প্যাকেজ
                'date' => Carbon::now()->subDays(6),
                'status' => 'pending'
            ],

            // 7. Organization - DC Special Relief Application
            [
                'application_type' => 'organization',
                'applicant_name' => 'মোঃ আবুল কালাম',
                'applicant_nid' => '7890123456789',
                'applicant_phone' => '01878901234',
                'applicant_address' => 'বাড়ি নং ৫৫৫, নন্দীগ্রাম, বগুড়া',
                'organization_name' => 'নন্দীগ্রাম মসজিদ কমিটি',
                'organization_address' => 'নন্দীগ্রাম কেন্দ্রীয় মসজিদ, নন্দীগ্রাম, বগুড়া',
                'organization_type_id' => 2, // Mosque
                'applicant_designation' => 'সভাপতি',
                'relief_type_id' => 7, // জেলা প্রশাসকের সাহায্য
                'project_id' => 10, // জেলা প্রশাসকের বিশেষ সহায়তা প্রকল্প
                'zilla_id' => 1,
                'upazila_id' => 7,
                'union_id' => 1,
                'ward_id' => 7,
                'subject' => 'মসজিদের উন্নয়ন ও দরিদ্র মুসল্লিদের সহায়তা',
                'details' => 'আমাদের মসজিদে প্রায় ২০০ জন মুসল্লি নামাজ পড়েন। অনেক মুসল্লি দরিদ্র এবং তাদের পরিবারের খাদ্য নিরাপত্তা নেই। মসজিদের রান্নাঘর ও খাবার বিতরণের জন্য স্থান প্রয়োজন। এছাড়াও কিছু অসহায় পরিবার আছে যারা নিয়মিত সহায়তা পেলে ভালো থাকবে। জেলা প্রশাসকের বিশেষ সহায়তা পেলে আমরা এই কাজগুলো করতে পারব।',
                'amount_requested' => 1.00, // ১ প্যাকেজ
                'date' => Carbon::now()->subDays(7),
                'status' => 'pending'
            ],

            // 8. Individual - Cash Relief Application (Woman)
            [
                'application_type' => 'individual',
                'applicant_name' => 'মোছাঃ রাবেয়া খাতুন',
                'applicant_nid' => '8901234567890',
                'applicant_phone' => '01989012345',
                'applicant_address' => 'বাড়ি নং ৯৯৯, সারিয়াকান্দি, বগুড়া',
                'organization_name' => null,
                'organization_address' => null,
                'organization_type_id' => null,
                'applicant_designation' => null,
                'relief_type_id' => 2, // নগদ অর্থ সহায়তা
                'project_id' => 4, // অসহায় মহিলাদের নগদ সহায়তা প্রকল্প
                'zilla_id' => 1,
                'upazila_id' => 8,
                'union_id' => 1,
                'ward_id' => 8,
                'subject' => 'অসহায় মহিলার নগদ সহায়তা',
                'details' => 'আমি একজন অসহায় মহিলা এবং আমার বয়স ৪০ বছর। আমার স্বামী মারা গেছেন ২ বছর আগে। আমার ২টি ছোট সন্তান আছে। আমি সেলাইয়ের কাজ করি কিন্তু আয় খুব কম। আমার মেয়ের বিয়ে দিতে হবে এবং ছেলেকে স্কুলে পাঠাতে হবে। সামান্য নগদ সহায়তা পেলে আমি ছোট ব্যবসা শুরু করতে পারব এবং পরিবার চালাতে পারব।',
                'amount_requested' => 25000.00, // ২৫ হাজার টাকা
                'date' => Carbon::now()->subDays(8),
                'status' => 'pending'
            ],

            // 9. Organization - Food Relief Application
            [
                'application_type' => 'organization',
                'applicant_name' => 'মোঃ সিরাজুল ইসলাম',
                'applicant_nid' => '9012345678901',
                'applicant_phone' => '01590123456',
                'applicant_address' => 'বাড়ি নং ১১১, শেরপুর, বগুড়া',
                'organization_name' => 'শেরপুর দরিদ্র কল্যাণ সংস্থা',
                'organization_address' => 'শেরপুর বাজার, শেরপুর, বগুড়া',
                'organization_type_id' => 4, // NGO
                'applicant_designation' => 'নির্বাহী পরিচালক',
                'relief_type_id' => 1, // খাদ্য সহায়তা
                'project_id' => 2, // অসহায় পরিবারের খাদ্য নিরাপত্তা প্রকল্প
                'zilla_id' => 1,
                'upazila_id' => 9,
                'union_id' => 1,
                'ward_id' => 9,
                'subject' => 'দরিদ্র পরিবারের খাদ্য নিরাপত্তা প্রকল্প',
                'details' => 'আমাদের সংস্থা শেরপুর এলাকায় ১০০টি দরিদ্র পরিবারের সাথে কাজ করে। এই পরিবারগুলোর মধ্যে অনেক পরিবার একবেলা খেয়ে একবেলা না খেয়ে থাকে। বিশেষ করে শিশু ও বৃদ্ধদের পুষ্টির অভাব রয়েছে। আমরা নিয়মিত খাদ্য বিতরণ কর্মসূচি চালাই কিন্তু সম্পদ সীমিত। সরকারের সহায়তা পেলে আমরা আরও বেশি পরিবারকে সাহায্য করতে পারব।',
                'amount_requested' => 500.00, // ৫০০ কেজি
                'date' => Carbon::now()->subDays(9),
                'status' => 'pending'
            ],

            // 10. Individual - Tin Sheet Relief Application (Flood affected)
            [
                'application_type' => 'individual',
                'applicant_name' => 'মোঃ হাসান আলী',
                'applicant_nid' => '0123456789012',
                'applicant_phone' => '01601234567',
                'applicant_address' => 'গ্রাম: চরাঞ্চল, শিবগঞ্জ, বগুড়া',
                'organization_name' => null,
                'organization_address' => null,
                'organization_type_id' => null,
                'applicant_designation' => null,
                'relief_type_id' => 5, // ঢেউটিন সহায়তা
                'project_id' => 8, // বন্যা কবলিত পরিবারের ঢেউটিন সহায়তা
                'zilla_id' => 1,
                'upazila_id' => 10,
                'union_id' => 1,
                'ward_id' => 10,
                'subject' => 'বন্যা কবলিত পরিবারের ঢেউটিন সহায়তা',
                'details' => 'আমি একজন কৃষক এবং বন্যা কবলিত এলাকায় থাকি। গত বন্যায় আমাদের ঘরের ছাদ সম্পূর্ণভাবে ভেঙে গেছে। আমাদের পরিবারে ৬ জন সদস্য আছে এবং আমরা খোলা আকাশের নিচে থাকি। বর্ষা মৌসুম আসছে এবং আমাদের জরুরি ঢেউটিন প্রয়োজন। আমার ফসল নষ্ট হয়ে গেছে তাই ঢেউটিন কিনতে পারি না। সরকারের সহায়তা পেলে আমরা নিরাপদ আশ্রয় পাব।',
                'amount_requested' => 15.00, // ১৫ পিস
                'date' => Carbon::now()->subDays(10),
                'status' => 'pending'
            ]
        ];

        foreach ($testApplications as $application) {
            ReliefApplication::create($application);
        }

        $this->command->info('Created 10 test relief applications with Bengali content');
    }
}
