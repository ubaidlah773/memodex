<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Photo;
use App\Models\SharedLink;
use App\Services\StorageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class MediaController extends Controller
{
    public function __construct(
        protected StorageService $storageService
    ) {}

    /**
     * Stream private original uploaded photo.
     */
    public function photoOriginal(Request $request, Photo $photo): BinaryFileResponse
    {
        if (Auth::id() !== $photo->user_id) {
            abort(403, 'Unauthorized access to private photo.');
        }

        return $this->storageService->servePrivateFile($photo->original_path);
    }

    /**
     * Stream private photo thumbnail.
     */
    public function photoThumbnail(Request $request, Photo $photo): BinaryFileResponse
    {
        if (Auth::id() !== $photo->user_id) {
            abort(403, 'Unauthorized access to private thumbnail.');
        }

        $path = $photo->thumbnail_path ?: $photo->original_path;
        return $this->storageService->servePrivateFile($path);
    }

    /**
     * Stream the framed card composite PNG for display.
     */
    public function cardFramed(Request $request, Card $card): BinaryFileResponse
    {
        // 1. Owner access
        if (Auth::id() === $card->user_id) {
            $path = $card->effectiveFramedPath();
            return $this->storageService->servePrivateFile($path);
        }

        // 2. Token-authorized access (shared album)
        $token = $request->query('token');
        if ($token) {
            $sharedLink = SharedLink::where('token', $token)->first();
            if ($sharedLink && $sharedLink->isValid()) {
                $isCardInSharedAlbum = $sharedLink->album->cards()->where('cards.id', $card->id)->exists();
                if ($isCardInSharedAlbum) {
                    $path = $card->effectiveFramedPath();
                    return $this->storageService->servePrivateFile($path);
                }
            }
        }

        abort(403, 'Unauthorized access to card.');
    }

    /**
     * Stream the 100% untouched original card image for comparison tab in card viewer.
     */
    public function cardOriginal(Request $request, Card $card): BinaryFileResponse
    {
        // 1. Owner access
        if (Auth::id() === $card->user_id) {
            if (!$card->photo || !$card->photo->original_path) {
                abort(404, 'Original photo not found.');
            }
            return $this->storageService->servePrivateFile($card->photo->original_path);
        }

        // 2. Token-authorized access
        $token = $request->query('token');
        if ($token) {
            $sharedLink = SharedLink::where('token', $token)->first();
            if ($sharedLink && $sharedLink->isValid()) {
                $isCardInSharedAlbum = $sharedLink->album->cards()->where('cards.id', $card->id)->exists();
                if ($isCardInSharedAlbum && $card->photo) {
                    return $this->storageService->servePrivateFile($card->photo->original_path);
                }
            }
        }

        abort(403, 'Unauthorized access to original photo.');
    }

    /**
     * Download the framed card composite as a PNG attachment.
     */
    public function downloadFramed(Request $request, Card $card): BinaryFileResponse
    {
        if (Auth::id() !== $card->user_id) {
            abort(403, 'Unauthorized download.');
        }

        $sanitizedName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $card->name ?: 'pokemon_card');
        $filename = "{$card->card_number}_{$sanitizedName}_framed.png";

        return $this->storageService->servePrivateFile($card->effectiveFramedPath(), $filename);
    }

    /**
     * Download the 100% untouched original Pokémon card image file as attachment.
     */
    public function downloadOriginal(Request $request, Card $card): BinaryFileResponse
    {
        if (Auth::id() !== $card->user_id) {
            abort(403, 'Unauthorized download.');
        }

        $photo = $card->photo;
        if (!$photo || !$photo->original_path) {
            abort(404, 'Original photo not found.');
        }

        $extension = pathinfo($photo->original_name, PATHINFO_EXTENSION) ?: 'png';
        $sanitizedName = preg_replace('/[^A-Za-z0-9_\-]/', '_', pathinfo($photo->original_name, PATHINFO_FILENAME));
        $filename = "{$card->card_number}_{$sanitizedName}_original.{$extension}";

        return $this->storageService->servePrivateFile($photo->original_path, $filename);
    }

    /**
     * Legacy route aliases for backward compatibility.
     */
    public function cardRender(Request $request, Card $card): BinaryFileResponse
    {
        return $this->cardFramed($request, $card);
    }

    public function cardDownload(Request $request, Card $card): BinaryFileResponse
    {
        return $this->downloadFramed($request, $card);
    }
}
