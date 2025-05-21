<x-dhtt.layouts.app>
    <div class="dhttbreadcrumb">
        <h1>Regisztráció</h1>
    </div>

    <section class="py-5 dark:bg-gray-700">
        <div class="container mx-auto max-w-screen-xl">
            @if ($event)
                <h2>{{ $event->name }}</h2>
            @endif

            @if ($status == 1 || $status == 2)
                <div class="card">
                    <div class="text-lg mb-4">Jelentkező adatai</div>
                    <livewire:dhtt.registration :$event key="dhtt.registration.form"/>
                </div>
            @elseif($status == 3)
                <x-alert variant="yellow">
                    <p class="text-center w-full">Az online regisztráció lezárva, már csak a helyszínen lehet
                        regisztrálni!</p>
                </x-alert>
            @else
                <x-alert>
                    <p class="text-center w-full">Pillanatnyilag nincs aktív esemény!</p>
                </x-alert>
            @endif

            @if ($status == 1 || $status == 2 || $status == 3)
                <livewire:dhtt.registration-list :$event key="dhtt.registration.list"/>
            @endif
        </div>
    </section>

    <x-dhtt.layouts.footer/>
</x-dhtt.layouts.app>
