<?php

namespace Database\Seeders;

use App\Models\EconomicYear;
use Illuminate\Database\Seeder;

class BanglaEconomicYearsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creating comprehensive Bangla economic years...');

        $economicYears = [
            [
                'name' => '2022-2023',
                'name_bn' => '২০২২-২০২৩',
                'start_date' => '2022-07-01',
                'end_date' => '2023-06-30',
                'is_active' => true,
                'is_current' => false,
                'description' => '২০২২-২০২৩ অর্থবছর',
                'description_en' => 'Fiscal Year 2022-2023',
            ],
            [
                'name' => '2023-2024',
                'name_bn' => '২০২৩-২০২৪',
                'start_date' => '2023-07-01',
                'end_date' => '2024-06-30',
                'is_active' => true,
                'is_current' => false,
                'description' => '২০২৩-২০২৪ অর্থবছর',
                'description_en' => 'Fiscal Year 2023-2024',
            ],
            [
                'name' => '2024-2025',
                'name_bn' => '২০২৪-২০২৫',
                'start_date' => '2024-07-01',
                'end_date' => '2025-06-30',
                'is_active' => true,
                'is_current' => true, // Current economic year
                'description' => '২০২৪-২০২৫ অর্থবছর (বর্তমান)',
                'description_en' => 'Fiscal Year 2024-2025 (Current)',
            ],
            [
                'name' => '2025-2026',
                'name_bn' => '২০২৫-২০২৬',
                'start_date' => '2025-07-01',
                'end_date' => '2026-06-30',
                'is_active' => true,
                'is_current' => false,
                'description' => '২০২৫-২০২৬ অর্থবছর (পরবর্তী)',
                'description_en' => 'Fiscal Year 2025-2026 (Next)',
            ],
            [
                'name' => '2026-2027',
                'name_bn' => '২০২৬-২০২৭',
                'start_date' => '2026-07-01',
                'end_date' => '2027-06-30',
                'is_active' => true,
                'is_current' => false,
                'description' => '২০২৬-২০২৭ অর্থবছর',
                'description_en' => 'Fiscal Year 2026-2027',
            ]
        ];

        $createdCount = 0;

        foreach ($economicYears as $economicYearData) {
            $economicYear = EconomicYear::firstOrCreate(
                ['name' => $economicYearData['name']],
                [
                    'name' => $economicYearData['name'],
                    'name_bn' => $economicYearData['name_bn'],
                    'start_date' => $economicYearData['start_date'],
                    'end_date' => $economicYearData['end_date'],
                    'is_active' => $economicYearData['is_active'],
                    'is_current' => $economicYearData['is_current'],
                ]
            );

            $createdCount++;
        }

        $this->command->info("Created {$createdCount} comprehensive Bangla economic years.");
        $this->command->info('Economic years include 2022-2023, 2023-2024, 2024-2025 (current), 2025-2026, and 2026-2027.');
        $this->command->info('All years follow Bangladesh fiscal year calendar (July to June).');
    }
}
