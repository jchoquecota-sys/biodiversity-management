<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\HomeContent;

class HeroSliderConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $heroConfigs = [
            [
                'section' => 'hero',
                'key' => 'use_image_slider',
                'value' => 'false',
                'type' => 'text',
                'is_active' => true,
                'sort_order' => 1
            ],
            [
                'section' => 'hero',
                'key' => 'slider_autoplay',
                'value' => 'true',
                'type' => 'text',
                'is_active' => true,
                'sort_order' => 2
            ],
            [
                'section' => 'hero',
                'key' => 'slider_interval',
                'value' => '5000',
                'type' => 'text',
                'is_active' => true,
                'sort_order' => 3
            ],
            [
                'section' => 'hero',
                'key' => 'enable_icons',
                'value' => 'true',
                'type' => 'text',
                'is_active' => true,
                'sort_order' => 4
            ]
        ];

        foreach ($heroConfigs as $config) {
            HomeContent::updateOrCreate(
                [
                    'section' => $config['section'],
                    'key' => $config['key']
                ],
                $config
            );
        }
    }
}
