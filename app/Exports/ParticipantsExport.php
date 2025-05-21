<?php

declare(strict_types=1);

namespace App\Exports;

use App\Models\Event;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

/**
 * Excel exportálás több munkalappal a résztvevők adataihoz.
 */
class ParticipantsExport implements WithMultipleSheets
{
    use Exportable;

    protected ?Event $event;

    /**
     * Létrehozza az új exportálási példányt.
     *
     * @param  int  $eventId  Az esemény azonosítója
     */
    public function __construct(int $eventId)
    {
        $this->event = Event::with('hikes')->find($eventId);
    }

    /**
     * Létrehozza a munkalapokat az egyes túrákhoz.
     *
     * @return array<ParticipantsSheet>
     */
    public function sheets(): array
    {
        $sheets = [];
        $event = $this->event;
        $hikes = $event->hikes->sortBy('name');

        foreach ($hikes as $hike) {
            $sheets[] = new ParticipantsSheet($event, $hike->id, $hike->name);
        }

        return $sheets;
    }
}
