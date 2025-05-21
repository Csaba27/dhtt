<div>
    @if ($participants)
        <div id="dhtt-registration-list" class="card mt-5" d>
            <h4 class="text-lg mb-4">Regisztrált személyek</h4>
            <table class="w-full" wire:loading.class="opacity-50">
                <thead>
                <tr>
                    <th><a href="#" wire:click.prevent="sortBy('id')">
                        <span>#</span>
                        @if ($sortField === 'id')
                            <i class="fa fa-sort-{{ $sortAsc ? 'up' : 'down' }}"></i>
                        @endif
                    </a></th>
                    <th><a href="#" wire:click.prevent="sortBy('name')">
                        <span>Név</span>
                        @if ($sortField === 'name')
                            <i class="fa fa-sort-{{ $sortAsc ? 'up' : 'down' }}"></i>
                        @endif
                    </a></th>
                    <th><a href="#" wire:click.prevent="sortBy('city')">
                        <span>Helység</span>
                        @if ($sortField === 'city')
                            <i class="fa fa-sort-{{ $sortAsc ? 'up' : 'down' }}"></i>
                        @endif
                    </a></th>
                    <th><a href="#" wire:click.prevent="sortBy('hike')">
                        <span>Túra</span>
                        @if ($sortField === 'hike')
                            <i class="fa fa-sort-{{ $sortAsc ? 'up' : 'down' }}"></i>
                        @endif
                    </a></th>
                </tr>
                </thead>
                <tbody>
                @foreach ($participants as $participant)
                    <tr wire:key="{{ $participant->id }}">
                        <td>{{ $participant->id }}</td>
                        <td>{{ $participant->name }}</td>
                        <td>{{ $participant->city }}</td>
                        <td>{{ $participant->hike->name }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{ $participants->links(data: ['scrollTo' => '#dhtt-registration-list']) }}
        </div>
    @endif
</div>
