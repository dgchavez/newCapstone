<x-app-layout>
    <div class="container mx-auto p-6">
        <!-- Card Container with improved styling -->
        <div class="bg-white shadow-xl rounded-lg overflow-hidden max-w-4xl mx-auto">
            <!-- Header with gradient background -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 p-6">
                <h2 class="text-2xl font-bold text-white text-center">Edit Animal</h2>
            </div>

            <!-- Error and Success Messages -->
            @if ($errors->any())
                <div class="bg-red-100 text-red-700 p-4 border-l-4 border-red-500 m-4">
                    <ul class="list-disc pl-4">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="bg-green-100 text-green-700 p-4 border-l-4 border-green-500 m-4">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Animal Form -->
            <form action="{{ route('admin-animals.update', $animal->animal_id) }}" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Left Column -->
                    <div class="space-y-6">
                        <!-- Owner Selection -->
                        <div>
                            <label for="owner_id" class="block text-sm font-medium text-gray-700 mb-1">Select Owner <span class="text-red-500">*</span></label>
                            <select name="owner_id" id="owner_id" class="w-full p-2.5 bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
                                <option value="">Select an owner</option>
                                @foreach ($owners as $owner)
                                    <option value="{{ $owner->owner_id }}" {{ old('owner_id', $animal->owner_id) == $owner->owner_id ? 'selected' : '' }}>{{ $owner->complete_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Is Group? -->
                        <div>
                            <label for="is_group" class="block text-sm font-medium text-gray-700 mb-1">Animal Type <span class="text-red-500">*</span></label>
                            <select name="is_group" id="is_group" class="w-full p-2.5 bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500" onchange="toggleGroupFields()">
                                <option value="0" {{ old('is_group', $animal->is_group) == '0' ? 'selected' : '' }}>Individual Animal</option>
                                <option value="1" {{ old('is_group', $animal->is_group) == '1' ? 'selected' : '' }}>Group of Animals</option>
                            </select>
                        </div>

                        <!-- Animal Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Animal Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $animal->name) }}" class="w-full p-2.5 bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- Vaccination Status -->
                        <div>
                            <label for="is_vaccinated" class="block text-sm font-medium text-gray-700 mb-1">Vaccination Status <span class="text-red-500">*</span></label>
                            <select name="is_vaccinated" id="is_vaccinated" class="w-full p-2.5 bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
                                <option value="" {{ old('is_vaccinated', $animal->is_vaccinated) === null ? 'selected' : '' }}>Select Vaccination Status</option>
                                <option value="0" {{ old('is_vaccinated', $animal->is_vaccinated) == '0' ? 'selected' : '' }}>Not Vaccinated</option>
                                <option value="1" {{ old('is_vaccinated', $animal->is_vaccinated) == '1' ? 'selected' : '' }}>Vaccinated</option>
                                <option value="2" {{ old('is_vaccinated', $animal->is_vaccinated) == '2' ? 'selected' : '' }}>No Vaccination Required</option>
                            </select>
                        </div>

                        <!-- Species -->
                        <div>
                            <label for="species_id" class="block text-sm font-medium text-gray-700 mb-1">Species <span class="text-red-500">*</span></label>
                            <select name="species_id" id="species_id" class="w-full p-2.5 bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
                                @foreach ($species as $specie)
                                    <option value="{{ $specie->id }}" {{ old('species_id', $animal->species_id) == $specie->id ? 'selected' : '' }}>
                                        {{ $specie->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Breed -->
                        <div>
                            <label for="breed_id" class="block text-sm font-medium text-gray-700 mb-1">Breed <span class="text-red-500">*</span></label>
                            <select name="breed_id" id="breed_id" class="w-full p-2.5 bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
                                <option value="">Select a breed</option>
                                @foreach ($breeds as $breed)
                                    <option value="{{ $breed->id }}" {{ old('breed_id', $animal->breed_id) == $breed->id ? 'selected' : '' }}>{{ $breed->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-6">
                        <!-- Individual Animal Fields -->
                        <div id="individual-animal-fields" class="{{ $animal->is_group ? 'hidden' : '' }}">
                            <div>
                                <label for="gender" class="block text-sm font-medium text-gray-700 mb-1">Gender <span class="text-red-500">*</span></label>
                                <select name="gender" id="gender" class="w-full p-2.5 bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                    <option value="Male" {{ old('gender', $animal->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ old('gender', $animal->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                                </select>
                            </div>
                        </div>

                        <!-- Group Animal Fields -->
                        <div id="group-fields" class="{{ $animal->is_group ? '' : 'hidden' }}">
                            <div>
                                <label for="group_count" class="block text-sm font-medium text-gray-700 mb-1">Group Count <span class="text-red-500">*</span></label>
                                <input type="number" name="group_count" id="group_count" value="{{ old('group_count', $animal->group_count) }}" class="w-full p-2.5 bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500" min="1">
                            </div>
                        </div>

                        <!-- Color -->
                        <div>
                            <label for="color" class="block text-sm font-medium text-gray-700 mb-1">Color</label>
                            <input type="text" name="color" id="color" value="{{ old('color', $animal->color ?? '') }}" class="w-full p-2.5 bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- Birth Date -->
                        <div>
                            <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-1">Birth Date</label>
                            <input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date', optional($animal->birth_date)->format('Y-m-d')) }}" class="w-full p-2.5 bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- Medical Condition -->
                        <div>
                            <label for="medical_condition" class="block text-sm font-medium text-gray-700 mb-1">Medical Condition</label>
                            <textarea name="medical_condition" id="medical_condition" rows="3" class="w-full p-2.5 bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500">{{ old('medical_condition', $animal->medical_condition) }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Photo Upload Section -->
                <div class="mt-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b">Animal Photos</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Front Photo -->
                        <div class="border rounded-lg p-4 bg-gray-50 text-center">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Front View</label>
                            <div class="mb-3 relative">
                                @if($animal->photo_front)
                                    <img src="{{ Storage::url($animal->photo_front) }}" alt="Front photo" class="w-full h-32 object-cover rounded mx-auto mb-2">
                                @else
                                    <div class="w-full h-32 bg-gray-200 flex items-center justify-center rounded mb-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif
                                <input type="file" name="photo_front" id="photo_front" accept="image/*" class="absolute inset-0 opacity-0 w-full h-32 cursor-pointer">
                            </div>
                            <label for="photo_front" class="text-xs bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded cursor-pointer">Change Photo</label>
                        </div>

                        <!-- Back Photo -->
                        <div class="border rounded-lg p-4 bg-gray-50 text-center">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Back View</label>
                            <div class="mb-3 relative">
                                @if($animal->photo_back)
                                    <img src="{{ Storage::url($animal->photo_back) }}" alt="Back photo" class="w-full h-32 object-cover rounded mx-auto mb-2">
                                @else
                                    <div class="w-full h-32 bg-gray-200 flex items-center justify-center rounded mb-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif
                                <input type="file" name="photo_back" id="photo_back" accept="image/*" class="absolute inset-0 opacity-0 w-full h-32 cursor-pointer">
                            </div>
                            <label for="photo_back" class="text-xs bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded cursor-pointer">Change Photo</label>
                        </div>

                        <!-- Left Side Photo -->
                        <div class="border rounded-lg p-4 bg-gray-50 text-center">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Left Side</label>
                            <div class="mb-3 relative">
                                @if($animal->photo_left_side)
                                    <img src="{{ Storage::url($animal->photo_left_side) }}" alt="Left side photo" class="w-full h-32 object-cover rounded mx-auto mb-2">
                                @else
                                    <div class="w-full h-32 bg-gray-200 flex items-center justify-center rounded mb-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif
                                <input type="file" name="photo_left_side" id="photo_left_side" accept="image/*" class="absolute inset-0 opacity-0 w-full h-32 cursor-pointer">
                            </div>
                            <label for="photo_left_side" class="text-xs bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded cursor-pointer">Change Photo</label>
                        </div>

                        <!-- Right Side Photo -->
                        <div class="border rounded-lg p-4 bg-gray-50 text-center">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Right Side</label>
                            <div class="mb-3 relative">
                                @if($animal->photo_right_side)
                                    <img src="{{ Storage::url($animal->photo_right_side) }}" alt="Right side photo" class="w-full h-32 object-cover rounded mx-auto mb-2">
                                @else
                                    <div class="w-full h-32 bg-gray-200 flex items-center justify-center rounded mb-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif
                                <input type="file" name="photo_right_side" id="photo_right_side" accept="image/*" class="absolute inset-0 opacity-0 w-full h-32 cursor-pointer">
                            </div>
                            <label for="photo_right_side" class="text-xs bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded cursor-pointer">Change Photo</label>
                        </div>
                    </div>
                    <p class="mt-2 text-xs text-gray-500">Click on any photo box to upload a new image. Accepted formats: JPG, PNG. Max size: 2MB.</p>
                </div>

                <!-- Submit Button -->
                <div class="mt-8 flex justify-end">
                    <a href="{{ route('admin-animals') }}" class="px-6 py-2.5 bg-gray-500 text-white font-medium rounded-lg hover:bg-gray-600 focus:ring-4 focus:outline-none focus:ring-gray-300 mr-4">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300">
                        Update Animal
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleGroupFields() {
            const isGroup = document.getElementById('is_group').value;
            const groupFields = document.getElementById('group-fields');
            const individualFields = document.getElementById('individual-animal-fields');
            const gender = document.getElementById('gender');
            const groupCount = document.getElementById('group_count');

            if (isGroup === '1') {
                groupFields.classList.remove('hidden');
                individualFields.classList.add('hidden');
                gender.required = false;
                gender.disabled = true;
                if (groupCount) {
                    groupCount.required = true;
                    groupCount.disabled = false;
                }
            } else {
                groupFields.classList.add('hidden');
                individualFields.classList.remove('hidden');
                gender.required = true;
                gender.disabled = false;
                if (groupCount) {
                    groupCount.required = false;
                    groupCount.disabled = true;
                }
            }
        }

        // Preview images before upload
        function setupImagePreview(inputId) {
            const fileInput = document.getElementById(inputId);
            if (fileInput) {
                fileInput.addEventListener('change', function(event) {
                    const file = event.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            // Find the closest image container
                            const container = fileInput.closest('div').querySelector('img, div.bg-gray-200');
                            if (container.tagName === 'IMG') {
                                container.src = e.target.result;
                            } else {
                                // Create an image and replace the placeholder
                                const img = document.createElement('img');
                                img.src = e.target.result;
                                img.className = 'w-full h-32 object-cover rounded mx-auto mb-2';
                                img.alt = inputId + ' photo';
                                container.parentNode.replaceChild(img, container);
                            }
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            toggleGroupFields();
            
            // Set up image previews
            setupImagePreview('photo_front');
            setupImagePreview('photo_back');
            setupImagePreview('photo_left_side');
            setupImagePreview('photo_right_side');

            // Fetch breeds based on selected species
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
                                breedSelect.appendChild(option);
                            });
                        })
                        .catch(error => console.error('Error fetching breeds:', error));
                }
            });
        });
    </script>
</x-app-layout>