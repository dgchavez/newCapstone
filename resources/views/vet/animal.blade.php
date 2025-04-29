<x-app-layout>
    <div class="max-w-6xl mx-auto px-4 py-6">
        <!-- Animal Header -->
        <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-lg p-6 shadow-md transition duration-300">
            <div class="flex items-center space-x-8">
                <!-- Animal Photo -->
                <div class="relative">
                    <img 
                        src="{{ $animal->photo_front ? asset('storage/' . $animal->photo_front) : asset('assets/default-avatar.png') }}" 
                        alt="{{ $animal->name }}" 
                        class="w-36 h-36 object-cover rounded-full border-4 border-gray-300 dark:border-neutral-600 shadow-lg"
                    >
                    <div class="absolute bottom-0 right-0 bg-blue-500 text-white p-1 rounded-full shadow-md">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M8 9a3 3 0 100 6 3 3 0 000-6zm3-3a3 3 0 11-6 0 3 3 0 016 0zm3 6a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                </div>

      <!-- Animal Details -->
<div class="flex-1">
    <h1 class="text-4xl font-bold text-gray-900 dark:text-neutral-100 flex items-center space-x-2">
        <span>{{ $animal->name }}</span>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400 dark:text-neutral-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 4.5v15m7.5-7.5h-15" />
        </svg>
    </h1>
    <p class="text-sm text-gray-600 dark:text-neutral-400 mt-2">
        <span class="font-semibold">Species:</span> 
        {{ $animal->species ? $animal->species->name : 'Species not specified' }}
        <span class="mx-2 text-gray-400 dark:text-neutral-500">|</span>
        <span class="font-semibold">Breed:</span> 
        {{ $animal->breed ? $animal->breed->name : 'Breed not specified' }}
    </p>
    
    <p class="text-sm text-gray-600 dark:text-neutral-400 mt-2 flex items-center space-x-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 21v-2a4 4 0 00-3-3.87V10a7 7 0 10-14 0v5.13A4 4 0 002 19v2h16z" />
        </svg>
        <a href="{{ route('vet.profile-owner', ['owner_id' => $animal->owner->owner_id]) }}" class="text-blue-600 dark:text-blue-400 hover:underline">
            {{ $animal->owner->user->complete_name }}
        </a>
    </p>
    <p class="text-sm text-gray-600 dark:text-neutral-400 mt-2 flex items-center space-x-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12h3m0 0v3m0-3v-3m0 0H12m0 0v3m0-3H9m0 0H6m3 0h3m0 0V9m0 3v3m0-3h3" />
        </svg>
        <span>
            Birthdate: 
            @if ($animal->birth_date)
                {{ $animal->birth_date->format('Y-m-d') }} ({{ $animal->birth_date->age }} years old)
            @else
                Not Available
            @endif
        </span>
    </p>
    @if ($animal->is_group)
        <p class="text-sm text-gray-600 dark:text-neutral-400 mt-2">
            <span class="font-semibold">Group Count:</span> {{ $animal->group_count ?? 'Unknown' }}
        </p>
    @else
        <p class="text-sm text-gray-600 dark:text-neutral-400 mt-2">
            <span class="font-semibold">Gender:</span> {{ ucfirst($animal->gender) }}
        </p>
    @endif
    <p class="text-sm text-gray-600 dark:text-neutral-400 mt-2">
        <span class="font-semibold">Medical Condition:</span> {{ $animal->medical_condition ?? 'None' }}
    </p>
</div>


              
            </div>
        </div
  
        <!-- Transactions Section -->
        <div class="mt-8">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-neutral-200">Transactions</h2>
            
            <!-- Filter Form -->
            <form id="filterForm" method="GET" action="{{ route('animals.profile', ['animal_id' => $animal->animal_id]) }}" class="mt-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Date Filter -->
                    <div>
                        <label for="search_date" class="block text-sm font-medium text-gray-600 dark:text-neutral-400">Date</label>
                        <input type="date" id="search_date" name="search_date" value="{{ request('search_date') }}" 
                               class="w-full border border-gray-300 rounded-lg dark:bg-neutral-800 dark:border-neutral-600 dark:text-neutral-200"
                               onchange="document.getElementById('filterForm').submit()">
                    </div>
            
                    <!-- Transaction Type Filter -->
                    <div>
                        <label for="transaction_type" class="block text-sm font-medium text-gray-600 dark:text-neutral-400">Transaction Type</label>
                        <select id="transaction_type" name="transaction_type" 
                                class="w-full border border-gray-300 rounded-lg dark:bg-neutral-800 dark:border-neutral-600 dark:text-neutral-200"
                                onchange="document.getElementById('filterForm').submit()">
                            <option value="">All Types</option>
                            @foreach($transactionTypes as $type)
                                <option value="{{ $type->id }}" {{ request('transaction_type') == $type->id ? 'selected' : '' }}>{{ $type->type_name }}</option>
                            @endforeach
                        </select>
                    </div>
            
                    <!-- Transaction Status Filter -->
                    <div>
                        <label for="transaction_status" class="block text-sm font-medium text-gray-600 dark:text-neutral-400">Status</label>
                        <select id="transaction_status" name="transaction_status" 
                                class="w-full border border-gray-300 rounded-lg dark:bg-neutral-800 dark:border-neutral-600 dark:text-neutral-200"
                                onchange="document.getElementById('filterForm').submit()">
                            <option value="">All Status</option>
                            <option value="0" {{ request('transaction_status') == '0' ? 'selected' : '' }}>Pending</option>
                            <option value="1" {{ request('transaction_status') == '1' ? 'selected' : '' }}>Completed</option>
                            <option value="2" {{ request('transaction_status') == '2' ? 'selected' : '' }}>Canceled</option>
                        </select>
                    </div>
                </div>
            </form>
            
            <!-- Transactions Table -->
            <table class="w-full mt-4 bg-white dark:bg-neutral-800 border rounded-lg shadow-sm">
                <thead>
                    <tr class="bg-gray-50 dark:bg-neutral-700 text-sm text-gray-800 dark:text-neutral-200">
                        <th class="px-4 py-2 text-left">Date</th>
                        <th class="px-4 py-2 text-left">Type</th>
                        <th class="px-4 py-2 text-left">Subtype</th>
                        <th class="px-4 py-2 text-left">Veterinarian</th>
                        <th class="px-4 py-2 text-left">Status</th>
                        <th class="px-4 py-2 text-left">Notes</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($animal->transactions as $transaction)
                        <tr class="border-t border-gray-200 dark:border-neutral-600">
                            <td class="px-4 py-2 text-gray-600 dark:text-neutral-400">{{ $transaction->created_at->format('Y-m-d') }}</td>
                            <td class="px-4 py-2 text-gray-800 dark:text-neutral-200">{{ $transaction->transactionType->type_name }}</td>
                            <td class="px-4 py-2 text-gray-800 dark:text-neutral-200">
                                {{ $transaction->transactionSubtype->subtype_name ?? 'N/A' }}
                            </td>
                            <td class="px-4 py-2 text-gray-800 dark:text-neutral-200">
                                {{ $transaction->vet->complete_name ?? 'N/A' }}
                            </td>
                            <td class="px-4 py-2 text-gray-600 dark:text-neutral-400">
                                @if ($transaction->status == 0)
                                    Pending
                                @elseif ($transaction->status == 1)
                                    Completed
                                @elseif ($transaction->status == 2)
                                    Canceled
                                @endif
                            </td>
                            <td class="px-4 py-2 text-gray-600 dark:text-neutral-400">{{ $transaction->details }}</td>
                             <!-- Action Buttons -->
                          
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-2 text-center text-gray-600 dark:text-neutral-400">No transactions available.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Pagination Links -->
            <div class="mt-4">
                {{ $transactions->links() }} <!-- Use the paginated collection here -->
            </div>
        </div>

        <!-- Add Transaction Form -->
     

    </div>

<script>
    // Preloaded transaction subtypes in JavaScript
    const transactionSubtypes = @json($transactionSubtypes);
</script>
    <script>
        // Confirm delete function
        function confirmDelete() {
            return confirm('Are you sure you want to delete this transaction?');
        }

        // Show or hide vaccine field based on selected subtype
        document.getElementById('transaction_subtype_id').addEventListener('change', function() {
            const subtypeId = this.value;
            const vaccineField = document.getElementById('vaccine_field');

            if (subtypeId == 8) {  // Vaccination subtype
                vaccineField.classList.remove('hidden');
            } else {
                vaccineField.classList.add('hidden');
            }
        });
        document.addEventListener('DOMContentLoaded', function() {
        // Function to filter subtypes based on selected transaction type
        function loadSubtypesForSelectedType() {
            const typeId = document.getElementById('transaction_type_id').value;
            const subtypeDropdown = document.getElementById('transaction_subtype_id');

            // Clear existing subtypes
            subtypeDropdown.innerHTML = '<option value="" selected disabled>Select Subtype</option>';

            if (typeId) {
                // Filter subtypes based on selected transaction type
                const filteredSubtypes = transactionSubtypes.filter(subtype => subtype.transaction_type_id == typeId);

                // Populate subtype dropdown with filtered subtypes
                filteredSubtypes.forEach(subtype => {
                    const option = document.createElement('option');
                    option.value = subtype.id;
                    option.textContent = subtype.subtype_name;
                    subtypeDropdown.appendChild(option);
                });
            }
        }

        // Event listener for the transaction type dropdown
        document.getElementById('transaction_type_id').addEventListener('change', loadSubtypesForSelectedType);
    });
    </script>
</x-app-layout>
