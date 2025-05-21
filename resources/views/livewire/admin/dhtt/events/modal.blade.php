@use(Carbon\Carbon)

<x-modal>
    <x-slot name="trigger">
        <button x-data="{ modalOpen: $wire.entangle('open') }"
                x-init="$watch('modalOpen', value => value && open()); $watch('on', value => ! value && (modalOpen = false))"></button>
    </x-slot>

    <x-slot name="modalTitle">
        Esemény információi
    </x-slot>

    <x-slot name="content">
        @if ($event)
            <div class="space-y-6 text-gray-800">


                <div class="flex items-center justify-between bg-gray-100 p-4 rounded-xl shadow-sm">
                    <div class="flex items-center gap-2">
                        <span class="text-sm font-semibold text-gray-700">Státusz:</span>
                        <span class="text-base font-medium text-indigo-600">
                             @switch($event->status)
                                @case(1)
                                    Kedvezményes regisztráció
                                    @break
                                @case(2)
                                    Teljes árú regisztráció
                                    @break
                                @case(3)
                                    Regisztráció lezárva (automatikusan)
                                    @break
                                @case(4)
                                    Regisztráció lezárva (manuálisan)
                                    @break
                                @default
                                    Ismeretlen státusz
                            @endswitch
                        </span>
                    </div>
                    <div>
                        @if ($event->active)
                            <x-badge variant="green">Aktív</x-badge>
                        @else
                            <x-badge variant="red">Inaktív</x-badge>
                        @endif
                    </div>
                </div>

                <div>
                    <span class="text-sm font-semibold text-gray-500">Rövid név</span>
                    <div class="text-xl font-bold text-gray-800">{{ $event->short_name }}</div>
                    <span class="text-sm font-semibold text-gray-500 mt-1 block">Teljes név</span>
                    <div class="text-lg font-medium text-gray-700">{{ $event->name }}</div>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    <div>
                        <span class="block text-sm font-semibold text-gray-600">Év:</span>
                        <span class="text-lg font-medium">{{ $event->year }}</span>
                    </div>
                    <div>
                        <span class="block text-sm font-semibold text-gray-600">Dátum:</span>
                        <span class="text-lg font-medium">{{ $event->date }}</span>
                    </div>
                    <div>
                        <span class="block text-sm font-semibold text-gray-600">Helyszín:</span>
                        <span class="text-lg font-medium">{{ $event->location }}</span>
                    </div>
                </div>

                <div class="p-4 border border-indigo-100 rounded-xl bg-indigo-50">
                    <div class="text-sm font-semibold text-indigo-700 mb-2">Részvételi díjak</div>
                    <div class="flex flex-wrap gap-6 text-lg font-medium text-gray-800">
                        <div><span class="text-sm text-gray-600 block">Alap díj</span>{{ $event->entry_fee }}</div>
                        <div><span class="text-sm text-gray-600 block">Kedvezmény 1</span>{{ $event->discount1 }}
                        </div>
                        <div><span class="text-sm text-gray-600 block">Kedvezmény 2</span>{{ $event->discount2 }}
                        </div>
                    </div>
                </div>

                <div class="p-4 border border-yellow-100 rounded-xl bg-yellow-50">
                    <div class="text-sm font-semibold text-yellow-700 mb-2">Regisztrációs időszakok</div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 font-medium text-gray-800">
                        <div>
                            <span class="block text-sm text-gray-600">Kezdete</span>
                            <x-badge>{{ $event->registration_start }}</x-badge>
                        </div>
                        <div>
                            <span class="block text-sm text-gray-600">Vége</span>
                            <x-badge>{{ $event->registration_end }}</x-badge>
                        </div>
                        <div>
                            <span class="block text-sm text-gray-600">Kedvezmény eddig</span>
                            <x-badge>{{ $event->registration_discount_until }}</x-badge>
                        </div>
                    </div>
                </div>

                <div class="p-4 border border-gray-200 rounded-xl bg-white shadow-sm">
                    <div class="text-sm font-semibold text-gray-700 mb-2">Szervező</div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-gray-800">
                        <div>
                            <span class="block text-sm text-gray-600">Név</span>
                            <span class="text-base font-medium">{{ $event->organizer_name }}</span>
                        </div>
                        <div>
                            <span class="block text-sm text-gray-600">Email</span>
                            <span class="text-base font-medium">{{ $event->organizer_email }}</span>
                        </div>
                        <div>
                            <span class="block text-sm text-gray-600">Telefonszám</span>
                            <span class="text-base font-medium">{{ $event->organizer_phone }}</span>
                        </div>
                    </div>
                </div>
            </div>

        @else
            <p class="text-gray-500 text-sm">Nincs kiválasztott esemény.</p>
        @endif
    </x-slot>

    <x-slot name="footer">
        <x-button type="button" variant="red" @click="close()">Bezár</x-button>
    </x-slot>
</x-modal>
