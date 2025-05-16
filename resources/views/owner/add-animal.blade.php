    <x-app-layout>
        <div class="py-10 ">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-white shadow-xl rounded-xl overflow-hidden max-w-4xl mx-auto">
                    <!-- Header -->
                    <div class="bg-gradient-to-r from-green-800 to-green-600 px-8 py-6">
                        <h2 class="text-2xl font-bold text-white">Add New Pet</h2>
                        <p class="text-green-100">Owner: {{ $owner->complete_name }}</p>
                    </div>

                    <!-- Alerts -->
                    <div class="px-8 pt-6">
                        @if ($errors->any())
                            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-md mb-6 shadow-sm">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-red-700">Please correct the following errors:</h3>
                                        <ul class="mt-1 text-sm text-red-600 list-disc list-inside">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if (session('success'))
                            <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-md mb-6 shadow-sm">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium">{{ session('success') }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <form action="{{ route('owner.createAnimal', ['owner_id' => $owner_id]) }}" method="POST" enctype="multipart/form-data" class="px-8 pb-8">
                        @csrf
                        <input type="hidden" name="owner_id" value="{{ $owner_id }}">

                        <!-- Form Sections -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                            <!-- Left Column -->
                            <div class="space-y-6">
                                <!-- Basic Information Section -->
                                <div class="border border-gray-200 rounded-lg p-5 bg-white shadow-sm">
                                    <h3 class="text-lg font-medium text-gray-800 mb-4 flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        Basic Information
                                    </h3>
                                    
                                    <!-- Is Group? -->
                                    <div class="mb-4">
                                        <label for="is_group" class="block text-sm font-medium text-gray-700 mb-1">Registration Type</label>
                                        <select name="is_group" id="is_group" class="w-full p-2.5 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 shadow-sm" onchange="toggleGroupFields()">
                                            <option value="0" {{ old('is_group') == '0' ? 'selected' : '' }}>Individual Animal</option>
                                            <option value="1" {{ old('is_group') == '1' ? 'selected' : '' }}>Animal Group</option>
                                        </select>
                                    </div>

                                    <!-- Animal Name -->
                                    <div class="mb-4">
                                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Animal Name</label>
                                        <input type="text" name="name" id="name" value="{{ old('name') }}" class="w-full p-2.5 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                                    </div>

                                    <!-- Individual Animal Fields -->
                                    <div id="individual-animal-fields" class="mb-4">
                                        <label for="gender" class="block text-sm font-medium text-gray-700 mb-1">Gender</label>
                                        <select name="gender" id="gender" class="w-full p-2.5 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                                            <option value="">Select Gender</option>
                                            <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                                            <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                                        </select>
                                    </div>

                                    <!-- Group Animal Fields -->
                                    <div id="group-fields" class="hidden mb-4">
                                        <label for="group_count" class="block text-sm font-medium text-gray-700 mb-1">Number of Animals in Group</label>
                                        <input type="number" name="group_count" id="group_count" value="{{ old('group_count') }}" class="w-full p-2.5 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 shadow-sm" min="1">
                                    </div>
                                </div>

                                <!-- Species & Breed Section -->
                                <div class="border border-gray-200 rounded-lg p-5 bg-white shadow-sm">
                                    <h3 class="text-lg font-medium text-gray-800 mb-4 flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                        </svg>
                                        Classification
                                    </h3>
                                    
                                    <!-- Species -->
                                    <div class="mb-4">
                                        <label for="species_id" class="block text-sm font-medium text-gray-700 mb-1">Species</label>
                                        <select name="species_id" id="species_id" class="w-full p-2.5 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 shadow-sm" required>
                                            <option value="">Select a species</option>
                                            @foreach ($species as $specie)
                                                <option value="{{ $specie->id }}" {{ old('species_id') == $specie->id ? 'selected' : '' }}>{{ $specie->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Breed -->
                                    <div class="mb-4">
                                        <label for="breed_id" class="block text-sm font-medium text-gray-700 mb-1">Breed</label>
                                        <select name="breed_id" id="breed_id" class="w-full p-2.5 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 shadow-sm" required>
                                            <option value="">Select a breed</option>
                                            @foreach ($breeds as $breed)
                                                <option value="{{ $breed->id }}" {{ old('breed_id') == $breed->id ? 'selected' : '' }}>{{ $breed->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Color -->
                                    <div>
                                        <label for="color" class="block text-sm font-medium text-gray-700 mb-1">Color</label>
                                        <input type="text" name="color" id="color" value="{{ old('color') }}" class="w-full p-2.5 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                                    </div>
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div class="space-y-6">
                                <!-- Additional Details Section -->
                                <div class="border border-gray-200 rounded-lg p-5 bg-white shadow-sm">
                                    <h3 class="text-lg font-medium text-gray-800 mb-4 flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Additional Details
                                    </h3>
                                    
                                    <!-- Birth Date -->
                                    <div class="mb-4">
                                        <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-1">Birth Date</label>
                                        <input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date') }}" class="w-full p-2.5 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                                    </div>

                                    <!-- Medical Condition -->
                                    <div>
                                        <label for="medical_condition" class="block text-sm font-medium text-gray-700 mb-1">Medical Condition</label>
                                        <textarea name="medical_condition" id="medical_condition" rows="3" class="w-full p-2.5 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 shadow-sm">{{ old('medical_condition') }}</textarea>
                                    </div>
                                </div>

                                <!-- Photos Section -->
                                <div class="border border-gray-200 rounded-lg p-5 bg-white shadow-sm">
                                    <h3 class="text-lg font-medium text-gray-800 mb-4 flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        Photos
                                    </h3>
                                    
                                    <div class="grid grid-cols-2 gap-4">
                                        @foreach (['photo_front' => 'Front View', 'photo_back' => 'Back View', 'photo_left_side' => 'Left Side', 'photo_right_side' => 'Right Side'] as $field => $label)
                                            <div class="mb-3">
                                                <label for="{{ $field }}" class="block text-sm font-medium text-gray-700 mb-1">{{ $label }}</label>
                                                <div class="flex items-center justify-center w-full">
                                                    <label for="{{ $field }}" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                                                        <div id="{{ $field }}-placeholder" class="flex flex-col items-center justify-center pt-5 pb-6">
                                                            <svg class="w-8 h-8 mb-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                                            </svg>
                                                            <p class="text-xs text-gray-500">Upload {{ $label }}</p>
                                                        </div>
                                                        <div id="{{ $field }}-preview" class="hidden w-full h-full"></div>
                                                        <input type="file" name="{{ $field }}" id="{{ $field }}" class="hidden" onchange="previewImage(event, '{{ $field }}')">
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="mt-8 flex justify-between border-t border-gray-200 pt-6">
                            <button onclick="window.history.back()" class="bg-gray-500 hover:bg-gray-600 focus:ring-4 focus:ring-gray-300 focus:outline-none text-white font-semibold rounded-lg px-8 py-3 shadow-md transition duration-300 ease-in-out transform hover:scale-105">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 inline" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                                Cancel
                            </button>
                            <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Save Pet
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            // Function to toggle the display of group fields based on the selection
            function toggleGroupFields() {
                var isGroup = document.getElementById('is_group').value;
                var nameLabel = document.querySelector('label[for="name"]');
                var genderField = document.getElementById('gender');

                if (isGroup == '1') {
                    // Update name label to "Group Name"
                    nameLabel.textContent = "Group Name";

                    // Set gender to "N/A" and hide the field
                    genderField.value = "N/A";
                    genderField.closest('div').classList.add('hidden');

                    // Show group-specific fields and hide individual-specific fields
                    document.getElementById('group-fields').classList.remove('hidden');
                    document.getElementById('individual-animal-fields').classList.add('hidden');
                } else {
                    // Update name label to "Animal Name"
                    nameLabel.textContent = "Animal Name";

                    // Reset gender visibility and clear its value
                    genderField.closest('div').classList.remove('hidden');
                    genderField.value = "";

                    // Hide group-specific fields and show individual-specific fields
                    document.getElementById('group-fields').classList.add('hidden');
                    document.getElementById('individual-animal-fields').classList.remove('hidden');
                }
            }

            // Preview the uploaded image before submission
            function previewImage(event, field) {
                var reader = new FileReader();
                reader.onload = function() {
                    var preview = document.getElementById(field + '-preview');
                    var placeholder = document.getElementById(field + '-placeholder');
                    
                    preview.innerHTML = `<img src="${reader.result}" alt="Preview" class="w-full h-full object-cover rounded-lg">`;
                    preview.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                };
                reader.readAsDataURL(event.target.files[0]);
            }

            // Handle the change event for the species field to fetch corresponding breeds
            $(document).ready(function() {
                $('#species_id').change(function() {
                    var speciesId = $(this).val();

                    if (speciesId) {
                        $.ajax({
                            url: '/get-breed/' + speciesId,
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

                // Initialize the form state
                toggleGroupFields();
            });
        </script>
    </x-app-layout>
