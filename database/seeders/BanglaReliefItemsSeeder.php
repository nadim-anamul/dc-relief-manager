<?php

namespace Database\Seeders;

use App\Models\ReliefItem;
use Illuminate\Database\Seeder;

class BanglaReliefItemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creating comprehensive Bangla relief items...');

        $reliefItems = [
            // Food Items
            [
                'name' => 'চাল',
                'name_en' => 'Rice',
                'type' => 'food',
                'unit' => 'kg',
                'description' => 'উচ্চমানের চাল খাদ্য ত্রাণের জন্য',
                'description_en' => 'High quality rice for food relief',
                'is_active' => true,
            ],
            [
                'name' => 'গম',
                'name_en' => 'Wheat',
                'type' => 'food',
                'unit' => 'kg',
                'description' => 'পুষ্টিকর গম খাদ্য সহায়তার জন্য',
                'description_en' => 'Nutritious wheat for food assistance',
                'is_active' => true,
            ],
            [
                'name' => 'ডাল',
                'name_en' => 'Lentils',
                'type' => 'food',
                'unit' => 'kg',
                'description' => 'প্রোটিন সমৃদ্ধ ডাল খাদ্য ত্রাণের জন্য',
                'description_en' => 'Protein-rich lentils for food relief',
                'is_active' => true,
            ],
            [
                'name' => 'রান্নার তেল',
                'name_en' => 'Cooking Oil',
                'type' => 'food',
                'unit' => 'liter',
                'description' => 'রান্নার জন্য তেল',
                'description_en' => 'Cooking oil for food preparation',
                'is_active' => true,
            ],
            [
                'name' => 'লবণ',
                'name_en' => 'Salt',
                'type' => 'food',
                'unit' => 'kg',
                'description' => 'আয়োডিনযুক্ত লবণ খাদ্য ত্রাণের জন্য',
                'description_en' => 'Iodized salt for food relief',
                'is_active' => true,
            ],
            [
                'name' => 'চিনি',
                'name_en' => 'Sugar',
                'type' => 'food',
                'unit' => 'kg',
                'description' => 'পরিশোধিত চিনি খাদ্য ত্রাণের জন্য',
                'description_en' => 'Refined sugar for food relief',
                'is_active' => true,
            ],
            [
                'name' => 'আটা',
                'name_en' => 'Flour',
                'type' => 'food',
                'unit' => 'kg',
                'description' => 'গমের আটা রুটি তৈরির জন্য',
                'description_en' => 'Wheat flour for bread making',
                'is_active' => true,
            ],
            [
                'name' => 'দুধ',
                'name_en' => 'Milk',
                'type' => 'food',
                'unit' => 'liter',
                'description' => 'পাস্তুরিত দুধ শিশুদের জন্য',
                'description_en' => 'Pasteurized milk for children',
                'is_active' => true,
            ],
            [
                'name' => 'মাছ',
                'name_en' => 'Fish',
                'type' => 'food',
                'unit' => 'kg',
                'description' => 'তাজা মাছ প্রোটিনের জন্য',
                'description_en' => 'Fresh fish for protein',
                'is_active' => true,
            ],
            [
                'name' => 'মাংস',
                'name_en' => 'Meat',
                'type' => 'food',
                'unit' => 'kg',
                'description' => 'তাজা মাংস প্রোটিনের জন্য',
                'description_en' => 'Fresh meat for protein',
                'is_active' => true,
            ],

            // Medical Items
            [
                'name' => 'প্রাথমিক চিকিৎসা কিট',
                'name_en' => 'First Aid Kit',
                'type' => 'medical',
                'unit' => 'package',
                'description' => 'জরুরি প্রাথমিক চিকিৎসা সামগ্রী',
                'description_en' => 'Essential first aid supplies',
                'is_active' => true,
            ],
            [
                'name' => 'ঔষধ কিট',
                'name_en' => 'Medicine Kit',
                'type' => 'medical',
                'unit' => 'package',
                'description' => 'প্রাথমিক ঔষধ সামগ্রী',
                'description_en' => 'Basic medicine supplies',
                'is_active' => true,
            ],
            [
                'name' => 'প্যারাসিটামল',
                'name_en' => 'Paracetamol',
                'type' => 'medical',
                'unit' => 'box',
                'description' => 'জ্বর ও ব্যথা কমাতে প্যারাসিটামল',
                'description_en' => 'Paracetamol for fever and pain relief',
                'is_active' => true,
            ],
            [
                'name' => 'অ্যান্টিবায়োটিক',
                'name_en' => 'Antibiotic',
                'type' => 'medical',
                'unit' => 'box',
                'description' => 'সংক্রমণ প্রতিরোধে অ্যান্টিবায়োটিক',
                'description_en' => 'Antibiotic for infection prevention',
                'is_active' => true,
            ],
            [
                'name' => 'ভিটামিন সাপ্লিমেন্ট',
                'name_en' => 'Vitamin Supplement',
                'type' => 'medical',
                'unit' => 'box',
                'description' => 'ভিটামিন সাপ্লিমেন্ট পুষ্টির জন্য',
                'description_en' => 'Vitamin supplements for nutrition',
                'is_active' => true,
            ],
            [
                'name' => 'স্যালাইন',
                'name_en' => 'Saline',
                'type' => 'medical',
                'unit' => 'bottle',
                'description' => 'ডায়রিয়ার জন্য স্যালাইন',
                'description_en' => 'Saline for diarrhea treatment',
                'is_active' => true,
            ],
            [
                'name' => 'মাস্ক',
                'name_en' => 'Face Mask',
                'type' => 'medical',
                'unit' => 'piece',
                'description' => 'মুখে মাস্ক রোগ প্রতিরোধের জন্য',
                'description_en' => 'Face mask for disease prevention',
                'is_active' => true,
            ],
            [
                'name' => 'হ্যান্ড স্যানিটাইজার',
                'name_en' => 'Hand Sanitizer',
                'type' => 'medical',
                'unit' => 'bottle',
                'description' => 'হাত জীবাণুমুক্ত করার জন্য স্যানিটাইজার',
                'description_en' => 'Hand sanitizer for hand hygiene',
                'is_active' => true,
            ],

            // Shelter Items
            [
                'name' => 'তাঁবু',
                'name_en' => 'Tent',
                'type' => 'shelter',
                'unit' => 'piece',
                'description' => 'জরুরি আশ্রয়ের জন্য তাঁবু',
                'description_en' => 'Emergency shelter tent',
                'is_active' => true,
            ],
            [
                'name' => 'কম্বল',
                'name_en' => 'Blanket',
                'type' => 'shelter',
                'unit' => 'piece',
                'description' => 'উষ্ণতার জন্য কম্বল',
                'description_en' => 'Warm blanket for shelter relief',
                'is_active' => true,
            ],
            [
                'name' => 'মশারি',
                'name_en' => 'Mosquito Net',
                'type' => 'shelter',
                'unit' => 'piece',
                'description' => 'মশা থেকে সুরক্ষার জন্য মশারি',
                'description_en' => 'Mosquito net for protection',
                'is_active' => true,
            ],
            [
                'name' => 'শীতবস্ত্র',
                'name_en' => 'Winter Clothes',
                'type' => 'shelter',
                'unit' => 'piece',
                'description' => 'শীতকালীন পোশাক',
                'description_en' => 'Winter clothing for cold weather protection',
                'is_active' => true,
            ],
            [
                'name' => 'বান্ডিল ঢেউটিন',
                'name_en' => 'Corrugated Iron Sheet Bundle',
                'type' => 'shelter',
                'unit' => 'bundle',
                'description' => 'ঘর নির্মাণের জন্য ঢেউটিন',
                'description_en' => 'Corrugated iron sheets for house construction',
                'is_active' => true,
            ],
            [
                'name' => 'টারপলিন',
                'name_en' => 'Tarpaulin',
                'type' => 'shelter',
                'unit' => 'piece',
                'description' => 'আবহাওয়া থেকে সুরক্ষার জন্য টারপলিন',
                'description_en' => 'Tarpaulin for weather protection',
                'is_active' => true,
            ],
            [
                'name' => 'বালি',
                'name_en' => 'Sand',
                'type' => 'shelter',
                'unit' => 'kg',
                'description' => 'নির্মাণ কাজের জন্য বালি',
                'description_en' => 'Sand for construction work',
                'is_active' => true,
            ],
            [
                'name' => 'সিমেন্ট',
                'name_en' => 'Cement',
                'type' => 'shelter',
                'unit' => 'bag',
                'description' => 'নির্মাণ কাজের জন্য সিমেন্ট',
                'description_en' => 'Cement for construction work',
                'is_active' => true,
            ],
            [
                'name' => 'ইট',
                'name_en' => 'Brick',
                'type' => 'shelter',
                'unit' => 'piece',
                'description' => 'নির্মাণ কাজের জন্য ইট',
                'description_en' => 'Brick for construction work',
                'is_active' => true,
            ],

            // Educational Items
            [
                'name' => 'স্কুল ব্যাগ',
                'name_en' => 'School Bag',
                'type' => 'other',
                'unit' => 'piece',
                'description' => 'স্কুলের জন্য ব্যাগ',
                'description_en' => 'School bag for students',
                'is_active' => true,
            ],
            [
                'name' => 'খাতা সেট',
                'name_en' => 'Notebook Set',
                'type' => 'other',
                'unit' => 'set',
                'description' => 'ছাত্রছাত্রীদের জন্য খাতার সেট',
                'description_en' => 'Set of notebooks for students',
                'is_active' => true,
            ],
            [
                'name' => 'কলম',
                'name_en' => 'Pen',
                'type' => 'other',
                'unit' => 'piece',
                'description' => 'লেখার জন্য কলম',
                'description_en' => 'Pen for writing',
                'is_active' => true,
            ],
            [
                'name' => 'পেন্সিল',
                'name_en' => 'Pencil',
                'type' => 'other',
                'unit' => 'piece',
                'description' => 'লেখার জন্য পেন্সিল',
                'description_en' => 'Pencil for writing',
                'is_active' => true,
            ],
            [
                'name' => 'রাবার',
                'name_en' => 'Eraser',
                'type' => 'other',
                'unit' => 'piece',
                'description' => 'মুছে ফেলার জন্য রাবার',
                'description_en' => 'Eraser for erasing',
                'is_active' => true,
            ],
            [
                'name' => 'স্কেল',
                'name_en' => 'Ruler',
                'type' => 'other',
                'unit' => 'piece',
                'description' => 'মাপার জন্য স্কেল',
                'description_en' => 'Ruler for measuring',
                'is_active' => true,
            ],
            [
                'name' => 'রঙ পেন্সিল সেট',
                'name_en' => 'Color Pencil Set',
                'type' => 'other',
                'unit' => 'set',
                'description' => 'আঁকার জন্য রঙ পেন্সিল সেট',
                'description_en' => 'Color pencil set for drawing',
                'is_active' => true,
            ],
            [
                'name' => 'স্কুল ইউনিফর্ম',
                'name_en' => 'School Uniform',
                'type' => 'other',
                'unit' => 'set',
                'description' => 'স্কুলের জন্য ইউনিফর্ম',
                'description_en' => 'School uniform for students',
                'is_active' => true,
            ],

            // Monetary
            [
                'name' => 'নগদ ত্রাণ',
                'name_en' => 'Cash Relief',
                'type' => 'monetary',
                'unit' => 'BDT',
                'description' => 'প্রত্যক্ষ নগদ সহায়তা',
                'description_en' => 'Direct cash assistance',
                'is_active' => true,
            ],

            // Other Items
            [
                'name' => 'জল বিশুদ্ধকরণ ট্যাবলেট',
                'name_en' => 'Water Purification Tablet',
                'type' => 'other',
                'unit' => 'box',
                'description' => 'পানি বিশুদ্ধ করার জন্য ট্যাবলেট',
                'description_en' => 'Tablets for water purification',
                'is_active' => true,
            ],
            [
                'name' => 'সাবান',
                'name_en' => 'Soap',
                'type' => 'other',
                'unit' => 'piece',
                'description' => 'পরিচ্ছন্নতার জন্য সাবান',
                'description_en' => 'Soap for cleanliness',
                'is_active' => true,
            ],
            [
                'name' => 'টুথব্রাশ',
                'name_en' => 'Toothbrush',
                'type' => 'other',
                'unit' => 'piece',
                'description' => 'দাঁত পরিষ্কার করার জন্য টুথব্রাশ',
                'description_en' => 'Toothbrush for dental hygiene',
                'is_active' => true,
            ],
            [
                'name' => 'টুথপেস্ট',
                'name_en' => 'Toothpaste',
                'type' => 'other',
                'unit' => 'tube',
                'description' => 'দাঁত পরিষ্কার করার জন্য টুথপেস্ট',
                'description_en' => 'Toothpaste for dental hygiene',
                'is_active' => true,
            ],
            [
                'name' => 'শ্যাম্পু',
                'name_en' => 'Shampoo',
                'type' => 'other',
                'unit' => 'bottle',
                'description' => 'চুল পরিষ্কার করার জন্য শ্যাম্পু',
                'description_en' => 'Shampoo for hair care',
                'is_active' => true,
            ],
            [
                'name' => 'টাওয়েল',
                'name_en' => 'Towel',
                'type' => 'other',
                'unit' => 'piece',
                'description' => 'গোসলের জন্য তোয়ালে',
                'description_en' => 'Towel for bathing',
                'is_active' => true,
            ],
            [
                'name' => 'লাইটার',
                'name_en' => 'Lighter',
                'type' => 'other',
                'unit' => 'piece',
                'description' => 'আগুন জ্বালানোর জন্য লাইটার',
                'description_en' => 'Lighter for fire ignition',
                'is_active' => true,
            ],
            [
                'name' => 'মোমবাতি',
                'name_en' => 'Candle',
                'type' => 'other',
                'unit' => 'piece',
                'description' => 'আলোর জন্য মোমবাতি',
                'description_en' => 'Candle for lighting',
                'is_active' => true,
            ],
            [
                'name' => 'বাটারি',
                'name_en' => 'Battery',
                'type' => 'other',
                'unit' => 'piece',
                'description' => 'ইলেকট্রনিক যন্ত্রের জন্য ব্যাটারি',
                'description_en' => 'Battery for electronic devices',
                'is_active' => true,
            ]
        ];

        $createdCount = 0;

        foreach ($reliefItems as $itemData) {
            $reliefItem = ReliefItem::firstOrCreate(
                ['name' => $itemData['name_en']],
                [
                    'name' => $itemData['name_en'],
                    'name_bn' => $itemData['name'],
                    'type' => $itemData['type'],
                    'unit' => $itemData['unit'],
                    'description' => $itemData['description_en'],
                    'is_active' => $itemData['is_active'],
                ]
            );

            $createdCount++;
        }

        $this->command->info("Created {$createdCount} comprehensive Bangla relief items.");
        $this->command->info('Relief items include food items (rice, wheat, lentils, cooking oil, salt, sugar, flour, milk, fish, meat), medical items (first aid kit, medicine kit, paracetamol, antibiotic, vitamin supplement, saline, face mask, hand sanitizer), shelter items (tent, blanket, mosquito net, winter clothes, corrugated iron sheets, tarpaulin, sand, cement, brick), educational items (school bag, notebook set, pen, pencil, eraser, ruler, color pencil set, school uniform), monetary assistance (cash relief), and other essential items (water purification tablets, soap, toothbrush, toothpaste, shampoo, towel, lighter, candle, battery).');
    }
}
