<div>
    <div class="flex justify-between gap-3 flex-wrap mb-3">
        <h1 class="text-2xl font-semibold">Túrák</h1>
        <x-a href="{{ route('admin.dhtt.hikes.create') }}" class="btn btn-primary">
            <i class="fa fa-plus"></i> <span class="ml-1">Új túra hozzáadása</span>
        </x-a>
    </div>

    @if ($event)
        <div class="text-gray-400 dark:text-gray-300 text-md mb-1">{{ $event->name }}</div>
    @endif

    <div class="card">

        @include('errors.messages')

        <div class="my-3 flex flex-wrap lg:flex-nowrap items-center md:justify-between gap-3">

            <div class="flex flex-row items-center gap-3 mb-3">
                <span>Szűrés: </span>

                <select wire:model.live="hikeType"
                        name="hikeTpe"
                        id="hikeType"
                        class="border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg focus:ring focus:ring-blue-300 dark:focus:ring-blue-600 outline-none py-2 px-3">
                    <x-form.select-option value="" :selected="$hikeType">Összes típus</x-form.select-option>
                    @foreach ($this->hikeTypes as $row)
                        <x-form.select-option value="{{ $row->getKey() }}">{{ $row->name }}</x-form.select-option>
                    @endforeach
                </select>

                <select wire:model.live="distance"
                        name="distance"
                        id="distance"
                        class="border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg focus:ring focus:ring-blue-300 dark:focus:ring-blue-600 outline-none py-2 px-3">
                    <x-form.select-option value="" :selected="$distance">Összes táv</x-form.select-option>
                    @foreach ($this->distances as $value)
                        <x-form.select-option value="{{ $value }}"
                                              :selected="$distance">{{ $value }}</x-form.select-option>
                    @endforeach
                </select>
            </div>

            <div class="flex lg:justify-end w-full lg:w-1/2 mb-3">
                <input type="text" name="search" wire:model.live="search"
                       class="block w-full dark:bg-gray-500 dark:text-gray-200 dark:placeholder-gray-200 border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-light-blue-500 focus:border-light-blue-500 sm:text-sm"
                       placeholder="Keresés"/>
            </div>
        </div>

        <div class="overflow-x-scroll">
            <table wire:loading.class="opacity-50" wire:target="gotoPage">
                <thead>
                <tr>
                    <th><a href="#" wire:click.prevent="sortBy('id')">
                            #
                            @if ($sortField === 'id')
                                <i class="fa fa-sort-{{ $sortAsc ? 'up' : 'down' }}"></i>
                            @endif
                        </a></th>
                    <th><a href="#" wire:click.prevent="sortBy('name')">
                            Név
                            @if ($sortField === 'name')
                                <i class="fa fa-sort-{{ $sortAsc ? 'up' : 'down' }}"></i>
                            @endif
                        </a></th>
                    <th><a href="#" wire:click.prevent="sortBy('hike_type_id')">
                            Túra típus
                            @if ($sortField === 'hike_type_id')
                                <i class="fa fa-sort-{{ $sortAsc ? 'up' : 'down' }}"></i>
                            @endif
                        </a></th>
                    <th><a href="#" wire:click.prevent="sortBy('year')">
                            Év
                            @if ($sortField === 'year')
                                <i class="fa fa-sort-{{ $sortAsc ? 'up' : 'down' }}"></i>
                            @endif
                        </a></th>
                    <th><a href="#" wire:click.prevent="sortBy('route')">
                           Útvonal
                            @if ($sortField === 'route')
                                <i class="fa fa-sort-{{ $sortAsc ? 'up' : 'down' }}"></i>
                            @endif
                        </a></th>
                    <th><a href="#" wire:click.prevent="sortBy('distance')">
                           Távolság
                            @if ($sortField === 'distance')
                                <i class="fa fa-sort-{{ $sortAsc ? 'up' : 'down' }}"></i>
                            @endif
                        </a></th>
                    <th><a href="#" wire:click.prevent="sortBy('time_limit')">
                            Szint idő
                            @if ($sortField === 'elevation')
                                <i class="fa fa-sort-{{ $sortAsc ? 'up' : 'down' }}"></i>
                            @endif
                        </a></th>
                    <th>Műveletek</th>
                </tr>
                </thead>
                <tbody>
                @foreach($hikes as $hike)
                    <tr wire:key="hike{{ $hike->id }}"
                        class="border-b transition duration-300 ease-in-out dark:hover:bg-gray-600 hover:bg-gray-100">
                        <td>{{ $hike['id'] }}</td>
                        <td>{{ $hike['name'] }}</td>
                        <td>{{ $hike->hikeType->name }}</td>
                        <td>{{ $hike['year'] }}</td>
                        <td>{{ $hike['route'] }}</td>
                        <td>{{ $hike['distance'] }}</td>
                        <td>{{ $hike['time_limit'] }}</td>
                        <td>
                            <a href="{{ route('admin.dhtt.hikes.edit', $hike) }}"
                               class="btn btn-primary btn-sm mb-1 disabled:opacity-50 disabled:cursor-not-allowed space-x-1">
                                <i class="fas fa-edit"></i><span>Szerkesztés</span>
                            </a>
                            <x-modal>
                                <x-slot name="trigger" class="block">
                                    <x-button type="button" variant="red" size="xs" class="space-x-1"
                                              @click.prevent="open()"><i class="fas fa-trash"></i><span>Törlés</span>
                                    </x-button>
                                </x-slot>

                                <x-slot name="modalTitle">Törlés megerősítése</x-slot>

                                <x-slot name="content">
                                    <div wire:loading>
                                        <span>Törlés folyamatban...</span> <i
                                            class="fas fa-spinner fa-spin animate-spin"></i>
                                    </div>
                                    <div wire:loading.remove>
                                        <div class="text-center">
                                            Biztos, hogy törölni szeretnéd törölni az alábbi túrát?<br>
                                            <b>{{ $hike['name'] }}</b>
                                        </div>
                                    </div>
                                </x-slot>

                                <x-slot name="footer">
                                    <x-button
                                        type="button"
                                        variant="red"
                                        wire:click="delete('{{ $hike['id'] }}')"
                                        wire:loading.attr="disabled"
                                        wire:loading.class="opacity-50"><span>Törlés</span>
                                    </x-button>
                                </x-slot>
                            </x-modal>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $hikes->links() }}
        </div>
    </div>

</div>
