<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Card;
use App\Models\CardFrame;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PokedexController extends Controller
{
    public function index(Request $request): View
    {
        $user = Auth::user();

        $query = $user->cards()->with(['photo', 'frame', 'albums']);

        // Search by name or card number
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('card_number', 'like', "%{$search}%");
            });
        }

        // Frame Filter
        if ($frameId = $request->input('frame_id')) {
            $query->where('frame_id', $frameId);
        }

        // Album Filter
        if ($albumId = $request->input('album_id')) {
            $query->whereHas('albums', function ($q) use ($albumId) {
                $q->where('albums.id', $albumId);
            });
        }

        // Date Filter
        if ($dateFilter = $request->input('date')) {
            if ($dateFilter === 'today') {
                $query->whereDate('created_at', today());
            } elseif ($dateFilter === 'this_week') {
                $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
            } elseif ($dateFilter === 'this_month') {
                $query->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
            }
        }

        // Sorting
        $sort = $request->input('sort', 'newest');
        match ($sort) {
            'oldest' => $query->oldest('id'),
            'name' => $query->orderBy('name', 'asc'),
            'number' => $query->orderBy('card_number', 'asc'),
            default => $query->latest('id'),
        };

        $viewMode = $request->input('view', 'grid'); // 'grid' or 'list'
        $cards = $query->paginate(12)->withQueryString();

        $frames = CardFrame::where('is_active', true)->orderBy('id')->get();
        $albums = $user->albums()->orderBy('name')->get();

        return view('pokedex.index', compact(
            'cards',
            'frames',
            'albums',
            'search',
            'frameId',
            'albumId',
            'dateFilter',
            'sort',
            'viewMode'
        ));
    }

    public function show(Card $card): View
    {
        if ($card->user_id !== Auth::id()) {
            abort(403);
        }

        $card->load(['photo', 'frame', 'albums']);
        $frames = CardFrame::where('is_active', true)->orderBy('id')->get();
        $userAlbums = Auth::user()->albums()->orderBy('name')->get();

        return view('pokedex.show', compact('card', 'frames', 'userAlbums'));
    }
}
