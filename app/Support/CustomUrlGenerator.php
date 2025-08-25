<?php

namespace App\Support;

use Spatie\MediaLibrary\Support\UrlGenerator\DefaultUrlGenerator;

class CustomUrlGenerator extends DefaultUrlGenerator
{
    public function getUrl(): string
    {
        $url = parent::getUrl();
        
        // Replace localhost with the correct APP_URL
        return str_replace('http://localhost', config('app.url'), $url);
    }
}