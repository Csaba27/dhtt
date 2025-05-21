<div x-data="{ confirmAgeModal: false, ageConfirm: false, confirmEntryFeeModal: false, entryFeeEditable: false }">
    <main class="mb-4">

        <x-dhtt.breadcrumb :items="[
            ['label' => 'DHTT', 'url' => route('admin.dhtt.index')],
            ['label' => 'Jelentkezők', 'url' =>  route('admin.dhtt.index', ['tab' => 'participants', 'event' => $event])],
            ['label' =>  ($isEdit ? 'Szerkesztés' : 'Hozzáadás')]
        ]"/>

        <div class="text-gray-400 dark:text-gray-300 text-md mb-1">{{ $event->name }}</div>

        @include('errors.messages')

        <div class="card">

            <x-form @submit.prevent="ageConfirm ? (confirmAgeModal = true) : $wire.submit()">

                <div class="mb-3 w-full">
                    <div class="mt-1 bg-white dark:bg-gray-500 dark:text-gray-200 rounded-md shadow-sm -space-y-px">

                        <div class="relative border rounded-md p-4 flex border-gray-200">
                            <div class="flex items-center h-5">
                                <input
                                    @change="$wire.number != 0 && $event.target.checked && ! confirm('Biztos új rajtszámot generálsz ennek a jelentkezőnek?') && ($event.target.checked = false)"
                                    wire:model="generateNumber" id="generateNumber" type="checkbox"
                                    class="h-4 w-4 text-light-blue-600 cursor-pointer focus:ring-light-blue-500 border-gray-300">
                                <label
                                    for="generateNumber"
                                    class="ml-3 block text-md font-medium cursor-pointer select-none text-gray-900 dark:text-gray-300">
                                    @if ($number == 0)
                                        Rajtszám generálás
                                    @else
                                        Új rajtszám generálás
                                    @endif
                                </label>
                            </div>
                        </div>
                    </div>
                    @error('generateNumber')
                    <p class="error" aria-live="assertive">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex space-x-4 lg:flex-row flex-col mb-3">
                    <div class="flex-1">
                        <x-form.input type="text" label="Név" wire:model="name" name="name"/>
                    </div>
                    <div class="flex-1">
                        <x-form.input type="text" label="Város" wire:model="city" name="city"/>
                    </div>
                    <div class="flex-1">
                        <x-form.input type="text" label="Telefonszám" wire:model="phone" name="phone"/>
                    </div>
                </div>

                <div class="flex space-x-4 mb-3">
                    <div class="flex-1">
                        <x-form.select wire:model="age" name="age" label="Életkor"
                                       placeholder="Válaszd ki az életkort"
                                       @blur="ageConfirm = $wire.age && $wire.age <= 14">
                            @foreach(range(1, 100) as $currentAge)
                                <x-form.select-option value="{{ $currentAge }}"
                                                      :selected="$age">{{ $currentAge }}</x-form.select-option>
                            @endforeach
                        </x-form.select>
                    </div>

                    <div class="flex-1">
                        <x-form.select wire:model="member" name="member" label="Tagság">
                            <x-form.select-option value="" :selected="$member">Nincs</x-form.select-option>
                            @foreach(\App\Models\Association::all() as $association)
                                <x-form.select-option value="{{ $association->id }}"
                                                      :selected="$member">{{ $association->name }}</x-form.select-option>
                            @endforeach

                        </x-form.select>
                    </div>

                    <div class="flex-1">
                        <x-form.select wire:model="isStudent" name="isStudent" label="Diák-e">
                            <x-form.select-option value="1"
                                                  selected="{{ $isStudent }}">Igen
                            </x-form.select-option>
                            <x-form.select-option value="0"
                                                  selected="{{ $isStudent }}">Nem
                            </x-form.select-option>
                        </x-form.select>
                    </div>
                </div>

                <div class="flex space-x-4 mb-3">
                    <div class="flex-1">
                        <x-form.input type="text" label="Rajtszám" readonly
                                      placeholder="{{ ($modalOpen ? 'Automatikusan generálva mentéskor' : ($number == 0 ? 'Még nincs rajtszám generálva' : '')) }}">{{ ($number > 0 && ! $modalOpen ? $number : '') }}</x-form.input>
                    </div>

                    <div class="flex-1">
                        <div class="mb-5">
                            <label for="entryFee" class="block mb-2 font-bold text-sm text-gray-600 dark:text-gray-200">
                                Részvételi díj
                            </label>
                            <div class="flex items-center">
                                <div class="rounded-md shadow-sm">
                                    <input
                                        type="number"
                                        id="entryFee"
                                        name="entryFee"
                                        wire:model="entryFee"
                                        :readonly="! entryFeeEditable"
                                        class="block w-full bg-white dark:bg-gray-500 dark:text-gray-200 dark:placeholder-gray-200 border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-light-blue-500 focus:border-light-blue-500 sm:text-sm read-only:bg-gray-100 dark:read-only:bg-gray-700 read-only:text-gray-800 dark:read-only:text-gray-200"
                                        step="0.01"
                                        min="0.0">
                                </div>
                                <button
                                    type="button"
                                    @click="! entryFeeEditable && (confirmEntryFeeModal = true)"
                                    class="ml-2 text-gray-500 dark:text-gray-300 hover:text-gray-700 dark:hover:text-gray-200 cursor-pointer"
                                    :class="entryFeeEditable ? 'text-green-500' : 'text-blue-500'">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </div>

                        </div>

                        <div class="text-sm text-amber-300 font-medium" wire:dirty wire:target="isStudent,member">
                            <i class="fa fa-circle-info"></i>
                            <span>Részvételi díj esetleg újraszámolást igényelhet</span>
                        </div>
                    </div>
                </div>

                <div class="flex space-x-4 mb-3">
                    <div class="flex-1">
                        <x-form.label label="Kezdési idő" name="startTime"></x-form.label>
                        <div class="flex items-center gap-3">
                            <input type="time"
                                   wire:model="startTime"
                                   id="startTime"
                                   value="{{ $startTime }}"
                                   step="1"
                                   class="block w-full bg-white dark:bg-gray-500 dark:text-gray-200 dark:placeholder-gray-200 border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-light-blue-500 focus:border-light-blue-500 sm:text-sm read-only:bg-gray-100 dark:read-only:bg-gray-700 read-only:text-gray-800 dark:read-only:text-gray-200">
                            <x-button type="button" variant="gray"
                                      wire:click="insertStartTime">Start
                            </x-button>
                        </div>
                        @error('startTime')
                        <p class="error" aria-live="assertive">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex-1">
                        <x-form.label label="Érdkezési idő" name="finishTime"></x-form.label>
                        <div class="flex items-center gap-3">
                            <input type="time"
                                   wire:model="finishTime"
                                   id="finishTime"
                                   value="{{ $finishTime }}"
                                   step="1"
                                   class="block w-full bg-white dark:bg-gray-500 dark:text-gray-200 dark:placeholder-gray-200 border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-light-blue-500 focus:border-light-blue-500 sm:text-sm read-only:bg-gray-100 dark:read-only:bg-gray-700 read-only:text-gray-800 dark:read-only:text-gray-200">
                            <x-button type="button" variant="gray"
                                      wire:click="insertFinishTime">Stop
                            </x-button>
                        </div>
                        @error('finishTime')
                        <p class="error" aria-live="assertive">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex-1">
                        <x-form.select wire:model="status" name="status" label="Státusz"
                                       placeholder="Státusz kiválasztása">
                            @foreach(\App\Enums\ParticipantStatus::toArray() as $value => $text)
                                <x-form.select-option value="{{ $value }}"
                                                      :selected="$status">{{ $text }}</x-form.select-option>
                            @endforeach
                        </x-form.select>
                    </div>
                </div>

                <hr class="my-3">

                <div class="mb-3">
                    <h3>Túra hozzárendelés</h3>
                    <div class="flex items-center gap-4 flex-wrap">
                        @foreach ($event->hikes as $hike)
                            <div class="flex items-center">
                                <input type="radio" id="hike-radio-{{ $hike->id }}"
                                       value="{{ $hike->id }}"
                                       wire:model="hikeId"
                                       class="hidden peer">

                                <label for="hike-radio-{{ $hike->id }}" wire:key="hike{{ $hike->id }}"
                                       class="flex items-center cursor-pointer px-3 py-1.5 bg-gray-100 dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg peer-checked:bg-blue-500 dark:peer-checked:bg-blue-600 peer-checked:text-white transition">
                                    <span class="text-sm font-medium">
                                        {{ $hike->name }}
                                    </span>
                                </label>
                            </div>
                        @endforeach
                    </div>

                    @error('hikeId')
                    <p class="error" aria-live="assertive">{{ $message }}</p>
                    @enderror
                </div>
                {{--
                <div class="w-full mb-3">
                    <x-form.textarea name="notes" label="Jegyzet" cols="10" rows="6"
                                     wire:model="notes"></x-form.textarea>
                </div>
                --}}

                <div class="flex justify-center gap-3">
                    <x-button type="button" variant="red" class="mr-1" wire:click="cancel">Mégse</x-button>
                    <x-button type="submit" class="px-6" x-show="! ($wire.generateNumber && ! $wire.number)">Mentés
                    </x-button>
                    <x-button type="submit" variant="green" x-show="$wire.generateNumber && ! $wire.number">Mentés és
                        rajtoltatás
                    </x-button>
                </div>
            </x-form>
        </div>
    </main>

    @if ($modalOpen)
        <x-modal>
            <x-slot name="trigger">
                <div x-data="{ modalOpen: $wire.entangle('modalOpen') }">
                    <button class="hidden" type="button" @click="open()" x-bind:aria-expanded="modalOpen && open"
                            x-init="$watch('on', value => !value && ($wire.modalOpen = false))"></button>
                </div>
            </x-slot>

            <x-slot name="modalTitle">
                Rajtolási információk
            </x-slot>

            <x-slot name="content">
                <div class="mb-3">
                    <p><span class="font-semibold">Név:</span> {{ $participant->name }}</p>
                    <p><span class="font-semibold">Túra:</span> {{ $participant->hike->name }}</p>
                    <p><span class="font-semibold">Indulási idő:</span> {{ $participant->start_time }}</p>
                </div>

                <div class="bg-yellow-100 text-yellow-800 p-3 rounded shadow mb-3">
                    <span class="font-semibold text-black text-md">Fizetendő összeg:</span>
                    <span class="text-green-600 font-bold text-lg ml-1">{{ number_format($participant->entry_fee, 2) }} RON</span>
                </div>

                <div class="bg-blue-100 text-blue-800 p-4 rounded shadow text-center mb-3">
                    <p class="font-semibold text-black text-md pb-1">Rajtszám:</p>
                    <p class="text-5xl font-bold text-blue-700">{{ $participant->number }}</p>
                </div>
            </x-slot>

            <x-slot name="footer">
                <div class="flex justify-end">
                    <x-button type="button" variant="blue" @click="close()">Bezárás</x-button>
                </div>
            </x-slot>
        </x-modal>
    @endif

    <x-modal>
        <x-slot name="trigger">
            <button
                x-init="$watch('confirmAgeModal', value => value && open()); $watch('on', value => ! value && (confirmAgeModal = false))"></button>
        </x-slot>
        <x-slot name="modalTitle">
            Kor megerősítése
        </x-slot>
        <x-slot name="content">
            Biztos, hogy jól töltötted ki az életkort? Kérlek erősítsd meg!
        </x-slot>

        <x-slot name="footer">
            <x-button type="button" variant="red" @click="close()">Mégse</x-button>
            <x-button type="button" variant="green" @click="ageConfirm = false, close()">Megerősít</x-button>
        </x-slot>
    </x-modal>

    <x-modal>
        <x-slot name="trigger">
            <button
                x-init="$watch('confirmEntryFeeModal', value => value && open()); $watch('on', value => ! value && (confirmEntryFeeModal = false))"></button>
        </x-slot>
        <x-slot name="modalTitle">
            Részvételi díj szerkesztésének megerősítése
        </x-slot>
        <x-slot name="content">
            Biztos, hogy szerkeszteni akarod a részvételi díjat? Kérlek erősítsd meg!
        </x-slot>

        <x-slot name="footer">
            <x-button type="button" variant="red" @click="close()">Mégse</x-button>
            <x-button type="button" variant="green" @click="entryFeeEditable = true, close()">Megerősít</x-button>
        </x-slot>
    </x-modal>

</div>
