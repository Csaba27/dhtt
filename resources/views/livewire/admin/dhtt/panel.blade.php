<div>
    <div class="flex justify-between items-center flex-wrap lg:flex-nowrap mb-5">
        <h1 class="text-xl font-semibold">Teljesítménytúra Szervező</h1>

        <div class="flex items-center space-x-3 flex-wrap">
            <span class="text-gray-700 dark:text-gray-300">Esemény:</span>
            <select wire:model.live="eventId"
                    name="event_id"
                    class="border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg focus:ring focus:ring-blue-300 dark:focus:ring-blue-600 outline-none px-3 py-2">
                <x-form.select-option value="all" :selected="$eventId">Összes</x-form.select-option>
                @foreach(App\Models\Event::all() as $model)
                    <x-form.select-option value="{{ $model->id }}"
                                          :selected="$eventId">{{ $model->short_name }}</x-form.select-option>
                @endforeach
            </select>
            <a class="btn btn-green transition space-x-2" href="{{ route('admin.dhtt.events.create') }}">
                <i class="fa fa-plus"></i> <span>Esemény létrehozása</span>
            </a>
        </div>
    </div>

    <div class="border-b flex space-x-4 bg-gray-100 dark:bg-gray-700 p-2 mb-5 overflow-x-auto text-center"
         x-data="{ tab: $wire.entangle('activeTab') }"
         x-init="$nextTick(() => { const el = $refs[tab]; (el && ($el.scrollLeft = el.offsetLeft - $el.offsetWidth / 2 + el.offsetWidth / 2)) })">
        @foreach($tabs as $name => $tab)
            <a
                wire:navigate
                x-ref="{{ $name }}"
                :class="{
                    'bg-gray-300 dark:bg-gray-600 text-green-600 dark:text-green-400': tab === '{{ $name }}',
                    'text-gray-800 dark:text-gray-100': tab !== '{{ $name }}'
                }"
                href="{{ route('admin.dhtt.index', ['tab' => $name, 'event' => $eventId]) }}"
                class="px-4 py-2 transition hover:bg-gray-200 dark:hover:bg-gray-600 rounded-md font-semibold"
            >
                @isset ($tab['icon'])
                    <i class="fa fa-{{ $tab['icon'] }}"></i>
                @endisset
                <span>{{ $tab['title'] }}</span>
            </a>
        @endforeach
    </div>

    @if (! in_array($activeTab, ['settings', 'supporters', 'events', 'templates']))
        <div wire:loading.block wire:target="eventId">
            <div class="flex items-center flex-col justify-center mt-3 mb-5">
                <div class="w-16 h-16 border-4 border-blue-500 border-t-transparent rounded-full animate-spin"></div>
                <p class="font-bold text-md">Betöltés...</p>
            </div>
        </div>

        <div class="pt-5" wire:cloak wire:loading.block.remove wire:target="eventId">
            @livewire($tabComponent, ['eventId' => $this->eventId], key($tabComponent . $eventId))
        </div>
    @else
        <div class="pt-5" wire:ignore wire:key="{{ $tabComponent . '_container' }}">
            @livewire($tabComponent, ['eventId' => $this->eventId], key($tabComponent))
        </div>
    @endif

</div>
