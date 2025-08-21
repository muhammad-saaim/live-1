<button {{ $attributes->merge([
    'type' => 'submit',
    'class' => 'inline-flex items-center h-8 px-4 font-semibold text-sm text-white tracking-wide
                bg-gray-700 border border-transparent rounded-md
                hover:bg-ml-color-orange focus:bg-ml-color-orange
                active:bg-ml-color-orange
                focus:outline-none focus:ring-2 focus:ring-ml-color-orange focus:ring-offset-2
                transition ease-in-out duration-150'
]) }}>
    {{ $slot }}
</button>
