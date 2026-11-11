<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\SharedLink;
use App\Services\AlbumService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ShareController extends Controller
{
    public function __construct(
        protected AlbumService $albumService
    ) {}

    public function share(Request $request, Album $album): RedirectResponse|JsonResponse
    {
        if ($album->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'expires_at' => ['nullable', 'date', 'after:now'],
        ]);

        $sharedLink = $this->albumService->createOrGetSharedLink($album, $validated['expires_at'] ?? null);
        $shareUrl = route('shared.show', $sharedLink->token);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'token' => $sharedLink->token,
                'url' => $shareUrl,
            ]);
        }

        return back()->with('success', 'Public share link generated. Anyone with the link can view this binder.');
    }

    public function revoke(Album $album, SharedLink $sharedLink): RedirectResponse|JsonResponse
    {
        if ($album->user_id !== Auth::id() || $sharedLink->album_id !== $album->id) {
            abort(403);
        }

        $this->albumService->revokeSharedLink($sharedLink);

        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Share link has been revoked.');
    }

    /**
     * Public read-only endpoint for shared album.
     */
    public function showPublic(string $token): View
    {
        $sharedLink = SharedLink::where('token', $token)->first();

        if (!$sharedLink || !$sharedLink->isValid()) {
            abort(404, 'This shared album link is invalid or has expired.');
        }

        $album = $sharedLink->album;
        $album->load(['cards' => function ($q) {
            $q->with('template');
        }]);

        return view('sharing.show', compact('album', 'sharedLink'));
    }
}
