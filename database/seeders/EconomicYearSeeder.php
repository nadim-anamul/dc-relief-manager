<?php

namespace Database\Seeders;

use App\Models\EconomicYear;
use Illuminate\Database\Seeder;

class EconomicYearSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		$economicYears = [
			[
				'name' => '2022-2023',
				'name_bn' => '২০২২-২০২৩',
				'start_date' => '2022-07-01',
				'end_date' => '2023-06-30',
				'is_active' => true,
				'is_current' => false,
			],
			[
				'name' => '2023-2024',
				'name_bn' => '২০২৩-২০২৪',
				'start_date' => '2023-07-01',
				'end_date' => '2024-06-30',
				'is_active' => true,
				'is_current' => false,
			],
			[
				'name' => '2024-2025',
				'name_bn' => '২০২৪-২০২৫',
				'start_date' => '2024-07-01',
				'end_date' => '2025-06-30',
				'is_active' => true,
				'is_current' => true, // Current economic year
			],
			[
				'name' => '2025-2026',
				'name_bn' => '২০২৫-২০২৬',
				'start_date' => '2025-07-01',
				'end_date' => '2026-06-30',
				'is_active' => true,
				'is_current' => false,
			],
		];

		foreach ($economicYears as $economicYear) {
			EconomicYear::create($economicYear);
		}
	}
}