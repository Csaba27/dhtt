<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\HikeFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * Túra modell.
 */
class Hike extends Model implements HasMedia
{
    /**
     * @use HasFactory<HikeFactory>
     */
    use HasFactory, InteractsWithMedia;

    /**
     * Tömegesen hozzárendelhető attribútumok.
     *
     * @var list<string>
     */
    protected $fillable = [
        'hike_type_id', 'name', 'year', 'route',
        'track_link', 'distance', 'time_limit', 'elevation', 'number_start', 'number_end',
        'number_start_extra', 'number_end_extra',
    ];

    /**
     * A túrához tartozó események.
     *
     * @return BelongsToMany<Event, $this>
     */
    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class);
    }

    /**
     * A túrához tartozó túratípus.
     *
     * @return BelongsTo<HikeType, $this>
     */
    public function hikeType(): BelongsTo
    {
        return $this->belongsTo(HikeType::class);
    }

    /**
     * A túrán résztvevő személyek.
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
