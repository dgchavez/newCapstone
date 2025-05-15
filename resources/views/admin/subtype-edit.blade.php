<x-app-layout>
    <div class="bg-gradient-to-b from-green-50 to-white min-h-screen">
        <div class="max-w-3xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-6">
                <div class="bg-gradient-to-r from-green-600 to-green-400 h-16"></div>
                <div class="px-6 py-5 -mt-1">
                    <h1 class="text-2xl font-bold text-gray-800 flex items-center">
                        <i class="fas fa-edit text-blue-500 mr-3"></i>Edit Transaction Subtype
                    </h1>
                    <p class="text-gray-600 mt-2">Update the details for this transaction subtype</p>
                </div>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="p-6">
                    <form action="{{ route('transaction-subtypes.update', $transactionSubtype) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <!-- Form introduction -->
                        <div class="mb-6 pb-4 border-b border-gray-200">
                            <div class="flex items-center">
                                <div class="h-12 w-12 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center mr-4">
                                    <i class="fas fa-clipboard-list text-xl"></i>
                                </div>
                                <div>
                                    <h2 class="text-lg font-medium text-gray-800">Subtype Information</h2>
                                    <p class="text-sm text-gray-500">Editing: {{ $transactionSubtype->subtype_name }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Transaction Type Field -->
                        <div class="mb-6">
                            <label for="transaction_type_id" class="text-sm font-medium text-gray-700 block mb-2">Transaction Type</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-list-alt text-gray-400"></i>
                                </div>
                                <select 
                                    name="transaction_type_id" 
                                    id="transaction_type_id" 
                                    class="pl-10 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 py-3 @error('transaction_type_id') border-red-500 @enderror" 
                                    required
                                    onchange="updateColorPreview()"
                                >
                                    @foreach($transactionTypes as $type)
                                        <option 
                                            value="{{ $type->id }}" 
                                            {{ old('transaction_type_id', $transactionSubtype->transaction_type_id) == $type->id ? 'selected' : '' }}
                                            data-name="{{ $type->type_name }}"
                                        >
                                            {{ $type->type_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('transaction_type_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Color Preview -->
                        <div class="mb-6">
                            <label class="text-sm font-medium text-gray-700 block mb-2">Transaction Type Color</label>
                            <div class="flex items-center">
                                <span id="colorPreview" class="px-3 py-1 text-sm inline-flex items-center rounded-full text-white mr-3">
                                    <!-- Will be filled by JavaScript -->
                                </span>
                                <div id="typeName" class="text-sm text-gray-500">
                                    <!-- Will be filled by JavaScript -->
                                </div>
                            </div>
                        </div>
                        
                        <!-- Subtype Name Field -->
                        <div class="mb-6">
                            <label for="subtype_name" class="text-sm font-medium text-gray-700 block mb-2">Subtype Name</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-tag text-gray-400"></i>
                                </div>
                                <input 
                                    type="text" 
                                    name="subtype_name" 
                                    id="subtype_name" 
                                    value="{{ old('subtype_name', $transactionSubtype->subtype_name) }}"
                                    class="pl-10 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 py-3 @error('subtype_name') border-red-500 @enderror" 
                                    placeholder="Enter subtype name"
                                    required
                                >
                            </div>
                            @error('subtype_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Form Actions -->
                        <div class="mt-8 flex items-center justify-between">
                            <a href="{{ route('transaction-subtypes.index') }}" class="text-blue-600 hover:text-blue-800">
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
                                    <span>Update Transaction Subtype</span>
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
        const originalName = "{{ $transactionSubtype->subtype_name }}";
        const originalTypeId = "{{ $transactionSubtype->transaction_type_id }}";
        
        // Array of color classes to match the same ones used in the index page
        const colors = [
            'bg-blue-500',
            'bg-green-500',
            'bg-purple-500',
            'bg-red-500',
            'bg-yellow-500',
            'bg-indigo-500'
        ];
        
        function updateColorPreview() {
            const typeSelect = document.getElementById('transaction_type_id');
            const preview = document.getElementById('colorPreview');
            const typeNameElement = document.getElementById('typeName');
            
            // Clear existing background color classes
            colors.forEach(color => {
                preview.classList.remove(color);
            });
            
            if (typeSelect.value) {
                const selectedOption = typeSelect.options[typeSelect.selectedIndex];
                const typeName = selectedOption.getAttribute('data-name');
                
                // Use the same color algorithm as the index page
                const hash = hexToNumber(stringToMD5(typeName).substring(0, 8));
                const colorIndex = hash % colors.length;
                
                // Add the new color class
                preview.classList.add(colors[colorIndex]);
                
                // Update preview text and type name
                preview.innerText = typeName;
                typeNameElement.innerText = 'Selected: ' + typeName;
            } else {
                // Default if no type selected (shouldn't happen in edit form)
                preview.classList.add('bg-gray-300');
                preview.innerText = 'No type selected';
                typeNameElement.innerText = 'Please select a transaction type';
            }
        }
        
        // Function to reset the form to original values
        function resetForm() {
            document.getElementById('subtype_name').value = originalName;
            document.getElementById('transaction_type_id').value = originalTypeId;
            updateColorPreview();
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
            updateColorPreview();
        });
    </script>
</x-app-layout>