<div>
    <section id="dhtt-contact" class="mt-5 py-5">
        <div class="container mx-auto px-4">
            <h1 class="dhtt-h1">Kapcsolat</h1>
            <div class="flex flex-col md:flex-row md:justify-center gap-6">
                <div class="md:w-1/4 flex flex-col items-center">
                    <div class="text-center">
                        <h3 class="text-xl mb-3">Szervező:</h3>
                        <a href="{{ setting('dhtt_organizer_url', '') }}">
                            <img src="{{ setting('dhtt_organizer_image', '') }}" class="h-48" alt="Logo">
                        </a>
                    </div>
                </div>
                <div class="md:w-2/5">
                    <div class="info">
                        <h3 class="text-xl mb-3">Információk:</h3>
                        <h6 class="mb-4">
                            {{ setting('dhtt_info', '') }}
                        </h6>
                        <div class="flex items-center gap-2 mb-2">
                            <i class="fas fa-phone-square-alt"></i>
                            <p>{{ setting('dhtt_phone', '') }}</p>
                        </div>
                        <div class="flex items-center gap-2 mb-2">
                            <i class="fas fa-envelope"></i>
                            <p><a href="mailto:{{ setting('dhtt_email', '') }}"
                                  class="hover:underline">{{ setting('dhtt_email', '') }}</a></p>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fab fa-facebook"></i>
                            <p>{!! setting('dhtt_facebook', '') !!}</p>
                        </div>
                    </div>
                </div>
                <div class="md:w-2/5">
                    <div class="info">
                        <h3 class="text-xl mb-3">Helyszín:</h3>
                        <div class="flex items-center gap-2 mb-2">
                            <i class="fas fa-map-marker-alt"></i>
                            <p>{{ setting('dhtt_location', '') }}</p>
                        </div>
                        <div class="map w-full h-64">
                            <iframe class="w-full h-full rounded-lg"
                                    src="{{ setting('dhtt_google_map', '') }}">
                            </iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
