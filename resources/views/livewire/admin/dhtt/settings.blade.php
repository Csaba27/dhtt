<div>
    <h1 class="text-2xl font-semibold mb-3">DHTT beállítások</h1>

    @include('errors.messages')

    <div class="card">
        <form wire:submit.prevent="save" class="space-y-4">

            <x-form.input label="Email" wire:model="email" name="email"/>
            <x-form.input label="Telefonszám" wire:model="phone" name="phone"/>
            <x-form.input label="Facebook" wire:model="facebook" name="facebook"/>
            <x-form.textarea label="Információ" wire:model="info" name="info"/>
            <x-form.input label="Helyszín" wire:model="location" name="location"/>
            <x-form.input label="Google Térkép beágyazás linkje" wire:model="google_map" name="google_map"/>
            <x-form.input label="Szervező URL" wire:model="organizer_url" name="organizer_url"/>

            <x-form.input label="Szervező kép logo linkje" wire:model="organizer_image" name="organizer_image"/>

            @if ($organizer_image)
                <a href="{{ $organizer_image }}" target="_blank">
                    <img src="{{ $organizer_image }}" alt="logo" style="max-height: 150px;">
                </a>
            @endif

            <div class="flex justify-center">
                <x-button type="submit">Mentés</x-button>
            </div>
        </form>
    </div>
</div>
