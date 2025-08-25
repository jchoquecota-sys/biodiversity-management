<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class BiodiversityCategory extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'name',
        'scientific_name',
        'common_name',
        'description',
        'conservation_status',
        'conservation_status_id',
        'kingdom',
        'habitat',
        'idfamilia',
        'idreino',
        'idclase',
        'idorden',
        'image_path',
        'image_path_2',
        'image_path_3',
        'image_path_4',
    ];

    /**
     * The publications that belong to the biodiversity category.
     */
    public function publications()
    {
        return $this->belongsToMany(Publication::class, 'biodiversity_category_publication')
            ->withPivot('relevant_excerpt', 'page_reference')
            ->withTimestamps();
    }

    /**
     * Relación con el estado de conservación (nueva relación por ID).
     */
    public function conservationStatus()
    {
        return $this->belongsTo(ConservationStatus::class, 'conservation_status_id');
    }

    /**
     * Relación con el estado de conservación (legacy por código).
     */
    public function conservationStatusLegacy()
    {
        return $this->belongsTo(ConservationStatus::class, 'conservation_status', 'code');
    }

    /**
     * Relación con el reino taxonómico.
     */
    public function reino()
    {
        return $this->belongsTo(Reino::class, 'idreino');
    }

    /**
     * Relación con la familia taxonómica.
     */
    public function familia()
    {
        return $this->belongsTo(Familia::class, 'idfamilia', 'idfamilia');
    }

    /**
     * Relación con la clase taxonómica.
     */
    public function clase()
    {
        return $this->belongsTo(Clase::class, 'idclase', 'idclase');
    }

    /**
     * Relación con el orden taxonómico.
     */
    public function orden()
    {
        return $this->belongsTo(Orden::class, 'idorden', 'idorden');
    }






    
    /**
     * Get the image URL for the category.
     *
     * @return string|null
     */
    public function getImageUrl()
    {
        if ($this->image_path) {
            // Si la ruta empieza con 'images/', usar asset() directamente
            if (str_starts_with($this->image_path, 'images/')) {
                return asset($this->image_path);
            }
            // Para otras rutas, usar Storage como antes
            return \Storage::disk('public')->url($this->image_path);
        }
        
        return null;
    }
    
    /**
     * Get all image URLs for the category.
     *
     * @return array
     */
    public function getAllImageUrls()
    {
        $images = [];
        
        $imagePaths = [
            $this->image_path,
            $this->image_path_2,
            $this->image_path_3,
            $this->image_path_4,
        ];
        
        foreach ($imagePaths as $path) {
            if ($path) {
                // Si la ruta empieza con 'images/', usar asset() directamente
                if (str_starts_with($path, 'images/')) {
                    $images[] = asset($path);
                } else {
                    // Para otras rutas, usar Storage como antes
                    $images[] = \Storage::disk('public')->url($path);
                }
            }
        }
        
        return $images;
    }
    
    /**
     * Get the count of available images.
     *
     * @return int
     */
    public function getImageCount()
    {
        return count(array_filter([
            $this->image_path,
            $this->image_path_2,
            $this->image_path_3,
            $this->image_path_4,
        ]));
    }
    
    /**
     * Check if the category has multiple images.
     *
     * @return bool
     */
    public function hasMultipleImages()
    {
        return $this->getImageCount() > 1;
    }
    
    /**
     * Obtener categorías similares basadas en el mismo reino y estado de conservación.
     *
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getSimilarCategories($limit = 5)
    {
        return self::where('id', '!=', $this->id)
            ->where(function ($query) {
                $query->where('kingdom', $this->kingdom)
                    ->orWhere('conservation_status', $this->conservation_status);
            })
            ->inRandomOrder()
            ->limit($limit)
            ->get();
    }

    /**
     * Obtener el color de la insignia según el estado de conservación.
     *
     * @return string
     */
    public function getStatusColorAttribute()
    {
        return $this->conservationStatus?->color ?? 'secondary';
    }

    /**
     * Obtener el nombre del estado de conservación.
     *
     * @return string
     */
    public function getStatusNameAttribute()
    {
        return $this->conservationStatus?->name ?? $this->conservation_status;
    }

    /**
     * Obtener el nombre del reino.
     *
     * @return string
     */
    public function getKingdomNameAttribute()
    {
        $kingdoms = [
            'animalia' => 'Animalia',
            'plantae' => 'Plantae',
            'fungi' => 'Fungi',
            'protista' => 'Protista',
            'monera' => 'Monera',
        ];

        return $kingdoms[$this->kingdom] ?? $this->kingdom;
    }

    /**
     * Obtener la familia taxonómica.
     *
     * @return string|null
     */
    public function getFamiliaNameAttribute()
    {
        return $this->familia?->nombre;
    }

    /**
     * Obtener el orden taxonómico a través de la familia.
     *
     * @return string|null
     */
    public function getOrdenNameAttribute()
    {
        return $this->familia?->orden?->nombre;
    }

    /**
     * Obtener la clase taxonómica a través del orden.
     *
     * @return string|null
     */
    public function getClaseNameAttribute()
    {
        return $this->familia?->orden?->clase?->nombre;
    }

    /**
     * Obtener la jerarquía taxonómica completa.
     *
     * @return array
     */
    public function getTaxonomicHierarchy()
    {
        return [
            'reino' => $this->kingdom_name,
            'clase' => $this->clase_name,
            'orden' => $this->orden_name,
            'familia' => $this->familia_name,
            'especie' => $this->scientific_name,
        ];
    }
}