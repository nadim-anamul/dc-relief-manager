<?php

namespace Database\Seeders;

use App\Models\Union;
use App\Models\Upazila;
use Illuminate\Database\Seeder;

class UnionSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		$boguraSadar = Upazila::where('code', 'BOG_SADAR')->first();

		if (!$boguraSadar) {
			$this->command->error('Bogura Sadar Upazila not found. Please run UpazilaSeeder first.');
			return;
		}

		$unions = [
			[
				'upazila_id' => $boguraSadar->id,
				'name' => 'Bogura Municipality',
				'name_bn' => 'বগুড়া পৌরসভা',
				'code' => 'BOG_MUNI',
				'is_active' => true,
			],
			[
				'upazila_id' => $boguraSadar->id,
				'name' => 'Chakla',
				'name_bn' => 'চাকলা',
				'code' => 'CHAKLA',
				'is_active' => true,
			],
			[
				'upazila_id' => $boguraSadar->id,
				'name' => 'Chandaikona',
				'name_bn' => 'চান্দাইকোনা',
				'code' => 'CHANDAIKONA',
				'is_active' => true,
			],
			[
				'upazila_id' => $boguraSadar->id,
				'name' => 'Chhatiangram',
				'name_bn' => 'ছাতিয়ানগ্রাম',
				'code' => 'CHHATIANGRAM',
				'is_active' => true,
			],
			[
				'upazila_id' => $boguraSadar->id,
				'name' => 'Chondropur',
				'name_bn' => 'চন্দ্রপুর',
				'code' => 'CHONDROPUR',
				'is_active' => true,
			],
			[
				'upazila_id' => $boguraSadar->id,
				'name' => 'Dakshinpara',
				'name_bn' => 'দক্ষিণপাড়া',
				'code' => 'DAKSHINPARA',
				'is_active' => true,
			],
			[
				'upazila_id' => $boguraSadar->id,
				'name' => 'Dudhgram',
				'name_bn' => 'দুধগ্রাম',
				'code' => 'DUDHGRAM',
				'is_active' => true,
			],
			[
				'upazila_id' => $boguraSadar->id,
				'name' => 'Gokul',
				'name_bn' => 'গোকুল',
				'code' => 'GOKUL',
				'is_active' => true,
			],
			[
				'upazila_id' => $boguraSadar->id,
				'name' => 'Gopinathpur',
				'name_bn' => 'গোপীনাথপুর',
				'code' => 'GOPINATHPUR',
				'is_active' => true,
			],
			[
				'upazila_id' => $boguraSadar->id,
				'name' => 'Khanpur',
				'name_bn' => 'খানপুর',
				'code' => 'KHANPUR',
				'is_active' => true,
			],
			[
				'upazila_id' => $boguraSadar->id,
				'name' => 'Kirtipur',
				'name_bn' => 'কীর্তিপুর',
				'code' => 'KIRTIPUR',
				'is_active' => true,
			],
			[
				'upazila_id' => $boguraSadar->id,
				'name' => 'Kutubpur',
				'name_bn' => 'কুতুবপুর',
				'code' => 'KUTUBPUR',
				'is_active' => true,
			],
			[
				'upazila_id' => $boguraSadar->id,
				'name' => 'Lahiri',
				'name_bn' => 'লাহিড়ী',
				'code' => 'LAHIRI',
				'is_active' => true,
			],
			[
				'upazila_id' => $boguraSadar->id,
				'name' => 'Majhira',
				'name_bn' => 'মাঝিরা',
				'code' => 'MAJHIRA',
				'is_active' => true,
			],
			[
				'upazila_id' => $boguraSadar->id,
				'name' => 'Nishindara',
				'name_bn' => 'নিশিন্দারা',
				'code' => 'NISHINDARA',
				'is_active' => true,
			],
			[
				'upazila_id' => $boguraSadar->id,
				'name' => 'Palli Unnyan',
				'name_bn' => 'পল্লী উন্নয়ন',
				'code' => 'PALLI_UNNYAN',
				'is_active' => true,
			],
			[
				'upazila_id' => $boguraSadar->id,
				'name' => 'Radhanagar',
				'name_bn' => 'রাধানগর',
				'code' => 'RADHANAGAR',
				'is_active' => true,
			],
			[
				'upazila_id' => $boguraSadar->id,
				'name' => 'Ramchandrapur',
				'name_bn' => 'রামচন্দ্রপুর',
				'code' => 'RAMCHANDRAPUR',
				'is_active' => true,
			],
			[
				'upazila_id' => $boguraSadar->id,
				'name' => 'Sekherkola',
				'name_bn' => 'শেখেরকোলা',
				'code' => 'SEKHERKOLA',
				'is_active' => true,
			],
			[
				'upazila_id' => $boguraSadar->id,
				'name' => 'Shabgram',
				'name_bn' => 'শাবগ্রাম',
				'code' => 'SHABGRAM',
				'is_active' => true,
			],
		];

		foreach ($unions as $union) {
			Union::create($union);
		}
	}
}