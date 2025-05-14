<x-app-layout>
    <div class="max-w-6xl mx-auto px-4 py-6">
        <div class="bg-white dark:bg-neutral-800 border rounded-lg shadow-md overflow-hidden">
            <!-- Header -->
            <div class="bg-gray-50 dark:bg-neutral-700 px-6 py-4 border-b border-gray-200 dark:border-neutral-600 flex justify-between items-center">
                <h1 class="text-2xl font-bold text-gray-800 dark:text-neutral-200">Edit Animal</h1>
                <a href="{{ route('newanimals.profile', $animal->animal_id) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                    Back
                </a>
            </div>

            <form method="POST" action="{{ route('newanimals.update', $animal->animal_id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="px-6 py-4">
                    <!-- Animal Type Section -->
                    <div class="mb-8 bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg border border-blue-100 dark:border-blue-800">
                        <h2 class="text-lg font-semibold text-blue-800 dark:text-blue-300 mb-4 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                            Animal Classification
                        </h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Is Group -->
                            <div>
                                <label for="is_group" class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">Type</label>
                                <select id="is_group" name="is_group" 
                                        class="w-full p-2.5 bg-white dark:bg-neutral-800 border border-gray-300 dark:border-neutral-600 text-gray-700 dark:text-neutral-200 rounded-lg focus:ring-blue-500 focus:border-blue-500" 
                                        onchange="toggleFields()">
                                    <option value="0" {{ old('is_group', $animal->is_group) == 0 ? 'selected' : '' }}>Individual Animal</option>
                                    <option value="1" {{ old('is_group', $animal->is_group) == 1 ? 'selected' : '' }}>Group of Animals</option>
                                </select>
                            </div>

                            <!-- Group Count -->
                            <div id="group_count_field" class="{{ old('is_group', $animal->is_group) == 1 ? '' : 'hidden' }}">
                                <label for="group_count" class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">Number of Animals</label>
                                <input type="number" id="group_count" name="group_count" value="{{ old('group_count', $animal->group_count) }}" 
                                    class="w-full p-2.5 bg-white dark:bg-neutral-800 border border-gray-300 dark:border-neutral-600 text-gray-700 dark:text-neutral-200 rounded-lg focus:ring-blue-500 focus:border-blue-500" min="1">
                            </div>
                        </div>
                    </div>

                    <!-- Basic Information Section -->
                    <div class="mb-8">
                        <h2 class="text-lg font-semibold text-gray-800 dark:text-neutral-200 border-b border-gray-200 dark:border-neutral-700 pb-2 mb-4">Basic Information</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Name -->
                            <div id="name_field">
                                <label for="name" id="name_label" class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">
                                    {{ old('is_group', $animal->is_group) == 1 ? 'Group Name' : 'Animal Name' }}
                                </label>
                                <input type="text" id="name" name="name" value="{{ old('name', $animal->name) }}" 
                                    class="w-full p-2.5 bg-white dark:bg-neutral-800 border border-gray-300 dark:border-neutral-600 text-gray-700 dark:text-neutral-200 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <!-- Gender -->
                            <div id="gender_field" class="{{ old('is_group', $animal->is_group) == 0 ? '' : 'hidden' }}">
                                <label for="gender" class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">Gender</label>
                                <select id="gender" name="gender" 
                                        class="w-full p-2.5 bg-white dark:bg-neutral-800 border border-gray-300 dark:border-neutral-600 text-gray-700 dark:text-neutral-200 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                    <option value="male" {{ old('gender', $animal->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender', $animal->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                </select>
                            </div>

                            <!-- Species -->
                            <div>
                                <label for="species_id" class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">Species</label>
                                <select id="species_id" name="species_id" 
                                        class="w-full p-2.5 bg-white dark:bg-neutral-800 border border-gray-300 dark:border-neutral-600 text-gray-700 dark:text-neutral-200 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                    @foreach($species as $specie)
                                        <option value="{{ $specie->id }}" {{ old('species_id', $animal->species_id) == $specie->id ? 'selected' : '' }}>{{ $specie->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Breed -->
                            <div>
                                <label for="breed_id" class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">Breed</label>
                                <select id="breed_id" name="breed_id" 
                                        class="w-full p-2.5 bg-white dark:bg-neutral-800 border border-gray-300 dark:border-neutral-600 text-gray-700 dark:text-neutral-200 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                    @foreach($breeds as $breed)
                                        <option value="{{ $breed->id }}" data-species="{{ $breed->species_id }}" {{ old('breed_id', $animal->breed_id) == $breed->id ? 'selected' : '' }}>{{ $breed->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Birth Date -->
                            <div>
                                <label for="birth_date" class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">Birth Date</label>
                                <input type="date" id="birth_date" name="birth_date" 
                                    value="{{ old('birth_date', $animal->birth_date ? $animal->birth_date->format('Y-m-d') : '') }}" 
                                    class="w-full p-2.5 bg-white dark:bg-neutral-800 border border-gray-300 dark:border-neutral-600 text-gray-700 dark:text-neutral-200 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <!-- Color -->
                            <div>
                                <label for="color" class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">Color</label>
                                <input type="text" id="color" name="color" value="{{ old('color', $animal->color) }}" 
                                    class="w-full p-2.5 bg-white dark:bg-neutral-800 border border-gray-300 dark:border-neutral-600 text-gray-700 dark:text-neutral-200 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>
                    </div>

                    <!-- Medical Information Section -->
                    <div class="mb-8">
                        <h2 class="text-lg font-semibold text-gray-800 dark:text-neutral-200 border-b border-gray-200 dark:border-neutral-700 pb-2 mb-4">Medical Information</h2>
                        
                        <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-lg p-4">
                            <label for="medical_condition" class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-2">Medical Condition</label>
                            <textarea id="medical_condition" name="medical_condition" rows="4"
                                    class="w-full p-2.5 bg-white dark:bg-neutral-800 border border-gray-300 dark:border-neutral-600 text-gray-700 dark:text-neutral-200 rounded-lg focus:ring-blue-500 focus:border-blue-500">{{ old('medical_condition', $animal->medical_condition) }}</textarea>
                        </div>
                    </div>

                    <!-- Photo Section -->
                    <div class="mb-8">
                        <h2 class="text-lg font-semibold text-gray-800 dark:text-neutral-200 border-b border-gray-200 dark:border-neutral-700 pb-2 mb-4 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                            </svg>
                            Photo Documentation
                        </h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach (['photo_front', 'photo_back', 'photo_left_side', 'photo_right_side'] as $photo)
                                <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-lg p-4 shadow-sm">
                                    <label for="{{ $photo }}" class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-2">{{ ucfirst(str_replace('_', ' ', $photo)) }}</label>
                                    
                                    @if ($animal->{$photo})
                                        <div class="mb-3 relative group">
                                            <img src="{{ asset('storage/' . $animal->{$photo}) }}" alt="{{ ucfirst($photo) }}" class="w-full h-48 object-cover rounded-md">
                                            <div class="absolute inset-0 bg-black bg-opacity-40 opacity-0 group-hover:opacity-100 transition-opacity duration-200 rounded-md flex items-center justify-center">
                                                <span class="text-white text-sm">Click to replace</span>
                                            </div>
                                        </div>
                                    @else
                                        <div class="mb-3 bg-gray-100 dark:bg-neutral-900 border border-dashed border-gray-300 dark:border-neutral-700 rounded-md p-4 text-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mx-auto text-gray-400 dark:text-neutral-500 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <p class="text-sm text-gray-500 dark:text-neutral-400">No image uploaded</p>
                                        </div>
                                    @endif
                                    
                                    <input type="file" id="{{ $photo }}" name="{{ $photo }}" 
                                        class="w-full p-2 bg-white dark:bg-neutral-800 border border-gray-300 dark:border-neutral-600 text-gray-700 dark:text-neutral-200 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                
                <!-- Footer / Action Buttons -->
                <div class="bg-gray-50 dark:bg-neutral-700 px-6 py-4 border-t border-gray-200 dark:border-neutral-600 flex justify-end space-x-3">
             
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200">
                        Update Animal
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- JavaScript to toggle fields dynamically -->
    <script>
        function toggleFields() {
            const isGroup = document.getElementById('is_group').value;
            const groupCountField = document.getElementById('group_count_field');
            const genderField = document.getElementById('gender_field');
            const nameLabel = document.getElementById('name_label');

            if (isGroup === "1") {
                groupCountField.classList.remove('hidden');
                genderField.classList.add('hidden');
                nameLabel.textContent = "Group Name";
            } else {
                groupCountField.classList.add('hidden');
                genderField.classList.remove('hidden');
                nameLabel.textContent = "Animal Name";
            }
        }

        document.addEventListener('DOMContentLoaded', toggleFields);
        document.getElementById('is_group').addEventListener('change', toggleFields);
    </script>
</x-app-layout>
