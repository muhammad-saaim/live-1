<!-- Report Modal -->
<div x-show="showModal" x-cloak
    class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50 w-100">
    <div @click.away="showModal = false"
        class="bg-white rounded-2xl p-3 max-w-lg w-1/4 mx-auto mx-4 shadow-2xl relative animate-fade-in">
        <!-- Close Icon Button -->
        <button @click="showModal = false"
            class="absolute top-4 right-4 text-gray-400 hover:text-blue-600 focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
        <!-- Modal Header -->
        <div class="flex items-center mb-4 gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 17v-2a4 4 0 014-4h3m4 4v6a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h6" />
            </svg>
            <h2 class="text-xl font-bold text-gray-800">Self Evaluation</h2>
        </div>
        <div class="space-y-4">
            <div class="flex items-center justify-between border-b pb-2">
                <span class="text-sm font-semibold text-gray-700">Self-Esteem:</span>
                @if (($total_points) >= 33)
                <span class="text-sm font-bold text-green-600">Very good</span>
                @elseif(($total_points) >= 25 && ($total_points) < 33)
                <span class="text-sm font-semibold text-yellow-600">Good</span>
                @elseif(($total_points) >= 16 && ($total_points) < 25)
                <span class="text-sm font-semibold text-orange-500">You can be better</span>
                @elseif (($total_points) < 16)
                <span class="text-sm font-semibold text-red-500">You can be better</span>
                @endif
            </div>
            {{-- <div class="flex items-center justify-between border-b pb-2">
                <span class="text-sm font-semibold text-gray-700">Self-Esteem:</span>
                @if (($points_self) >= 33)
                <span class="text-sm font-bold text-green-600">Very good</span>
                @elseif(($points_self) >= 25 && ($points_self) < 33)
                <span class="text-sm font-semibold text-yellow-600">Good</span>
                @elseif(($points_self) >= 16 && ($points_self) < 25)
                <span class="text-sm font-semibold text-orange-500">You can be better</span>
                @elseif (($points_self) < 16)
                <span class="text-sm font-semibold text-red-500">You can be better</span>
                @endif
            </div> --}}
            <div class="flex items-center justify-between border-b pb-2">
                <span class="text-sm font-semibold text-gray-700">Competence:</span>
                @if (($points_competence ?? 0) >= 25)
                <span class="text-sm font-bold text-green-600">Perfect</span>
                @elseif(($points_competence ?? 0) >= 20 && ($points_competence ?? 0) < 25)
                <span class="text-sm font-semibold text-yellow-600">Very Good</span>
                @elseif(($points_competence ?? 0) >= 10 && ($points_competence ?? 0) < 20)
                <span class="text-sm font-semibold text-orange-500">Good</span>
                @elseif(($points_competence ?? 0) < 10 )
                <span class="text-sm font-semibold text-red-500">Poor</span>
                @endif
            </div>
            <div class="flex items-center justify-between border-b pb-2">
                <span class="text-sm font-semibold text-gray-700">Autonomy:</span>
                @if (($points_autonomy ?? 0) >= 30)
                <span class="text-sm font-bold text-green-600">Perfect</span>
                @elseif(($points_autonomy ?? 0) >= 22 && ($points_autonomy ?? 0) < 30)
                <span class="text-sm font-semibold text-yellow-600">Very Good</span>
                @elseif(($points_autonomy ?? 0) >= 12 && ($points_autonomy ?? 0) < 22)
                <span class="text-sm font-semibold text-orange-500">Good</span>
                @elseif (($points_autonomy ?? 0) < 12)
                <span class="text-sm font-semibold text-red-500">Poor</span>
                @endif
            </div>
            <div class="flex items-center justify-between">
                <span class="text-sm font-semibold text-gray-700">Relatedness:</span>
                @if (($points_relatedness ?? 0) >= 32)
                <span class="text-sm font-bold text-green-600">Perfect</span>
                @elseif(($points_relatedness ?? 0) >= 25 && ($points_relatedness ?? 0) < 32)
                <span class="text-sm font-semibold text-yellow-600">Very Good</span>
                @elseif(($points_relatedness ?? 0) >= 15 && ($points_relatedness ?? 0) < 25)
                <span class="text-sm font-semibold text-orange-500">Good</span>
                @elseif(($points_relatedness ?? 0) < 15)
                <span class="text-sm font-semibold text-red-500">Poor</span>
                @endif
            </div>
        </div>
    </div>
</div>