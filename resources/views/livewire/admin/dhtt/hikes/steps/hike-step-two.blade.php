<div>
    <main class="mb-4">
        @include('livewire.admin.dhtt.hikes.steps.navigation', ['steps' => $steps])

        <div class="card">
            <x-form wire:submit.prevent="submit" method="post">
                <div class="mb-3">
                    <x-form.input type="text" wire:model="numberStart" name="numberStart" label="Alsó sorszám"/>
                </div>

                <div class="mb-3">
                    <x-form.input type="text" wire:model="numberEnd" name="numberEnd" label="Felső sorszám"/>
                </div>

                <div class="mb-3">
                    <x-form.input type="text" wire:model="numberStartExtra" name="numberStartExtra"
                                  label="Alsó sorszám extra"/>
                </div>

                <div class="mb-3">
                    <x-form.input type="text" wire:model="numberEndExtra" name="numberEndExtra"
                                  label="Felső sorszám extra"/>
                </div>

                <div class="flex justify-end">
                    <x-button type="button" class="mr-1" wire:click="previousStep">Vissza</x-button>
                    <x-button type="submit">Tovább</x-button>
                </div>
            </x-form>
        </div>
    </main>
</div>
