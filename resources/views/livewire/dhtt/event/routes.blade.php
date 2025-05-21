<div>
    @if ($hikes)
        <section id="dhtt-hikes" class="py-5">
            <div class="container mx-auto px-4">
                <h1 class="dhtt-h1">Túraútvonalak</h1>
                <div class="flex flex-wrap -mx-4 justify-center">
                    @foreach ($hikes as $hike)
                        <div class="w-full sm:w-1/2 lg:w-1/3 px-4 mb-6">
                            <div
                                class="bg-white dark:bg-gray-700 dark:text-cyan-50 text-gray-600 rounded-lg shadow-xl hover:shadow-2xl transition duration-300 ease-in-out">
                                <div class="p-6">
                                    <div class="flex items-center justify-between text-xl font-semibold mb-4">
                                        <h4 class="text-lg text-gray-800 dark:text-white">{{ $hike->name }}</h4>
                                        @if ($hike->hike_type_id == 1)
                                            <div><i class="fas fa-walking"></i></div>
                                        @else
                                            <div><i class="fas fa-biking"></i></div>
                                        @endif
                                    </div>
                                    <div class="mb-4">
                                        @if ($hike->getMedia('tracks')->count())
                                            <div class="track-container mb-6"
                                                 data-track="{{ $hike->getFirstMediaUrl('tracks') }}">
                                                <div class="map-container">
                                                    <div class="map" style="height: 450px; width: 100%"></div>
                                                </div>
                                                <div class="elevation-chart-container mt-4">
                                                    <canvas class="elevation-chart"></canvas>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    <ul class="space-y-3 text-sm text-gray-600 dark:text-gray-200">
                                        <li class="flex items-center justify-between">
                                            <div class="mr-1">
                                                <i class="fas fa-file-alt mr-3 text-gray-500"></i>
                                                <span class="font-semibold">Track fájl:</span>
                                            </div>
                                            <a href="{{ asset($hike->getFirstMediaUrl('tracks')) }}"
                                               class="text-blue-600 dark:text-blue-400 hover:underline ml-2">Letöltés
                                                <i
                                                    class="fas fa-arrow-circle-down"></i></a>
                                        </li>
                                        <li class="flex items-center justify-between flex-wrap gap-1">
                                            <div class="mr-1 flex flex-nowrap">
                                                <i class="fas fa-route mr-3 text-gray-500"></i>
                                                <span class="font-semibold">Útvonal:</span>
                                            </div>
                                            <span>{{ $hike->route }}</span>
                                        </li>
                                        <li class="flex items-center justify-between">
                                            <div class="mr-1">
                                                <i class="fa fa-arrows-h mr-3 text-gray-500"></i>
                                                <span class="font-semibold">Túrahossz:</span>
                                            </div>
                                            <span>{{ $hike->distance }} km</span>
                                        </li>
                                        <li class="flex items-center justify-between">
                                            <div class="mr-1">
                                                <i class="fas fa-chart-area mr-3 text-gray-500"></i>
                                                <span class="font-semibold">Szintkülönbség:</span>
                                            </div>
                                            <span>{{ $hike->elevation }} m</span>
                                        </li>
                                        <li class="flex items-center justify-between">
                                            <div class="mr-1">
                                                <i class="fas fa-hourglass-half mr-3 text-gray-500"></i>
                                                <span class="font-semibold">Szintidő:</span>
                                            </div>
                                            <span>{{ $hike->time_limit }} óra</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        @once
            @vite('resources/js/dhtt/drawRoute.js')
        @endonce
    @endif
</div>
