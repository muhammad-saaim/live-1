<button {{ $attributes->merge([
    'type' => 'button',
    'class' => 'inline-flex items-center h-8 px-4 font-semibold text-sm text-ml-color-orange tracking-wide bg-transparent rounded-md
     hover:underline
     focus:outline-none
     disabled:opacity-25
     transition ease-in-out duration-150'
]) }}>
    {{ $slot }}
</button>
