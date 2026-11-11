<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function index(Request $request): View
    {
        $user = Auth::user();
        $totalPhotos = $user->photos()->count();
        $totalCards = $user->cards()->count();
        $totalAlbums = $user->albums()->count();

        return view('settings.index', compact('user', 'totalPhotos', 'totalCards', 'totalAlbums'));
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['nullable', 'string', 'max:50', 'alpha_dash', 'unique:users,username,' . $user->id],
        ]);

        $user->update($validated);

        return back()->with('success', 'Profile updated successfully.');
    }
}
