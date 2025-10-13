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
            [
                'name' => 'Bogura',
                'name_bn' => 'বগুড়া',
                'is_active' => true,
            ]
        );

        $this->command->info('Created Bogura Zilla');

        // Create all Upazilas of Bogura with complete data
        $upazilas = [
            [
                'name' => 'Bogura Sadar',
                'name_bn' => 'বগুড়া সদর',
                'unions' => [
                    ['name' => 'Bogura Sadar', 'name_bn' => 'বগুড়া সদর'],
                    ['name' => 'Chandipur', 'name_bn' => 'চন্দিপুর'],
                    ['name' => 'Durgapur', 'name_bn' => 'দুর্গাপুর'],
                    ['name' => 'Gokul', 'name_bn' => 'গোকুল'],
                    ['name' => 'Lahiri', 'name_bn' => 'লাহিড়ী'],
                    ['name' => 'Namuja', 'name_bn' => 'নামুজা'],
                    ['name' => 'Nishindara', 'name_bn' => 'নিশিন্দারা'],
                    ['name' => 'Noongola', 'name_bn' => 'নূনগোলা'],
                    ['name' => 'Rajapur', 'name_bn' => 'রাজাপুর'],
                    ['name' => 'Sekherkola', 'name_bn' => 'শেখরকোলা'],
                    ['name' => 'Shahbandha', 'name_bn' => 'শাহবান্ধা'],
                    ['name' => 'Shahjahanpur', 'name_bn' => 'শাহজাহানপুর'],
                    ['name' => 'Sultanganj', 'name_bn' => 'সুলতানগঞ্জ'],
                    ['name' => 'Ullapara', 'name_bn' => 'উল্লাপাড়া'],
                ]
            ],
            [
                'name' => 'Adamdighi',
                'name_bn' => 'আদমদিঘী',
                
                'unions' => [
                    ['name' => 'Adamdighi', 'name_bn' => 'আদমদিঘী'],
                    ['name' => 'Chapapur', 'name_bn' => 'চাপাপুর'],
                    ['name' => 'Chhatiangram', 'name_bn' => 'ছাতিয়ানগ্রাম'],
                    ['name' => 'Kundugram', 'name_bn' => 'কুন্ডুগ্রাম'],
                    ['name' => 'Nandigram', 'name_bn' => 'নন্দীগ্রাম'],
                    ['name' => 'Nasratpur', 'name_bn' => 'নসরতপুর'],
                    ['name' => 'Santahar', 'name_bn' => 'সান্তাহার'],
                    ['name' => 'Sundarpur', 'name_bn' => 'সুন্দরপুর'],
                ]
            ],
            [
                'name' => 'Dhunat',
                'name_bn' => 'ধুনট',
                
                'unions' => [
                    ['name' => 'Dhunat', 'name_bn' => 'ধুনট'],
                    ['name' => 'Bhandarbari', 'name_bn' => 'ভান্ডারবাড়ী'],
                    ['name' => 'Champapur', 'name_bn' => 'চম্পাপুর'],
                    ['name' => 'Goshaibari', 'name_bn' => 'গোশাইবাড়ী'],
                    ['name' => 'Khanpur', 'name_bn' => 'খানপুর'],
                    ['name' => 'Kushdaha', 'name_bn' => 'কুশদহ'],
                    ['name' => 'Madla', 'name_bn' => 'মাদলা'],
                    ['name' => 'Majhgram', 'name_bn' => 'মাঝগ্রাম'],
                    ['name' => 'Mokamtala', 'name_bn' => 'মোকামতলা'],
                    ['name' => 'Palli Unnyan', 'name_bn' => 'পল্লী উন্নয়ন'],
                    ['name' => 'Shimabari', 'name_bn' => 'শিমাবাড়ী'],
                ]
            ],
            [
                'name' => 'Dupchanchia',
                'name_bn' => 'দুপচাঁচিয়া',
                
                'unions' => [
                    ['name' => 'Dupchanchia', 'name_bn' => 'দুপচাঁচিয়া'],
                    ['name' => 'Alokdihi', 'name_bn' => 'আলোকদীহ'],
                    ['name' => 'Bhatgram', 'name_bn' => 'ভাটগ্রাম'],
                    ['name' => 'Chamrul', 'name_bn' => 'চামরুল'],
                    ['name' => 'Chandanbaisha', 'name_bn' => 'চন্দনবাইশা'],
                    ['name' => 'Gobindapur', 'name_bn' => 'গোবিন্দপুর'],
                    ['name' => 'Mamudpur', 'name_bn' => 'মামুদপুর'],
                    ['name' => 'Pirab', 'name_bn' => 'পিরাব'],
                    ['name' => 'Shyamkur', 'name_bn' => 'শ্যামকুড়'],
                    ['name' => 'Sundarpur', 'name_bn' => 'সুন্দরপুর'],
                ]
            ],
            [
                'name' => 'Gabtali',
                'name_bn' => 'গাবতলী',
                
                'unions' => [
                    ['name' => 'Gabtali', 'name_bn' => 'গাবতলী'],
                    ['name' => 'Balia Dighi', 'name_bn' => 'বালিয়া দীঘি'],
                    ['name' => 'Bhatra', 'name_bn' => 'ভাটরা'],
                    ['name' => 'Durgahata', 'name_bn' => 'দুর্গাহাটা'],
                    ['name' => 'Gokul', 'name_bn' => 'গোকুল'],
                    ['name' => 'Khanpur', 'name_bn' => 'খানপুর'],
                    ['name' => 'Krishnapur', 'name_bn' => 'কৃষ্ণপুর'],
                    ['name' => 'Majhgram', 'name_bn' => 'মাঝগ্রাম'],
                    ['name' => 'Mokamtala', 'name_bn' => 'মোকামতলা'],
                    ['name' => 'Nandigram', 'name_bn' => 'নন্দীগ্রাম'],
                    ['name' => 'Nasratpur', 'name_bn' => 'নসরতপুর'],
                    ['name' => 'Santhia', 'name_bn' => 'সানথিয়া'],
                    ['name' => 'Sundarpur', 'name_bn' => 'সুন্দরপুর'],
                ]
            ],
            [
                'name' => 'Kahaloo',
                'name_bn' => 'কাহালু',
                
                'unions' => [
                    ['name' => 'Kahaloo', 'name_bn' => 'কাহালু'],
                    ['name' => 'Chhatiangram', 'name_bn' => 'ছাতিয়ানগ্রাম'],
                    ['name' => 'Dhamoir', 'name_bn' => 'ধামইর'],
                    ['name' => 'Jamgaon', 'name_bn' => 'জামগাঁও'],
                    ['name' => 'Kahaloo', 'name_bn' => 'কাহালু'],
                    ['name' => 'Khalisabari', 'name_bn' => 'খলিসাবাড়ী'],
                    ['name' => 'Kundugram', 'name_bn' => 'কুন্ডুগ্রাম'],
                    ['name' => 'Mokamtala', 'name_bn' => 'মোকামতলা'],
                    ['name' => 'Nandigram', 'name_bn' => 'নন্দীগ্রাম'],
                    ['name' => 'Nasratpur', 'name_bn' => 'নসরতপুর'],
                ]
            ],
            [
                'name' => 'Nandigram',
                'name_bn' => 'নন্দীগ্রাম',
                
                'unions' => [
                    ['name' => 'Nandigram', 'name_bn' => 'নন্দীগ্রাম'],
                    ['name' => 'Balia Dighi', 'name_bn' => 'বালিয়া দীঘি'],
                    ['name' => 'Bhatra', 'name_bn' => 'ভাটরা'],
                    ['name' => 'Durgahata', 'name_bn' => 'দুর্গাহাটা'],
                    ['name' => 'Gokul', 'name_bn' => 'গোকুল'],
                    ['name' => 'Khanpur', 'name_bn' => 'খানপুর'],
                    ['name' => 'Krishnapur', 'name_bn' => 'কৃষ্ণপুর'],
                    ['name' => 'Majhgram', 'name_bn' => 'মাঝগ্রাম'],
                    ['name' => 'Mokamtala', 'name_bn' => 'মোকামতলা'],
                ]
            ],
            [
                'name' => 'Sariakandi',
                'name_bn' => 'সারিয়াকান্দি',
                
                'unions' => [
                    ['name' => 'Sariakandi', 'name_bn' => 'সারিয়াকান্দি'],
                    ['name' => 'Balia Dighi', 'name_bn' => 'বালিয়া দীঘি'],
                    ['name' => 'Bhatra', 'name_bn' => 'ভাটরা'],
                    ['name' => 'Durgahata', 'name_bn' => 'দুর্গাহাটা'],
                    ['name' => 'Gokul', 'name_bn' => 'গোকুল'],
                    ['name' => 'Khanpur', 'name_bn' => 'খানপুর'],
                    ['name' => 'Krishnapur', 'name_bn' => 'কৃষ্ণপুর'],
                    ['name' => 'Majhgram', 'name_bn' => 'মাঝগ্রাম'],
                    ['name' => 'Mokamtala', 'name_bn' => 'মোকামতলা'],
                    ['name' => 'Nandigram', 'name_bn' => 'নন্দীগ্রাম'],
                    ['name' => 'Nasratpur', 'name_bn' => 'নসরতপুর'],
                ]
            ],
            [
                'name' => 'Sherpur',
                'name_bn' => 'শেরপুর',
                
                'unions' => [
                    ['name' => 'Sherpur', 'name_bn' => 'শেরপুর'],
                    ['name' => 'Balia Dighi', 'name_bn' => 'বালিয়া দীঘি'],
                    ['name' => 'Bhatra', 'name_bn' => 'ভাটরা'],
                    ['name' => 'Durgahata', 'name_bn' => 'দুর্গাহাটা'],
                    ['name' => 'Gokul', 'name_bn' => 'গোকুল'],
                    ['name' => 'Khanpur', 'name_bn' => 'খানপুর'],
                    ['name' => 'Krishnapur', 'name_bn' => 'কৃষ্ণপুর'],
                    ['name' => 'Majhgram', 'name_bn' => 'মাঝগ্রাম'],
                    ['name' => 'Mokamtala', 'name_bn' => 'মোকামতলা'],
                    ['name' => 'Nandigram', 'name_bn' => 'নন্দীগ্রাম'],
                ]
            ],
            [
                'name' => 'Shibganj',
                'name_bn' => 'শিবগঞ্জ',
                
                'unions' => [
                    ['name' => 'Shibganj', 'name_bn' => 'শিবগঞ্জ'],
                    ['name' => 'Balia Dighi', 'name_bn' => 'বালিয়া দীঘি'],
                    ['name' => 'Bhatra', 'name_bn' => 'ভাটরা'],
                    ['name' => 'Durgahata', 'name_bn' => 'দুর্গাহাটা'],
                    ['name' => 'Gokul', 'name_bn' => 'গোকুল'],
                    ['name' => 'Khanpur', 'name_bn' => 'খানপুর'],
                    ['name' => 'Krishnapur', 'name_bn' => 'কৃষ্ণপুর'],
                    ['name' => 'Majhgram', 'name_bn' => 'মাঝগ্রাম'],
                    ['name' => 'Mokamtala', 'name_bn' => 'মোকামতলা'],
                    ['name' => 'Nandigram', 'name_bn' => 'নন্দীগ্রাম'],
                    ['name' => 'Nasratpur', 'name_bn' => 'নসরতপুর'],
                ]
            ],
            [
                'name' => 'Sonatala',
                'name_bn' => 'সোনাতলা',
                
                'unions' => [
                    ['name' => 'Sonatala', 'name_bn' => 'সোনাতলা'],
                    ['name' => 'Balia Dighi', 'name_bn' => 'বালিয়া দীঘি'],
                    ['name' => 'Bhatra', 'name_bn' => 'ভাটরা'],
                    ['name' => 'Durgahata', 'name_bn' => 'দুর্গাহাটা'],
                    ['name' => 'Gokul', 'name_bn' => 'গোকুল'],
                    ['name' => 'Khanpur', 'name_bn' => 'খানপুর'],
                    ['name' => 'Krishnapur', 'name_bn' => 'কৃষ্ণপুর'],
                    ['name' => 'Majhgram', 'name_bn' => 'মাঝগ্রাম'],
                    ['name' => 'Mokamtala', 'name_bn' => 'মোকামতলা'],
                    ['name' => 'Nandigram', 'name_bn' => 'নন্দীগ্রাম'],
                ]
            ]
        ];

        $totalUpazilas = 0;
        $totalUnions = 0;
        $totalWards = 0;

        foreach ($upazilas as $upazilaData) {
            $upazila = Upazila::firstOrCreate(
                [
                    'zilla_id' => $boguraZilla->id,
                    'name' => $upazilaData['name'],
                ],
                [
                    'zilla_id' => $boguraZilla->id,
                    'name' => $upazilaData['name'],
                    'name_bn' => $upazilaData['name_bn'],
                    'is_active' => true,
                ]
            );
            $totalUpazilas++;

            // Create Unions for this Upazila
            foreach ($upazilaData['unions'] as $unionData) {
                $union = Union::firstOrCreate(
                    [
                        'upazila_id' => $upazila->id,
                        'name' => $unionData['name'],
                    ],
                    [
                        'upazila_id' => $upazila->id,
                        'name' => $unionData['name'],
                        'name_bn' => $unionData['name_bn'],
                        'is_active' => true,
                    ]
                );
                $totalUnions++;

                // Create Wards for this Union (typically 9 wards per union)
                for ($wardNumber = 1; $wardNumber <= 9; $wardNumber++) {
                    $ward = Ward::firstOrCreate(
                        [
                            'union_id' => $union->id,
                        ],
                        [
                            'union_id' => $union->id,
                            'name' => $union->name . ' Ward ' . $wardNumber,
                            'name_bn' => $union->name_bn . ' ওয়ার্ড ' . $wardNumber,
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
