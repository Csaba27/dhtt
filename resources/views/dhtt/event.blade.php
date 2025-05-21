<x-dhtt.layouts.app>

    @php
        $isArchive = Route::is('dhtt.archive');
    @endphp

    @if ($event)
        <div class="dhttbreadcrumb">
            <h1>{{ $event->name }}</h1>
        </div>
    @endif

    <div class="container mx-auto my-5">

        <div class="flex justify-center mb-5">
            <div
                    class="flex flex-wrap lg:flex-nowrap items-center bg-gray-300 dark:bg-gray-600 rounded-md p-3 shadow-x gap-1 max-w-screen-xl w-full">

                @if ($isArchive)
                    <h2 class="text-center text-xl font-semibold mr-1">Archívum: </h2>
                    <select
                            class="border border-gray-300 bg-white dark:bg-gray-500 dark:text-gray-200 py-2 px-3 rounded-md shadow"
                            name="event"
                            label="none"
                            onchange="window.location.href = '{{ route('dhtt.archive') }}/' + this.value + '{{ $option ? '?option=' . $option : ''}}'">

                        @foreach($events as $_event)
                            <x-form.select-option :value="$_event['id']"
                                                  :selected="$event['id']">{{ $_event['short_name'] }}</x-form.select-option>
                        @endforeach

                    </select>
                @else
                    <h2 class="text-center text-xl font-semibold mr-1 w-full lg:w-auto">{{ $event->name }}</h2>
                @endif

                @foreach(['detail' => 'Részletek', 'routes' => 'Útvonalak', 'results' => 'Eredmények', 'gallery' => 'Galéria'] as $section => $label)
                    <a href="{{ route(request()->route()->getName(), ['option' => $section]) }}"
                       class="px-5 py-2 text-black dark:text-gray-100 rounded-2xl hover:bg-gray-400 dark:hover:bg-gray-500 hover:text-white transition ease-linear duration-300 {{ $option == $section ? 'font-bold' : '' }}">
                        {{ $label }}
                    </a>
                @endforeach

            </div>
        </div>

        <div class="p-5">

            @if ($option == 'detail')
                <div class="bg-white dark:bg-gray-700 rounded-2xl shadow p-6 space-y-4 mb-3">
                    <div class="flex items-start justify-between">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Esemény információk</h2>
                        <x-badge variant="primary">{{ $event['date'] }}</x-badge>
                    </div>

                    <div class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                        <p><span
                                    class="font-semibold text-gray-900 dark:text-white">Helyszín:</span> {{ $event['location'] }}
                        </p>
                        <p><span
                                    class="font-semibold text-gray-900 dark:text-white">Részvételi díj:</span> {{ $event['entry_fee'] }}
                            RON</p>
                        <p>
                            <span
                                    class="font-semibold text-gray-900 dark:text-white">Szervező:</span> {{ $event['organizer_name'] }}
                            <br>
                            <span class="ml-2"><i class="fa fa-envelope me-3"></i> <a
                                        class="hover:underline text-blue-600 dark:text-blue-400"
                                        href="mailto:{{ $event['organizer_email'] }}">{{ $event['organizer_email'] }}</a></span><br>
                            <span class="ml-2"><i class="fa fa-phone me-3"></i> <a
                                        class="hover:underline text-blue-600 dark:text-blue-400"
                                        href="tel:{{ $event['organizer_phone'] }}">{{ $event['organizer_phone'] }}</a></span>
                        </p>
                        <p>
                            <span class="font-semibold text-gray-900 dark:text-white">Regisztráció:</span>
                            {{ $event['registration_start'] }} – {{ $event['registration_end'] }}
                        </p>
                        <p>
                        <div class="font-semibold text-gray-900 dark:text-white">Kedvezmények:</div>
                        <div class="ml-2">Korai regisztráció: <span
                                    class="font-semibold">{{ $event['discount1'] }} RON</span></div>
                        <div class="ml-2">Tagság/Diák: <span class="font-semibold">{{ $event['discount2'] }} RON</span>
                        </div>
                        <div class="ml-2">Lejár:
                            <x-badge variant="primary">{{ $event['registration_discount_until'] }}</x-badge>
                        </div>
                        </p>
                    </div>
                </div>

                <h1 class="dhtt-h1">Meghívó</h1>
                {!! $event->invitation !!}
                <br>

                <h1 class="dhtt-h1">Leírás</h1>
                {!! $event->description !!}

                <br>
                <h1 class="dhtt-h1">Szabályzat</h1>
                {!! $event->rules !!}
            @elseif ($option == 'routes')

                <livewire:dhtt.event.routes :$event/>

            @elseif ($option == 'results')

                <livewire:dhtt.event.results :$event/>

            @elseif ($option == 'gallery')

                <livewire:dhtt.event.images :$event/>

            @endif
        </div>
    </div>

    <x-dhtt.layouts.footer/>
</x-dhtt.layouts.app>
