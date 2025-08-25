<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'group',
        'type',
        'options',
        'label',
        'description',
        'order'
    ];

    protected $casts = [
        'options' => 'array',
    ];

    /**
     * Get a setting value by key
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get(string $key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        
        return $setting ? $setting->value : $default;
    }

    /**
     * Set a setting value
     *
     * @param string $key
     * @param mixed $value
     * @return bool
     */
    public static function set(string $key, $value)
    {
        $setting = self::firstOrNew(['key' => $key]);
        $setting->value = $value;
        
        return $setting->save();
    }

    /**
     * Get all settings by group
     *
     * @param string $group
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getByGroup(string $group)
    {
        return self::where('group', $group)
            ->orderBy('order')
            ->get()
            ->keyBy('key');
    }
}
