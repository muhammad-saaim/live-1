<x-app-layout>
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="flex items-center mb-6">
            <a href="{{ route('services.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-bold rounded-lg mr-4 transition duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Services
            </a>
            <h1 class="text-3xl font-bold text-gray-800">Edit Service</h1>
        </div>

        <div class="bg-white shadow-md rounded-lg p-6">
            <form method="POST" action="{{ route('services.update', $service) }}">
                @csrf
                @method('PUT')
                
                {{-- Service Name --}}
                <div class="mb-4 flex items-center gap-2">
                    <label for="name" class="w-40 text-sm font-medium text-gray-700">Service Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $service->name) }}" 
                        class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    @error('name')
                        <span class="text-red-500 text-sm ml-2">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Category --}}
                <div class="mb-4 flex items-center gap-2">
                    <label for="category" class="w-40 text-sm font-medium text-gray-700">Category</label>
                    <select name="category" id="category" 
                        class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="">Select Category</option>
                        <option value="report" {{ old('category', $service->category) === 'report' ? 'selected' : '' }}>Report</option>
                        <option value="mentoring" {{ old('category', $service->category) === 'mentoring' ? 'selected' : '' }}>Mentoring</option>
                    </select>
                    @error('category')
                        <span class="text-red-500 text-sm ml-2">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Price --}}
                <div class="mb-4 flex items-center gap-2">
                    <label for="price" class="w-40 text-sm font-medium text-gray-700">Price ($)</label>
                    <input type="number" name="price" id="price" value="{{ old('price', $service->price) }}" step="0.01" min="0"
                        class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    @error('price')
                        <span class="text-red-500 text-sm ml-2">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Description --}}
                <div class="mb-4 flex items-start gap-2">
                    <label for="description" class="w-40 text-sm font-medium text-gray-700 mt-1">Description</label>
                    <textarea name="description" id="description" rows="3" 
                        class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description', $service->description) }}</textarea>
                    @error('description')
                        <span class="text-red-500 text-sm ml-2">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Active Checkbox --}}
                <div class="mb-6 flex items-center gap-2">
                    <label class="w-40 text-sm font-medium text-gray-700">Status</label>
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $service->is_active) ? 'checked' : '' }}
                            class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-700">Active</span>
                    </label>
                </div>

                <div class="flex justify-end space-x-4">
                    <a href="{{ route('services.index') }}" 
                        class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        Cancel
                    </a>
                    <button type="submit" 
                        class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                        Update Service
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
</x-app-layout>
