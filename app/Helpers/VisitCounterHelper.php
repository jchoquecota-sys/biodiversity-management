<?php

namespace App\Helpers;

use App\Models\PageVisit;
use Illuminate\Support\Facades\Cache;

class VisitCounterHelper
{
    /**
     * Obtener el conteo de visitas para la URL actual con caché
     */
    public static function getCurrentPageVisits(): int
    {
        $url = request()->fullUrl();
        return self::getPageVisits($url);
    }

    /**
     * Obtener el conteo de visitas para una URL específica con caché
     */
    public static function getPageVisits(string $url): int
    {
        $cacheKey = 'page_visits_' . md5($url);
        
        return Cache::remember($cacheKey, now()->addMinutes(5), function () use ($url) {
            return PageVisit::getVisitCount($url);
        });
    }

    /**
     * Obtener el conteo de visitas únicas para la URL actual con caché
     */
    public static function getCurrentPageUniqueVisits(): int
    {
        $url = request()->fullUrl();
        return self::getPageUniqueVisits($url);
    }

    /**
     * Obtener el conteo de visitas únicas para una URL específica con caché
     */
    public static function getPageUniqueVisits(string $url): int
    {
        $cacheKey = 'page_unique_visits_' . md5($url);
        
        return Cache::remember($cacheKey, now()->addMinutes(5), function () use ($url) {
            return PageVisit::getUniqueVisitCount($url);
        });
    }

    /**
     * Obtener estadísticas generales del sitio
     */
    public static function getSiteStats(): array
    {
        return Cache::remember('site_visit_stats', now()->addMinutes(10), function () {
            return [
                'total_visits' => PageVisit::count(),
                'unique_visitors' => PageVisit::distinct('ip_address')->count('ip_address'),
                'today_visits' => PageVisit::whereDate('created_at', today())->count(),
                'this_week_visits' => PageVisit::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
                'this_month_visits' => PageVisit::whereMonth('created_at', now()->month)->count(),
            ];
        });
    }

    /**
     * Obtener las páginas más visitadas
     */
    public static function getTopPages(int $limit = 10): array
    {
        return Cache::remember('top_pages_' . $limit, now()->addMinutes(15), function () use ($limit) {
            return PageVisit::selectRaw('url, COUNT(*) as visit_count')
                ->groupBy('url')
                ->orderByDesc('visit_count')
                ->limit($limit)
                ->get()
                ->toArray();
        });
    }

    /**
     * Limpiar caché de contadores de visitas
     */
    public static function clearCache(): void
    {
        Cache::forget('site_visit_stats');
        
        // Limpiar caché de páginas principales
        $topPages = self::getTopPages(50);
        foreach ($topPages as $page) {
            $cacheKey = 'page_visits_' . md5($page['url']);
            Cache::forget($cacheKey);
            
            $uniqueCacheKey = 'page_unique_visits_' . md5($page['url']);
            Cache::forget($uniqueCacheKey);
        }
    }
}