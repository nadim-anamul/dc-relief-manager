<?php

namespace Database\Seeders;

use App\Models\Upazila;
use App\Models\Zilla;
use Illuminate\Database\Seeder;

class UpazilaSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		$boguraZilla = Zilla::where('code', 'BOG')->first();

		if (!$boguraZilla) {
			$this->command->error('Bogura Zilla not found. Please run ZillaSeeder first.');
			return;
		}

		$upazilas = [
			[
				'zilla_id' => $boguraZilla->id,
				'name' => 'Bogura Sadar',
				'name_bn' => 'বগুড়া সদর',
				'code' => 'BOG_SADAR',
				'is_active' => true,
			],
			[
				'zilla_id' => $boguraZilla->id,
				'name' => 'Adamdighi',
				'name_bn' => 'আদমদিঘী',
				'code' => 'ADAM',
				'is_active' => true,
			],
			[
				'zilla_id' => $boguraZilla->id,
				'name' => 'Dhunat',
				'name_bn' => 'ধুনট',
				'code' => 'DHUNAT',
				'is_active' => true,
			],
			[
				'zilla_id' => $boguraZilla->id,
				'name' => 'Dupchanchia',
				'name_bn' => 'দুপচাঁচিয়া',
				'code' => 'DUPCHANCHIA',
				'is_active' => true,
			],
			[
				'zilla_id' => $boguraZilla->id,
				'name' => 'Gabtali',
				'name_bn' => 'গাবতলী',
				'code' => 'GABTALI',
				'is_active' => true,
			],
			[
				'zilla_id' => $boguraZilla->id,
				'name' => 'Kahaloo',
				'name_bn' => 'কাহালু',
				'code' => 'KAHALOO',
				'is_active' => true,
			],
			[
				'zilla_id' => $boguraZilla->id,
				'name' => 'Nandigram',
				'name_bn' => 'নন্দীগ্রাম',
				'code' => 'NANDIGRAM',
				'is_active' => true,
			],
			[
				'zilla_id' => $boguraZilla->id,
				'name' => 'Sariakandi',
				'name_bn' => 'সারিয়াকান্দি',
				'code' => 'SARIAKANDI',
				'is_active' => true,
			],
			[
				'zilla_id' => $boguraZilla->id,
				'name' => 'Sherpur',
				'name_bn' => 'শেরপুর',
				'code' => 'SHERPUR',
				'is_active' => true,
			],
			[
				'zilla_id' => $boguraZilla->id,
				'name' => 'Shibganj',
				'name_bn' => 'শিবগঞ্জ',
				'code' => 'SHIBGANJ',
				'is_active' => true,
			],
			[
				'zilla_id' => $boguraZilla->id,
				'name' => 'Sonatala',
				'name_bn' => 'সোনাতলা',
				'code' => 'SONATALA',
				'is_active' => true,
			],
		];

		foreach ($upazilas as $upazila) {
			Upazila::create($upazila);
		}
	}
}