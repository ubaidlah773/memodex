<?php

namespace Database\Seeders;

use App\Models\CardTemplate;
use Illuminate\Database\Seeder;

class CardTemplateSeeder extends Seeder
{
    public function run(): void
    {
        CardTemplate::updateOrCreate(
            ['slug' => 'basic'],
            [
                'name' => 'Memodex Basic Standard',
                'rarity' => 'BASIC',
                'version' => '1.0.0',
                'is_active' => true,
                'preview_path' => null,
                'config' => [
                    'theme' => 'brass-neutral',
                    'primary_accent' => '#C49A45',
                    'border_color' => '#3D4F66',
                    'frame_bg' => '#141D2B',
                    'header_style' => 'solid',
                    'safe_inset_mm' => 3,
                    'export_width' => 744,
                    'export_height' => 1039,
                    'aspect_ratio' => '63/88',
                ],
            ]
        );

        CardTemplate::updateOrCreate(
            ['slug' => 'silver-star'],
            [
                'name' => 'Memodex Silver Star ★★',
                'rarity' => 'BASIC ★★',
                'version' => '1.0.0',
                'is_active' => true,
                'preview_path' => null,
                'config' => [
                    'theme' => 'silver-blue',
                    'primary_accent' => '#83B9F2',
                    'border_color' => '#4A6282',
                    'frame_bg' => '#162232',
                    'header_style' => 'refined',
                    'safe_inset_mm' => 3,
                    'export_width' => 744,
                    'export_height' => 1039,
                    'aspect_ratio' => '63/88',
                ],
            ]
        );
    }
}
