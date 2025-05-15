<x-app-layout>
    <div class="bg-gradient-to-b from-green-50 to-white min-h-screen">
        <div class="max-w-3xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-6">
                <div class="bg-gradient-to-r from-green-600 to-green-400 h-16"></div>
                <div class="px-6 py-5 -mt-1">
                    <h1 class="text-2xl font-bold text-gray-800 flex items-center">
                        <i class="fas fa-edit text-blue-500 mr-3"></i>Edit Designation
                    </h1>
                    <p class="text-gray-600 mt-2">Update the details for this designation</p>
                </div>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="p-6">
                    <form action="{{ route('designations.update', $designation) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <!-- Form introduction -->
                        <div class="mb-6 pb-4 border-b border-gray-200">
                            <div class="flex items-center">
                                <div class="h-12 w-12 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center mr-4">
                                    <i class="fas fa-id-badge text-xl"></i>
                                </div>
                                <div>
                                    <h2 class="text-lg font-medium text-gray-800">Designation Details</h2>
                                    <p class="text-sm text-gray-500">Editing: {{ $designation->name }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Name Field -->
                        <div class="mb-6">
                            <label for="name" class="text-sm font-medium text-gray-700 block mb-2">Designation Name</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-tag text-gray-400"></i>
                                </div>
                                <input 
                                    type="text" 
                                    name="name" 
                                    id="name" 
                                    value="{{ old('name', $designation->name) }}"
                                    class="pl-10 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 py-3 @error('name') border-red-500 @enderror" 
                                    required
                                >
                            </div>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Description Field -->
                        <div class="mb-6">
                            <label for="description" class="text-sm font-medium text-gray-700 block mb-2">Description</label>
                            <div class="relative">
                                <div class="absolute top-3 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-align-left text-gray-400"></i>
                                </div>
                                <textarea 
                                    name="description" 
                                    id="description" 
                                    class="pl-10 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 py-3 @error('description') border-red-500 @enderror" 
                                    rows="4"
                                >{{ old('description', $designation->description) }}</textarea>
                            </div>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Form Actions -->
                        <div class="mt-8 flex items-center justify-between">
                            <a href="{{ route('designations.index') }}" class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-arrow-left mr-2"></i>
                                <span>Back to list</span>
                            </a>
                            
                            <div class="flex gap-3">
                                <button 
                                    type="reset" 
                                    onclick="resetForm()"
                                    class="px-5 py-2.5 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition duration-200"
                                >
                                    Reset
                                </button>
                                
                                <button 
                                    type="submit" 
                                    class="px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200 flex items-center gap-2"
                                >
                                    <i class="fas fa-save"></i>
                                    <span>Update Designation</span>
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
    
    <script>
        // Store original values for reset
        const originalName = "{{ $designation->name }}";
        const originalDescription = `{{ $designation->description }}`;
        
        // Function to reset the form to original values
        function resetForm() {
            document.getElementById('name').value = originalName;
            document.getElementById('description').value = originalDescription;
        }
    </script>
</x-app-layout>