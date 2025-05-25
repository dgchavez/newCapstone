<x-app-layout>
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-4xl mx-auto">
            <!-- Header Section -->
            <div class="mb-8 text-center">
                <h2 class="text-3xl font-bold text-gray-900">Update Animal Profile</h2>
                <p class="mt-2 text-sm text-gray-600">Update the information for this animal's profile</p>
            </div>

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-md">
                    <div class="flex items-center mb-2">
                        <i class="fas fa-exclamation-circle text-red-500 mr-2"></i>
                        <p class="text-sm font-medium text-red-800">Please correct the following errors:</p>
                    </div>
                    <ul class="ml-6 text-sm text-red-700 list-disc">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Success Message -->
            @if (session('success'))
                <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-md">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                        <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <!-- Form -->
            <form action="{{ route('owner.updateAnimal', ['owner_id' => $owner_id, 'animal_id' => $animal->animal_id]) }}" 
                  method="POST" 
                  enctype="multipart/form-data" 
                  class="bg-white shadow-md rounded-lg overflow-hidden">
                @csrf
                @method('PUT')

                <!-- Form Sections -->
                <div class="p-6 space-y-6">
                    <!-- Basic Information Section -->
                    <div class="border-b border-gray-200 pb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Basic Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Group Toggle -->
                            <div class="col-span-1">
                                <label for="is_group" class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                                <select name="is_group" id="is_group" 
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                                    <option value="0" {{ old('is_group', $animal->is_group) == '0' ? 'selected' : '' }}>Individual Animal</option>
                                    <option value="1" {{ old('is_group', $animal->is_group) == '1' ? 'selected' : '' }}>Group of Animals</option>
                                </select>
                            </div>

                            <!-- Name -->
                            <div class="col-span-1">
                                <label for="name" id="name-label" class="block text-sm font-medium text-gray-700 mb-1">
                                    {{ $animal->is_group ? 'Group Name' : 'Animal Name' }}
                                </label>
                                <input type="text" name="name" id="name" 
                                       value="{{ old('name', $animal->name) }}" 
                                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500" 
                                       required>
                            </div>

                            <!-- Species -->
                            <div class="col-span-1">
                                <label for="species_id" class="block text-sm font-medium text-gray-700 mb-1">Species</label>
                                <select name="species_id" id="species_id" 
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500" 
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
                            <div class="col-span-1">
                                <label for="breed_id" class="block text-sm font-medium text-gray-700 mb-1">Breed</label>
                                <select name="breed_id" id="breed_id" 
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500" 
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
                        </div>
                    </div>

                    <!-- Details Section -->
                    <div class="border-b border-gray-200 pb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Additional Details</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Vaccination Status -->
                            <div class="col-span-1">
                                <label for="is_vaccinated" class="block text-sm font-medium text-gray-700 mb-1">Vaccination Status</label>
                                <select name="is_vaccinated" id="is_vaccinated" 
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                                    <option value="0" {{ old('is_vaccinated', $animal->is_vaccinated) == '0' ? 'selected' : '' }}>Not Vaccinated</option>
                                    <option value="1" {{ old('is_vaccinated', $animal->is_vaccinated) == '1' ? 'selected' : '' }}>Vaccinated</option>
                                    <option value="3" {{ old('is_vaccinated', $animal->is_vaccinated) == '3' ? 'selected' : '' }}>No Vaccination Required</option>
                                </select>
                            </div>

                            <!-- Color -->
                            <div class="col-span-1">
                                <label for="color" class="block text-sm font-medium text-gray-700 mb-1">Color</label>
                                <input type="text" name="color" id="color" 
                                       value="{{ old('color', $animal->color ?? '') }}"
                                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                            </div>

                            <!-- Gender (Hidden for Groups) -->
                            <div id="gender-container" class="col-span-1 {{ $animal->is_group ? 'hidden' : '' }}">
                                <label for="gender" class="block text-sm font-medium text-gray-700 mb-1">Gender</label>
                                <select name="gender" id="gender" 
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                                    <option value="Male" {{ old('gender', $animal->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ old('gender', $animal->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                                </select>
                            </div>

                            <!-- Group Count (Shown for Groups) -->
                            <div id="group-count-container" class="col-span-1 {{ $animal->is_group ? '' : 'hidden' }}">
                                <label for="group_count" class="block text-sm font-medium text-gray-700 mb-1">Number of Animals</label>
                                <input type="number" name="group_count" id="group_count" 
                                       value="{{ old('group_count', $animal->group_count) }}" 
                                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500" 
                                       min="1">
                            </div>

                            <!-- Birth Date -->
                            <div class="col-span-1">
                                <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-1">Birth Date</label>
                                <input type="date" name="birth_date" id="birth_date" 
                                       value="{{ old('birth_date', optional($animal->birth_date)->format('Y-m-d')) }}" 
                                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                            </div>
                        </div>

                        <!-- Medical Condition -->
                        <div class="mt-6">
                            <label for="medical_condition" class="block text-sm font-medium text-gray-700 mb-1">Medical Condition</label>
                            <textarea name="medical_condition" id="medical_condition" rows="3"
                                      class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500">{{ old('medical_condition', $animal->medical_condition) }}</textarea>
                        </div>
                    </div>

                    <!-- Photos Section -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Animal Photos</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                            @foreach (['front', 'back', 'left_side', 'right_side'] as $position)
                                <div class="relative">
                                    <label for="photo_{{ $position }}" class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ ucfirst(str_replace('_', ' ', $position)) }} View
                                    </label>
                                    <div class="relative border-2 border-dashed border-gray-300 rounded-lg p-4 hover:border-blue-500 transition-colors">
                                        @if ($animal->{'photo_' . $position})
                                            <img src="{{ asset('storage/' . $animal->{'photo_' . $position}) }}" 
                                                 alt="{{ ucfirst($position) }} Photo" 
                                                 class="w-full h-32 object-cover rounded-lg mb-2">
                                        @else
                                            <div class="w-full h-32 bg-gray-50 rounded-lg flex items-center justify-center mb-2">
                                                <i class="fas fa-camera text-gray-400 text-2xl"></i>
                                            </div>
                                        @endif
                                        <input type="file" name="photo_{{ $position }}" id="photo_{{ $position }}" 
                                               class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                               accept="image/*">
                                        <p class="text-xs text-center text-gray-500">Click to {{ $animal->{'photo_' . $position} ? 'change' : 'upload' }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="px-6 py-4 bg-gray-50 flex justify-end space-x-4">
                    <a href="{{ route('owners.profile-owner', ['owner_id' => $owner_id]) }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-times mr-2"></i>
                        Cancel
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-save mr-2"></i>
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Toggle fields based on the is_group value
            $('#is_group').change(function() {
                var isGroup = $(this).val() === '1';
                $('#gender-container').toggleClass('hidden', isGroup);
                $('#group-count-container').toggleClass('hidden', !isGroup);
                $('#name-label').text(isGroup ? 'Group Name' : 'Animal Name');
            });

            // Trigger change event on page load to set initial state
            $('#is_group').trigger('change');

            // Load breeds dynamically when species is selected
            $('#species_id').change(function() {
                var speciesId = $(this).val();
                if (speciesId) {
                    $.ajax({
                        url: '/get-breeds/' + speciesId,
                        type: 'GET',
                        success: function(data) {
                            $('#breed_id').empty();
                            $('#breed_id').append('<option value="">Select a breed</option>');
                            $.each(data.breeds, function(index, breed) {
                                $('#breed_id').append('<option value="' + breed.id + '">' + breed.name + '</option>');
                            });
                        }
                    });
                } else {
                    $('#breed_id').empty();
                    $('#breed_id').append('<option value="">Select a breed</option>');
                }
            });

            // Preview uploaded images
            $('input[type="file"]').change(function(e) {
                if (this.files && this.files[0]) {
                    var reader = new FileReader();
                    var imgContainer = $(this).siblings('img').length ? 
                                     $(this).siblings('img') : 
                                     $(this).siblings('div');
                    
                    reader.onload = function(e) {
                        if (imgContainer.is('img')) {
                            imgContainer.attr('src', e.target.result);
                        } else {
                            imgContainer.html('<img src="' + e.target.result + '" class="w-full h-32 object-cover rounded-lg">');
                        }
                    }
                    
                    reader.readAsDataURL(this.files[0]);
                }
            });
        });
    </script>
</x-app-layout>
