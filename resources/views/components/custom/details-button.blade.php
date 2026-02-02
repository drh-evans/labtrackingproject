<button {{ $attributes->merge(['type' => 'button']) }} class="hover:underline">
    {{ $slot }}
</button>
