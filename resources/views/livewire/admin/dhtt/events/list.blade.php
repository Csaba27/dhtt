<div>
    <h1 class="text-2xl font-semibold mb-3">Események</h1>

    <div class="card">

        @include('errors.messages')

        <div class="my-3 flex flex-wrap lg:flex-nowrap items-center md:justify-between gap-3">

            <div class="flex flex-row items-center gap-3 mb-3">
                <span>Oldalanként: </span>

                <input type="number" name="paginate" wire:model.live.debounce.850ms="paginate"
                       class="block w-full dark:bg-gray-500 dark:text-gray-200 dark:placeholder-gray-200 border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-light-blue-500 focus:border-light-blue-500 sm:text-sm"
                       min="5" max="100" step="1"/>
            </div>

            <div class="flex lg:justify-end w-full lg:w-1/2 mb-3">
                <input type="text" name="search" wire:model.live="search"
                       class="block w-full dark:bg-gray-500 dark:text-gray-200 dark:placeholder-gray-200 border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-light-blue-500 focus:border-light-blue-500 sm:text-sm"
                       placeholder="Keresés"/>
            </div>
        </div>

        <div class="overflow-x-scroll">
            <table wire:loading.class="opacity-50 pointer-events-none">
                <thead>
                <tr>
                    <th><a href="#" wire:click.prevent="sortBy('id')">
                            #
                            @if ($sortField === 'id')
                                <i class="fa fa-sort-{{ $sortAsc ? 'up' : 'down' }}"></i>
                            @endif
                        </a></th>
                    <th><a href="#" wire:click.prevent="sortBy('short_name')">
                            Név
                            @if ($sortField === 'short_name')
                                <i class="fa fa-sort-{{ $sortAsc ? 'up' : 'down' }}"></i>
                            @endif
                        </a></th>
                    <th><a href="#" wire:click.prevent="sortBy('year')">
                            Év
                            @if ($sortField === 'year')
                                <i class="fa fa-sort-{{ $sortAsc ? 'up' : 'down' }}"></i>
                            @endif
                        </a></th>
                    <th><a href="#" wire:click.prevent="sortBy('registration_start')">
                            Regisztráció kezdete
                            @if ($sortField === 'registration_start')
                                <i class="fa fa-sort-{{ $sortAsc ? 'up' : 'down' }}"></i>
                            @endif
                        </a></th>
                    <th><a href="#" wire:click.prevent="sortBy('registration_end')">
                            Regisztráció vége
                            @if ($sortField === 'registration_end')
                                <i class="fa fa-sort-{{ $sortAsc ? 'up' : 'down' }}"></i>
                            @endif
                        </a></th>
                    <th><a href="#" wire:click.prevent="sortBy('registration_discount_until')">
                            Kedvezmény lejárata
                            @if ($sortField === 'registration_discount_until')
                                <i class="fa fa-sort-{{ $sortAsc ? 'up' : 'down' }}"></i>
                            @endif
                        </a></th>
                    <th>Műveletek</th>
                </tr>
                </thead>
                <tbody>
                @foreach($events as $event)
                    <tr wire:key="events.{{ $event->id }}"
                        class="border-b transition duration-300 ease-in-out dark:hover:bg-gray-600 hover:bg-gray-100">
                        <td>{{ $event['id'] }}</td>
                        <td title="{{ $event['short_name'] }}">{{ Str::limit($event['short_name'], 30) }}</td>
                        <td>{{ $event['year'] }}</td>
                        <td>{{ $event['registration_start'] }}</td>
                        <td>{{ $event['registration_end'] }}</td>
                        <td>{{ $event['registration_discount_until'] }}</td>
                        <td>
                            <div class="flex items-center gap-x-2">
                                <div x-data="{ checked: @json($event->active) }" x-tooltip="Aktív állapot"
                                     class="flex items-center" wire:ignore>
                                    <div
                                        @click="checked = !checked; $wire.setEventActive({{ $event->id }}, checked)"
                                        class="w-12 h-6 flex items-center bg-gray-300 rounded-full p-1 cursor-pointer transition-colors duration-300"
                                        :class="checked ? 'bg-green-500' : 'bg-red-500'">
                                        <div
                                            class="bg-white w-4 h-4 rounded-full shadow-md transform transition-transform duration-300"
                                            :class="checked ? 'translate-x-6' : 'translate-x-0'">
                                        </div>
                                    </div>
                                </div>

                                <a href="{{ route('admin.dhtt.events.edit', $event) }}" x-tooltip="Szerkesztés"
                                   class="ml-2 btn btn-primary {{ $event->active == 1 ? 'opacity-50 cursor-not-allowed pointer-events-none' : '' }}"
                                    {{ $event->active == 1 ? '@click.prevent' : '' }}
                                >
                                    <i class="fas fa-edit"></i>
                                </a>

                                <x-button type="button" variant="yellow"
                                          :disabled="($event->active == 1 && $event->status == 3)"
                                          wire:confirm="Biztos hogy lezárod ezt az eseményt?"
                                          wire:click="close({{ $event->id }})"
                                          x-tooltip="Lezárás"
                                >
                                    <i class="fas fa-ban"></i>
                                </x-button>

                                <x-modal>
                                    <x-slot name="trigger">
                                        <x-button type="button" variant="red"
                                                  :disabled="($event->active == 1 && $event->status == 0)"
                                                  @click.prevent="open()"
                                                  x-tooltip="Törlés"
                                        >
                                            <i class="fas fa-trash"></i>
                                        </x-button>
                                    </x-slot>

                                    <x-slot name="modalTitle">Törlés megerősítése</x-slot>

                                    <x-slot name="content">
                                        <div wire:loading>
                                            <span>Törlés folyamatban...</span>
                                            <i class="fas fa-spinner fa-spin animate-spin"></i>
                                        </div>
                                        <div wire:loading.remove>
                                            <div class="text-center">
                                                Biztos hogy törölni szeretnéd az alábbi eseményt:
                                                <b>{{ $event['name'] }}</b>
                                            </div>
                                        </div>
                                    </x-slot>

                                    <x-slot name="footer">
                                        <x-button type="button" variant="red"
                                                  wire:click="delete('{{ $event['id'] }}')"
                                                  wire:loading.attr="disabled"
                                                  wire:loading.class="opacity-50"
                                        >
                                            <span>Törlés</span>
                                        </x-button>
                                    </x-slot>
                                </x-modal>

                                <x-button type="button" variant="dark"
                                          wire:click="openModal({{ $event->id }})"
                                          x-tooltip="Bővebb információk"
                                >
                                    <i class="fas fa-circle-notch fa-spin"
                                       wire:target="openModal({{ $event->id }})"
                                       wire:loading.inline></i>
                                    <i class="fas fa-eye"
                                       wire:target="openModal({{ $event->id }})"
                                       wire:loading.remove></i>
                                </x-button>
                            </div>
                        </td>

                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $events->links() }}
        </div>
    </div>

    <livewire:admin.dhtt.events.event-modal/>
</div>
