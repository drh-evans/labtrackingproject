@props(['disabled' => false])

<input @disabled($disabled)
    {{ $attributes->merge(['class' => 'border-gray-300 dark:border-gray-500 dark:bg-gray-600 dark:text-white  rounded-md shadow-lg']) }}>
