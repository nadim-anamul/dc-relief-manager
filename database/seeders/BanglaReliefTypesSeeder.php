<?php

namespace Database\Seeders;

use App\Models\ReliefType;
use Illuminate\Database\Seeder;

class BanglaReliefTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creating comprehensive Bangla relief types...');

        $reliefTypes = [
            [
                'name' => 'নগদ ত্রাণ',
                'name_en' => 'Cash Relief',
                'description' => 'তাৎক্ষণিক প্রয়োজনের জন্য নগদ সহায়তা',
                'description_en' => 'Direct cash assistance for immediate needs',
                'unit' => 'Taka',
                'unit_bn' => 'টাকা',
                'color_code' => '#10B981',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'খাদ্য ত্রাণ',
                'name_en' => 'Food Relief',
                'description' => 'খাদ্য সামগ্রী ও প্রয়োজনীয় সরবরাহ',
                'description_en' => 'Food items and essential supplies',
                'unit' => 'KG',
                'unit_bn' => 'কেজি',
                'color_code' => '#F59E0B',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'চিকিৎসা ত্রাণ',
                'name_en' => 'Medical Relief',
                'description' => 'চিকিৎসা সামগ্রী ও স্বাস্থ্য সহায়তা',
                'description_en' => 'Medical supplies and health assistance',
                'unit' => 'Package',
                'unit_bn' => 'প্যাকেজ',
                'color_code' => '#EF4444',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'আশ্রয় ত্রাণ',
                'name_en' => 'Shelter Relief',
                'description' => 'আবাসন ও আশ্রয় সামগ্রী',
                'description_en' => 'Housing and shelter materials',
                'unit' => 'Piece',
                'unit_bn' => 'পিস',
                'color_code' => '#3B82F6',
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'শিক্ষা ত্রাণ',
                'name_en' => 'Educational Relief',
                'description' => 'শিক্ষা সামগ্রী ও সরবরাহ',
                'description_en' => 'Educational materials and supplies',
                'unit' => 'Set',
                'unit_bn' => 'সেট',
                'color_code' => '#8B5CF6',
                'is_active' => true,
                'sort_order' => 5,
            ],
            [
                'name' => 'শীতকালীন ত্রাণ',
                'name_en' => 'Winter Relief',
                'description' => 'শীতকালীন পোশাক ও গরম সহায়তা',
                'description_en' => 'Winter clothing and heating support',
                'unit' => 'Piece',
                'unit_bn' => 'পিস',
                'color_code' => '#06B6D4',
                'is_active' => true,
                'sort_order' => 6,
            ],
            [
                'name' => 'বন্যা ত্রাণ',
                'name_en' => 'Flood Relief',
                'description' => 'বন্যা কবলিত এলাকার জরুরি সহায়তা',
                'description_en' => 'Emergency assistance for flood affected areas',
                'unit' => 'Package',
                'unit_bn' => 'প্যাকেজ',
                'color_code' => '#3B82F6',
                'is_active' => true,
                'sort_order' => 7,
            ],
            [
                'name' => 'ঘূর্ণিঝড় ত্রাণ',
                'name_en' => 'Cyclone Relief',
                'description' => 'ঘূর্ণিঝড় কবলিত এলাকার সহায়তা',
                'description_en' => 'Assistance for cyclone affected areas',
                'unit' => 'Package',
                'unit_bn' => 'প্যাকেজ',
                'color_code' => '#F97316',
                'is_active' => true,
                'sort_order' => 8,
            ],
            [
                'name' => 'খরা ত্রাণ',
                'name_en' => 'Drought Relief',
                'description' => 'খরা কবলিত এলাকার সহায়তা',
                'description_en' => 'Assistance for drought affected areas',
                'unit' => 'Package',
                'unit_bn' => 'প্যাকেজ',
                'color_code' => '#F59E0B',
                'is_active' => true,
                'sort_order' => 9,
            ],
            [
                'name' => 'জরুরি ত্রাণ',
                'name_en' => 'Emergency Relief',
                'description' => 'জরুরি পরিস্থিতির জন্য দ্রুত সহায়তা',
                'description_en' => 'Quick assistance for emergency situations',
                'unit' => 'Package',
                'unit_bn' => 'প্যাকেজ',
                'color_code' => '#DC2626',
                'is_active' => true,
                'sort_order' => 10,
            ],
            [
                'name' => 'মহিলা ত্রাণ',
                'name_en' => 'Women Relief',
                'description' => 'মহিলাদের জন্য বিশেষ সহায়তা',
                'description_en' => 'Special assistance for women',
                'unit' => 'Package',
                'unit_bn' => 'প্যাকেজ',
                'color_code' => '#EC4899',
                'is_active' => true,
                'sort_order' => 11,
            ],
            [
                'name' => 'শিশু ত্রাণ',
                'name_en' => 'Child Relief',
                'description' => 'শিশুদের জন্য বিশেষ সহায়তা',
                'description_en' => 'Special assistance for children',
                'unit' => 'Package',
                'unit_bn' => 'প্যাকেজ',
                'color_code' => '#8B5CF6',
                'is_active' => true,
                'sort_order' => 12,
            ],
            [
                'name' => 'বৃদ্ধ ত্রাণ',
                'name_en' => 'Elderly Relief',
                'description' => 'বৃদ্ধদের জন্য বিশেষ সহায়তা',
                'description_en' => 'Special assistance for elderly',
                'unit' => 'Package',
                'unit_bn' => 'প্যাকেজ',
                'color_code' => '#6B7280',
                'is_active' => true,
                'sort_order' => 13,
            ],
            [
                'name' => 'প্রতিবন্ধী ত্রাণ',
                'name_en' => 'Disability Relief',
                'description' => 'প্রতিবন্ধীদের জন্য বিশেষ সহায়তা',
                'description_en' => 'Special assistance for persons with disabilities',
                'unit' => 'Package',
                'unit_bn' => 'প্যাকেজ',
                'color_code' => '#059669',
                'is_active' => true,
                'sort_order' => 14,
            ],
            [
                'name' => 'কৃষি ত্রাণ',
                'name_en' => 'Agricultural Relief',
                'description' => 'কৃষকদের জন্য কৃষি সহায়তা',
                'description_en' => 'Agricultural assistance for farmers',
                'unit' => 'Package',
                'unit_bn' => 'প্যাকেজ',
                'color_code' => '#16A34A',
                'is_active' => true,
                'sort_order' => 15,
            ],
            [
                'name' => 'পানি ত্রাণ',
                'name_en' => 'Water Relief',
                'description' => 'পানি সরবরাহ ও বিশুদ্ধকরণ সহায়তা',
                'description_en' => 'Water supply and purification assistance',
                'unit' => 'Liter',
                'unit_bn' => 'লিটার',
                'color_code' => '#0EA5E9',
                'is_active' => true,
                'sort_order' => 16,
            ],
            [
                'name' => 'স্বাস্থ্য ত্রাণ',
                'name_en' => 'Health Relief',
                'description' => 'স্বাস্থ্য সেবা ও চিকিৎসা সহায়তা',
                'description_en' => 'Healthcare and medical assistance',
                'unit' => 'Package',
                'unit_bn' => 'প্যাকেজ',
                'color_code' => '#DC2626',
                'is_active' => true,
                'sort_order' => 17,
            ],
            [
                'name' => 'পুনর্বাসন ত্রাণ',
                'name_en' => 'Rehabilitation Relief',
                'description' => 'পুনর্বাসন ও পুনর্গঠন সহায়তা',
                'description_en' => 'Rehabilitation and reconstruction assistance',
                'unit' => 'Package',
                'unit_bn' => 'প্যাকেজ',
                'color_code' => '#7C3AED',
                'is_active' => true,
                'sort_order' => 18,
            ],
            [
                'name' => 'সামাজিক ত্রাণ',
                'name_en' => 'Social Relief',
                'description' => 'সামাজিক উন্নয়ন ও কল্যাণ সহায়তা',
                'description_en' => 'Social development and welfare assistance',
                'unit' => 'Package',
                'unit_bn' => 'প্যাকেজ',
                'color_code' => '#0891B2',
                'is_active' => true,
                'sort_order' => 19,
            ],
            [
                'name' => 'মানবিক ত্রাণ',
                'name_en' => 'Humanitarian Relief',
                'description' => 'মানবিক সহায়তা ও সমর্থন',
                'description_en' => 'Humanitarian assistance and support',
                'unit' => 'Package',
                'unit_bn' => 'প্যাকেজ',
                'color_code' => '#BE185D',
                'is_active' => true,
                'sort_order' => 20,
            ]
        ];

        $createdCount = 0;

        foreach ($reliefTypes as $reliefTypeData) {
            $reliefType = ReliefType::firstOrCreate(
                ['name' => $reliefTypeData['name_en']],
                [
                    'name' => $reliefTypeData['name_en'],
                    'name_bn' => $reliefTypeData['name'],
                    'description' => $reliefTypeData['description_en'],
                    'description_bn' => $reliefTypeData['description'],
                    'unit' => $reliefTypeData['unit'],
                    'unit_bn' => $reliefTypeData['unit_bn'],
                    'color_code' => $reliefTypeData['color_code'],
                    'is_active' => $reliefTypeData['is_active'],
                    'sort_order' => $reliefTypeData['sort_order'],
                ]
            );

            $createdCount++;
        }

        $this->command->info("Created {$createdCount} comprehensive Bangla relief types.");
        $this->command->info('Relief types include cash relief, food relief, medical relief, shelter relief, educational relief, winter relief, flood relief, cyclone relief, drought relief, emergency relief, women relief, child relief, elderly relief, disability relief, agricultural relief, water relief, health relief, rehabilitation relief, social relief, and humanitarian relief.');
    }
}
