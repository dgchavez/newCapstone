<!-- resources/views/receptionist/edit-barangay.blade.php -->
<x-app-layout>
    <!-- Page Header -->
    <div class="bg-gradient-to-r from-green-800 to-green-600 shadow-xl">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 relative">
            <div class="absolute inset-0 opacity-10">
                <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                    <path d="M0,0 L100,0 L100,100 L0,100 Z" fill="url(#pet-pattern)" />
                </svg>
                <defs>
                    <pattern id="pet-pattern" width="10" height="10" patternUnits="userSpaceOnUse">
                        <path d="M5,2 C7,2 8,3 8,5 C8,7 7,8 5,8 C3,8 2,7 2,5 C2,3 3,2 5,2 Z" fill="currentColor" />
                    </pattern>
                </defs>
            </div>
            <div class="relative z-10 flex flex-col md:flex-row justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-white flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit Barangay
                    </h1>
                    <p class="text-blue-100 mt-1">Update barangay information</p>
                </div>
                <div class="mt-4 md:mt-0">
                    <a href="{{ route('newbarangay.load') }}" 
                       class="inline-flex items-center px-4 py-2 bg-white text-green-700 rounded-lg shadow-md hover:bg-green-50 transition-all duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="max-w-3xl mx-auto">
            <div class="bg-white rounded-xl shadow-lg overflow-hidden border-t-4 border-green-500">
                <form action="{{ route('newbarangay.update', $barangay->id) }}" method="POST" class="p-6">
                    @csrf
                    @method('PUT')

                    <div class="space-y-6">
                        <!-- Barangay Name -->
                        <div>
                            <label for="barangay_name" class="block text-sm font-medium text-gray-700">
                                Barangay Name
                            </label>
                            <div class="mt-1">
                                <input type="text" 
                                       name="barangay_name" 
                                       id="barangay_name" 
                                       value="{{ old('barangay_name', $barangay->barangay_name) }}"
                                       class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md @error('barangay_name') border-red-300 @enderror"
                                       required>
                            </div>
                            @error('barangay_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end">
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Update Barangay
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>