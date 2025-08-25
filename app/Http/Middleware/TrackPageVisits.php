<?php

namespace App\Http\Middleware;

use App\Models\PageVisit;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TrackPageVisits
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Solo registrar visitas para requests GET exitosos
        if ($request->isMethod('GET') && $response->getStatusCode() === 200) {
            $this->recordPageVisit($request);
        }

        return $response;
    }

    /**
     * Registrar la visita de la página
     */
    private function recordPageVisit(Request $request): void
    {
        try {
            // Evitar registrar visitas a rutas de API, assets, etc.
            $excludedPaths = [
                'api/',
                'storage/',
                'css/',
                'js/',
                'images/',
                'favicon.ico',
                '_debugbar',
                'livewire'
            ];

            $url = $request->fullUrl();
            $path = $request->path();

            // Verificar si la ruta debe ser excluida
            foreach ($excludedPaths as $excludedPath) {
                if (str_starts_with($path, $excludedPath)) {
                    return;
                }
            }

            // Evitar registrar múltiples visitas de la misma sesión en un corto período
            $sessionId = $request->session()->getId();
            $recentVisit = PageVisit::where('url', $url)
                ->where('session_id', $sessionId)
                ->where('created_at', '>=', now()->subMinutes(5))
                ->exists();

            if (!$recentVisit) {
                PageVisit::recordVisit(
                    url: $url,
                    ipAddress: $request->ip(),
                    userAgent: $request->userAgent(),
                    sessionId: $sessionId,
                    userId: Auth::id()
                );
            }
        } catch (\Exception $e) {
            // Log el error pero no interrumpir la respuesta
            \Log::error('Error recording page visit: ' . $e->getMessage());
        }
    }
}
