<?php

namespace App\Services;

use App\Models\Card;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CardRenderer
{
    public const CARD_WIDTH = 744;
    public const CARD_HEIGHT = 1039;

    /**
     * Render a Card model into a 744x1039 PNG file in private storage.
     * Returns the relative storage path.
     */
    public function renderToPng(Card $card): string
    {
        $im = imagecreatetruecolor(self::CARD_WIDTH, self::CARD_HEIGHT);
        imagealphablending($im, true);
        imagesavealpha($im, true);

        // Palette definitions
        $bgColor = imagecolorallocate($im, 12, 17, 24);         // #0C1118
        $frameColor = imagecolorallocate($im, 20, 29, 43);       // #141D2B
        $borderOuter = imagecolorallocate($im, 42, 57, 75);      // #2A394B
        $accentBrass = imagecolorallocate($im, 196, 154, 69);    // #C49A45 (BASIC)
        $accentSilver = imagecolorallocate($im, 131, 185, 242);  // #83B9F2 (BASIC ★★)
        $textColor = imagecolorallocate($im, 241, 244, 248);     // #F1F4F8
        $textMuted = imagecolorallocate($im, 155, 170, 189);     // #9BAABD
        $textSubtle = imagecolorallocate($im, 113, 129, 150);    // #718196
        $boxBg = imagecolorallocate($im, 25, 37, 53);           // #192535

        $accent = ($card->rarity === 'BASIC ★★') ? $accentSilver : $accentBrass;

        // 1. Fill base canvas
        imagefilledrectangle($im, 0, 0, self::CARD_WIDTH, self::CARD_HEIGHT, $bgColor);

        // 2. Outer card frame with 24px margin
        $pad = 24;
        imagefilledrectangle($im, $pad, $pad, self::CARD_WIDTH - $pad, self::CARD_HEIGHT - $pad, $frameColor);
        imagerectangle($im, $pad, $pad, self::CARD_WIDTH - $pad, self::CARD_HEIGHT - $pad, $accent);
        imagerectangle($im, $pad + 4, $pad + 4, self::CARD_WIDTH - ($pad + 4), self::CARD_HEIGHT - ($pad + 4), $borderOuter);

        // 3. Header bar: Name and HP
        $headerY = 48;
        $headerH = 56;
        $contentX = 52;
        $contentW = self::CARD_WIDTH - ($contentX * 2);

        // Draw card name
        $nameText = mb_strtoupper($card->name);
        imagestring($im, 5, $contentX + 10, $headerY + 16, $nameText, $textColor);

        // Draw HP and Type if present
        $hpX = self::CARD_WIDTH - $contentX - 160;
        if ($card->hp) {
            imagestring($im, 4, $hpX, $headerY + 16, "HP {$card->hp}", $accent);
        }
        if ($card->type) {
            $typeStr = mb_strtoupper($card->type);
            imagestring($im, 4, self::CARD_WIDTH - $contentX - 70, $headerY + 16, "[{$typeStr}]", $textMuted);
        }

        // Header separator line
        imageline($im, $contentX, $headerY + $headerH, $contentX + $contentW, $headerY + $headerH, $borderOuter);

        // 4. Photo / Artwork Window
        $photoX = 52;
        $photoY = 124;
        $photoW = 640;
        $photoH = 460;

        // Fill photo viewport background
        $photoBg = imagecolorallocate($im, 16, 24, 34);
        imagefilledrectangle($im, $photoX, $photoY, $photoX + $photoW, $photoY + $photoH, $photoBg);

        // Load photo if available
        if ($card->photo && $card->photo->original_path && Storage::disk('local')->exists($card->photo->original_path)) {
            $photoPath = Storage::disk('local')->path($card->photo->original_path);
            $photoInfo = @getimagesize($photoPath);

            if ($photoInfo) {
                $src = match ($photoInfo[2]) {
                    IMAGETYPE_JPEG => @imagecreatefromjpeg($photoPath),
                    IMAGETYPE_PNG => @imagecreatefrompng($photoPath),
                    IMAGETYPE_WEBP => @imagecreatefromwebp($photoPath),
                    default => null,
                };

                if ($src) {
                    $sw = imagesx($src);
                    $sh = imagesy($src);

                    // Compute crop cover
                    $crop = $card->custom_data['crop'] ?? ['zoom' => 1, 'x' => 0, 'y' => 0];
                    $zoom = max(1.0, (float) ($crop['zoom'] ?? 1.0));
                    $offsetX = (float) ($crop['x'] ?? 0);
                    $offsetY = (float) ($crop['y'] ?? 0);

                    // Cover calculation
                    $scale = max($photoW / $sw, $photoH / $sh) * $zoom;
                    $scaledW = (int) ($sw * $scale);
                    $scaledH = (int) ($sh * $scale);

                    // Centered position with offsets
                    $dstX = $photoX + (int) (($photoW - $scaledW) / 2) + (int) ($offsetX * $photoW);
                    $dstY = $photoY + (int) (($photoH - $scaledH) / 2) + (int) ($offsetY * $photoH);

                    // Create clipping container for photo
                    $clip = imagecreatetruecolor($photoW, $photoH);
                    imagecopyresampled($clip, $src, $dstX - $photoX, $dstY - $photoY, 0, 0, $scaledW, $scaledH, $sw, $sh);
                    imagecopy($im, $clip, $photoX, $photoY, 0, 0, $photoW, $photoH);

                    imagedestroy($clip);
                    imagedestroy($src);
                }
            }
        }

        // Photo frame border
        imagerectangle($im, $photoX, $photoY, $photoX + $photoW, $photoY + $photoH, $accent);
        imagerectangle($im, $photoX + 1, $photoY + 1, $photoX + $photoW - 1, $photoY + $photoH - 1, $borderOuter);

        // 5. Card Metadata Banner (Rarity & Set Marker)
        $metaY = 600;
        imagestring($im, 3, $contentX + 8, $metaY, "MEMODEX ARCHIVE", $textSubtle);
        $rarityText = mb_strtoupper($card->rarity);
        $rarityX = self::CARD_WIDTH - $contentX - (strlen($rarityText) * 8) - 10;
        imagestring($im, 3, $rarityX, $metaY, $rarityText, $accent);

        // 6. Skill / Ability Box
        $skillY = 636;
        $skillH = 210;
        imagefilledrectangle($im, $contentX, $skillY, $contentX + $contentW, $skillY + $skillH, $boxBg);
        imagerectangle($im, $contentX, $skillY, $contentX + $contentW, $skillY + $skillH, $borderOuter);

        $skillName = $card->skill_name ?: 'Signature Memory';
        imagestring($im, 5, $contentX + 20, $skillY + 18, mb_strtoupper($skillName), $accent);

        if ($card->skill_description) {
            $descLines = explode("\n", wordwrap($card->skill_description, 64, "\n"));
            $descY = $skillY + 54;
            foreach (array_slice($descLines, 0, 4) as $line) {
                imagestring($im, 3, $contentX + 20, $descY, $line, $textColor);
                $descY += 22;
            }
        }

        if ($card->flavor_text) {
            $flavorY = $skillY + 154;
            imageline($im, $contentX + 20, $flavorY - 8, $contentX + $contentW - 20, $flavorY - 8, $borderOuter);
            $flavorLines = explode("\n", wordwrap('"' . $card->flavor_text . '"', 68, "\n"));
            foreach (array_slice($flavorLines, 0, 2) as $line) {
                imagestring($im, 2, $contentX + 24, $flavorY, $line, $textMuted);
                $flavorY += 18;
            }
        }

        // 7. Footer: Card number & Safe edge
        $footerY = 880;
        imageline($im, $contentX, $footerY, $contentX + $contentW, $footerY, $borderOuter);

        // Card number
        imagestring($im, 4, $contentX + 10, $footerY + 16, $card->card_number, $textColor);

        // Authenticity tag
        imagestring($im, 2, self::CARD_WIDTH - $contentX - 180, $footerY + 18, "OFFICIAL ARCHIVE ITEM", $textSubtle);

        // Save PNG to private disk
        $userId = $card->user_id;
        $relCardDir = "memodex/users/{$userId}/cards";
        $absCardDir = Storage::disk('local')->path($relCardDir);
        if (!is_dir($absCardDir)) {
            mkdir($absCardDir, 0755, true);
        }

        $fileName = 'card_' . Str::slug($card->card_number) . '_' . Str::random(8) . '.png';
        $fullPath = $absCardDir . '/' . $fileName;

        imagepng($im, $fullPath, 9);
        imagedestroy($im);

        $relPath = $relCardDir . '/' . $fileName;
        $card->update(['render_path' => $relPath]);

        return $relPath;
    }
}
