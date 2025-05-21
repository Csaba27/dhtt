@props([
    'name' => '',
    'label' => '',
    'required' => false
])

@if ($label == '')
    @php
        //remove underscores from name
        $label = str_replace('_', ' ', $name);
        //detect subsequent letters starting with a capital
        $label = preg_split('/(?=[A-Z])/', $label);
        //display capital words with a space
        $label = implode(' ', $label);
        //uppercase first letter and lower the rest of a word
        $label = ucwords(strtolower($label));
    @endphp
@endif
<div wire:ignore class="mt-5">
    @if ($label !='none')
        <label aria-label="{{ $label }}" for="{{ $name }}"
               class="block text-sm font-medium leading-5 text-gray-700 dark:text-gray-200 mb-2">{{ $label }} @if ($required != '')
                <span class="error">*</span>
            @endif</label>
    @endif
    <textarea
            id="{{ $name }}"
            x-data
            x-init="
            if (!window.Jodit) {
                @php
                    $vite = Vite::__invoke('resources/js/modules/jodit.js');
                    preg_match('/<link[^>]+rel="stylesheet"[^>]+href="([^"]+)"/i', $vite, $matches);
                @endphp

                @isset($matches[1])
                    const link = document.createElement('link');
                    link.rel = 'stylesheet';
                    link.href = '{{ $matches[1] }}';
                    link.setAttribute('data-navigate-track', 'reload');
                    document.head.appendChild(link);
                @endisset

                await import('{{ Vite::asset('resources/js/modules/jodit.js') }}');

                while (!window.Jodit) {
                    await new Promise(resolve => setTimeout(resolve, 50));
                }
            }

            const editor = Jodit.make($refs.item, {
                language: '{{ app()->getLocale() }}',
                minHeight: 300,
                autofocus: true,
                toolbarStick: true,
                cleanHTML: {
                    removeEmpty: true,
                    fillEmptyParagraph: false
                },
                toolbarButtonSize: 'large',
            });

            await editor.waitForReady();

            editor.events.on('change', function () {
                const content = editor.getEditorValue();
                 if (content === '<p><br></p>') {
                        editor.setEditorValue('');
                 }
                 @this.set('{{ $name }}', editor.getEditorValue(), false);
                 //@this.{{ $name }} = editor.getEditorValue();
            });
        "
            x-ref="item"
            {{ $attributes }}
            @error($name)
            aria-invalid="true"
            aria-description="{{ $message }}"
        @enderror
    >
        {{ $slot }}
    </textarea>
</div>
@error($name)
<p class="error" aria-live="assertive">{{ $message }}</p>
@enderror
