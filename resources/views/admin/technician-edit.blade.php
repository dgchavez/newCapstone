<x-app-layout>
    <div class="bg-gradient-to-b from-green-50 to-white min-h-screen">
        <div class="container mx-auto p-4">
            <!-- Header with gradient banner -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6 max-w-2xl mx-auto">
                <div class="bg-gradient-to-r from-green-600 to-green-400 h-16"></div>
                <div class="px-6 py-4 -mt-1">
                    <h1 class="text-xl font-bold text-gray-800 flex items-center">
                        <i class="fas fa-user-edit text-blue-500 mr-2"></i>Edit Technician
                    </h1>
                </div>
            </div>
            
            <!-- Form section -->
            <div class="bg-white rounded-lg shadow-md p-6 max-w-2xl mx-auto">
                <form action="{{ route('veterinary-technicians.update', $technician->technician_id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label for="full_name" class="block text-sm font-medium text-gray-700">Full Name</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-user text-gray-400"></i>
                            </div>
                            <input 
                                type="text" 
                                name="full_name" 
                                id="full_name" 
                                value="{{ old('full_name', $technician->full_name) }}" 
                                class="pl-10 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 py-2" 
                                required
                            >
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="contact_number" class="block text-sm font-medium text-gray-700">Contact Number</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-phone-alt text-gray-400"></i>
                            </div>
                            <input 
                                type="text" 
                                name="contact_number" 
                                id="contact_number" 
                                value="{{ old('contact_number', $technician->contact_number) }}" 
                                class="pl-10 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 py-2" 
                                required
                            >
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-envelope text-gray-400"></i>
                            </div>
                            <input 
                                type="email" 
                                name="email" 
                                id="email" 
                                value="{{ old('email', $technician->email) }}" 
                                class="pl-10 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 py-2" 
                                required
                            >
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between mt-6">
                        <a href="#" onclick="history.back(); return false;" class="text-blue-600 hover:text-blue-800">
                            <i class="fas fa-arrow-left mr-1"></i> Back
                        </a>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 flex items-center">
                            <i class="fas fa-save mr-1"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Add Font Awesome CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
</x-app-layout>