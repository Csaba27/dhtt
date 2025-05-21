<div>
    <main class="mb-4">
        @include('livewire.admin.dhtt.events.steps.navigation', ['steps' => $steps])

        <div class="card">
            <x-form wire:submit.prevent="submit">

                <div x-data="{ active: $wire.entangle('active') }" class="space-y-3 mb-3">
                    <div
                        @click="active = !active"
                        class="relative w-16 h-8 flex items-center rounded-full p-1 cursor-pointer transition-colors duration-300 bg-gray-300"
                        :class="active ? 'bg-green-500' : 'bg-red-500'"
                    >
                        <div
                            class="bg-white w-6 h-6 rounded-full shadow-md transform transition-transform duration-300"
                            :class="active ? 'translate-x-8' : 'translate-x-0'"
                        ></div>
                    </div>

                    <div class="text-sm font-semibold" x-text="active ? 'Aktív' : 'Inaktív'"></div>

                    <p class="text-sm text-gray-500 dark:text-gray-300">
                        <i class="fa fa-info-circle"></i>
                        Csak akkor jelenik meg a főoldalon az új esemény, ha aktívra tetted. Lejárt eseményeknél pedig
                        az archívumba kerül, ha már nem aktív.
                    </p>
                </div>

                <div class="flex mb-3 lg:gap-3 md:gap-1 flex-col lg:flex-row">
                    <div class="lg:w-1/2">
                        <x-form.input type="text" label="Rövid név" wire:model="short_name"
                                      name="short_name"/>
                    </div>
                    <div class="lg:w-1/2">
                        <x-form.input type="text" label="Név" wire:model="name" name="name"/>
                    </div>
                </div>

                <div class="flex mb-3 lg:gap-3 md:gap-1 lg:flex-row flex-col md:flex-nowrap">
                    <div class="lg:w-1/4">
                        <x-form.input type="number" label="Év" wire:model="year" name="year"/>
                    </div>
                    <div class="lg:w-1/4">
                        <x-form.time type="date" label="Dátum" wire:model="date"
                                     name="date"
                                     :options="['dateFormat' => 'Y-m-d', 'noCalendar' => false, 'enableTime' => false]"/>
                    </div>
                    <div class="lg:w-1/2">
                        <x-form.input type="text" label="Helység" wire:model="location" name="location"/>
                    </div>
                </div>

                <div class="flex mb-3 lg:gap-3 md:gap-1 lg:flex-row flex-col md:flex-nowrap">
                    <div class="lg:w-1/4">
                        <x-form.input type="number" label="Részvételi díj" wire:model="entry_fee"
                                      name="entry_fee"/>
                    </div>
                    <div class="lg:w-1/4">
                        <x-form.input type="number" label="Kedvezmény 1" wire:model="discount1"
                                      name="discount1"/>
                        <p class="text-sm text-gray-500 dark:text-gray-300">Tagság és diák</p>
                    </div>

                    <div class="lg:w-1/4">
                        <x-form.input type="number" label="Kedvezmény 2" wire:model="discount2"
                                      name="discount2"/>
                        <p class="text-sm text-gray-500 dark:text-gray-300">Korai regisztráció</p>
                    </div>
                </div>

                <div class="flex mb-3 lg:gap-3 md:gap-1 lg:flex-row flex-col md:flex-nowrap">
                    <div class="lg:w-1/4">
                        <x-form.timeday label="Regisztráció kezdtete" wire:model="registration_start"
                                        name="registration_start"
                                        :options="['dateFormat' => 'Y-m-d H:i', 'noCalendar' => false]"/>
                    </div>
                    <!--
                    <div class="lg:w-1/4">
                        <x-form.input type="text" label="Regisztráció vége" wire:model="registration_end"
                                      name="registration_end" readonly/>
                    </div>-->
                    <div class="lg:w-1/4">
                        <x-form.time type="date" label="Kedvezmény lejárata"
                                     wire:model="registration_discount_until" name="registration_discount_until"
                                     :options="['dateFormat' => 'Y-m-d H:i', 'noCalendar' => false]"/>
                    </div>
                </div>

                <div class="flex mb-3 lg:gap-3 md:gap-1 lg:flex-row flex-col md:flex-nowrap">
                    <div class="lg:w-1/4">
                        <x-form.input type="text" label="Szervező neve" wire:model="organizer_name"
                                      name="organizer_name"/>
                    </div>
                    <div class="lg:w-1/4">
                        <x-form.input type="email" label="Szervező e-mail" wire:model="organizer_email"
                                      name="organizer_email"/>
                    </div>
                    <div class="lg:w-1/4">
                        <x-form.input type="text" label="Szervező telefonszám" wire:model="organizer_phone"
                                      name="organizer_phone"/>
                    </div>
                </div>

                <div class="row mb-3">
                    <x-form.text-editor wire:model="rules" name="rules" label="Szabályzat"></x-form.text-editor>
                </div>
                <!--
                                <div class="flex mb-3 lg:gap-5 md:gap-1 lg:flex-row flex-col md:flex-nowrap items-center">
                                    <div class="lg:w-1/4">
                                        <x-form.checkbox label="Megjelenítés" wire:model="show" name="show"/>
                                    </div>
                                </div>
                -->
                <div class="flex justify-end">
                    <x-button type="submit">Tovább</x-button>
                </div>
            </x-form>
        </div>
    </main>
</div>
