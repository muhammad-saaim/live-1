@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-ml-color-orange mb-2']) }}>
    {{ $value ?? $slot }}
</label>
