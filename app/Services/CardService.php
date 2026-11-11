<?php

namespace App\Services;

use App\Actions\CreateCollectionCard;
use App\Actions\SwitchCardFrame;
use App\Actions\ToggleCardFavorite;
use App\Models\Album;
use App\Models\Card;
use App\Models\CardFrame;
use App\Models\Photo;
use App\Models\User;
use App\Rendering\CardFrameRenderer;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use InvalidArgumentException;

class CardService
{
    public function __construct(
        protected CardFrameRenderer $frameRenderer,
        protected CreateCollectionCard $createAction,
        protected SwitchCardFrame $switchAction,
        protected ToggleCardFavorite $favoriteAction
    ) {}

    /**
     * Create an authentic collection card with decorative outer frame.
     * The original uploaded Pokémon card image is 100% preserved.
     */
    public function createCard(User $user, array $data): Card
    {
        $photo = Photo::findOrFail($data['photo_id']);
        if ($photo->user_id !== $user->id) {
            throw new InvalidArgumentException('Unauthorized photo reference.');
        }

        $frame = null;
        if (!empty($data['frame_id'])) {
            $frame = CardFrame::find($data['frame_id']);
        } elseif (!empty($data['frame_slug'])) {
            $frame = CardFrame::where('slug', $data['frame_slug'])->first();
        }

        if (!$frame) {
            $frame = CardFrame::where('slug', 'classic')->first() ?? CardFrame::firstOrFail();
        }

        $album = null;
        if (!empty($data['album_id'])) {
            $album = Album::where('user_id', $user->id)->find($data['album_id']);
        }

        $name = !empty($data['name']) ? $data['name'] : pathinfo($photo->original_name, PATHINFO_FILENAME);
        $isFavorite = !empty($data['is_favorite']);

        return $this->createAction->execute($user, $photo, $frame, $name, $album, $isFavorite);
    }

    /**
     * Batch import multiple Pokémon card images (Automatic Mode).
     *
     * @param  array<int>  $photoIds
     * @return array<Card>
     */
    public function batchCreateCards(User $user, array $photoIds, ?int $frameId = null, ?int $albumId = null): array
    {
        $frame = $frameId ? CardFrame::find($frameId) : CardFrame::where('slug', 'classic')->first();
        if (!$frame) {
            $frame = CardFrame::firstOrFail();
        }

        $album = $albumId ? Album::where('user_id', $user->id)->find($albumId) : null;
        $createdCards = [];

        foreach ($photoIds as $photoId) {
            $photo = Photo::where('user_id', $user->id)->find($photoId);
            if ($photo) {
                $createdCards[] = $this->createAction->execute(
                    $user,
                    $photo,
                    $frame,
                    pathinfo($photo->original_name, PATHINFO_FILENAME),
                    $album,
                    false
                );
            }
        }

        return $createdCards;
    }

    /**
     * Switch the decorative outer frame for a card.
     */
    public function switchFrame(Card $card, CardFrame $frame): Card
    {
        return $this->switchAction->execute($card, $frame);
    }

    /**
     * Toggle favorite status.
     */
    public function toggleFavorite(Card $card): bool
    {
        return $this->favoriteAction->execute($card);
    }

    /**
     * Update collection card label or album association.
     */
    public function updateCard(Card $card, array $data): Card
    {
        return DB::transaction(function () use ($card, $data) {
            if (isset($data['name'])) {
                $card->name = $data['name'];
            }
            if (isset($data['is_favorite'])) {
                $card->is_favorite = (bool) $data['is_favorite'];
            }
            if (isset($data['frame_id']) && $data['frame_id'] != $card->frame_id) {
                $newFrame = CardFrame::find($data['frame_id']);
                if ($newFrame) {
                    $this->switchFrame($card, $newFrame);
                }
            }

            $card->save();

            if (array_key_exists('album_id', $data)) {
                if ($data['album_id']) {
                    $album = Album::where('user_id', $card->user_id)->find($data['album_id']);
                    if ($album && !$album->cards()->where('cards.id', $card->id)->exists()) {
                        $maxOrder = $album->cards()->max('sort_order') ?? 0;
                        $album->cards()->attach($card->id, ['sort_order' => $maxOrder + 1]);
                    }
                }
            }

            return $card->fresh(['photo', 'frame', 'albums']);
        });
    }

    /**
     * Delete card and framed image, decrementing user storage.
     */
    public function deleteCard(Card $card): void
    {
        $user = $card->user;
        $framedPath = $card->framed_image_path ?: $card->render_path;

        if ($framedPath && Storage::disk('local')->exists($framedPath)) {
            $fileSize = Storage::disk('local')->size($framedPath);
            Storage::disk('local')->delete($framedPath);
            if ($user) {
                $user->decrement('storage_used_bytes', min($user->storage_used_bytes, $fileSize));
            }
        }

        $card->albums()->detach();
        $card->delete();
    }
}
