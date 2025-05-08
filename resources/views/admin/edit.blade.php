<x-app-layout>

    <div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-lg">
        
        <!-- Title Section -->
        <div class="text-center mb-6">
            <h2 class="text-3xl font-semibold text-gray-800">Edit User</h2>
            <p class="text-lg text-gray-600">Update the user details below.</p>
        </div>
        <!-- Logo -->
        <div class="text-center mb-8">
            <a href="/admin/users">
                <img class="h-20 w-auto mx-auto" src="{{ asset('assets/1.jpg') }}" alt="Your Logo">
            </a>
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
      <form action="{{ route('users.update', $user->user_id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Current Profile Image -->
        <div class="mb-6 text-center">
            @if ($user->profile_image)
            <img class="w-32 h-32 rounded-full mx-auto object-cover" src="{{ Storage::url($user->profile_image) }}" alt="Current Profile Image">

            @else
                <p class="text-gray-500">No profile image uploaded</p>
            @endif
        </div>

        <!-- Image Upload -->
        <div class="mb-6">
            <x-input-label for="profile_image" :value="__('Profile Image')" class="text-lg font-semibold text-gray-800"/>
            <input type="file" name="profile_image" id="profile_image" accept="image/*" class="mt-1 block w-full rounded-lg border-green-600 shadow-sm focus:ring-2 focus:ring-green-500">
            <p class="text-sm text-gray-600 mt-2">Upload a profile image (JPEG/PNG, max: 2MB).</p>
        </div>

            <div class="space-y-6">
                <!-- Complete Name -->
                <div>
                    <x-input-label for="complete_name" :value="__('Complete Name')" class="text-lg font-semibold text-gray-800"/>
                    <x-text-input name="complete_name" id="complete_name" value="{{ old('complete_name', $user->complete_name) }}" class="mt-1 block w-full rounded-lg border-green-600 shadow-sm focus:ring-2 focus:ring-green-500" required />
                </div>

                <!-- Role Selection -->
                <div>
                    <x-input-label for="role" :value="__('Role')" class="text-lg font-semibold text-gray-800"/>
                    <select name="role" id="role" class="mt-1 block w-full rounded-lg border-green-600 shadow-sm focus:ring-2 focus:ring-green-500" required onchange="toggleOwnerFields()">
                        <option value="1" {{ old('role', $user->role) == 1 ? 'selected' : '' }}>Animal Owner</option>
                        <option value="2" {{ old('role', $user->role) == 2 ? 'selected' : '' }}>Veterinarian</option>
                        <option value="3" {{ old('role', $user->role) == 3 ? 'selected' : '' }}>Veterinary Receptionist</option>
                    </select>
                </div>

                <!-- Designation Selection -->
                <div id="designationFields" class="{{ old('role', $user->role) == 2 ? '' : 'hidden' }}">
                    <div class="mt-6">
                        <x-input-label for="designation_id" :value="__('Designation')" class="text-lg font-semibold text-gray-800"/>
                        <select name="designation_id" id="designation_id" class="mt-1 block w-full rounded-lg border-green-600 shadow-sm focus:ring-2 focus:ring-green-500">
                            <option value="">Select Designation</option>
                            @foreach ($designations as $designation)
                                <option value="{{ $designation->designation_id }}" 
                                    {{ old('designation_id', $user->designation_id) == $designation->designation_id ? 'selected' : '' }}>
                                    {{ $designation->name }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('designation_id')" class="mt-2" />
                    </div>
                </div>
                

                <!-- Contact No. -->
                <div>
                    <x-input-label for="contact_no" :value="__('Contact No.')" class="text-lg font-semibold text-gray-800"/>
                    <x-text-input name="contact_no" id="contact_no" value="{{ old('contact_no', $user->contact_no) }}" class="mt-1 block w-full rounded-lg border-green-600 shadow-sm focus:ring-2 focus:ring-green-500" required />
                </div>

                <!-- Gender -->
                <div>
                    <x-input-label for="gender" :value="__('Gender')" class="text-lg font-semibold text-gray-800"/>
                    <select name="gender" id="gender" class="mt-1 block w-full rounded-lg border-green-600 shadow-sm focus:ring-2 focus:ring-green-500">
                        <option value="Male" {{ old('gender', $user->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ old('gender', $user->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                    </select>
                </div>

                <!-- Birth Date -->
                <div>
                    <x-input-label for="birth_date" :value="__('Birth Date')" class="text-lg font-semibold text-gray-800"/>
                    <x-text-input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date', $user->birth_date = \Carbon\Carbon::parse($user->birth_date)->format('Y-m-d')); }}" class="mt-1 block w-full rounded-lg border-green-600 shadow-sm focus:ring-2 focus:ring-green-500" required />
                </div>

                <!-- Status -->
                <div>
                    <x-input-label for="status" :value="__('Status')" class="text-lg font-semibold text-gray-800"/>
                    <select name="status" id="status" class="mt-1 block w-full rounded-lg border-green-600 shadow-sm focus:ring-2 focus:ring-green-500">
                        @if($user->status == 0)
                            <option value="0" selected>Pending</option>
                        @endif
                        <option value="1" {{ old('status', $user->status) == 1 ? 'selected' : '' }}>Enable</option>
                        <option value="2" {{ old('status', $user->status) == 2 ? 'selected' : '' }}>Disable</option>
                    </select>
                </div>

                <!-- Authentication Section -->
                <div class="space-y-4">
                    <!-- Authentication Method Toggle (only for owners) -->
                    @if($user->role == 1)
                    <div class="authentication-toggle-section">
                        <x-input-label :value="__('Authentication Method')" class="text-lg font-semibold text-gray-800 mb-2"/>
                        <div class="p-3 bg-gray-50 rounded-lg border border-gray-200">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-500">Email</span>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="hidden" name="is_email_field" value="0">
                                    <input type="checkbox" 
                                           name="is_email_field" 
                                           value="1"
                                           class="sr-only peer"
                                           {{ $user->is_email_field ? 'checked' : '' }}
                                           onchange="toggleAuthenticationMethod(this)">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                                </label>
                                <span class="text-gray-500">Username</span>
                            </div>
                        </div>
                    </div>
                    @else
                        <input type="hidden" name="is_email_field" value="1">
                    @endif

                    <!-- Email/Username Field (always visible) -->
                    <div>
                        <x-input-label id="identifier_label" :value="__($user->role == 1 ? ($user->is_email_field ? 'Email Address' : 'Username') : 'Email Address')" class="text-lg font-semibold text-gray-800"/>
                        <x-text-input 
                            type="{{ $user->role == 1 ? ($user->is_email_field ? 'email' : 'text') : 'email' }}"
                            name="identifier" 
                            id="identifier" 
                            value="{{ old('identifier', $user->email) }}" 
                            class="mt-1 block w-full rounded-lg border-green-600 shadow-sm focus:ring-2 focus:ring-green-500" 
                            required 
                        />
                        <p class="mt-1 text-sm text-gray-500" id="identifier_help">
                            @if($user->role == 1)
                                {{ $user->is_email_field ? 'Enter valid email address' : 'Username must be at least 5 characters and can only contain letters, numbers, underscore and dot' }}
                            @else
                                Enter valid email address
                            @endif
                        </p>
                        <x-input-error :messages="$errors->get('identifier')" class="mt-2" />
                    </div>
                </div>

                <!-- Address Fields -->
                <div>
                    <x-input-label for="barangay_id" :value="__('Barangay')" class="text-lg font-semibold text-gray-800"/>
                    <select name="barangay_id" id="barangay_id" class="mt-1 block w-full rounded-lg border-green-600 shadow-sm focus:ring-2 focus:ring-green-500" required>
                        <option value="">Select Barangay</option>
                        @foreach ($barangays as $barangay)
                            <option value="{{ $barangay->id }}" {{ old('barangay_id', $user->address->barangay_id ?? '') == $barangay->id ? 'selected' : '' }}>{{ $barangay->barangay_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <x-input-label for="street" :value="__('Street')" class="text-lg font-semibold text-gray-800"/>
                    <x-text-input type="text" name="street" id="street" value="{{ old('street', $user->address->street ?? '') }}" class="mt-1 block w-full rounded-lg border-green-600 shadow-sm focus:ring-2 focus:ring-green-500" />
                </div>

                <!-- Owner Details Section (Visible only if role is Animal Owner) -->
                <div id="ownerFields" class="{{ old('role', $user->role) == 1 ? '' : 'hidden' }}">
                    <div class="mt-6 space-y-4">
                        <div>
                            <x-input-label for="civil_status" :value="__('Civil Status')" class="text-lg font-semibold text-gray-800"/>
                            <select name="civil_status" id="civil_status" class="mt-1 block w-full rounded-lg border-green-600 shadow-sm focus:ring-2 focus:ring-green-500">
                                <option value="">Select Civil Status</option>
                                <option value="Married" {{ old('civil_status', $user->owner->civil_status ?? '') == 'Married' ? 'selected' : '' }}>Married</option>
                                <option value="Separated" {{ old('civil_status', $user->owner->civil_status ?? '') == 'Separated' ? 'selected' : '' }}>Separated</option>
                                <option value="Single" {{ old('civil_status', $user->owner->civil_status ?? '') == 'Single' ? 'selected' : '' }}>Single</option>
                                <option value="Widow" {{ old('civil_status', $user->owner->civil_status ?? '') == 'Widow' ? 'selected' : '' }}>Widow</option>
                            </select>
                            <x-input-error :messages="$errors->get('civil_status')" class="mt-2" />
                        </div>

                           <!-- Categories Selection -->
                           <div>
                            <x-input-label for="categories" :value="__('Categories')" class="text-lg font-semibold text-gray-800"/>
                            <div class="space-y-2">
                                @foreach($categories as $category)
                                    <div class="flex items-center">
                                        <input 
                                            type="checkbox" 
                                            id="category_{{ $category->id }}" 
                                            name="selectedCategories[]" 
                                            value="{{ $category->id }}" 
                                            class="mr-2"
                                            {{ in_array($category->id, $user->categories->pluck('id')->toArray()) ? 'checked' : '' }}
                                        />
                                        <label for="category_{{ $category->id }}" class="text-gray-800">{{ $category->name }}</label>
                                    </div>
                                @endforeach
                            </div>
                            <x-input-error :messages="$errors->get('selectedCategories')" class="mt-2 text-sm text-red-500" />
                        </div>
                                    </div>
                </div>

                <div class="flex items-center justify-center mt-8">
                    <x-primary-button class="bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 focus:outline-none text-white font-semibold rounded-lg px-6 py-3 shadow-md transition duration-300 ease-in-out transform hover:scale-105">
                        {{ __('Update User') }}
                    </x-primary-button>
                </div>
            </div>
        </form>
    </div>

    <!-- Toggle Owner Fields Script -->
    <script>
        function toggleOwnerFields() {
            const role = document.getElementById('role').value;
            const ownerFields = document.getElementById('ownerFields');
            const designationFields = document.getElementById('designationFields');
            const authenticationToggle = document.querySelector('.authentication-toggle-section');
            const identifierLabel = document.getElementById('identifier_label');
            const identifierHelp = document.getElementById('identifier_help');
            const identifierInput = document.getElementById('identifier');

            // Toggle Owner Fields for Animal Owners
            if (role == '1') {
                ownerFields.classList.remove('hidden');
                // Show authentication toggle for owners
                if (authenticationToggle) {
                    authenticationToggle.classList.remove('hidden');
                }
            } else {
                ownerFields.classList.add('hidden');
                // Hide authentication toggle for non-owners
                if (authenticationToggle) {
                    authenticationToggle.classList.add('hidden');
                }
                // Force email authentication for non-owners
                if (identifierLabel) identifierLabel.textContent = 'Email Address';
                if (identifierHelp) identifierHelp.textContent = 'Enter valid email address';
                if (identifierInput) {
                    identifierInput.type = 'email';
                    // Don't clear the value here to preserve existing data
                }
                // Set the hidden input value to 1 (email) for non-owners
                document.querySelector('input[name="is_email_field"]').value = '1';
            }

            // Toggle Designation Fields for Veterinarians
            if (role == '2') {
                designationFields.classList.remove('hidden');
            } else {
                designationFields.classList.add('hidden');
            }
        }

        // Make sure the toggle is handled on page load
        document.addEventListener('DOMContentLoaded', function() {
            toggleOwnerFields();
            
            // Add event listener for role changes
            document.getElementById('role').addEventListener('change', function() {
                toggleOwnerFields();
            });
        });

        function toggleAuthenticationMethod(checkbox) {
            const role = document.getElementById('role').value;
            
            // Only allow toggle for owners
            if (role != '1') {
                checkbox.checked = true;
                return;
            }

            const identifierInput = document.getElementById('identifier');
            const identifierLabel = document.getElementById('identifier_label');
            const identifierHelp = document.getElementById('identifier_help');
            const isEmail = checkbox.checked;

            // Update input type
            identifierInput.type = isEmail ? 'email' : 'text';
            
            // Update label
            identifierLabel.textContent = isEmail ? 'Email Address' : 'Username';
            
            // Update help text
            identifierHelp.textContent = isEmail 
                ? 'Enter valid email address' 
                : 'Username must be at least 5 characters and can only contain letters, numbers, underscore and dot';
            
            // Clear the input value when switching types
            identifierInput.value = '';

            // Update the hidden input value
            checkbox.value = isEmail ? '1' : '0';
        }

        function handleRoleChange() {
            const role = document.getElementById('role').value;
            const authenticationSection = document.querySelector('.authentication-toggle');
            const identifierInput = document.getElementById('identifier');
            const identifierLabel = document.getElementById('identifier_label');
            const identifierHelp = document.getElementById('identifier_help');

            if (role != '1') {
                // For non-owners, force email authentication
                if (authenticationSection) {
                    authenticationSection.classList.add('hidden');
                }
                identifierInput.type = 'email';
                identifierLabel.textContent = 'Email Address';
                identifierHelp.textContent = 'Enter valid email address';
                document.querySelector('input[name="is_email_field"]').value = '1';
            } else {
                // For owners, show authentication toggle
                if (authenticationSection) {
                    authenticationSection.classList.remove('hidden');
                }
            }
        }
    </script>
</x-app-layout>
