<x-app-layout>
    <div class="py-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Success Message -->
                    @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4"
                        role="alert">
                        {{ session('success') }}
                    </div>
                    @endif

                    <!-- Error Message -->
                    @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 ">
                        {{-- @foreach (auth()->user()->groups as $group) --}}
                        <div style="background-color: {{ $group->color }};"
                            class="rounded-xl border border-gray-300 px-3 pt-2 pb-3">
                            <h1 class="font-semibold text-lg mb-2">{{ $group->name ?? __('Group Name') }}</h1>
                            <p class="text-gray-700 ml-2">{{ __('User') }}: {{ $group->users()->count() }}</p>
                            <p class="text-gray-500 ml-2 text-sm">{{ __('Created') }}
                                : {{ $group->created_at->format('d/m/Y') }}
                            </p>
                            <div class="">
                                @php
                                    $allSurveys = $group->defaultSurveys();
                                    $totalQuestions = 0;
                                    $completedQuestions = 0;

                                    foreach ($allSurveys as $survey) {
                                        $totalQuestions += $survey->questions->count();
                                        $completedQuestions += $survey->usersSurveysRates()
                                            ->where('users_id', auth()->id())
                                            ->where('evaluatee_id', auth()->id())
                                            ->count();
                                    }

                                    $selfPercentage = $totalQuestions > 0 ? round(($completedQuestions / $totalQuestions) * 100, 1) : 0;

                                    // For others' completion rate
                                    $othersCompletedQuestions = 0;
                                    foreach ($allSurveys as $survey) {
                                        $othersCompletedQuestions += $survey->usersSurveysRates()
                                            ->where('users_id', auth()->id())
                                            ->where('evaluatee_id', '!=', auth()->id())
                                            ->count();
                                    }

                                    $othersPercentage = $totalQuestions > 0 ? round(($othersCompletedQuestions / $totalQuestions) * 100, 1) : 0;
                                @endphp
                                <x-group-progressbar class="mb-2" :num="$selfPercentage">Me ({{ $completedQuestions }}/{{ $totalQuestions }})</x-group-progressbar>
                                <x-group-progressbar :num="$othersPercentage">Others ({{ $othersCompletedQuestions }}/{{ $totalQuestions }})</x-group-progressbar>
                            </div>

                            <div class="space-y-3 mt-3 p-2">
                                @foreach($group->defaultSurveys() as $survey)
                                    <div class="flex items-center justify-between space-x-2">
                                        <label for="survey-{{ $survey->id }}" class="w-2/5 text-gray-600 truncate whitespace-nowrap overflow-hidden" title="{{ $survey->title }}">{{ $survey->title }}</label>
                                        <form action="{{ route('rate.survey') }}" method="POST" class="w-1/4">
                                            @csrf
                                            <input type="hidden" name="survey_id" value="{{ $survey->id }}">
                                            <input type="hidden" name="group_id" value="{{ $group->id }}">
                                            <button type="submit" class="w-full bg-white text-ml-color-lime border border-ml-color-lime rounded-xl px-2 py-1 hover:bg-ml-color-sky transition text-center text-secondary">
                                                Rate
                                            </button>
                                        </form>
                                        <select id="survey-{{ $survey->id }}"
                                            class="w-1/2 border border-gray-300 rounded-xl px-2 py-1 text-gray-600">
                                            <option value="status">
                                                {{ $survey->users()->where('users_surveys.is_completed', true)->count() }} / {{ $group->users->count() }} {{ __('Completed') }}
                                            </option>
                                        </select>
                                    </div>
                                @endforeach
                            </div>

                            <div class="pt-3 flex space-x-2">
                                <!-- Edit Group Button -->
                                @if (Auth::id() === $group->group_admin)
                                    <a href="{{ route('group.edit', $group->id) }}"
                                       class="w-full inline-flex items-center justify-center px-4 py-2 bg-green-500 text-white text-sm font-medium rounded-md hover:bg-green-600 transition duration-150">
                                        <i class="fas fa-edit mr-2"></i> Edit 
                                    </a>
                                @else
                                    <button disabled
                                            class="w-full inline-flex items-center justify-center px-4 py-2 bg-green-500 text-white text-sm font-medium rounded-md opacity-50 cursor-not-allowed">
                                        <i class="fas fa-edit mr-2"></i> Edit 
                                    </button>
                                @endif
                            
                                <!-- Delete Group Form -->
                                <form action="{{ route('group.destroy', $group->id) }}" method="POST"
                                      class="w-full"
                                      onsubmit="return confirm('Are you sure you want to delete this group?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="w-full inline-flex items-center justify-center px-4 py-2 bg-red-500 text-white text-sm font-medium rounded-md hover:bg-red-600 transition duration-150 {{ Auth::id() !== $group->group_admin ? 'opacity-50 cursor-not-allowed' : '' }}" 
                                {{ Auth::id() !== $group->group_admin ? 'disabled' : '' }}>
                                        <i class="fas fa-trash mr-2"></i> Delete 
                                    </button>
                                </form>
                            </div>
                        </div>
                        {{-- @endforeach --}}
                    </div>


                    {{-- <div class="mb-4">
                        <h2 class="text-xl font-semibold text-gray-800">{{ $group->name }}</h2>
                        <p class="my-2 text-gray-600">{{ $group->description }}</p>
                    </div>
                    <div class="mb-4">
                        <p class="my-2 text-gray-600">User(s): {{ $group->users()->count() }}</p>
                    </div>
                    <div class="mb-4">
                        <p class="my-2 text-gray-600">Applies to:
                            @foreach ($group->groupTypes as $groupType)
                            {{ $groupType->name }}{{ !$loop->last ? ', ' : '' }}
                            @endforeach
                        </p>
                    </div>

                    <div class="my-3 text-sm text-gray-500">
                        <span class="me-2">Updated on: {{ $group->updated_at->format('M d, Y') }}</span>|<span
                            class="ms-2">Created on: {{ $group->created_at->format('M d, Y') }}</span>
                    </div> --}}
                    {{-- <div class="my-3 flex space-x-2">
                        <a href="{{ route('group.edit', $group->id) }}">
                            <x-secondary-button>Edit</x-secondary-button>
                        </a>
                        <form action="{{ route('group.invite') }}" method="POST" x-data="emailForm()"
                            class="relative w-full max-w-md">
                            @csrf
                            <input type="hidden" name="group_id" value="{{ $group->id }}">

                            <!-- Hidden inputs for email submission -->
                            <template x-for="(email, index) in emails" :key="index">
                                <input type="hidden" name="emails[]" :value="email">
                            </template>

                            <div class="flex items-center space-x-2">
                                <x-form-input-small type="email" name="email_input" x-model="newEmail"
                                    @keydown.enter.prevent="addEmail" placeholder="Enter email" />

                                <button type="button" @click="dropdownOpen = !dropdownOpen" class="px-2 py-2"
                                    style="margin-left: -40px">
                                    <svg :class="{ 'rotate-180': dropdownOpen }"
                                        class="w-4 h-4 transition-transform duration-200" fill="none"
                                        stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <x-outline-button type="button" @click="addEmail">Add</x-outline-button>
                                <x-outline-button type="submit">Invite</x-outline-button>
                            </div>
                            <!-- Dropdown Email List -->
                            <div x-show="dropdownOpen"
                                class="absolute z-10 mt-2 bg-white border rounded shadow-md max-h-40 overflow-y-auto">
                                <template x-if="emails.length === 0">
                                    <div class="text-gray-500 px-4 py-2 text-sm">No emails added yet.</div>
                                </template>
                                <template x-for="(email, index) in emails" :key="index">
                                    <div class="flex justify-between items-center px-4 py-2 hover:bg-gray-100 text-sm">
                                        <span x-text="email"></span>
                                        <button type="button" @click="removeEmail(index)"
                                            class="text-red-500 hover:text-red-700 text-sm ml-2">×</button>
                                    </div>
                                </template>
                            </div>
                        </form>
                    </div> --}}
                    <hr class="my-3">
                  
                    <div class="my-3">
                        <h3 class="text-xl font-semibold text-gray-800">{{ $group->name ?? __('Group Name') }}</h3>

                        <div class="overflow-hidden rounded-lg border border-gray-300 shadow-sm mt-4">
                            <form id="bulk-action-form" method="POST" action="{{ route('groups.removeMembers', ['group' => $group->id]) }}">
                                @csrf
                                @method('DELETE')
                                <div class="p-4 border-b border-gray-200">
                                    @if (Auth::id() === $group->group_admin)
                                        <button type="submit" id="bulk-remove-btn" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded text-sm hidden" onclick="return confirm('Are you sure you want to remove selected members?');">
                                            Remove Selected
                                        </button>
                                    @endif
                                </div>
                                <table class="min-w-full divide-y divide-gray-200 text-sm text-left bg-white">
                                    <thead class="bg-gray-100 text-gray-600">
                                        <tr>
                                            <th class="px-4 py-2 border border-gray-300">
                                                <input type="checkbox" id="select-all"
                                                    class="form-checkbox h-4 w-4 text-blue-600">
                                            </th>
                                            <th class="px-4 py-2 border border-gray-300">#</th>
                                            <th class="px-4 py-2 border border-gray-300">Name</th>
                                            <th class="px-4 py-2 border border-gray-300">Email</th>
                                            <th class="px-4 py-2 border border-gray-300">Member</th>
                                            <th class="px-4 py-2 border border-gray-300 text-center">Status</th>
                                            <th class="px-4 py-2 border border-gray-300 text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        @foreach ($allUsers as $user)
                                        <tr>
                                            <td class="px-4 py-2 border border-gray-300 text-gray-700">
                                                <input type="checkbox" name="selected_users[]" value="{{ $user['id'] }}"
                                                    class="row-checkbox form-checkbox h-4 w-4 text-blue-600">
                                            </td>
                                            <td class="px-4 py-2 border border-gray-300 text-gray-700">
                                                {{ $loop->iteration }}</td>
                                            <td class="px-4 py-2 border border-gray-300 text-gray-700">
                                                {{ $user['name'] }}</td>
                                            <td class="px-4 py-2 border border-gray-300 text-gray-700">
                                                {{ $user['email'] }}</td>
                                            <td class="px-4 py-2 border border-gray-300 text-gray-700">
                                                {{ $user['relation'] }}
                                            </td>

                                            <td class="px-4 py-2 border border-gray-300">
                                                <p class="px-3 text-center py-1 rounded text-white {{ $user['status'] === 'member' ? 'bg-green-500 hover:bg-green-600' : 'bg-ml-color-orange hover:bg-ml-color-orange' }} transition">
                                                    {{ $user['status'] === 'member' ? 'Active' : 'Invited' }}
                                                </p>
                                            </td>
                                            
                                            <td class="px-4 py-2 border border-gray-300 text-gray-700 text-center">
                                                @if ($user['status'] === 'member')
                                                    @if (Auth::id() === $user['id'])
                                                        {{-- Leave Button for Logged-in User --}}
                                                        <form action="{{ route('groups.removeMembers', ['group' => $group->id, 'user' => $user['id']]) }}" method="POST" onsubmit="return confirm('Are you sure you want to leave this group?');">
                                                            @method('DELETE')
                                                            <input type="hidden" name="user_id" value="{{ $user['id'] }}">
                                                            <button type="submit" class="w-24 bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm">
                                                                Leave
                                                            </button>
                                                        </form>
                                                    @elseif (Auth::id() === $group->group_admin)
                                                        <form action="{{ route('groups.removeMembers', ['group' => $group->id, 'user' => $user['id']]) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove this member?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="w-24 bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                                                                Remove
                                                            </button>
                                                        </form>
                                                    @else
                                                        <button class="w-24 bg-gray-300 text-gray-600 px-3 py-1 rounded text-sm cursor-not-allowed" disabled>
                                                            Remove
                                                        </button>
                                                    @endif
                                                @elseif ($user['status'] === 'invited' && Auth::id() === $group->group_admin)
                                                    {{-- Cancel Invitation Button for Group Admin --}}
                                                    <form action="{{ route('groups.cancelInvitation', ['group' => $group->id, 'email' => $user['email']]) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this invitation?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="w-24 bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                                                            Cancel
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </form>
                        </div>

                        <!-- Buttons below the table -->
                        <div class="flex gap-4 mt-4">
                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#inviteModal">
                                Add
                            </button>
                        </div>
                    </div>
                    
                    <!-- Modal -->
                    <div class="modal fade" id="inviteModal" tabindex="-1" aria-labelledby="inviteModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-md">
                            <div class="modal-content border-0 shadow-lg rounded-4"
                                x-data="emailForm('{{ strtolower($group?->groupTypes?->first()?->name) === 'friend' ? 'true' : 'false' }}')">

                                <div class="modal-header bg-primary text-white rounded-top-4">
                                    <h5 class="modal-title" id="inviteModalLabel">
                                        <i class="bi bi-person-plus me-2"></i> Invite Members
                                    </h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>

                                <form action="{{ route('group.invite') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="group_id" value="{{ $group->id }}">
                                    <input type="hidden" name="group name" value="{{ $group?->groupTypes?->first()?->name}}">

                                    <div class="modal-body">
                                        <!-- Hidden email inputs -->
                                        <template x-for="(email, index) in emails" :key="index">
                                            <input type="hidden" name="emails[]" :value="email">
                                        </template>

                                        <!-- Hidden relation inputs only if not a friend group -->
                                        <template x-if="!isFriendGroup">
                                            <template x-for="(relation, index) in relations" :key="index">
                                                <input type="hidden" name="relations[]" :value="relation">
                                            </template>
                                        </template>

                                        <!-- Email Input -->
                                        <div class="flex items-center space-x-2 mb-3">
                                            <input type="email" class="w-full border border-gray-300 rounded-md px-3 py-1 text-gray-700 focus:ring-ml-color-lime focus:border-ml-color-lime" name="email_input" x-model="newEmail"
                                                 @keydown.enter.prevent="addEmail" placeholder="Enter email" style="margin-right: 5px" autocomplete="off" />
                                            <button type="button" @click="dropdownOpen = !dropdownOpen"
                                                class="px-2 py-2" style="margin-left: -40px">
                                                <svg :class="{ 'rotate-180': dropdownOpen }"
                                                    class="w-4 h-4 transition-transform duration-200" fill="none"
                                                    stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M19 9l-7 7-7-7" />
                                                </svg>
                                            </button>
                                            <x-outline-button type="button" @click="addEmail">Add</x-outline-button>
                                        </div>

                                        <!-- Dropdown Email List -->
                                        <div x-show="dropdownOpen"
                                            class="border rounded shadow-sm max-h-40 overflow-y-auto text-sm bg-white">
                                            <template x-if="emails.length === 0">
                                                <div class="text-gray-500 px-4 py-2">No emails added yet.</div>
                                            </template>
                                            <template x-for="(email, index) in emails" :key="index">
                                                <div class="p-2 border border-gray-200 rounded-md bg-gray-50">
                                                    <div class="flex items-center justify-between">
                                                        <label class="font-semibold text-gray-800"><span x-text="email"></span></label>
                                                        <button type="button" @click="removeEmail(index)"
                                                            class="text-red-600 hover:text-red-800 text-sm font-medium px-2 py-1 rounded-md border border-red-600 hover:bg-red-50 transition-colors duration-200">Remove</button>
                                                    </div>

                                                    <!-- Only show the relation select if not a friend group -->
                                                    <template x-if="!isFriendGroup">
                                                        <div class="mt-1">
                                                            <label class="form-label text-gray-600 mb-1 block">Relation</label>
                                                            <select class="form-select w-full border border-gray-300 rounded-md py-1 px-2 text-gray-700 focus:ring-ml-color-lime focus:border-ml-color-lime"
                                                                name="relations[]" x-model="relations[index]">
                                                                <option value="" disabled selected>Select relation</option>
                                                                <template x-for="relation in relationOptions" :key="relation.id">
                                                                    <option :value="relation.id"
                                                                        x-text="`${capitalize(relation.name)} – ${capitalize(relation.inverse_name)}`">
                                                                    </option>
                                                                </template>
                                                            </select>
                                                        </div>
                                                    </template>
                                                </div>
                                            </template>
                                        </div>
                                    </div>

                                    <div class="modal-footer border-0 pb-4 px-4">
                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-send me-1"></i> Invite
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    @php
                    $minimumUsers = $group->groupTypes->contains('name', 'Family') ? 2 : 6;
                    @endphp

                    @if ($group->users()->count() >= $minimumUsers)
                    <div class="bg-ml-color-lime border rounded-xl p-4 space-y-3">
                        @php
                        $defaultSurveys = $group->defaultSurveys();
                        @endphp

                        @if ($defaultSurveys->isNotEmpty())
                        <h3 class="text-lg font-semibold mb-2">Default Surveys</h3>

                        @foreach ($defaultSurveys as $survey)
                        @php
                            // Count questions where all group members have been rated by the current user
                            $totalQuestions = $survey->questions->count();
                            $completedQuestions = 0;
                            
                            foreach ($survey->questions as $question) {
                                // Get all group user IDs (including self)
                                $groupUserIds = $group->users->pluck('id')->toArray();
                                
                                // Get all ratings by current user for this specific question
                                $questionRatings = auth()->user()->usersSurveysRates
                                    ->where('survey_id', $survey->id)
                                    ->where('question_id', $question->id)
                                    ->pluck('evaluatee_id')
                                    ->toArray();
                                
                                // Check if all group members have been rated for this question
                                $allRated = true;
                                foreach ($groupUserIds as $groupId) {
                                    if (!in_array($groupId, $questionRatings)) {
                                        $allRated = false;
                                        break;
                                    }
                                }
                                
                                if ($allRated) {
                                    $completedQuestions++;
                                }
                            }
                        @endphp
                        <x-dashboard-progressbar
                            :completedQuestion="$completedQuestions"
                            :survey_id="$survey->id"
                            :totalQuestion="$totalQuestions"
                            :group="$group">
                            {{ $survey->title }}
                        </x-dashboard-progressbar>
                        @endforeach
                        @else
                        <p class="text-gray-500">No default surveys available for this group.</p>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
<script>
   function emailForm(isFriendGroup = false) {
    return {
        newEmail: '',
        emails: [],
        dropdownOpen: false,
        relations: [],
        isFriendGroup: isFriendGroup === true || isFriendGroup === 'true', // normalize type
        relationOptions: @json($relations),
        
        addEmail() {
            const email = this.newEmail.trim();
            if (email && this.validateEmail(email) && !this.emails.includes(email)) {
                this.emails.push(email);
                this.relations.push('');
                this.$nextTick(() => this.newEmail = ''); // Clear the input after the next DOM update
                this.dropdownOpen = true;
            }
        },
        removeEmail(index) {
            this.emails.splice(index, 1);
            this.relations.splice(index, 1);
        },
        validateEmail(email) {
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        },
        getInverseName(relationId) {
            const rel = this.relationOptions.find(r => r.id == relationId);
            return rel ? rel.inverse_name : '';
        },
        capitalize(str) {
            if (!str) return '';
            return str.charAt(0).toUpperCase() + str.slice(1);
        }
    };
}


    
    //Select All Script

    document.addEventListener('DOMContentLoaded', function() {
        const selectAllCheckbox = document.getElementById('select-all');
        const rowCheckboxes = document.querySelectorAll('.row-checkbox');
        const bulkRemoveBtn = document.getElementById('bulk-remove-btn');
        const bulkActionForm = document.getElementById('bulk-action-form');

        // Handle select all checkbox
        selectAllCheckbox.addEventListener('change', function() {
            rowCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateBulkActionButton();
        });

        // Handle individual checkboxes
        rowCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                updateBulkActionButton();
                // Update select all checkbox state
                selectAllCheckbox.checked = Array.from(rowCheckboxes).every(cb => cb.checked);
            });
        });

        // Update bulk action button visibility
        function updateBulkActionButton() {
            const checkedBoxes = document.querySelectorAll('.row-checkbox:checked');
            bulkRemoveBtn.classList.toggle('hidden', checkedBoxes.length === 0);
        }

        // Handle form submission
        bulkActionForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const checkedBoxes = document.querySelectorAll('.row-checkbox:checked');
            const userIds = Array.from(checkedBoxes).map(cb => cb.value);
            
            // Add selected user IDs to form
            userIds.forEach(userId => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'user_ids[]';
                input.value = userId;
                bulkActionForm.appendChild(input);
            });

            // Submit the form
            this.submit();
        });
    });
</script>