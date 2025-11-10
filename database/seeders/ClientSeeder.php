<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clients = [
            [
                'name' => 'Göteborg Digital',
                'address' => 'Kungsportsaven 15, 411 04 Göteborg, Sweden',
                'contact_email' => 'contact@goteborg-digital.se',
                'contact_phone' => '+46 31 123 45 67',
            ],
            [
                'name' => 'Stockholm Tech Hub',
                'address' => 'Drottninggatan 25, 111 20 Stockholm, Sweden',
                'contact_email' => 'info@stockholm-tech.se',
                'contact_phone' => '+46 8 987 65 43',
            ],
            [
                'name' => 'Malmö Dev House',
                'address' => 'Stortorget 8, 211 20 Malmö, Sweden',
                'contact_email' => 'hello@malmo-dev.se',
                'contact_phone' => '+46 40 555 12 34',
            ],
            [
                'name' => 'Uppsala Code Factory',
                'address' => 'Åsögatan 12, 753 20 Uppsala, Sweden',
                'contact_email' => 'jobs@uppsala-code.se',
                'contact_phone' => '+46 18 777 88 99',
            ],
            [
                'name' => 'Nordic Software AB',
                'address' => 'Sveavägen 50, 111 34 Stockholm, Sweden',
                'contact_email' => 'contact@nordic-software.se',
                'contact_phone' => '+46 70 123 45 67',
            ],
        ];

        foreach ($clients as $client) {
            Client::create($client);
        }
    }
}
