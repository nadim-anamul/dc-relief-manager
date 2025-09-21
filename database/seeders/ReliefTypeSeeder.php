<?php

namespace Database\Seeders;

use App\Models\ReliefType;
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
				'description' => 'Basic food grain for relief distribution',
				'description_bn' => 'ত্রাণ বিতরণের জন্য মৌলিক খাদ্যশস্য',
				'unit' => 'kg',
				'unit_bn' => 'কেজি',
				'color_code' => '#10B981', // Green
				'is_active' => true,
				'sort_order' => 1,
			],
			[
				'name' => 'Dal (Lentils)',
				'name_bn' => 'ডাল',
				'description' => 'Protein-rich lentils for nutrition',
				'description_bn' => 'পুষ্টির জন্য প্রোটিন সমৃদ্ধ ডাল',
				'unit' => 'kg',
				'unit_bn' => 'কেজি',
				'color_code' => '#F59E0B', // Amber
				'is_active' => true,
				'sort_order' => 2,
			],
			[
				'name' => 'Cooking Oil',
				'name_bn' => 'রান্নার তেল',
				'description' => 'Edible oil for cooking purposes',
				'description_bn' => 'রান্নার জন্য ভোজ্য তেল',
				'unit' => 'liter',
				'unit_bn' => 'লিটার',
				'color_code' => '#EF4444', // Red
				'is_active' => true,
				'sort_order' => 3,
			],
			[
				'name' => 'Salt',
				'name_bn' => 'লবণ',
				'description' => 'Essential seasoning for food preparation',
				'description_bn' => 'খাদ্য প্রস্তুতির জন্য অপরিহার্য মসলা',
				'unit' => 'kg',
				'unit_bn' => 'কেজি',
				'color_code' => '#6B7280', // Gray
				'is_active' => true,
				'sort_order' => 4,
			],
			[
				'name' => 'Sugar',
				'name_bn' => 'চিনি',
				'description' => 'Sweetener for food and beverages',
				'description_bn' => 'খাদ্য ও পানীয়ের জন্য মিষ্টি',
				'unit' => 'kg',
				'unit_bn' => 'কেজি',
				'color_code' => '#F97316', // Orange
				'is_active' => true,
				'sort_order' => 5,
			],
			[
				'name' => 'Potato',
				'name_bn' => 'আলু',
				'description' => 'Staple vegetable for nutrition',
				'description_bn' => 'পুষ্টির জন্য প্রধান সবজি',
				'unit' => 'kg',
				'unit_bn' => 'কেজি',
				'color_code' => '#8B5CF6', // Purple
				'is_active' => true,
				'sort_order' => 6,
			],
			[
				'name' => 'Onion',
				'name_bn' => 'পেঁয়াজ',
				'description' => 'Essential cooking ingredient',
				'description_bn' => 'অপরিহার্য রান্নার উপাদান',
				'unit' => 'kg',
				'unit_bn' => 'কেজি',
				'color_code' => '#EC4899', // Pink
				'is_active' => true,
				'sort_order' => 7,
			],
			[
				'name' => 'Cash Relief',
				'name_bn' => 'নগদ ত্রাণ',
				'description' => 'Monetary assistance for immediate needs',
				'description_bn' => 'তাৎক্ষণিক প্রয়োজনের জন্য আর্থিক সহায়তা',
				'unit' => 'BDT',
				'unit_bn' => 'টাকা',
				'color_code' => '#059669', // Emerald
				'is_active' => true,
				'sort_order' => 8,
			],
			[
				'name' => 'Blanket',
				'name_bn' => 'কম্বল',
				'description' => 'Warmth and comfort during cold weather',
				'description_bn' => 'শীতকালে উষ্ণতা ও আরাম',
				'unit' => 'piece',
				'unit_bn' => 'টি',
				'color_code' => '#3B82F6', // Blue
				'is_active' => true,
				'sort_order' => 9,
			],
			[
				'name' => 'Medicine',
				'name_bn' => 'ঔষধ',
				'description' => 'Essential medicines for health',
				'description_bn' => 'স্বাস্থ্যের জন্য অপরিহার্য ওষুধ',
				'unit' => 'packet',
				'unit_bn' => 'প্যাকেট',
				'color_code' => '#DC2626', // Red-600
				'is_active' => true,
				'sort_order' => 10,
			],
		];

		foreach ($reliefTypes as $reliefType) {
			ReliefType::create($reliefType);
		}
	}
}