<?php

namespace App\Policies;

use App\Models\Album;
use App\Models\Card;
use App\Models\User;

class AlbumPolicy
{
    public function view(User $user, Album $album): bool
    {
        return $user->id === $album->user_id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Album $album): bool
    {
        return $user->id === $album->user_id;
    }

    public function delete(User $user, Album $album): bool
    {
        return $user->id === $album->user_id;
    }

    public function manageCards(User $user, Album $album, ?Card $card = null): bool
    {
        if ($user->id !== $album->user_id) {
            return false;
        }

        if ($card !== null && $card->user_id !== $user->id) {
            return false;
        }

        return true;
    }
}
