<x-app-layout>
    <div class="bg-gradient-to-b from-green-50 to-white min-h-screen">
        <div class="container mx-auto p-4">
            <!-- Header with gradient banner -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6 max-w-2xl mx-auto">
                <div class="bg-gradient-to-r from-green-600 to-green-400 h-16"></div>
                <div class="px-6 py-4 -mt-1">
                    <h1 class="text-xl font-bold text-gray-800 flex items-center">
                        <i class="fas fa-edit text-blue-500 mr-2"></i>Edit Barangay
                    </h1>
                </div>
            </div>
            
            <!-- Form section -->
            <div class="bg-white rounded-lg shadow-md p-6 max-w-2xl mx-auto">
                <form action="{{ route('barangays.update', $barangay) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label for="barangay_name" class="block text-sm font-medium text-gray-700">Barangay Name</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-map-marker-alt text-gray-400"></i>
                            </div>
                            <input 
                                type="text" 
                                name="barangay_name" 
                                id="barangay_name" 
                                value="{{ old('barangay_name', $barangay->barangay_name) }}"
                                class="pl-10 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 py-2"
                                required
                            >
                            @error('barangay_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between mt-6">
                        <a href="{{ route('barangay.load') }}" class="text-blue-600 hover:text-blue-800">
                            <i class="fas fa-arrow-left mr-1"></i> Back
                        </a>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 flex items-center">
                            <i class="fas fa-save mr-1"></i> Update Barangay
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Add Font Awesome CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
</x-app-layout>