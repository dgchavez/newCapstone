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
                        <i class="fas fa-user-edit text-blue-500 mr-3"></i>Edit Veterinarian
                    </h1>
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
                    <form action="{{ route('admin-veterinarians.update', $veterinarian->user_id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')

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
                                           value="{{ old('complete_name', $veterinarian->complete_name) }}" 
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
                                           value="{{ old('email', $veterinarian->email) }}" 
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
                                           value="{{ old('contact_no', $veterinarian->contact_no) }}" 
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
                                        <option value="Male" {{ old('gender', $veterinarian->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                                        <option value="Female" {{ old('gender', $veterinarian->gender) == 'Female' ? 'selected' : '' }}>Female</option>
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
                                           value="{{ old('birth_date', $veterinarian->birth_date->format('Y-m-d')) }}" 
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
                                        @foreach($designations as $designation)
                                            <option value="{{ $designation->designation_id }}" {{ old('designation_id', $veterinarian->designation_id) == $designation->designation_id ? 'selected' : '' }}>
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

                        <!-- Profile Image Section -->
                        <div class="pt-4 border-t border-gray-200">
                            <h3 class="text-md font-medium text-gray-700 mb-4">Profile Image</h3>
                            
                            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-6">
                                <!-- Current Image Display -->
                                <div class="flex-shrink-0">
                                    <div class="text-sm font-medium text-gray-700 mb-2">Current Image</div>
                                    <div class="w-32 h-32 rounded-lg border border-gray-300 overflow-hidden bg-gray-100 flex items-center justify-center" id="current-image">
                                        @if($veterinarian->profile_image)
                                            <img src="{{ asset('storage/' . $veterinarian->profile_image) }}" alt="{{ $veterinarian->complete_name }}" class="w-full h-full object-cover">
                                        @else
                                            <i class="fas fa-user-md text-gray-400 text-4xl"></i>
                                        @endif
                                    </div>
                                </div>
                                
                                <!-- New Image Upload -->
                                <div class="flex-grow">
                                    <div class="text-sm font-medium text-gray-700 mb-2">Upload New Image</div>
                                    <div class="mt-1 flex items-start">
                                        <div>
                                            <label class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500">
                                                <span class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm bg-white hover:bg-gray-50">
                                                    <i class="fas fa-upload mr-2"></i>
                                                    Choose File
                                                </span>
                                                <input id="profile_image" name="profile_image" type="file" class="sr-only" accept="image/*" onchange="previewNewImage()">
                                            </label>
                                            <div class="mt-2 text-xs text-gray-500">
                                                <p>Leave empty to keep current image</p>
                                                <p>PNG, JPG, or JPEG up to 2MB</p>
                                            </div>
                                        </div>
                                        
                                        <!-- New Image Preview -->
                                        <div class="ml-4 w-20 h-20 rounded-lg border border-gray-300 overflow-hidden bg-gray-100 flex items-center justify-center" id="new-image-preview">
                                            <i class="fas fa-image text-gray-400 text-xl"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="pt-6 border-t border-gray-200 flex justify-end space-x-4">
                            <a href="{{ route('admin-veterinarians') }}" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition duration-200 flex items-center">
                                <i class="fas fa-times mr-2"></i>
                                Cancel
                            </a>
                            <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200 shadow-md flex items-center">
                                <i class="fas fa-save mr-2"></i>
                                Update Veterinarian
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
                        <span>Editing Guidelines</span>
                    </h3>
                </div>
                <div class="p-6">
                    <p class="text-blue-700 mb-3">When updating veterinarian information:</p>
                    <ul class="list-disc pl-5 text-blue-700 space-y-1">
                        <li>All fields marked with an asterisk (*) are required</li>
                        <li>Ensure contact information is up-to-date for emergency communications</li>
                        <li>You can upload a new profile image or keep the current one</li>
                        <li>Any changes made will be immediately reflected in the system</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Image Preview Script -->
    <script>
        function previewNewImage() {
            const preview = document.getElementById('new-image-preview');
            const file = document.getElementById('profile_image').files[0];
            const reader = new FileReader();
            
            reader.onloadend = function() {
                preview.innerHTML = `<img src="${reader.result}" class="w-full h-full object-cover">`;
            }
            
            if (file) {
                reader.readAsDataURL(file);
            } else {
                preview.innerHTML = `<i class="fas fa-image text-gray-400 text-xl"></i>`;
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