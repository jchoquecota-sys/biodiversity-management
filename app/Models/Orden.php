<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Orden extends Model
{
    protected $table = 'ordens';
    protected $primaryKey = 'idorden';
    
    protected $fillable = [
        'nombre',
        'definicion',
        'idclase'
    ];
    
    /**
     * Relación con clase
     */
    public function clase(): BelongsTo
    {
        return $this->belongsTo(Clase::class, 'idclase', 'idclase');
    }
    
    /**
     * Relación con familias
     */
    public function familias(): HasMany
    {
        return $this->hasMany(Familia::class, 'idorden', 'idorden');
    }

    /**
     * Obtener el reino a través de la clase
     */
    public function reino()
    {
        return $this->hasOneThrough(Reino::class, Clase::class, 'idclase', 'id', 'idclase', 'idreino');
    }
}
