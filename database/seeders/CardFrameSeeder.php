<?php

namespace Database\Seeders;

use App\Models\CardFrame;
use Illuminate\Database\Seeder;

class CardFrameSeeder extends Seeder
{
    public function run(): void
    {
        $frames = [
            [
                'name' => 'Classic Frame',
                'slug' => 'classic',
                'description' => 'Timeless deep obsidian matting with refined double border and clean collector corners.',
                'config' => [
                    'theme' => 'classic',
                    'outer_bg' => '#0F1622',
                    'border_primary' => '#3D4F66',
                    'border_inner' => '#202E40',
                    'accent_color' => '#83B9F2',
                    'border_width' => 28,
                    'inner_padding' => 12,
                    'label' => 'CLASSIC ARCHIVE',
                ],
                'is_active' => true,
            ],
            [
                'name' => 'Silver Frame',
                'slug' => 'silver',
                'description' => 'Brushed platinum and cool metallic silver border with dual micro-insets.',
                'config' => [
                    'theme' => 'silver',
                    'outer_bg' => '#141D2B',
                    'border_primary' => '#94A3B8',
                    'border_inner' => '#CBD5E1',
                    'accent_color' => '#E2E8F0',
                    'border_width' => 30,
                    'inner_padding' => 12,
                    'label' => 'SILVER EDITION',
                ],
                'is_active' => true,
            ],
            [
                'name' => 'Gold Frame',
                'slug' => 'gold',
                'description' => 'Rich metallic gold border with warm brass bevels and museum presentation.',
                'config' => [
                    'theme' => 'gold',
                    'outer_bg' => '#1A160E',
                    'border_primary' => '#D4AF37',
                    'border_inner' => '#AA771C',
                    'accent_color' => '#F3E5AB',
                    'border_width' => 32,
                    'inner_padding' => 14,
                    'label' => 'GOLD MASTER',
                ],
                'is_active' => true,
            ],
            [
                'name' => 'Electric Frame',
                'slug' => 'electric',
                'description' => 'Vibrant electric blue border with technical energy circuit accents.',
                'config' => [
                    'theme' => 'electric',
                    'outer_bg' => '#0A1526',
                    'border_primary' => '#00D2FF',
                    'border_inner' => '#2563EB',
                    'accent_color' => '#38BDF8',
                    'border_width' => 30,
                    'inner_padding' => 12,
                    'label' => 'ELECTRIC POWER',
                ],
                'is_active' => true,
            ],
            [
                'name' => 'Holographic Frame',
                'slug' => 'holographic',
                'description' => 'Prismatic iridescent foil border with shimmering ultraviolet and teal facets.',
                'config' => [
                    'theme' => 'holographic',
                    'outer_bg' => '#161328',
                    'border_primary' => '#A78BFA',
                    'border_inner' => '#38BDF8',
                    'accent_color' => '#F472B6',
                    'border_width' => 32,
                    'inner_padding' => 14,
                    'label' => 'HOLO FOIL',
                ],
                'is_active' => true,
            ],
            [
                'name' => 'Rainbow Frame',
                'slug' => 'rainbow',
                'description' => 'Radiant spectrum border with vibrant spectral luminescence.',
                'config' => [
                    'theme' => 'rainbow',
                    'outer_bg' => '#181226',
                    'border_primary' => '#F43F5E',
                    'border_inner' => '#8B5CF6',
                    'accent_color' => '#10B981',
                    'border_width' => 32,
                    'inner_padding' => 14,
                    'label' => 'RAINBOW RARE',
                ],
                'is_active' => true,
            ],
            [
                'name' => 'Premium Frame',
                'slug' => 'premium',
                'description' => 'Museum-grade acrylic slab mount with recessed velvet bevel and gold authenticity seal.',
                'config' => [
                    'theme' => 'premium',
                    'outer_bg' => '#080C12',
                    'border_primary' => '#C49A45',
                    'border_inner' => '#475569',
                    'accent_color' => '#E6B979',
                    'border_width' => 36,
                    'inner_padding' => 16,
                    'label' => 'PREMIUM GRADE',
                ],
                'is_active' => true,
            ],
        ];

        foreach ($frames as $frame) {
            CardFrame::updateOrCreate(
                ['slug' => $frame['slug']],
                $frame
            );
        }
    }
}
