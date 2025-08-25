<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Override the getUrl method to ensure correct URL generation
        Media::macro('getCorrectUrl', function ($conversionName = '') {
            $url = $this->getUrl($conversionName);
            
            // Replace any localhost references with the correct APP_URL
            $correctedUrl = str_replace(
                ['http://127.0.0.1', 'http://127.0.0.1:8000'],
                config('app.url'),
                $url
            );
            
            return $correctedUrl;
        });
    }
}