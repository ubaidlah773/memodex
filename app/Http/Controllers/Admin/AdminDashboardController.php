<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Album;
use App\Models\Card;
use App\Models\CardFrame;
use App\Models\Photo;
use App\Models\User;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index(): View
    {
        $totalUsers = User::count();
        $totalCards = Card::count();
        $totalPhotos = Photo::count();
        $totalAlbums = Album::count();
        $totalFrames = CardFrame::count();
        $activeFrames = CardFrame::where('is_active', true)->count();
        $totalStorageBytes = User::sum('storage_used_bytes');

        $recentUsers = User::latest()->take(5)->get();
        $recentCards = Card::with(['user', 'frame', 'photo'])->latest()->take(6)->get();
        $frames = CardFrame::withCount('cards')->orderBy('id')->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalCards',
            'totalPhotos',
            'totalAlbums',
            'totalFrames',
            'activeFrames',
            'totalStorageBytes',
            'recentUsers',
            'recentCards',
            'frames'
        ));
    }
}
