<?php

namespace Database\Seeders;

use App\Models\ConservationStatus;
use Illuminate\Database\Seeder;

class ConservationStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            [
                'code' => 'EX',
                'name' => 'Extinto',
                'name_en' => 'Extinct',
                'description' => 'No hay duda razonable de que el último individuo existente ha muerto.',
                'color' => 'danger',
                'priority' => 9,
            ],
            [
                'code' => 'EW',
                'name' => 'Extinto en Estado Silvestre',
                'name_en' => 'Extinct in the Wild',
                'description' => 'Se sabe que solo sobrevive en cultivo, en cautividad o como población naturalizada fuera de su área de distribución histórica.',
                'color' => 'danger',
                'priority' => 8,
            ],
            [
                'code' => 'CR',
                'name' => 'En Peligro Crítico',
                'name_en' => 'Critically Endangered',
                'description' => 'Se considera que se enfrenta a un riesgo extremadamente alto de extinción en estado silvestre.',
                'color' => 'danger',
                'priority' => 7,
            ],
            [
                'code' => 'EN',
                'name' => 'En Peligro',
                'name_en' => 'Endangered',
                'description' => 'Se considera que se enfrenta a un riesgo muy alto de extinción en estado silvestre.',
                'color' => 'warning',
                'priority' => 6,
            ],
            [
                'code' => 'VU',
                'name' => 'Vulnerable',
                'name_en' => 'Vulnerable',
                'description' => 'Se considera que se enfrenta a un riesgo alto de extinción en estado silvestre.',
                'color' => 'warning',
                'priority' => 5,
            ],
            [
                'code' => 'NT',
                'name' => 'Casi Amenazado',
                'name_en' => 'Near Threatened',
                'description' => 'No califica para En Peligro Crítico, En Peligro o Vulnerable ahora, pero está cerca de calificar o es probable que califique para una categoría amenazada en el futuro cercano.',
                'color' => 'info',
                'priority' => 4,
            ],
            [
                'code' => 'LC',
                'name' => 'Preocupación Menor',
                'name_en' => 'Least Concern',
                'description' => 'Ha sido evaluado y no califica para En Peligro Crítico, En Peligro, Vulnerable o Casi Amenazado.',
                'color' => 'success',
                'priority' => 3,
            ],
            [
                'code' => 'DD',
                'name' => 'Datos Insuficientes',
                'name_en' => 'Data Deficient',
                'description' => 'No hay información adecuada para hacer una evaluación directa o indirecta de su riesgo de extinción.',
                'color' => 'secondary',
                'priority' => 2,
            ],
            [
                'code' => 'NE',
                'name' => 'No Evaluado',
                'name_en' => 'Not Evaluated',
                'description' => 'No ha sido evaluado contra los criterios.',
                'color' => 'secondary',
                'priority' => 1,
            ],
        ];

        foreach ($statuses as $status) {
            ConservationStatus::updateOrCreate(
                ['code' => $status['code']],
                $status
            );
        }
    }
}