<div class="w-full p-2 mb-4 rounded-xl bg-ml-color-lime"> <!-- bg-ml-color-lime -->
    <input type="{{ $type ?? 'text' }}" placeholder="{{ $slot }}" {{$attributes->merge(["class" => "bg-white w-full p-2 mb-4 border rounded-xl shadow-sm border-none"])}}>
</div>

