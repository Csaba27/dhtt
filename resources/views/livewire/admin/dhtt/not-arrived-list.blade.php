<div>
    <div class="mt-6 mx-auto max-w-7xl">

        @if ($hike)
            <h2 class="text-xl font-bold mb-2">Be nem érkezett résztvevők – {{ $hike->name }}</h2>
        @else
            <h2 class="text-xl font-bold mb-2">Be nem érkezett résztvevők – összes túra</h2>
        @endif

        <div class="overflow-x-auto">
            <table wire:loading.class="opacity-50">
                <thead>
                <tr>
                    <th class="px-4 py-2 text-left">Név</th>
                    <th class="px-4 py-2 text-left">Rajtszám</th>
                    <th class="px-4 py-2 text-left">Telefon</th>
                    <th class="px-4 py-2 text-left">Indulási idő</th>
                    <th class="px-4 py-2 text-left">Műveletek</th>
                </tr>
                </thead>
                <tbody>
                @foreach($this->getNotArrivedParticipants() as $p)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $p->name }}</td>
                        <td class="px-4 py-2">{{ $p->number }}</td>
                        <td class="px-4 py-2">{{ $p->phone }}</td>
                        <td class="px-4 py-2">{{ $p->start_time }}</td>
                        <td class="px-4 py-2">
                            <button wire:confirm="Biztos vagy benne?" wire:click="markArrived({{ $p->id }})"
                                    class="text-green-600 hover:underline cursor-pointer">
                                Beérkeztetés
                            </button>
                            <button wire:confirm="Biztos vagy benne?" wire:click="markAbandoned({{ $p->id }})"
                                    class="text-red-600 hover:underline ml-2 cursor-pointer">
                                Feladás
                            </button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
