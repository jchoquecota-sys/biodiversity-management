<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\BiodiversityCategory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class UpdateGlobalBiodiversityImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'biodiversity:update-global-images 
                            {--source=all : Fuente de imágenes: inaturalist, gbif, wikimedia, all}
                            {--species= : ID o nombre científico de especie específica}
                            {--limit=50 : Límite de especies a procesar}
                            {--quality=medium : Calidad de imagen: low, medium, high}
                            {--dry-run : Mostrar qué se actualizaría sin realizar cambios}
                            {--force : Sobrescribir imágenes existentes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Actualizar fotos reales de especies desde fuentes globales (iNaturalist, GBIF, Wikimedia Commons)';

    /**
     * APIs y configuraciones
     */
    private $apis = [
        'inaturalist' => [
            'base_url' => 'https://api.inaturalist.org/v1',
            'enabled' => true,
            'priority' => 1
        ],
        'gbif' => [
            'base_url' => 'https://api.gbif.org/v1',
            'enabled' => true,
            'priority' => 2
        ],
        'wikimedia' => [
            'base_url' => 'https://commons.wikimedia.org/w/api.php',
            'enabled' => true,
            'priority' => 3
        ]
    ];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $source = $this->option('source');
        $species = $this->option('species');
        $limit = (int) $this->option('limit');
        $quality = $this->option('quality');
        $dryRun = $this->option('dry-run');
        $force = $this->option('force');

        $this->info('🌍 Iniciando actualización de imágenes desde fuentes globales...');
        
        if ($dryRun) {
            $this->warn('⚠️  Modo DRY-RUN activado - No se realizarán cambios reales');
        }

        // Obtener especies a procesar
        $speciesToProcess = $this->getSpeciesToProcess($species, $limit, $force);
        
        if ($speciesToProcess->isEmpty()) {
            $this->info('ℹ️  No hay especies para procesar.');
            return 0;
        }

        $this->info("📊 Procesando {$speciesToProcess->count()} especies...");
        
        $progressBar = $this->output->createProgressBar($speciesToProcess->count());
        $progressBar->start();

        $stats = [
            'processed' => 0,
            'updated' => 0,
            'errors' => 0,
            'skipped' => 0
        ];

        foreach ($speciesToProcess as $speciesRecord) {
            $result = $this->processSpecies($speciesRecord, $source, $quality, $dryRun, $force);
            $stats[$result]++;
            $stats['processed']++;
            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine(2);

        // Mostrar estadísticas finales
        $this->displayStats($stats);

        return 0;
    }

    /**
     * Obtener especies a procesar
     */
    private function getSpeciesToProcess($species, $limit, $force)
    {
        $query = BiodiversityCategory::query();

        if ($species) {
            if (is_numeric($species)) {
                $query->where('id', $species);
            } else {
                $query->where('scientific_name', 'LIKE', "%{$species}%")
                      ->orWhere('name', 'LIKE', "%{$species}%");
            }
        } else {
            // Si no se fuerza, solo procesar especies sin imágenes o con pocas imágenes
            if (!$force) {
                $query->where(function($q) {
                    $q->whereNull('image_path')
                      ->orWhere(function($subQ) {
                          $subQ->whereNull('image_path_2')
                               ->whereNull('image_path_3')
                               ->whereNull('image_path_4');
                      });
                });
            }
        }

        return $query->limit($limit)->get();
    }

    /**
     * Procesar una especie individual
     */
    private function processSpecies($species, $source, $quality, $dryRun, $force)
    {
        try {
            $this->newLine();
            $this->info("🔍 Procesando: {$species->name} ({$species->scientific_name})");

            // Determinar qué APIs usar
            $apisToUse = $this->getApisToUse($source);
            
            $imagesFound = [];
            
            foreach ($apisToUse as $apiName) {
                $images = $this->searchImagesInApi($apiName, $species, $quality);
                if (!empty($images)) {
                    $imagesFound = array_merge($imagesFound, $images);
                    if (count($imagesFound) >= 4) break; // Máximo 4 imágenes
                }
            }

            if (empty($imagesFound)) {
                $this->warn("⚠️  No se encontraron imágenes para {$species->name}");
                return 'skipped';
            }

            // Descargar y guardar imágenes
            $savedCount = 0;
            foreach (array_slice($imagesFound, 0, 4) as $index => $imageData) {
                if ($this->downloadAndSaveGlobalImage($species, $imageData, $index + 1, $dryRun, $force)) {
                    $savedCount++;
                }
            }

            if ($savedCount > 0) {
                $this->info("✅ {$savedCount} imagen(es) actualizada(s) para {$species->name}");
                return 'updated';
            } else {
                return 'errors';
            }

        } catch (\Exception $e) {
            $this->error("❌ Error procesando {$species->name}: " . $e->getMessage());
            Log::error("Error updating images for species {$species->id}", [
                'species' => $species->scientific_name,
                'error' => $e->getMessage()
            ]);
            return 'errors';
        }
    }

    /**
     * Determinar qué APIs usar según la fuente especificada
     */
    private function getApisToUse($source)
    {
        if ($source === 'all') {
            return array_keys(array_filter($this->apis, fn($api) => $api['enabled']));
        }
        
        if (isset($this->apis[$source]) && $this->apis[$source]['enabled']) {
            return [$source];
        }
        
        return ['inaturalist']; // Fallback
    }

    /**
     * Buscar imágenes en una API específica
     */
    private function searchImagesInApi($apiName, $species, $quality)
    {
        switch ($apiName) {
            case 'inaturalist':
                return $this->searchInaturalistImages($species, $quality);
            case 'gbif':
                return $this->searchGbifImages($species, $quality);
            case 'wikimedia':
                return $this->searchWikimediaImages($species, $quality);
            default:
                return [];
        }
    }

    /**
     * Buscar imágenes en iNaturalist
     */
    private function searchInaturalistImages($species, $quality)
    {
        try {
            $response = Http::timeout(30)
                ->get($this->apis['inaturalist']['base_url'] . '/taxa', [
                    'q' => $species->scientific_name,
                    'rank' => 'species',
                    'per_page' => 5
                ]);

            if (!$response->successful()) {
                return [];
            }

            $data = $response->json();
            $images = [];

            if (isset($data['results']) && !empty($data['results'])) {
                foreach ($data['results'] as $taxon) {
                    if (isset($taxon['default_photo'])) {
                        $photo = $taxon['default_photo'];
                        $imageUrl = $this->getInaturalistImageUrl($photo, $quality);
                        if ($imageUrl) {
                            $images[] = [
                                'url' => $imageUrl,
                                'source' => 'iNaturalist',
                                'license' => $photo['license_code'] ?? 'unknown',
                                'attribution' => $photo['attribution'] ?? 'iNaturalist'
                            ];
                        }
                    }
                    
                    // Obtener fotos adicionales
                    if (isset($taxon['taxon_photos'])) {
                        foreach (array_slice($taxon['taxon_photos'], 0, 3) as $taxonPhoto) {
                            if (isset($taxonPhoto['photo'])) {
                                $photo = $taxonPhoto['photo'];
                                $imageUrl = $this->getInaturalistImageUrl($photo, $quality);
                                if ($imageUrl) {
                                    $images[] = [
                                        'url' => $imageUrl,
                                        'source' => 'iNaturalist',
                                        'license' => $photo['license_code'] ?? 'unknown',
                                        'attribution' => $photo['attribution'] ?? 'iNaturalist'
                                    ];
                                }
                            }
                        }
                    }
                }
            }

            return $images;

        } catch (\Exception $e) {
            $this->warn("⚠️  Error buscando en iNaturalist: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtener URL de imagen de iNaturalist según calidad
     */
    private function getInaturalistImageUrl($photo, $quality)
    {
        $sizeMap = [
            'low' => 'small',
            'medium' => 'medium',
            'high' => 'large'
        ];
        
        $size = $sizeMap[$quality] ?? 'medium';
        return $photo['url'] ?? null;
    }

    /**
     * Buscar imágenes en GBIF
     */
    private function searchGbifImages($species, $quality)
    {
        try {
            // Primero buscar el taxón
            $response = Http::timeout(30)
                ->get($this->apis['gbif']['base_url'] . '/species/search', [
                    'q' => $species->scientific_name,
                    'limit' => 5
                ]);

            if (!$response->successful()) {
                return [];
            }

            $data = $response->json();
            $images = [];

            if (isset($data['results']) && !empty($data['results'])) {
                foreach ($data['results'] as $taxon) {
                    if (isset($taxon['key'])) {
                        // Buscar imágenes para este taxón
                        $mediaResponse = Http::timeout(30)
                            ->get($this->apis['gbif']['base_url'] . '/species/' . $taxon['key'] . '/media');
                        
                        if ($mediaResponse->successful()) {
                            $mediaData = $mediaResponse->json();
                            if (isset($mediaData['results'])) {
                                foreach (array_slice($mediaData['results'], 0, 3) as $media) {
                                    if ($media['type'] === 'StillImage' && isset($media['identifier'])) {
                                        $images[] = [
                                            'url' => $media['identifier'],
                                            'source' => 'GBIF',
                                            'license' => $media['license'] ?? 'unknown',
                                            'attribution' => $media['rightsHolder'] ?? 'GBIF'
                                        ];
                                    }
                                }
                            }
                        }
                    }
                }
            }

            return $images;

        } catch (\Exception $e) {
            $this->warn("⚠️  Error buscando en GBIF: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Buscar imágenes en Wikimedia Commons
     */
    private function searchWikimediaImages($species, $quality)
    {
        try {
            $searchTerms = [
                $species->scientific_name,
                $species->name,
                $species->common_name
            ];

            $images = [];

            foreach (array_filter($searchTerms) as $term) {
                $response = Http::timeout(30)
                    ->get($this->apis['wikimedia']['base_url'], [
                        'action' => 'query',
                        'format' => 'json',
                        'list' => 'search',
                        'srsearch' => $term,
                        'srnamespace' => 6, // File namespace
                        'srlimit' => 5
                    ]);

                if ($response->successful()) {
                    $data = $response->json();
                    if (isset($data['query']['search'])) {
                        foreach ($data['query']['search'] as $result) {
                            $filename = $result['title'];
                            $imageUrl = $this->getWikimediaImageUrl($filename, $quality);
                            if ($imageUrl) {
                                $images[] = [
                                    'url' => $imageUrl,
                                    'source' => 'Wikimedia Commons',
                                    'license' => 'CC',
                                    'attribution' => 'Wikimedia Commons'
                                ];
                            }
                        }
                    }
                }
                
                if (count($images) >= 3) break;
            }

            return $images;

        } catch (\Exception $e) {
            $this->warn("⚠️  Error buscando en Wikimedia: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtener URL de imagen de Wikimedia Commons
     */
    private function getWikimediaImageUrl($filename, $quality)
    {
        $sizeMap = [
            'low' => '300px',
            'medium' => '800px',
            'high' => '1200px'
        ];
        
        $size = $sizeMap[$quality] ?? '800px';
        $filename = str_replace('File:', '', $filename);
        
        return "https://commons.wikimedia.org/wiki/Special:FilePath/{$filename}?width={$size}";
    }

    /**
     * Descargar y guardar imagen desde fuente global
     */
    private function downloadAndSaveGlobalImage($species, $imageData, $imageIndex, $dryRun = false, $force = false)
    {
        try {
            if ($dryRun) {
                $this->line("[DRY-RUN] Descargaría imagen de {$imageData['source']} para {$species->name}");
                return true;
            }

            // Verificar si ya existe imagen y no se fuerza la actualización
            $fieldName = $imageIndex === 1 ? 'image_path' : "image_path_{$imageIndex}";
            if (!$force && $species->{$fieldName}) {
                $this->line("⏭️  Saltando {$fieldName} - ya existe imagen");
                return false;
            }

            // Descargar imagen
            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0 (compatible; BiodiversityBot/1.0)'
            ])->timeout(30)->get($imageData['url']);

            if (!$response->successful()) {
                $this->warn("⚠️  Error descargando imagen: HTTP {$response->status()}");
                return false;
            }

            // Validar que es una imagen
            $contentType = $response->header('Content-Type');
            if (!str_starts_with($contentType, 'image/')) {
                $this->warn("⚠️  El contenido no es una imagen válida: {$contentType}");
                return false;
            }

            // Generar nombre de archivo
            $extension = $this->getExtensionFromContentType($contentType);
            $baseFilename = Str::slug($species->scientific_name);
            $filename = "{$baseFilename}_{$imageIndex}_" . time() . ".{$extension}";
            $path = "biodiversity/especies/global/{$filename}";

            // Guardar imagen
            Storage::disk('public')->put($path, $response->body());

            // Eliminar imagen anterior si existe
            if ($species->{$fieldName} && Storage::disk('public')->exists($species->{$fieldName})) {
                Storage::disk('public')->delete($species->{$fieldName});
            }

            // Actualizar base de datos
            $species->update([$fieldName => $path]);

            // Log de metadatos
            Log::info("Image updated for species {$species->id}", [
                'species' => $species->scientific_name,
                'source' => $imageData['source'],
                'license' => $imageData['license'],
                'field' => $fieldName,
                'path' => $path
            ]);

            return true;

        } catch (\Exception $e) {
            $this->error("❌ Error guardando imagen: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtener extensión desde Content-Type
     */
    private function getExtensionFromContentType($contentType)
    {
        $map = [
            'image/jpeg' => 'jpg',
            'image/jpg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'image/webp' => 'webp'
        ];
        
        return $map[$contentType] ?? 'jpg';
    }

    /**
     * Mostrar estadísticas finales
     */
    private function displayStats($stats)
    {
        $this->info('📊 Estadísticas de actualización:');
        $this->table(
            ['Métrica', 'Cantidad'],
            [
                ['Especies procesadas', $stats['processed']],
                ['Imágenes actualizadas', $stats['updated']],
                ['Especies omitidas', $stats['skipped']],
                ['Errores', $stats['errors']]
            ]
        );

        if ($stats['updated'] > 0) {
            $this->info('🎉 ¡Actualización completada exitosamente!');
        }
    }
}