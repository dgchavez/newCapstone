<x-app-layout>
    <div class="bg-gradient-to-b from-green-50 to-white min-h-screen py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Alert Messages -->
            @if (session('success'))
                <div class="mb-6 flex items-center p-4 bg-green-50 border-l-4 border-green-500 rounded-lg shadow-sm animate-fadeIn" role="alert">
                    <i class="fas fa-check-circle text-green-500 mr-3 text-lg"></i>
                    <span class="text-green-800 font-medium">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 flex items-center p-4 bg-red-50 border-l-4 border-red-500 rounded-lg shadow-sm animate-fadeIn" role="alert">
                    <i class="fas fa-exclamation-circle text-red-500 mr-3 text-lg"></i>
                    <span class="text-red-800 font-medium">{{ session('error') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 flex p-4 bg-red-50 border-l-4 border-red-500 rounded-lg shadow-sm animate-fadeIn" role="alert">
                    <i class="fas fa-exclamation-triangle text-red-500 mr-3 text-lg self-start mt-0.5"></i>
                    <div>
                        <p class="text-red-800 font-medium mb-2">Please correct the following errors:</p>
                        <ul class="list-disc pl-5 text-red-700 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <!-- Header Section -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-6">
                <div class="bg-gradient-to-r from-green-600 to-green-400 h-16"></div>
                <div class="px-6 py-5 -mt-1 flex flex-col sm:flex-row justify-between items-start sm:items-center">
                    <h1 class="text-2xl font-bold text-gray-800 flex items-center">
                        <i class="fas fa-user-plus text-blue-500 mr-3"></i>Register New Veterinarian
                    </h1>
                    <a href="{{ route('admin-veterinarians') }}" class="mt-3 sm:mt-0 bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg transition duration-200 flex items-center gap-2">
                        <i class="fas fa-arrow-left text-sm"></i>
                        <span>Back to List</span>
                    </a>
                </div>
            </div>

            <!-- Form Section -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h2 class="text-lg font-medium text-gray-800 flex items-center">
                        <i class="fas fa-user-md text-blue-500 mr-2"></i>
                        <span>Veterinarian Information</span>
                    </h2>
                </div>
                
                <div class="p-6">
                    <form action="{{ route('veterinarians.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Complete Name -->
                            <div>
                                <label for="complete_name" class="block text-sm font-medium text-gray-700 mb-1">
                                    Complete Name <span class="text-red-500">*</span>
                                </label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-user text-gray-400"></i>
                                    </div>
                                    <input type="text" 
                                           class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm" 
                                           id="complete_name" 
                                           name="complete_name" 
                                           value="{{ old('complete_name') }}" 
                                           placeholder="Enter full name"
                                           required>
                                </div>
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                                    Email <span class="text-red-500">*</span>
                                </label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-envelope text-gray-400"></i>
                                    </div>
                                    <input type="email" 
                                           class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email') }}" 
                                           placeholder="email@example.com"
                                           required>
                                </div>
                            </div>
                            
                            <!-- Contact Number -->
                            <div>
                                <label for="contact_no" class="block text-sm font-medium text-gray-700 mb-1">
                                    Contact Number <span class="text-red-500">*</span>
                                </label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-phone-alt text-gray-400"></i>
                                    </div>
                                    <input type="text" 
                                           class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm" 
                                           id="contact_no" 
                                           name="contact_no" 
                                           value="{{ old('contact_no') }}" 
                                           placeholder="Enter contact number"
                                           required>
                                </div>
                            </div>

                            <!-- Gender -->
                            <div>
                                <label for="gender" class="block text-sm font-medium text-gray-700 mb-1">
                                    Gender <span class="text-red-500">*</span>
                                </label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-venus-mars text-gray-400"></i>
                                    </div>
                                    <select class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm appearance-none" 
                                            id="gender" 
                                            name="gender" 
                                            required>
                                        <option value="" disabled {{ old('gender') ? '' : 'selected' }}>Select gender</option>
                                        <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                                        <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Birthdate -->
                            <div>
                                <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-1">
                                    Birthdate <span class="text-red-500">*</span>
                                </label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-calendar-alt text-gray-400"></i>
                                    </div>
                                    <input type="date" 
                                           class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm" 
                                           id="birth_date" 
                                           name="birth_date" 
                                           value="{{ old('birth_date') }}" 
                                           required>
                                </div>
                            </div>

                            <!-- Designation -->
                            <div>
                                <label for="designation_id" class="block text-sm font-medium text-gray-700 mb-1">
                                    Designation <span class="text-red-500">*</span>
                                </label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-tag text-gray-400"></i>
                                    </div>
                                    <select class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm appearance-none" 
                                            id="designation_id" 
                                            name="designation_id" 
                                            required>
                                        <option value="" disabled {{ old('designation_id') ? '' : 'selected' }}>Select designation</option>
                                        @foreach($designations as $designation)
                                            <option value="{{ $designation->designation_id }}" {{ old('designation_id') == $designation->designation_id ? 'selected' : '' }}>
                                                {{ $designation->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Address Section -->
                        <div class="pt-4 border-t border-gray-200">
                            <h3 class="text-md font-medium text-gray-700 mb-4">Address Information</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Barangay -->
                                <div>
                                    <label for="barangay_id" class="block text-sm font-medium text-gray-700 mb-1">
                                        Barangay <span class="text-red-500">*</span>
                                    </label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-map-marker-alt text-gray-400"></i>
                                        </div>
                                        <select class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm appearance-none" 
                                                id="barangay_id" 
                                                name="barangay_id" 
                                                required>
                                            <option value="" disabled {{ old('barangay_id') ? '' : 'selected' }}>Select barangay</option>
                                            @foreach($barangays as $barangay)
                                                <option value="{{ $barangay->id }}" {{ old('barangay_id') == $barangay->id ? 'selected' : '' }}>
                                                    {{ $barangay->barangay_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                                        </div>
                                    </div>
                                </div>

                                <!-- Street -->
                                <div>
                                    <label for="street" class="block text-sm font-medium text-gray-700 mb-1">
                                        Street Address <span class="text-red-500">*</span>
                                    </label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-road text-gray-400"></i>
                                        </div>
                                        <input type="text" 
                                               class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm" 
                                               id="street" 
                                               name="street" 
                                               value="{{ old('street') }}" 
                                               placeholder="Enter street address"
                                               required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Profile Image -->
                        <div class="pt-4 border-t border-gray-200">
                            <h3 class="text-md font-medium text-gray-700 mb-4">Profile Image</h3>
                            
                            <div class="flex items-center">
                                <div class="flex-shrink-0 w-20 h-20 mr-4 bg-gray-100 rounded-full flex items-center justify-center border border-gray-200 overflow-hidden" id="image-preview">
                                    <i class="fas fa-user-md text-gray-400 text-3xl"></i>
                                </div>
                                
                                <div class="flex-grow">
                                    <label for="profile_image" class="block text-sm font-medium text-gray-700 mb-1">
                                        Upload Image
                                    </label>
                                    <div class="mt-1 flex items-center">
                                        <label class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none">
                                            <span class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm bg-white hover:bg-gray-50">
                                                <i class="fas fa-upload mr-2"></i>
                                                Browse Files
                                            </span>
                                            <input id="profile_image" name="profile_image" type="file" class="sr-only" accept="image/*" onchange="previewImage()">
                                        </label>
                                        <p class="pl-3 text-xs text-gray-500">PNG, JPG, or JPEG up to 2MB</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-6 border-t border-gray-200 flex justify-end">
                            <a href="{{ route('admin-veterinarians') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-3 rounded-lg transition duration-200 mr-4">
                                Cancel
                            </a>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition duration-200 shadow-md flex items-center">
                                <i class="fas fa-save mr-2"></i>
                                Register Veterinarian
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Help Box -->
            <div class="mt-6 bg-blue-50 rounded-lg border border-blue-200 overflow-hidden shadow-md">
                <div class="px-6 py-4 border-b border-blue-100 bg-blue-100">
                    <h3 class="text-lg font-medium text-blue-800 flex items-center">
                        <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                        <span>Registration Guidelines</span>
                    </h3>
                </div>
                <div class="p-6">
                    <p class="text-blue-700 mb-3">Please ensure you provide accurate information when registering a new veterinarian:</p>
                    <ul class="list-disc pl-5 text-blue-700 space-y-1">
                        <li>Complete all required fields marked with an asterisk (*)</li>
                        <li>Use a professional email address that the veterinarian checks regularly</li>
                        <li>Provide a current contact number for emergency situations</li>
                        <li>Upload a clear professional photo for identification purposes</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Image Preview Script -->
    <script>
        function previewImage() {
            const preview = document.getElementById('image-preview');
            const file = document.getElementById('profile_image').files[0];
            const reader = new FileReader();
            
            reader.onloadend = function() {
                preview.innerHTML = `<img src="${reader.result}" class="w-full h-full object-cover">`;
            }
            
            if (file) {
                reader.readAsDataURL(file);
            } else {
                preview.innerHTML = `<i class="fas fa-user-md text-gray-400 text-3xl"></i>`;
            }
        }
    </script>
    
    <!-- Animation for alerts -->
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-1rem); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .animate-fadeIn {
            animation: fadeIn 0.3s ease-out forwards;
        }
    </style>
    
    <!-- Add Font Awesome CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
</x-app-layout>