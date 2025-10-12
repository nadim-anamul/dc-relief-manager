<?php

namespace Database\Seeders;

use App\Models\OrganizationType;
use Illuminate\Database\Seeder;

class BanglaOrganizationTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creating comprehensive Bangla organization types...');

        $organizationTypes = [
            [
                'name' => 'মাদ্রাসা',
                'name_en' => 'Madrasa',
                'description' => 'ইসলামিক শিক্ষা প্রতিষ্ঠান',
                'description_en' => 'Islamic educational institution',
            ],
            [
                'name' => 'মসজিদ',
                'name_en' => 'Mosque',
                'description' => 'ইসলামিক উপাসনালয়',
                'description_en' => 'Islamic place of worship',
            ],
            [
                'name' => 'মন্দির',
                'name_en' => 'Temple',
                'description' => 'হিন্দু ধর্মীয় উপাসনালয়',
                'description_en' => 'Hindu religious place of worship',
            ],
            [
                'name' => 'এনজিও',
                'name_en' => 'NGO',
                'description' => 'বেসরকারি উন্নয়ন সংস্থা',
                'description_en' => 'Non-Governmental Development Organization',
            ],
            [
                'name' => 'বৃদ্ধাশ্রম',
                'name_en' => 'Old Home',
                'description' => 'বৃদ্ধদের জন্য আবাসিক সেবা কেন্দ্র',
                'description_en' => 'Residential service center for elderly',
            ],
            [
                'name' => 'শিক্ষা প্রতিষ্ঠান',
                'name_en' => 'Educational Institute',
                'description' => 'সাধারণ শিক্ষা প্রতিষ্ঠান',
                'description_en' => 'General educational institution',
            ],
            [
                'name' => 'দাতব্য ফাউন্ডেশন',
                'name_en' => 'Charitable Foundation',
                'description' => 'দাতব্য ও মানবিক সহায়তা ফাউন্ডেশন',
                'description_en' => 'Charitable and humanitarian assistance foundation',
            ],
            [
                'name' => 'সমাজ কল্যাণ সংস্থা',
                'name_en' => 'Social Welfare Organization',
                'description' => 'সামাজিক কল্যাণ ও উন্নয়ন সংস্থা',
                'description_en' => 'Social welfare and development organization',
            ],
            [
                'name' => 'সম্প্রদায় সংগঠন',
                'name_en' => 'Community Organization',
                'description' => 'স্থানীয় সম্প্রদায়ভিত্তিক সংগঠন',
                'description_en' => 'Local community-based organization',
            ],
            [
                'name' => 'ধর্মীয় সংস্থা',
                'name_en' => 'Religious Organization',
                'description' => 'ধর্মীয় ও বিশ্বাসভিত্তিক সংস্থা',
                'description_en' => 'Religious and faith-based organization',
            ],
        ];

        $createdCount = 0;

        foreach ($organizationTypes as $typeData) {
            $organizationType = OrganizationType::firstOrCreate(
                ['name' => $typeData['name_en']],
                [
                    'name' => $typeData['name_en'],
                    'description' => $typeData['description_en'],
                ]
            );

            $createdCount++;
        }

        $this->command->info("Created {$createdCount} comprehensive Bangla organization types.");
        $this->command->info('Organization types include: মাদ্রাসা, মসজিদ, মন্দির, এনজিও, বৃদ্ধাশ্রম, শিক্ষা প্রতিষ্ঠান, দাতব্য ফাউন্ডেশন, সমাজ কল্যাণ সংস্থা, সম্প্রদায় সংগঠন, ধর্মীয় সংস্থা');
    }
}
