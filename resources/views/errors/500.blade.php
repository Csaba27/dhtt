<x-layouts.plain>
    @section('title', '500')

    <!-- Pages: Errors: 500 -->
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
                    class="absolute top-0 bottom-0 left-0 -ml-44 w-48 bg-red-50 md:-ml-28 md:skew-x-6 dark:bg-red-500/10"
                    aria-hidden="true"
                ></div>
                <div
                    class="absolute top-0 right-0 bottom-0 -mr-44 w-48 bg-red-50 md:-mr-28 md:skew-x-6 dark:bg-red-500/10"
                    aria-hidden="true"
                ></div>
                <!-- END Left/Right Background -->

                <!-- Error Content -->
                <div
                    class="relative container mx-auto space-y-16 px-8 py-16 text-center lg:py-32 xl:max-w-7xl"
                >
                    <div>
                        <div class="mb-5 text-red-300 dark:text-red-300/50">
                            <svg
                                class="hi-outline hi-server inline-block h-21 w-12"
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
                                    d="M21.75 17.25v-.228a4.5 4.5 0 00-.12-1.03l-2.268-9.64a3.375 3.375 0 00-3.285-2.602H7.923a3.375 3.375 0 00-3.285 2.602l-2.268 9.64a4.5 4.5 0 00-.12 1.03v.228m19.5 0a3 3 0 01-3 3H5.25a3 3 0 01-3-3m19.5 0a3 3 0 00-3-3H5.25a3 3 0 00-3 3m16.5 0h.008v.008h-.008v-.008zm-3 0h.008v.008h-.008v-.008z"
                                />
                            </svg>
                        </div>
                        <div
                            class="text-6xl font-extrabold text-red-600 md:text-7xl dark:text-red-500"
                        >
                            500
                        </div>
                        <div
                            class="mx-auto my-6 h-1.5 w-12 rounded-lg bg-gray-200 md:my-10 dark:bg-gray-700"
                            aria-hidden="true"
                        ></div>
                        <h1 class="mb-3 text-2xl font-extrabold md:text-3xl">
                            Houston, We Have a Problem
                        </h1>
                        <h2
                            class="mx-auto mb-5 font-medium text-gray-500 md:leading-relaxed lg:w-3/5 dark:text-gray-400"
                        >
                            Our server is having a bit of a meltdown. Don't worry, our team of
                            experts is on it. Please try again later.
                        </h2>
                    </div>
                </div>
                <!-- END Error Content -->
            </div>
        </main>
        <!-- END Page Content -->
    </div>
    <!-- END Page Container -->
    <!-- END Pages: Errors: 500 -->


</x-layouts.plain>
