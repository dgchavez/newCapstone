<x-app-layout>
    <div class="container mx-auto px-4 py-8">
        <!-- Main Card Container with Improved Styling -->
        <div class="bg-white dark:bg-neutral-800 border rounded-lg shadow-lg overflow-hidden max-w-5xl mx-auto">
            <!-- Header with gradient background -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-6">
                <h1 class="text-2xl font-bold text-white">Edit Animal</h1>
                <p class="text-blue-100 mt-1">Update animal information and photos</p>
            </div>

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 m-4">
                    <div class="font-medium">Please correct the following errors:</div>
                    <ul class="mt-1 ml-4 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Success Message -->
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 m-4">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('animals.update', $animal->animal_id) }}" enctype="multipart/form-data" class="p-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Left Column: Basic Info -->
                    <div class="space-y-6">
                        <h2 class="text-lg font-semibold text-gray-800 dark:text-neutral-200 border-b pb-2">Basic Information</h2>

                        <!-- Animal Type (Is Group) -->
                        <div>
                            <label for="is_group" class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">Animal Type <span class="text-red-500">*</span></label>
                            <select id="is_group" name="is_group" 
                                    class="w-full p-2.5 bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-neutral-700 dark:border-neutral-600 dark:text-white" 
                                    onchange="toggleFields()">
                                <option value="0" {{ old('is_group', $animal->is_group) == 0 ? 'selected' : '' }}>Individual Animal</option>
                                <option value="1" {{ old('is_group', $animal->is_group) == 1 ? 'selected' : '' }}>Group of Animals</option>
                            </select>
                        </div>

                        <!-- Vaccination Status -->
                        <div>
                            <label for="is_vaccinated" class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">Vaccination Status <span class="text-red-500">*</span></label>
                            <select name="is_vaccinated" id="is_vaccinated" 
                                    class="w-full p-2.5 bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-neutral-700 dark:border-neutral-600 dark:text-white" required>
                                <option value="" {{ old('is_vaccinated', $animal->is_vaccinated) === null ? 'selected' : '' }}>Select Vaccination Status</option>
                                <option value="0" {{ old('is_vaccinated', $animal->is_vaccinated) == '0' ? 'selected' : '' }}>Not Vaccinated</option>
                                <option value="1" {{ old('is_vaccinated', $animal->is_vaccinated) == '1' ? 'selected' : '' }}>Vaccinated</option>
                                <option value="2" {{ old('is_vaccinated', $animal->is_vaccinated) == '2' ? 'selected' : '' }}>No Vaccination Required</option>
                            </select>
                        </div>

                        <!-- Name -->
                        <div>
                            <label for="name" id="name_label" class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">
                                {{ old('is_group', $animal->is_group) == 1 ? 'Group Name' : 'Animal Name' }} <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="name" name="name" value="{{ old('name', $animal->name) }}" 
                                   class="w-full p-2.5 bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-neutral-700 dark:border-neutral-600 dark:text-white" required>
                        </div>

                        <!-- Group Count (conditional) -->
                        <div id="group_count_field" class="{{ old('is_group', $animal->is_group) == 1 ? '' : 'hidden' }}">
                            <label for="group_count" class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">Number of Animals <span class="text-red-500">*</span></label>
                            <input type="number" id="group_count" name="group_count" value="{{ old('group_count', $animal->group_count) }}" 
                                   class="w-full p-2.5 bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-neutral-700 dark:border-neutral-600 dark:text-white" min="1">
                        </div>

                        <!-- Gender (conditional) -->
                        <div id="gender_field" class="{{ old('is_group', $animal->is_group) == 0 ? '' : 'hidden' }}">
                            <label for="gender" class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">Gender</label>
                            <select id="gender" name="gender" 
                                    class="w-full p-2.5 bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-neutral-700 dark:border-neutral-600 dark:text-white">
                                <option value="">Select Gender</option>
                                <option value="Male" {{ old('gender', $animal->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ old('gender', $animal->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                            </select>
                        </div>
                    </div>

                    <!-- Right Column: Details -->
                    <div class="space-y-6">
                        <h2 class="text-lg font-semibold text-gray-800 dark:text-neutral-200 border-b pb-2">Animal Details</h2>

                        <!-- Species -->
                        <div>
                            <label for="species_id" class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">Species <span class="text-red-500">*</span></label>
                            <select id="species_id" name="species_id" 
                                    class="w-full p-2.5 bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-neutral-700 dark:border-neutral-600 dark:text-white" required>
                                <option value="">Select Species</option>
                                @foreach($species as $specie)
                                    <option value="{{ $specie->id }}" {{ old('species_id', $animal->species_id) == $specie->id ? 'selected' : '' }}>{{ $specie->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Breed -->
                        <div>
                            <label for="breed_id" class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">Breed <span class="text-red-500">*</span></label>
                            <select id="breed_id" name="breed_id" 
                                    class="w-full p-2.5 bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-neutral-700 dark:border-neutral-600 dark:text-white" required>
                                <option value="">Select Breed</option>
                                @foreach($breeds as $breed)
                                    <option value="{{ $breed->id }}" data-species="{{ $breed->species_id }}" {{ old('breed_id', $animal->breed_id) == $breed->id ? 'selected' : '' }}>{{ $breed->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Color -->
                        <div>
                            <label for="color" class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">Color</label>
                            <input type="text" id="color" name="color" value="{{ old('color', $animal->color) }}" 
                                   class="w-full p-2.5 bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-neutral-700 dark:border-neutral-600 dark:text-white">
                        </div>

                        <!-- Birth Date -->
                        <div>
                            <label for="birth_date" class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">Birth Date</label>
                            <input type="date" id="birth_date" name="birth_date" 
                                   value="{{ old('birth_date', $animal->birth_date ? $animal->birth_date->format('Y-m-d') : '') }}" 
                                   class="w-full p-2.5 bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-neutral-700 dark:border-neutral-600 dark:text-white">
                        </div>

                        <!-- Medical Condition -->
                        <div>
                            <label for="medical_condition" class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">Medical Condition</label>
                            <textarea id="medical_condition" name="medical_condition" rows="3"
                                      class="w-full p-2.5 bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-neutral-700 dark:border-neutral-600 dark:text-white">{{ old('medical_condition', $animal->medical_condition) }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Photo Upload Section -->
                <div class="mt-8">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-neutral-200 border-b pb-2 mb-4">Animal Photos</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Front Photo -->
                        <div class="border rounded-lg p-4 dark:border-neutral-600 bg-gray-50 dark:bg-neutral-700 text-center">
                            <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-2">Front View</label>
                            <div class="mb-3 relative">
                                @if($animal->photo_front)
                                    <img src="{{ asset('storage/' . $animal->photo_front) }}" alt="Front photo" class="w-full h-32 object-cover rounded mx-auto mb-2">
                                @else
                                    <div class="w-full h-32 bg-gray-200 dark:bg-neutral-600 flex items-center justify-center rounded mb-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 dark:text-neutral-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif
                                <input type="file" name="photo_front" id="photo_front" accept="image/*" class="absolute inset-0 opacity-0 w-full h-32 cursor-pointer" onchange="previewImage(this)">
                            </div>
                            <label for="photo_front" class="cursor-pointer text-xs px-3 py-1.5 rounded bg-blue-600 text-white hover:bg-blue-700 focus:ring-4 focus:ring-blue-300">
                                Change Photo
                            </label>
                        </div>

                        <!-- Back Photo -->
                        <div class="border rounded-lg p-4 dark:border-neutral-600 bg-gray-50 dark:bg-neutral-700 text-center">
                            <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-2">Back View</label>
                            <div class="mb-3 relative">
                                @if($animal->photo_back)
                                    <img src="{{ asset('storage/' . $animal->photo_back) }}" alt="Back photo" class="w-full h-32 object-cover rounded mx-auto mb-2">
                                @else
                                    <div class="w-full h-32 bg-gray-200 dark:bg-neutral-600 flex items-center justify-center rounded mb-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 dark:text-neutral-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif
                                <input type="file" name="photo_back" id="photo_back" accept="image/*" class="absolute inset-0 opacity-0 w-full h-32 cursor-pointer" onchange="previewImage(this)">
                            </div>
                            <label for="photo_back" class="cursor-pointer text-xs px-3 py-1.5 rounded bg-blue-600 text-white hover:bg-blue-700 focus:ring-4 focus:ring-blue-300">
                                Change Photo
                            </label>
                        </div>

                        <!-- Left Side Photo -->
                        <div class="border rounded-lg p-4 dark:border-neutral-600 bg-gray-50 dark:bg-neutral-700 text-center">
                            <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-2">Left Side</label>
                            <div class="mb-3 relative">
                                @if($animal->photo_left_side)
                                    <img src="{{ asset('storage/' . $animal->photo_left_side) }}" alt="Left side photo" class="w-full h-32 object-cover rounded mx-auto mb-2">
                                @else
                                    <div class="w-full h-32 bg-gray-200 dark:bg-neutral-600 flex items-center justify-center rounded mb-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 dark:text-neutral-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif
                                <input type="file" name="photo_left_side" id="photo_left_side" accept="image/*" class="absolute inset-0 opacity-0 w-full h-32 cursor-pointer" onchange="previewImage(this)">
                            </div>
                            <label for="photo_left_side" class="cursor-pointer text-xs px-3 py-1.5 rounded bg-blue-600 text-white hover:bg-blue-700 focus:ring-4 focus:ring-blue-300">
                                Change Photo
                            </label>
                        </div>

                        <!-- Right Side Photo -->
                        <div class="border rounded-lg p-4 dark:border-neutral-600 bg-gray-50 dark:bg-neutral-700 text-center">
                            <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-2">Right Side</label>
                            <div class="mb-3 relative">
                                @if($animal->photo_right_side)
                                    <img src="{{ asset('storage/' . $animal->photo_right_side) }}" alt="Right side photo" class="w-full h-32 object-cover rounded mx-auto mb-2">
                                @else
                                    <div class="w-full h-32 bg-gray-200 dark:bg-neutral-600 flex items-center justify-center rounded mb-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 dark:text-neutral-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif
                                <input type="file" name="photo_right_side" id="photo_right_side" accept="image/*" class="absolute inset-0 opacity-0 w-full h-32 cursor-pointer" onchange="previewImage(this)">
                            </div>
                            <label for="photo_right_side" class="cursor-pointer text-xs px-3 py-1.5 rounded bg-blue-600 text-white hover:bg-blue-700 focus:ring-4 focus:ring-blue-300">
                                Change Photo
                            </label>
                        </div>
                    </div>
                    <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Click on any photo box to upload. Accepted formats: JPEG, PNG, JPG, GIF (max 2MB)</p>
                </div>

                <!-- Form Buttons -->
                <div class="mt-8 flex justify-end space-x-4">
                    <a href="{{ route('animals.profile', $animal->animal_id) }}" class="px-5 py-2.5 bg-gray-500 text-white font-medium rounded-lg hover:bg-gray-600 focus:ring-4 focus:ring-gray-300">
                        Cancel
                    </a>
                    <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300">
                        Update Animal
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Toggle fields based on animal type (individual/group)
        function toggleFields() {
            const isGroup = document.getElementById('is_group').value;
            const groupCountField = document.getElementById('group_count_field');
            const genderField = document.getElementById('gender_field');
            const nameLabel = document.getElementById('name_label');
            const groupCount = document.getElementById('group_count');
            const gender = document.getElementById('gender');

            if (isGroup === "1") {
                // Enable and show group-specific fields
                groupCountField.classList.remove('hidden');
                groupCount.disabled = false;
                groupCount.required = true;
                
                // Hide and disable individual animal fields
                genderField.classList.add('hidden');
                gender.disabled = true;
                gender.required = false;
                
                // Update the name label
                nameLabel.textContent = "Group Name";
            } else {
                // Hide and disable group-specific fields
                groupCountField.classList.add('hidden');
                groupCount.disabled = true;
                groupCount.required = false;
                
                // Enable and show individual animal fields
                genderField.classList.remove('hidden');
                gender.disabled = false;
                gender.required = true;
                
                // Update the name label
                nameLabel.textContent = "Animal Name";
            }
        }

        // Preview images before upload
        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    const photoContainer = input.closest('div');
                    const existingImage = photoContainer.querySelector('img');
                    const placeholderDiv = photoContainer.querySelector('div.bg-gray-200, div.bg-neutral-600');
                    
                    if (existingImage) {
                        // Update existing image src
                        existingImage.src = e.target.result;
                    } else if (placeholderDiv) {
                        // Replace placeholder with new image
                        const newImage = document.createElement('img');
                        newImage.src = e.target.result;
                        newImage.alt = input.id + " photo";
                        newImage.className = "w-full h-32 object-cover rounded mx-auto mb-2";
                        placeholderDiv.parentNode.replaceChild(newImage, placeholderDiv);
                    }
                };
                
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Filter breeds based on selected species
        function filterBreeds() {
            const speciesId = document.getElementById('species_id').value;
            const breedSelect = document.getElementById('breed_id');
            const breedOptions = breedSelect.querySelectorAll('option');
            
            // Skip the first option (placeholder)
            for (let i = 1; i < breedOptions.length; i++) {
                const breedOption = breedOptions[i];
                const breedSpeciesId = breedOption.getAttribute('data-species');
                
                if (breedSpeciesId === speciesId) {
                    breedOption.style.display = '';
                } else {
                    breedOption.style.display = 'none';
                }
            }
            
            // Reset selection if current breed doesn't match species
            const currentBreed = breedSelect.value;
            let breedMatchesSpecies = false;
            
            for (let i = 1; i < breedOptions.length; i++) {
                if (breedOptions[i].value === currentBreed && breedOptions[i].getAttribute('data-species') === speciesId) {
                    breedMatchesSpecies = true;
                    break;
                }
            }
            
            if (!breedMatchesSpecies) {
                breedSelect.value = '';
            }
        }

        // Document ready function
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize field visibility
            toggleFields();
            
            // Set up event listeners
            document.getElementById('is_group').addEventListener('change', toggleFields);
            
            // Set up species-breed relationship
            document.getElementById('species_id').addEventListener('change', function() {
                const speciesId = this.value;
                const breedSelect = document.getElementById('breed_id');
                
                // Clear current breed options
                breedSelect.innerHTML = '<option value="">Select a breed</option>';
                
                // Make AJAX call to fetch breeds for selected species
                if (speciesId) {
                    fetch(`/get-breeds/${speciesId}`)
                        .then(response => response.json())
                        .then(data => {
                            // Add the breed options
                            data.breeds.forEach(breed => {
                                const option = document.createElement('option');
                                option.value = breed.id;
                                option.textContent = breed.name;
                                option.setAttribute('data-species', speciesId);
                                breedSelect.appendChild(option);
                            });
                        })
                        .catch(error => console.error('Error fetching breeds:', error));
                }
            });
        });
    </script>
</x-app-layout>
