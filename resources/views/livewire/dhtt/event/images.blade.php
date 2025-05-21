<div>
    @if ($media && $media->count() > 0)
        <div class="flex flex-wrap justify-around mx-auto p-3 gap-5" x-data="{ open: false, imageUrl: '' }">
            @foreach($media as $item)
                <img class="max-w-50 inline-block cursor-pointer object-contain"
                     src="{{ $item->getUrl('small') }}"
                     alt="{{ $item->id }}" @click="open = true; imageUrl = '{{ $item->getUrl('large') }}'"/>
            @endforeach

            <div x-show="open"
                 x-cloak
                 class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 backdrop-blur-lg"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 x-bind:class="{'overflow-hidden': open}"
                 x-init="$watch('open', value => (value ? document.body.classList.add('overflow-hidden') : document.body.classList.remove('overflow-hidden')))"
                 @click="open = false">

                <div class="max-h-[80vh] max-w-[80vw] p-1 overflow-hidden bg-white rounded-lg shadow-lg">
                    <img :src="imageUrl" class="w-full h-[50vh] object-contain cursor-pointer" @click.stop>
                </div>
            </div>
        </div>
    @else
        <h3 class="text-center my-5">Nem találhatóak képek ennél az eseménynél</h3>
    @endif
</div>
