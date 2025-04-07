<button {{ $attributes->merge([
    'type' => 'button',
    'class' => 'inline-flex items-center h-8 px-4 font-semibold text-sm text-gray-700 tracking-wide
                bg-gray-300 border border-transparent rounded-md
                hover:bg-gray-400 hover:text-white
                focus:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 focus:text-white
                active:bg-gray-600
                transition ease-in-out duration-150'
]) }}>
    {{ $slot }}
</button>
