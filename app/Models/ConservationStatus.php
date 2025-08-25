<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConservationStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'name_en',
        'description',
        'color',
        'priority',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relación con las categorías de biodiversidad.
     */
    public function biodiversityCategories()
    {
        return $this->hasMany(BiodiversityCategory::class, 'conservation_status', 'code');
    }

    /**
     * Scope para obtener solo los estados activos.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope para ordenar por prioridad.
     */
    public function scopeByPriority($query)
    {
        return $query->orderBy('priority', 'desc');
    }

    /**
     * Obtener el estado por código.
     */
    public static function findByCode($code)
    {
        return static::where('code', $code)->first();
    }

    /**
     * Obtener todos los estados como array para selects.
     */
    public static function getForSelect()
    {
        return static::active()->byPriority()->pluck('name', 'code')->toArray();
    }
}