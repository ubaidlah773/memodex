<?php

namespace App\Actions;

use App\Models\Album;
use App\Models\Card;
use App\Models\CardFrame;
use App\Models\Photo;
use App\Models\User;
use App\Rendering\CardFrameRenderer;
use App\Services\StorageService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use InvalidArgumentException;

class CreateCollectionCard
{
    public function __construct(
        protected CardFrameRenderer $frameRenderer,
        protected StorageService $storageService
    ) {}

    /**
     * Create a new Pokémon collection card from an authentic, untouched photo.
     * The original artwork and card data are 100% preserved.
     */
    public function execute(
        User $user,
        Photo $photo,
        ?CardFrame $frame = null,
        ?string $name = null,
        ?Album $album = null,
        bool $isFavorite = false
    ): Card {
        if ($photo->user_id !== $user->id) {
            throw new InvalidArgumentException('Unauthorized photo reference.');
        }

        // Default to classic frame if none specified
        $frame = $frame ?: CardFrame::where('slug', 'classic')->firstOrFail();

        return DB::transaction(function () use ($user, $photo, $frame, $name, $album, $isFavorite) {
            $cardNumber = $user->nextCardNumber();
            $displayName = $name ?: pathinfo($photo->original_name, PATHINFO_FILENAME);

            $card = Card::create([
                'user_id' => $user->id,
                'photo_id' => $photo->id,
                'frame_id' => $frame->id,
                'card_number' => $cardNumber,
                'name' => $displayName,
                'is_favorite' => $isFavorite,
            ]);

            // Render high-resolution outer framed PNG
            $framedPath = $this->frameRenderer->renderFramedCard($card, $frame);

            // Track storage for the framed PNG
            if ($framedPath && Storage::disk('local')->exists($framedPath)) {
                $framedSize = Storage::disk('local')->size($framedPath);
                $user->increment('storage_used_bytes', $framedSize);
            }

            // Attach to album if supplied
            if ($album && $album->user_id === $user->id) {
                $maxOrder = $album->cards()->max('sort_order') ?? 0;
                $album->cards()->attach($card->id, ['sort_order' => $maxOrder + 1]);
            }

            return $card->fresh(['photo', 'frame', 'albums']);
        });
    }
}
