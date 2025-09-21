<?php

namespace Database\Seeders;

use App\Models\ReliefItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReliefItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $reliefItems = [
            // Monetary Items
            [
                'name' => 'Cash Relief',
                'name_bn' => 'নগদ ত্রাণ',
                'type' => 'monetary',
                'unit' => 'BDT',
                'description' => 'Direct cash assistance for immediate needs',
            ],
            
            // Food Items
            [
                'name' => 'Rice',
                'name_bn' => 'চাল',
                'type' => 'food',
                'unit' => 'metric_ton',
                'description' => 'Staple food grain for disaster relief',
            ],
            [
                'name' => 'Wheat',
                'name_bn' => 'গম',
                'type' => 'food',
                'unit' => 'metric_ton',
                'description' => 'Wheat grain for food assistance',
            ],
            [
                'name' => 'Lentils',
                'name_bn' => 'ডাল',
                'type' => 'food',
                'unit' => 'kg',
                'description' => 'Protein-rich lentils for nutrition',
            ],
            [
                'name' => 'Cooking Oil',
                'name_bn' => 'রান্নার তেল',
                'type' => 'food',
                'unit' => 'liter',
                'description' => 'Edible oil for cooking',
            ],
            [
                'name' => 'Salt',
                'name_bn' => 'লবণ',
                'type' => 'food',
                'unit' => 'kg',
                'description' => 'Essential salt for food preparation',
            ],
            [
                'name' => 'Sugar',
                'name_bn' => 'চিনি',
                'type' => 'food',
                'unit' => 'kg',
                'description' => 'Sugar for energy and nutrition',
            ],
            
            // Medical Items
            [
                'name' => 'Medicine',
                'name_bn' => 'ঔষধ',
                'type' => 'medical',
                'unit' => 'box',
                'description' => 'Essential medicines for health care',
            ],
            [
                'name' => 'First Aid Kit',
                'name_bn' => 'প্রাথমিক চিকিৎসা সরঞ্জাম',
                'type' => 'medical',
                'unit' => 'piece',
                'description' => 'First aid supplies for emergency care',
            ],
            [
                'name' => 'Water Purification Tablets',
                'name_bn' => 'পানি বিশুদ্ধকরণ ট্যাবলেট',
                'type' => 'medical',
                'unit' => 'box',
                'description' => 'Tablets for water purification',
            ],
            
            // Shelter Items
            [
                'name' => 'Tarpaulin',
                'name_bn' => 'টারপলিন',
                'type' => 'shelter',
                'unit' => 'piece',
                'description' => 'Waterproof tarpaulin for temporary shelter',
            ],
            [
                'name' => 'Blanket',
                'name_bn' => 'কম্বল',
                'type' => 'shelter',
                'unit' => 'piece',
                'description' => 'Warm blankets for cold weather protection',
            ],
            [
                'name' => 'Mosquito Net',
                'name_bn' => 'মশারি',
                'type' => 'shelter',
                'unit' => 'piece',
                'description' => 'Mosquito nets for disease prevention',
            ],
            [
                'name' => 'Tent',
                'name_bn' => 'তাঁবু',
                'type' => 'shelter',
                'unit' => 'piece',
                'description' => 'Temporary shelter tents',
            ],
            
            // Other Items
            [
                'name' => 'Clothing',
                'name_bn' => 'পোশাক',
                'type' => 'other',
                'unit' => 'piece',
                'description' => 'Clothing items for affected families',
            ],
            [
                'name' => 'Hygiene Kit',
                'name_bn' => 'স্বাস্থ্যবিধি কিট',
                'type' => 'other',
                'unit' => 'box',
                'description' => 'Personal hygiene supplies',
            ],
            [
                'name' => 'School Supplies',
                'name_bn' => 'স্কুল সরঞ্জাম',
                'type' => 'other',
                'unit' => 'box',
                'description' => 'Educational materials for children',
            ],
        ];

        foreach ($reliefItems as $item) {
            ReliefItem::create($item);
        }
    }
}
