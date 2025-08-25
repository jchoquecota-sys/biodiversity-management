<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BiodiversityCategory;
use App\Models\ConservationStatus;
use App\Models\Reino;
use App\Models\Clase;
use App\Models\Orden;
use App\Models\Familia;

class PeruvianBiodiversitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Los estados de conservación y reinos se manejan como strings directamente

        $species = [
            // MAMÍFEROS PERUANOS
            [
                'name' => 'Oso de Anteojos',
                'scientific_name' => 'Tremarctos ornatus',
                'kingdom' => 'Animalia',
                'habitat' => 'Bosques nublados y páramos andinos entre 1,000 y 4,200 msnm',
                'description' => 'Único oso nativo de Sudamérica, caracterizado por las manchas claras alrededor de los ojos que le dan su nombre. Es una especie emblemática del Perú y símbolo de conservación.',
                'conservation_status' => 'VU',
                'image_path' => 'images/peru/oso_anteojos_1.svg'
            ],
            [
                'name' => 'Vicuña',
                'scientific_name' => 'Vicugna vicugna',
                'kingdom' => 'Animalia',
                'habitat' => 'Puna y altiplano andino entre 3,200 y 4,800 msnm',
                'description' => 'Camélido sudamericano silvestre, ancestro de la alpaca. Produce la fibra más fina del mundo y es símbolo nacional del Perú.',
                'conservation_status' => 'LC',
                'image_path' => 'images/peru/vicuna_1.svg'
            ],
            [
                'name' => 'Jaguar',
                'scientific_name' => 'Panthera onca',
                'kingdom' => 'Animalia',
                'habitat' => 'Selva amazónica, bosques tropicales húmedos',
                'description' => 'El felino más grande de América, depredador tope de la Amazonía peruana. Excelente nadador y cazador nocturno.',
                'conservation_status' => 'NT',
                'image_path' => 'images/peru/jaguar_1.svg'
            ],
            [
                'name' => 'Mono Choro de Cola Amarilla',
                'scientific_name' => 'Oreonax flavicauda',
                'kingdom' => 'Animalia',
                'habitat' => 'Bosques nublados de los Andes del norte del Perú, entre 1,500 y 2,700 msnm',
                'description' => 'Primate endémico del Perú, redescubierto en 1974. Vive en los bosques nublados de Amazonas y San Martín.',
                'conservation_status' => 'CR',
                'image_path' => 'images/peru/mono_choro_1.svg'
            ],
            [
                'name' => 'Nutria Gigante',
                'scientific_name' => 'Pteronura brasiliensis',
                'kingdom' => 'Animalia',
                'habitat' => 'Ríos y lagos de la Amazonía peruana',
                'description' => 'La nutria más grande del mundo, puede medir hasta 2 metros. Vive en grupos familiares y es excelente pescadora.',
                'conservation_status' => 'EN',
                'image_path' => 'images/peru/nutria_gigante_1.svg'
            ],

            // AVES PERUANAS
            [
                'name' => 'Gallito de las Rocas',
                'scientific_name' => 'Rupicola peruvianus',
                'kingdom' => 'Animalia',
                'habitat' => 'Bosques nublados de la vertiente oriental de los Andes, entre 500 y 2,400 msnm',
                'description' => 'Ave nacional del Perú, famosa por su plumaje anaranjado brillante y su elaborado ritual de cortejo.',
                'conservation_status' => 'LC',
                'image_path' => 'images/peru/gallito_rocas_1.svg'
            ],
            [
                'name' => 'Cóndor Andino',
                'scientific_name' => 'Vultur gryphus',
                'kingdom' => 'Animalia',
                'habitat' => 'Cordillera de los Andes, desde el nivel del mar hasta 5,000 msnm',
                'description' => 'Ave voladora más grande del mundo, símbolo de los Andes. Puede volar hasta 300 km sin batir las alas.',
                'conservation_status' => 'NT',
                'image_path' => 'images/peru/condor_1.svg'
            ],
            [
                'name' => 'Colibrí Cola de Espátula',
                'scientific_name' => 'Loddigesia mirabilis',
                'kingdom' => 'Animalia',
                'habitat' => 'Bosques montanos del norte del Perú, entre 2,100 y 2,900 msnm',
                'description' => 'Colibrí endémico del Perú con una cola única en forma de espátula. Una de las aves más raras del mundo.',
                'conservation_status' => 'EN',
                'image_path' => 'images/peru/colibri_espatula_1.svg'
            ],
            [
                'name' => 'Guacamayo Rojo',
                'scientific_name' => 'Ara macao',
                'kingdom' => 'Animalia',
                'habitat' => 'Selva amazónica, bosques tropicales húmedos',
                'description' => 'Loro grande y colorido, símbolo de la biodiversidad amazónica. Forma parejas de por vida.',
                'conservation_status' => 'LC',
                'image_path' => 'images/peru/guacamayo_rojo_1.svg'
            ],
            [
                'name' => 'Pinguino de Humboldt',
                'scientific_name' => 'Spheniscus humboldti',
                'kingdom' => 'Animalia',
                'habitat' => 'Costa peruana, islas guaneras y acantilados rocosos',
                'description' => 'Pingüino que habita las costas del Perú y Chile, adaptado a aguas templadas de la Corriente de Humboldt.',
                'conservation_status' => 'VU',
                'image_path' => 'images/peru/pinguino_humboldt_1.svg'
            ],

            // REPTILES PERUANOS
            [
                'name' => 'Anaconda Verde',
                'scientific_name' => 'Eunectes murinus',
                'kingdom' => 'Animalia',
                'habitat' => 'Ríos, pantanos y humedales de la Amazonía peruana',
                'description' => 'La serpiente más pesada del mundo, excelente nadadora. Puede medir hasta 9 metros de longitud.',
                'conservation_status' => 'LC',
                'image_path' => 'images/peru/anaconda_1.svg'
            ],
            [
                'name' => 'Iguana Verde',
                'scientific_name' => 'Iguana iguana',
                'kingdom' => 'Animalia',
                'habitat' => 'Bosques tropicales de la Amazonía peruana',
                'description' => 'Lagarto herbívoro de gran tamaño, excelente nadador y trepador. Importante dispersor de semillas.',
                'conservation_status' => 'LC',
                'image_path' => 'images/peru/iguana_verde_1.svg'
            ],

            // ANFIBIOS PERUANOS
            [
                'name' => 'Rana Venenosa Dorada',
                'scientific_name' => 'Phyllobates aurotaenia',
                'kingdom' => 'Animalia',
                'habitat' => 'Bosques húmedos de la Amazonía peruana',
                'description' => 'Pequeña rana de colores brillantes, su piel contiene alcaloides tóxicos utilizados tradicionalmente en dardos.',
                'conservation_status' => 'NT',
                'image_path' => 'images/peru/rana_venenosa_1.svg'
            ],

            // PECES PERUANOS
            [
                'name' => 'Paiche',
                'scientific_name' => 'Arapaima gigas',
                'kingdom' => 'Animalia',
                'habitat' => 'Ríos y lagos de la Amazonía peruana',
                'description' => 'Uno de los peces de agua dulce más grandes del mundo, puede medir hasta 3 metros. Respira aire atmosférico.',
                'conservation_status' => 'VU',
                'image_path' => 'images/peru/paiche_1.svg'
            ],
            [
                'name' => 'Delfín Rosado',
                'scientific_name' => 'Inia geoffrensis',
                'kingdom' => 'Animalia',
                'habitat' => 'Ríos de la Amazonía peruana',
                'description' => 'Delfín de río endémico de la Amazonía, su color rosado se intensifica con la edad. Muy inteligente y social.',
                'conservation_status' => 'EN',
                'image_path' => 'images/peru/delfin_rosado_1.svg'
            ],

            // PLANTAS PERUANAS
            [
                'name' => 'Puya Raimondi',
                'scientific_name' => 'Puya raimondii',
                'kingdom' => 'Plantae',
                'habitat' => 'Puna de los Andes centrales del Perú, entre 3,200 y 4,800 msnm',
                'description' => 'La bromeliacea más grande del mundo, puede vivir hasta 100 años y alcanzar 12 metros de altura al florecer.',
                'conservation_status' => 'VU',
                'image_path' => 'images/peru/puya_raimondi_1.svg'
            ],
            [
                'name' => 'Orquídea Waqanki',
                'scientific_name' => 'Masdevallia veitchiana',
                'kingdom' => 'Plantae',
                'habitat' => 'Bosques nublados de los Andes peruanos, entre 2,500 y 3,500 msnm',
                'description' => 'Orquídea endémica del Perú con flores de color naranja intenso. Flor nacional del Perú.',
                'conservation_status' => 'VU',
                'image_path' => 'images/peru/orquidea_waqanki_1.svg'
            ],
            [
                'name' => 'Cantuta',
                'scientific_name' => 'Cantua buxifolia',
                'kingdom' => 'Plantae',
                'habitat' => 'Valles interandinos del Perú, entre 1,200 y 3,800 msnm',
                'description' => 'Flor sagrada de los incas, símbolo nacional del Perú junto con Bolivia. Flores tubulares de colores vivos.',
                'conservation_status' => 'LC',
                'image_path' => 'images/peru/cantuta_1.svg'
            ],
            [
                'name' => 'Quinua',
                'scientific_name' => 'Chenopodium quinoa',
                'kingdom' => 'Plantae',
                'habitat' => 'Altiplano andino del Perú, entre 3,500 y 4,000 msnm',
                'description' => 'Pseudocereal andino, superalimento con proteínas completas. Cultivado por más de 5,000 años en los Andes.',
                'conservation_status' => 'LC',
                'image_path' => 'images/peru/quinua_1.svg'
            ],
            [
                'name' => 'Cacao Peruano',
                'scientific_name' => 'Theobroma cacao',
                'kingdom' => 'Plantae',
                'habitat' => 'Selva amazónica del Perú, entre 200 y 1,000 msnm',
                'description' => 'Árbol del cacao, origen del chocolate. El Perú produce algunos de los cacaos más finos del mundo.',
                'conservation_status' => 'LC',
                'image_path' => 'images/peru/cacao_1.svg'
            ],

            // INVERTEBRADOS PERUANOS
            [
                'name' => 'Mariposa Morpho Azul',
                'scientific_name' => 'Morpho menelaus',
                'kingdom' => 'Animalia',
                'habitat' => 'Selva amazónica del Perú, bosques tropicales húmedos',
                'description' => 'Mariposa de gran tamaño con alas azul metálico iridiscente. Sus alas pueden medir hasta 20 cm de envergadura.',
                'conservation_status' => 'LC',
                'image_path' => 'images/peru/morpho_azul_1.svg'
            ],
            [
                'name' => 'Tarántula Goliath',
                'scientific_name' => 'Theraphosa blondi',
                'kingdom' => 'Animalia',
                'habitat' => 'Selva amazónica del Perú, suelos húmedos del bosque',
                'description' => 'La araña más grande del mundo por masa corporal. Puede tener una envergadura de hasta 30 cm.',
                'conservation_status' => 'LC',
                'image_path' => 'images/peru/tarantula_goliath_1.svg'
            ],

            // ESPECIES MARINAS PERUANAS
            [
                'name' => 'Lobo Marino Sudamericano',
                'scientific_name' => 'Otaria flavescens',
                'kingdom' => 'Animalia',
                'habitat' => 'Costa peruana, playas rocosas e islas',
                'description' => 'Pinnípedo que habita las costas del Pacífico sudamericano. Los machos pueden pesar hasta 350 kg.',
                'conservation_status' => 'LC',
                'image_path' => 'images/peru/lobo_marino_1.svg'
            ],
            [
                'name' => 'Anchoveta Peruana',
                'scientific_name' => 'Engraulis ringens',
                'kingdom' => 'Animalia',
                'habitat' => 'Aguas costeras del Perú, mar peruano',
                'description' => 'Pez pelágico base de la cadena alimentaria marina peruana. Sustenta la industria pesquera más importante del país.',
                'conservation_status' => 'LC',
                'image_path' => 'images/peru/anchoveta_1.svg'
            ],

            // ESPECIES ADICIONALES
            [
                'name' => 'Chinchilla de Cola Larga',
                'scientific_name' => 'Chinchilla chinchilla',
                'kingdom' => 'Animalia',
                'habitat' => 'Andes del norte del Perú, zonas rocosas entre 3,000 y 5,000 msnm',
                'description' => 'Roedor con el pelaje más denso del mundo, hasta 60 pelos por folículo. Casi extinto en estado silvestre.',
                'conservation_status' => 'CR',
                'image_path' => 'images/peru/chinchilla_1.svg'
            ],
            [
                'name' => 'Sachavaca',
                'scientific_name' => 'Tapirus terrestris',
                'kingdom' => 'Animalia',
                'habitat' => 'Selva amazónica del Perú, cerca de ríos y pantanos',
                'description' => 'El mamífero terrestre más grande de la Amazonía. Excelente nadador y dispersor de semillas.',
                'conservation_status' => 'VU',
                'image_path' => 'images/peru/sachavaca_1.svg'
            ],
            [
                'name' => 'Rana Gigante del Titicaca',
                'scientific_name' => 'Telmatobius culeus',
                'kingdom' => 'Animalia',
                'habitat' => 'Lago Titicaca, endémica de este ecosistema',
                'description' => 'Rana acuática endémica del Lago Titicaca, la más grande de Sudamérica. Respira a través de su piel.',
                'conservation_status' => 'CR',
                'image_path' => 'images/peru/rana_titicaca_1.svg'
            ],
            [
                'name' => 'Maca',
                'scientific_name' => 'Lepidium meyenii',
                'kingdom' => 'Plantae',
                'habitat' => 'Puna de los Andes centrales del Perú, entre 4,000 y 4,500 msnm',
                'description' => 'Planta adaptógena cultivada en los Andes por más de 2,000 años. Conocida por sus propiedades nutritivas y medicinales.',
                'conservation_status' => 'LC',
                'image_path' => 'images/peru/maca_1.svg'
            ],
            [
                'name' => 'Uña de Gato',
                'scientific_name' => 'Uncaria tomentosa',
                'kingdom' => 'Plantae',
                'habitat' => 'Selva amazónica del Perú, bosques tropicales húmedos',
                'description' => 'Liana medicinal amazónica con propiedades inmunoestimulantes. Utilizada tradicionalmente por pueblos indígenas.',
                'conservation_status' => 'LC',
                'image_path' => 'images/peru/una_gato_1.svg'
            ]
        ];

        foreach ($species as $speciesData) {
            BiodiversityCategory::create($speciesData);
        }

        $this->command->info('Se han creado ' . count($species) . ' especies peruanas exitosamente.');
    }
}