<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CardFrame extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'preview_path',
        'frame_asset_path',
        'config',
        'is_active',
    ];

    protected $casts = [
        'config' => 'array',
        'is_active' => 'boolean',
    ];

    public function cards(): HasMany
    {
        return $this->hasMany(Card::class, 'frame_id');
    }
}
