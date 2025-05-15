<x-app-layout>
    <div class="bg-gradient-to-b from-green-50 to-white min-h-screen">
        <div class="max-w-3xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-6">
                <div class="bg-gradient-to-r from-green-600 to-green-400 h-16"></div>
                <div class="px-6 py-5 -mt-1">
                    <h1 class="text-2xl font-bold text-gray-800 flex items-center">
                        <i class="fas fa-edit text-blue-500 mr-3"></i>Edit Species
                    </h1>
                    <p class="text-gray-600 mt-2">Update the details for this species</p>
                </div>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="p-6">
                    <form action="{{ route('species.update', $species) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <!-- Form introduction -->
                        <div class="mb-6 pb-4 border-b border-gray-200">
                            <div class="flex items-center">
                                <div class="h-12 w-12 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center mr-4">
                                    <i class="fas fa-paw text-xl"></i>
                                </div>
                                <div>
                                    <h2 class="text-lg font-medium text-gray-800">Species Information</h2>
                                    <p class="text-sm text-gray-500">Editing: {{ $species->name }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Color Preview -->
                        <div class="mb-6">
                            <label class="text-sm font-medium text-gray-700 block mb-2">Color Preview</label>
                            <div class="flex items-center">
                                <div id="colorPreview" class="h-12 w-12 rounded-full flex items-center justify-center text-white font-bold mr-3">
                                    {{ strtoupper(substr($species->name, 0, 1)) }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    Preview of how this species will appear in the list
                                </div>
                            </div>
                        </div>
                        
                        <!-- Species Name Field -->
                        <div class="mb-6">
                            <label for="name" class="text-sm font-medium text-gray-700 block mb-2">Species Name</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-tag text-gray-400"></i>
                                </div>
                                <input 
                                    type="text" 
                                    name="name" 
                                    id="name" 
                                    value="{{ old('name', $species->name) }}"
                                    class="pl-10 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 py-3 @error('name') border-red-500 @enderror" 
                                    required
                                    oninput="updateColorPreview(this.value)"
                                >
                            </div>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Form Actions -->
                        <div class="mt-8 flex items-center justify-between">
                            <a href="{{ route('species.breed') }}" class="text-blue-600 hover:text-blue-800">
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
                                    <span>Update Species</span>
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
        // Store original value for reset
        let originalName = "{{ $species->name }}";
        
        // Array of color classes to match the same ones used in the index page
        const colors = [
            'bg-blue-500',
            'bg-green-500',
            'bg-purple-500',
            'bg-red-500',
            'bg-yellow-500',
            'bg-indigo-500'
        ];
        
        function updateColorPreview(name) {
            const preview = document.getElementById('colorPreview');
            
            // Clear existing background color classes
            colors.forEach(color => {
                preview.classList.remove(color);
            });
            
            if (name.length > 0) {
                // Use the same color algorithm as the index page
                const hash = hexToNumber(stringToMD5(name).substring(0, 8));
                const colorIndex = hash % colors.length;
                
                // Add the new color class
                preview.classList.add(colors[colorIndex]);
                
                // Update the letter
                preview.innerText = name.charAt(0).toUpperCase();
            } else {
                // Default if no name
                preview.classList.add('bg-blue-500');
                preview.innerText = 'A';
            }
        }
        
        // Function to reset the form to original values
        function resetForm() {
            document.getElementById('name').value = originalName;
            updateColorPreview(originalName);
        }
        
        // Simple MD5 hash simulation - not the actual MD5 but good enough for preview
        function stringToMD5(str) {
            let hash = 0;
            for (let i = 0; i < str.length; i++) {
                const char = str.charCodeAt(i);
                hash = ((hash << 5) - hash) + char;
                hash = hash & hash; // Convert to 32bit integer
            }
            // Convert to hex string
            return Math.abs(hash).toString(16).padStart(8, '0');
        }
        
        // Convert hex to number
        function hexToNumber(hex) {
            return parseInt(hex, 16);
        }
        
        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateColorPreview(originalName);
        });
    </script>
</x-app-layout>