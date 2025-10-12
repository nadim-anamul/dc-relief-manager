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
                'name' => 'দুঃস্থদের খাদ্য সহায়তা',
                'name_en' => 'Food Assistance for Destitute',
                'description' => 'দুঃস্থ ও দরিদ্র পরিবারগুলোর জন্য খাদ্য সহায়তা',
                'description_en' => 'Food assistance for destitute and poor families',
                'unit' => 'KG',
                'unit_bn' => 'কেজি',
                'color_code' => '#F59E0B',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'নগদ অর্থ সহায়তা',
                'name_en' => 'Cash Assistance',
                'description' => 'তাৎক্ষণিক প্রয়োজনের জন্য নগদ সহায়তা',
                'description_en' => 'Direct cash assistance for immediate needs',
                'unit' => 'Taka',
                'unit_bn' => 'টাকা',
                'color_code' => '#10B981',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'খাদ্যশস্য সহায়তা',
                'name_en' => 'Grain Assistance',
                'description' => 'খাদ্যশস্য ও শস্য সহায়তা',
                'description_en' => 'Grain and crop assistance',
                'unit' => 'KG',
                'unit_bn' => 'কেজি',
                'color_code' => '#84CC16',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'শীতবস্ত্র সহায়তা',
                'name_en' => 'Winter Clothing Assistance',
                'description' => 'শীতকালীন পোশাক ও গরম সহায়তা',
                'description_en' => 'Winter clothing and heating support',
                'unit' => 'Piece',
                'unit_bn' => 'পিস',
                'color_code' => '#06B6D4',
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'ঢেউটিন সহায়তা',
                'name_en' => 'Corrugated Iron Sheet Assistance',
                'description' => 'আবাসন নির্মাণের জন্য ঢেউটিন সহায়তা',
                'description_en' => 'Corrugated iron sheet assistance for housing construction',
                'unit' => 'Piece',
                'unit_bn' => 'পিস',
                'color_code' => '#3B82F6',
                'is_active' => true,
                'sort_order' => 5,
            ],
            [
                'name' => 'গৃহবাবদ সহায়তা',
                'name_en' => 'Housing Assistance',
                'description' => 'আবাসন ও গৃহ নির্মাণ সহায়তা',
                'description_en' => 'Housing and home construction assistance',
                'unit' => 'Package',
                'unit_bn' => 'প্যাকেজ',
                'color_code' => '#7C3AED',
                'is_active' => true,
                'sort_order' => 6,
            ],
            [
                'name' => 'জেলা প্রশাসকের সাহায্য',
                'name_en' => 'District Commissioner Assistance',
                'description' => 'জেলা প্রশাসকের বিশেষ সহায়তা',
                'description_en' => 'Special assistance from District Commissioner',
                'unit' => 'Package',
                'unit_bn' => 'প্যাকেজ',
                'color_code' => '#DC2626',
                'is_active' => true,
                'sort_order' => 7,
            ],
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
        $this->command->info('Relief types include: দুঃস্থদের খাদ্য সহায়তা, নগদ অর্থ সহায়তা, খাদ্যশস্য সহায়তা, শীতবস্ত্র সহায়তা, ঢেউটিন সহায়তা, গৃহবাবদ সহায়তা, জেলা প্রশাসকের সাহায্য');
    }
}
