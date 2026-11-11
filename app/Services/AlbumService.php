<?php

namespace App\Services;

use App\Models\Album;
use App\Models\Card;
use App\Models\SharedLink;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AlbumService
{
    public function createAlbum(User $user, array $data): Album
    {
        return Album::create([
            'user_id' => $user->id,
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'cover_path' => $data['cover_path'] ?? null,
            'visibility' => $data['visibility'] ?? 'private',
        ]);
    }

    public function updateAlbum(Album $album, array $data): Album
    {
        $album->update([
            'name' => $data['name'] ?? $album->name,
            'description' => $data['description'] ?? $album->description,
            'cover_path' => $data['cover_path'] ?? $album->cover_path,
            'visibility' => $data['visibility'] ?? $album->visibility,
        ]);

        return $album;
    }

    public function deleteAlbum(Album $album): void
    {
        $album->cards()->detach();
        $album->sharedLinks()->delete();
        $album->delete();
    }

    public function addCardToAlbum(Album $album, Card $card): void
    {
        if ($album->cards()->where('card_id', $card->id)->exists()) {
            return;
        }

        $maxOrder = $album->cards()->max('sort_order') ?? 0;
        $album->cards()->attach($card->id, ['sort_order' => $maxOrder + 1]);
    }

    public function removeCardFromAlbum(Album $album, Card $card): void
    {
        $album->cards()->detach($card->id);
    }

    public function reorderCards(Album $album, array $cardIds): void
    {
        DB::transaction(function () use ($album, $cardIds) {
            foreach ($cardIds as $order => $cardId) {
                $album->cards()->updateExistingPivot($cardId, ['sort_order' => $order + 1]);
            }
        });
    }

    public function createOrGetSharedLink(Album $album, ?string $expiresAt = null): SharedLink
    {
        // Deactivate previous active links if renewing
        $album->sharedLinks()->update(['is_active' => false]);

        $album->update(['visibility' => 'public-link']);

        return SharedLink::create([
            'user_id' => $album->user_id,
            'album_id' => $album->id,
            'token' => SharedLink::generateUniqueToken(),
            'expires_at' => $expiresAt ? now()->parse($expiresAt) : null,
            'is_active' => true,
        ]);
    }

    public function revokeSharedLink(SharedLink $sharedLink): void
    {
        $sharedLink->update(['is_active' => false]);
    }
}
