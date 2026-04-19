<button
    {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-2 py-1 bg-white dark:bg-blue-950 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
