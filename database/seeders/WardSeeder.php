<?php

namespace Database\Seeders;

use App\Models\Union;
use App\Models\Ward;
use Illuminate\Database\Seeder;

class WardSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		$boguraMunicipality = Union::where('code', 'BOG_MUNI')->first();

		if (!$boguraMunicipality) {
			$this->command->error('Bogura Municipality Union not found. Please run UnionSeeder first.');
			return;
		}

		$wards = [
			[
				'union_id' => $boguraMunicipality->id,
				'name' => 'Ward 1',
				'name_bn' => 'ওয়ার্ড ১',
				'code' => 'WARD_01',
				'is_active' => true,
			],
			[
				'union_id' => $boguraMunicipality->id,
				'name' => 'Ward 2',
				'name_bn' => 'ওয়ার্ড ২',
				'code' => 'WARD_02',
				'is_active' => true,
			],
			[
				'union_id' => $boguraMunicipality->id,
				'name' => 'Ward 3',
				'name_bn' => 'ওয়ার্ড ৩',
				'code' => 'WARD_03',
				'is_active' => true,
			],
			[
				'union_id' => $boguraMunicipality->id,
				'name' => 'Ward 4',
				'name_bn' => 'ওয়ার্ড ৪',
				'code' => 'WARD_04',
				'is_active' => true,
			],
			[
				'union_id' => $boguraMunicipality->id,
				'name' => 'Ward 5',
				'name_bn' => 'ওয়ার্ড ৫',
				'code' => 'WARD_05',
				'is_active' => true,
			],
			[
				'union_id' => $boguraMunicipality->id,
				'name' => 'Ward 6',
				'name_bn' => 'ওয়ার্ড ৬',
				'code' => 'WARD_06',
				'is_active' => true,
			],
			[
				'union_id' => $boguraMunicipality->id,
				'name' => 'Ward 7',
				'name_bn' => 'ওয়ার্ড ৭',
				'code' => 'WARD_07',
				'is_active' => true,
			],
			[
				'union_id' => $boguraMunicipality->id,
				'name' => 'Ward 8',
				'name_bn' => 'ওয়ার্ড ৮',
				'code' => 'WARD_08',
				'is_active' => true,
			],
			[
				'union_id' => $boguraMunicipality->id,
				'name' => 'Ward 9',
				'name_bn' => 'ওয়ার্ড ৯',
				'code' => 'WARD_09',
				'is_active' => true,
			],
			[
				'union_id' => $boguraMunicipality->id,
				'name' => 'Ward 10',
				'name_bn' => 'ওয়ার্ড ১০',
				'code' => 'WARD_10',
				'is_active' => true,
			],
			[
				'union_id' => $boguraMunicipality->id,
				'name' => 'Ward 11',
				'name_bn' => 'ওয়ার্ড ১১',
				'code' => 'WARD_11',
				'is_active' => true,
			],
			[
				'union_id' => $boguraMunicipality->id,
				'name' => 'Ward 12',
				'name_bn' => 'ওয়ার্ড ১২',
				'code' => 'WARD_12',
				'is_active' => true,
			],
			[
				'union_id' => $boguraMunicipality->id,
				'name' => 'Ward 13',
				'name_bn' => 'ওয়ার্ড ১৩',
				'code' => 'WARD_13',
				'is_active' => true,
			],
			[
				'union_id' => $boguraMunicipality->id,
				'name' => 'Ward 14',
				'name_bn' => 'ওয়ার্ড ১৪',
				'code' => 'WARD_14',
				'is_active' => true,
			],
			[
				'union_id' => $boguraMunicipality->id,
				'name' => 'Ward 15',
				'name_bn' => 'ওয়ার্ড ১৫',
				'code' => 'WARD_15',
				'is_active' => true,
			],
			[
				'union_id' => $boguraMunicipality->id,
				'name' => 'Ward 16',
				'name_bn' => 'ওয়ার্ড ১৬',
				'code' => 'WARD_16',
				'is_active' => true,
			],
			[
				'union_id' => $boguraMunicipality->id,
				'name' => 'Ward 17',
				'name_bn' => 'ওয়ার্ড ১৭',
				'code' => 'WARD_17',
				'is_active' => true,
			],
			[
				'union_id' => $boguraMunicipality->id,
				'name' => 'Ward 18',
				'name_bn' => 'ওয়ার্ড ১৮',
				'code' => 'WARD_18',
				'is_active' => true,
			],
			[
				'union_id' => $boguraMunicipality->id,
				'name' => 'Ward 19',
				'name_bn' => 'ওয়ার্ড ১৯',
				'code' => 'WARD_19',
				'is_active' => true,
			],
			[
				'union_id' => $boguraMunicipality->id,
				'name' => 'Ward 20',
				'name_bn' => 'ওয়ার্ড ২০',
				'code' => 'WARD_20',
				'is_active' => true,
			],
		];

		foreach ($wards as $ward) {
			Ward::create($ward);
		}
	}
}