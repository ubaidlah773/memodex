<?php

namespace App\Http\Controllers;

use App\Models\Card;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class FavoriteController extends Controller
{
    public function index(Request $request): View
    {
        $cards = Auth::user()->cards()
            ->where('is_favorite', true)
            ->with(['photo', 'template'])
            ->latest()
            ->paginate(12);

        return view('favorites.index', compact('cards'));
    }

    public function toggle(Card $card): RedirectResponse|JsonResponse
    {
        if ($card->user_id !== Auth::id()) {
            abort(403);
        }

        $card->update(['is_favorite' => !$card->is_favorite]);

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'is_favorite' => $card->is_favorite,
            ]);
        }

        $msg = $card->is_favorite ? 'Added to favorites.' : 'Removed from favorites.';
        return back()->with('success', $msg);
    }
}
