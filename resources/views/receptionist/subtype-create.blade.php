
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Create Transaction Subtype
                    </h1>
                    <p class="text-blue-100 mt-2">Enter the details for a new transaction subtype</p>
                </div>
            </div>

            <!-- Form with enhanced styling -->
            <form action="{{ route('rec-subtypes.store') }}" method="POST" class="space-y-6">
                @csrf
                
                <!-- Transaction Type -->
                <div>
                    <label for="transaction_type_id" class="block text-sm font-medium text-gray-700 mb-1">Transaction Type</label>
                    <div class="relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                        </div>
                        <select 
                            name="transaction_type_id" 
                            id="transaction_type_id" 
                            class="pl-10 block w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500"
                            required
                        >
                            <option value="">Select Transaction Type</option>
                            @foreach($transactionTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->type_name }}</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
                            </svg>
                        </div>
                    </div>
                    @error('transaction_type_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Subtype Name -->
                <div>
                    <label for="subtype_name" class="block text-sm font-medium text-gray-700 mb-1">Subtype Name</label>
                    <div class="relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                        </div>
                        <input
                            type="text"
                            name="subtype_name"
                            id="subtype_name"
                            class="pl-10 block w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500"
                            required
                            placeholder="Enter subtype name"
                            value="{{ old('subtype_name') }}"
                        >
                    </div>
                    @error('subtype_name')
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
                        Save Subtype
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>