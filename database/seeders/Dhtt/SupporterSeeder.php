<?php

namespace Database\Seeders\Dhtt;

use App\Models\Supporter;
use Illuminate\Database\Seeder;

class SupporterSeeder extends Seeder
{
    /**
     * Támogatók.
     */
    public function run(): void
    {
        $supporters = [
            [
                'name' => 'BaraEV',
                'image_url' => 'baraev.png',
                'is_local' => false,
                'link' => '',
            ],
            [
                'name' => 'Bocskor Pékség',
                'image_url' => 'bocskorpekseg.jpg',
                'is_local' => false,
                'link' => 'http://bocskorpekseg.ro/',
            ],
            [
                'name' => 'End IBO',
                'image_url' => 'endibo.jpg',
                'is_local' => false,
                'link' => 'https://end-ibo.com/hu/',
            ],
            [
                'name' => 'Hargita Népe',
                'image_url' => 'hargitanepe.jpg',
                'is_local' => false,
                'link' => 'https://hargitanepe.eu/',
            ],
            [
                'name' => 'Harmopan',
                'image_url' => 'harmopan.jpg',
                'is_local' => false,
                'link' => 'https://harmopan.ro/',
            ],
            [
                'name' => 'Kissomlyó Síközpont',
                'image_url' => 'kissomlyoski.png',
                'is_local' => false,
                'link' => 'https://www.facebook.com/kissomlyo/?locale=hu_HU',
            ],
            [
                'name' => 'Marcipán Fagyi',
                'image_url' => 'marcipan.jpg',
                'is_local' => false,
                'link' => 'https://www.facebook.com/MARCIPANFAGYI/?locale=hu_HU',
            ],
            [
                'name' => 'Mikopaszka',
                'image_url' => 'mikopaszka.png',
                'is_local' => false,
                'link' => 'https://www.facebook.com/Mikopaszka/',
            ],
            [
                'name' => 'Pergament Office',
                'image_url' => 'papirmadar.jpg',
                'is_local' => false,
                'link' => 'https://pergamentoffice.ro/',
            ],
            [
                'name' => 'Székelyhon',
                'image_url' => 'szekelyhon.jpg',
                'is_local' => false,
                'link' => 'https://www.szekelyhon.ro/',
            ],
            [
                'name' => 'Székely TV',
                'image_url' => 'szekelytv.jpg',
                'is_local' => false,
                'link' => 'https://www.szekelytv.ro/',
            ],
            [
                'name' => 'Szépvíz FM',
                'image_url' => 'SzepvizFM.png',
                'is_local' => false,
                'link' => 'https://szepvizfm.ro/',
            ],
            [
                'name' => 'Thermal Wellness',
                'image_url' => 'Thermal Wellness.jpg',
                'is_local' => false,
                'link' => 'https://wellness-tusnad.ro/hu/',
            ],
            [
                'name' => 'Visit Harghita',
                'image_url' => 'visitharghita.jpg',
                'is_local' => false,
                'link' => 'https://visitharghita.com/',
            ],
        ];

        foreach ($supporters as $supporter) {
            $supporter['image_url'] = asset('img/dhtt/tamogatok/'.$supporter['image_url']);
            Supporter::create($supporter);
        }
    }
}
