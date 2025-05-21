<div x-data="{ statistics: {{ json_encode($statistics) }} }" x-init="$store.statistics = statistics">
    <h1 class="text-2xl font-semibold mb-3">Statisztika</h1>

    @if($event)
        <div class="text-gray-400 dark:text-gray-300 text-md mb-1">{{ $event->name }}</div>

        <div class="flex flex-wrap">
            <div class="w-full lg:w-1/2 p-3">
                <div class="border border-gray-300 rounded-lg shadow-md p-4 bg-white dark:bg-gray-600">
                    <h2 class="text-lg font-bold mb-2">Online regisztrálók (<span
                            id="total-online-participants">{{ $statistics['totalOnlineParticipants'] }}</span>)</h2>
                    <canvas id="onlineChart" class="bg-white"></canvas>
                </div>
            </div>
            <div class="w-full lg:w-1/2 p-3">
                <div class="border border-gray-300 rounded-lg shadow-md p-4 bg-white dark:bg-gray-600">
                    <h2 class="text-lg font-bold mb-2">Helyszínen regisztrálók (<span
                            id="total-onsite-participants">{{ $statistics['totalOnSiteParticipants'] }}</span>)</h2>
                    <canvas id="onsiteChart" class="bg-white"></canvas>
                </div>
            </div>
            <div class="w-full lg:w-1/2 p-3">
                <div class="border border-gray-300 rounded-lg shadow-md p-4 bg-white dark:bg-gray-600">
                    <h2 class="text-lg font-bold mb-2">Rajtoltak <span
                            id="total-start-participants">({{ $statistics['totalStartParticipants'] }})</span></h2>
                    <canvas id="startChart" class="bg-white"></canvas>
                </div>
            </div>
            <div class="w-full lg:w-1/2 p-3">
                <div class="border border-gray-300 rounded-lg shadow-md p-4 bg-white dark:bg-gray-600">
                    <h2 class="text-lg font-bold mb-2">Beérkezők (<span
                            id="total-arrivals">{{ $statistics['totalArrivals'] }}</span>)
                    </h2>
                    <canvas id="arrivalChart" class="bg-white"></canvas>
                </div>
            </div>
        </div>

        <div class="flex justify-center p-3">
            <div class="border border-gray-300 rounded-lg shadow-md p-4 bg-white dark:bg-gray-600 flex-1">
                <h2 class="text-lg text-center font-bold mb-2">Még be nem érkezett résztvevők túratípusonként (<span
                        id="total-notarrivals">{{ $statistics['totalNotArrived'] }}</span>)</h2>
                <div class="lg:p-3 mx-auto" style="max-width: 800px; max-height: 800px;">
                    <canvas id="notArrivedChart" class="bg-white cursor-pointer mb-3"></canvas>
                    <div class="text-xs dark:text-gray-300 italic">Kattints egy oszlopra a résztvevők szűréséhez túra
                        alapján
                    </div>
                </div>
            </div>
        </div>

        <livewire:admin.dhtt.not-arrived-list :event="$event"/>

    @else
        <div class="alert alert-danger">Esemény nem létezik vagy nincs kiválasztva!</div>
    @endif

    @vite('resources/js/dhtt/statistics.js')
</div>
