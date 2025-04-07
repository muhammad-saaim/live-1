<!-- resources/views/components/form-input-small.blade.php -->

<div>
    <input
        type="{{ $type ?? 'text' }}"
        name="{{ $name ?? 'name' }}"
        {{ $attributes->merge([
            'class' => 'bg-transparent
            placeholder:text-slate-400 text-slate-700 text-sm
            border border-ml-color-orange rounded-md
            h-8 px-4 transition duration-300 ease-in-out
            hover:border-ml-color-orange shadow-sm focus:shadow'
        ]) }}
        placeholder="{{ $placeholder ?? 'Type here...' }}"
    >
</div>
