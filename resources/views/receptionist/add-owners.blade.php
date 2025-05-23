<x-app-layout>
    <!-- Loading Overlay -->
    <div id="loadingOverlay" class="fixed inset-0 bg-gray-900/70 backdrop-blur-sm flex items-center justify-center z-50 hidden">
        <div class="bg-white p-8 rounded-2xl shadow-2xl max-w-sm w-full mx-4">
            <div class="flex flex-col items-center">
                <!-- Loading Animation -->
                <div class="flex items-center justify-center mb-6">
                    <div class="relative">
                        <!-- Outer spinning circle -->
                        <div class="w-16 h-16 border-4 border-green-100 border-t-green-600 rounded-full animate-spin"></div>
                        <!-- Inner spinning circle -->
                        <div class="w-12 h-12 border-4 border-green-100 border-t-green-600 rounded-full animate-spin absolute top-2 left-2"></div>
                        <!-- Center dot -->
                        <div class="w-4 h-4 bg-green-600 rounded-full absolute top-6 left-6"></div>
                    </div>
                </div>
                
                <div class="text-center">
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Registering Owner</h3>
                    <p class="text-gray-600">Please wait while we process your request...</p>
                </div>

                <!-- Progress Bar -->
                <div class="w-full bg-gray-200 rounded-full h-2.5 mt-6 overflow-hidden">
                    <div id="progressBar" class="bg-green-600 h-2.5 rounded-full w-0 transition-all duration-300"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="mb-9 "></div>
    <div class="max-w-4xl mx-auto p-8 bg-white rounded-lg shadow-lg relative">
        @if(session('credentials'))
        <div class="fixed inset-0 bg-gray-900/70 backdrop-blur-sm flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl p-8 max-w-md w-full" id="credentials-printable">
                <div class="text-center mb-6">
                    <h3 class="text-2xl font-bold text-gray-800">User Credentials</h3>
                    <p class="text-sm text-gray-600">Please provide these credentials to the user</p>
                </div>
                
                <div class="border border-gray-200 rounded-lg p-6 bg-gray-50 mb-6">
                    <div class="mb-4">
                        <p class="text-sm text-gray-500">{{ $is_email_field ? 'Email' : 'Username' }}</p>
                        <p class="text-lg font-semibold text-gray-800">{{ $userIdentifier }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Password</p>
                        <p class="text-lg font-semibold text-gray-800">{{ $generatedPassword }}</p>
                    </div>
                </div>
                
                <div class="text-center text-sm text-gray-500 mb-6">
                    <p>User must change their password after first login</p>
                </div>
                
                <div class="flex space-x-3 justify-center">
                    <button wire:click="printCredentials" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        Print
                    </button>
                    <button wire:click="downloadCredentialsPDF" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Download PDF
                    </button>
                    <button wire:click="closeCredentialsModal" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium py-2 px-4 rounded-lg">
                        Close
                    </button>
                </div>
            </div>
        </div>
        @endif
    
        @if (session('error'))
        <div class="text-center mb-6">
            <p class="text-lg text-red-500">{{ session('error') }}</p>
        </div>
        @endif
        
            <!-- Title with Gradient Background -->
    <div class=" mb-6 bg-gradient-to-r from-green-800 to-green-600 p-6 rounded-t-lg -mt-8 -mx-8 shadow-md">
        <h2 class="text-3xl font-bold text-white">Owner Registration Form</h2>
        <p class="text-lg text-blue-100">Add a new animal owner to the system</p>
    </div>

    
        <form method="POST" action="{{ route('reg-owner.submit') }}">
            @csrf
    
            <!-- User Basic Info Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Complete Name -->
                <div class="mb-4">
                    <label for="complete_name" class="text-gray-700 font-medium">Full Name</label>
                    <input type="text" name="complete_name" id="complete_name" 
                           value="{{ old('complete_name') }}"
                           class="block mt-1 w-full border border-gray-300 rounded-lg p-3 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           required autofocus>
                    @error('complete_name')
                        <span class="mt-2 text-sm text-red-500">{{ $message }}</span>
                    @enderror
                </div>
    
                <!-- Role Selection -->
                <div class="mb-4">
                    <label for="role" class="text-gray-700 font-medium">Role</label>
                    <select name="role" id="role" class="block mt-1 w-full border border-gray-300 rounded-lg p-3 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        <option value="1" {{ old('role', 1) == 1 ? 'selected' : '' }}>Animal Owner</option>
                    </select>
                    @error('role')
                        <span class="mt-2 text-sm text-red-500">{{ $message }}</span>
                    @enderror
                </div>
    
                <!-- Contact Number -->
                <div class="mb-4">
                    <label for="contact_no" class="text-gray-700 font-medium">Contact Number</label>
                    <div class="flex">
                        <span class="inline-flex items-center px-3 text-gray-500 bg-gray-100 border border-r-0 border-gray-300 rounded-l-lg">+63</span>
                        <input type="text" name="contact_no" id="contact_no" 
                               value="{{ old('contact_no') }}"
                               class="block mt-0 w-full border border-gray-300 rounded-r-lg p-3 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="9XXXXXXXXX">
                    </div>
                    @error('contact_no')
                        <span class="mt-2 text-sm text-red-500">{{ $message }}</span>
                    @enderror
                </div>
    
                <!-- Gender -->
                <div class="mb-4">
                    <label class="text-gray-700 font-medium">Gender</label>
                    <div class="mt-1 flex space-x-4">
                        <label class="inline-flex items-center">
                            <input type="radio" name="gender" value="Male" 
                                   {{ old('gender') == 'Male' ? 'checked' : '' }} class="text-blue-600 focus:ring-blue-500 h-5 w-5">
                            <span class="ml-2 text-gray-700">Male</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="gender" value="Female" 
                                   {{ old('gender') == 'Female' ? 'checked' : '' }} class="text-blue-600 focus:ring-blue-500 h-5 w-5">
                            <span class="ml-2 text-gray-700">Female</span>
                        </label>
                    </div>
                    @error('gender')
                        <span class="mt-2 text-sm text-red-500">{{ $message }}</span>
                    @enderror
                </div>
    
                <!-- Birth Date -->
                <div>
                    <label for="birth_date" class="text-gray-700 font-medium">Birth Date</label>
                    <input type="date" name="birth_date" id="birth_date" 
                           value="{{ old('birth_date') }}"
                           class="block mt-1 w-full border border-gray-300 rounded-lg p-3 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('birth_date')
                        <span class="mt-2 text-sm text-red-500">{{ $message }}</span>
                    @enderror
                </div>
            </div>
    
            <!-- Address Section -->
            <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow duration-300 mb-8">
                <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center border-b pb-2">
                    Address Information
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Barangay Selection -->
                    <div>
                        <label for="barangay_id" class="text-gray-700 font-medium">Barangay</label>
                        <select name="barangay_id" id="barangay_id" class="block mt-1 w-full border border-gray-300 rounded-lg p-3 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                            <option value="">Select Barangay</option>
                            @foreach($barangays as $barangay)
                                <option value="{{ $barangay->id }}" {{ old('barangay_id') == $barangay->id ? 'selected' : '' }}>{{ $barangay->barangay_name }}</option>
                            @endforeach
                        </select>
                        @error('barangay_id')
                            <span class="mt-2 text-sm text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
    
                    <!-- Street Name -->
                    <div>
                        <label for="street" class="text-gray-700 font-medium">Street Name</label>
                        <input type="text" name="street" id="street" 
                               value="{{ old('street') }}"
                               class="block mt-1 w-full border border-gray-300 rounded-lg p-3 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        @error('street')
                            <span class="mt-2 text-sm text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
    
            <!-- Owner-Specific Fields -->
            <div id="owner-section" style="{{ old('role', 1) == 1 ? '' : 'display: none;' }}" class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow duration-300 mb-8">
                <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center border-b pb-2">
                    Owner Details
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Civil Status -->
                    <div>
                        <label for="civil_status" class="text-gray-700 font-medium">Civil Status</label>
                        <select name="civil_status" id="civil_status" class="block mt-1 w-full border border-gray-300 rounded-lg p-3 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select Civil Status</option>
                            <option value="Married" {{ old('civil_status') == 'Married' ? 'selected' : '' }}>Married</option>
                            <option value="Separated" {{ old('civil_status') == 'Separated' ? 'selected' : '' }}>Separated</option>
                            <option value="Single" {{ old('civil_status') == 'Single' ? 'selected' : '' }}>Single</option>
                            <option value="Widow" {{ old('civil_status') == 'Widow' ? 'selected' : '' }}>Widow</option>
                        </select>
                        @error('civil_status')
                            <span class="mt-2 text-sm text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
    
                    <!-- Categories Selection -->
                    <div class="mt-4">
                        <label class="text-gray-700 font-medium">Pet Categories</label>
                        <div class="mt-2 p-4 border border-gray-300 rounded-lg bg-white shadow-sm">
                            <!-- Special Categories (0, 8, 9) - Radio Buttons -->
                            <div class="mb-3 pb-3 border-b border-gray-200">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Select one of these categories:</label>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                    @foreach($categories as $category)
                                        @if(in_array($category->id, [0, 8, 9]))
                                            <div class="flex items-center">
                                                <input type="radio" 
                                                      name="selectedCategories[]" 
                                                      id="category_radio_{{ $category->id }}" 
                                                      value="{{ $category->id }}" 
                                                      {{ in_array($category->id, old('selectedCategories', [])) ? 'checked' : '' }}
                                                      class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                                <label for="category_radio_{{ $category->id }}" class="ml-2 text-gray-700">{{ $category->name }}</label>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                                    
                            <!-- Regular Categories (Checkboxes) -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Select additional categories:</label>
                                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                    @foreach($categories as $category)
                                        @if(!in_array($category->id, [0, 8, 9]))
                                            <div class="flex items-center" 
                                                 x-data="{}"
                                                 x-show="!(['4', '6'].includes('{{ $category->id }}') && document.querySelector('input[name=gender][value=Male]').checked)">
                                                <input type="checkbox" 
                                                      name="selectedCategories[]" 
                                                      id="category_{{ $category->id }}" 
                                                      value="{{ $category->id }}" 
                                                      {{ in_array($category->id, old('selectedCategories', [])) ? 'checked' : '' }}
                                                      class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                                <label for="category_{{ $category->id }}" class="ml-2 text-gray-700">{{ $category->name }}</label>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @error('selectedCategories')
                            <span class="mt-2 text-sm text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
    
            <!-- Authentication Section -->
            <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow duration-300">
                <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center border-b pb-2">
                    Authentication
                </h3>

                <!-- Email/Username Toggle -->
                <div id="auth-toggle" class="mb-6">
                    <div id="auth-toggle" class="mb-6">
                        <div class="p-3 bg-gray-50 rounded-lg border border-gray-200">
                            <div class="flex items-center justify-between">
                                <span class="{{ old('is_email_field', true) ? 'font-semibold text-blue-600' : 'text-gray-500' }}">Email</span>
                                <button type="button" onclick="toggleAuthMethod()" class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 {{ old('is_email_field', true) ? 'bg-blue-600' : 'bg-gray-200' }}">
                                    <span class="sr-only">Toggle email/username</span>
                                    <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform {{ old('is_email_field', true) ? 'translate-x-6' : 'translate-x-1' }}"></span>
                                </button>
                                <span class="{{ !old('is_email_field', true) ? 'font-semibold text-blue-600' : 'text-gray-500' }}">Username</span>
                            </div>
                        </div>
                        <input type="hidden" name="is_email_field" id="is_email_field" value="{{ old('is_email_field', 1) }}">
                    </div>
                </div>

                <!-- Email Field -->
                <div id="email-field" style="{{ old('is_email_field', true) ? '' : 'display: none;' }}">
                    <label for="email" class="text-gray-700 font-medium">Email</label>
                    <div class="relative">
                        <input type="email" 
                            name="email" 
                            id="email" 
                            value="{{ old('email') }}"
                            class="block mt-1 w-full pl-10 border border-gray-300 rounded-lg p-3 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="email@example.com"
                            {{ old('is_email_field', true) ? 'required' : '' }}>
                    </div>
                    @error('email')
                        <span class="mt-2 text-sm text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Username Field -->
                <div id="username-field" style="{{ old('is_email_field', true) ? 'display: none;' : '' }}">
                    <label for="username" class="text-gray-700 font-medium">Username</label>
                    <input type="text" 
                        name="username" 
                        id="username" 
                        value="{{ old('username') }}"
                        class="block mt-1 w-full border border-gray-300 rounded-lg p-3 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="username"
                        {{ !old('is_email_field', true) ? 'required' : '' }}>
                    @error('username')
                        <span class="mt-2 text-sm text-red-500">{{ $message }}</span>
                    @enderror
                </div>
            </div>
    
            <div class="flex items-center justify-center space-x-9 mt-8">
                <button type="button" onclick="window.history.back()" class="bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg px-8 py-3 shadow-md transition duration-300 ease-in-out transform hover:scale-105">
                    Cancel
                </button>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg px-8 py-3 shadow-md transition duration-300 ease-in-out transform hover:scale-105">
                    Add User
                </button>
            </div>
        </form>
    </div>
    
    <script>
function toggleAuthMethod() {
    const emailField = document.getElementById('email-field');
    const usernameField = document.getElementById('username-field');
    const emailInput = document.getElementById('email');
    const usernameInput = document.getElementById('username');
    const toggleButton = document.querySelector('#auth-toggle button');
    const hiddenInput = document.getElementById('is_email_field');

    const isEmail = emailField.style.display !== 'none';
    
    // Toggle visibility
    emailField.style.display = isEmail ? 'none' : 'block';
    usernameField.style.display = isEmail ? 'block' : 'none';
    
    // Toggle required attributes
    emailInput.required = !isEmail;
    usernameInput.required = isEmail;
    
    // Clear values when switching
    if(isEmail) {
        emailInput.value = '';
    } else {
        usernameInput.value = '';
    }
    
    // Update toggle styles
    toggleButton.classList.toggle('bg-blue-600');
    toggleButton.classList.toggle('bg-gray-200');
    toggleButton.querySelector('span:last-child').style.transform = isEmail 
        ? 'translate-x-1' 
        : 'translate-x-6';
    
    // Update hidden field value
    hiddenInput.value = isEmail ? 0 : 1;
}
    
        // Handle role change visibility
        document.addEventListener('DOMContentLoaded', function() {
            const roleSelect = document.querySelector('select[name="role"]');
            const ownerSection = document.querySelector('#owner-section');
    
            function toggleOwnerFields() {
                ownerSection.style.display = roleSelect.value === '1' ? 'block' : 'none';
            }
            
            roleSelect.addEventListener('change', toggleOwnerFields);
            toggleOwnerFields(); // Initial check
        });

    // Handle gender change to show/hide categories 4 and 6
    document.addEventListener('DOMContentLoaded', function() {
        const maleRadio = document.querySelector('input[name=gender][value=Male]');
        const femaleRadio = document.querySelector('input[name=gender][value=Female]');
        const category4 = document.querySelector('input[name="selectedCategories[]"][value="4"]')?.closest('div');
        const category6 = document.querySelector('input[name="selectedCategories[]"][value="6"]')?.closest('div');
        
        function toggleCategories() {
            if (category4 && category6) {
                if (maleRadio.checked) {
                    category4.style.display = 'none';
                    category6.style.display = 'none';
                    // Uncheck if hidden
                    document.querySelector('input[name="selectedCategories[]"][value="4"]').checked = false;
                    document.querySelector('input[name="selectedCategories[]"][value="6"]').checked = false;
                } else {
                    category4.style.display = '';
                    category6.style.display = '';
                }
            }
        }
        
        if (maleRadio && femaleRadio) {
            maleRadio.addEventListener('change', toggleCategories);
            femaleRadio.addEventListener('change', toggleCategories);
            toggleCategories(); // Initial check
        }
    });

    // Add this new code at the bottom
    document.querySelector('form').addEventListener('submit', function(e) {
        // Show loading overlay
        const loadingOverlay = document.getElementById('loadingOverlay');
        const progressBar = document.getElementById('progressBar');
        loadingOverlay.classList.remove('hidden');
        
        // Animate progress bar
        let width = 0;
        const interval = setInterval(() => {
            if (width >= 90) {
                clearInterval(interval);
            } else {
                width += Math.random() * 30;
                if (width > 90) width = 90;
                progressBar.style.width = width + '%';
            }
        }, 500);
    });
    </script>

    <style>
        /* Add these styles for smooth transitions */
        #loadingOverlay {
            transition: opacity 0.3s ease-in-out;
        }

        #loadingOverlay.hidden {
            opacity: 0;
            pointer-events: none;
        }

        #progressBar {
            transition: width 0.5s ease-in-out;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .animate-spin {
            animation: spin 1s linear infinite;
        }
    </style>
    </x-app-layout>