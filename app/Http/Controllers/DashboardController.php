<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = Auth::user();

        $totalCards = $user->cards()->count();
        $totalAlbums = $user->albums()->count();
        $totalFavorites = $user->favorites()->count();
        $storageUsed = $user->storage_used_bytes;
        $storageQuota = $user->storage_quota_bytes;
        $storagePercent = $user->storagePercent();

        $featuredCard = $user->cards()
            ->with(['photo', 'frame'])
            ->where('is_favorite', true)
            ->latest()
            ->first() ?? $user->cards()->with(['photo', 'frame'])->latest()->first();

        $recentCards = $user->cards()
            ->with(['photo', 'frame'])
            ->latest()
            ->take(6)
            ->get();

        $recentAlbums = $user->albums()
            ->withCount('cards')
            ->latest('updated_at')
            ->take(4)
            ->get();

        return view('dashboard.index', compact(
            'user',
            'totalCards',
            'totalAlbums',
            'totalFavorites',
            'storageUsed',
            'storageQuota',
            'storagePercent',
            'featuredCard',
            'recentCards',
            'recentAlbums'
        ));
    }
}
