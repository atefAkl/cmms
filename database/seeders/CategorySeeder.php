<?php

namespace Database\Seeders;

use App\Models\ItemCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            // الأجهزة (كمبيوتر - طابعة - شاشات - أجهزة تحكم - أجهزة شبكات - أجهزة أخرى)
            ['name' => 'Devices', 'description' => 'Devices and sensors', 'is_active' => true,
                'children' => [
                    ['name' => 'Computers', 'description' => 'Computers', 'is_active' => true],
                    ['name' => 'Printers', 'description' => 'Printers', 'is_active' => true],
                    ['name' => 'Actuators', 'description' => 'Actuators', 'is_active' => true],
                    ['name' => 'Controllers', 'description' => 'Controllers', 'is_active' => true],
                    ['name' => 'Network Devices', 'description' => 'Network Devices', 'is_active' => true],
                    ['name' => 'Other Devices', 'description' => 'Other Devices', 'is_active' => true],
                ],
            ],
            // المعدات (رافعة شوكية - رافعة يدوية - موازين - آلات قطع - معدات أخرى)
            ['name' => 'Equipment', 'description' => 'Equipment and machines', 'is_active' => true,
                'children' => [
                    ['name' => 'Forklift', 'description' => 'Forklift', 'is_active' => true],
                    ['name' => 'Pallet Jack', 'description' => 'Pallet Jack', 'is_active' => true],
                    ['name' => 'Thermometers', 'description' => 'Thermometers', 'is_active' => true],
                    ['name' => 'Weight Balance', 'description' => 'Weight Balance', 'is_active' => true],
                    ['name' => 'Cutting Machine', 'description' => 'Cutting Machine', 'is_active' => true],
                    ['name' => 'Other Equipment', 'description' => 'Other Equipment', 'is_active' => true],
                ],
            ],
            // قطع الغيار (حساسات - مفاتيح - تلامسات - كابلات - فيوزات - قواطع - قطع غيار أخرى)
            ['name' => 'Spare Parts', 'description' => 'Spare Parts & devices components', 'is_active' => true,
                'children' => [
                    ['name' => 'Sensors', 'description' => 'Sensors', 'is_active' => true],
                    ['name' => 'Switch', 'description' => 'Switch', 'is_active' => true],
                    ['name' => 'Contacts', 'description' => 'Contacts', 'is_active' => true],
                    ['name' => 'Cables', 'description' => 'Cables', 'is_active' => true],
                    ['name' => 'Fuses', 'description' => 'Fuses', 'is_active' => true],
                    ['name' => 'Breakers', 'description' => 'Breakers', 'is_active' => true],
                    ['name' => 'Other Parts', 'description' => 'Other Parts', 'is_active' => true],
                ],
            ],
            // المستلزمات التشغيلية (زيوت - فلاتر - مستلزمات تشغيلية أخرى)
            ['name' => 'Operational Supplies', 'description' => 'Operational Supplies', 'is_active' => true,
                'children' => [
                    ['name' => 'Shell Oil', 'description' => 'Shell Oil', 'is_active' => true],
                    ['name' => 'Filters', 'description' => 'Filters', 'is_active' => true],
                    ['name' => 'Freezone Oil', 'description' => 'Freezone Oil', 'is_active' => true],
                ],
            ],
            // المستلزمات المكتبية (ورق طباعة - خراطيش طباعة - ورق طباعة - أشرطة لاصقة)
            ['name' => 'Office Supplies', 'description' => 'Office Supplies', 'is_active' => true,
                'children' => [
                    ['name' => 'Printing paper', 'description' => 'Printing paper', 'is_active' => true],
                    ['name' => 'Printing Cartridges', 'description' => 'Printing Cartridges', 'is_active' => true],
                    ['name' => 'Marking Paper', 'description' => 'Marking Paper', 'is_active' => true],
                    ['name' => 'Stickers tapes', 'description' => 'Stickers tapes', 'is_active' => true],
                ],
            ],
            // مستلزمات السلامة (قفازات - أقنعة - أحذية سلامة - نظارات سلامة - مستلزمات سلامة أخرى)
            ['name' => 'Safety Items', 'description' => 'Safety Items', 'is_active' => true,
                'children' => [
                    ['name' => 'Gloves', 'description' => 'Gloves', 'is_active' => true],
                    ['name' => 'Masks', 'description' => 'Masks', 'is_active' => true],
                    ['name' => 'Safety Shoes', 'description' => 'Safety Shoes', 'is_active' => true],
                    ['name' => 'Safety Glasses', 'description' => 'Safety Glasses', 'is_active' => true],
                ],
            ],
            // الأثاث والتجهيزات (أثاث - تجهيزات - أثاث وتجهيزات أخرى)
            ['name' => 'Furniture & Fixtures', 'description' => 'Furniture & Fixtures', 'is_active' => true,
                'children' => [
                    ['name' => 'Furniture', 'description' => 'Furniture', 'is_active' => true],
                    ['name' => 'Fixtures', 'description' => 'Fixtures', 'is_active' => true],
                ],
            ],
            // مواد التعبئة والتغليف (صناديق كرتونية - مواد تغليف أخرى)
            ['name' => 'Packaging Materials', 'description' => 'Packaging Materials', 'is_active' => true,
                'children' => [
                    ['name' => 'Stretch Film', 'description' => 'Stretch Film', 'is_active' => true],
                    ['name' => 'Pallet Wrap', 'description' => 'Pallet Wrap', 'is_active' => true],
                    ['name' => 'Carton Boxes', 'description' => 'Carton Boxes', 'is_active' => true],
                    ['name' => 'Other Packaging Materials', 'description' => 'Other Packaging Materials', 'is_active' => true],
                ],
            ],
        ];

        foreach ($categories as $categoryData) {
            $children = $categoryData['children'] ?? [];

            $parent = ItemCategory::create([
                'name' => $categoryData['name'],
                'slug' => Str::slug($categoryData['name']),
                'description' => $categoryData['description'] ?? null,
                'level' => 0,
                'parent_id' => null,
            ]);

            foreach ($children as $childData) {
                ItemCategory::create([
                    'name' => $childData['name'],
                    'slug' => Str::slug($childData['name']),
                    'description' => $childData['description'] ?? null,
                    'level' => 1,
                    'parent_id' => $parent->id,
                ]);
            }
        }
    }
}
