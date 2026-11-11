<?php

use App\Http\Controllers\AlbumController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\PokedexController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ShareController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// Public shared album view (Token-scoped and read-only)
Route::get('/shared/{token}', [ShareController::class, 'showPublic'])->name('shared.show');

// Public/Private token-authorized card render for shared views
Route::get('/media/cards/{card}/render', [MediaController::class, 'cardRender'])->name('media.card.render');

// Authenticated private routes
Route::middleware(['auth'])->group(function () {
    // Media streaming (private storage)
    Route::get('/media/photos/{photo}/original', [MediaController::class, 'photoOriginal'])->name('media.photo.original');
    Route::get('/media/photos/{photo}/thumbnail', [MediaController::class, 'photoThumbnail'])->name('media.photo.thumbnail');
    Route::get('/media/cards/{card}/framed', [MediaController::class, 'cardFramed'])->name('media.card.framed');
    Route::get('/media/cards/{card}/original', [MediaController::class, 'cardOriginal'])->name('media.card.original');
    Route::get('/media/cards/{card}/download', [MediaController::class, 'cardDownload'])->name('media.card.download');
    Route::get('/media/cards/{card}/download/framed', [MediaController::class, 'downloadFramed'])->name('media.card.download.framed');
    Route::get('/media/cards/{card}/download/original', [MediaController::class, 'downloadOriginal'])->name('media.card.download.original');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Pokédex collection
    Route::get('/pokedex', [PokedexController::class, 'index'])->name('pokedex.index');
    Route::get('/pokedex/{card}', [PokedexController::class, 'show'])->name('pokedex.show');

    // Photo Library
    Route::get('/photos', [PhotoController::class, 'index'])->name('photos.index');
    Route::post('/photos', [PhotoController::class, 'store'])->name('photos.store');
    Route::delete('/photos/{photo}', [PhotoController::class, 'destroy'])->name('photos.destroy');

    // Cards creation & editing
    Route::get('/cards/create', [CardController::class, 'create'])->name('cards.create');
    Route::post('/cards', [CardController::class, 'store'])->name('cards.store');
    Route::get('/cards/{card}/edit', [CardController::class, 'edit'])->name('cards.edit');
    Route::put('/cards/{card}', [CardController::class, 'update'])->name('cards.update');
    Route::post('/cards/{card}/frame', [CardController::class, 'switchFrame'])->name('cards.switch-frame');
    Route::delete('/cards/{card}', [CardController::class, 'destroy'])->name('cards.destroy');
    Route::post('/cards/{card}/render', [CardController::class, 'render'])->name('cards.render');

    // Binders / Albums
    Route::get('/albums', [AlbumController::class, 'index'])->name('albums.index');
    Route::post('/albums', [AlbumController::class, 'store'])->name('albums.store');
    Route::get('/albums/{album}', [AlbumController::class, 'show'])->name('albums.show');
    Route::put('/albums/{album}', [AlbumController::class, 'update'])->name('albums.update');
    Route::delete('/albums/{album}', [AlbumController::class, 'destroy'])->name('albums.destroy');
    Route::post('/albums/{album}/cards', [AlbumController::class, 'addCard'])->name('albums.cards.add');
    Route::delete('/albums/{album}/cards/{card}', [AlbumController::class, 'removeCard'])->name('albums.cards.remove');
    Route::post('/albums/{album}/reorder', [AlbumController::class, 'reorder'])->name('albums.reorder');
    Route::post('/albums/{album}/share', [ShareController::class, 'share'])->name('albums.share');
    Route::delete('/albums/{album}/share/{sharedLink}', [ShareController::class, 'revoke'])->name('albums.share.revoke');

    // Favorites
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/favorites/{card}/toggle', [FavoriteController::class, 'toggle'])->name('favorites.toggle');

    // Profile & Settings
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings/profile', [SettingsController::class, 'updateProfile'])->name('settings.profile');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin Panel (Protected by EnsureUserIsAdmin)
    Route::prefix('admin')->middleware(['admin'])->as('admin.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/frames', [\App\Http\Controllers\Admin\AdminFrameController::class, 'index'])->name('frames.index');
        Route::get('/frames/{frame}/edit', [\App\Http\Controllers\Admin\AdminFrameController::class, 'edit'])->name('frames.edit');
        Route::put('/frames/{frame}', [\App\Http\Controllers\Admin\AdminFrameController::class, 'update'])->name('frames.update');
        Route::post('/frames/{frame}/toggle', [\App\Http\Controllers\Admin\AdminFrameController::class, 'toggle'])->name('frames.toggle');
        Route::get('/users', [\App\Http\Controllers\Admin\AdminUserController::class, 'index'])->name('users.index');
        Route::post('/users/{user}/toggle-admin', [\App\Http\Controllers\Admin\AdminUserController::class, 'toggleAdmin'])->name('users.toggle-admin');
    });
});

require __DIR__.'/auth.php';
