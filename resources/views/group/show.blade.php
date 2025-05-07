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

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-1">
                        {{-- @foreach (auth()->user()->groups as $group) --}}
                        <div style="background-color: {{ $group->color }};"
                            class="rounded-xl border border-gray-300 px-3 pt-2 pb-3">
                            <h1 class="font-semibold text-lg mb-2">{{ $group->name ?? __('Group Name') }}</h1>
                            <p class="text-gray-700 ml-2">{{ __('User') }}: {{ $group->users()->count() }}</p>
                            <p class="text-gray-500 ml-2 text-sm">{{ __('Created') }}
                                : {{ $group->created_at->format('d/m/Y') }}
                            </p>
                            <div class="">
                                <x-group-progressbar class="mb-2" num="100"> Me</x-group-progressbar>
                                <x-group-progressbar num="40"> Others</x-group-progressbar>
                            </div>

                            <div class="space-y-3 mt-3 p-2">
                                <div class="flex items-center justify-between space-x-2">
                                    <label for="loyalty-survey" class="w-1/3 text-gray-600">Loyalty</label>
                                    <button
                                        class="w-1/4 bg-white text-ml-color-lime border border-ml-color-lime rounded-xl px-2 py-1 hover:bg-ml-color-sky transition">
                                        Solve
                                    </button>
                                    <select id="loyalty-survey"
                                        class="w-1/2 border border-gray-300 rounded-xl px-2 py-1 text-gray-600">
                                        <option value="status">{{ __('Participant Status') }}</option>
                                    </select>
                                </div>

                                <div class="flex items-center justify-between space-x-2">
                                    <label for="confidence-survey" class="w-1/3 text-gray-600">Confidence</label>
                                    <button
                                        class="w-1/4 bg-white text-ml-color-lime border border-ml-color-lime rounded-xl px-2 py-1 hover:bg-ml-color-sky transition">
                                        Solve
                                    </button>
                                    <select id="confidence-survey"
                                        class="w-1/2 border border-gray-300 rounded-xl px-2 py-1 text-gray-600">
                                        <option value="status">{{ __('Participant Status') }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="pt-3">
                                <form action="{{ route('group.destroy', $group->id) }}" method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this group?');">
                                    @csrf
                                    @method('DELETE')
                                    <x-danger-button class="flex justify-center items-center w-full space-x-2">
                                        <i class="fas fa-trash"></i>
                                        <span>Group Delete</span>
                                    </x-danger-button>
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
                                        <th class="px-4 py-2 border border-gray-300">Send</th>
                                        <th class="px-4 py-2 border border-gray-300">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach ($group->users as $user)
                                    <tr>
                                        <td class="px-4 py-2 border border-gray-300 text-gray-700">
                                            <input type="checkbox" name="selected_users[]" value="{{ $user->id }}"
                                                class="row-checkbox form-checkbox h-4 w-4 text-blue-600">
                                        </td>
                                        <td class="px-4 py-2 border border-gray-300 text-gray-700">
                                            {{ $loop->iteration }}</td>
                                        <td class="px-4 py-2 border border-gray-300 text-gray-700">
                                            {{ $user->name }}</td>
                                        <td class="px-4 py-2 border border-gray-300 text-gray-700">
                                            {{ $user->email }}</td>
                                        <td class="px-4 py-2 border border-gray-300 text-gray-700">
                                            @php
                                                $relation = \App\Models\UserRelative::where('relative_id', $user->id)
                                                    ->where('user_id', $group->owner_id ?? auth()->id()) // adjust depending on who invited
                                                    ->with('relation')
                                                    ->first();
                                            @endphp
                                            {{ $relation?->relation?->name ?? 'N/A' }}
                                        </td>
                                        <td class="px-4 py-2 border border-gray-300">
                                            <button
                                                class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 transition">Send</button>
                                        </td>
                                        <td class="px-4 py-2 border border-gray-300 text-gray-700">
                                            Invite Accept</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Buttons below the table -->
                        <div class="flex gap-4 mt-4">
                            <button class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition">
                                Delete
                            </button>
                            <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                data-bs-target="#inviteModal">
                                Add
                            </button>
                        </div>
                    </div>

                    <!-- Modal -->
                    <div class="modal fade" id="inviteModal" tabindex="-1" aria-labelledby="inviteModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content border-0 shadow-lg rounded-4" x-data="emailForm()">
                                <div class="modal-header bg-primary text-white rounded-top-4">
                                    <h5 class="modal-title" id="inviteModalLabel">
                                        <i class="bi bi-person-plus me-2"></i> Invite Members
                                    </h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>

                                <form action="{{ route('group.invite') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="group_id" value="{{ $group->id }}">

                                    <div class="modal-body">
                                        <!-- Hidden inputs for email submission -->
                                        <template x-for="(email, index) in emails" :key="index">
                                            <input type="hidden" name="emails[]" :value="email">
                                        </template>

                                        <!-- Email Input -->
                                        <div class="flex items-center space-x-2 mb-3">
                                            <x-form-input-small type="email" name="email_input" x-model="newEmail"
                                                @keydown.enter.prevent="addEmail" placeholder="Enter email" />
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
                                                <div class="mb-3">
                                                    <label class="form-label">Relation for <span x-text="email"></span></label>
                                                    <select class="form-select" name="relations[]" x-model="relations[index]">
                                                        <option value="" disabled selected>Select relation</option>
                                                        <template x-for="relation in relationOptions" :key="relation.id">
                                                            <option :value="relation.id" x-text="`${capitalize(relation.name)} – ${capitalize(relation.inverse_name)}`"></option>
                                                        </template>
                                                    </select>
                                                </div>
                                            </template>
                                        </div>
                                    </div>

                                    <div class="modal-footer border-0 pb-4 px-4">
                                        <button type="button" class="btn btn-outline-secondary"
                                            data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary"><i class="bi bi-send me-1"></i>
                                            Invite</button>
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
                        <x-dashboard-progressbar
                            completedQuestion="{{ auth()->user()->usersSurveysRates->where('survey_id', $survey->id)->count() }}"
                            survey_id="{{ $survey->id }}" totalQuestion="{{ $survey->questions->count() }}">
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
    function emailForm() {
        return {
            newEmail: '',
            emails: [],
            dropdownOpen: false,
            relations: [],
            relationOptions: @json($relations), // inject Laravel variable
            addEmail() {
                const email = this.newEmail.trim();
                if (email && this.validateEmail(email) && !this.emails.includes(email)) {
                    this.emails.push(email);
                    this.relations.push(''); // match index for relation
                    this.newEmail = '';
                    this.dropdownOpen = true;
                }
                this.newEmail = '';
            },
            removeEmail(index) {
                this.emails.splice(index, 1);
                this.relations.splice(index, 1); // remove related relation
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
        selectAllCheckbox.addEventListener('change', function() {
            rowCheckboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });
        });
    });
</script>