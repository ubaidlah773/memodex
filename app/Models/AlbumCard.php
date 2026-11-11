<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class AlbumCard extends Pivot
{
    protected $table = 'album_cards';

    protected $fillable = [
        'album_id',
        'card_id',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];
}
