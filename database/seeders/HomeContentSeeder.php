<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\HomeContent;

class HomeContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $content = [
            // Hero Section
            ['section' => 'hero', 'key' => 'title', 'value' => 'Descubre la Riqueza de la Biodiversidad', 'type' => 'text'],
            ['section' => 'hero', 'key' => 'subtitle', 'value' => 'Explora nuestra extensa base de datos de especies, ecosistemas y publicaciones científicas. México es uno de los países con mayor biodiversidad del mundo, hogar de miles de especies únicas.', 'type' => 'text'],
            ['section' => 'hero', 'key' => 'button_primary_text', 'value' => 'Explorar Especies', 'type' => 'text'],
            ['section' => 'hero', 'key' => 'button_primary_url', 'value' => '/biodiversity', 'type' => 'url'],
            ['section' => 'hero', 'key' => 'button_secondary_text', 'value' => 'Publicaciones', 'type' => 'text'],
            ['section' => 'hero', 'key' => 'button_secondary_url', 'value' => '/publications', 'type' => 'url'],
            ['section' => 'hero', 'key' => 'hero_image', 'value' => 'fas fa-globe-americas', 'type' => 'text'],
            ['section' => 'hero', 'key' => 'use_image_slider', 'value' => '0', 'type' => 'text'],
            ['section' => 'hero', 'key' => 'slider_autoplay', 'value' => '1', 'type' => 'text'],
            ['section' => 'hero', 'key' => 'slider_interval', 'value' => '5000', 'type' => 'text'],
            ['section' => 'hero', 'key' => 'enable_icons', 'value' => '1', 'type' => 'text'],
            
            // Search Section
            ['section' => 'search', 'key' => 'title', 'value' => '¿Qué especie buscas?', 'type' => 'text'],
            ['section' => 'search', 'key' => 'subtitle', 'value' => 'Busca entre miles de especies registradas en nuestro sistema', 'type' => 'text'],
            ['section' => 'search', 'key' => 'placeholder', 'value' => 'Buscar por nombre común o científico...', 'type' => 'text'],
            
            // Statistics Section
            ['section' => 'stats', 'key' => 'title', 'value' => 'Nuestra Biodiversidad en Números', 'type' => 'text'],
            ['section' => 'stats', 'key' => 'categories_title', 'value' => 'Categorías de Especies', 'type' => 'text'],
            ['section' => 'stats', 'key' => 'categories_description', 'value' => 'Diferentes grupos taxonómicos registrados', 'type' => 'text'],
            ['section' => 'stats', 'key' => 'publications_title', 'value' => 'Publicaciones Científicas', 'type' => 'text'],
            ['section' => 'stats', 'key' => 'publications_description', 'value' => 'Investigaciones y estudios disponibles', 'type' => 'text'],
            ['section' => 'stats', 'key' => 'endangered_title', 'value' => 'Especies en Peligro', 'type' => 'text'],
            ['section' => 'stats', 'key' => 'endangered_description', 'value' => 'Requieren protección especial', 'type' => 'text'],
            ['section' => 'stats', 'key' => 'critical_title', 'value' => 'En Peligro Crítico', 'type' => 'text'],
            ['section' => 'stats', 'key' => 'critical_description', 'value' => 'Situación de conservación crítica', 'type' => 'text'],
            
            // Featured Species Section
            ['section' => 'featured', 'key' => 'title', 'value' => 'Especies Destacadas', 'type' => 'text'],
            ['section' => 'featured', 'key' => 'view_all_text', 'value' => 'Ver Todas las Especies', 'type' => 'text'],
            
            // Publications Section
            ['section' => 'publications', 'key' => 'title', 'value' => 'Publicaciones Científicas Recientes', 'type' => 'text'],
            ['section' => 'publications', 'key' => 'view_all_text', 'value' => 'Ver Todas las Publicaciones', 'type' => 'text'],
            
            // Call to Action Section
            ['section' => 'cta', 'key' => 'title', 'value' => 'Contribuye a la Conservación', 'type' => 'text'],
            ['section' => 'cta', 'key' => 'description', 'value' => 'La biodiversidad es un tesoro que debemos proteger. Únete a nuestros esfuerzos de conservación e investigación.', 'type' => 'text'],
            ['section' => 'cta', 'key' => 'button_primary_text', 'value' => 'Colaborar', 'type' => 'text'],
            ['section' => 'cta', 'key' => 'button_primary_url', 'value' => '#', 'type' => 'url'],
            ['section' => 'cta', 'key' => 'button_secondary_text', 'value' => 'Descargar Datos', 'type' => 'text'],
            ['section' => 'cta', 'key' => 'button_secondary_url', 'value' => '#', 'type' => 'url'],
        ];

        foreach ($content as $item) {
            HomeContent::updateOrCreate(
                ['section' => $item['section'], 'key' => $item['key']],
                $item
            );
        }
    }
}
