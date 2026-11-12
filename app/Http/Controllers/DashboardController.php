<?php

namespace App\Http\Controllers;

use App\Models\CardFrame;
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
        $totalPhotos = $user->photos()->count();
        $storageUsed = $user->storage_used_bytes;
        $storageQuota = $user->storage_quota_bytes;
        $storagePercent = $user->storagePercent();

        $activeFramesCount = CardFrame::where('is_active', true)->count();
        $framesCollectedCount = $user->cards()->whereNotNull('frame_id')->distinct('frame_id')->count('frame_id');

        $featuredCard = $user->cards()
            ->with(['photo', 'frame', 'albums'])
            ->where('is_favorite', true)
            ->latest()
            ->first() ?? $user->cards()->with(['photo', 'frame', 'albums'])->latest()->first();

        $recentCards = $user->cards()
            ->with(['photo', 'frame'])
            ->latest()
            ->take(5)
            ->get();

        $recentAlbums = $user->albums()
            ->withCount('cards')
            ->with(['cards' => function ($q) {
                $q->with(['photo', 'frame'])->take(3);
            }])
            ->latest('updated_at')
            ->take(4)
            ->get();

        return view('dashboard.index', compact(
            'user',
            'totalCards',
            'totalAlbums',
            'totalFavorites',
            'totalPhotos',
            'storageUsed',
            'storageQuota',
            'storagePercent',
            'activeFramesCount',
            'framesCollectedCount',
            'featuredCard',
            'recentCards',
            'recentAlbums'
        ));
    }
}
