<div>
    <div class="flex justify-between gap-3 flex-wrap mb-3">
        <h1 class="text-2xl font-semibold">Támogatók</h1>
        <x-button class="space-x-2" wire:click="create">
            <i class="fa fa-plus"></i><span class="ml-1">Új támogató</span>
        </x-button>
    </div>

    @include('errors.messages')

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-1 lg:grid-cols-3 xl:grid-cols-4 gap-4"
         wire:loading.class="opacity-50" wire:target="gotoPage">
        @foreach ($supporters as $supporter)
            <div
                class="bg-white dark:bg-gray-700 dark:border-gray-600 dark:shadow-gray-400/10 shadow p-4 rounded text-center"
                wire:key="{{ $supporter->id }}">
                <a href="{{ $supporter->link }}" target="_blank" class="no-underline">
                    <img
                        src="{{ $supporter->imagePath }}"
                        class="w-full h-32 object-contain mb-2">
                </a>
                <h2 class="text-lg font-bold">{{ $supporter->name }}</h2>
                <div class="mt-2">
                    <x-button type="button" class="btn-sm" variant="yellow"
                              wire:click="edit({{ $supporter->id }})">
                        <i class="fas fa-circle-notch fa-spin"
                           wire:loading.inline
                           wire:target="edit({{ $supporter->id }})"></i>
                        <i class="fa fa-edit"
                           wire:loading.remove
                           wire:target="edit({{ $supporter->id }})"></i>
                        <span class="ml-1">@lang('Edit')</span>
                    </x-button>

                    <x-button
                        type="button"
                        class="btn-sm" variant="red"
                        wire:click="delete({{ $supporter->id }})"
                        wire:confirm="Biztos hogy törölni szereténd?">

                        <i class="fas fa-circle-notch fa-spin"
                           wire:loading.inline
                           wire:target="delete({{ $supporter->id }})"></i>
                        <i class="fa fa-trash"
                           wire:loading.remove
                           wire:target="delete({{ $supporter->id }})"></i>
                        <span class="ml-1">@lang('Delete')</span>
                    </x-button>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-4">
        {{ $supporters->links() }}
    </div>

    <div>
        <x-modal>
            <x-slot name="trigger">
                <div x-data="{ modalOpen: $wire.entangle('modalOpen') }">
                    <button class="hidden" type="button" @click="open()" x-bind:aria-expanded="modalOpen && open"
                            x-init="$watch('on', value => !value && $wire.cancel())"></button>
                </div>
            </x-slot>

            <x-slot name="modalTitle">
                {{ 'Támogató ' . ($supporterId ? 'szerkesztése' : 'hozzáadása') }}
            </x-slot>

            <x-slot name="content">
                <x-form wire:submit.prevent="save" x-data="{ isUploading: false, progress: 0 }"
                        x-on:livewire-upload-start="isUploading = true"
                        x-on:livewire-upload-finish="isUploading = false"
                        x-on:livewire-upload-error="isUploading = false"
                        x-on:livewire-upload-progress="progress = $event.detail.progress">

                    <x-form.input type="text" name="name" wire:model="name" label="Név"/>

                    <x-form.input type="text" name="link" wire:model="link" label="Link"/>

                    <x-form.input type="text" name="image_url" wire:model="image_url" label="Kép link"/>

                    <x-form.input type="file" name="newImage" wire:model.live="newImage"
                                  label="Kép"/>

                    @if ($newImage)
                        <img src="{{ $newImage->temporaryUrl() }}" class="w-32 h-32 object-contain mb-2">
                    @elseif ($image_url)
                        <img src="{{ $is_local ? asset('storage/' . $image_url) : $image_url }}"
                             class="w-32 h-32 object-contain">
                    @endif

                    <div x-show="isUploading" class="my-1">
                        <div wire:loading wire:target="newImage">Feltöltés folyamatban..</div>
                        <div class="flex w-full h-1.5 bg-gray-200 rounded-full overflow-hidden" role="progressbar"
                             aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                            <div
                                class="flex flex-col justify-center rounded-full overflow-hidden bg-blue-600 text-xs text-white text-center whitespace-nowrap transition duration-500"
                                :style="'width: ' + progress + '%'"></div>
                        </div>
                        <x-button type="button" wire:click="$cancelUpload('newImage')" class="my-1"
                                  x-on:click="isUploading = ! isUploading;">Feltöltés visszavonása
                        </x-button>
                    </div>
                </x-form>
            </x-slot>

            <x-slot name="footer">
                <div class="flex justify-between gap-3">
                    <x-button type="submit" variant="green" class="space-x-2" wire:click="save">
                        <i class="fa fa-save"></i>
                        <span>Mentés</span>
                    </x-button>
                    <x-button type="button" variant="red" class="space-x-2" @click="close()">
                        <i class="fa fa-cancel"></i>
                        <span>Mégse</span>
                    </x-button>
                </div>
            </x-slot>
        </x-modal>
    </div>
</div>
