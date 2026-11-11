<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use App\Services\StorageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PhotoController extends Controller
{
    public function __construct(
        protected StorageService $storageService
    ) {}

    public function index(Request $request): View
    {
        $user = Auth::user();

        $query = $user->photos()->withCount('cards')->latest();

        if ($filter = $request->input('filter')) {
            if ($filter === 'used') {
                $query->has('cards');
            } elseif ($filter === 'unused') {
                $query->doesntHave('cards');
            }
        }

        $photos = $query->paginate(18)->withQueryString();

        return view('photos.index', compact('photos', 'filter'));
    }

    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $request->validate([
            'photos' => ['required', 'array', 'min:1'],
            'photos.*' => ['required', 'file', 'image', 'mimes:jpeg,png,webp', 'max:15360'], // 15MB limit
        ]);

        $user = Auth::user();
        $stored = [];
        $errors = [];

        foreach ($request->file('photos') as $file) {
            try {
                $photo = $this->storageService->storeUploadedPhoto($user, $file);
                $stored[] = [
                    'id' => $photo->id,
                    'original_name' => $photo->original_name,
                    'thumbnail_url' => route('media.photo.thumbnail', $photo),
                ];
            } catch (\Exception $e) {
                $errors[] = $file->getClientOriginalName() . ': ' . $e->getMessage();
            }
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => count($stored) > 0,
                'stored' => $stored,
                'errors' => $errors,
                'message' => count($stored) . ' photo(s) uploaded successfully.',
            ], count($errors) && !count($stored) ? 422 : 200);
        }

        if (count($errors)) {
            return back()->with('warning', implode('<br>', $errors));
        }

        return back()->with('success', count($stored) . ' photo(s) uploaded successfully.');
    }

    public function destroy(Photo $photo): RedirectResponse
    {
        if ($photo->user_id !== Auth::id()) {
            abort(403);
        }

        // Check if referenced by cards
        if ($photo->isUsedInCards()) {
            return back()->with('error', 'Cannot delete photo: it is currently used by ' . $photo->cards()->count() . ' card(s). Delete or reassign those cards first.');
        }

        $this->storageService->deletePhoto($photo);

        return back()->with('success', 'Photo deleted successfully.');
    }
}
