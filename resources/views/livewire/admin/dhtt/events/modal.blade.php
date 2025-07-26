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
            <div class="space-y-6 text-gray-800 dark:text-gray-200">
                <div class="flex items-center justify-between bg-gray-100 dark:bg-gray-700 p-4 rounded-xl shadow-sm">
                    <div class="flex items-center gap-2">
                        <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Státusz:</span>
                        <span class="text-base font-medium text-indigo-600 dark:text-indigo-300">
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
                    <span class="text-sm font-semibold text-gray-500 dark:text-gray-400">Rövid név</span>
                    <div class="text-xl font-bold text-gray-800 dark:text-gray-100">{{ $event->short_name }}</div>
                    <span class="text-sm font-semibold text-gray-500 dark:text-gray-400 mt-1 block">Teljes név</span>
                    <div class="text-lg font-medium text-gray-700 dark:text-gray-300">{{ $event->name }}</div>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    <div>
                        <span class="block text-sm font-semibold text-gray-600 dark:text-gray-400">Év:</span>
                        <span class="text-lg font-medium">{{ $event->year }}</span>
                    </div>
                    <div>
                        <span class="block text-sm font-semibold text-gray-600 dark:text-gray-400">Dátum:</span>
                        <span class="text-lg font-medium">{{ $event->date }}</span>
                    </div>
                    <div>
                        <span class="block text-sm font-semibold text-gray-600 dark:text-gray-400">Helyszín:</span>
                        <span class="text-lg font-medium">{{ $event->location }}</span>
                    </div>
                </div>

                <div
                    class="p-4 border border-indigo-200 dark:border-indigo-500 rounded-xl bg-indigo-50 dark:bg-indigo-700/30">
                    <div class="text-sm font-semibold text-indigo-700 dark:text-indigo-200 mb-2">Részvételi díjak</div>
                    <div class="flex flex-wrap gap-6 text-lg font-medium text-gray-800 dark:text-gray-100">
                        <div><span
                                class="text-sm text-gray-600 dark:text-gray-400 block">Alap díj</span>{{ $event->entry_fee }}
                        </div>
                        <div><span
                                class="text-sm text-gray-600 dark:text-gray-400 block">Kedvezmény 1</span>{{ $event->discount1 }}
                        </div>
                        <div><span
                                class="text-sm text-gray-600 dark:text-gray-400 block">Kedvezmény 2</span>{{ $event->discount2 }}
                        </div>
                    </div>
                </div>

                <div
                    class="p-4 border border-yellow-200 dark:border-yellow-500 rounded-xl bg-yellow-50 dark:bg-yellow-700/30">
                    <div class="text-sm font-semibold text-yellow-700 dark:text-yellow-200 mb-2">Regisztrációs
                        időszakok
                    </div>
                    <div
                        class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 font-medium text-gray-800 dark:text-gray-100">
                        <div>
                            <span class="block text-sm text-gray-600 dark:text-gray-400">Kezdete</span>
                            <x-badge>{{ $event->registration_start }}</x-badge>
                        </div>
                        <div>
                            <span class="block text-sm text-gray-600 dark:text-gray-400">Vége</span>
                            <x-badge>{{ $event->registration_end }}</x-badge>
                        </div>
                        <div>
                            <span class="block text-sm text-gray-600 dark:text-gray-400">Kedvezmény eddig</span>
                            <x-badge>{{ $event->registration_discount_until }}</x-badge>
                        </div>
                    </div>
                </div>

                <div
                    class="p-4 border border-gray-200 dark:border-gray-500 rounded-xl bg-white dark:bg-gray-700 shadow-sm">
                    <div class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Szervező</div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-gray-800 dark:text-gray-100">
                        <div>
                            <span class="block text-sm text-gray-600 dark:text-gray-400">Név</span>
                            <span class="text-base font-medium">{{ $event->organizer_name }}</span>
                        </div>
                        <div>
                            <span class="block text-sm text-gray-600 dark:text-gray-400">Email</span>
                            <span class="text-base font-medium">{{ $event->organizer_email }}</span>
                        </div>
                        <div>
                            <span class="block text-sm text-gray-600 dark:text-gray-400">Telefonszám</span>
                            <span class="text-base font-medium">{{ $event->organizer_phone }}</span>
                        </div>
                    </div>
                </div>

                @if ($event->hikes->isNotEmpty())
                    <div
                        class="p-4 border border-green-200 dark:border-green-500 rounded-xl bg-green-50 dark:bg-green-700/30 mt-6">
                        <div class="text-sm font-semibold text-green-700 dark:text-green-200 mb-4">Kapcsolódó túrák
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                            @foreach ($event->hikes as $hike)
                                <div
                                    class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-500 rounded-lg shadow-sm p-3">
                                    <h6 class="text-md font-semibold text-gray-800 dark:text-gray-100">{{ $hike->name }}</h6>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        @else
            <p class="text-gray-500 dark:text-gray-300 text-sm">Nincs kiválasztott esemény.</p>
        @endif

    </x-slot>

    <x-slot name="footer">
        <x-button type="button" variant="red" @click="close()">Bezár</x-button>
    </x-slot>
</x-modal>
