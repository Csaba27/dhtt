<?php

declare(strict_types=1);

namespace App\Exports;

use App\Models\Event;
use App\Models\Participant;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class ParticipantsSheet implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping, WithTitle
{
    use Exportable;

    /**
     * @var Event Az esemény.
     */
    private Event $event;

    /**
     * @var int A túra azonosítója.
     */
    private int $hike_id;

    /**
     * @var string A túra neve.
     */
    private string $hike_name;

    /**
     * @param  Event  $event  Az esemény.
     * @param  int  $hike_id  A túra azonosítója.
     * @param  string  $hike_name  A túra neve.
     */
    public function __construct(Event $event, int $hike_id, string $hike_name)
    {
        $this->event = $event;
        $this->hike_id = $hike_id;
        $this->hike_name = $hike_name;
    }

    /**
     * Visszaadja a résztvevők kollekcióját.
     *
     * @return Collection<int, Participant>
     */
    public function collection(): Collection
    {
        $event = $this->event;

        return Participant::where('event_id', $event->id)
            ->where('hike_id', $this->hike_id)
            ->with('association')
            ->get();
    }

    /**
     * Leképezi a résztvevő adatait a táblázat sorához.
     *
     * @param  mixed  $row  A résztvevő modell.
     * @return array<int, mixed>
     */
    public function map(mixed $row): array
    {
        return [
            $row->name,
            $row->city,
            $row->is_member == 1 ? $row->association->name : '',
            $row->is_student == 1 ? 'Igen' : 'Nem',
            $row->age,
            $row->phone,
            $this->hike_name,
            // $participant->tshirt,
            $row->entry_fee,
            $row->start_time,
            $row->finish_time,
            $row->completion_time,
        ];
    }

    /**
     * Visszaadja a táblázat fejlécét.
     *
     * @return array<int, string>
     */
    public function headings(): array
    {
        return [
            'Név',
            'Helység',
            'Tagság',
            'Diák',
            'Életkor',
            'Telefonszám',
            'Túra',
            // 'Poló méret',
            'Részvételi díj',
            'Ind_idő',
            'Érk_idő',
            'Telj_idő',
        ];
    }

    /**
     * Visszaadja a munkalap címét.
     */
    public function title(): string
    {
        return $this->hike_name;
    }
}
