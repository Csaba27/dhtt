<?php

namespace Database\Seeders\Dhtt;

use App\Models\Association;
use Illuminate\Database\Seeder;

class AssociationSeeder extends Seeder
{
    /**
     * Egyesületek.
     */
    public function run(): void
    {
        $associations = [
            'Csíkszéki EKE', 'Csíki TTE', 'EKE Székelyudvarhely', 'EKE Gyergyószentmiklós', 'EKE Háromszék', 'EKE Marosvásárhely', 'EKE Brassó',
            'EKE-Kolozsvár 189', 'EKE Szecseleváros', 'EKE \'91 Nagyvárad-Bihar', 'EKE Gutin Nagybánya', 'EKE Szatmárnémeti', 'Bánsági KE',
            'Kárpát Gyepu Egyesület', 'Magyarországi Kárpát Egyesület', 'Czárán Gyula Alapítvány', 'Más egyesület',
        ];

        foreach ($associations as $association) {
            Association::create(['name' => $association]);
        }
    }
}
