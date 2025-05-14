<x-app-layout>
    <div class="container mx-auto px-4 py-8">
        <!-- Main Card Container with Improved Styling -->
        <div class="max-w-4xl mx-auto bg-white dark:bg-neutral-800 shadow-lg rounded-lg overflow-hidden">
            <div class="p-6">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Edit Animal</h1>
                
                <form method="POST" action="{{ route('animals.update', $animal->animal_id) }}" enctype="multipart/form-data" class="space-y-8">
                @csrf
                @method('PUT')

                    <!-- Type Selection Card -->
                    <div class="bg-gray-50 dark:bg-neutral-700 rounded-lg p-6">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Animal Type</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                                <label for="is_group" class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-2">
                                    Registration Type <span class="text-red-500">*</span>
                                </label>
                                <select name="is_group" id="is_group" 
                                        class="w-full p-3 bg-white dark:bg-neutral-800 border border-gray-300 dark:border-neutral-600 text-gray-900 dark:text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm"
                                    onchange="toggleFields()">
                                    <option value="0" {{ old('is_group', $animal->is_group) == '0' ? 'selected' : '' }}>Individual Animal</option>
                                    <option value="1" {{ old('is_group', $animal->is_group) == '1' ? 'selected' : '' }}>Group of Animals</option>
                            </select>
                        </div>
                        </div>
                        </div>

                    <!-- Basic Information Card -->
                    <div class="bg-gray-50 dark:bg-neutral-700 rounded-lg p-6">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Basic Information</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Name -->
                        <div>
                                <label for="name" id="name-label" class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-2">
                                    {{ $animal->is_group ? 'Group Name' : 'Animal Name' }} <span class="text-red-500">*</span>
                            </label>
                                <input type="text" name="name" id="name" 
                                       value="{{ old('name', $animal->name) }}" 
                                       class="w-full p-3 bg-white dark:bg-neutral-800 border border-gray-300 dark:border-neutral-600 text-gray-900 dark:text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm"
                                       required>
                        </div>

                            <!-- Conditional Fields -->
                            <div id="gender-container" class="{{ $animal->is_group ? 'hidden' : '' }}">
                                <label for="gender" class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-2">
                                    Gender <span class="text-red-500">*</span>
                                </label>
                                <select name="gender" id="gender" 
                                        class="w-full p-3 bg-white dark:bg-neutral-800 border border-gray-300 dark:border-neutral-600 text-gray-900 dark:text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                                <option value="Male" {{ old('gender', $animal->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ old('gender', $animal->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                            </select>
                            </div>

                            <div id="group-count-container" class="{{ $animal->is_group ? '' : 'hidden' }}">
                                <label for="group_count" class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-2">
                                    Number of Animals <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="group_count" id="group_count" 
                                       value="{{ old('group_count', $animal->group_count) }}" 
                                       class="w-full p-3 bg-white dark:bg-neutral-800 border border-gray-300 dark:border-neutral-600 text-gray-900 dark:text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm"
                                       min="1">
                            </div>
                        </div>
                    </div>

                    <!-- Species & Breed Card -->
                    <div class="bg-gray-50 dark:bg-neutral-700 rounded-lg p-6">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Species & Breed</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Species -->
                        <div>
                                <label for="species_id" class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-2">
                                    Species <span class="text-red-500">*</span>
                                </label>
                                <select name="species_id" id="species_id" 
                                        class="w-full p-3 bg-white dark:bg-neutral-800 border border-gray-300 dark:border-neutral-600 text-gray-900 dark:text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm"
                                        required>
                                    <option value="">Select a species</option>
                                    @foreach ($species as $specie)
                                        <option value="{{ $specie->id }}" 
                                                {{ old('species_id', $animal->species_id) == $specie->id ? 'selected' : '' }}>
                                            {{ $specie->name }}
                                        </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Breed -->
                        <div>
                                <label for="breed_id" class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-2">
                                    Breed <span class="text-red-500">*</span>
                                </label>
                                <select name="breed_id" id="breed_id" 
                                        class="w-full p-3 bg-white dark:bg-neutral-800 border border-gray-300 dark:border-neutral-600 text-gray-900 dark:text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm"
                                        required>
                                    <option value="">Select a breed</option>
                                    @foreach ($breeds as $breed)
                                        <option value="{{ $breed->id }}" 
                                                {{ old('breed_id', $animal->breed_id) == $breed->id ? 'selected' : '' }}>
                                            {{ $breed->name }}
                                        </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Color -->
                        <div>
                                <label for="color" class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-2">Color</label>
                                <input type="text" name="color" id="color" 
                                       value="{{ old('color', $animal->color ?? '') }}"
                                       class="w-full p-3 bg-white dark:bg-neutral-800 border border-gray-300 dark:border-neutral-600 text-gray-900 dark:text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                        </div>
                        </div>
                    </div>

                    <!-- Additional Details Card -->
                    <div class="bg-gray-50 dark:bg-neutral-700 rounded-lg p-6">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Additional Details</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Birth Date -->
                            <div>
                                <label for="birth_date" class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-2">Birth Date</label>
                                <input type="date" name="birth_date" id="birth_date" 
                                       value="{{ old('birth_date', optional($animal->birth_date)->format('Y-m-d')) }}" 
                                       class="w-full p-3 bg-white dark:bg-neutral-800 border border-gray-300 dark:border-neutral-600 text-gray-900 dark:text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                            </div>
                        </div>

                            <!-- Medical Condition -->
                            <div class="mt-6">
                                <label for="medical_condition" class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-2">Medical Condition</label>
                                <textarea name="medical_condition" id="medical_condition" rows="4"
                                          class="w-full p-3 bg-white dark:bg-neutral-800 border border-gray-300 dark:border-neutral-600 text-gray-900 dark:text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm">{{ old('medical_condition', $animal->medical_condition) }}</textarea>
                            </div>
                        </div>
                </div>

                    <!-- Form Actions -->
                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('admin-animals') }}" 
                           class="px-6 py-3 bg-gray-500 text-white font-medium rounded-lg hover:bg-gray-600 focus:ring-4 focus:outline-none focus:ring-gray-300 transition-colors">
                        Cancel
                    </a>
                        <button type="submit" 
                                class="px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 transition-colors">
                        Update Animal
                    </button>
                </div>
            </form>
            </div>
        </div>
    </div>

    <script>
        function toggleFields() {
            const isGroup = document.getElementById('is_group').value;
            const groupCountContainer = document.getElementById('group-count-container');
            const genderContainer = document.getElementById('gender-container');
            const nameLabel = document.getElementById('name-label');
            const groupCount = document.getElementById('group_count');
            const gender = document.getElementById('gender');

            if (isGroup === "1") {
                groupCountContainer.classList.remove('hidden');
                genderContainer.classList.add('hidden');
                nameLabel.textContent = 'Group Name *';
                gender.required = false;
                gender.disabled = true;
                if (groupCount) {
                    groupCount.required = true;
                    groupCount.disabled = false;
                }
            } else {
                groupCountContainer.classList.add('hidden');
                genderContainer.classList.remove('hidden');
                nameLabel.textContent = 'Animal Name *';
                gender.required = true;
                gender.disabled = false;
                if (groupCount) {
                    groupCount.required = false;
                    groupCount.disabled = true;
                }
            }
        }
    </script>
</x-app-layout>
