<div>
    <div class="flex justify-between gap-3 flex-wrap mb-3">
        <h1 class="text-2xl font-semibold">Jelentkezők</h1>
        <div>
            <x-a href="{{ route('admin.dhtt.participants.create', ['event' => $event]) }}" class="btn btn-primary">
                <i class="fa fa-plus"></i> <span class="ml-1">Új jelentkező</span>
            </x-a>
            <button class="btn btn-indigo" wire:click="export" wire:loading.class="disabled"
                    wire:loading.attr="disabled">
                <i class="fas fa-circle-notch fa-spin" wire:loading.inline wire:target="export"></i>
                <i class="fa fa-download" wire:loading.remove wire:target="export"></i> <span class="ml-1">Jelentkezők letöltés</span>
            </button>
        </div>
    </div>

    @if ($event)
        <div class="text-gray-400 dark:text-gray-300 text-md mb-1">{{ $event->name }}</div>
    @endif

    <div class="card">

        @include('errors.messages')

        <div class="my-3 flex flex-wrap lg:flex-nowrap items-center md:justify-between gap-3">

            <div class="flex flex-row items-center gap-3 mb-3">
                <span>Oldalanként: </span>

                <input type="number" name="paginate" wire:model.live.debounce.850ms="paginate"
                       class="block w-full dark:bg-gray-500 dark:text-gray-200 dark:placeholder-gray-200 border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-light-blue-500 focus:border-light-blue-500 sm:text-sm"
                       min="5" max="100" step="1"/>

                <span>Túra: </span>
                <select wire:model.live="hike"
                        name="hike"
                        id="hike"
                        class="border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg focus:ring focus:ring-blue-300 dark:focus:ring-blue-600 outline-none py-2 px-3">
                    <x-form.select-option value="" :selected="$hike">Összes túra</x-form.select-option>
                    @foreach ($this->hikes as $id => $value)
                        <x-form.select-option value="{{ $id }}"
                                              :selected="$hike">{{ $value }}</x-form.select-option>
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
                    <th><a href="#" wire:click.prevent="sortBy('hike')">
                            Túra
                            @if ($sortField === 'hike')
                                <i class="fa fa-sort-{{ $sortAsc ? 'up' : 'down' }}"></i>
                            @endif
                        </a></th>
                    <th><a href="#" wire:click.prevent="sortBy('number')">
                            Rajtszám
                            @if ($sortField === 'number')
                                <i class="fa fa-sort-{{ $sortAsc ? 'up' : 'down' }}"></i>
                            @endif
                        </a></th>
                    <th><a href="#" wire:click.prevent="sortBy('start_time')">
                            Rajt kezdete
                            @if ($sortField === 'start_time')
                                <i class="fa fa-sort-{{ $sortAsc ? 'up' : 'down' }}"></i>
                            @endif
                        </a></th>
                    <th><a href="#" wire:click.prevent="sortBy('finish_time')">
                            Beérkezési idő
                            @if ($sortField === 'distance')
                                <i class="fa fa-sort-{{ $sortAsc ? 'up' : 'down' }}"></i>
                            @endif
                        </a></th>
                    <th><a href="#" wire:click.prevent="sortBy('is_member')">
                            Tagság
                            @if ($sortField === 'is_member')
                                <i class="fa fa-sort-{{ $sortAsc ? 'up' : 'down' }}"></i>
                            @endif
                        </a></th>
                    <th><a href="#" wire:click.prevent="sortBy('is_student')">
                            Diák
                            @if ($sortField === 'is_student')
                                <i class="fa fa-sort-{{ $sortAsc ? 'up' : 'down' }}"></i>
                            @endif
                        </a></th>
                    <th><a href="#" wire:click.prevent="sortBy('age')">
                            Életkor
                            @if ($sortField === 'age')
                                <i class="fa fa-sort-{{ $sortAsc ? 'up' : 'down' }}"></i>
                            @endif
                        </a></th>
                    <th><a href="#" wire:click.prevent="sortBy('entry_fee')">
                            Részvételi díj
                            @if ($sortField === 'entry_fee')
                                <i class="fa fa-sort-{{ $sortAsc ? 'up' : 'down' }}"></i>
                            @endif
                        </a></th>
                    <th>Műveletek</th>
                </tr>
                </thead>
                <tbody>
                @foreach($participants as $participant)
                    <tr wire:key="{{ $participant->id }}"
                        class="border-b transition duration-300 ease-in-out dark:hover:bg-gray-600 hover:bg-gray-100">
                        <td>{{ $participant['id'] }}</td>
                        <td>{{ $participant['name'] }}</td>
                        <td>{{ $participant->hike->name }}</td>
                        <td>{{ $participant['number'] }}</td>
                        <td>{{ $participant['start_time'] }}</td>
                        <td>{{ $participant['finish_time'] }}</td>
                        <td>{{ $participant['is_member'] ? $participant->association->name : 'Nincs' }}</td>
                        <td>{{ ($participant['is_student'] ? 'Igen' : 'Nem') }}</td>
                        <td>{{ $participant['age'] }}</td>
                        <td>{{ $participant['entry_fee'] }}</td>

                        <td>
                            <a class="btn btn-primary btn-sm mr-1 mb-1"
                               href="{{ route('admin.dhtt.participants.edit', $participant) }}"><i
                                    class="fa fa-edit"></i> <span>Szerkesztés</span></a>

                            <x-modal>
                                <x-slot name="trigger" class="block">
                                    <x-button type="button" variant="red" size="xs" class="space-x-1"
                                              @click.prevent="open()"
                                    ><i class="fas fa-trash"></i><span>Törlés</span>
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
                                            Biztos hogy törölni szeretnéd törölni az alábbi túrát:
                                            <b>{{ $participant['name'] }}</b>
                                        </div>
                                    </div>
                                </x-slot>

                                <x-slot name="footer">
                                    <x-button
                                        type="button"
                                        variant="red"
                                        wire:click="delete('{{ $participant['id'] }}')"
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
            {{ $participants->links() }}
        </div>
    </div>

</div>
