<?php

namespace Database\Seeders;

use App\Models\ReliefType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReliefTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $reliefTypes = [
            [
                'name' => 'Rice',
                'name_bn' => 'চাল',
                'description' => 'Rice for food assistance',
                'description_bn' => 'খাদ্য সহায়তার জন্য চাল',
                'unit' => 'Metric Ton',
                'unit_bn' => 'মেট্রিক টন',
                'color_code' => '#10B981',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Wheat',
                'name_bn' => 'গম',
                'description' => 'Wheat for food assistance',
                'description_bn' => 'খাদ্য সহায়তার জন্য গম',
                'unit' => 'Metric Ton',
                'unit_bn' => 'মেট্রিক টন',
                'color_code' => '#F59E0B',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Corrugated Iron Sheet Bundle',
                'name_bn' => 'বান্ডিল ঢেউটিন',
                'description' => 'Corrugated iron sheet bundles for shelter',
                'description_bn' => 'আশ্রয়ের জন্য ঢেউটিনের বান্ডিল',
                'unit' => 'Bundle',
                'unit_bn' => 'বান্ডিল',
                'color_code' => '#6B7280',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Cash',
                'name_bn' => 'টাকা',
                'description' => 'Cash assistance for immediate needs',
                'description_bn' => 'তাৎক্ষণিক প্রয়োজনের জন্য নগদ সহায়তা',
                'unit' => 'Taka',
                'unit_bn' => 'টাকা',
                'color_code' => '#EF4444',
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Winter Clothes',
                'name_bn' => 'শীতবস্ত্র',
                'description' => 'Winter clothing for cold weather protection',
                'description_bn' => 'শীতকালীন সুরক্ষার জন্য শীতবস্ত্র',
                'unit' => 'Piece',
                'unit_bn' => 'টি',
                'color_code' => '#3B82F6',
                'is_active' => true,
                'sort_order' => 5,
            ],
            [
                'name' => 'Blanket',
                'name_bn' => 'কম্বল',
                'description' => 'Blankets for warmth and comfort',
                'description_bn' => 'উষ্ণতা ও আরামের জন্য কম্বল',
                'unit' => 'Piece',
                'unit_bn' => 'টি',
                'color_code' => '#8B5CF6',
                'is_active' => true,
                'sort_order' => 6,
            ],
            [
                'name' => 'Lentils',
                'name_bn' => 'ডাল',
                'description' => 'Lentils for protein nutrition',
                'description_bn' => 'প্রোটিন পুষ্টির জন্য ডাল',
                'unit' => 'Kg',
                'unit_bn' => 'কেজি',
                'color_code' => '#F97316',
                'is_active' => true,
                'sort_order' => 7,
            ],
            [
                'name' => 'Cooking Oil',
                'name_bn' => 'রান্নার তেল',
                'description' => 'Cooking oil for food preparation',
                'description_bn' => 'রান্নার জন্য তেল',
                'unit' => 'Liter',
                'unit_bn' => 'লিটার',
                'color_code' => '#FBBF24',
                'is_active' => true,
                'sort_order' => 8,
            ],
        ];

        foreach ($reliefTypes as $reliefType) {
            ReliefType::create($reliefType);
        }
    }
}