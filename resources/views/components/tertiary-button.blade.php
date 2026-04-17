<button
    {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-2 py-1 border border-gray-700 text-gray-700 rounded-lg font-semibold text-sm uppercase shadow-sm hover:bg-gray-200 disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
