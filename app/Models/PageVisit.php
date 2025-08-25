<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PageVisit extends Model
{
    protected $fillable = [
        'url',
        'ip_address',
        'user_agent',
        'session_id',
        'user_id'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relación con el usuario
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtener el conteo de visitas para una URL específica
     */
    public static function getVisitCount(string $url): int
    {
        return static::where('url', $url)->count();
    }

    /**
     * Obtener el conteo de visitas únicas para una URL específica
     */
    public static function getUniqueVisitCount(string $url): int
    {
        return static::where('url', $url)
            ->distinct('ip_address')
            ->count('ip_address');
    }

    /**
     * Registrar una nueva visita
     */
    public static function recordVisit(string $url, ?string $ipAddress = null, ?string $userAgent = null, ?string $sessionId = null, ?int $userId = null): void
    {
        static::create([
            'url' => $url,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'session_id' => $sessionId,
            'user_id' => $userId,
        ]);
    }
}
