<x-app-layout>
    <div class="max-w-4xl mx-auto p-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6 text-center">Update Animal</h1>

        <!-- Error Messages -->
        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-6">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Success Message -->
        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-4 rounded-lg mb-6">
                {{ session('success') }}
            </div>
        @endif

        <!-- Form -->
        <form action="{{ route('owner.NewupdateAnimal', ['owner_id' => $owner_id, 'animal_id' => $animal->animal_id]) }}" 
              method="POST" 
              enctype="multipart/form-data" 
              class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Type Selection Card -->
            <div class="bg-white dark:bg-neutral-800 shadow-lg rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Animal Type</h2>
                <div>
                    <label for="is_group" class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-2">
                        Registration Type <span class="text-red-500">*</span>
                    </label>
                    <select name="is_group" id="is_group" 
                            class="w-full p-3 bg-gray-50 dark:bg-neutral-700 border border-gray-300 dark:border-neutral-600 text-gray-900 dark:text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                        <option value="0" {{ old('is_group', $animal->is_group) == '0' ? 'selected' : '' }}>Individual Animal</option>
                        <option value="1" {{ old('is_group', $animal->is_group) == '1' ? 'selected' : '' }}>Group of Animals</option>
                    </select>
                </div>
            </div>

            <!-- Basic Information Card -->
            <div class="bg-white dark:bg-neutral-800 shadow-lg rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Basic Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Name -->
                    <div>
                        <label for="name" id="name-label" class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-2">
                            {{ $animal->is_group ? 'Group Name' : 'Animal Name' }} <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" 
                               value="{{ old('name', $animal->name) }}" 
                               class="w-full p-3 bg-gray-50 dark:bg-neutral-700 border border-gray-300 dark:border-neutral-600 text-gray-900 dark:text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm"
                               required>
                    </div>

                    <!-- Gender (Hidden for Groups) -->
                    <div id="gender-container" class="{{ $animal->is_group ? 'hidden' : '' }}">
                        <label for="gender" class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-2">
                            Gender <span class="text-red-500">*</span>
                        </label>
                        <select name="gender" id="gender" 
                                class="w-full p-3 bg-gray-50 dark:bg-neutral-700 border border-gray-300 dark:border-neutral-600 text-gray-900 dark:text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                            <option value="Male" {{ old('gender', $animal->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('gender', $animal->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                        </select>
                    </div>

                    <!-- Group Count (Shown for Groups) -->
                    <div id="group-count-container" class="{{ $animal->is_group ? '' : 'hidden' }}">
                        <label for="group_count" class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-2">
                            Number of Animals <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="group_count" id="group_count" 
                               value="{{ old('group_count', $animal->group_count) }}" 
                               class="w-full p-3 bg-gray-50 dark:bg-neutral-700 border border-gray-300 dark:border-neutral-600 text-gray-900 dark:text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm"
                               min="1">
                    </div>
                </div>
            </div>

            <!-- Species & Breed Card -->
            <div class="bg-white dark:bg-neutral-800 shadow-lg rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Species & Breed</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Species -->
                    <div>
                        <label for="species_id" class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-2">
                            Species <span class="text-red-500">*</span>
                        </label>
                        <select name="species_id" id="species_id" 
                                class="w-full p-3 bg-gray-50 dark:bg-neutral-700 border border-gray-300 dark:border-neutral-600 text-gray-900 dark:text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm"
                                required>
                            <option value="">Select a species</option>
                            @foreach ($species as $specie)
                                <option value="{{ $specie->id }}" {{ old('species_id', $animal->species_id) == $specie->id ? 'selected' : '' }}>
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
                                class="w-full p-3 bg-gray-50 dark:bg-neutral-700 border border-gray-300 dark:border-neutral-600 text-gray-900 dark:text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm"
                                required>
                            <option value="">Select a breed</option>
                            @foreach ($breeds as $breed)
                                <option value="{{ $breed->id }}" {{ old('breed_id', $animal->breed_id) == $breed->id ? 'selected' : '' }}>
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
                               class="w-full p-3 bg-gray-50 dark:bg-neutral-700 border border-gray-300 dark:border-neutral-600 text-gray-900 dark:text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                    </div>
                </div>
            </div>

            <!-- Additional Details Card -->
            <div class="bg-white dark:bg-neutral-800 shadow-lg rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Additional Details</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Birth Date -->
                    <div>
                        <label for="birth_date" class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-2">Birth Date</label>
                        <input type="date" name="birth_date" id="birth_date" 
                               value="{{ old('birth_date', optional($animal->birth_date)->format('Y-m-d')) }}" 
                               class="w-full p-3 bg-gray-50 dark:bg-neutral-700 border border-gray-300 dark:border-neutral-600 text-gray-900 dark:text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                    </div>
                </div>

                <!-- Medical Condition -->
                <div class="mt-6">
                    <label for="medical_condition" class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-2">Medical Condition</label>
                    <textarea name="medical_condition" id="medical_condition" rows="4"
                              class="w-full p-3 bg-gray-50 dark:bg-neutral-700 border border-gray-300 dark:border-neutral-600 text-gray-900 dark:text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm">{{ old('medical_condition', $animal->medical_condition) }}</textarea>
                </div>
            </div>

            <!-- Photos Card -->
            <div class="bg-white dark:bg-neutral-800 shadow-lg rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Animal Photos</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach (['front', 'back', 'left_side', 'right_side'] as $position)
                        <div>
                            <label for="photo_{{ $position }}" class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-2">
                                {{ ucfirst(str_replace('_', ' ', $position)) }} Photo
                            </label>
                            <input type="file" name="photo_{{ $position }}" id="photo_{{ $position }}" 
                                   class="w-full p-3 bg-gray-50 dark:bg-neutral-700 border border-gray-300 dark:border-neutral-600 text-gray-900 dark:text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm" 
                                   accept="image/*">
                            @if ($animal->{'photo_' . $position})
                                <div class="mt-2">
                                    <img src="{{ asset('storage/' . $animal->{'photo_' . $position}) }}" 
                                         alt="{{ ucfirst($position) }} Photo" 
                                         class="w-32 h-32 object-cover rounded-lg">
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end space-x-4">
              
                <button type="submit" 
                        class="px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 transition-colors">
                    Update Animal
                </button>
            </div>
        </form>
    </div>

    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
            // Toggle fields based on the is_group value
            $('#is_group').change(function() {
                var isGroup = $(this).val() === '1';
                $('#gender-container').toggleClass('hidden', isGroup);
                $('#group-count-container').toggleClass('hidden', !isGroup);
                $('#name-label').text(isGroup ? 'Group Name *' : 'Animal Name *');
            });

            // Trigger change event on page load to set initial state
            $('#is_group').trigger('change');

            // Load breeds dynamically when species is selected
            $('#species_id').change(function() {
                var speciesId = $(this).val();
                if (speciesId) {
                    $.ajax({
                        url: '/get-breed/' + speciesId,
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
        });
    </script>
</x-app-layout>
