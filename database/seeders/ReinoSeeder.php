<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Reino;

class ReinoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $reinos = [
            [
                'nombre' => 'Animalia',
                'descripcion' => 'Reino que incluye todos los organismos multicelulares eucariotas que se alimentan por ingestión.'
            ],
            [
                'nombre' => 'Plantae',
                'descripcion' => 'Reino que incluye organismos eucariotas multicelulares que realizan fotosíntesis.'
            ],
            [
                'nombre' => 'Fungi',
                'descripcion' => 'Reino que incluye organismos eucariotas que se alimentan por absorción, como hongos y levaduras.'
            ],
            [
                'nombre' => 'Protista',
                'descripcion' => 'Reino que incluye organismos eucariotas unicelulares y algunos multicelulares simples.'
            ],
            [
                'nombre' => 'Monera',
                'descripcion' => 'Reino que incluye organismos procariotas como bacterias y cianobacterias.'
            ]
        ];

        foreach ($reinos as $reino) {
            Reino::create($reino);
        }
    }
}
