<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Album extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'cover_path',
        'visibility',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function cards(): BelongsToMany
    {
        return $this->belongsToMany(Card::class, 'album_cards')
            ->withPivot('sort_order')
            ->withTimestamps()
            ->orderByPivot('sort_order');
    }

    public function sharedLinks(): HasMany
    {
        return $this->hasMany(SharedLink::class);
    }

    public function activeSharedLink(): HasOne
    {
        return $this->hasOne(SharedLink::class)
            ->where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->latestOfMany();
    }

    public function isPublic(): bool
    {
        return $this->visibility === 'public-link';
    }
}
