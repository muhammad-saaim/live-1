<x-app-layout>
    <x-slot name="header">
        <h4 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create Group') }}
        </h4>
    </x-slot>

    <div class="py-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded" role="alert">
                            <ul class="list-disc pl-5 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('group.store') }}" method="POST" id="create-group-form">
                        @csrf

                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Group Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                        </div>

                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" id="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>{{ old('description') }}</textarea>
                        </div>

                        <!-- Group Type Selection -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Group Types</label>
                            <div class="space-y-4 mt-2">
                                @foreach ($groupTypes as $groupType)
                                    <div>
                                        <!-- Group Type Checkbox -->
                                        <label class="inline-flex items-center me-4">
                                            <input type="checkbox" name="group_types[]" value="{{ $groupType->id }}"
                                                   class="form-checkbox h-4 w-4 text-green-600 group-type-checkbox"
                                                   data-group-id="{{ $groupType->id }}"
                                                {{ is_array(old('group_types')) && in_array($groupType->id, old('group_types')) ? 'checked' : '' }}>
                                            <span class="ms-2">{{ $groupType->name }}</span>
                                        </label>

                                        <!-- Inline Subgroups (Only shown if they exist) -->
                                        @if($groupType->children->isNotEmpty())
                                            <br>
                                            <div class="inline-flex items-center space-x-4 ms-8">
                                                @foreach($groupType->children as $child)
                                                    <label class="inline-flex items-center">
                                                        <input type="checkbox" name="subgroup_types[]" value="{{ $child->id }}"
                                                               class="form-checkbox h-4 w-4 text-blue-600 subgroup-checkbox"
                                                               data-parent-group-id="{{ $groupType->id }}"
                                                            {{ is_array(old('subgroup_types')) && in_array($child->id, old('subgroup_types')) ? 'checked' : '' }}>
                                                        <span class="ms-2">{{ $child->name }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>


                        <div class="flex items-center justify-end">
                            <a href="{{ route('group.index') }}" class="text-gray-600 hover:text-gray-900">{{ __('Cancel') }}</a>
                            <x-primary-button type="submit" class="ml-4">
                                {{ __('Create Group') }}
                            </x-primary-button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript to handle automatic selection logic -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Select all main group checkboxes and subgroup checkboxes
            const groupTypeCheckboxes = document.querySelectorAll('.group-type-checkbox');
            const subgroupCheckboxes = document.querySelectorAll('.subgroup-checkbox');

            // Function to handle checking/unchecking all subgroups when main group is toggled
            groupTypeCheckboxes.forEach(groupCheckbox => {
                groupCheckbox.addEventListener('change', function () {
                    const groupId = groupCheckbox.getAttribute('data-group-id');
                    const relatedSubgroups = document.querySelectorAll(`.subgroup-checkbox[data-parent-group-id="${groupId}"]`);
                    relatedSubgroups.forEach(subgroupCheckbox => {
                        subgroupCheckbox.checked = groupCheckbox.checked;
                    });
                });
            });

            // Function to handle selecting the main group if any subgroup is selected
            subgroupCheckboxes.forEach(subgroupCheckbox => {
                subgroupCheckbox.addEventListener('change', function () {
                    const parentGroupId = subgroupCheckbox.getAttribute('data-parent-group-id');
                    const parentGroupCheckbox = document.querySelector(`.group-type-checkbox[data-group-id="${parentGroupId}"]`);
                    const relatedSubgroups = document.querySelectorAll(`.subgroup-checkbox[data-parent-group-id="${parentGroupId}"]`);

                    // If any subgroup is checked, check the main group checkbox
                    parentGroupCheckbox.checked = Array.from(relatedSubgroups).some(cb => cb.checked);
                });
            });
        });
    </script>

</x-app-layout>
