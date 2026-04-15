<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Lab Tracker</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('storage/logos/applogo.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body>
    @include('layouts.guestnavigation')

    <div
        class="bg-no-repeat bg-cover bg-center bg-fixed bg-gray-200 flex p-12 lg:p-24 items-start lg:justify-start min-h-screen flex-col">
        <div class="max-w-8xl mx-auto px-6 lg:px-8">
            <div class="bg-gray-50 p-6 m-10 rounded-lg shadow-lg border border-gray-200">
                <div class="mb-10 text-blacktext-center">
                    <h3 class="text-2xl font-bold"> Welcome to Lab Tracker!
                    </h3>
                    <h3 class="text-lg font-semibold"> Your home for labs at Swansea University, be it seeking
                        assistance
                        or tracking your progress.
                    </h3>
                    <h3 class="text-lg font-semibold"> In order to get started sign-in below with your provided account.
                    </h3>
                </div>

                <div class="flex justify-center mt-4">
                    @if (Route::has('login'))
                        <nav class="flex items-center gap-4">
                            @auth
                                <a href="{{ url('/dashboard') }}"
                                    class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-green-400 border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-green-500 backdrop-blur-sm rounded-sm text-lg leading-normal">
                                    Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}"
                                    class="inline-block px-5 py-1.5 text-black-500 border-[#19140035] hover:border-[#1915014a] border dark:border-[#3E3E3A] dark:hover:border-green-500 dark:hover:text-green-500 rounded-lg backdrop-blur-sm text-lg leading-normal">
                                    Login
                                </a>
                            @endauth
                        </nav>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @include('components.custom.footer')
</body>

</html>
