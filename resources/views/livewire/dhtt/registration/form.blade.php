<div x-data="{ confirmAgeModal: false, ageConfirm: false }">

    @include('errors.messages')

    <x-form method="post"
            @submit.prevent="ageConfirm ? (confirmAgeModal = true) : $wire.submit()">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="form-group">
                <x-form.input name="name" label="Név" wire:model="name"/>
            </div>
            <div class="form-group">
                <x-form.input name="city" label="Város" wire:model="city"/>
            </div>
            <div class="form-group">
                <x-form.input name="phone" label="Telefonszám" wire:model="phone"/>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-4">
            <div class="form-group">
                <x-form.select name="member" wire:model="member" label="Egyesületi tagság">
                    <x-form.select-option value="" :selected="$member">Nem
                    </x-form.select-option>
                    @foreach ($associations as $association)
                        <x-form.select-option value="{{ $association->id }}"
                                              :selected="$member">{{ $association->name }}</x-form.select-option>
                    @endforeach
                </x-form.select>
            </div>
            <div class="form-group">
                <x-form.select wire:model="age" name="age" label="Életkor" placeholder="Válaszd ki az életkorod"
                               @blur="ageConfirm = $wire.age && $wire.age <= {{ $minimumConfirmAge }}">
                    @foreach(range(1, 100) as $currentAge)
                        <x-form.select-option value="{{ $currentAge }}"
                                              :selected="$age">{{ $currentAge }}</x-form.select-option>
                    @endforeach
                </x-form.select>
            </div>
            <div class="form-group">
                <x-form.select name="is_student" wire:model="is_student" label="Diák">
                    <x-form.select-option value="0" :selected="$is_student">Nem
                    </x-form.select-option>
                    <x-form.select-option value="1" :selected="$is_student">Igen
                    </x-form.select-option>
                </x-form.select>
            </div>
            <div class="form-group">
                <x-form.select name="hike" label="Túratípus" wire:model="hike" placeholder="Túra kiválasztása">
                    @foreach ($hikes as $hike)
                        <x-form.select-option value="{{ $hike->id }}"
                                              :selected="$this->hike">{{ $hike->name }}</x-form.select-option>
                    @endforeach
                </x-form.select>
            </div>
        </div>

        <div class="form-group mt-4" x-data="{ open: false }">
            <x-modal>
                <x-slot name="trigger">
                    <x-form.checkbox name="terms" wire:model="terms" value="1" label="Elfogadom a feltételeket"/>

                    <div class="ml-2 text-sm text-gray-400" for="terms">
                        A <a href="#" @click.prevent="open()" class="text-blue-500 hover:underline">részvételi
                            feltételeket</a> itt olvashatod el
                    </div>
                    @error('terms')
                    <p class="error">{{ $message }}</p>
                    @enderror
                </x-slot>
                <x-slot name="modalTitle">
                    Részvételi feltételek
                </x-slot>
                <x-slot name="content">
                    <div class="p-4">
                        {!! $this->termsContent !!}
                    </div>
                </x-slot>

                <x-slot name="footer">
                    <x-button type="button" variant="red" @click="close()">Bezár</x-button>
                </x-slot>
            </x-modal>
        </div>

        <div class="form-group mt-4 flex justify-center">
            <x-button type="submit" variant="green">Regisztrálás</x-button>
        </div>
    </x-form>

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
</div>
