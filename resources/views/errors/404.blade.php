<x-layouts.plain>
    @section('title', '404')

    <!-- Pages: Errors: 404 -->
    <!-- Page Container -->
    <div
        id="page-container"
        class="mx-auto flex min-h-dvh w-full min-w-80 flex-col bg-gray-100 dark:bg-gray-900 dark:text-gray-100"
    >
        <!-- Page Content -->
        <main id="page-content" class="flex max-w-full flex-auto flex-col">
            <div
                class="relative flex min-h-dvh items-center overflow-hidden bg-white dark:bg-gray-800"
            >
                <!-- Left/Right Background -->
                <div
                    class="absolute top-0 bottom-0 left-0 -ml-44 w-48 bg-rose-50 md:-ml-28 md:skew-x-6 dark:bg-rose-500/10"
                    aria-hidden="true"
                ></div>
                <div
                    class="absolute top-0 right-0 bottom-0 -mr-44 w-48 bg-rose-50 md:-mr-28 md:skew-x-6 dark:bg-rose-500/10"
                    aria-hidden="true"
                ></div>
                <!-- END Left/Right Background -->

                <!-- Error Content -->
                <div
                    class="relative container mx-auto space-y-16 px-8 py-16 text-center lg:py-32 xl:max-w-7xl"
                >
                    <div>
                        <div class="mb-5 text-rose-300 dark:text-rose-300/50">
                            <svg
                                class="hi-outline hi-document-magnifying-glass inline-block size-12"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                                aria-hidden="true"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m5.231 13.481L15 17.25m-4.5-15H5.625c-.621 0-1.125.504-1.125 1.125v16.5c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9zm3.75 11.625a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"
                                />
                            </svg>
                        </div>
                        <div
                            class="text-6xl font-extrabold text-rose-600 md:text-7xl dark:text-rose-500"
                        >
                            404
                        </div>
                        <div
                            class="mx-auto my-6 h-1.5 w-12 rounded-lg bg-gray-200 md:my-10 dark:bg-gray-700"
                            aria-hidden="true"
                        ></div>
                        <h1 class="mb-3 text-2xl font-extrabold md:text-3xl">
                            {{ __('Well, This is Awkward...') }}
                        </h1>
                        <h2
                            class="mx-auto mb-5 font-medium text-gray-500 md:leading-relaxed lg:w-3/5 dark:text-gray-400"
                        >
                            {{ $exception->getMessage() ?: __('Looks like we can\'t find the page you\'re looking for. Maybe it\'s
                            been moved or deleted. Sorry about that!') }}
                        </h2>

                    </div>
                </div>
                <!-- END Error Content -->
            </div>
        </main>
        <!-- END Page Content -->
    </div>
    <!-- END Page Container -->
    <!-- END Pages: Errors: 404 -->


</x-layouts.plain>

