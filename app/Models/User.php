<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'username',
        'avatar_path',
        'storage_used_bytes',
        'storage_quota_bytes',
        'is_admin',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
            'storage_used_bytes' => 'integer',
            'storage_quota_bytes' => 'integer',
        ];
    }

    public function photos(): HasMany
    {
        return $this->hasMany(Photo::class);
    }

    public function cards(): HasMany
    {
        return $this->hasMany(Card::class);
    }

    public function albums(): HasMany
    {
        return $this->hasMany(Album::class);
    }

    public function sharedLinks(): HasMany
    {
        return $this->hasMany(SharedLink::class);
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Card::class)->where('is_favorite', true);
    }

    /**
     * Generate the next user-scoped card number (e.g. MDX-000001).
     */
    public function nextCardNumber(): string
    {
        $lastCard = $this->cards()
            ->orderByDesc('id')
            ->first();

        $nextSeq = 1;
        if ($lastCard && preg_match('/MDX-(\d+)/', $lastCard->card_number, $matches)) {
            $nextSeq = ((int) $matches[1]) + 1;
        }

        return sprintf('MDX-%06d', $nextSeq);
    }

    public function storageRemainingBytes(): int
    {
        return max(0, $this->storage_quota_bytes - $this->storage_used_bytes);
    }

    public function storagePercent(): float
    {
        if ($this->storage_quota_bytes <= 0) {
            return 100.0;
        }

        return min(100.0, round(($this->storage_used_bytes / $this->storage_quota_bytes) * 100, 1));
    }

    public function hasStorageFor(int $bytes): bool
    {
        return ($this->storage_used_bytes + $bytes) <= $this->storage_quota_bytes;
    }
}
