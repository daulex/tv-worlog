<?php

namespace Database\Factories\Providers;

use Faker\Provider\Base;

class CustomFakerProvider extends Base
{
    /**
     * Generate a Swedish address
     */
    public function swedishAddress()
    {
        $streetNumbers = ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '12', '14', '16', '18', '20'];
        $streetNames = [
            'Drottninggatan', 'Kungsgatan', 'Vasagatan', 'Storgatan', 'Götgatan',
            'Sveavägen', 'Odengatan', 'Karlavägen', 'Folkungagatan', 'Ringvägen',
            'Borgmästargatan', 'Norrmalmstorg', 'Sergels Torg', 'Medborgarplatsen',
        ];
        $cities = ['Stockholm', 'Gothenburg', 'Malmö', 'Uppsala', 'Västerås', 'Örebro', 'Linköping'];
        $postalCodes = ['111 20', '113 27', '115 20', '118 25', '120 66', '151 72', '411 05', '416 55', '703 61'];

        return $this->generator->randomElement($streetNumbers).' '.
               $this->generator->randomElement($streetNames).', '.
               $this->generator->randomElement($postalCodes).' '.
               $this->generator->randomElement($cities).', Sweden';
    }

    /**
     * Generate a Latvian address
     */
    public function latvianAddress()
    {
        $streetNumbers = ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '12', '14', '16', '18', '20'];
        $streetNames = [
            'Brīvības iela', 'Kr. Valdemāra iela', 'Dzirciema iela', 'Lāčplēša iela',
            'Elizabetes iela', 'Tērbatas iela', 'Matīsa iela', 'A. Čaka iela',
            'Stabu iela', 'Vēsturiskais centrs', 'Pērses iela', 'Rūpniecības iela',
        ];
        $cities = ['Rīga', 'Daugavpils', 'Liepāja', 'Jelgava', 'Jūrmala', 'Ventspils', 'Rēzekne', 'Valmiera', 'Ogre'];
        $postalCodes = ['LV-1001', 'LV-1010', 'LV-1050', 'LV-1082', 'LV-1053', 'LV-1048', 'LV-1002'];

        return $this->generator->randomElement($streetNumbers).' '.
               $this->generator->randomElement($streetNames).', '.
               $this->generator->randomElement($postalCodes).', '.
               $this->generator->randomElement($cities);
    }
}
