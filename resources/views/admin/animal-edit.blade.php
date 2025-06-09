<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Main Form Card -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <!-- Header Section -->
            <div class="relative h-32 bg-gradient-to-r from-green-800 to-green-600">
                <div class="absolute inset-0 bg-black/20"></div>
                <div class="absolute bottom-6 left-8">
                    <h2 class="text-2xl font-bold text-white">Edit Animal Profile</h2>
                    <p class="text-green-100 mt-1">Update information and photos</p>
                </div>
            </div>

            <!-- Error and Success Messages -->
            <div class="px-8 pt-6">
                @if ($errors->any())
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg mb-6">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-exclamation-circle text-red-500 mr-2"></i>
                            <p class="font-semibold text-red-800">Please correct the following errors:</p>
                        </div>
                        <ul class="list-disc list-inside text-red-700">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('success'))
                    <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg mb-6">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                            <p class="text-green-800">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Form Content -->
            <form action="{{ route('animals.update', $animal->animal_id) }}" method="POST" enctype="multipart/form-data" class="p-8">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Basic Information Section -->
                    <div class="space-y-6">
                        <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Basic Information</h3>
                        
                        <!-- Group Status -->
                        <div class="flex space-x-4">
                            <div class="flex-1">
                                <label for="is_group" class="block text-sm font-medium text-gray-600 mb-1">Group Status</label>
                                <select name="is_group" id="is_group" class="w-full p-2.5 border border-gray-300 rounded-lg shadow-sm" onchange="toggleGroupFields()">
                                    <option value="0" {{ old('is_group', $animal->is_group) == '0' ? 'selected' : '' }}>Individual Animal</option>
                                    <option value="1" {{ old('is_group', $animal->is_group) == '1' ? 'selected' : '' }}>Animal Group</option>
                                </select>
                            </div>
                            <div class="flex-1">
                                <label for="is_vaccinated" class="block text-sm font-medium text-gray-600 mb-1">Vaccination Status</label>
                                <select name="is_vaccinated" id="is_vaccinated" class="w-full p-2.5 border border-gray-300 rounded-lg shadow-sm">
                                    <option value="" {{ old('is_vaccinated', $animal->is_vaccinated) === null ? 'selected' : '' }}>Select Status</option>
                                    <option value="0" {{ old('is_vaccinated', $animal->is_vaccinated) == '0' ? 'selected' : '' }}>Not Vaccinated</option>
                                    <option value="1" {{ old('is_vaccinated', $animal->is_vaccinated) == '1' ? 'selected' : '' }}>Vaccinated</option>
                                    <option value="2" {{ old('is_vaccinated', $animal->is_vaccinated) == '2' ? 'selected' : '' }}>Not Required</option>
                                </select>
                            </div>
                        </div>

                        <!-- Name and Gender/Group Count -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-600 mb-1">Animal Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $animal->name) }}" 
                                   class="w-full p-2.5 border border-gray-300 rounded-lg shadow-sm">
                        </div>

                        <div id="individual-animal-fields">
                            <label for="gender" class="block text-sm font-medium text-gray-600 mb-1">Gender</label>
                            <select name="gender" id="gender" class="w-full p-2.5 border border-gray-300 rounded-lg shadow-sm">
                                <option value="Male" {{ old('gender', $animal->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ old('gender', $animal->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                            </select>
                        </div>

                        <div id="group-fields" class="{{ $animal->is_group ? '' : 'hidden' }}">
                            <label for="group_count" class="block text-sm font-medium text-gray-600 mb-1">Number of Animals</label>
                            <input type="number" name="group_count" id="group_count" value="{{ old('group_count', $animal->group_count) }}" 
                                   class="w-full p-2.5 border border-gray-300 rounded-lg shadow-sm" min="1">
                        </div>

                        <!-- Species and Breed -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="species_id" class="block text-sm font-medium text-gray-600 mb-1">Species</label>
                                <select name="species_id" id="species_id" class="w-full p-2.5 border border-gray-300 rounded-lg shadow-sm" required>
                                    @foreach ($species as $specie)
                                        <option value="{{ $specie->id }}" {{ old('species_id', $animal->species_id) == $specie->id ? 'selected' : '' }}>
                                            {{ $specie->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="breed_id" class="block text-sm font-medium text-gray-600 mb-1">Breed</label>
                                <select name="breed_id" id="breed_id" class="w-full p-2.5 border border-gray-300 rounded-lg shadow-sm" required>
                                    <option value="">Select a breed</option>
                                    @foreach ($breeds as $breed)
                                        <option value="{{ $breed->id }}" {{ old('breed_id', $animal->breed_id) == $breed->id ? 'selected' : '' }}>
                                            {{ $breed->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Details Section -->
                    <div class="space-y-6">
                        <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Additional Details</h3>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="color" class="block text-sm font-medium text-gray-600 mb-1">Color</label>
                                <input type="text" name="color" id="color" value="{{ old('color', $animal->color ?? '') }}"
                                       class="w-full p-2.5 border border-gray-300 rounded-lg shadow-sm">
                            </div>
                            <div>
                                <label for="birth_date" class="block text-sm font-medium text-gray-600 mb-1">Birth Date</label>
                                <input type="date" name="birth_date" id="birth_date" 
                                       value="{{ old('birth_date', optional($animal->birth_date)->format('Y-m-d')) }}"
                                       class="w-full p-2.5 border border-gray-300 rounded-lg shadow-sm">
                            </div>
                        </div>

                        <div>
                            <label for="medical_condition" class="block text-sm font-medium text-gray-600 mb-1">Medical Condition</label>
                            <textarea name="medical_condition" id="medical_condition" rows="3" 
                                      class="w-full p-2.5 border border-gray-300 rounded-lg shadow-sm">{{ old('medical_condition', $animal->medical_condition) }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Photos Section -->
                <div class="mt-8">
                    <h3 class="text-lg font-semibold text-gray-900 border-b pb-2 mb-6">Animal Photos</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                        @foreach (['front', 'back', 'left_side', 'right_side'] as $field)
                            <div class="space-y-2">
                                <label for="photo_{{ $field }}" class="block text-sm font-medium text-gray-600">
                                    {{ ucfirst(str_replace('_', ' ', $field)) }} View
                                </label>
                                <div class="relative">
                                    <div class="aspect-square rounded-lg border-2 border-dashed border-gray-300 overflow-hidden">
                                        <img id="preview_{{ $field }}" 
                                             src="{{ $animal->{'photo_' . $field} ? asset('storage/' . $animal->{'photo_' . $field}) : '' }}"
                                             class="w-full h-full object-cover {{ $animal->{'photo_' . $field} ? '' : 'hidden' }}">
                                        <div class="absolute inset-0 flex items-center justify-center {{ $animal->{'photo_' . $field} ? 'hidden' : '' }}">
                                            <i class="fas fa-camera text-gray-400 text-2xl"></i>
                                        </div>
                                    </div>
                                    <input type="file" name="photo_{{ $field }}" id="photo_{{ $field }}"
                                           class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                           onchange="previewImage(event, 'preview_{{ $field }}')">
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="mt-8 flex justify-end">
                    <button type="submit" class="px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors duration-200">
                        Update Animal Profile
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function toggleGroupFields() {
            const isGroup = document.getElementById('is_group').value;
            const groupFields = document.getElementById('group-fields');
            const individualFields = document.getElementById('individual-animal-fields');

            if (isGroup === '1') {
                groupFields.classList.remove('hidden');
                individualFields.classList.add('hidden');
            } else {
                groupFields.classList.add('hidden');
                individualFields.classList.remove('hidden');
            }
        }

        document.addEventListener('DOMContentLoaded', toggleGroupFields);

        function previewImage(event, previewId) {
            const reader = new FileReader();
            reader.onload = function() {
                const preview = document.getElementById(previewId);
                preview.src = reader.result;
                preview.classList.remove('hidden');
                preview.parentElement.querySelector('.flex').classList.add('hidden');
            }
            reader.readAsDataURL(event.target.files[0]);
        }

        // Fetch breeds based on selected species
        document.getElementById('species_id').addEventListener('change', function () {
            const speciesId = this.value;

            fetch(`/get-breedz/${speciesId}`)
                .then(response => response.json())
                .then(data => {
                    const breedSelect = document.getElementById('breed_id');
                    breedSelect.innerHTML = '<option value="">Select a breed</option>';
                    data.breeds.forEach(breed => {
                        const option = document.createElement('option');
                        option.value = breed.id;
                        option.textContent = breed.name;
                        breedSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error fetching breeds:', error));
        });
    </script>
</x-app-layout>
