<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Túratípus modell.
 */
class HikeType extends Model
{
    /**
     * Tömegesen hozzárendelhető attribútumok.
     *
     * @var list<string>
     */
    protected $fillable = ['name', 'description'];

    /**
     * Túratípushoz tartozó túrák.
     *
     * @return HasMany<Hike, $this>
     */
    public function hikes(): HasMany
    {
        return $this->hasMany(Hike::class);
    }
}
