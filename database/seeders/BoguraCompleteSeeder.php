<?php

namespace Database\Seeders;

use App\Models\Zilla;
use App\Models\Upazila;
use App\Models\Union;
use App\Models\Ward;
use Illuminate\Database\Seeder;

class BoguraCompleteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creating complete Bogura administrative structure...');

        // Create Bogura Zilla
        $boguraZilla = Zilla::firstOrCreate(
            ['code' => 'BOG'],
            [
                'name' => 'Bogura',
                'name_bn' => 'বগুড়া',
                'code' => 'BOG',
                'is_active' => true,
            ]
        );

        $this->command->info('Created Bogura Zilla');

        // Create all Upazilas of Bogura with complete data
        $upazilas = [
            [
                'name' => 'Bogura Sadar',
                'name_bn' => 'বগুড়া সদর',
                'code' => 'BOG_SADAR',
                'unions' => [
                    ['name' => 'Bogura Sadar', 'name_bn' => 'বগুড়া সদর', 'code' => 'BOG_SADAR_01'],
                    ['name' => 'Chandipur', 'name_bn' => 'চন্দিপুর', 'code' => 'BOG_SADAR_02'],
                    ['name' => 'Durgapur', 'name_bn' => 'দুর্গাপুর', 'code' => 'BOG_SADAR_03'],
                    ['name' => 'Gokul', 'name_bn' => 'গোকুল', 'code' => 'BOG_SADAR_04'],
                    ['name' => 'Lahiri', 'name_bn' => 'লাহিড়ী', 'code' => 'BOG_SADAR_05'],
                    ['name' => 'Namuja', 'name_bn' => 'নামুজা', 'code' => 'BOG_SADAR_06'],
                    ['name' => 'Nishindara', 'name_bn' => 'নিশিন্দারা', 'code' => 'BOG_SADAR_07'],
                    ['name' => 'Noongola', 'name_bn' => 'নূনগোলা', 'code' => 'BOG_SADAR_08'],
                    ['name' => 'Rajapur', 'name_bn' => 'রাজাপুর', 'code' => 'BOG_SADAR_09'],
                    ['name' => 'Sekherkola', 'name_bn' => 'শেখরকোলা', 'code' => 'BOG_SADAR_10'],
                    ['name' => 'Shahbandha', 'name_bn' => 'শাহবান্ধা', 'code' => 'BOG_SADAR_11'],
                    ['name' => 'Shahjahanpur', 'name_bn' => 'শাহজাহানপুর', 'code' => 'BOG_SADAR_12'],
                    ['name' => 'Sultanganj', 'name_bn' => 'সুলতানগঞ্জ', 'code' => 'BOG_SADAR_13'],
                    ['name' => 'Ullapara', 'name_bn' => 'উল্লাপাড়া', 'code' => 'BOG_SADAR_14'],
                ]
            ],
            [
                'name' => 'Adamdighi',
                'name_bn' => 'আদমদিঘী',
                'code' => 'ADAM',
                'unions' => [
                    ['name' => 'Adamdighi', 'name_bn' => 'আদমদিঘী', 'code' => 'ADAM_01'],
                    ['name' => 'Chapapur', 'name_bn' => 'চাপাপুর', 'code' => 'ADAM_02'],
                    ['name' => 'Chhatiangram', 'name_bn' => 'ছাতিয়ানগ্রাম', 'code' => 'ADAM_03'],
                    ['name' => 'Kundugram', 'name_bn' => 'কুন্ডুগ্রাম', 'code' => 'ADAM_04'],
                    ['name' => 'Nandigram', 'name_bn' => 'নন্দীগ্রাম', 'code' => 'ADAM_05'],
                    ['name' => 'Nasratpur', 'name_bn' => 'নসরতপুর', 'code' => 'ADAM_06'],
                    ['name' => 'Santahar', 'name_bn' => 'সান্তাহার', 'code' => 'ADAM_07'],
                    ['name' => 'Sundarpur', 'name_bn' => 'সুন্দরপুর', 'code' => 'ADAM_08'],
                ]
            ],
            [
                'name' => 'Dhunat',
                'name_bn' => 'ধুনট',
                'code' => 'DHUNAT',
                'unions' => [
                    ['name' => 'Dhunat', 'name_bn' => 'ধুনট', 'code' => 'DHUNAT_01'],
                    ['name' => 'Bhandarbari', 'name_bn' => 'ভান্ডারবাড়ী', 'code' => 'DHUNAT_02'],
                    ['name' => 'Champapur', 'name_bn' => 'চম্পাপুর', 'code' => 'DHUNAT_03'],
                    ['name' => 'Goshaibari', 'name_bn' => 'গোশাইবাড়ী', 'code' => 'DHUNAT_04'],
                    ['name' => 'Khanpur', 'name_bn' => 'খানপুর', 'code' => 'DHUNAT_05'],
                    ['name' => 'Kushdaha', 'name_bn' => 'কুশদহ', 'code' => 'DHUNAT_06'],
                    ['name' => 'Madla', 'name_bn' => 'মাদলা', 'code' => 'DHUNAT_07'],
                    ['name' => 'Majhgram', 'name_bn' => 'মাঝগ্রাম', 'code' => 'DHUNAT_08'],
                    ['name' => 'Mokamtala', 'name_bn' => 'মোকামতলা', 'code' => 'DHUNAT_09'],
                    ['name' => 'Palli Unnyan', 'name_bn' => 'পল্লী উন্নয়ন', 'code' => 'DHUNAT_10'],
                    ['name' => 'Shimabari', 'name_bn' => 'শিমাবাড়ী', 'code' => 'DHUNAT_11'],
                ]
            ],
            [
                'name' => 'Dupchanchia',
                'name_bn' => 'দুপচাঁচিয়া',
                'code' => 'DUPCHANCHIA',
                'unions' => [
                    ['name' => 'Dupchanchia', 'name_bn' => 'দুপচাঁচিয়া', 'code' => 'DUP_01'],
                    ['name' => 'Alokdihi', 'name_bn' => 'আলোকদীহ', 'code' => 'DUP_02'],
                    ['name' => 'Bhatgram', 'name_bn' => 'ভাটগ্রাম', 'code' => 'DUP_03'],
                    ['name' => 'Chamrul', 'name_bn' => 'চামরুল', 'code' => 'DUP_04'],
                    ['name' => 'Chandanbaisha', 'name_bn' => 'চন্দনবাইশা', 'code' => 'DUP_05'],
                    ['name' => 'Gobindapur', 'name_bn' => 'গোবিন্দপুর', 'code' => 'DUP_06'],
                    ['name' => 'Mamudpur', 'name_bn' => 'মামুদপুর', 'code' => 'DUP_07'],
                    ['name' => 'Pirab', 'name_bn' => 'পিরাব', 'code' => 'DUP_08'],
                    ['name' => 'Shyamkur', 'name_bn' => 'শ্যামকুড়', 'code' => 'DUP_09'],
                    ['name' => 'Sundarpur', 'name_bn' => 'সুন্দরপুর', 'code' => 'DUP_10'],
                ]
            ],
            [
                'name' => 'Gabtali',
                'name_bn' => 'গাবতলী',
                'code' => 'GABTALI',
                'unions' => [
                    ['name' => 'Gabtali', 'name_bn' => 'গাবতলী', 'code' => 'GAB_01'],
                    ['name' => 'Balia Dighi', 'name_bn' => 'বালিয়া দীঘি', 'code' => 'GAB_02'],
                    ['name' => 'Bhatra', 'name_bn' => 'ভাটরা', 'code' => 'GAB_03'],
                    ['name' => 'Durgahata', 'name_bn' => 'দুর্গাহাটা', 'code' => 'GAB_04'],
                    ['name' => 'Gokul', 'name_bn' => 'গোকুল', 'code' => 'GAB_05'],
                    ['name' => 'Khanpur', 'name_bn' => 'খানপুর', 'code' => 'GAB_06'],
                    ['name' => 'Krishnapur', 'name_bn' => 'কৃষ্ণপুর', 'code' => 'GAB_07'],
                    ['name' => 'Majhgram', 'name_bn' => 'মাঝগ্রাম', 'code' => 'GAB_08'],
                    ['name' => 'Mokamtala', 'name_bn' => 'মোকামতলা', 'code' => 'GAB_09'],
                    ['name' => 'Nandigram', 'name_bn' => 'নন্দীগ্রাম', 'code' => 'GAB_10'],
                    ['name' => 'Nasratpur', 'name_bn' => 'নসরতপুর', 'code' => 'GAB_11'],
                    ['name' => 'Santhia', 'name_bn' => 'সানথিয়া', 'code' => 'GAB_12'],
                    ['name' => 'Sundarpur', 'name_bn' => 'সুন্দরপুর', 'code' => 'GAB_13'],
                ]
            ],
            [
                'name' => 'Kahaloo',
                'name_bn' => 'কাহালু',
                'code' => 'KAHALOO',
                'unions' => [
                    ['name' => 'Kahaloo', 'name_bn' => 'কাহালু', 'code' => 'KAH_01'],
                    ['name' => 'Chhatiangram', 'name_bn' => 'ছাতিয়ানগ্রাম', 'code' => 'KAH_02'],
                    ['name' => 'Dhamoir', 'name_bn' => 'ধামইর', 'code' => 'KAH_03'],
                    ['name' => 'Jamgaon', 'name_bn' => 'জামগাঁও', 'code' => 'KAH_04'],
                    ['name' => 'Kahaloo', 'name_bn' => 'কাহালু', 'code' => 'KAH_05'],
                    ['name' => 'Khalisabari', 'name_bn' => 'খলিসাবাড়ী', 'code' => 'KAH_06'],
                    ['name' => 'Kundugram', 'name_bn' => 'কুন্ডুগ্রাম', 'code' => 'KAH_07'],
                    ['name' => 'Mokamtala', 'name_bn' => 'মোকামতলা', 'code' => 'KAH_08'],
                    ['name' => 'Nandigram', 'name_bn' => 'নন্দীগ্রাম', 'code' => 'KAH_09'],
                    ['name' => 'Nasratpur', 'name_bn' => 'নসরতপুর', 'code' => 'KAH_10'],
                ]
            ],
            [
                'name' => 'Nandigram',
                'name_bn' => 'নন্দীগ্রাম',
                'code' => 'NANDIGRAM',
                'unions' => [
                    ['name' => 'Nandigram', 'name_bn' => 'নন্দীগ্রাম', 'code' => 'NAN_01'],
                    ['name' => 'Balia Dighi', 'name_bn' => 'বালিয়া দীঘি', 'code' => 'NAN_02'],
                    ['name' => 'Bhatra', 'name_bn' => 'ভাটরা', 'code' => 'NAN_03'],
                    ['name' => 'Durgahata', 'name_bn' => 'দুর্গাহাটা', 'code' => 'NAN_04'],
                    ['name' => 'Gokul', 'name_bn' => 'গোকুল', 'code' => 'NAN_05'],
                    ['name' => 'Khanpur', 'name_bn' => 'খানপুর', 'code' => 'NAN_06'],
                    ['name' => 'Krishnapur', 'name_bn' => 'কৃষ্ণপুর', 'code' => 'NAN_07'],
                    ['name' => 'Majhgram', 'name_bn' => 'মাঝগ্রাম', 'code' => 'NAN_08'],
                    ['name' => 'Mokamtala', 'name_bn' => 'মোকামতলা', 'code' => 'NAN_09'],
                ]
            ],
            [
                'name' => 'Sariakandi',
                'name_bn' => 'সারিয়াকান্দি',
                'code' => 'SARIAKANDI',
                'unions' => [
                    ['name' => 'Sariakandi', 'name_bn' => 'সারিয়াকান্দি', 'code' => 'SAR_01'],
                    ['name' => 'Balia Dighi', 'name_bn' => 'বালিয়া দীঘি', 'code' => 'SAR_02'],
                    ['name' => 'Bhatra', 'name_bn' => 'ভাটরা', 'code' => 'SAR_03'],
                    ['name' => 'Durgahata', 'name_bn' => 'দুর্গাহাটা', 'code' => 'SAR_04'],
                    ['name' => 'Gokul', 'name_bn' => 'গোকুল', 'code' => 'SAR_05'],
                    ['name' => 'Khanpur', 'name_bn' => 'খানপুর', 'code' => 'SAR_06'],
                    ['name' => 'Krishnapur', 'name_bn' => 'কৃষ্ণপুর', 'code' => 'SAR_07'],
                    ['name' => 'Majhgram', 'name_bn' => 'মাঝগ্রাম', 'code' => 'SAR_08'],
                    ['name' => 'Mokamtala', 'name_bn' => 'মোকামতলা', 'code' => 'SAR_09'],
                    ['name' => 'Nandigram', 'name_bn' => 'নন্দীগ্রাম', 'code' => 'SAR_10'],
                    ['name' => 'Nasratpur', 'name_bn' => 'নসরতপুর', 'code' => 'SAR_11'],
                ]
            ],
            [
                'name' => 'Sherpur',
                'name_bn' => 'শেরপুর',
                'code' => 'SHERPUR',
                'unions' => [
                    ['name' => 'Sherpur', 'name_bn' => 'শেরপুর', 'code' => 'SHE_01'],
                    ['name' => 'Balia Dighi', 'name_bn' => 'বালিয়া দীঘি', 'code' => 'SHE_02'],
                    ['name' => 'Bhatra', 'name_bn' => 'ভাটরা', 'code' => 'SHE_03'],
                    ['name' => 'Durgahata', 'name_bn' => 'দুর্গাহাটা', 'code' => 'SHE_04'],
                    ['name' => 'Gokul', 'name_bn' => 'গোকুল', 'code' => 'SHE_05'],
                    ['name' => 'Khanpur', 'name_bn' => 'খানপুর', 'code' => 'SHE_06'],
                    ['name' => 'Krishnapur', 'name_bn' => 'কৃষ্ণপুর', 'code' => 'SHE_07'],
                    ['name' => 'Majhgram', 'name_bn' => 'মাঝগ্রাম', 'code' => 'SHE_08'],
                    ['name' => 'Mokamtala', 'name_bn' => 'মোকামতলা', 'code' => 'SHE_09'],
                    ['name' => 'Nandigram', 'name_bn' => 'নন্দীগ্রাম', 'code' => 'SHE_10'],
                ]
            ],
            [
                'name' => 'Shibganj',
                'name_bn' => 'শিবগঞ্জ',
                'code' => 'SHIBGANJ',
                'unions' => [
                    ['name' => 'Shibganj', 'name_bn' => 'শিবগঞ্জ', 'code' => 'SHI_01'],
                    ['name' => 'Balia Dighi', 'name_bn' => 'বালিয়া দীঘি', 'code' => 'SHI_02'],
                    ['name' => 'Bhatra', 'name_bn' => 'ভাটরা', 'code' => 'SHI_03'],
                    ['name' => 'Durgahata', 'name_bn' => 'দুর্গাহাটা', 'code' => 'SHI_04'],
                    ['name' => 'Gokul', 'name_bn' => 'গোকুল', 'code' => 'SHI_05'],
                    ['name' => 'Khanpur', 'name_bn' => 'খানপুর', 'code' => 'SHI_06'],
                    ['name' => 'Krishnapur', 'name_bn' => 'কৃষ্ণপুর', 'code' => 'SHI_07'],
                    ['name' => 'Majhgram', 'name_bn' => 'মাঝগ্রাম', 'code' => 'SHI_08'],
                    ['name' => 'Mokamtala', 'name_bn' => 'মোকামতলা', 'code' => 'SHI_09'],
                    ['name' => 'Nandigram', 'name_bn' => 'নন্দীগ্রাম', 'code' => 'SHI_10'],
                    ['name' => 'Nasratpur', 'name_bn' => 'নসরতপুর', 'code' => 'SHI_11'],
                ]
            ],
            [
                'name' => 'Sonatala',
                'name_bn' => 'সোনাতলা',
                'code' => 'SONATALA',
                'unions' => [
                    ['name' => 'Sonatala', 'name_bn' => 'সোনাতলা', 'code' => 'SON_01'],
                    ['name' => 'Balia Dighi', 'name_bn' => 'বালিয়া দীঘি', 'code' => 'SON_02'],
                    ['name' => 'Bhatra', 'name_bn' => 'ভাটরা', 'code' => 'SON_03'],
                    ['name' => 'Durgahata', 'name_bn' => 'দুর্গাহাটা', 'code' => 'SON_04'],
                    ['name' => 'Gokul', 'name_bn' => 'গোকুল', 'code' => 'SON_05'],
                    ['name' => 'Khanpur', 'name_bn' => 'খানপুর', 'code' => 'SON_06'],
                    ['name' => 'Krishnapur', 'name_bn' => 'কৃষ্ণপুর', 'code' => 'SON_07'],
                    ['name' => 'Majhgram', 'name_bn' => 'মাঝগ্রাম', 'code' => 'SON_08'],
                    ['name' => 'Mokamtala', 'name_bn' => 'মোকামতলা', 'code' => 'SON_09'],
                    ['name' => 'Nandigram', 'name_bn' => 'নন্দীগ্রাম', 'code' => 'SON_10'],
                ]
            ]
        ];

        $totalUpazilas = 0;
        $totalUnions = 0;
        $totalWards = 0;

        foreach ($upazilas as $upazilaData) {
            $upazila = Upazila::firstOrCreate(
                ['code' => $upazilaData['code']],
                [
                    'zilla_id' => $boguraZilla->id,
                    'name' => $upazilaData['name'],
                    'name_bn' => $upazilaData['name_bn'],
                    'code' => $upazilaData['code'],
                    'is_active' => true,
                ]
            );
            $totalUpazilas++;

            // Create Unions for this Upazila
            foreach ($upazilaData['unions'] as $unionData) {
                $union = Union::firstOrCreate(
                    ['code' => $unionData['code']],
                    [
                        'upazila_id' => $upazila->id,
                        'name' => $unionData['name'],
                        'name_bn' => $unionData['name_bn'],
                        'code' => $unionData['code'],
                        'is_active' => true,
                    ]
                );
                $totalUnions++;

                // Create Wards for this Union (typically 9 wards per union)
                for ($wardNumber = 1; $wardNumber <= 9; $wardNumber++) {
                    $ward = Ward::firstOrCreate(
                        [
                            'union_id' => $union->id,
                            'code' => $union->code . '_W' . str_pad($wardNumber, 2, '0', STR_PAD_LEFT),
                        ],
                        [
                            'union_id' => $union->id,
                            'name' => $union->name . ' Ward ' . $wardNumber,
                            'name_bn' => $union->name_bn . ' ওয়ার্ড ' . $wardNumber,
                            'code' => $union->code . '_W' . str_pad($wardNumber, 2, '0', STR_PAD_LEFT),
                            'is_active' => true,
                        ]
                    );
                    $totalWards++;
                }
            }
        }

        $this->command->info("Created complete Bogura administrative structure:");
        $this->command->info("- 1 Zilla (বগুড়া)");
        $this->command->info("- {$totalUpazilas} Upazilas");
        $this->command->info("- {$totalUnions} Unions");
        $this->command->info("- {$totalWards} Wards");
    }
}
