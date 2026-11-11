<?php

namespace App\Rendering;

use App\Models\Card;
use App\Models\CardFrame;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CardFrameRenderer
{
    public const CANVAS_WIDTH = 744;
    public const CANVAS_HEIGHT = 1039;

    /**
     * Compose a high-resolution decorative outer frame around the 100% untouched original Pokémon card image.
     * The original artwork, character, text, and visual content are strictly and completely preserved.
     */
    public function renderFramedCard(Card $card, ?CardFrame $frame = null): string
    {
        $frame = $frame ?: ($card->frame ?: CardFrame::where('slug', 'classic')->first());
        $config = $frame?->config ?? [];

        $canvas = imagecreatetruecolor(self::CANVAS_WIDTH, self::CANVAS_HEIGHT);
        imagealphablending($canvas, true);
        imagesavealpha($canvas, true);

        // Frame configuration styling
        $theme = $config['theme'] ?? 'classic';
        $borderWidth = (int) ($config['border_width'] ?? 32);

        // Frame colors from configuration
        $outerBg = $this->hexToRgb($config['outer_bg'] ?? '#0F1622');
        $primaryColor = $this->hexToRgb($config['border_primary'] ?? '#3D4F66');
        $innerColor = $this->hexToRgb($config['border_inner'] ?? '#202E40');
        $accentColor = $this->hexToRgb($config['accent_color'] ?? '#83B9F2');

        $bgColor = imagecolorallocate($canvas, $outerBg['r'], $outerBg['g'], $outerBg['b']);
        $borderColor = imagecolorallocate($canvas, $primaryColor['r'], $primaryColor['g'], $primaryColor['b']);
        $innerBorderColor = imagecolorallocate($canvas, $innerColor['r'], $innerColor['g'], $innerColor['b']);
        $accentC = imagecolorallocate($canvas, $accentColor['r'], $accentColor['g'], $accentColor['b']);
        $plaqueBg = imagecolorallocate($canvas, 10, 15, 22);
        $textColor = imagecolorallocate($canvas, 241, 244, 248);
        $textSubtle = imagecolorallocate($canvas, 148, 163, 184);

        // 1. Draw outer frame matting background
        imagefilledrectangle($canvas, 0, 0, self::CANVAS_WIDTH, self::CANVAS_HEIGHT, $bgColor);

        // 2. Draw outer multi-line precision rim
        imagerectangle($canvas, 6, 6, self::CANVAS_WIDTH - 7, self::CANVAS_HEIGHT - 7, $borderColor);
        imagerectangle($canvas, 8, 8, self::CANVAS_WIDTH - 9, self::CANVAS_HEIGHT - 9, $innerBorderColor);
        imagerectangle($canvas, 10, 10, self::CANVAS_WIDTH - 11, self::CANVAS_HEIGHT - 11, $borderColor);

        // Theme-specific decorative corner flourishes on the outer frame
        $this->drawThemeFlourishes($canvas, $theme, $borderColor, $innerBorderColor, $accentC);

        // Inner window coordinates where the card sits
        // Standard Pokémon card aspect ratio: 63:88 (~0.7159)
        // Reserve 40px at the bottom for the collector frame plaque
        $winX = $borderWidth;
        $winY = $borderWidth;
        $winW = self::CANVAS_WIDTH - ($borderWidth * 2);
        $winH = self::CANVAS_HEIGHT - ($borderWidth * 2) - 36;

        // Bevel matting container behind card
        imagefilledrectangle($canvas, $winX - 5, $winY - 5, $winX + $winW + 4, $winY + $winH + 4, $innerBorderColor);
        imagerectangle($canvas, $winX - 6, $winY - 6, $winX + $winW + 5, $winY + $winH + 5, $borderColor);
        imagerectangle($canvas, $winX - 1, $winY - 1, $winX + $winW, $winY + $winH, $accentC);

        // 3. Composite the original card image into the window
        // STRICT RULE: ORIGINAL CARD IS NEVER CROPPED, DISTORTED, OR ALTERED
        $photo = $card->photo;
        if ($photo && $photo->original_path && Storage::disk('local')->exists($photo->original_path)) {
            $photoFullPath = Storage::disk('local')->path($photo->original_path);
            $imgInfo = @getimagesize($photoFullPath);

            if ($imgInfo) {
                $src = match ($imgInfo[2]) {
                    IMAGETYPE_JPEG => @imagecreatefromjpeg($photoFullPath),
                    IMAGETYPE_PNG => @imagecreatefrompng($photoFullPath),
                    IMAGETYPE_WEBP => @imagecreatefromwebp($photoFullPath),
                    default => null,
                };

                if ($src) {
                    $srcW = imagesx($src);
                    $srcH = imagesy($src);

                    // Fit card image proportionally inside window without cropping
                    $scale = min($winW / $srcW, $winH / $srcH);
                    $dstW = (int) round($srcW * $scale);
                    $dstH = (int) round($srcH * $scale);

                    // Centered coordinates inside window
                    $dstX = $winX + (int) round(($winW - $dstW) / 2);
                    $dstY = $winY + (int) round(($winH - $dstH) / 2);

                    // Copy card image with smooth high quality resampling
                    imagecopyresampled($canvas, $src, $dstX, $dstY, 0, 0, $dstW, $dstH, $srcW, $srcH);

                    // Refined protective border around card perimeter
                    imagerectangle($canvas, $dstX, $dstY, $dstX + $dstW - 1, $dstY + $dstH - 1, $innerBorderColor);

                    imagedestroy($src);
                }
            }
        }

        // 4. Collector Frame Bottom Plaque (On outer frame, below card)
        $plaqueY = self::CANVAS_HEIGHT - 44;
        $plaqueX = $borderWidth;
        $plaqueW = self::CANVAS_WIDTH - ($borderWidth * 2);

        imagefilledrectangle($canvas, $plaqueX, $plaqueY, $plaqueX + $plaqueW, $plaqueY + 28, $plaqueBg);
        imagerectangle($canvas, $plaqueX, $plaqueY, $plaqueX + $plaqueW, $plaqueY + 28, $innerBorderColor);
        imagerectangle($canvas, $plaqueX + 1, $plaqueY + 1, $plaqueX + $plaqueW - 1, $plaqueY + 27, $borderColor);

        // Text labels on plaque
        $frameLabel = mb_strtoupper($config['label'] ?? ($frame->name ?? 'MEMODEX FRAME'));
        $cardNumber = $card->card_number ?: 'MDX-000000';

        imagestring($canvas, 2, $plaqueX + 12, $plaqueY + 7, "MEMODEX ARCHIVE", $textSubtle);
        imagestring($canvas, 3, $plaqueX + (int) ($plaqueW / 2) - (strlen($frameLabel) * 4), $plaqueY + 7, $frameLabel, $accentC);
        imagestring($canvas, 2, $plaqueX + $plaqueW - (strlen($cardNumber) * 7) - 12, $plaqueY + 7, $cardNumber, $textColor);

        // 5. Save framed card to private disk: users/{user_id}/framed-cards/
        $userId = $card->user_id;
        $relDir = "memodex/users/{$userId}/framed-cards";
        $absDir = Storage::disk('local')->path($relDir);
        if (!is_dir($absDir)) {
            mkdir($absDir, 0755, true);
        }

        $fileName = 'framed_' . Str::slug($card->card_number) . '_' . Str::random(8) . '.png';
        $fullPath = $absDir . '/' . $fileName;

        imagepng($canvas, $fullPath, 9);
        imagedestroy($canvas);

        $relPath = $relDir . '/' . $fileName;
        $card->update([
            'frame_id' => $frame->id,
            'framed_image_path' => $relPath,
            'render_path' => $relPath, // keep backwards compatible
        ]);

        return $relPath;
    }

    protected function drawThemeFlourishes($im, string $theme, int $borderC, int $innerC, int $accentC): void
    {
        $corners = [
            [16, 16, 1, 1],
            [self::CANVAS_WIDTH - 17, 16, -1, 1],
            [16, self::CANVAS_HEIGHT - 17, 1, -1],
            [self::CANVAS_WIDTH - 17, self::CANVAS_HEIGHT - 17, -1, -1],
        ];

        foreach ($corners as [$cx, $cy, $dx, $dy]) {
            match ($theme) {
                'gold', 'premium' => $this->drawGoldCorner($im, $cx, $cy, $dx, $dy, $borderC, $accentC),
                'electric' => $this->drawElectricCorner($im, $cx, $cy, $dx, $dy, $borderC, $accentC),
                'silver' => $this->drawSilverCorner($im, $cx, $cy, $dx, $dy, $borderC, $innerC, $accentC),
                'holographic' => $this->drawHoloCorner($im, $cx, $cy, $dx, $dy, $borderC, $accentC),
                'rainbow' => $this->drawRainbowCorner($im, $cx, $cy, $dx, $dy, $borderC, $accentC),
                default => $this->drawClassicCorner($im, $cx, $cy, $dx, $dy, $borderC, $accentC),
            };
        }
    }

    protected function drawClassicCorner($im, int $cx, int $cy, int $dx, int $dy, int $borderC, int $accentC): void
    {
        imageline($im, $cx, $cy, $cx + (18 * $dx), $cy, $accentC);
        imageline($im, $cx, $cy, $cx, $cy + (18 * $dy), $accentC);
        imageline($im, $cx + (4 * $dx), $cy + (4 * $dy), $cx + (12 * $dx), $cy + (4 * $dy), $borderC);
        imageline($im, $cx + (4 * $dx), $cy + (4 * $dy), $cx + (4 * $dx), $cy + (12 * $dy), $borderC);
    }

    protected function drawGoldCorner($im, int $cx, int $cy, int $dx, int $dy, int $borderC, int $accentC): void
    {
        // Double bracket and gold corner gem
        imageline($im, $cx, $cy, $cx + (22 * $dx), $cy, $accentC);
        imageline($im, $cx, $cy, $cx, $cy + (22 * $dy), $accentC);
        imageline($im, $cx + (3 * $dx), $cy + (3 * $dy), $cx + (16 * $dx), $cy + (3 * $dy), $borderC);
        imageline($im, $cx + (3 * $dx), $cy + (3 * $dy), $cx + (3 * $dx), $cy + (16 * $dy), $borderC);
        // Small corner stud
        imagefilledrectangle($im, $cx + (5 * $dx), $cy + (5 * $dy), $cx + (8 * $dx), $cy + (8 * $dy), $accentC);
    }

    protected function drawElectricCorner($im, int $cx, int $cy, int $dx, int $dy, int $borderC, int $accentC): void
    {
        // Sharp energy chevron
        imageline($im, $cx, $cy, $cx + (20 * $dx), $cy, $accentC);
        imageline($im, $cx, $cy, $cx, $cy + (20 * $dy), $accentC);
        imageline($im, $cx + (6 * $dx), $cy + (2 * $dy), $cx + (16 * $dx), $cy + (2 * $dy), $borderC);
        imageline($im, $cx + (2 * $dx), $cy + (6 * $dy), $cx + (2 * $dx), $cy + (16 * $dy), $borderC);
        imageline($im, $cx + (8 * $dx), $cy + (8 * $dy), $cx + (14 * $dx), $cy + (14 * $dy), $accentC);
    }

    protected function drawSilverCorner($im, int $cx, int $cy, int $dx, int $dy, int $borderC, int $innerC, int $accentC): void
    {
        imageline($im, $cx, $cy, $cx + (18 * $dx), $cy, $accentC);
        imageline($im, $cx, $cy, $cx, $cy + (18 * $dy), $accentC);
        imageline($im, $cx + (2 * $dx), $cy + (2 * $dy), $cx + (14 * $dx), $cy + (2 * $dy), $innerC);
        imageline($im, $cx + (2 * $dx), $cy + (2 * $dy), $cx + (2 * $dx), $cy + (14 * $dy), $innerC);
        imageline($im, $cx + (5 * $dx), $cy + (5 * $dy), $cx + (10 * $dx), $cy + (5 * $dy), $borderC);
        imageline($im, $cx + (5 * $dx), $cy + (5 * $dy), $cx + (5 * $dx), $cy + (10 * $dy), $borderC);
    }

    protected function drawHoloCorner($im, int $cx, int $cy, int $dx, int $dy, int $borderC, int $accentC): void
    {
        imageline($im, $cx, $cy, $cx + (20 * $dx), $cy, $accentC);
        imageline($im, $cx, $cy, $cx, $cy + (20 * $dy), $accentC);
        imageline($im, $cx + (4 * $dx), $cy + (4 * $dy), $cx + (16 * $dx), $cy + (4 * $dy), $borderC);
        imageline($im, $cx + (4 * $dx), $cy + (4 * $dy), $cx + (4 * $dx), $cy + (16 * $dy), $borderC);
        // Prismatic diamond
        imagefilledellipse($im, $cx + (10 * $dx), $cy + (10 * $dy), 4, 4, $accentC);
    }

    protected function drawRainbowCorner($im, int $cx, int $cy, int $dx, int $dy, int $borderC, int $accentC): void
    {
        imageline($im, $cx, $cy, $cx + (22 * $dx), $cy, $accentC);
        imageline($im, $cx, $cy, $cx, $cy + (22 * $dy), $accentC);
        imageline($im, $cx + (3 * $dx), $cy + (3 * $dy), $cx + (16 * $dx), $cy + (3 * $dy), $borderC);
        imageline($im, $cx + (3 * $dx), $cy + (3 * $dy), $cx + (3 * $dx), $cy + (16 * $dy), $borderC);
        imageline($im, $cx + (6 * $dx), $cy + (6 * $dy), $cx + (12 * $dx), $cy + (6 * $dy), $accentC);
        imageline($im, $cx + (6 * $dx), $cy + (6 * $dy), $cx + (6 * $dx), $cy + (12 * $dy), $accentC);
    }

    protected function hexToRgb(string $hex): array
    {
        $hex = ltrim($hex, '#');
        if (strlen($hex) === 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }

        return [
            'r' => hexdec(substr($hex, 0, 2)),
            'g' => hexdec(substr($hex, 2, 2)),
            'b' => hexdec(substr($hex, 4, 2)),
        ];
    }
}
