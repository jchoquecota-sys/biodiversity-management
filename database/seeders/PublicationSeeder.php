<?php

namespace Database\Seeders;

use App\Models\Publication;
use App\Models\BiodiversityCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\File;

class PublicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Asegurarse de que el directorio de PDFs de ejemplo existe
        if (!Storage::disk('public')->exists('sample-pdfs')) {
            Storage::disk('public')->makeDirectory('sample-pdfs');
        }

        // Datos de ejemplo para publicaciones
        $publications = [
            [
                'title' => 'Conservación del Tigre de Bengala: Desafíos y Estrategias',
                'author' => 'Sharma, P. & Kumar, A.',
                'publication_year' => 2021,
                'journal' => 'Journal of Wildlife Conservation',
                'doi' => '10.1234/jwc.2021.0123',
                'abstract' => '<p>Este estudio examina los desafíos actuales en la conservación del tigre de Bengala (Panthera tigris tigris) en el subcontinente indio. Se analizan las amenazas principales como la pérdida de hábitat, la caza furtiva y los conflictos humano-tigre, así como las estrategias de conservación implementadas en diferentes reservas de tigres.</p><p>Los resultados indican que las áreas protegidas con corredores ecológicos funcionales y participación comunitaria muestran mejores tasas de recuperación de población. Se proponen recomendaciones para mejorar los esfuerzos de conservación, incluyendo el fortalecimiento de la vigilancia contra la caza furtiva, la restauración de hábitats degradados y la implementación de programas de compensación por pérdidas de ganado.</p>',
                'pdf_url' => 'https://www.example.com/sample.pdf', // URL ficticia
                'related_biodiversity' => [
                    'Tigre de Bengala' => [
                        'excerpt' => 'La población de tigres de Bengala ha mostrado signos de recuperación en áreas donde se han implementado medidas estrictas de protección y participación comunitaria. En la Reserva de Tigres de Ranthambore, la población aumentó de 25 individuos en 2010 a 45 en 2020, demostrando la eficacia de las estrategias integradas de conservación.',
                        'page' => 'pp. 45-47'
                    ]
                ]
            ],
            [
                'title' => 'Impacto del Cambio Climático en los Arrecifes de Coral del Caribe',
                'author' => 'Martínez, L. & Johnson, T.',
                'publication_year' => 2022,
                'journal' => 'Marine Ecology Progress',
                'doi' => '10.5678/mep.2022.0456',
                'abstract' => '<p>Este estudio evalúa el impacto del cambio climático en los arrecifes de coral del Caribe, con especial atención al coral cuerno de ciervo (Acropora cervicornis). Se analizaron datos de temperatura del agua, acidificación oceánica y eventos de blanqueamiento durante un período de 15 años (2007-2022).</p><p>Los resultados muestran una correlación significativa entre el aumento de la temperatura del mar y la frecuencia de eventos de blanqueamiento, con consecuencias devastadoras para las poblaciones de A. cervicornis. Se discuten las implicaciones para la biodiversidad marina y se proponen medidas de mitigación y adaptación para la conservación de estos ecosistemas críticos.</p>',
                'pdf_url' => 'https://www.example.com/sample2.pdf', // URL ficticia
                'related_biodiversity' => [
                    'Coral Cuerno de Ciervo' => [
                        'excerpt' => 'Las poblaciones de Acropora cervicornis han disminuido más del 80% en las últimas tres décadas en toda la región del Caribe. Los eventos de blanqueamiento masivo registrados en 2010, 2015 y 2020 han sido particularmente devastadores, con tasas de mortalidad superiores al 60% en algunas áreas estudiadas.',
                        'page' => 'p. 23'
                    ]
                ]
            ],
            [
                'title' => 'Regeneración Tisular en Axolotes: Mecanismos Moleculares y Aplicaciones Biomédicas',
                'author' => 'González, R. & Smith, J.',
                'publication_year' => 2020,
                'journal' => 'Developmental Biology Research',
                'doi' => '10.9012/dbr.2020.0789',
                'abstract' => '<p>Este estudio investiga los mecanismos moleculares subyacentes a la extraordinaria capacidad de regeneración del axolote (Ambystoma mexicanum). Se analizaron los perfiles de expresión génica durante la regeneración de extremidades, identificando vías de señalización clave y factores de transcripción involucrados en este proceso.</p><p>Los resultados revelan la importancia de la reprogramación celular y la activación de células madre en la formación del blastema regenerativo. Se discuten las implicaciones para la medicina regenerativa humana y las posibles aplicaciones terapéuticas, así como la importancia de conservar esta especie en peligro crítico como recurso para la investigación biomédica.</p>',
                'pdf_url' => 'https://www.example.com/sample3.pdf', // URL ficticia
                'related_biodiversity' => [
                    'Axolote' => [
                        'excerpt' => 'El axolote puede regenerar extremidades completas, partes del corazón, secciones del cerebro y médula espinal, y otros órganos. Nuestros análisis identificaron 378 genes diferencialmente expresados durante las primeras 72 horas después de la amputación de una extremidad, con una sobrerrepresentación de genes involucrados en la respuesta inmune, proliferación celular y remodelación de la matriz extracelular.',
                        'page' => 'pp. 112-115'
                    ]
                ]
            ],
            [
                'title' => 'Diversidad Genética y Conservación de la Secuoya Gigante en el Contexto del Cambio Climático',
                'author' => 'Wilson, E. & García, M.',
                'publication_year' => 2019,
                'journal' => 'Forest Ecology and Management',
                'doi' => '10.3456/fem.2019.0234',
                'abstract' => '<p>Este estudio evalúa la diversidad genética de las poblaciones remanentes de secuoya gigante (Sequoiadendron giganteum) y su vulnerabilidad ante el cambio climático. Se analizaron muestras de 20 bosques naturales utilizando marcadores genéticos para determinar la estructura poblacional y la variabilidad genética.</p><p>Los resultados indican niveles moderados de diversidad genética, con evidencia de cuellos de botella históricos. Los modelos de distribución de especies bajo diferentes escenarios climáticos sugieren una contracción significativa del hábitat adecuado para esta especie en las próximas décadas. Se discuten estrategias de conservación, incluyendo la migración asistida y el establecimiento de corredores ecológicos.</p>',
                'pdf_url' => 'https://www.example.com/sample4.pdf', // URL ficticia
                'related_biodiversity' => [
                    'Secuoya Gigante' => [
                        'excerpt' => 'Los análisis genéticos revelaron que los bosques de secuoyas del norte de Sierra Nevada muestran mayor diversidad genética que los del sur, sugiriendo que estas poblaciones podrían ser prioritarias para la conservación. Los modelos climáticos proyectan una reducción del 25-40% en el hábitat adecuado para S. giganteum para 2080 bajo escenarios de emisiones moderadas a altas.',
                        'page' => 'p. 78'
                    ]
                ]
            ],
            [
                'title' => 'Rutas Migratorias y Conservación de la Mariposa Monarca en América del Norte',
                'author' => 'López, C. & Anderson, B.',
                'publication_year' => 2023,
                'journal' => 'Insect Conservation and Diversity',
                'doi' => '10.7890/icd.2023.0567',
                'abstract' => '<p>Este estudio documenta las rutas migratorias actuales de la mariposa monarca (Danaus plexippus) en América del Norte y evalúa los factores que afectan su migración. Se utilizaron datos de monitoreo ciudadano, seguimiento con etiquetas y modelado espacial para mapear las rutas migratorias y identificar áreas críticas.</p><p>Los resultados muestran cambios significativos en las rutas migratorias tradicionales, posiblemente debido al cambio climático y la pérdida de hábitat. Se identificaron "cuellos de botella" críticos donde la conservación debería priorizarse. Se discuten estrategias de conservación transfronterizas, incluyendo la protección de sitios de descanso, la restauración de hábitats con plantas hospederas y la reducción del uso de pesticidas.</p>',
                'pdf_url' => 'https://www.example.com/sample5.pdf', // URL ficticia
                'related_biodiversity' => [
                    'Mariposa Monarca' => [
                        'excerpt' => 'La población oriental de mariposas monarca, que migra a México, ha disminuido aproximadamente un 80% desde la década de 1990. Nuestro análisis identificó tres corredores migratorios principales que han experimentado una degradación significativa del hábitat, con una pérdida estimada del 40% de plantas de algodoncillo (Asclepias spp.) en las últimas dos décadas.',
                        'page' => 'pp. 34-36'
                    ]
                ]
            ],
            [
                'title' => 'Propiedades Medicinales del Hongo Matsutake: Compuestos Bioactivos y Aplicaciones Potenciales',
                'author' => 'Tanaka, H. & Miller, S.',
                'publication_year' => 2018,
                'journal' => 'Journal of Ethnopharmacology',
                'doi' => '10.2345/jep.2018.0345',
                'abstract' => '<p>Este estudio investiga los compuestos bioactivos presentes en el hongo matsutake (Tricholoma matsutake) y sus potenciales aplicaciones medicinales. Se aislaron y caracterizaron varios compuestos, incluyendo polisacáridos, terpenos y compuestos fenólicos, y se evaluaron sus propiedades antioxidantes, antiinflamatorias e inmunomoduladoras.</p><p>Los resultados indican que los extractos de T. matsutake poseen actividad antioxidante significativa y efectos inmunomoduladores prometedores. Se discute el uso tradicional de este hongo en la medicina asiática y su potencial como fuente de nuevos compuestos farmacéuticos, así como la importancia de su conservación como recurso medicinal.</p>',
                'pdf_url' => 'https://www.example.com/sample6.pdf', // URL ficticia
                'related_biodiversity' => [
                    'Hongo Matsutake' => [
                        'excerpt' => 'Los polisacáridos extraídos de T. matsutake mostraron una potente actividad inmunomoduladora, aumentando la producción de citoquinas proinflamatorias en macrófagos y estimulando la proliferación de linfocitos T. Además, los extractos etanólicos exhibieron una capacidad antioxidante comparable a la del ácido ascórbico, con un IC50 de 45.3 μg/ml en el ensayo de eliminación de radicales DPPH.',
                        'page' => 'p. 189'
                    ]
                ]
            ],
            [
                'title' => 'Aplicaciones Biotecnológicas de Deinococcus radiodurans en Biorremediación de Sitios Contaminados con Radiación',
                'author' => 'Chen, W. & Rodríguez, D.',
                'publication_year' => 2020,
                'journal' => 'Applied and Environmental Microbiology',
                'doi' => '10.5432/aem.2020.0876',
                'abstract' => '<p>Este estudio explora el potencial de la bacteria extremófila Deinococcus radiodurans para la biorremediación de sitios contaminados con radiación y metales pesados. Se desarrollaron cepas modificadas genéticamente con capacidad mejorada para degradar compuestos tóxicos y acumular metales radioactivos.</p><p>Los resultados de experimentos de laboratorio y pruebas piloto en sitios contaminados demuestran la eficacia de estas cepas en la reducción de niveles de uranio, mercurio y cesio radioactivo. Se discuten los mecanismos moleculares de resistencia a la radiación y las consideraciones de bioseguridad para aplicaciones a gran escala.</p>',
                'pdf_url' => 'https://www.example.com/sample7.pdf', // URL ficticia
                'related_biodiversity' => [
                    'Bacteria Extremófila Deinococcus radiodurans' => [
                        'excerpt' => 'Las cepas modificadas de D. radiodurans que expresan la reductasa mercúrica (merA) y la reductasa de uranio (phoN) mostraron una capacidad notable para reducir la toxicidad en suelos contaminados, disminuyendo las concentraciones de uranio soluble en un 87% y de mercurio en un 94% después de 120 horas de incubación en condiciones de alta radiación (5 kGy).',
                        'page' => 'pp. 67-70'
                    ]
                ]
            ],
            [
                'title' => 'Propiedades Nutricionales y Antioxidantes del Alga Roja Irlandesa (Palmaria palmata)',
                'author' => 'O\'Sullivan, A. & Fernández, L.',
                'publication_year' => 2021,
                'journal' => 'Journal of Applied Phycology',
                'doi' => '10.6789/jap.2021.0678',
                'abstract' => '<p>Este estudio evalúa el perfil nutricional y las propiedades antioxidantes del alga roja irlandesa (Palmaria palmata) recolectada en diferentes estaciones y localidades de la costa atlántica europea. Se analizaron el contenido de proteínas, minerales, vitaminas, ácidos grasos y compuestos fenólicos, así como la actividad antioxidante mediante diversos ensayos.</p><p>Los resultados revelan un alto contenido proteico (hasta 35% del peso seco) y una composición de aminoácidos equilibrada, así como niveles significativos de minerales esenciales y compuestos bioactivos con potente actividad antioxidante. Se discute el potencial de esta alga como alimento funcional y suplemento nutricional, así como las implicaciones para su cultivo sostenible.</p>',
                'pdf_url' => 'https://www.example.com/sample8.pdf', // URL ficticia
                'related_biodiversity' => [
                    'Alga Roja Irlandesa' => [
                        'excerpt' => 'Las muestras de P. palmata recolectadas en primavera mostraron el mayor contenido de proteínas (35.2 ± 2.1% del peso seco) y compuestos fenólicos (14.7 ± 1.3 mg GAE/g), así como la mayor actividad antioxidante. El análisis de minerales reveló concentraciones excepcionales de hierro (35.7 mg/100g), yodo (150.3 μg/100g) y calcio (912 mg/100g), superiores a muchos alimentos terrestres.',
                        'page' => 'p. 145'
                    ]
                ]
            ],
            [
                'title' => 'Polinización y Reproducción de la Orquídea Fantasma (Epipogium aphyllum) en Bosques Europeos',
                'author' => 'Müller, H. & Novak, J.',
                'publication_year' => 2019,
                'journal' => 'Botanical Journal of the Linnean Society',
                'doi' => '10.8765/bjls.2019.0987',
                'abstract' => '<p>Este estudio investiga los mecanismos de polinización y reproducción de la rara orquídea fantasma (Epipogium aphyllum) en bosques caducifolios de Europa Central. Se monitorearon poblaciones en Alemania, Austria y República Checa durante cinco años, documentando la fenología, visitantes florales y éxito reproductivo.</p><p>Los resultados indican que E. aphyllum es principalmente polinizada por abejorros del género Bombus, aunque se observó una alta tasa de autopolinización. La producción de semillas fue extremadamente variable entre años y poblaciones, influenciada por factores climáticos. Se discuten las implicaciones para la conservación de esta especie enigmática y sus relaciones simbióticas con hongos micorrízicos.</p>',
                'pdf_url' => 'https://www.example.com/sample9.pdf', // URL ficticia
                'related_biodiversity' => [
                    'Orquídea Fantasma' => [
                        'excerpt' => 'Durante el período de estudio, solo el 23% de los individuos monitoreados emergieron y florecieron en años consecutivos, mientras que el 45% permaneció bajo tierra durante al menos dos años entre floraciones. La tasa de visitas de polinizadores fue extremadamente baja (0.08 visitas/flor/hora), pero el 67% de las flores produjo cápsulas de semillas, sugiriendo un alto nivel de autopolinización.',
                        'page' => 'pp. 203-205'
                    ]
                ]
            ],
            [
                'title' => 'Estrategias de Reintroducción y Monitoreo del Cóndor de California: Lecciones Aprendidas y Desafíos Futuros',
                'author' => 'Ramírez, P. & Thompson, K.',
                'publication_year' => 2022,
                'journal' => 'Conservation Biology',
                'doi' => '10.1357/cb.2022.1234',
                'abstract' => '<p>Este estudio evalúa los programas de reintroducción del cóndor de California (Gymnogyps californianus) implementados desde 1992 hasta 2022. Se analizaron datos demográficos, genéticos y de comportamiento de las poblaciones reintroducidas en California, Arizona y Baja California.</p><p>Los resultados muestran un aumento sostenido en la población total, que ha pasado de 22 individuos en 1982 a más de 500 en 2022, con aproximadamente la mitad viviendo en estado salvaje. Sin embargo, la intoxicación por plomo sigue siendo la principal causa de mortalidad. Se discuten estrategias para mitigar esta amenaza, mejorar la diversidad genética y fomentar comportamientos naturales de anidación y alimentación.</p>',
                'pdf_url' => 'https://www.example.com/sample10.pdf', // URL ficticia
                'related_biodiversity' => [
                    'Cóndor de California' => [
                        'excerpt' => 'El análisis de 30 años de datos de reintroducción revela que la supervivencia anual de cóndores adultos en estado salvaje ha aumentado del 65% en la década de 1990 al 78% en la última década. Sin embargo, el 76% de las muertes documentadas están relacionadas con la intoxicación por plomo, principalmente por la ingestión de carroña de animales cazados con munición de plomo.',
                        'page' => 'pp. 89-92'
                    ]
                ]
            ],
        ];

        // Obtener todas las categorías de biodiversidad
        $biodiversityCategories = BiodiversityCategory::all()->keyBy('name');

        foreach ($publications as $publicationData) {
            $publication = Publication::create([
                'title' => $publicationData['title'],
                'author' => $publicationData['author'],
                'publication_year' => $publicationData['publication_year'],
                'journal' => $publicationData['journal'],
                'doi' => $publicationData['doi'],
                'abstract' => $publicationData['abstract'],
            ]);

            // Crear un PDF de ejemplo
            try {
                // Crear un PDF simple con FPDF (simulado aquí)
                $tempFile = tempnam(sys_get_temp_dir(), 'pdf');
                $content = "Sample PDF for {$publication->title}\n\nAuthor: {$publication->author}\nYear: {$publication->publication_year}\n\nThis is a sample PDF file created for demonstration purposes.";
                file_put_contents($tempFile, $content);
                
                $publication->addMedia($tempFile)
                    ->usingName(Str::slug($publication->title))
                ->usingFileName(Str::slug($publication->title) . '.pdf')
                    ->toMediaCollection('pdfs');
                    
                @unlink($tempFile);
            } catch (\Exception $e) {
                $this->command->info("No se pudo crear el PDF para {$publication->title}: {$e->getMessage()}");
            }

            // Relacionar con categorías de biodiversidad
            foreach ($publicationData['related_biodiversity'] as $biodiversityName => $data) {
                if (isset($biodiversityCategories[$biodiversityName])) {
                    $biodiversity = $biodiversityCategories[$biodiversityName];
                    $publication->biodiversityCategories()->attach($biodiversity->id, [
                        'relevant_excerpt' => $data['excerpt'],
                        'page_reference' => $data['page'],
                    ]);
                }
            }
        }
    }
}