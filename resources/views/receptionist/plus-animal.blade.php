<x-app-layout>
    <div class="container mx-auto px-4 py-8">
        <!-- Heading with gradient background - MOVED INSIDE CONTAINER -->
        <div class="max-w-4xl mx-auto bg-gradient-to-r from-green-800 to-green-600 rounded-xl shadow-lg p-6 mb-8 relative overflow-hidden">
            <div class="absolute inset-0 opacity-10">
                <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                    <path d="M0,0 L100,0 L100,100 L0,100 Z" fill="url(#pet-pattern)" />
                </svg>
                <defs>
                    <pattern id="pet-pattern" width="10" height="10" patternUnits="userSpaceOnUse">
                        <path d="M5,2 C7,2 8,3 8,5 C8,7 7,8 5,8 C3,8 2,7 2,5 C2,3 3,2 5,2 Z" fill="currentColor" />
                    </pattern>
                </defs>
            </div>
            <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-3xl font-bold text-white">
                        Add Animal
                    </h2>
                    <p class="text-blue-100 mt-2">
                        Fill in the details below to add a new animal
                    </p>
                </div>
                <div class="mt-4 md:mt-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-white opacity-80" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Error and Success Messages -->
        @if ($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg shadow-md mb-6 max-w-4xl mx-auto">
                <div class="flex items-center">
                    <svg class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <span class="font-medium">Please correct the following errors:</span>
                </div>
                <ul class="list-disc ml-8 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-md mb-6 max-w-4xl mx-auto">
                <div class="flex items-center">
                    <svg class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

        <!-- Animal Form -->
        <form action="{{ route('rec-animals.store') }}" method="POST" enctype="multipart/form-data" class="max-w-4xl mx-auto bg-white shadow-xl rounded-xl p-8 border-t-4 border-green-500">
            @csrf

            <!-- Form Sections -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <!-- Left Column - Basic Info -->
                <div class="lg:col-span-6 space-y-6">
                    <h3 class="font-semibold text-gray-800 text-xl border-b border-gray-200 pb-3 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Basic Information
                    </h3>
                    
                    <!-- Owner Selection -->
                    <div>
                        <label for="owner_id" class="block text-sm font-medium text-gray-700 mb-1">Owner</label>
                        <select name="owner_id" id="owner_id" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" required>
                            <option value="">Select an owner</option>
                            @foreach ($owners as $owner)
                                <option value="{{ $owner->owner_id }}" {{ old('owner_id') == $owner->owner_id ? 'selected' : '' }}>
                                    {{ $owner->complete_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Animal/Group Type Selection -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Is Group? -->
                        <div>
                            <label for="is_group" class="block text-sm font-medium text-gray-700 mb-1">Animal Type</label>
                            <select name="is_group" id="is_group" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" onchange="toggleGroupFields()">
                                <option value="0" {{ old('is_group') == '0' ? 'selected' : '' }}>Individual Animal</option>
                                <option value="1" {{ old('is_group') == '1' ? 'selected' : '' }}>Animal Group</option>
                            </select>
                        </div>

                        <!-- Vaccination Status -->
                        <div>
                            <label for="is_vaccinated" class="block text-sm font-medium text-gray-700 mb-1">Vaccination Status</label>
                            <select name="is_vaccinated" id="is_vaccinated" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                <option value="" {{ old('is_vaccinated',) === null ? 'selected' : '' }}>Select Status</option>
                                <option value="0" {{ old('is_vaccinated') == '0' ? 'selected' : '' }}>Not Vaccinated</option>
                                <option value="1" {{ old('is_vaccinated') == '1' ? 'selected' : '' }}>Vaccinated</option>
                                <option value="2" {{ old('is_vaccinated') == '2' ? 'selected' : '' }}>No Vaccination Required</option>
                            </select>
                        </div>
                    </div>

                    <!-- Name and Gender -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Animal Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Animal Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        </div>

                        <!-- Individual Animal Fields -->
                        <div id="individual-animal-fields">
                            <label for="gender" class="block text-sm font-medium text-gray-700 mb-1">Gender</label>
                            <select name="gender" id="gender" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                <option value="">Select Gender</option>
                                <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                            </select>
                        </div>

                        <!-- Group Animal Fields -->
                        <div id="group-fields" class="hidden">
                            <label for="group_count" class="block text-sm font-medium text-gray-700 mb-1">Group Count</label>
                            <input 
                                type="number" 
                                name="group_count" 
                                id="group_count" 
                                value="{{ old('group_count', 1) }}" 
                                class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" 
                                min="1"
                            >
                        </div>
                    </div>

                    <!-- Species and Breed -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Species -->
                        <div>
                            <label for="species_id" class="block text-sm font-medium text-gray-700 mb-1">Species</label>
                            <select name="species_id" id="species_id" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" required>
                                <option value="">Select a species</option>
                                @foreach ($species as $specie)
                                    <option value="{{ $specie->id }}" {{ old('species_id') == $specie->id ? 'selected' : '' }}>{{ $specie->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Breed -->
                        <div>
                            <label for="breed_id" class="block text-sm font-medium text-gray-700 mb-1">Breed</label>
                            <select name="breed_id" id="breed_id" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" required>
                                <option value="">Select a breed</option>
                                @foreach ($breeds as $breed)
                                    <option value="{{ $breed->id }}" {{ old('breed_id') == $breed->id ? 'selected' : '' }}>{{ $breed->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Color and Birth Date -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Color -->
                        <div>
                            <label for="color" class="block text-sm font-medium text-gray-700 mb-1">Color</label>
                            <input type="text" name="color" id="color" value="{{ old('color') }}" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        </div>

                        <!-- Birth Date -->
                        <div>
                            <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-1">Birth Date</label>
                            <input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date') }}" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        </div>
                    </div>
                    
                    <!-- Medical Condition -->
                    <div>
                        <label for="medical_condition" class="block text-sm font-medium text-gray-700 mb-1">Medical Condition</label>
                        <textarea name="medical_condition" id="medical_condition" rows="3" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">{{ old('medical_condition') }}</textarea>
                    </div>
                </div>
                
                <!-- Right Column - Photos -->
                <div class="lg:col-span-6 space-y-6">
                    <h3 class="font-semibold text-gray-800 text-xl border-b border-gray-200 pb-3 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Animal Photos
                    </h3>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach (['photo_front' => 'Front View', 'photo_back' => 'Back View', 'photo_left_side' => 'Left Side', 'photo_right_side' => 'Right Side'] as $field => $label)
                            <div class="border border-dashed border-gray-300 rounded-lg p-4 hover:border-green-500 transition-colors duration-300">
                                <label for="{{ $field }}" class="block text-sm font-medium text-gray-700 mb-2">{{ $label }}</label>
                                
                                <div id="{{ $field }}-preview" class="mb-3 h-[140px] flex items-center justify-center bg-gray-50 rounded-lg overflow-hidden">
                                    <span class="text-gray-400 text-sm">Image preview</span>
                                </div>
                                
                                <div class="flex items-center justify-center">
                                    <label for="{{ $field }}" class="cursor-pointer px-4 py-2 text-sm font-medium text-center text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                        </svg>
                                        Choose File
                                        <input type="file" name="{{ $field }}" id="{{ $field }}" class="hidden" onchange="previewImage(event, '{{ $field }}')">
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="mt-8 pt-5 border-t border-gray-200">
                <div class="flex justify-between">
                    <!-- CANCEL BUTTON  -->
                    <button type="button" onclick="window.history.back()" class="inline-flex items-center px-5 py-3 bg-red-500 text-white font-medium rounded-lg hover:bg-red-700 transition-all duration-300 shadow-md">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                        </svg>
                        Cancel
                    </button>
                    <button type="submit" class="inline-flex items-center px-8 py-3 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-all duration-300 shadow-md">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Save Animal
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Function to toggle the display of group fields based on the selection
        function toggleGroupFields() {
            var isGroup = document.getElementById('is_group').value;
            var nameLabel = document.querySelector('label[for="name"]');
            var individualFields = document.getElementById('individual-animal-fields');
            var groupFields = document.getElementById('group-fields');

            if (isGroup == '1') {
                // Update name label to "Group Name"
                nameLabel.textContent = "Group Name";

                // Hide individual-specific fields and show group-specific fields
                individualFields.classList.add('hidden');
                groupFields.classList.remove('hidden');
                
                // Set gender to "N/A" since it's hidden
                document.getElementById('gender').value = "N/A";
            } else {
                // Update name label to "Animal Name"
                nameLabel.textContent = "Animal Name";

                // Show individual-specific fields and hide group-specific fields
                individualFields.classList.remove('hidden');
                groupFields.classList.add('hidden');
                
                // Reset gender value
                document.getElementById('gender').value = "";
            }
        }

        // Preview the uploaded image before submission
        function previewImage(event, field) {
            var preview = document.getElementById(field + '-preview');
            preview.innerHTML = '';

            if (event.target.files && event.target.files[0]) {
                var reader = new FileReader();
                
                reader.onload = function(e) {
                    preview.innerHTML = `
                        <img src="${e.target.result}" alt="Preview" class="w-full h-full object-cover rounded-lg">
                    `;
                };
                
                reader.readAsDataURL(event.target.files[0]);
            } else {
                preview.innerHTML = `<span class="text-gray-400 text-sm">Image preview</span>`;
            }
        }

        // Handle the change event for the species field to fetch corresponding breeds
        $(document).ready(function() {
            // Initialize toggle fields on page load
            toggleGroupFields();
            
            $('#species_id').change(function() {
                var speciesId = $(this).val();

                if (speciesId) {
                    $.ajax({
                        url: '/get-breeds/' + speciesId,
                        type: 'GET',
                        success: function(data) {
                            $('#breed_id').empty().append('<option value="">Select a breed</option>');
                            $.each(data.breeds, function(index, breed) {
                                $('#breed_id').append('<option value="' + breed.id + '">' + breed.name + '</option>');
                            });
                        }
                    });
                } else {
                    $('#breed_id').empty().append('<option value="">Select a breed</option>');
                }
            });
        });
    </script>
</x-app-layout>