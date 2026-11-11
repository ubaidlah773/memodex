<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Card;
use App\Models\CardFrame;
use App\Models\Photo;
use App\Services\CardRenderer;
use App\Services\CardService;
use App\Services\StorageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CardController extends Controller
{
    public function __construct(
        protected CardService $cardService,
        protected CardRenderer $cardRenderer,
        protected StorageService $storageService
    ) {}

    public function create(Request $request): View
    {
        $user = Auth::user();
        $photos = $user->photos()->latest()->get();
        $frames = CardFrame::where('is_active', true)->orderBy('id')->get();
        $albums = $user->albums()->orderBy('name')->get();

        $selectedPhotoId = $request->query('photo_id');
        $selectedPhoto = $selectedPhotoId ? $photos->firstWhere('id', $selectedPhotoId) : null;
        $mode = $request->query('mode', 'automatic'); // 'automatic' or 'manual'

        return view('cards.create', compact('photos', 'frames', 'albums', 'selectedPhoto', 'mode'));
    }

    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $user = Auth::user();

        // 1. Batch Upload (Automatic Mode)
        if ($request->hasFile('photos')) {
            $request->validate([
                'photos' => ['required', 'array', 'min:1'],
                'photos.*' => ['required', 'file', 'image', 'mimes:jpeg,png,webp', 'max:15360'],
                'frame_id' => ['nullable', 'exists:card_frames,id'],
                'album_id' => ['nullable', 'exists:albums,id'],
            ]);

            $storedPhotoIds = [];
            $errors = [];

            foreach ($request->file('photos') as $file) {
                try {
                    $photo = $this->storageService->storeUploadedPhoto($user, $file);
                    $storedPhotoIds[] = $photo->id;
                } catch (\Exception $e) {
                    $errors[] = $file->getClientOriginalName() . ': ' . $e->getMessage();
                }
            }

            if (empty($storedPhotoIds)) {
                return back()->with('error', 'No photos could be uploaded: ' . implode(', ', $errors));
            }

            $cards = $this->cardService->batchCreateCards(
                $user,
                $storedPhotoIds,
                $request->input('frame_id'),
                $request->input('album_id')
            );

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'count' => count($cards),
                    'cards' => $cards,
                    'redirect' => route('pokedex.index'),
                ]);
            }

            $count = count($cards);
            return redirect()->route('pokedex.index')->with('success', "{$count} Pokémon card(s) framed and added to your Pokédex!");
        }

        // 2. Single Card Upload or Selection (Manual Mode)
        // If an uploaded file is provided directly in manual mode:
        $photoId = $request->input('photo_id');
        if ($request->hasFile('photo_file')) {
            $request->validate([
                'photo_file' => ['required', 'file', 'image', 'mimes:jpeg,png,webp', 'max:15360'],
            ]);

            try {
                $photo = $this->storageService->storeUploadedPhoto($user, $request->file('photo_file'));
                $photoId = $photo->id;
            } catch (\Exception $e) {
                return back()->withInput()->with('error', 'Photo upload failed: ' . $e->getMessage());
            }
        }

        $request->merge(['photo_id' => $photoId]);

        $validated = $request->validate([
            'photo_id' => ['required', 'exists:photos,id'],
            'frame_id' => ['nullable', 'exists:card_frames,id'],
            'template_id' => ['nullable', 'exists:card_templates,id'],
            'name' => ['nullable', 'string', 'max:80'],
            'hp' => ['nullable', 'integer', 'min:10', 'max:999'],
            'type' => ['nullable', 'string', 'max:30'],
            'rarity' => ['nullable', 'string', 'max:30'],
            'skill_name' => ['nullable', 'string', 'max:50'],
            'skill_description' => ['nullable', 'string', 'max:250'],
            'flavor_text' => ['nullable', 'string', 'max:180'],
            'album_id' => ['nullable', 'exists:albums,id'],
            'crop' => ['nullable', 'array'],
        ]);

        try {
            $card = $this->cardService->createCard($user, $validated);

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'card' => $card,
                    'redirect' => route('pokedex.show', $card),
                ]);
            }

            return redirect()->route('pokedex.show', $card)->with('success', 'Card ' . $card->card_number . ' successfully archived into your Pokédex!');
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'error' => $e->getMessage()], 422);
            }

            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function edit(Card $card): View
    {
        if ($card->user_id !== Auth::id()) {
            abort(403);
        }

        $user = Auth::user();
        $frames = CardFrame::where('is_active', true)->orderBy('id')->get();
        $albums = $user->albums()->orderBy('name')->get();

        return view('cards.edit', compact('card', 'frames', 'albums'));
    }

    public function update(Request $request, Card $card): RedirectResponse|JsonResponse
    {
        if ($card->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => ['nullable', 'string', 'max:80'],
            'frame_id' => ['nullable', 'exists:card_frames,id'],
            'album_id' => ['nullable', 'exists:albums,id'],
            'is_favorite' => ['nullable', 'boolean'],
            'hp' => ['nullable', 'integer', 'min:10', 'max:999'],
            'type' => ['nullable', 'string', 'max:30'],
            'rarity' => ['nullable', 'string', 'max:30'],
            'skill_name' => ['nullable', 'string', 'max:50'],
            'skill_description' => ['nullable', 'string', 'max:250'],
            'flavor_text' => ['nullable', 'string', 'max:180'],
        ]);

        $card = $this->cardService->updateCard($card, $validated);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'card' => $card,
            ]);
        }

        return redirect()->route('pokedex.show', $card)->with('success', 'Card updated successfully.');
    }

    public function switchFrame(Request $request, Card $card): RedirectResponse|JsonResponse
    {
        if ($card->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'frame_id' => ['required', 'exists:card_frames,id'],
        ]);

        $frame = CardFrame::findOrFail($validated['frame_id']);
        $card = $this->cardService->switchFrame($card, $frame);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'card' => $card,
                'frame' => $frame,
                'framed_url' => route('media.card.framed', $card) . '?v=' . time(),
            ]);
        }

        return redirect()->route('pokedex.show', $card)->with('success', "Frame changed to {$frame->name}.");
    }

    public function destroy(Card $card): RedirectResponse
    {
        if ($card->user_id !== Auth::id()) {
            abort(403);
        }

        $this->cardService->deleteCard($card);

        return redirect()->route('pokedex.index')->with('success', 'Card deleted successfully.');
    }

    public function render(Card $card): JsonResponse
    {
        if ($card->user_id !== Auth::id()) {
            abort(403);
        }

        $path = $card->effectiveFramedPath();

        return response()->json([
            'success' => true,
            'render_url' => route('media.card.framed', $card) . '?v=' . time(),
        ]);
    }
}
