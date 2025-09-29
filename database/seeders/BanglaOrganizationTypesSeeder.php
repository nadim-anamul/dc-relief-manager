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
                'name' => 'এনজিও',
                'name_en' => 'NGO',
                'description' => 'বেসরকারি উন্নয়ন সংস্থা',
                'description_en' => 'Non-Governmental Development Organization',
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
                'name' => 'কৃষক সমিতি',
                'name_en' => 'Farmers Association',
                'description' => 'কৃষক ও কৃষি শ্রমিকদের সমিতি',
                'description_en' => 'Association of farmers and agricultural workers',
            ],
            [
                'name' => 'ফাউন্ডেশন',
                'name_en' => 'Foundation',
                'description' => 'দাতব্য ফাউন্ডেশন',
                'description_en' => 'Charitable foundation',
            ],
            [
                'name' => 'জরুরি সাড়া সংস্থা',
                'name_en' => 'Emergency Response Organization',
                'description' => 'জরুরি সাড়া প্রদান ও দুর্যোগ ব্যবস্থাপনা সংস্থা',
                'description_en' => 'Emergency response and disaster management organization',
            ],
            [
                'name' => 'ধর্মীয় সংস্থা',
                'name_en' => 'Religious Organization',
                'description' => 'ধর্মীয় ও বিশ্বাসভিত্তিক সংস্থা',
                'description_en' => 'Religious and faith-based organization',
            ],
            [
                'name' => 'যুব সংগঠন',
                'name_en' => 'Youth Organization',
                'description' => 'যুব নেতৃত্বাধীন সংগঠন',
                'description_en' => 'Youth-led organization',
            ],
            [
                'name' => 'মহিলা সংগঠন',
                'name_en' => 'Women Organization',
                'description' => 'মহিলা উন্নয়ন ও ক্ষমতায়ন সংগঠন',
                'description_en' => 'Women development and empowerment organization',
            ],
            [
                'name' => 'শিক্ষা সংস্থা',
                'name_en' => 'Educational Organization',
                'description' => 'শিক্ষা ও মানব উন্নয়ন সংস্থা',
                'description_en' => 'Educational and human development organization',
            ],
            [
                'name' => 'স্বাস্থ্য সংস্থা',
                'name_en' => 'Health Organization',
                'description' => 'স্বাস্থ্য সেবা ও চিকিৎসা সংস্থা',
                'description_en' => 'Healthcare and medical organization',
            ],
            [
                'name' => 'পরিবেশ সংস্থা',
                'name_en' => 'Environmental Organization',
                'description' => 'পরিবেশ সংরক্ষণ ও উন্নয়ন সংস্থা',
                'description_en' => 'Environmental conservation and development organization',
            ],
            [
                'name' => 'সহযোগিতা সমিতি',
                'name_en' => 'Cooperative Society',
                'description' => 'সহযোগিতা ভিত্তিক সমিতি',
                'description_en' => 'Cooperation-based society',
            ],
            [
                'name' => 'মুক্তিযোদ্ধা সংগঠন',
                'name_en' => 'Freedom Fighter Organization',
                'description' => 'মুক্তিযোদ্ধা কল্যাণ সংগঠন',
                'description_en' => 'Freedom fighter welfare organization',
            ],
            [
                'name' => 'বেকার যুব উন্নয়ন সংস্থা',
                'name_en' => 'Unemployed Youth Development Organization',
                'description' => 'বেকার যুবকদের দক্ষতা উন্নয়ন সংস্থা',
                'description_en' => 'Skills development organization for unemployed youth',
            ],
            [
                'name' => 'প্রতিবন্ধী কল্যাণ সংস্থা',
                'name_en' => 'Disability Welfare Organization',
                'description' => 'প্রতিবন্ধীদের কল্যাণ ও উন্নয়ন সংস্থা',
                'description_en' => 'Welfare and development organization for persons with disabilities',
            ],
            [
                'name' => 'বৃদ্ধ কল্যাণ সংস্থা',
                'name_en' => 'Elderly Welfare Organization',
                'description' => 'বৃদ্ধদের কল্যাণ ও সেবা সংস্থা',
                'description_en' => 'Welfare and service organization for elderly',
            ],
            [
                'name' => 'শিশু কল্যাণ সংস্থা',
                'name_en' => 'Child Welfare Organization',
                'description' => 'শিশু কল্যাণ ও উন্নয়ন সংস্থা',
                'description_en' => 'Child welfare and development organization',
            ],
            [
                'name' => 'ক্রীড়া সংগঠন',
                'name_en' => 'Sports Organization',
                'description' => 'ক্রীড়া ও শরীরচর্চা সংগঠন',
                'description_en' => 'Sports and physical fitness organization',
            ],
            [
                'name' => 'সাংস্কৃতিক সংগঠন',
                'name_en' => 'Cultural Organization',
                'description' => 'সাংস্কৃতিক উন্নয়ন ও সংরক্ষণ সংগঠন',
                'description_en' => 'Cultural development and preservation organization',
            ]
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
        $this->command->info('Organization types include NGOs, social welfare organizations, community organizations, farmers associations, foundations, emergency response organizations, religious organizations, youth organizations, women organizations, educational organizations, health organizations, environmental organizations, cooperative societies, freedom fighter organizations, unemployed youth development organizations, disability welfare organizations, elderly welfare organizations, child welfare organizations, sports organizations, and cultural organizations.');
    }
}
