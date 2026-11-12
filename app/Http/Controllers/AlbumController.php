<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Card;
use App\Services\AlbumService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AlbumController extends Controller
{
    public function __construct(
        protected AlbumService $albumService
    ) {}

    public function index(Request $request): View
    {
        $user = Auth::user();
        $albums = $user->albums()
            ->withCount('cards')
            ->with(['cards' => function ($q) {
                $q->with(['photo', 'frame'])->take(4);
            }])
            ->latest('updated_at')
            ->paginate(12);

        return view('albums.index', compact('albums'));
    }

    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:80'],
            'description' => ['nullable', 'string', 'max:300'],
            'visibility' => ['nullable', 'in:private,public-link'],
        ]);

        $album = $this->albumService->createAlbum(Auth::user(), $validated);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'album' => $album]);
        }

        return redirect()->route('albums.show', $album)->with('success', 'Binder "' . $album->name . '" created.');
    }

    public function show(Album $album): View
    {
        if ($album->user_id !== Auth::id()) {
            abort(403);
        }

        $album->load(['cards' => function ($q) {
            $q->with(['photo', 'frame', 'template']);
        }, 'activeSharedLink']);

        // Cards available to add that aren't already in this album
        $existingCardIds = $album->cards->pluck('id')->toArray();
        $availableCards = Auth::user()->cards()
            ->whereNotIn('id', $existingCardIds)
            ->with(['photo', 'frame', 'template'])
            ->latest()
            ->get();

        return view('albums.show', compact('album', 'availableCards'));
    }

    public function update(Request $request, Album $album): RedirectResponse|JsonResponse
    {
        if ($album->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:80'],
            'description' => ['nullable', 'string', 'max:300'],
            'visibility' => ['nullable', 'in:private,public-link'],
        ]);

        $album = $this->albumService->updateAlbum($album, $validated);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'album' => $album]);
        }

        return back()->with('success', 'Binder updated.');
    }

    public function destroy(Album $album): RedirectResponse
    {
        if ($album->user_id !== Auth::id()) {
            abort(403);
        }

        $this->albumService->deleteAlbum($album);

        return redirect()->route('albums.index')->with('success', 'Binder deleted successfully.');
    }

    public function addCard(Request $request, Album $album): RedirectResponse|JsonResponse
    {
        if ($album->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'card_id' => ['required', 'exists:cards,id'],
        ]);

        $card = Card::where('user_id', Auth::id())->findOrFail($validated['card_id']);
        $this->albumService->addCardToAlbum($album, $card);

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Card added to binder.');
    }

    public function removeCard(Album $album, Card $card): RedirectResponse|JsonResponse
    {
        if ($album->user_id !== Auth::id()) {
            abort(403);
        }

        $this->albumService->removeCardFromAlbum($album, $card);

        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Card removed from binder.');
    }

    public function reorder(Request $request, Album $album): JsonResponse
    {
        if ($album->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'card_ids' => ['required', 'array'],
            'card_ids.*' => ['integer', 'exists:cards,id'],
        ]);

        $this->albumService->reorderCards($album, $validated['card_ids']);

        return response()->json(['success' => true]);
    }
}
