<x-app-layout>
    <div class="max-w-4xl mx-auto p-8 bg-white rounded-lg shadow-lg relative">
        <!-- Title with Gradient Background -->
        <div class="text-center mb-6 bg-gradient-to-r from-green-800 to-green-600 p-6 rounded-t-lg -mt-8 -mx-8 shadow-md">
            <h2 class="text-3xl font-bold text-white">Add Animal</h2>
            <p class="text-lg text-blue-100">Fill in the details below to add a new animal</p>
        </div>

        <!-- Error and Success Messages -->
        @if ($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded mb-6">
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
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded mb-6">
                <div class="flex items-center">
                    <svg class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

        <!-- Animal Form -->
        <form action="{{ route('admin-animals.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Form Sections -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <!-- Left Column - Basic Info -->
                <div class="lg:col-span-6 space-y-6">
                    <h3 class="font-semibold text-gray-800 text-lg border-b pb-2">Basic Information</h3>
                    
                    <!-- Owner Selection -->
                    <div class="mb-4">
                        <label for="owner_id" class="text-gray-700 font-medium">Owner</label>
                        <select name="owner_id" id="owner_id" class="block mt-1 w-full border border-gray-300 rounded-lg p-3 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                            <option value="">Select an owner</option>
                            @foreach ($owners as $owner)
                                <option value="{{ $owner->owner_id }}" {{ old('owner_id') == $owner->owner_id ? 'selected' : '' }}>
                                    {{ $owner->complete_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Animal/Group Type Selection -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <!-- Is Group? -->
                        <div>
                            <label for="is_group" class="text-gray-700 font-medium">Animal Type</label>
                            <select name="is_group" id="is_group" class="block mt-1 w-full border border-gray-300 rounded-lg p-3 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" onchange="toggleGroupFields()">
                                <option value="0" {{ old('is_group') == '0' ? 'selected' : '' }}>Individual Animal</option>
                                <option value="1" {{ old('is_group') == '1' ? 'selected' : '' }}>Animal Group</option>
                            </select>
                        </div>

                        <!-- Vaccination Status -->
                        <div>
                            <label for="is_vaccinated" class="text-gray-700 font-medium">Vaccination Status</label>
                            <select name="is_vaccinated" id="is_vaccinated" class="block mt-1 w-full border border-gray-300 rounded-lg p-3 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="" {{ old('is_vaccinated',) === null ? 'selected' : '' }}>Select Status</option>
                                <option value="0" {{ old('is_vaccinated') == '0' ? 'selected' : '' }}>Not Vaccinated</option>
                                <option value="1" {{ old('is_vaccinated') == '1' ? 'selected' : '' }}>Vaccinated</option>
                                <option value="2" {{ old('is_vaccinated') == '2' ? 'selected' : '' }}>No Vaccination Required</option>
                            </select>
                        </div>
                    </div>

                    <!-- Name and Gender -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <!-- Animal Name -->
                        <div>
                            <label for="name" class="text-gray-700 font-medium">Animal Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" class="block mt-1 w-full border border-gray-300 rounded-lg p-3 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- Individual Animal Fields -->
                        <div id="individual-animal-fields">
                            <label for="gender" class="text-gray-700 font-medium">Gender</label>
                            <select name="gender" id="gender" class="block mt-1 w-full border border-gray-300 rounded-lg p-3 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select Gender</option>
                                <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                            </select>
                        </div>

                        <!-- Group Animal Fields -->
                        <div id="group-fields" class="hidden">
                            <label for="group_count" class="text-gray-700 font-medium">Group Count</label>
                            <input 
                                type="number" 
                                name="group_count" 
                                id="group_count" 
                                value="{{ old('group_count', 1) }}" 
                                class="block mt-1 w-full border border-gray-300 rounded-lg p-3 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                min="1"
                            >
                        </div>
                    </div>

                    <!-- Species and Breed -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <!-- Species -->
                        <div>
                            <label for="species_id" class="text-gray-700 font-medium">Species</label>
                            <select name="species_id" id="species_id" class="block mt-1 w-full border border-gray-300 rounded-lg p-3 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                <option value="">Select a species</option>
                                @foreach ($species as $specie)
                                    <option value="{{ $specie->id }}" {{ old('species_id') == $specie->id ? 'selected' : '' }}>{{ $specie->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Breed -->
                        <div>
                            <label for="breed_id" class="text-gray-700 font-medium">Breed</label>
                            <select name="breed_id" id="breed_id" class="block mt-1 w-full border border-gray-300 rounded-lg p-3 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                <option value="">Select a breed</option>
                                @foreach ($breeds as $breed)
                                    <option value="{{ $breed->id }}" {{ old('breed_id') == $breed->id ? 'selected' : '' }}>{{ $breed->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Color and Birth Date -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <!-- Color -->
                        <div>
                            <label for="color" class="text-gray-700 font-medium">Color</label>
                            <input type="text" name="color" id="color" value="{{ old('color') }}" class="block mt-1 w-full border border-gray-300 rounded-lg p-3 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- Birth Date -->
                        <div>
                            <label for="birth_date" class="text-gray-700 font-medium">Birth Date</label>
                            <input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date') }}" class="block mt-1 w-full border border-gray-300 rounded-lg p-3 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                    
                    <!-- Medical Condition -->
                    <div class="mb-4">
                        <label for="medical_condition" class="text-gray-700 font-medium">Medical Condition</label>
                        <textarea name="medical_condition" id="medical_condition" rows="3" class="block mt-1 w-full border border-gray-300 rounded-lg p-3 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('medical_condition') }}</textarea>
                    </div>
                </div>
                
                <!-- Right Column - Photos -->
                <div class="lg:col-span-6 space-y-6">
                    <h3 class="font-semibold text-gray-800 text-lg border-b pb-2">Animal Photos</h3>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach (['photo_front' => 'Front View', 'photo_back' => 'Back View', 'photo_left_side' => 'Left Side', 'photo_right_side' => 'Right Side'] as $field => $label)
                            <div class="border border-dashed border-gray-300 rounded-lg p-4">
                                <label for="{{ $field }}" class="text-gray-700 font-medium mb-2">{{ $label }}</label>
                                
                                <div id="{{ $field }}-preview" class="mb-2 min-h-[100px] flex items-center justify-center bg-gray-50 rounded">
                                    <span class="text-gray-400 text-sm">Image preview</span>
                                </div>
                                
                                <div class="flex items-center justify-center">
                                    <label for="{{ $field }}" class="cursor-pointer px-3 py-2 text-sm font-medium text-center text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                                        <span>Choose File</span>
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
                <div class="flex items-center justify-center gap-64">
                    <button type="button" onclick="window.history.back()" class="bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg px-8 py-3 shadow-md transition duration-300 ease-in-out transform hover:scale-105">
                        Cancel
                    </button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg px-8 py-3 shadow-md transition duration-300 ease-in-out transform hover:scale-105">
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
                        <img src="${e.target.result}" alt="Preview" class="w-full h-[120px] object-cover rounded">
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