<button {{ $attributes->merge([
    'type' => 'button',
    'class' => 'inline-flex items-center h-8 px-4 font-semibold text-sm text-gray-700 tracking-wide
                bg-transparent rounded-md
                hover:text-ml-color-orange hover:underline
                transition ease-in-out duration-150'
]) }}>
    {{ $slot }}
</button>
