@if (session('success'))
    <div class="fixed top-20 left-0 right-0 mx-auto w-fit z-50">
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 8000)"
            x-transition:leave="transition ease-in duration-700" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="bg-green-300 border border-green-400 rounded-lg text-gray-700 text-sm text-center font-semibold uppercase px-3 py-2 w-fit">
            {{ session('success') }}
            <button x-on:click="show = false" class="ml-3 text-gray-700 hover:text-gray-800">&#10006</button>
        </div>
    </div>
@endif

@if (session('error'))
    <div class="fixed top-20 left-0 right-0 mx-auto w-fit z-50">
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 8000)"
            x-transition:leave="transition ease-in duration-700" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="bg-red-400 border border-red-500 rounded-lg text-gray-600 text-center text-sm font-semibold uppercase px-3 py-2 w-fit">
            {{ session('error') }} <button x-on:click="show = false"
                class="ml-3 text-gray-700 hover:text-gray-800">&#10006</button>
        </div>
    </div>
@endif
