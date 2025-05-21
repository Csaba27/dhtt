<div>
    <main class="mb-4">
        @include('livewire.admin.dhtt.hikes.steps.navigation', ['steps' => $steps])

        <div class="card">
            <x-form wire:submit.prevent="submit" x-data="{ isUploading: false, progress: 0 }"
                    x-on:livewire-upload-start="isUploading = true"
                    x-on:livewire-upload-finish="isUploading = false"
                    x-on:livewire-upload-error="isUploading = false"
                    x-on:livewire-upload-progress="progress = $event.detail.progress">
                <div class="mb-3">
                    <x-form.textarea wire:model="route" name="route" label="Útvonal"></x-form.textarea>
                </div>

                <div class="mb-3">
                    <x-form.input name="trackLink"
                                  label="Track link" readonly="">{{ $trackLink }}</x-form.input>
                </div>

                <div class="mb-3">
                    <x-form.input wire:model="track" name="track" label="Track fájl" type="file"></x-form.input>
                </div>

                @if ($track)
                    <x-button type="button" wire:click="resetTrack"
                              class="my-1">Track fájl törlése
                    </x-button>
                @endif

                <div x-show="isUploading" class="my-1">
                    <div wire:loading wire:target="track">Feltöltés folyamatban...</div>
                    <div class="flex w-full h-1.5 bg-gray-200 rounded-full overflow-hidden" role="progressbar"
                         aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                        <div
                            class="flex flex-col justify-center rounded-full overflow-hidden bg-blue-600 text-xs text-white text-center whitespace-nowrap transition duration-500"
                            :style="'width: ' + progress + '%'"></div>
                    </div>
                    <x-button type="button" wire:click="$cancelUpload('track')" class="my-1"
                              x-on:click="isUploading = ! isUploading;">Feltöltés visszavonása
                    </x-button>
                </div>

                <div class="flex justify-end">
                    <x-button type="button" class="mr-1" wire:click="previousStep">Vissza</x-button>
                    <x-button type="submit">Mentés</x-button>
                </div>
            </x-form>
        </div>
    </main>
</div>
