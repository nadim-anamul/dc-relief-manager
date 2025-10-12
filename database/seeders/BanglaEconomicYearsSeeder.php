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
        $this->command->info('Creating comprehensive Bangla economic years from 2020-2050...');

        $economicYears = [];
        $currentYear = date('Y');
        $currentMonth = date('m');
        
        // Determine current economic year based on current date
        // If current month is July or later, current fiscal year starts this year
        // If current month is before July, current fiscal year started last year
        $currentFiscalYear = ($currentMonth >= 7) ? $currentYear : $currentYear - 1;
        
        // Generate economic years from 2020 to 2050
        for ($year = 2020; $year <= 2050; $year++) {
            $startYear = $year;
            $endYear = $year + 1;
            $isCurrent = ($year === $currentFiscalYear);
            
            $economicYears[] = [
                'name' => $startYear . '-' . $endYear,
                'name_bn' => $this->convertToBanglaYear($startYear) . '-' . $this->convertToBanglaYear($endYear),
                'start_date' => $startYear . '-07-01',
                'end_date' => $endYear . '-06-30',
                'is_active' => true,
                'is_current' => $isCurrent,
                'description' => $this->convertToBanglaYear($startYear) . '-' . $this->convertToBanglaYear($endYear) . ' অর্থবছর' . ($isCurrent ? ' (বর্তমান)' : ''),
                'description_en' => 'Fiscal Year ' . $startYear . '-' . $endYear . ($isCurrent ? ' (Current)' : ''),
            ];
        }

        $createdCount = 0;

        // First, set all existing years as not current
        EconomicYear::query()->update(['is_current' => false]);

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

            // Update the current year status if this year should be current
            if ($economicYearData['is_current']) {
                $economicYear->update(['is_current' => true]);
            }

            $createdCount++;
        }

        $this->command->info("Created {$createdCount} comprehensive Bangla economic years.");
        $this->command->info("Economic years include 2020-2021 to 2050-2051 with current year: {$currentFiscalYear}-" . ($currentFiscalYear + 1));
        $this->command->info('All years follow Bangladesh fiscal year calendar (July to June).');
    }

    /**
     * Convert English year to Bangla numerals
     */
    private function convertToBanglaYear($year)
    {
        $banglaNumbers = [
            '0' => '০', '1' => '১', '2' => '২', '3' => '৩', '4' => '৪',
            '5' => '৫', '6' => '৬', '7' => '৭', '8' => '৮', '9' => '৯'
        ];
        
        return str_replace(array_keys($banglaNumbers), array_values($banglaNumbers), (string)$year);
    }
}
