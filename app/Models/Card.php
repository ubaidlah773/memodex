<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Card extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'photo_id',
        'frame_id',
        'template_id',
        'template_version',
        'template_snapshot',
        'card_number',
        'name',
        'framed_image_path',
        'render_path',
        'hp',
        'type',
        'rarity',
        'skill_name',
        'skill_description',
        'flavor_text',
        'custom_data',
        'is_favorite',
    ];

    protected $casts = [
        'template_snapshot' => 'array',
        'custom_data' => 'array',
        'is_favorite' => 'boolean',
        'hp' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function photo(): BelongsTo
    {
        return $this->belongsTo(Photo::class);
    }

    public function frame(): BelongsTo
    {
        return $this->belongsTo(CardFrame::class, 'frame_id');
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(CardTemplate::class, 'template_id');
    }

    public function albums(): BelongsToMany
    {
        return $this->belongsToMany(Album::class, 'album_cards')
            ->withPivot('sort_order')
            ->withTimestamps();
    }

    public function getDisplayNameAttribute(): string
    {
        if (!empty($this->name)) {
            return $this->name;
        }

        if ($this->photo && !empty($this->photo->original_name)) {
            return pathinfo($this->photo->original_name, PATHINFO_FILENAME);
        }

        return $this->card_number;
    }

    public function effectiveFramedPath(): ?string
    {
        return $this->framed_image_path ?: $this->render_path;
    }
}
