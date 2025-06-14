<x-app-layout>

    <div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-lg relative">
        
        <!-- Title with Gradient Background -->
        <div class="text-center mb-6 bg-gradient-to-r from-green-800 to-green-600 p-6 rounded-t-lg -mt-8 -mx-8 shadow-md">
            <h2 class="text-3xl font-bold text-white">Edit Animal Owner</h2>
            <p class="text-lg text-green-100">Update the owner details below</p>
        </div>

        <!-- Error Messages -->
        @if ($errors->any())
            <div class="mb-4 bg-red-100 text-red-700 p-4 rounded-lg shadow-sm">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

      <!-- User Edit Form -->
      <form action="{{ route('admin.owner-form-update', $user->user_id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Current Profile Image -->
        <div class="mb-6 text-center">
            @if ($user->profile_image)
                <img class="w-32 h-32 rounded-full mx-auto object-cover border-4 border-blue-200 shadow-lg" src="{{ Storage::url($user->profile_image) }}" alt="Current Profile Image">
            @else
                <div class="w-32 h-32 rounded-full mx-auto bg-gray-200 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                    </svg>
                </div>
                <p class="text-gray-500 mt-2">No profile image uploaded</p>
            @endif
        </div>

        <!-- Form Sections with Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <!-- User Basic Info Section -->
            <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow duration-300">
                <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center border-b pb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-600" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                    </svg>
                    Basic Information
                </h3>
                
                <!-- Profile Image Upload -->
                <div class="mb-4">
                    <x-input-label for="profile_image" :value="__('Profile Image')" class="text-gray-700 font-medium"/>
                    <input type="file" name="profile_image" id="profile_image" accept="image/*" class="block mt-1 w-full border border-gray-300 rounded-lg p-3 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <p class="text-sm text-gray-600 mt-2">Upload a profile image (JPEG/PNG, max: 2MB).</p>
                </div>

                <!-- Complete Name -->
                <div class="mb-4">
                    <x-input-label for="complete_name" :value="__('Full Name')" class="text-gray-700 font-medium"/>
                    <x-text-input name="complete_name" id="complete_name" value="{{ old('complete_name', $user->complete_name) }}" class="block mt-1 w-full border border-gray-300 rounded-lg p-3 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required />
                </div>

                <!-- Status -->
                <div>
                    <x-input-label for="status" :value="__('Status')" class="text-gray-700 font-medium"/>
                    <select name="status" id="status" class="block mt-1 w-full border border-gray-300 rounded-lg p-3 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @if($user->status == 0)
                            <option value="0" selected>Pending</option>
                        @endif
                        <option value="1" {{ old('status', $user->status) == 1 ? 'selected' : '' }}>Enable</option>
                        <option value="2" {{ old('status', $user->status) == 2 ? 'selected' : '' }}>Disable</option>
                    </select>
                </div>
            </div>

            <!-- Contact Info Section -->
            <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow duration-300">
                <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center border-b pb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-600" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
                    </svg>
                    Contact Information
                </h3>
                
                <!-- Contact Number -->
                <div class="mb-4">
                    <x-input-label for="contact_no" :value="__('Contact Number')" class="text-gray-700 font-medium"/>
                    <div class="flex">
                        <span class="inline-flex items-center px-3 text-gray-500 bg-gray-100 border border-r-0 border-gray-300 rounded-l-lg">
                            +63
                        </span>
                        <x-text-input name="contact_no" id="contact_no" value="{{ old('contact_no', $user->contact_no) }}" class="block mt-0 w-full border border-gray-300 rounded-r-lg p-3 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="9XXXXXXXXX" required />
                    </div>
                </div>

                <!-- Gender -->
                <div class="mb-4">
                    <x-input-label for="gender" :value="__('Gender')" class="text-gray-700 font-medium"/>
                    <div class="mt-1 flex space-x-4">
                        <label class="inline-flex items-center">
                            <input type="radio" name="gender" value="Male" class="text-blue-600 focus:ring-blue-500 h-5 w-5" {{ old('gender', $user->gender) == 'Male' ? 'checked' : '' }}>
                            <span class="ml-2 text-gray-700">Male</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="gender" value="Female" class="text-blue-600 focus:ring-blue-500 h-5 w-5" {{ old('gender', $user->gender) == 'Female' ? 'checked' : '' }}>
                            <span class="ml-2 text-gray-700">Female</span>
                        </label>
                    </div>
                </div>

                <!-- Birth Date -->
                <div>
                    <x-input-label for="birth_date" :value="__('Birth Date')" class="text-gray-700 font-medium"/>
                    <x-text-input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date', $user->birth_date = \Carbon\Carbon::parse($user->birth_date)->format('Y-m-d')) }}" class="block mt-1 w-full border border-gray-300 rounded-lg p-3 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required />
                </div>
            </div>
        </div>

        <!-- Address Section -->
        <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow duration-300 mb-8">
            <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center border-b pb-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-600" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                </svg>
                Address Information
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Barangay Selection -->
                <div>
                    <x-input-label for="barangay_id" :value="__('Barangay')" class="text-gray-700 font-medium"/>
                    <select name="barangay_id" id="barangay_id" class="block mt-1 w-full border border-gray-300 rounded-lg p-3 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        <option value="">Select Barangay</option>
                        @foreach ($barangays as $barangay)
                            <option value="{{ $barangay->id }}" {{ old('barangay_id', $user->address->barangay_id ?? '') == $barangay->id ? 'selected' : '' }}>{{ $barangay->barangay_name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Street Name -->
                <div>
                    <x-input-label for="street" :value="__('Street Name')" class="text-gray-700 font-medium"/>
                    <x-text-input type="text" name="street" id="street" value="{{ old('street', $user->address->street ?? '') }}" class="block mt-1 w-full border border-gray-300 rounded-lg p-3 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required />
                </div>
            </div>
        </div>

        <!-- Owner-Specific Fields -->
        <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow duration-300 mb-8">
            <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center border-b pb-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-600" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 2a1 1 0 00-1 1v1a1 1 0 002 0V3a1 1 0 00-1-1zM4 4h3a3 3 0 006 0h3a2 2 0 012 2v9a2 2 0 01-2 2H4a2 2 0 01-2-2V6a2 2 0 012-2zm2.5 7a1.5 1.5 0 100-3 1.5 1.5 0 000 3zm2.45 4a2.5 2.5 0 10-4.9 0h4.9zM12 9a1 1 0 100 2h3a1 1 0 100-2h-3zm-1 4a1 1 0 011-1h2a1 1 0 110 2h-2a1 1 0 01-1-1z" clip-rule="evenodd" />
                </svg>
                Owner Details
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Civil Status -->
                <div>
                    <x-input-label for="civil_status" :value="__('Civil Status')" class="text-gray-700 font-medium"/>
                    <select name="civil_status" id="civil_status" class="block mt-1 w-full border border-gray-300 rounded-lg p-3 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        <option value="">Select Civil Status</option>
                        <option value="Married" {{ old('civil_status', $user->owner->civil_status ?? '') == 'Married' ? 'selected' : '' }}>Married</option>
                        <option value="Separated" {{ old('civil_status', $user->owner->civil_status ?? '') == 'Separated' ? 'selected' : '' }}>Separated</option>
                        <option value="Single" {{ old('civil_status', $user->owner->civil_status ?? '') == 'Single' ? 'selected' : '' }}>Single</option>
                        <option value="Widow" {{ old('civil_status', $user->owner->civil_status ?? '') == 'Widow' ? 'selected' : '' }}>Widow</option>
                    </select>
                    <x-input-error :messages="$errors->get('civil_status')" class="mt-2 text-sm text-red-500" />
                </div>
            </div>

            <!-- Categories Selection -->
            <div class="mt-4">
                <x-input-label for="categories" :value="__('Pet Categories')" class="text-gray-700 font-medium" />
                
                <!-- Special Categories (Radio Buttons) -->
                <div class="mt-2 mb-4">
                    <p class="text-sm font-medium text-gray-600 mb-2">Select one of the following:</p>
                    <div class="flex flex-wrap gap-4 p-3 border border-gray-300 rounded-lg bg-gray-50">
                        @foreach($categories as $category)
                                @if(in_array($category->id, [0, 8, 9]))
                                <div class="flex items-center">
                                    <input 
                                        type="radio" 
                                        id="special_category_{{ $category->id }}" 
                                        name="specialCategories" 
                                        value="{{ $category->id }}" 
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded-full"
                                        {{ in_array($category->id, $user->categories->pluck('id')->toArray()) ? 'checked' : '' }}
                                        onchange="handleSpecialCategorySelection({{ $category->id }})"
                                    >
                                    <input 
                                        type="checkbox" 
                                        id="hidden_category_{{ $category->id }}" 
                                        name="selectedCategories[]" 
                                        value="{{ $category->id }}" 
                                        class="hidden"
                                        {{ in_array($category->id, $user->categories->pluck('id')->toArray()) ? 'checked' : '' }}
                                    >
                                    <label for="special_category_{{ $category->id }}" class="ml-2 text-gray-700">{{ $category->name }}</label>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
                
                <!-- Regular Categories (Checkboxes) -->
                <div class="mt-2 p-4 border border-gray-300 rounded-lg bg-white shadow-sm">
                    <p class="text-sm font-medium text-gray-600 mb-2">Select all that apply:</p>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        @foreach($categories as $category)
                            @if(!in_array($category->id, [0, 8, 9]))
                                <div class="flex items-center category-item" 
                                     id="category_container_{{ $category->id }}"
                                     style="{{ in_array($category->id, [4, 6]) && $user->gender == 'Male' ? 'display: none;' : '' }}">
                                    <input 
                                        type="checkbox" 
                                        id="category_{{ $category->id }}" 
                                        name="selectedCategories[]" 
                                        value="{{ $category->id }}" 
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                        {{ in_array($category->id, $user->categories->pluck('id')->toArray()) ? 'checked' : '' }}
                                    >
                                    <label for="category_{{ $category->id }}" class="ml-2 text-gray-700">{{ $category->name }}</label>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
                <x-input-error :messages="$errors->get('selectedCategories')" class="mt-2 text-sm text-red-500" />
            </div>
        </div>

        <!-- Authentication Section -->
        <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow duration-300 mb-8">
            <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center border-b pb-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-600" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                </svg>
                Authentication
            </h3>

            <!-- Current Authentication Method Alert -->
            <div class="mb-4 p-3 rounded-lg {{ filter_var($user->email, FILTER_VALIDATE_EMAIL) ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2h-1V9a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="font-medium">Current login method: <span class="font-semibold">{{ filter_var($user->email, FILTER_VALIDATE_EMAIL) ? 'Email Address' : 'Username' }}</span></p>
                        <p class="text-sm mt-1">Current value: <span class="font-medium">{{ $user->email }}</span></p>
                    </div>
                </div>
            </div>

            <!-- Toggle between Email/Username -->
            <div class="mb-4">
                <x-input-label for="identifier_type" :value="__('Authentication Method')" class="text-gray-700 font-medium mb-2"/>
                <div class="p-3 bg-gray-50 rounded-lg border border-gray-200">
                    <div class="flex items-center justify-between">
                        <span class="{{ filter_var($user->email, FILTER_VALIDATE_EMAIL) ? 'font-semibold text-blue-600' : 'text-gray-500' }}">Email</span>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="hidden" name="is_email_field" value="0">
                            <input type="checkbox" 
                                   name="is_email_field" 
                                   value="1"
                                   class="sr-only peer"
                                   {{ filter_var($user->email, FILTER_VALIDATE_EMAIL) ? 'checked' : '' }}
                                   onchange="toggleAuthenticationMethod(this)">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                        <span class="{{ !filter_var($user->email, FILTER_VALIDATE_EMAIL) ? 'font-semibold text-blue-600' : 'text-gray-500' }}">Username</span>
                    </div>
                </div>
            </div>

            <!-- Email/Username Field -->
            <div>
                <x-input-label id="identifier_label" :value="__(filter_var($user->email, FILTER_VALIDATE_EMAIL) ? 'Email Address' : 'Username')" class="text-gray-700 font-medium"/>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                        </svg>
                    </div>
                    <x-text-input 
                        type="{{ filter_var($user->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'text' }}"
                        name="email" 
                        id="email" 
                        value="{{ old('email', $user->email) }}" 
                        class="block mt-1 w-full pl-10 border border-gray-300 rounded-lg p-3 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                        required 
                        placeholder="{{ filter_var($user->email, FILTER_VALIDATE_EMAIL) ? 'email@example.com' : 'username' }}"
                    />
                </div>
                <p class="mt-1 text-sm text-gray-500" id="identifier_help">
                    {{ filter_var($user->email, FILTER_VALIDATE_EMAIL) ? 'Enter valid email address' : 'Username must be at least 5 characters and can only contain letters, numbers, underscore and dot' }}
                </p>
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-500" />
            </div>
            
            <!-- Warning about changing authentication method -->
            <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">
                            <strong>Important:</strong> Changing the authentication method will clear the current value and require you to enter a new {{ filter_var($user->email, FILTER_VALIDATE_EMAIL) ? 'username' : 'email address' }}. The user will need to use this new credential to log in.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex items-center justify-center gap-4 mt-8">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 focus:outline-none text-white font-semibold rounded-lg px-8 py-3 shadow-md transition duration-300 ease-in-out transform hover:scale-105">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 inline" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                </svg>
                {{ __('Update Owner') }}
            </button>
            
            <a href="{{ route('owners.profile-owner', ['owner_id' => $user->owner->owner_id]) }}" class="bg-gray-500 hover:bg-gray-600 focus:ring-4 focus:ring-gray-300 focus:outline-none text-white font-semibold rounded-lg px-8 py-3 shadow-md transition duration-300 ease-in-out transform hover:scale-105">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 inline" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
                {{ __('Cancel') }}
            </a>
        </div>
      </form>
    </div>

    <script>
        function toggleAuthenticationMethod(checkbox) {
            const emailInput = document.getElementById('email');
            const identifierLabel = document.getElementById('identifier_label');
            const identifierHelp = document.getElementById('identifier_help');
            const isEmail = checkbox.checked;

            // Update input type
            emailInput.type = isEmail ? 'email' : 'text';
            
            // Update label
            identifierLabel.textContent = isEmail ? 'Email Address' : 'Username';
            
            // Update help text
            identifierHelp.textContent = isEmail 
                ? 'Enter valid email address' 
                : 'Username must be at least 5 characters and can only contain letters, numbers, underscore and dot';
            
            // Clear the input value when switching types
            emailInput.value = '';
            emailInput.placeholder = isEmail ? 'email@example.com' : 'username';
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Toggle owner fields if needed
            const ownerFields = document.getElementById('ownerFields');
            if (ownerFields) {
                ownerFields.classList.remove('hidden');
            }

            // Handle gender selection and category visibility
            const genderInputs = document.querySelectorAll('input[name="gender"]');
            
            function updateCategoryVisibility() {
                const isMale = document.querySelector('input[name="gender"]:checked')?.value === 'Male';
                
                // Hide/show categories 4 and 6 based on gender
                document.querySelectorAll('.category-item').forEach(item => {
                    const categoryId = parseInt(item.id.replace('category_container_', ''));
                    if (categoryId === 4 || categoryId === 6) {
                        item.style.display = isMale ? 'none' : 'flex';
                        
                        // Uncheck if hidden
                        if (isMale) {
                            const checkbox = document.getElementById(`category_${categoryId}`);
                            if (checkbox) checkbox.checked = false;
                        }
                    }
                });
            }

            genderInputs.forEach(input => {
                input.addEventListener('change', updateCategoryVisibility);
            });

            // Initial check
            updateCategoryVisibility();
            
            // Initialize special categories
            const specialCategories = document.querySelectorAll('input[name="specialCategories"]');
            specialCategories.forEach(radio => {
                if (radio.checked) {
                    handleSpecialCategorySelection(radio.value);
                }
            });
        });

        // Handle radio button selection for special categories
        function handleSpecialCategorySelection(categoryId) {
            const specialCategoryIds = [0, 8, 9];
            
            // Uncheck all hidden checkboxes for special categories
            specialCategoryIds.forEach(id => {
                const hiddenCheckbox = document.getElementById(`hidden_category_${id}`);
                if (hiddenCheckbox) {
                    hiddenCheckbox.checked = false;
                }
            });
            
            // Check only the selected one
            const selectedHiddenCheckbox = document.getElementById(`hidden_category_${categoryId}`);
            if (selectedHiddenCheckbox) {
                selectedHiddenCheckbox.checked = true;
            }
        }
    </script>
</x-app-layout>
