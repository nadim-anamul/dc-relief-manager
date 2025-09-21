<?php

namespace Database\Seeders;

use App\Models\Zilla;
use Illuminate\Database\Seeder;

class ZillaSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		$zillas = [
			[
				'name' => 'Bogura',
				'name_bn' => 'বগুড়া',
				'code' => 'BOG',
				'is_active' => true,
			],
		];

		foreach ($zillas as $zilla) {
			Zilla::create($zilla);
		}
	}
}