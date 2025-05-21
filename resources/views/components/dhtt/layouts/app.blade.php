<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dél-Hargita Teljesítménytúra</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <meta name="description" content="Dél-Hargita Teljesítménytúra">
    <meta name="keywords" content="Dél-Hargita Teljesítménytúra, CsEKE, természetjárás, turázás, honismeret">
    <meta property="og:title" content="Dél-Hargita Teljesítménytúra">
    <meta property="og:description" content="Dél-Hargita Teljesítménytúra, CsEKE, természetjárás, turázás, honismeret">
    <meta property="og:image" content="">
    @livewireStyles
</head>
<body class="bg-gray-100 dark:bg-gray-800 dark:text-gray-100">
<div class="relative pb-1 shadow-lg">
    <nav class="w-full flex flex-wrap items-center justify-between px-2 py-3">
        <div class="container px-4 mx-auto flex flex-wrap items-center justify-between" x-data="{ navBarOpen: false }">
            <div class="w-full relative flex justify-between lg:w-auto lg:justify-start items-center">
                <a href="{{ route('dhtt.home') }}">
                    <img class="img-fluid" src="{{ asset('img/dhtt/dhtt-logo-2024.jpg') }}" alt="CsEKE logo"
                         style="max-height: 60px;">
                </a>
                <button
                    type="button"
                    class="cursor-pointer text-xl px-4 py-2 border border-solid border-gray-300 rounded block lg:hidden outline-none hover:bg-gray-300 dark:hover:bg-gray-700 transition"
                    @click="navBarOpen = !navBarOpen">
                    <i class="dark:text-gray-300 text-gray-600 fas fa-bars text-2xl"></i>
                </button>
            </div>
            <!-- Collapse menu -->
            <div class="lg:flex! flex-grow flex-col lg:flex-row items-center lg:shadow-none"
                 x-show="navBarOpen"
                 x-transition:enter="transition ease-out duration-500"
                 x-transition:enter-start="opacity-0 transform -translate-y-4"
                 x-transition:enter-end="opacity-100 transform translate-y-0"
                 x-transition:leave="transition ease-in duration-300"
                 x-transition:leave-start="opacity-100 transform translate-y-0"
                 x-transition:leave-end="opacity-0 transform -translate-y-4"
            >
                <ul class="flex flex-col lg:flex-row list-none mr-auto">
                    <li class="flex items-center">
                        <a class="dark:text-gray-200 text-dhtt-green px-3 py-4 lg:py-2 flex items-center text-lg uppercase font-bold"
                           href="{{ route('dhtt.home') }}">
                            <i class="fas fa-hiking text-xl mr-2"></i>
                            <span>DHTT</span>
                        </a>
                    </li>
                </ul>
                <ul class="flex flex-col lg:flex-row list-none lg:ml-auto items-center gap-3">
                    <li class="inline-block relative lg:w-auto w-full" x-data="{ show: false }">
                        <a class="hover:text-green-600 text-green-800 dark:text-green-700 px-3 text-md font-bold"
                           href="#" @click.prevent="show = ! show">Verseny</a>
                        <div
                            x-cloak
                            x-show="show"
                            :class="show ? '' : 'hidden'"
                            @click.outside="show = false"
                            class="bg-white text-base z-50 py-2 list-none rounded shadow-lg min-w-48 lg:absolute"
                        >

                            <a href="{{ route('dhtt.event', ['option' => 'detail']) }}"
                               class="text-md py-2 px-4 font-semibold block w-full whitespace-nowrap text-green-700 hover:bg-green-600 hover:text-gray-100">
                                Részletek
                            </a>
                            <a href="{{ route('dhtt.event', ['option' => 'routes']) }}"
                               class="text-md py-2 px-4 font-semibold block w-full whitespace-nowrap text-green-700 hover:bg-green-600 hover:text-gray-100">
                                Útvonalak
                            </a>
                            <a href="{{ route('dhtt.event', ['option' => 'results']) }}"
                               class="text-md py-2 px-4 font-semibold block w-full whitespace-nowrap text-green-700 hover:bg-green-600 hover:text-gray-100">
                                Eredmények
                            </a>
                        </div>
                    </li>
                    <li class="flex items-center lg:w-auto w-full">
                        <a class="hover:text-green-600 text-green-800 dark:text-green-700 px-3 text-md font-bold"
                           href="{{ route('dhtt.home') }}#dhtt-contact">
                            Kapcsolat
                        </a>
                    </li>
                    <li class="flex items-center lg:w-auto w-full">
                        <a class="hover:text-green-600 text-green-800 dark:text-green-700 px-3 text-md font-bold"
                           href="{{ route('dhtt.archive') }}">
                            Archívum
                        </a>
                    </li>
                    <li class="flex items-center lg:w-auto w-full">
                        <button id="theme-toggle" class="cursor-pointer border border-black rounded-full p-1" x-cloak>
                            <x-heroicon-o-sun id="theme-toggle-light" class="size-5 text-yellow-500"/>
                            <x-heroicon-o-moon id="theme-toggle-dark" class="size-5 text-gray-900 dark:text-white"/>
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

</div>

{{ $slot }}

@livewireScripts
</body>
</html>
