<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Country extends Model
{
    protected $fillable = ['name', 'image'];

    protected $casts = [
        'name' => 'array',
    ];

    protected static function booted(): void
    {
        // deleted (nem deleting): FK miatt meghiúsult törlés ne vigye el a képeket
        static::deleted(function (Country $country) {
            Storage::disk('public')->deleteDirectory("countries/{$country->id}");
        });
    }

    public function cities(): HasMany
    {
        return $this->hasMany(City::class);
    }

    public function programs(): HasMany
    {
        return $this->hasMany(Program::class);
    }

    public function getTranslation(?string $locale = null): string
    {
        $locale ??= app()->getLocale();

        return ($this->name[$locale] ?? '') ?: ($this->name['hu'] ?? '');
    }

    public function imageUrl(): ?string
    {
        return $this->image ? Storage::url($this->image) : null;
    }
}
