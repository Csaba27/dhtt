<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\EventFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * Esemény modell.
 */
class Event extends Model implements HasMedia
{
    /**
     * @use HasFactory<EventFactory>
     */
    use HasFactory, InteractsWithMedia;

    /**
     * Tömegesen hozzárendelhető attribútumok.
     *
     * @var list<string>
     */
    protected $fillable = [
        'short_name', 'name', 'year', 'date', 'location',
        'entry_fee', 'discount1', 'discount2', 'registration_start', 'registration_end',
        'registration_discount_until', 'organizer_name', 'organizer_email', 'organizer_phone',
        'invitation', 'description', 'rules', 'status', 'active', 'show',
    ];

    /**
     * Eseményhez tartozó túrák.
     *
     * @return BelongsToMany<Hike, $this>
     */
    public function hikes(): BelongsToMany
    {
        return $this->belongsToMany(Hike::class);
    }

    /**
     * Eseményhez tartozó résztvevők.
     *
     * @return HasMany<Participant, $this>
     */
    public function participants(): HasMany
    {
        return $this->hasMany(Participant::class);
    }

    /**
     * Média konverziók regisztrálása.
     */
    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('small')
//            ->flip(FlipDirection::Both)
//            ->width(400);
//            ->height(266);
//            ->sharpen(10);
//             ->fit(FIT::Fill, 400, 266)
            ->crop(400, 266);
        //        ->background('white');

        $this->addMediaConversion('medium')
            ->width(768);
        //        ->height(400);
        //        ->sharpen(10);

        $this->addMediaConversion('large')
            ->width(1024);
        //        ->height(400)
        //        ->sharpen(10);
    }
}
