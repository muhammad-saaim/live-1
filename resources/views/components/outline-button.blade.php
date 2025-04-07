<button {{ $attributes->merge([
    'type' => 'button',
    'class' => 'inline-flex items-center h-8 px-4 font-semibold text-sm text-ml-color-orange tracking-wide
                bg-transparent border border-ml-color-orange border-2 rounded-md
                hover:bg-ml-color-orange hover:text-white
                focus:bg-ml-color-orange
                active:bg-ml-color-orange
                focus:outline-none focus:ring-2 focus:ring-ml-color-orange focus:ring-offset-2
                transition ease-in-out duration-150'
]) }}>
    {{ $slot }}
</button>
