@props(['disabled' => false])
<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'bg-white px-3 py-2 rounded-lg shadow-sm border-none']) !!}>
