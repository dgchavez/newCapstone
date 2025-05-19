<x-app-layout>
    <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        
        <!-- Main Card with Form -->
        <div class="bg-white shadow-xl rounded-xl p-8 border-t-4 border-green-500">
            <!-- Header inside container with gradient background -->
            <div class="bg-gradient-to-r from-green-800 to-green-600 rounded-xl shadow-md p-6 -mt-8 -mx-8 mb-8 relative overflow-hidden">
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
                <div class="relative z-10">
                    <h1 class="text-2xl font-bold text-white flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Add a Transaction
                    </h1>
                    <p class="text-green-100 mt-2">Owner: {{ $owner->user->complete_name }}</p>
                </div>
            </div>

            <!-- Display Success Message -->
            @if(session('success'))
                <div class="alert alert-success mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-md">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Transaction Form -->
            <form action="{{ route('owner.addTransaction', $owner_id) }}" method="POST" class="space-y-6">
                @csrf

                <!-- Animal Selection -->
                <div>
                    <label for="animal_id" class="block text-sm font-medium text-gray-700 mb-1">Select Animal</label>
                    <div class="relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <select 
                            name="animal_id" 
                            id="animal_id" 
                            class="pl-10 block w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500"
                        >
                            <option value="">Select Animal</option>
                            @foreach($animals as $animal)
                                <option value="{{ $animal->animal_id }}">
                                    {{ $animal->name }} - 
                                    {{ $animal->species ? $animal->species->name : 'Species not specified' }} - 
                                    {{ $animal->breed ? $animal->breed->name : 'Breed not specified' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @error('animal_id')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Transaction Type -->
                <div>
                    <label for="transaction_type_id" class="block text-sm font-medium text-gray-700 mb-1">Select Transaction</label>
                    <div class="relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <select 
                            name="transaction_type_id" 
                            id="transaction_type_id" 
                            class="pl-10 block w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500"
                        >
                            <option value="">Select Transaction</option>
                            @foreach($transactionTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->type_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('transaction_type_id')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Transaction Subtype -->
                <div>
                    <label for="transaction_subtype_id" class="block text-sm font-medium text-gray-700 mb-1">Select Transaction Type</label>
                    <div class="relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <select 
                            name="transaction_subtype_id" 
                            id="transaction_subtype_id" 
                            class="pl-10 block w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500"
                        >
                            <option value="">Select Transaction Type</option>
                        </select>
                    </div>
                    @error('transaction_subtype_id')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Transaction Vaccine -->
                <div id="vaccine_dropdown" style="display: none;">
                    <label for="vaccine_id" class="block text-sm font-medium text-gray-700 mb-1">Select Vaccine</label>
                    <div class="relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                            </svg>
                        </div>
                        <select 
                            name="vaccine_id" 
                            id="vaccine_id" 
                            class="pl-10 block w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500"
                        >
                            <option value="">Select a Vaccine</option>
                            @foreach ($vaccines as $vaccine)
                                <option value="{{ $vaccine->id }}" {{ old('vaccine_id') == $vaccine->id ? 'selected' : '' }}>
                                    {{ $vaccine->vaccine_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @error('vaccine_id')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Veterinarian Selection -->
                <div>
                    <label for="vet_id" class="block text-sm font-medium text-gray-700 mb-1">Select Veterinarian</label>
                    <div class="relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <select 
                            name="vet_id" 
                            id="vet_id" 
                            class="pl-10 block w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500"
                        >
                            <option value="">Select Veterinarian</option>
                            @foreach($vets as $vet)
                                <option value="{{ $vet->user_id }}">{{ $vet->complete_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('vet_id')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Technician Selection -->   
                <div>
                    <label for="technician_id" class="block text-sm font-medium text-gray-700 mb-1">Select Technician</label>
                    <div class="relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <select 
                            name="technician_id" 
                            id="technician_id" 
                            class="pl-10 block w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500"
                        >
                            <option value="">Select Technician</option>
                            @foreach($technicians as $technician)
                                <option value="{{ $technician->technician_id }}" {{ old('technician_id') == $technician->technician_id ? 'selected' : '' }}>
                                    {{ $technician->full_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @error('technician_id')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status - Hidden -->
                <div hidden>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select 
                        name="status" 
                        id="status" 
                        class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500" 
                        required
                    >
                        <option value="0">Pending</option>
                        <option value="1">Completed</option>
                        <option value="2">Cancelled</option>
                    </select>
                    @error('status')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Details -->
                <div>
                    <label for="details" class="block text-sm font-medium text-gray-700 mb-1">Details</label>
                    <div class="relative rounded-md shadow-sm">
                        <div class="absolute top-3 left-3 flex items-start pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                            </svg>
                        </div>
                        <textarea 
                            name="details" 
                            id="details" 
                            rows="4" 
                            class="pl-10 block w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500"
                        ></textarea>
                    </div>
                    @error('details')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Buttons with space between -->
                <div class="flex items-center justify-between space-x-4 pt-4 mt-6 border-t border-gray-200">
                    <a 
                        href="{{ route('owners.profile-owner', ['owner_id' => $owner_id]) }}" 
                        class="inline-flex items-center px-5 py-3 bg-red-500 text-white font-medium rounded-lg hover:bg-red-700 transition-all duration-300 shadow-md"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                        </svg>
                        Cancel
                    </a>
                    
                    <button
                        type="submit"
                        class="inline-flex items-center px-5 py-3 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-all duration-300 shadow-md"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Add Transaction
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('transaction_type_id').addEventListener('change', function () {
            const transactionTypeId = this.value;
            const subtypeSelect = document.getElementById('transaction_subtype_id');
            const transactionData = @json($transactionTypes);
    
            // Clear existing options
            subtypeSelect.innerHTML = '<option value="">Select Transaction Type</option>';
    
            if (transactionTypeId) {
                // Find the selected transaction type's subtypes
                const selectedType = transactionData.find(type => type.id == transactionTypeId);
    
                if (selectedType && selectedType.subtypes) {
                    selectedType.subtypes.forEach(subtype => {
                        const option = document.createElement('option');
                        option.value = subtype.id;
                        option.textContent = subtype.subtype_name;
                        subtypeSelect.appendChild(option);
                    });
    
                    // Automatically trigger change event to toggle vaccine dropdown
                    subtypeSelect.dispatchEvent(new Event('change'));
                }
            }
        });
    
        document.getElementById('transaction_subtype_id').addEventListener('change', function () {
            const selectedSubtypeId = this.value;
            toggleVaccineDropdown(selectedSubtypeId);
        });
    
        function toggleVaccineDropdown(subtypeId) {
            // Assuming "8" is the ID for "Vaccination" in your transaction_subtypes table
            const vaccineDropdown = document.getElementById('vaccine_dropdown');
            if (subtypeId == '8') {
                vaccineDropdown.style.display = 'block'; // Show vaccine dropdown
            } else {
                vaccineDropdown.style.display = 'none'; // Hide vaccine dropdown
            }
        }
    
        // Trigger subtype and vaccine dropdown visibility on page load if needed
        document.addEventListener('DOMContentLoaded', function () {
            const initialSubtypeId = document.getElementById('transaction_subtype_id').value;
            toggleVaccineDropdown(initialSubtypeId);
        });
    </script>
</x-app-layout>