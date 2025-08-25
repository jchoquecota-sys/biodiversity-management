<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Reino extends Model
{
    protected $table = 'reinos';
    
    protected $fillable = [
        'nombre',
        'descripcion'
    ];

    /**
     * Relación con Clase
     */
    public function clases(): HasMany
    {
        return $this->hasMany(Clase::class, 'idreino');
    }

    /**
     * Relación con órdenes a través de clases
     */
    public function ordenes()
    {
        return $this->hasManyThrough(Orden::class, Clase::class, 'idreino', 'idclase', 'id', 'idclase');
    }

    /**
     * Obtener todas las familias de este reino
     */
    public function getAllFamilias()
    {
        return Familia::whereHas('orden.clase', function($query) {
            $query->where('idreino', $this->id);
        });
    }

    /**
     * Relación con BiodiversityCategory
     */
    public function biodiversityCategories(): HasMany
    {
        return $this->hasMany(BiodiversityCategory::class, 'idreino');
    }
}
