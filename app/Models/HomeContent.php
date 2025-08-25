<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class HomeContent extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $table = 'home_content';

    protected $fillable = [
        'section',
        'key',
        'value',
        'type',
        'is_active',
        'sort_order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer'
    ];

    /**
     * Get content by section and key
     */
    public static function getContent($section, $key, $default = '')
    {
        $content = static::where('section', $section)
                        ->where('key', $key)
                        ->where('is_active', true)
                        ->first();

        return $content ? $content->value : $default;
    }

    /**
     * Get all content for a section
     */
    public static function getSectionContent($section)
    {
        return static::where('section', $section)
                    ->where('is_active', true)
                    ->orderBy('sort_order')
                    ->get()
                    ->keyBy('key');
    }

    /**
     * Set content value
     */
    public static function setContent($section, $key, $value, $type = 'text')
    {
        return static::updateOrCreate(
            ['section' => $section, 'key' => $key],
            ['value' => $value, 'type' => $type, 'is_active' => true]
        );
    }

    /**
     * Register media collections
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')
              ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif', 'image/webp']);
    }

    /**
     * Get the image URL
     */
    public function getImageUrl()
    {
        if ($this->type === 'image' && $this->getFirstMedia('images')) {
            return $this->getFirstMediaUrl('images');
        }
        return $this->value;
    }
}
