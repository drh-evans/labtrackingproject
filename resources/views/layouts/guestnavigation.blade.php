<nav x-data="{ open: false }" class="bg-white dark:bg-blue-950 border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="text-white font-bold">
                        <x-navbar-logo />
                    </a>
                </div>
            </div>

            <!-- Login -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <a href="{{ route('login') }}"> <x-primary-button> Login
                    </x-primary-button></a>
            </div>
        </div>
    </div>
</nav>
