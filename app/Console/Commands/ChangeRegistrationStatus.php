<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ChangeRegistrationStatus extends Command
{
    /**
     * A parancs neve és szignatúrája
     *
     * @var string
     */
    protected $signature = 'registration:status';

    /**
     * A parancs leírása
     *
     * @var string
     */
    protected $description = 'Frissíti az esemény regisztrációs állapotát az aktuális dátum alapján.
Időpontok szerint módosítja az állapotát:
1 - Kedvezményes regisztráció
2 - Teljes árú regisztráció
3 - Regisztráció lezárva automatikusan
4 - Ha az esemény már le lett manuálisan zárva, nem történik módosítás.';

    /**
     * A parancs végrehajtása
     *
     * Ellenőrzi az aktív esemény regisztrációs időszakát és ennek megfelelően
     * frissíti a regisztrációs státuszt. Csak akkor módosítja az állapotot,
     * ha az esemény nincs manuálisan lezárva (státusz != 4).
     */
    public function handle(): void
    {
        $now = Carbon::now();
        $event = Event::where('active', 1)->first();

        // Ha nincs lezarva az esemeny
        if ($event && $event->status != 4) {
            if ($now->between($event->registration_start, $event->registration_discount_until)) {
                $event->status = 1; // Kedvezmenyes regisztracio
            } elseif ($now->gt($event->registration_discount_until) && $now->lt($event->registration_end)) {
                $event->status = 2; // Nincs kedvezmeny
            } elseif ($now->gt($event->registration_end)) {
                $event->status = 3; // Regisztracio lezarva
            }
            $event->save();
        }

        $this->info('Registration status updated.');
    }
}
