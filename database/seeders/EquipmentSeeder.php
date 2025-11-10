<?php

namespace Database\Seeders;

use App\Models\Equipment;
use Illuminate\Database\Seeder;

class EquipmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $equipment = [
            [
                'brand' => 'Apple',
                'model' => 'MacBook Pro 16"',
                'serial' => 'MBP2023A001',
                'purchase_date' => '2023-01-15',
                'purchase_price' => 28999.00,
            ],
            [
                'brand' => 'Dell',
                'model' => 'XPS 15',
                'serial' => 'DELLXPS2023B002',
                'purchase_date' => '2023-02-20',
                'purchase_price' => 22499.00,
            ],
            [
                'brand' => 'Lenovo',
                'model' => 'ThinkPad X1 Carbon',
                'serial' => 'LENOVO2023C003',
                'purchase_date' => '2023-03-10',
                'purchase_price' => 18999.00,
            ],
            [
                'brand' => 'HP',
                'model' => 'EliteBook 840',
                'serial' => 'HPELITE2023D004',
                'purchase_date' => '2023-04-05',
                'purchase_price' => 15999.00,
            ],
            [
                'brand' => 'Apple',
                'model' => 'iPhone 15 Pro',
                'serial' => 'IPHONE15P2023E005',
                'purchase_date' => '2023-05-12',
                'purchase_price' => 12999.00,
            ],
            [
                'brand' => 'Samsung',
                'model' => 'Galaxy S24 Ultra',
                'serial' => 'SAMSUNG24U2023F006',
                'purchase_date' => '2023-06-18',
                'purchase_price' => 11999.00,
            ],
            [
                'brand' => 'LG',
                'model' => 'UltraFine 27" 4K',
                'serial' => 'LG27U4K2023M007',
                'purchase_date' => '2023-07-22',
                'purchase_price' => 7499.00,
            ],
            [
                'brand' => 'Logitech',
                'model' => 'MX Master 3S',
                'serial' => 'LOGIMX3S2023A008',
                'purchase_date' => '2023-08-30',
                'purchase_price' => 1299.00,
            ],
            [
                'brand' => 'Sony',
                'model' => 'WH-1000XM5',
                'serial' => 'SONYWH5XM52023H009',
                'purchase_date' => '2023-09-14',
                'purchase_price' => 3499.00,
            ],
            [
                'brand' => 'Microsoft',
                'model' => 'Surface Pro 9',
                'serial' => 'SURFPRO92023T010',
                'purchase_date' => '2023-10-25',
                'purchase_price' => 14999.00,
            ],
            [
                'brand' => 'Apple',
                'model' => 'iPad Air',
                'serial' => 'IPADAIR2023P011',
                'purchase_date' => '2023-11-08',
                'purchase_price' => 8999.00,
            ],
            [
                'brand' => 'Keychron',
                'model' => 'K2 Pro',
                'serial' => 'KEYK2P2023K012',
                'purchase_date' => '2023-12-01',
                'purchase_price' => 1499.00,
            ],
            [
                'brand' => 'Dell',
                'model' => 'U2723QE',
                'serial' => 'DELLU272023M013',
                'purchase_date' => '2024-01-15',
                'purchase_price' => 5999.00,
            ],
            [
                'brand' => 'Bose',
                'model' => 'QuietComfort 45',
                'serial' => 'BOSEQC452024A014',
                'purchase_date' => '2024-02-20',
                'purchase_price' => 3999.00,
            ],
            [
                'brand' => 'Razer',
                'model' => 'DeathAdder V3 Pro',
                'serial' => 'RAZERDAV32024G015',
                'purchase_date' => '2024-03-10',
                'purchase_price' => 999.00,
            ],
            [
                'brand' => 'Apple',
                'model' => 'Magic Keyboard',
                'serial' => 'APPMK2024K016',
                'purchase_date' => '2024-04-05',
                'purchase_price' => 1799.00,
            ],
        ];

        foreach ($equipment as $item) {
            Equipment::create($item);
        }
    }
}
