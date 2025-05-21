<div>
    <main class="mb-4">
        @include('livewire.admin.dhtt.events.steps.navigation', ['steps' => $steps])

        <div class="card">
            <x-form wire:submit.prevent="submit">
                <div class="flex mb-3 lg:gap-3 md:gap-1 lg:flex-row flex-col md:flex-nowrap">
                    <div class="lg:w-full">
                        <h3>Túrák hozzárendelése</h3>

                        <div class="mb-3 mt-5">
                            <x-form.input type="text"
                                          label="Keresés"
                                          wire:model.live="search"
                                          name="search"/>
                        </div>

                        <div class="mt-2 flex flex-row flex-wrap">
                            @foreach ($allHikes as $key => $hike)
                                <div class="flex items-center ml-1.5 mb-1.5">
                                    <input id="hike-checkbox-{{ $key }}"
                                           type="checkbox"
                                           value="{{ $hike->id }}"
                                           wire:model="hikes"
                                           class="hidden peer">

                                    <label for="hike-checkbox-{{ $key }}"
                                           class="flex items-center cursor-pointer px-3 py-1.5 bg-gray-200 dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg peer-checked:bg-blue-500 dark:peer-checked:bg-blue-600 peer-checked:text-white transition">
                                    <span
                                        class="text-sm font-medium">
                                        {{ $hike->name }}
                                    </span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        @error('hikes') <span class="error">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="flex justify-end">
                    <x-button type="button" class="mr-1" wire:click="previousStep">
                        Vissza
                    </x-button>
                    <x-button type="submit">
                        Mentés
                    </x-button>
                </div>
            </x-form>
        </div>
    </main>
</div>
