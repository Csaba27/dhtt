<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\ParticipantFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Résztvevő model.
 *
 * @property-read bool $is_member
 * @property string|int|null $association_id
 */
class Participant extends Model
{
    /**
     * @use HasFactory<ParticipantFactory>
     */
    use HasFactory;

    /**
     * Tömegesen hozzárendelhető attribútumok.
     *
     * @var list<string>
     */
    protected $fillable = [
        'event_id', 'hike_id', 'association_id', 'name', 'city', 'is_student', 'age', 'notes', 'status',
        'phone', 'number', 'start_time', 'finish_time', 'completion_time', 'tshirt', 'entry_fee',
    ];

    /**
     * Résztvevőhöz tartozó esemény.
     *
     * @return BelongsTo<Event, $this>
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Résztvevőhöz tartozó túra.
     *
     * @return BelongsTo<Hike, $this>
     */
    public function hike(): BelongsTo
    {
        return $this->belongsTo(Hike::class);
    }

    /**
     * Résztvevőhöz tartozó egyesület.
     *
     * @return BelongsTo<Association, $this>
     */
    public function association(): BelongsTo
    {
        return $this->belongsTo(Association::class);
    }

    /**
     * Ellenőrzi, hogy a résztvevő egyesületi tag-e.
     *
     * @return Attribute<bool, bool>
     */
    protected function isMember(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->association_id !== null
        );
    }
}
