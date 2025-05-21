<div>
    <h1 class="text-2xl font-semibold mb-3">Galéria</h1>

    @if (!$event)
        <div class="alert alert-danger">Esemény nem létezik vagy nincs kiválasztva!</div>
    @else

        <div class="text-gray-400 dark:text-gray-300 text-md mb-1">{{ $event->name }}</div>

        @include('errors.messages')

        <x-form
            class="my-5"
            wire:submit="addImages"
            x-data="{ uploading: false, progress: 0 }"
            x-on:livewire-upload-start="uploading = true"
            x-on:livewire-upload-finish="uploading = false"
            x-on:livewire-upload-cancel="uploading = false"
            x-on:livewire-upload-error="uploading = false"
            x-on:livewire-upload-progress="progress = $event.detail.progress">

            <div class="mb-4 flex justify-center items-center flex-col">
                <div
                    x-on:click="$refs.fileInput.click()"
                    class="border-2 border-dashed border-gray-400 p-6 rounded-md text-center cursor-pointer max-w-4xl w-full hover:border-blue-500 transition-all duration-300"
                >

                    <input type="file" multiple wire:model="newImages" id="newImages" class="hidden" accept="image/*"
                           x-ref="fileInput">

                    <p class="text-gray-600 dark:text-gray-100"><i class="fa fa-image"></i>
                        <span>Képek feltöltése</span></p>

                    <div class="mt-4 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                        @foreach($newImages as $index => $image)
                            <div class="relative">
                                <img src="{{ $image->temporaryUrl() }}" alt="Preview Image"
                                     class="w-full h-32 object-cover mb-2 rounded">
                                <button type="button" wire:click.prevent="removeImage({{ $index }})" @click.stop
                                        class="absolute top-0 right-0 bg-red-500 text-white rounded-full p-1">
                                    <i class="fa fa-times"></i>
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>

                @error('newImages.*')
                <p class="error">{{ $message }}</p>
                @enderror

                <div class="mt-2">
                    <x-button type="submit" class="btn btn-primary">
                        <i class="fa fa-plus" wire:loading.remove wire:target="addImages"></i><span class="ml-1">Feltöltés</span>
                    </x-button>
                </div>
            </div>

            <div wire:target="newImages" class="mb-3" x-show="uploading">
                <div class="text-center mt-2">
                    <p>Feltöltés folyamatban...</p>
                    <div class="w-full bg-gray-200 rounded-full h-1.5 dark:bg-gray-700">
                        <div class="bg-blue-600 h-1.5 rounded-full" :style="'width: ' + progress + '%'"></div>
                    </div>
                </div>
            </div>
        </x-form>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4" wire:loading.class="opacity-50 cursor-wait" wire:target="moveUp,moveDown,deleteImage">
            @foreach($images as $image)
                <div
                    class="bg-white dark:bg-gray-700 dark:border-gray-600 dark:shadow-gray-400/10 shadow p-4 rounded text-center">
                    <img src="{{ $image->getUrl() }}" alt="Gallery Image" class="w-full h-32 object-cover mb-2">
                    <div class="flex justify-around">
                        <button wire:click="moveUp({{ $image->id }})"
                                class="bg-yellow-500 text-white px-2 py-1 rounded">
                            <i class="fa fa-arrow-up"></i>
                        </button>
                        <button wire:click="moveDown({{ $image->id }})"
                                class="bg-yellow-500 text-white px-2 py-1 rounded">
                            <i class="fa fa-arrow-down"></i>
                        </button>
                        <button wire:click="deleteImage({{ $image->id }})"
                                class="bg-red-500 text-white px-2 py-1 rounded">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
