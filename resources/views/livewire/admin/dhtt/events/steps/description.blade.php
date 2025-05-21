<div>
    <main class="mb-4">
        @include('livewire.admin.dhtt.events.steps.navigation', ['steps' => $steps])

        <div class="card">
            <x-form wire:submit.prevent="submit">
                <div class="row mb-3">
                    <h3>Leírás</h3>
                    <x-form.text-editor wire:model="description" name="description" label="none"></x-form.text-editor>
                </div>

                <div class="flex justify-end">
                    <x-button type="button" class="mr-1" wire:click="previousStep">Vissza</x-button>
                    <x-button type="submit">Tovább</x-button>
                </div>
            </x-form>
        </div>
    </main>
</div>
