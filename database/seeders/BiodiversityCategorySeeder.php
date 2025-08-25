<?php

namespace Database\Seeders;

use App\Models\BiodiversityCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\File;

class BiodiversityCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Asegurarse de que el directorio de imágenes de ejemplo existe
        if (!Storage::disk('public')->exists('sample-images')) {
            Storage::disk('public')->makeDirectory('sample-images');
        }

        // Datos de ejemplo para categorías de biodiversidad
        $categories = [
            [
                'name' => 'Tigre de Bengala',
                'scientific_name' => 'Panthera tigris tigris',
                'kingdom' => 'animalia',
                'conservation_status' => 'EN',
                'habitat' => 'Bosques tropicales y subtropicales',
                'description' => '<p>El tigre de Bengala es una población de la subespecie de tigre Panthera tigris tigris que se encuentra en el subcontinente indio. Se encuentra en peligro de extinción debido a la caza furtiva y la pérdida de hábitat.</p><p>Es uno de los grandes felinos más reconocibles y es el felino más grande que existe en la actualidad. Los machos pueden pesar hasta 260 kg y medir hasta 3 metros de longitud.</p>',
                'image_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/1/17/Tiger_in_Ranthambhore.jpg/1200px-Tiger_in_Ranthambhore.jpg',
            ],
            [
                'name' => 'Orquídea Fantasma',
                'scientific_name' => 'Epipogium aphyllum',
                'kingdom' => 'plantae',
                'conservation_status' => 'VU',
                'habitat' => 'Bosques caducifolios',
                'description' => '<p>La orquídea fantasma es una especie de orquídea que carece de clorofila y obtiene sus nutrientes a través de una relación simbiótica con hongos. Es extremadamente rara y difícil de encontrar debido a su naturaleza efímera y su apariencia fantasmal.</p><p>Esta planta puede permanecer dormida bajo tierra durante años antes de florecer, y cuando lo hace, sus flores pueden durar solo unos pocos días.</p>',
                'image_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/7/70/Epipogium_aphyllum_-_Vogelnestwurz.jpg/800px-Epipogium_aphyllum_-_Vogelnestwurz.jpg',
            ],
            [
                'name' => 'Coral Cuerno de Ciervo',
                'scientific_name' => 'Acropora cervicornis',
                'kingdom' => 'animalia',
                'conservation_status' => 'CR',
                'habitat' => 'Arrecifes de coral del Caribe',
                'description' => '<p>El coral cuerno de ciervo es una especie de coral que forma colonias ramificadas que se asemejan a los cuernos de un ciervo. Es un constructor de arrecifes importante en el Caribe, pero ha sufrido una disminución dramática en las últimas décadas debido al blanqueamiento de coral, enfermedades y otros factores.</p><p>Esta especie puede crecer hasta 2 metros de altura y proporciona un hábitat crucial para muchas especies marinas.</p>',
                'image_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/2/2f/Staghorn_coral_%28Acropora_cervicornis%29_%286142275700%29.jpg/1200px-Staghorn_coral_%28Acropora_cervicornis%29_%286142275700%29.jpg',
            ],
            [
                'name' => 'Hongo Matsutake',
                'scientific_name' => 'Tricholoma matsutake',
                'kingdom' => 'fungi',
                'conservation_status' => 'NT',
                'habitat' => 'Bosques de coníferas',
                'description' => '<p>El matsutake es un hongo micorrízico que crece en asociación con ciertas especies de árboles, particularmente pinos. Es altamente valorado en la cocina japonesa por su aroma distintivo y sabor.</p><p>Su población ha disminuido significativamente en Japón debido a la enfermedad del pino y la pérdida de hábitat, lo que ha llevado a un aumento en su valor comercial.</p>',
                'image_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/9/9c/Matsutake.JPG/1200px-Matsutake.JPG',
            ],
            [
                'name' => 'Cóndor de California',
                'scientific_name' => 'Gymnogyps californianus',
                'kingdom' => 'animalia',
                'conservation_status' => 'CR',
                'habitat' => 'Montañas y cañones',
                'description' => '<p>El cóndor de California es una de las aves más grandes de América del Norte, con una envergadura de hasta 3 metros. Estuvo al borde de la extinción en la década de 1980, cuando solo quedaban 22 individuos en estado salvaje.</p><p>Gracias a los programas de conservación y cría en cautividad, su población ha aumentado, pero sigue siendo una especie en peligro crítico debido a la intoxicación por plomo y la pérdida de hábitat.</p>',
                'image_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/d/d5/Gymnogyps_californianus_-San_Diego_Zoo-8a.jpg/1200px-Gymnogyps_californianus_-San_Diego_Zoo-8a.jpg',
            ],
            [
                'name' => 'Secuoya Gigante',
                'scientific_name' => 'Sequoiadendron giganteum',
                'kingdom' => 'plantae',
                'conservation_status' => 'VU',
                'habitat' => 'Sierra Nevada, California',
                'description' => '<p>La secuoya gigante es una de las especies de árboles más grandes del mundo, tanto en altura como en volumen. Estos árboles pueden vivir más de 3,000 años y alcanzar alturas de más de 80 metros.</p><p>A pesar de su tamaño imponente, están amenazados por el cambio climático, los incendios forestales y la tala histórica. Solo quedan aproximadamente 75 bosques naturales de secuoyas gigantes.</p>',
                'image_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/e/e8/General_Sherman_Tree.jpg/800px-General_Sherman_Tree.jpg',
            ],
            [
                'name' => 'Axolote',
                'scientific_name' => 'Ambystoma mexicanum',
                'kingdom' => 'animalia',
                'conservation_status' => 'CR',
                'habitat' => 'Lagos de México',
                'description' => '<p>El axolote es una salamandra neoténica que mantiene sus características larvarias, incluidas sus branquias externas, durante toda su vida. Es nativo de los lagos de Xochimilco y Chalco en la Ciudad de México.</p><p>Es famoso por su extraordinaria capacidad de regeneración, pudiendo regenerar extremidades completas, partes del corazón y cerebro. Está en peligro crítico debido a la contaminación del agua, la introducción de especies invasoras y la urbanización.</p>',
                'image_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/f/f6/AxolotlBE.jpg/1200px-AxolotlBE.jpg',
            ],
            [
                'name' => 'Alga Roja Irlandesa',
                'scientific_name' => 'Palmaria palmata',
                'kingdom' => 'protista',
                'conservation_status' => 'LC',
                'habitat' => 'Costas rocosas del Atlántico Norte',
                'description' => '<p>El alga roja irlandesa, también conocida como dulse, es un alga roja comestible que crece en las costas rocosas del Atlántico Norte. Ha sido cosechada durante siglos como alimento y por sus propiedades medicinales.</p><p>Es rica en minerales, vitaminas y proteínas, y se considera un superalimento. Aunque su estado de conservación es de preocupación menor, algunas poblaciones locales están disminuyendo debido a la contaminación costera y el cambio climático.</p>',
                'image_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/3/3e/Palmaria_palmata_-_Dulse_-_Dilisk_-_Creathnach.jpg/1200px-Palmaria_palmata_-_Dulse_-_Dilisk_-_Creathnach.jpg',
            ],
            [
                'name' => 'Bacteria Extremófila Deinococcus radiodurans',
                'scientific_name' => 'Deinococcus radiodurans',
                'kingdom' => 'monera',
                'conservation_status' => 'NE',
                'habitat' => 'Ambientes extremos',
                'description' => '<p>Deinococcus radiodurans es una bacteria extremadamente resistente a la radiación, el frío, la deshidratación, el vacío y los ácidos. Puede sobrevivir a dosis de radiación cientos de veces mayores que las letales para los humanos.</p><p>Su extraordinaria resistencia se debe a su capacidad para reparar eficientemente su ADN dañado. Esta bacteria tiene aplicaciones potenciales en biorremediación y biotecnología.</p>',
                'image_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/8/82/Deinococcus_radiodurans.jpg/1200px-Deinococcus_radiodurans.jpg',
            ],
            [
                'name' => 'Mariposa Monarca',
                'scientific_name' => 'Danaus plexippus',
                'kingdom' => 'animalia',
                'conservation_status' => 'EN',
                'habitat' => 'América del Norte',
                'description' => '<p>La mariposa monarca es conocida por su impresionante migración anual desde Canadá y Estados Unidos hasta México, un viaje de hasta 4,000 kilómetros. Esta migración es un fenómeno natural único que involucra a millones de mariposas.</p><p>Su población ha disminuido drásticamente en las últimas décadas debido a la pérdida de hábitat, el uso de pesticidas y el cambio climático. La conservación de la mariposa monarca requiere esfuerzos internacionales para proteger sus rutas migratorias y áreas de hibernación.</p>',
                'image_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/6/63/Monarch_In_May.jpg/1200px-Monarch_In_May.jpg',
            ],
        ];

        foreach ($categories as $categoryData) {
            $category = BiodiversityCategory::create([
                'name' => $categoryData['name'],
                'scientific_name' => $categoryData['scientific_name'],
                'kingdom' => $categoryData['kingdom'],
                'conservation_status' => $categoryData['conservation_status'],
                'habitat' => $categoryData['habitat'],
                'description' => $categoryData['description'],
            ]);

            // Descargar y adjuntar imagen
            try {
                $imageUrl = $categoryData['image_url'];
                $imageContents = file_get_contents($imageUrl);
                $tempFile = tempnam(sys_get_temp_dir(), 'img');
                file_put_contents($tempFile, $imageContents);
                
                $category->addMedia($tempFile)
                    ->usingName(Str::slug($category->name))
                    ->usingFileName(Str::slug($category->name) . '.jpg')
                    ->toMediaCollection('images');
                    
                @unlink($tempFile);
            } catch (\Exception $e) {
                // Si falla la descarga de la imagen, continuar con el siguiente
                $this->command->info("No se pudo descargar la imagen para {$category->name}: {$e->getMessage()}");
            }
        }
    }
}