@props(['num' => 0])

<div {{ $attributes->merge(['class' => "flex flex-col sm:flex-row items-center justify-between space-y-2 sm:space-y-0"]) }}>
    <label for="file-{{ $slot }}" class="text-left sm:w-1/6 w-full sm:ml-2">
        {{ $slot }}
    </label>

    <div class="w-full sm:w-3/4">
        <progress class="h-2 w-full rounded-xl" id="file-{{ $slot }}" value="{{ $num }}" max="100"></progress>
    </div>

    <label class="ml-0 sm:ml-4 mr-0 sm:mr-4 text-sm sm:text-base">{{ $num }}%</label>
</div>
