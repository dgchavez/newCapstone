
<x-app-layout>
    <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
      
        <!-- Main Card with Form -->
        <div class="bg-white shadow-xl rounded-xl p-8 border-t-4 border-green-500">
            <!-- Header inside container with gradient background -->
            <div class="bg-gradient-to-r from-green-800 to-green-600 rounded-xl shadow-md p-6 -mt-8 -mx-8 mb-8 relative overflow-hidden">
                <div class="absolute inset-0 opacity-10">
                    <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                        <path d="M0,0 L100,0 L100,100 L0,100 Z" fill="url(#pattern)" />
                    </svg>
                    <defs>
                        <pattern id="pattern" width="10" height="10" patternUnits="userSpaceOnUse">
                            <path d="M5,2 C7,2 8,3 8,5 C8,7 7,8 5,8 C3,8 2,7 2,5 C2,3 3,2 5,2 Z" fill="currentColor" />
                        </pattern>
                    </defs>
                </div>
                <div class="relative z-10">
                    <h1 class="text-2xl font-bold text-white flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit Designation
                    </h1>
                    <p class="text-blue-100 mt-2">Update details for "{{ $designation->name }}"</p>
                </div>
            </div>

            <!-- Current Details Card -->
            <div class="mb-6 bg-green-50 rounded-lg p-4 border border-green-100">
                <div class="flex items-center">
                    <div class="h-12 w-12 rounded-full bg-green-100 flex items-center justify-center text-green-800 font-bold mr-4">
                        {{ substr($designation->name, 0, 1) }}
                    </div>
                    <div>
                        <h3 class="text-md font-medium text-gray-800">Current Information</h3>
                        <p class="text-sm text-gray-600">
                            You are editing the "{{ $designation->name }}" designation.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Form with enhanced styling -->
            <form action="{{ route('recdesignations.update', $designation->designation_id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                
                <!-- Designation Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Designation Name</label>
                    <div class="relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <input
                            type="text"
                            name="name"
                            id="name"
                            class="pl-10 block w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500"
                            required
                            placeholder="Enter designation name (e.g., Manager, Supervisor)"
                            value="{{ old('name', $designation->name) }}"
                        >
                    </div>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <div class="relative rounded-md shadow-sm">
                        <div class="absolute top-3 left-3 flex items-start pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                            </svg>
                        </div>
                        <textarea
                            name="description"
                            id="description"
                            class="pl-10 block w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500"
                            rows="4"
                            placeholder="Enter a description of the job designation and its responsibilities"
                        >{{ old('description', $designation->description) }}</textarea>
                    </div>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Buttons with space between -->
                <div class="flex items-center justify-between space-x-4 pt-4 mt-6 border-t border-gray-200">
                    <button
                        type="button"
                        onclick="window.history.back()"
                        class="inline-flex items-center px-5 py-3 bg-red-500 text-white font-medium rounded-lg hover:bg-red-700 transition-all duration-300 shadow-md">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                        </svg>
                        Cancel
                    </button>
                    
                    <button
                        type="submit"
                        class="inline-flex items-center px-5 py-3 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-all duration-300 shadow-md">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Update Designation
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>