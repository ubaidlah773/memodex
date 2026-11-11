<?php

namespace App\Actions;

use App\Models\Card;

class ToggleCardFavorite
{
    /**
     * Toggle card favorite status.
     */
    public function execute(Card $card): bool
    {
        $card->is_favorite = !$card->is_favorite;
        $card->save();

        return $card->is_favorite;
    }
}
