<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BiodiversityCategory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class BiodiversityImagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🌿 Iniciando seeder de imágenes de biodiversidad...');
        
        // Mapeo de especies con URLs de imágenes reales de alta calidad
        $speciesImages = [
            // Mamíferos
            'Panthera onca' => 'https://upload.wikimedia.org/wikipedia/commons/0/0a/Standing_jaguar.jpg',
            'Tapirus terrestris' => 'https://upload.wikimedia.org/wikipedia/commons/6/6f/Tapir_terrestre.jpg',
            'Puma concolor' => 'https://upload.wikimedia.org/wikipedia/commons/d/d0/Cougar_25.jpg',
            'Odocoileus virginianus' => 'https://upload.wikimedia.org/wikipedia/commons/8/85/White-tailed_deer.jpg',
            'Alouatta palliata' => 'https://upload.wikimedia.org/wikipedia/commons/5/5a/Alouatta_palliata_Mexican_howler_monkey.jpg',
            
            // Aves
            'Ara macao' => 'https://upload.wikimedia.org/wikipedia/commons/c/c4/Ara_macao_-flying-8a.jpg',
            'Quetzalus quetzal' => 'https://upload.wikimedia.org/wikipedia/commons/b/b2/Pharomachrus_mocinno_Monteverde_2009.jpg',
            'Trogon massena' => 'https://upload.wikimedia.org/wikipedia/commons/8/8f/Slaty-tailed_Trogon.jpg',
            'Ramphastos sulfuratus' => 'https://upload.wikimedia.org/wikipedia/commons/f/f6/Keel-billed_toucan.jpg',
            'Harpyhaliaetus solitarius' => 'https://upload.wikimedia.org/wikipedia/commons/3/3f/Solitary_Eagle.jpg',
            
            // Reptiles
            'Boa constrictor' => 'https://upload.wikimedia.org/wikipedia/commons/4/4d/Boa_constrictor_2.jpg',
            'Iguana iguana' => 'https://upload.wikimedia.org/wikipedia/commons/7/7f/Green_Iguana_in_South_Florida.jpg',
            'Crocodylus acutus' => 'https://upload.wikimedia.org/wikipedia/commons/5/5f/American_crocodile.jpg',
            'Chelonia mydas' => 'https://upload.wikimedia.org/wikipedia/commons/7/7f/Green_turtle_swimming_over_coral_reefs_in_Kona.jpg',
            
            // Anfibios
            'Dendrobates auratus' => 'https://upload.wikimedia.org/wikipedia/commons/e/e5/Dendrobates_auratus_1zz.jpg',
            'Agalychnis callidryas' => 'https://upload.wikimedia.org/wikipedia/commons/f/f1/Red_eyed_tree_frog_edit2.jpg',
            
            // Plantas
            'Cecropia peltata' => 'https://upload.wikimedia.org/wikipedia/commons/8/8f/Cecropia_peltata_leaves.jpg',
            'Swietenia macrophylla' => 'https://upload.wikimedia.org/wikipedia/commons/9/9f/Swietenia_macrophylla_trunk.jpg',
            'Mangifera indica' => 'https://upload.wikimedia.org/wikipedia/commons/7/75/Mango_tree.jpg',
            'Ficus benjamina' => 'https://upload.wikimedia.org/wikipedia/commons/a/a5/Ficus_benjamina2.jpg',
            'Bactris gasipaes' => 'https://upload.wikimedia.org/wikipedia/commons/8/8c/Bactris_gasipaes_fruits.jpg',
            
            // Insectos
            'Morpho peleides' => 'https://upload.wikimedia.org/wikipedia/commons/f/f3/Morpho_peleides_2.jpg',
            'Dynastes hercules' => 'https://upload.wikimedia.org/wikipedia/commons/1/1c/Dynastes_hercules_ecuatorianus_MHNT.jpg',
            'Parides photinus' => 'https://upload.wikimedia.org/wikipedia/commons/8/8f/Parides_photinus.jpg',
            
            // Peces
            'Carcharhinus leucas' => 'https://upload.wikimedia.org/wikipedia/commons/6/6c/Bull_shark.jpg',
            'Epinephelus itajara' => 'https://upload.wikimedia.org/wikipedia/commons/a/a4/Goliath_grouper.jpg',
        ];
        
        $imported = 0;
        $errors = 0;
        $skipped = 0;
        
        foreach ($speciesImages as $scientificName => $imageUrl) {
            try {
                // Buscar la especie en la base de datos
                $species = BiodiversityCategory::where('scientific_name', $scientificName)
                    ->orWhere('name', 'LIKE', "%{$scientificName}%")
                    ->first();
                
                if (!$species) {
                    $this->command->warn("⚠️  Especie no encontrada: {$scientificName}");
                    $skipped++;
                    continue;
                }
                
                // Verificar si ya tiene imagen
                if ($species->image_path && Storage::disk('public')->exists($species->image_path)) {
                    $this->command->info("ℹ️  {$species->name} ya tiene imagen, omitiendo...");
                    $skipped++;
                    continue;
                }
                
                // Descargar y guardar la imagen
                if ($this->downloadAndSaveImage($species, $imageUrl)) {
                    $imported++;
                    $this->command->info("✅ Imagen agregada para: {$species->name}");
                } else {
                    $errors++;
                }
                
            } catch (\Exception $e) {
                $this->command->error("❌ Error procesando {$scientificName}: " . $e->getMessage());
                $errors++;
            }
        }
        
        $this->command->info("\n🎉 Seeder completado:");
        $this->command->info("   ✅ {$imported} imágenes importadas");
        $this->command->info("   ⚠️  {$skipped} especies omitidas");
        $this->command->info("   ❌ {$errors} errores");
    }
    
    /**
     * Descargar y guardar imagen desde URL
     */
    private function downloadAndSaveImage($species, $imageUrl)
    {
        try {
            // Descargar imagen con timeout
            $response = Http::timeout(30)->get($imageUrl);
            
            if (!$response->successful()) {
                $this->command->error("❌ Error al descargar imagen: {$imageUrl}");
                return false;
            }
            
            // Verificar que el contenido sea una imagen válida
            $imageInfo = getimagesizefromstring($response->body());
            if (!$imageInfo) {
                $this->command->error("❌ El archivo descargado no es una imagen válida: {$imageUrl}");
                return false;
            }
            
            // Generar nombre único para la imagen
            $extension = $this->getExtensionFromMimeType($imageInfo['mime']) ?: 'jpg';
            $filename = Str::slug($species->scientific_name) . '_' . time() . '.' . $extension;
            $path = 'biodiversity/especies/' . $filename;
            
            // Guardar imagen
            Storage::disk('public')->put($path, $response->body());
            
            // Actualizar registro en base de datos
            $species->update(['image_path' => $path]);
            
            return true;
            
        } catch (\Exception $e) {
            $this->command->error("❌ Error procesando {$species->name}: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Obtener extensión de archivo desde MIME type
     */
    private function getExtensionFromMimeType($mimeType)
    {
        $mimeToExt = [
            'image/jpeg' => 'jpg',
            'image/jpg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'image/webp' => 'webp',
        ];
        
        return $mimeToExt[$mimeType] ?? 'jpg';
    }
}
