<x-dhtt.layouts.app>
    <!-- <main class="mx-auto max-w-screen-xl px-4 sm:mt-12 sm:px-6 md:mt-15 mb-10"></main>-->

    <section id="dhtt-hero">
        <div class="hero-container">
            <img class="max-h-35 min-h-25 w-auto" src="{{ asset('img/dhtt/dhtt-logo-2024.jpg') }}" style="height: 25vh;" alt="CsEKE logo">

            @if ($event)
                @php
                    $carbonDate = Carbon\Carbon::create($event->date);
                @endphp
                <h1 class="lg:text-3xl text-xl">{{ $event->name }}</h1>
                <h2><span>{{ $event->location }}, </span>
                    <span>{{ $carbonDate->isoFormat('YYYY. MMMM D.') }}</span>
                </h2>
                @if (in_array($event->status, range(1,3)) && $carbonDate->greaterThan('now'))

                    <div class="text-white my-5">
                        <div id="countdown" class="grid grid-cols-4 gap-4">
                            <div class="time-card">
                                <span class="block text-lg font-bold">Nap</span>
                                <div class="text-3xl font-bold" id="days">00</div>
                            </div>
                            <div class="time-card">
                                <span class="block text-lg font-bold">Óra</span>
                                <div class="text-3xl font-bold" id="hours">00</div>
                            </div>
                            <div class="time-card">
                                <span class="block text-lg font-bold">Perc</span>
                                <div class="text-3xl font-bold" id="minutes">00</div>
                            </div>
                            <div class="time-card">
                                <span class="block text-lg font-bold">Másodperc</span>
                                <div class="text-3xl font-bold" id="seconds">00</div>
                            </div>
                        </div>
                    </div>

                    <script>
                        const eventDate = new Date('{{ $event->date }}').getTime();
                        const registrationStart = new Date('{{ $event->registration_start }}').getTime();
                        const discountUntil = new Date('{{ $event->registration_discount_until }}').getTime();
                        const registrationEnd = new Date('{{ $event->registration_end }}').getTime();

                        function updateCountdown() {
                            const now = new Date().getTime();
                            const timeLeft = eventDate - now;

                            if (timeLeft <= 0) {
                                return;
                            }

                            const days = Math.floor(timeLeft / (1000 * 60 * 60 * 24));
                            const hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                            const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
                            const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);

                            document.getElementById("days").innerText = days.toString().padStart(2, '0');
                            document.getElementById("hours").innerText = hours.toString().padStart(2, '0');
                            document.getElementById("minutes").innerText = minutes.toString().padStart(2, '0');
                            document.getElementById("seconds").innerText = seconds.toString().padStart(2, '0');

                            let bgColor = "bg-gray-700";

                            if (now >= registrationStart && now < discountUntil) {
                                bgColor = "bg-lime-700"; // Kedvezményes időszak
                            } else if (now >= discountUntil && now < registrationEnd) {
                                bgColor = "bg-slate-800"; // Kedvezmény lejárt, még regisztráció van
                            } else if (now >= registrationStart) {
                                bgColor = "bg-gray-700"; // Regisztráció elkezdődött
                            }

                            document.querySelectorAll(".time-card").forEach(card => {
                                card.className = `time-card p-3 lg:p-5 rounded-lg text-center border-t-4 border-t shadow-xl ${bgColor}`;
                            });
                        }

                        updateCountdown();
                        setInterval(updateCountdown, 1000);
                    </script>

                    <a href="{{ route('dhtt.registration') }}" class="btn btn-registration">Regisztráció</a>
                @endif
            @endif
        </div>
    </section>

    @if ($event)
        <section id="dhtt-invitation" class="py-5">
            <div class="container mx-auto px-4">
                <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                    <div class="flex-1">
                        <h1 class="dhtt-h1">Meghívó</h1>
                        <div class="text-justify">
                            {!! $event->invitation !!}
                        </div>
                    </div>
                    <div class="max-w-xl">
                        <img src="{{ asset('/img/dhtt/dhtt-mozaik.jpg') }}" class="img-fluid max-w-full" alt="DHTT mozaik kép">
                    </div>
                </div>
            </div>
        </section>

    @endif

    <livewire:dhtt.event.routes :$event />

    @if ($supporters)
        <section id="dhtt-supporters" class="py-5">
            <div class="container mx-auto px-4">
                <h1 class="dhtt-h1">Támogatók</h1>
                <div class="flex flex-wrap justify-start">
                    @foreach ($supporters as $supporter)
                        <div class="w-1/4 p-2">
                            <a href="{{ $supporter->link ?: '#' }}">
                                <img
                                    src="{{ $supporter->image_path }}"
                                    class="img-fluid" alt="{{ $supporter->name }}">
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

    @endif

    <x-dhtt.contact/>
    <x-dhtt.layouts.footer/>
</x-dhtt.layouts.app>
