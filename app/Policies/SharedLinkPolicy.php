<?php

namespace App\Policies;

use App\Models\SharedLink;
use App\Models\User;

class SharedLinkPolicy
{
    public function view(User $user, SharedLink $sharedLink): bool
    {
        return $user->id === $sharedLink->user_id;
    }

    public function delete(User $user, SharedLink $sharedLink): bool
    {
        return $user->id === $sharedLink->user_id;
    }
}
