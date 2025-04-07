<button {{ $attributes->merge([
    'type' => 'submit',
    'class' => 'inline-flex items-center px-3 py-1.5 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-wide hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-all ease-in-out duration-200'
]) }}>
    {{ $slot }}
</button>
