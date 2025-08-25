<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Clase extends Model
{
    protected $table = 'clases';
    protected $primaryKey = 'idclase';
    
    protected $fillable = [
        'nombre',
        'definicion',
        'idreino'
    ];
    
    /**
     * Relación con Reino
     */
    public function reino(): BelongsTo
    {
        return $this->belongsTo(Reino::class, 'idreino');
    }
    
    /**
     * Relación con órdenes
     */
    public function ordens(): HasMany
    {
        return $this->hasMany(Orden::class, 'idclase', 'idclase');
    }
}
