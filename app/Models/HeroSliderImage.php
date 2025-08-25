<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class HeroSliderImage extends Model implements HasMedia
{
    use InteractsWithMedia;
    
    protected $fillable = [
        'title',
        'description',
        'alt_text',
        'button_text',
        'button_url',
        'sort_order',
        'is_active',
        'has_overlay_image',
        'overlay_position',
        'overlay_alt_text',
        'overlay_description',
        'overlay_button_text',
        'overlay_button_url',
        'overlay_width',
        'overlay_height'
    ];
    
    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'has_overlay_image' => 'boolean'
    ];
    
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('hero_images')
              ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif', 'image/webp'])
              ->singleFile();
              
        $this->addMediaCollection('overlay_images')
              ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif', 'image/webp'])
              ->singleFile();
    }
    
    public function registerMediaConversions(Media $media = null): void
    {
        // Only apply conversions if media exists
        if ($media === null) {
            return;
        }
        
        // Conversions for hero images
        if ($media->collection_name === 'hero_images') {
            $this->addMediaConversion('thumb')
                  ->width(300)
                  ->height(200)
                  ->sharpen(10)
                  ->performOnCollections('hero_images');
                  
            $this->addMediaConversion('hero')
                  ->width(1920)
                  ->height(800)
                  ->optimize()
                  ->nonQueued()
                  ->performOnCollections('hero_images');
        }
        
        // Conversions for overlay images
        if ($media->collection_name === 'overlay_images') {
            $this->addMediaConversion('overlay_thumb')
                  ->width(200)
                  ->height(150)
                  ->sharpen(10)
                  ->performOnCollections('overlay_images');
                  
            $this->addMediaConversion('overlay')
                  ->width(600)
                  ->height(400)
                  ->optimize()
                  ->nonQueued()
                  ->performOnCollections('overlay_images');
        }
    }
    
    public function getImageUrl($conversion = null): string
    {
        if ($this->hasMedia('hero_images')) {
            return $conversion 
                ? $this->getFirstMediaUrl('hero_images', $conversion)
                : $this->getFirstMediaUrl('hero_images');
        }
        
        return asset('images/default-hero.jpg');
    }
    
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('created_at');
    }
    
    public function getOverlayImageUrl($conversion = null): string
    {
        if ($this->hasMedia('overlay_images')) {
            return $conversion 
                ? $this->getFirstMediaUrl('overlay_images', $conversion)
                : $this->getFirstMediaUrl('overlay_images');
        }
        
        return '';
    }
}
