<div>
    <h1 class="dhtt-h1">Eredmények</h1>

    @if ($hikes && $event)
        <div class="flex justify-around flex-wrap gap-5">
            @foreach($hikes as $hike)
                <div class="mb-3 border dark:border-gray-600 border-gray-300 p-2 pb-4 rounded flex-1 md:max-w-lg md:w-1/2 w-full">
                    <div class="flex justify-between mb-3">
                        <div class="p-2">
                            <h5>{{ $hike['name'] }}</h5>
                        </div>
                        @if ($hike['hike_type'] == 1)
                            <div class="p-2"><i class="fas fa-walking"></i></div>
                        @else
                            <div class="p-2"><i class="fas fa-biking"></i></div>
                        @endif
                    </div>
                    <div class="overflow-auto">
                        <table class="table mb-1">
                            <thead>
                            <tr>
                                <th>Rangsor</th>
                                <th>Név</th>
                                <th>Rajtszám</th>
                                <th>Időeredmény</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($hike['participants'] as $participant)
                                <tr class="odd:bg-white even:bg-gray-50 dark:odd:bg-gray-800 dark:even:bg-gray-700 {{ ($participant->status === 'abandoned' ? '!bg-red-300 !border-b-red-200 dark:!bg-red-300/30' : '') }}">
                                    <td>{{ $loop->iteration . '.' }}</td>
                                    <td>{{ $participant->name }}</td>
                                    <td>{{ $participant->number }}</td>
                                    <td>{{ $participant->completion_time }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <x-alert variant="info">
            <p>Még nincsenek eredmények!</p>
        </x-alert>
    @endif
</div>
