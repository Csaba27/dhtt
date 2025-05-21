<div class="w-full py-2">
    <div class="flex">
        @foreach($steps as $key => $step)
            <div class="w-1/3">
                <div class="relative mb-2">
                    @if($key > 0)
                        <div class="absolute"
                             style="width: calc(100% - 2.5rem - 1rem); top: 50%; transform: translate(-50%, -50%)">
                            <div class="bg-gray-200 rounded flex-1">
                                <div
                                    @class([
                                        'rounded py-0.5',
                                        'bg-green-300' => ($step->isPrevious() || $step->isCurrent()),
                                        'w-full' => true
                                    ])
                                ></div>
                            </div>
                        </div>
                    @endif

                    <div class="grid place-items-center">
                        <div
                            class="w-10 h-10 rounded-full flex items-center justify-center transition-all duration-300 hover:text-white
                     text-gray-300 {{ $step->isPrevious() ? 'bg-green-600 text-white hover:bg-green-300 cursor-pointer' : ($step->isCurrent() ? 'bg-blue-500 text-gray-300' : 'cursor-pointer bg-gray-300 text-gray-600 group-hover:text-gray-500 hover:bg-blue-600') }}"
                            @if (! $step->isCurrent())
                                wire:click="{{ $step->show() }}"
                            wire:loading.attr="disabled"
                            wire:loading.class="cursor-not-allowed"
                            wire:loading.class.remove="cursor-pointer"
                            wire:target="showStep"
                            @endif
                        >
                            <i class="fa fa-check" wire:target="{{ $step->show() }}" wire:loading.remove></i>
                            <i class="fas fa-circle-notch fa-spin"
                               wire:cloak
                               wire:target="{{ $step->show() }}"
                               wire:loading.inline></i>
                        </div>
                    </div>
                </div>
                <p class="mt-2 text-md text-center md:text-base {{ $step->isCurrent() ? 'font-bold' : '' }}">{{ $step->label }}</p>
            </div>
        @endforeach
    </div>
</div>


@error('error')

<div
    class="flex items-center p-4 mb-4 text-sm text-red-800 border border-red-300 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400 dark:border-red-800"
    role="alert">
    <svg class="flex-shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
         fill="currentColor" viewBox="0 0 20 20">
        <path
            d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
    </svg>
    <span class="sr-only">Info</span>
    <div>
        <span class="font-medium">Hiba</span> {{ $message }}
    </div>
</div>

@enderror
