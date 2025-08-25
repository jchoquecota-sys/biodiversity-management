<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FixImageUrls
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        
        // Process HTML and JSON responses
        if ($response instanceof \Illuminate\Http\Response) {
            $contentType = $response->headers->get('Content-Type', '');
            
            if (str_contains($contentType, 'text/html') || 
                str_contains($contentType, 'application/json')) {
                
                $content = $response->getContent();
                
                // Replace all localhost URLs with the correct APP_URL
                 $correctedContent = str_replace(
                     ['http://localhost/storage', 'http://localhost:8000/storage'],
                     [config('app.url') . '/storage', config('app.url') . '/storage'],
                     $content
                 );
                 
                 // Also replace any remaining localhost references
                 $correctedContent = str_replace(
                     'http://localhost',
                     config('app.url'),
                     $correctedContent
                 );
                
                $response->setContent($correctedContent);
            }
        }
        
        return $response;
    }
}
