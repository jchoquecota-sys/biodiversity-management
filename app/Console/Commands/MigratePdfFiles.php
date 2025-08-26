<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use App\Models\Publication;
use Exception;

class MigratePdfFiles extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'biodiversity:migrate-pdf-files 
                            {--dry-run : Ejecutar en modo de prueba sin descargar archivos}
                            {--limit=0 : Limitar número de archivos a procesar}
                            {--force : Forzar descarga incluso si el archivo ya existe}
                            {--base-url= : URL base del servidor donde están los PDFs}';

    /**
     * The console command description.
     */
    protected $description = 'Migrar archivos PDF de estudio_generals al proyecto actual';

    private $baseUrl;
    private $downloadedCount = 0;
    private $skippedCount = 0;
    private $errorCount = 0;

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Iniciando migración de archivos PDF...');
        
        // Configurar URL base
        $this->baseUrl = $this->option('base-url') ?? 'https://biodiversidad.regiontacna.gob.pe/';
        $this->info("📡 URL base configurada: {$this->baseUrl}");
        
        // Probar conexión a base de datos externa
        if (!$this->testExternalConnection()) {
            $this->error('❌ No se pudo conectar a la base de datos externa.');
            return 1;
        }
        
        // Crear directorio de destino si no existe
        $this->createStorageDirectories();
        
        // Obtener publicaciones con rutas PDF
        $publications = $this->getPublicationsWithPdfs();
        
        if ($publications->isEmpty()) {
            $this->info('ℹ️  No se encontraron publicaciones con archivos PDF.');
            return 0;
        }
        
        $this->info("📊 Se encontraron {$publications->count()} publicaciones con PDFs.");
        
        // Procesar archivos
        $this->processFiles($publications);
        
        // Mostrar resumen
        $this->showSummary();
        
        return 0;
    }
    
    /**
     * Probar conexión a base de datos externa
     */
    private function testExternalConnection()
    {
        try {
            DB::connection('external_bioserver')->getPdo();
            $this->info('✅ Conexión a base de datos externa exitosa.');
            return true;
        } catch (Exception $e) {
            $this->error("❌ Error de conexión: {$e->getMessage()}");
            return false;
        }
    }
    
    /**
     * Crear directorios de almacenamiento
     */
    private function createStorageDirectories()
    {
        $directories = [
            'public/publications',
            'public/publications/estudios'
        ];
        
        foreach ($directories as $dir) {
            if (!Storage::exists($dir)) {
                Storage::makeDirectory($dir);
                $this->info("📁 Directorio creado: {$dir}");
            }
        }
    }
    
    /**
     * Obtener publicaciones con archivos PDF
     */
    private function getPublicationsWithPdfs()
    {
        $query = Publication::whereNotNull('pdf_path')
                           ->where('pdf_path', '!=', '')
                           ->where('pdf_path', 'like', 'estudios/%');
        
        if ($this->option('limit') > 0) {
            $query->limit($this->option('limit'));
        }
        
        return $query->get();
    }
    
    /**
     * Procesar archivos PDF
     */
    private function processFiles($publications)
    {
        $progressBar = $this->output->createProgressBar($publications->count());
        $progressBar->start();
        
        foreach ($publications as $publication) {
            $this->processFile($publication);
            $progressBar->advance();
        }
        
        $progressBar->finish();
        $this->newLine();
    }
    
    /**
     * Procesar un archivo individual
     */
    private function processFile($publication)
    {
        $originalPath = $publication->pdf_path;
        $fileName = basename($originalPath);
        $localPath = "public/publications/estudios/{$fileName}";
        
        // Verificar si el archivo ya existe
        if (!$this->option('force') && Storage::exists($localPath)) {
            $this->skippedCount++;
            if (!$this->option('dry-run')) {
                $this->line("⏭️  Archivo ya existe: {$fileName}");
            }
            return;
        }
        
        if ($this->option('dry-run')) {
            $this->line("🔍 [DRY-RUN] Descargaría: {$this->baseUrl}{$originalPath} -> {$localPath}");
            return;
        }
        
        try {
            // Construir URL completa
            $fullUrl = $this->baseUrl . $originalPath;
            
            // Descargar archivo
            $response = Http::timeout(30)->get($fullUrl);
            
            if ($response->successful()) {
                // Guardar archivo
                Storage::put($localPath, $response->body());
                
                // Actualizar ruta en la base de datos
                $publication->update([
                    'pdf_path' => "publications/estudios/{$fileName}"
                ]);
                
                $this->downloadedCount++;
                $this->line("✅ Descargado: {$fileName} ({$this->formatBytes($response->header('Content-Length') ?? strlen($response->body()))})");
            } else {
                $this->errorCount++;
                $this->error("❌ Error HTTP {$response->status()}: {$fileName}");
            }
            
        } catch (Exception $e) {
            $this->errorCount++;
            $this->error("❌ Error descargando {$fileName}: {$e->getMessage()}");
        }
    }
    
    /**
     * Mostrar resumen de la migración
     */
    private function showSummary()
    {
        $this->newLine();
        $this->info('📋 Resumen de migración:');
        $this->line("✅ Archivos descargados: {$this->downloadedCount}");
        $this->line("⏭️  Archivos omitidos: {$this->skippedCount}");
        $this->line("❌ Errores: {$this->errorCount}");
        
        if ($this->option('dry-run')) {
            $this->warn('⚠️  Modo dry-run: No se descargaron archivos reales.');
        }
    }
    
    /**
     * Formatear bytes en formato legible
     */
    private function formatBytes($bytes)
    {
        if ($bytes == 0) return '0 B';
        
        $units = ['B', 'KB', 'MB', 'GB'];
        $factor = floor(log($bytes, 1024));
        
        return sprintf('%.1f %s', $bytes / pow(1024, $factor), $units[$factor]);
    }
}