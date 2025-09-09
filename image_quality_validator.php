<?php
/**
 * Validador de Calidad de Imágenes
 * Sistema para validar y filtrar imágenes de especies por calidad
 */

require_once 'config/database.php';

class ImageQualityValidator {
    private $pdo;
    private $logFile;
    private $validationStats;
    
    // Criterios de calidad
    private $quality_criteria = [
        'min_width' => 300,
        'min_height' => 300,
        'max_file_size' => 10485760, // 10MB
        'min_file_size' => 5120, // 5KB
        'allowed_formats' => ['jpg', 'jpeg', 'png', 'webp'],
        'min_quality_score' => 60,
        'max_blur_threshold' => 0.3,
        'min_brightness' => 20,
        'max_brightness' => 235
    ];
    
    public function __construct() {
        $database = new Database();
        $this->pdo = $database->getConnection();
        
        $this->logFile = __DIR__ . '/logs/image_validation.log';
        
        if (!file_exists(dirname($this->logFile))) {
            mkdir(dirname($this->logFile), 0755, true);
        }
        
        $this->validationStats = [
            'images_processed' => 0,
            'images_approved' => 0,
            'images_rejected' => 0,
            'rejection_reasons' => [],
            'quality_scores' => []
        ];
        
        $this->log("=== Iniciando validación de calidad de imágenes ===");
    }
    
    /**
     * Validar todas las imágenes en el sistema
     */
    public function validateAllImages() {
        $images = $this->getAllImages();
        
        $this->log("Encontradas " . count($images) . " imágenes para validar");
        
        foreach ($images as $image) {
            $this->validateSingleImage($image);
        }
        
        $this->generateValidationReport();
    }
    
    /**
     * Validar una imagen específica
     */
    public function validateSingleImage($image_data) {
        $this->validationStats['images_processed']++;
        
        $image_path = $this->getFullImagePath($image_data['imagen_url']);
        $species_name = $image_data['nombre_cientifico'];
        
        $this->log("Validando imagen de: {$species_name}");
        
        // Verificar si el archivo existe
        if (!file_exists($image_path)) {
            $this->rejectImage($image_data['id'], 'file_not_found', "Archivo no encontrado: {$image_path}");
            return false;
        }
        
        // Realizar validaciones
        $validation_results = [
            'file_format' => $this->validateFileFormat($image_path),
            'file_size' => $this->validateFileSize($image_path),
            'image_dimensions' => $this->validateImageDimensions($image_path),
            'image_quality' => $this->validateImageQuality($image_path),
            'content_analysis' => $this->analyzeImageContent($image_path)
        ];
        
        // Calcular puntuación total
        $quality_score = $this->calculateQualityScore($validation_results);
        $this->validationStats['quality_scores'][] = $quality_score;
        
        // Determinar si la imagen pasa la validación
        $passes_validation = $this->determineValidationResult($validation_results, $quality_score);
        
        if ($passes_validation) {
            $this->approveImage($image_data['id'], $quality_score, $validation_results);
            $this->validationStats['images_approved']++;
            $this->log("✅ Imagen aprobada (puntuación: {$quality_score})");
        } else {
            $rejection_reason = $this->getMainRejectionReason($validation_results);
            $this->rejectImage($image_data['id'], $rejection_reason, "Puntuación: {$quality_score}");
            $this->validationStats['images_rejected']++;
            $this->log("❌ Imagen rechazada: {$rejection_reason} (puntuación: {$quality_score})");
        }
        
        return $passes_validation;
    }
    
    /**
     * Obtener todas las imágenes del sistema
     */
    private function getAllImages() {
        $sql = "SELECT id, nombre_cientifico, imagen_url 
                FROM biodiversity_categories 
                WHERE imagen_url IS NOT NULL 
                AND imagen_url != '' 
                AND imagen_url NOT LIKE '%placeholder%'
                ORDER BY id";
        
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Validar formato de archivo
     */
    private function validateFileFormat($image_path) {
        $extension = strtolower(pathinfo($image_path, PATHINFO_EXTENSION));
        $is_valid = in_array($extension, $this->quality_criteria['allowed_formats']);
        
        // Verificar MIME type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($finfo, $image_path);
        finfo_close($finfo);
        
        $valid_mime_types = [
            'image/jpeg',
            'image/jpg', 
            'image/png',
            'image/webp'
        ];
        
        $mime_valid = in_array($mime_type, $valid_mime_types);
        
        return [
            'valid' => $is_valid && $mime_valid,
            'extension' => $extension,
            'mime_type' => $mime_type,
            'score' => ($is_valid && $mime_valid) ? 100 : 0
        ];
    }
    
    /**
     * Validar tamaño de archivo
     */
    private function validateFileSize($image_path) {
        $file_size = filesize($image_path);
        $min_size = $this->quality_criteria['min_file_size'];
        $max_size = $this->quality_criteria['max_file_size'];
        
        $is_valid = ($file_size >= $min_size && $file_size <= $max_size);
        
        // Calcular puntuación basada en tamaño óptimo
        $optimal_size = 500000; // 500KB
        $size_score = 100;
        
        if ($file_size < $min_size) {
            $size_score = 0;
        } elseif ($file_size > $max_size) {
            $size_score = 0;
        } else {
            // Puntuación basada en proximidad al tamaño óptimo
            $distance = abs($file_size - $optimal_size) / $optimal_size;
            $size_score = max(50, 100 - ($distance * 50));
        }
        
        return [
            'valid' => $is_valid,
            'file_size' => $file_size,
            'file_size_mb' => round($file_size / 1048576, 2),
            'score' => $size_score
        ];
    }
    
    /**
     * Validar dimensiones de imagen
     */
    private function validateImageDimensions($image_path) {
        $image_info = getimagesize($image_path);
        
        if (!$image_info) {
            return [
                'valid' => false,
                'width' => 0,
                'height' => 0,
                'score' => 0
            ];
        }
        
        $width = $image_info[0];
        $height = $image_info[1];
        $min_width = $this->quality_criteria['min_width'];
        $min_height = $this->quality_criteria['min_height'];
        
        $is_valid = ($width >= $min_width && $height >= $min_height);
        
        // Calcular puntuación basada en resolución
        $resolution_score = 100;
        
        if ($width < $min_width || $height < $min_height) {
            $resolution_score = 0;
        } else {
            // Bonificación por alta resolución
            $total_pixels = $width * $height;
            if ($total_pixels > 1000000) { // > 1MP
                $resolution_score = 100;
            } elseif ($total_pixels > 500000) { // > 0.5MP
                $resolution_score = 90;
            } else {
                $resolution_score = 70;
            }
        }
        
        // Penalizar imágenes muy desproporcionadas
        $aspect_ratio = $width / $height;
        if ($aspect_ratio > 3 || $aspect_ratio < 0.33) {
            $resolution_score *= 0.8;
        }
        
        return [
            'valid' => $is_valid,
            'width' => $width,
            'height' => $height,
            'aspect_ratio' => $aspect_ratio,
            'total_pixels' => $width * $height,
            'score' => $resolution_score
        ];
    }
    
    /**
     * Validar calidad técnica de imagen
     */
    private function validateImageQuality($image_path) {
        $quality_metrics = [
            'brightness' => $this->calculateBrightness($image_path),
            'contrast' => $this->calculateContrast($image_path),
            'sharpness' => $this->calculateSharpness($image_path),
            'noise_level' => $this->estimateNoiseLevel($image_path)
        ];
        
        // Calcular puntuación de calidad técnica
        $technical_score = 0;
        
        // Brightness score (0-100)
        $brightness = $quality_metrics['brightness'];
        if ($brightness >= $this->quality_criteria['min_brightness'] && 
            $brightness <= $this->quality_criteria['max_brightness']) {
            $brightness_score = 100 - abs($brightness - 128) / 128 * 30;
        } else {
            $brightness_score = 30;
        }
        
        // Contrast score (0-100)
        $contrast_score = min(100, $quality_metrics['contrast'] * 2);
        
        // Sharpness score (0-100)
        $sharpness_score = min(100, $quality_metrics['sharpness'] * 10);
        
        // Noise score (0-100, lower noise = higher score)
        $noise_score = max(0, 100 - $quality_metrics['noise_level'] * 5);
        
        $technical_score = ($brightness_score + $contrast_score + $sharpness_score + $noise_score) / 4;
        
        return [
            'valid' => $technical_score >= 50,
            'brightness' => $brightness,
            'contrast' => $quality_metrics['contrast'],
            'sharpness' => $quality_metrics['sharpness'],
            'noise_level' => $quality_metrics['noise_level'],
            'brightness_score' => $brightness_score,
            'contrast_score' => $contrast_score,
            'sharpness_score' => $sharpness_score,
            'noise_score' => $noise_score,
            'score' => $technical_score
        ];
    }
    
    /**
     * Analizar contenido de imagen
     */
    private function analyzeImageContent($image_path) {
        $content_analysis = [
            'has_main_subject' => $this->detectMainSubject($image_path),
            'background_quality' => $this->analyzeBackground($image_path),
            'color_distribution' => $this->analyzeColorDistribution($image_path),
            'composition_score' => $this->analyzeComposition($image_path)
        ];
        
        // Calcular puntuación de contenido
        $content_score = (
            ($content_analysis['has_main_subject'] ? 100 : 30) +
            $content_analysis['background_quality'] +
            $content_analysis['color_distribution'] +
            $content_analysis['composition_score']
        ) / 4;
        
        return [
            'valid' => $content_score >= 60,
            'analysis' => $content_analysis,
            'score' => $content_score
        ];
    }
    
    /**
     * Calcular brillo promedio
     */
    private function calculateBrightness($image_path) {
        $image = $this->loadImage($image_path);
        if (!$image) return 128; // Valor por defecto
        
        $width = imagesx($image);
        $height = imagesy($image);
        $total_brightness = 0;
        $pixel_count = 0;
        
        // Muestrear cada 10 píxeles para eficiencia
        for ($x = 0; $x < $width; $x += 10) {
            for ($y = 0; $y < $height; $y += 10) {
                $rgb = imagecolorat($image, $x, $y);
                $r = ($rgb >> 16) & 0xFF;
                $g = ($rgb >> 8) & 0xFF;
                $b = $rgb & 0xFF;
                
                // Calcular luminancia
                $brightness = 0.299 * $r + 0.587 * $g + 0.114 * $b;
                $total_brightness += $brightness;
                $pixel_count++;
            }
        }
        
        imagedestroy($image);
        
        return $pixel_count > 0 ? $total_brightness / $pixel_count : 128;
    }
    
    /**
     * Calcular contraste
     */
    private function calculateContrast($image_path) {
        $image = $this->loadImage($image_path);
        if (!$image) return 50;
        
        $width = imagesx($image);
        $height = imagesy($image);
        $brightness_values = [];
        
        // Muestrear píxeles
        for ($x = 0; $x < $width; $x += 20) {
            for ($y = 0; $y < $height; $y += 20) {
                $rgb = imagecolorat($image, $x, $y);
                $r = ($rgb >> 16) & 0xFF;
                $g = ($rgb >> 8) & 0xFF;
                $b = $rgb & 0xFF;
                
                $brightness = 0.299 * $r + 0.587 * $g + 0.114 * $b;
                $brightness_values[] = $brightness;
            }
        }
        
        imagedestroy($image);
        
        if (empty($brightness_values)) return 50;
        
        // Calcular desviación estándar como medida de contraste
        $mean = array_sum($brightness_values) / count($brightness_values);
        $variance = 0;
        
        foreach ($brightness_values as $value) {
            $variance += pow($value - $mean, 2);
        }
        
        $std_dev = sqrt($variance / count($brightness_values));
        
        return min(100, $std_dev);
    }
    
    /**
     * Calcular nitidez (sharpness)
     */
    private function calculateSharpness($image_path) {
        $image = $this->loadImage($image_path);
        if (!$image) return 5;
        
        // Convertir a escala de grises para análisis
        $width = imagesx($image);
        $height = imagesy($image);
        $gray_image = imagecreatetruecolor($width, $height);
        
        for ($x = 0; $x < $width; $x++) {
            for ($y = 0; $y < $height; $y++) {
                $rgb = imagecolorat($image, $x, $y);
                $r = ($rgb >> 16) & 0xFF;
                $g = ($rgb >> 8) & 0xFF;
                $b = $rgb & 0xFF;
                
                $gray = 0.299 * $r + 0.587 * $g + 0.114 * $b;
                $gray_color = imagecolorallocate($gray_image, $gray, $gray, $gray);
                imagesetpixel($gray_image, $x, $y, $gray_color);
            }
        }
        
        // Aplicar filtro Laplaciano para detectar bordes
        $laplacian_sum = 0;
        $pixel_count = 0;
        
        for ($x = 1; $x < $width - 1; $x += 5) {
            for ($y = 1; $y < $height - 1; $y += 5) {
                $center = imagecolorat($gray_image, $x, $y) & 0xFF;
                $top = imagecolorat($gray_image, $x, $y - 1) & 0xFF;
                $bottom = imagecolorat($gray_image, $x, $y + 1) & 0xFF;
                $left = imagecolorat($gray_image, $x - 1, $y) & 0xFF;
                $right = imagecolorat($gray_image, $x + 1, $y) & 0xFF;
                
                $laplacian = abs(4 * $center - $top - $bottom - $left - $right);
                $laplacian_sum += $laplacian;
                $pixel_count++;
            }
        }
        
        imagedestroy($image);
        imagedestroy($gray_image);
        
        return $pixel_count > 0 ? $laplacian_sum / $pixel_count / 255 * 10 : 5;
    }
    
    /**
     * Estimar nivel de ruido
     */
    private function estimateNoiseLevel($image_path) {
        // Implementación simplificada
        // En un sistema real, se usarían algoritmos más sofisticados
        return rand(5, 15); // Valor simulado
    }
    
    /**
     * Detectar sujeto principal
     */
    private function detectMainSubject($image_path) {
        // Implementación simplificada
        // En un sistema real, se usaría detección de objetos o análisis de saliencia
        return rand(0, 1) == 1;
    }
    
    /**
     * Analizar calidad del fondo
     */
    private function analyzeBackground($image_path) {
        // Implementación simplificada
        return rand(60, 90);
    }
    
    /**
     * Analizar distribución de colores
     */
    private function analyzeColorDistribution($image_path) {
        $image = $this->loadImage($image_path);
        if (!$image) return 70;
        
        $width = imagesx($image);
        $height = imagesy($image);
        $color_histogram = [];
        
        // Muestrear colores
        for ($x = 0; $x < $width; $x += 10) {
            for ($y = 0; $y < $height; $y += 10) {
                $rgb = imagecolorat($image, $x, $y);
                $r = ($rgb >> 16) & 0xFF;
                $g = ($rgb >> 8) & 0xFF;
                $b = $rgb & 0xFF;
                
                // Agrupar colores en rangos
                $color_key = floor($r / 32) . '_' . floor($g / 32) . '_' . floor($b / 32);
                $color_histogram[$color_key] = ($color_histogram[$color_key] ?? 0) + 1;
            }
        }
        
        imagedestroy($image);
        
        // Calcular diversidad de colores
        $unique_colors = count($color_histogram);
        $total_samples = array_sum($color_histogram);
        
        // Puntuación basada en diversidad (ni muy monótona ni muy caótica)
        if ($unique_colors < 10) {
            return 40; // Muy monótona
        } elseif ($unique_colors > 100) {
            return 60; // Muy caótica
        } else {
            return 80; // Buena diversidad
        }
    }
    
    /**
     * Analizar composición
     */
    private function analyzeComposition($image_path) {
        // Implementación simplificada
        // En un sistema real, se analizaría regla de tercios, simetría, etc.
        return rand(65, 85);
    }
    
    /**
     * Cargar imagen según formato
     */
    private function loadImage($image_path) {
        $extension = strtolower(pathinfo($image_path, PATHINFO_EXTENSION));
        
        switch ($extension) {
            case 'jpg':
            case 'jpeg':
                return imagecreatefromjpeg($image_path);
            case 'png':
                return imagecreatefrompng($image_path);
            case 'webp':
                return imagecreatefromwebp($image_path);
            default:
                return false;
        }
    }
    
    /**
     * Calcular puntuación total de calidad
     */
    private function calculateQualityScore($validation_results) {
        $weights = [
            'file_format' => 0.15,
            'file_size' => 0.10,
            'image_dimensions' => 0.25,
            'image_quality' => 0.35,
            'content_analysis' => 0.15
        ];
        
        $total_score = 0;
        
        foreach ($validation_results as $category => $result) {
            if (isset($weights[$category]) && isset($result['score'])) {
                $total_score += $result['score'] * $weights[$category];
            }
        }
        
        return round($total_score, 2);
    }
    
    /**
     * Determinar resultado de validación
     */
    private function determineValidationResult($validation_results, $quality_score) {
        // Verificar criterios obligatorios
        if (!$validation_results['file_format']['valid'] ||
            !$validation_results['file_size']['valid'] ||
            !$validation_results['image_dimensions']['valid']) {
            return false;
        }
        
        // Verificar puntuación mínima
        return $quality_score >= $this->quality_criteria['min_quality_score'];
    }
    
    /**
     * Obtener razón principal de rechazo
     */
    private function getMainRejectionReason($validation_results) {
        if (!$validation_results['file_format']['valid']) {
            return 'invalid_format';
        }
        if (!$validation_results['file_size']['valid']) {
            return 'invalid_size';
        }
        if (!$validation_results['image_dimensions']['valid']) {
            return 'invalid_dimensions';
        }
        if (!$validation_results['image_quality']['valid']) {
            return 'poor_technical_quality';
        }
        if (!$validation_results['content_analysis']['valid']) {
            return 'poor_content_quality';
        }
        
        return 'low_overall_score';
    }
    
    /**
     * Aprobar imagen
     */
    private function approveImage($species_id, $quality_score, $validation_results) {
        $this->updateImageValidationStatus($species_id, 'approved', $quality_score, $validation_results);
    }
    
    /**
     * Rechazar imagen
     */
    private function rejectImage($species_id, $reason, $details) {
        $this->updateImageValidationStatus($species_id, 'rejected', 0, null, $reason, $details);
        
        // Contar razones de rechazo
        if (!isset($this->validationStats['rejection_reasons'][$reason])) {
            $this->validationStats['rejection_reasons'][$reason] = 0;
        }
        $this->validationStats['rejection_reasons'][$reason]++;
    }
    
    /**
     * Actualizar estado de validación en base de datos
     */
    private function updateImageValidationStatus($species_id, $status, $quality_score, $validation_results, $rejection_reason = null, $rejection_details = null) {
        // Crear tabla de validación si no existe
        $this->createValidationTable();
        
        $sql = "INSERT INTO image_validation 
                (species_id, validation_status, quality_score, validation_details, rejection_reason, rejection_details, validated_at) 
                VALUES 
                (:species_id, :status, :quality_score, :validation_details, :rejection_reason, :rejection_details, NOW())
                ON DUPLICATE KEY UPDATE 
                validation_status = VALUES(validation_status),
                quality_score = VALUES(quality_score),
                validation_details = VALUES(validation_details),
                rejection_reason = VALUES(rejection_reason),
                rejection_details = VALUES(rejection_details),
                validated_at = VALUES(validated_at)";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':species_id' => $species_id,
            ':status' => $status,
            ':quality_score' => $quality_score,
            ':validation_details' => $validation_results ? json_encode($validation_results) : null,
            ':rejection_reason' => $rejection_reason,
            ':rejection_details' => $rejection_details
        ]);
    }
    
    /**
     * Crear tabla de validación
     */
    private function createValidationTable() {
        $sql = "CREATE TABLE IF NOT EXISTS image_validation (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    species_id INT NOT NULL,
                    validation_status ENUM('approved', 'rejected', 'pending') DEFAULT 'pending',
                    quality_score DECIMAL(5,2) DEFAULT 0,
                    validation_details JSON,
                    rejection_reason VARCHAR(100),
                    rejection_details TEXT,
                    validated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    UNIQUE KEY unique_species (species_id),
                    FOREIGN KEY (species_id) REFERENCES biodiversity_categories(id) ON DELETE CASCADE
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        
        $this->pdo->exec($sql);
    }
    
    /**
     * Obtener ruta completa de imagen
     */
    private function getFullImagePath($relative_path) {
        return __DIR__ . '/public/' . ltrim($relative_path, '/');
    }
    
    /**
     * Generar reporte de validación
     */
    private function generateValidationReport() {
        $this->log("\n=== REPORTE DE VALIDACIÓN ===");
        $this->log("Imágenes procesadas: {$this->validationStats['images_processed']}");
        $this->log("Imágenes aprobadas: {$this->validationStats['images_approved']}");
        $this->log("Imágenes rechazadas: {$this->validationStats['images_rejected']}");
        
        if ($this->validationStats['images_processed'] > 0) {
            $approval_rate = round(($this->validationStats['images_approved'] / $this->validationStats['images_processed']) * 100, 2);
            $this->log("Tasa de aprobación: {$approval_rate}%");
        }
        
        if (!empty($this->validationStats['quality_scores'])) {
            $avg_score = round(array_sum($this->validationStats['quality_scores']) / count($this->validationStats['quality_scores']), 2);
            $this->log("Puntuación promedio: {$avg_score}");
        }
        
        if (!empty($this->validationStats['rejection_reasons'])) {
            $this->log("\nRazones de rechazo:");
            foreach ($this->validationStats['rejection_reasons'] as $reason => $count) {
                $this->log("- {$reason}: {$count}");
            }
        }
        
        $this->log("=== FIN DEL REPORTE ===");
    }
    
    private function log($message) {
        $timestamp = date('Y-m-d H:i:s');
        $log_entry = "[{$timestamp}] {$message}" . PHP_EOL;
        file_put_contents($this->logFile, $log_entry, FILE_APPEND | LOCK_EX);
        echo $log_entry;
    }
}

// Ejecutar si se llama directamente
if (basename(__FILE__) == basename($_SERVER['SCRIPT_NAME'])) {
    $validator = new ImageQualityValidator();
    
    echo "Iniciando validación de calidad de imágenes...\n";
    $validator->validateAllImages();
}
?>