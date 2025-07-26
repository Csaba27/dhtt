<div class="max-w-5xl mx-auto p-6 bg-white dark:bg-gray-800 shadow rounded text-gray-800 dark:text-gray-100">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Sablonok</h1>
        @if (! $editing)
            <x-button wire:click="create" variant="green">
                <i class="fas fa-plus mr-2"></i> Új sablon
            </x-button>
        @endif
    </div>

    @include('errors.messages')

    @if ($formOpen)
        <div class="mb-6">
            <button wire:click="cancel" class="flex items-center text-sm text-blue-500 hover:underline mb-4 cursor-pointer">
                <i class="fas fa-arrow-left mr-2"></i> Vissza a listához
            </button>

            <form wire:submit.prevent="save">
                <div class="mb-4">
                    <x-form.input type="text" name="title" label="Cím" wire:model="title"></x-form.input>
                </div>

                <div class="mb-4">
                    <x-form.select wire:model="type" name="type" label="Típus" placeholder="Típus kiválasztása">
                        @foreach ($this->templateTypes() as $key => $value)
                            <x-form.select-option value="{{ $key }}">{{ $value }}</x-form.select-option>
                        @endforeach
                    </x-form.select>
                </div>

                <div class="mb-4">
                    <x-form.text-editor wire:model="content" name="content" label="Sablon tartalma"></x-form.text-editor>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex space-x-2">
                        <x-button type="submit" variant="blue">
                            <i class="fas fa-save mr-1"></i> <span class="ml-1">Mentés</span>
                        </x-button>
                        <x-button type="button" wire:click="cancel" variant="red">
                            <i class="fas fa-cancel mr-1"></i> <span class="ml-1">Mégse</span>
                        </x-button>
                    </div>
                    <div class="flex items-center space-x-2">
                        <label class="text-sm font-medium" for="isActive">Aktív:</label>
                        <input type="checkbox" wire:model="isActive" name="isActive" label="none"
                               class="form-checkbox h-5 w-5 text-green-500"/>
                    </div>
                </div>
            </form>
        </div>
    @else
        <ul class="space-y-2 mb-6">
            @foreach ($this->templates as $template)
                <li class="flex justify-between items-center bg-gray-100 dark:bg-gray-800 p-3 rounded">
                    <div class="flex items-center space-x-2">
                        <span class="font-medium">{{ $template->title }}</span>
                        <x-badge variant="blue">{{ $this->templateTypes()[$template->type] ?? 'Egyéb' }}</x-badge>
                        @if (! $template->is_active)
                            <x-badge variant="red">Inaktív</x-badge>
                        @endif
                    </div>
                    <div class="space-x-2 text-sm">
                        <button type="button" wire:click="edit({{ $template->id }})"
                                class="text-blue-600 dark:text-blue-400 hover:underline cursor-pointer">
                            <i class="fas fa-edit mr-1"></i> Szerkesztés
                        </button>
                        <button type="button" wire:click="delete({{ $template->id }})"
                                wire:confirm="Biztos törölni szeretnéd?"
                                class="text-red-600 dark:text-red-400 hover:underline cursor-pointer">
                            <i class="fas fa-trash-alt mr-1"></i> Törlés
                        </button>
                    </div>
                </li>
            @endforeach
        </ul>
    @endif
</div>
