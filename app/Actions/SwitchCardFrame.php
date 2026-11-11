<?php

namespace App\Actions;

use App\Models\Card;
use App\Models\CardFrame;
use App\Rendering\CardFrameRenderer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use InvalidArgumentException;

class SwitchCardFrame
{
    public function __construct(
        protected CardFrameRenderer $frameRenderer
    ) {}

    /**
     * Switch outer frame for an archived card and re-render without altering the original card image.
     */
    public function execute(Card $card, CardFrame $frame): Card
    {
        if (!$frame->is_active) {
            throw new InvalidArgumentException("Frame [{$frame->name}] is currently inactive.");
        }

        return DB::transaction(function () use ($card, $frame) {
            $user = $card->user;
            $oldFramedPath = $card->framed_image_path;
            $oldSize = ($oldFramedPath && Storage::disk('local')->exists($oldFramedPath))
                ? Storage::disk('local')->size($oldFramedPath)
                : 0;

            // Render new framed composite
            $newFramedPath = $this->frameRenderer->renderFramedCard($card, $frame);
            $newSize = ($newFramedPath && Storage::disk('local')->exists($newFramedPath))
                ? Storage::disk('local')->size($newFramedPath)
                : 0;

            // Delete old framed file if different
            if ($oldFramedPath && $oldFramedPath !== $newFramedPath && Storage::disk('local')->exists($oldFramedPath)) {
                Storage::disk('local')->delete($oldFramedPath);
            }

            // Adjust storage difference
            $sizeDiff = $newSize - $oldSize;
            if ($sizeDiff > 0) {
                $user->increment('storage_used_bytes', $sizeDiff);
            } elseif ($sizeDiff < 0) {
                $user->decrement('storage_used_bytes', abs($sizeDiff));
            }

            return $card->fresh(['photo', 'frame', 'albums']);
        });
    }
}
