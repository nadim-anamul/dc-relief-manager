<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\OrganizationType;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class BanglaUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creating comprehensive Bangla users...');

        // Get organization types
        $organizationTypes = OrganizationType::all();

        // Create Super Admin Users
        $superAdmins = [
            [
                'name' => 'মোঃ আব্দুর রহমান',
                'name_en' => 'Md. Abdur Rahman',
                'email' => 'superadmin@bogura.gov.bd',
                'password' => 'password123',
                'phone' => '01712345678',
                'role' => 'super-admin',
                'organization_type_id' => null,
                'is_approved' => true,
            ],
            [
                'name' => 'ডাঃ ফাতেমা খাতুন',
                'name_en' => 'Dr. Fatema Khatun',
                'email' => 'admin@bogura.gov.bd',
                'password' => 'password123',
                'phone' => '01787654321',
                'role' => 'super-admin',
                'organization_type_id' => null,
                'is_approved' => true,
            ]
        ];

        // Create District Admin Users
        $districtAdmins = [
            [
                'name' => 'মোঃ জাহিদুল ইসলাম',
                'name_en' => 'Md. Zahidul Islam',
                'email' => 'dc@bogura.gov.bd',
                'password' => 'password123',
                'phone' => '01711111111',
                'role' => 'district-admin',
                'organization_type_id' => null,
                'is_approved' => true,
            ],
            [
                'name' => 'নাসির উদ্দিন আহমেদ',
                'name_en' => 'Nasir Uddin Ahmed',
                'email' => 'district.admin@bogura.gov.bd',
                'password' => 'password123',
                'phone' => '01722222222',
                'role' => 'district-admin',
                'organization_type_id' => null,
                'is_approved' => true,
            ]
        ];

        // Create Data Entry Users
        $dataEntryUsers = [
            [
                'name' => 'রোকসানা বেগম',
                'name_en' => 'Roksana Begum',
                'email' => 'dataentry1@bogura.gov.bd',
                'password' => 'password123',
                'phone' => '01733333333',
                'role' => 'data-entry',
                'organization_type_id' => $organizationTypes->where('name', 'NGO')->first()?->id,
                'is_approved' => true,
            ],
            [
                'name' => 'মোঃ কামাল উদ্দিন',
                'name_en' => 'Md. Kamal Uddin',
                'email' => 'dataentry2@bogura.gov.bd',
                'password' => 'password123',
                'phone' => '01744444444',
                'role' => 'data-entry',
                'organization_type_id' => $organizationTypes->where('name', 'Community Organization')->first()?->id,
                'is_approved' => true,
            ],
            [
                'name' => 'সালেহা খাতুন',
                'name_en' => 'Saleha Khatun',
                'email' => 'dataentry3@bogura.gov.bd',
                'password' => 'password123',
                'phone' => '01755555555',
                'role' => 'data-entry',
                'organization_type_id' => $organizationTypes->where('name', 'Social Welfare')->first()?->id,
                'is_approved' => true,
            ]
        ];

        // Create Viewer Users
        $viewerUsers = [
            [
                'name' => 'মোঃ আলমগীর হোসেন',
                'name_en' => 'Md. Almgir Hossain',
                'email' => 'viewer1@bogura.gov.bd',
                'password' => 'password123',
                'phone' => '01766666666',
                'role' => 'viewer',
                'organization_type_id' => $organizationTypes->where('name', 'Foundation')->first()?->id,
                'is_approved' => true,
            ],
            [
                'name' => 'রাবেয়া খাতুন',
                'name_en' => 'Rabeya Khatun',
                'email' => 'viewer2@bogura.gov.bd',
                'password' => 'password123',
                'phone' => '01777777777',
                'role' => 'viewer',
                'organization_type_id' => $organizationTypes->where('name', 'Religious Organization')->first()?->id,
                'is_approved' => true,
            ]
        ];

        // Create NGO Representatives
        $ngoUsers = [
            [
                'name' => 'মোঃ রফিকুল ইসলাম',
                'name_en' => 'Md. Rafiqul Islam',
                'email' => 'rafiqul@brac.org',
                'password' => 'password123',
                'phone' => '01788888888',
                'role' => 'data-entry',
                'organization_type_id' => $organizationTypes->where('name', 'NGO')->first()?->id,
                'is_approved' => true,
                'organization_name' => 'ব্র্যাক',
            ],
            [
                'name' => 'শাহানা পারভীন',
                'name_en' => 'Shahana Parvin',
                'email' => 'shahana@grameen.org',
                'password' => 'password123',
                'phone' => '01799999999',
                'role' => 'data-entry',
                'organization_type_id' => $organizationTypes->where('name', 'NGO')->first()?->id,
                'is_approved' => true,
                'organization_name' => 'গ্রামীণ ব্যাংক',
            ],
            [
                'name' => 'মোঃ হাসান আলী',
                'name_en' => 'Md. Hasan Ali',
                'email' => 'hasan@proshika.org',
                'password' => 'password123',
                'phone' => '01700000000',
                'role' => 'data-entry',
                'organization_type_id' => $organizationTypes->where('name', 'NGO')->first()?->id,
                'is_approved' => true,
                'organization_name' => 'প্রশিকা',
            ]
        ];

        // Create Community Organization Representatives
        $communityUsers = [
            [
                'name' => 'মোঃ আবুল কালাম',
                'name_en' => 'Md. Abul Kalam',
                'email' => 'abul@community.org',
                'password' => 'password123',
                'phone' => '01711111112',
                'role' => 'data-entry',
                'organization_type_id' => $organizationTypes->where('name', 'Community Organization')->first()?->id,
                'is_approved' => true,
                'organization_name' => 'বগুড়া সমাজ কল্যাণ সমিতি',
            ],
            [
                'name' => 'রেহানা বেগম',
                'name_en' => 'Rehana Begum',
                'email' => 'rehana@community.org',
                'password' => 'password123',
                'phone' => '01722222223',
                'role' => 'data-entry',
                'organization_type_id' => $organizationTypes->where('name', 'Community Organization')->first()?->id,
                'is_approved' => true,
                'organization_name' => 'নারী উন্নয়ন সংস্থা',
            ]
        ];

        // Create Farmers Association Representatives
        $farmerUsers = [
            [
                'name' => 'মোঃ করিম উদ্দিন',
                'name_en' => 'Md. Karim Uddin',
                'email' => 'karim@farmers.org',
                'password' => 'password123',
                'phone' => '01733333334',
                'role' => 'data-entry',
                'organization_type_id' => $organizationTypes->where('name', 'Farmers Association')->first()?->id,
                'is_approved' => true,
                'organization_name' => 'বগুড়া কৃষক সমিতি',
            ],
            [
                'name' => 'সালেহা খাতুন',
                'name_en' => 'Saleha Khatun',
                'email' => 'saleha@farmers.org',
                'password' => 'password123',
                'phone' => '01744444445',
                'role' => 'data-entry',
                'organization_type_id' => $organizationTypes->where('name', 'Farmers Association')->first()?->id,
                'is_approved' => true,
                'organization_name' => 'নারী কৃষক সংগঠন',
            ]
        ];

        // Create Foundation Representatives
        $foundationUsers = [
            [
                'name' => 'মোঃ নজরুল ইসলাম',
                'name_en' => 'Md. Nazrul Islam',
                'email' => 'nazrul@foundation.org',
                'password' => 'password123',
                'phone' => '01755555556',
                'role' => 'data-entry',
                'organization_type_id' => $organizationTypes->where('name', 'Foundation')->first()?->id,
                'is_approved' => true,
                'organization_name' => 'মানবিক সহায়তা ফাউন্ডেশন',
            ]
        ];

        // Create Emergency Response Representatives
        $emergencyUsers = [
            [
                'name' => 'মোঃ শহিদুল ইসলাম',
                'name_en' => 'Md. Shahidul Islam',
                'email' => 'shahidul@emergency.org',
                'password' => 'password123',
                'phone' => '01766666667',
                'role' => 'data-entry',
                'organization_type_id' => $organizationTypes->where('name', 'Emergency Response')->first()?->id,
                'is_approved' => true,
                'organization_name' => 'বগুড়া জরুরি সহায়তা সংস্থা',
            ]
        ];

        // Create Religious Organization Representatives
        $religiousUsers = [
            [
                'name' => 'মুফতি আব্দুল হাই',
                'name_en' => 'Mufti Abdul Hai',
                'email' => 'mufti@religious.org',
                'password' => 'password123',
                'phone' => '01777777778',
                'role' => 'data-entry',
                'organization_type_id' => $organizationTypes->where('name', 'Religious Organization')->first()?->id,
                'is_approved' => true,
                'organization_name' => 'ইসলামিক রিলিফ কমিটি',
            ]
        ];

        // Create Social Welfare Representatives
        $socialWelfareUsers = [
            [
                'name' => 'ডাঃ রোকেয়া খাতুন',
                'name_en' => 'Dr. Rokeya Khatun',
                'email' => 'rokeya@social.org',
                'password' => 'password123',
                'phone' => '01788888889',
                'role' => 'data-entry',
                'organization_type_id' => $organizationTypes->where('name', 'Social Welfare')->first()?->id,
                'is_approved' => true,
                'organization_name' => 'সামাজিক কল্যাণ সংস্থা',
            ]
        ];

        // Create Youth Organization Representatives
        $youthUsers = [
            [
                'name' => 'মোঃ সাকিব আহমেদ',
                'name_en' => 'Md. Sakib Ahmed',
                'email' => 'sakib@youth.org',
                'password' => 'password123',
                'phone' => '01799999990',
                'role' => 'data-entry',
                'organization_type_id' => $organizationTypes->where('name', 'Youth Organization')->first()?->id,
                'is_approved' => true,
                'organization_name' => 'তরুণ সমাজ উন্নয়ন সংগঠন',
            ]
        ];

        // Combine all users
        $allUsers = array_merge(
            $superAdmins,
            $districtAdmins,
            $dataEntryUsers,
            $viewerUsers,
            $ngoUsers,
            $communityUsers,
            $farmerUsers,
            $foundationUsers,
            $emergencyUsers,
            $religiousUsers,
            $socialWelfareUsers,
            $youthUsers
        );

        $createdCount = 0;

        foreach ($allUsers as $userData) {
            $role = $userData['role'];
            unset($userData['role']);

            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name_en'] ?? $userData['name'],
                    'email' => $userData['email'],
                    'password' => Hash::make($userData['password']),
                    'phone' => $userData['phone'],
                    'organization_type_id' => $userData['organization_type_id'],
                    'is_approved' => $userData['is_approved'],
                    'email_verified_at' => now(),
                ]
            );

            // Assign role if not already assigned
            if (!$user->hasRole($role)) {
                $user->assignRole($role);
            }

            $createdCount++;
        }

        // Create additional test users with pending approval
        $pendingUsers = [
            [
                'name' => 'মোঃ আনোয়ার হোসেন',
                'name_en' => 'Md. Anwar Hossain',
                'email' => 'anwar@test.org',
                'password' => 'password123',
                'phone' => '01700000001',
                'role' => 'data-entry',
                'organization_type_id' => $organizationTypes->where('name', 'NGO')->first()?->id,
                'is_approved' => false,
            ],
            [
                'name' => 'নাসিরা খাতুন',
                'name_en' => 'Nasira Khatun',
                'email' => 'nasira@test.org',
                'password' => 'password123',
                'phone' => '01700000002',
                'role' => 'data-entry',
                'organization_type_id' => $organizationTypes->where('name', 'Community Organization')->first()?->id,
                'is_approved' => false,
            ]
        ];

        foreach ($pendingUsers as $userData) {
            $role = $userData['role'];
            unset($userData['role']);

            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name_en'] ?? $userData['name'],
                    'email' => $userData['email'],
                    'password' => Hash::make($userData['password']),
                    'phone' => $userData['phone'],
                    'organization_type_id' => $userData['organization_type_id'],
                    'is_approved' => $userData['is_approved'],
                    'email_verified_at' => null,
                ]
            );

            if (!$user->hasRole($role)) {
                $user->assignRole($role);
            }

            $createdCount++;
        }

        $this->command->info("Created {$createdCount} comprehensive Bangla users with realistic names and roles.");
        $this->command->info('Users include Super Admins, District Admins, Data Entry, Viewers, and organization representatives.');
        $this->command->info('Default password for all users: password123');
    }
}
