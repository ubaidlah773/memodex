<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CardTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'rarity',
        'config',
        'preview_path',
        'version',
        'is_active',
    ];

    protected $casts = [
        'config' => 'array',
        'is_active' => 'boolean',
    ];

    public function cards(): HasMany
    {
        return $this->hasMany(Card::class, 'template_id');
    }
}
