<div>
    <main class="mb-4">
        @include('livewire.admin.dhtt.hikes.steps.navigation', ['steps' => $steps])

        <div class="card">
            <x-form wire:submit.prevent="submit" method="post">
                <div class="mb-3">
                    <x-form.select wire:model="hikeType" label="Túra típus" name="hikeType"
                                   placeholder="Válaszd ki a túra típust">
                        @foreach(App\Models\HikeType::all() as $type)
                            <x-form.select-option
                                value="{{ $type['id']  }}">{{ $type['name'] }}</x-form.select-option>
                        @endforeach
                    </x-form.select>
                </div>

                <div class="mb-3">
                    <x-form.input type="text" wire:model="name" label="Név" name="name"/>
                </div>
                <div class="mb-3">
                    <x-form.input type="text" wire:model="year" label="Év" name="year"/>
                </div>

                <div class="mb-3">
                    <x-form.input type="text" wire:model="distance" label="Távolság" name="distance"/>
                </div>

                <div class="mb-3">
                    <x-form.input type="text" wire:model="timeLimit" label="Szint idő" name="timeLimit"/>
                </div>

                <div class="mb-3">
                    <x-form.input type="text" wire:model="elevation" label="Szintkülönbség" name="elevation"/>
                </div>

                <div class="flex justify-end">
                    <x-button type="submit">Tovább</x-button>
                </div>
            </x-form>
        </div>
    </main>
</div>
