<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('storage/logos/applogo.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles()
    @livewireScripts()
</head>

<body>
    @include('layouts.navigation')
    <div class="min-h-screen text-black">
        <!-- <h1 class="ml-4 font-bold text-3xl">@yield('title')</h1>-->
        <div>
            @yield('content')
        </div>
    </div>

    @include('components.custom.footer')
</body>

</html>
