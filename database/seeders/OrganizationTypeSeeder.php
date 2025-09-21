<?php

namespace Database\Seeders;

use App\Models\OrganizationType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrganizationTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $organizationTypes = [
            [
                'name' => 'NGO',
                'description' => 'Non-Governmental Organization',
            ],
            [
                'name' => 'Community Organization',
                'description' => 'Local community-based organization',
            ],
            [
                'name' => 'Farmers Association',
                'description' => 'Association of farmers and agricultural workers',
            ],
            [
                'name' => 'Foundation',
                'description' => 'Charitable foundation',
            ],
            [
                'name' => 'Emergency Response',
                'description' => 'Emergency response and disaster management organization',
            ],
            [
                'name' => 'Religious Organization',
                'description' => 'Religious and faith-based organization',
            ],
            [
                'name' => 'Social Welfare',
                'description' => 'Social welfare and development organization',
            ],
            [
                'name' => 'Youth Organization',
                'description' => 'Youth-led organization',
            ],
        ];

        foreach ($organizationTypes as $type) {
            OrganizationType::create($type);
        }

        $this->command->info('Created ' . count($organizationTypes) . ' organization types.');
    }
}
