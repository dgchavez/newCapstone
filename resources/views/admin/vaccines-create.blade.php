<x-app-layout>
    <div class="bg-gradient-to-b from-green-50 to-white min-h-screen">
        <div class="max-w-3xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-6">
                <div class="bg-gradient-to-r from-green-600 to-green-400 h-16"></div>
                <div class="px-6 py-5 -mt-1">
                    <h1 class="text-2xl font-bold text-gray-800 flex items-center">
                        <i class="fas fa-plus-circle text-blue-500 mr-3"></i>Create Vaccine
                    </h1>
                    <p class="text-gray-600 mt-2">Add a new vaccine to the system</p>
                </div>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="p-6">
                    <form action="{{ route('vaccines.store') }}" method="POST">
                        @csrf
                        
                        <!-- Form introduction -->
                        <div class="mb-6 pb-4 border-b border-gray-200">
                            <div class="flex items-center">
                                <div class="h-12 w-12 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center mr-4">
                                    <i class="fas fa-syringe text-xl"></i>
                                </div>
                                <div>
                                    <h2 class="text-lg font-medium text-gray-800">Vaccine Information</h2>
                                    <p class="text-sm text-gray-500">Enter the details for the new vaccine</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Vaccine Name Field -->
                        <div class="mb-6">
                            <label for="vaccine_name" class="text-sm font-medium text-gray-700 block mb-2">Vaccine Name</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-syringe text-gray-400"></i>
                                </div>
                                <input 
                                    type="text" 
                                    name="vaccine_name" 
                                    id="vaccine_name" 
                                    value="{{ old('vaccine_name') }}"
                                    class="pl-10 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 py-3 @error('vaccine_name') border-red-500 @enderror" 
                                    placeholder="Enter vaccine name"
                                    required
                                >
                            </div>
                            @error('vaccine_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Description Field -->
                        <div class="mb-6">
                            <label for="description" class="text-sm font-medium text-gray-700 block mb-2">Description</label>
                            <div class="relative">
                                <div class="absolute top-3 left-0 pl-3 flex items-start pointer-events-none">
                                    <i class="fas fa-file-alt text-gray-400"></i>
                                </div>
                                <textarea 
                                    name="description" 
                                    id="description" 
                                    rows="4"
                                    class="pl-10 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 py-3 @error('description') border-red-500 @enderror" 
                                    placeholder="Enter a description"
                                >{{ old('description') }}</textarea>
                            </div>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Form Actions -->
                        <div class="mt-8 flex items-center justify-between">
                            <a href="javascript:history.back()" class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-arrow-left mr-2"></i>
                                <span>Back</span>
                            </a>
                            
                            <div class="flex gap-3">
                                <button 
                                    type="reset" 
                                    class="px-5 py-2.5 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition duration-200"
                                >
                                    Reset
                                </button>
                                
                                <button 
                                    type="submit" 
                                    class="px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200 flex items-center gap-2"
                                >
                                    <i class="fas fa-save"></i>
                                    <span>Save Vaccine</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Add Font Awesome CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
</x-app-layout>