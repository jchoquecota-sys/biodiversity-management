<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Familia extends Model
{
    protected $table = 'familias';
    protected $primaryKey = 'idfamilia';
    
    protected $fillable = [
        'nombre',
        'definicion',
        'idorden'
    ];
    
    /**
     * Relación con orden
     */
    public function orden(): BelongsTo
    {
        return $this->belongsTo(Orden::class, 'idorden', 'idorden');
    }

    /**
     * Relación con clase a través del orden
     */
    public function clase()
    {
        return $this->hasOneThrough(Clase::class, Orden::class, 'idorden', 'idclase', 'idorden', 'idclase');
    }

    /**
     * Obtener el reino a través del orden y clase
     */
    public function getReinoAttribute()
    {
        return $this->orden?->clase?->reino;
    }

    /**
     * Relación con categorías de biodiversidad
     */
    public function biodiversityCategories()
    {
        return $this->hasMany(BiodiversityCategory::class, 'idfamilia', 'idfamilia');
    }
}
