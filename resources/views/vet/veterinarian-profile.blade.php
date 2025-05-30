<x-app-layout>
    <div class="container mx-auto px-4 py-6">
        <!-- Profile Section with improved styling -->
        <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 p-6 mb-8">
            <div class="flex items-center space-x-6">
                <img class="w-36 h-36 object-cover rounded-full border-4 border-green-500 shadow-lg hover:scale-105 transition-transform duration-300" 
                    src="{{ $veterinarian->profile_image ? Storage::url($veterinarian->profile_image) : asset('assets/default-avatar.png') }}" 
                    alt="Profile Image">
                <div>
                    <h2 class="text-2xl font-semibold text-gray-800 flex items-center">
                        <i class="fas fa-user-md text-blue-500 mr-2"></i>{{ $veterinarian->complete_name }}'s Profile
                    </h2>
                    <p class="text-lg text-gray-600">
                        <strong>Designation:</strong>
                        @if($veterinarian->designation)
                            {{ $veterinarian->designation->name }}
                        @else
                            No designation
                        @endif
                    </p>
                    <p class="text-sm text-gray-500"><i class="fas fa-phone-alt text-green-500"></i> Contact: {{ $veterinarian->contact_no ?? 'N/A' }}</p>
                    <p class="text-sm text-gray-500 mt-2"><strong>Transactions Handled:</strong> {{ $transactionCount }}</p>
            
                    <!-- Edit Profile Button -->
                    <a href="{{ route('newvets.edit', $veterinarian->user_id) }}" class="mt-4 inline-flex items-center px-5 py-3 bg-blue-500 text-white rounded-lg shadow-md hover:bg-blue-800 transition-all duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit Profile
                    </a>
                </div>
            </div>
        </div>

        <!-- Transactions Section Header -->
        <div class="bg-gradient-to-r from-green-800 to-green-600 rounded-t-lg shadow-md p-4 mb-0">
            <h2 class="text-xl font-semibold text-white flex items-center">
                <i class="fas fa-clipboard-list mr-2"></i>Transactions
            </h2>
        </div>

        <!-- Search and Filters Section -->
        <div class="bg-white rounded-b-lg shadow-lg mb-8">
            <div class="p-6 border-b border-gray-200">
                <form action="{{ route('vet.veterinarian.profile', $veterinarian->user_id) }}" method="GET" id="filtersForm" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <!-- Search Input -->
                    <div class="flex items-center">
                        <input 
                            type="text" 
                            name="search" 
                            value="{{ request('search') }}" 
                            placeholder="Search by Animal/Owner Name" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                            id="searchInput" />
                    </div>
                    <!-- Status Filter -->
                    <div class="flex items-center">
                        <select 
                            name="status" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                            onchange="document.getElementById('filtersForm').submit()">
                            <option value="">All Statuses</option>
                            <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Pending</option>
                            <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Completed</option>
                            <option value="2" {{ request('status') == '2' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
        
                    <!-- Transaction Type Filter -->
                    <div class="flex items-center">
                        <select 
                            name="transaction_type" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                            onchange="document.getElementById('filtersForm').submit()">
                            <option value="">All Transaction Types</option>
                            @foreach ($transactionTypes as $type)
                                <option value="{{ $type->id }}" {{ request('transaction_type') == $type->id ? 'selected' : '' }}>
                                    {{ $type->type_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
        
                    <!-- Transaction Subtype Filter -->
                    <div class="flex items-center">
                        <select 
                            name="transaction_subtype" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                            onchange="document.getElementById('filtersForm').submit()">
                            <option value="">All Subtypes</option>
                            @foreach ($transactionSubtypes as $subtype)
                                <option value="{{ $subtype->id }}" {{ request('transaction_subtype') == $subtype->id ? 'selected' : '' }}>
                                    {{ $subtype->subtype_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
        
                    <!-- Date Filter Start -->
                    <div class="flex items-center">
                        <label for="start_date" class="mr-2 text-gray-700">From:</label>
                        <input 
                            type="date" 
                            name="start_date" 
                            value="{{ request('start_date') }}" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                            onchange="document.getElementById('filtersForm').submit()" />
                    </div>

                    <!-- Date Filter End -->
                    <div class="flex items-center">
                        <label for="end_date" class="mr-2 text-gray-700">To:</label>
                        <input 
                            type="date" 
                            name="end_date" 
                            value="{{ request('end_date') }}" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                            onchange="document.getElementById('filtersForm').submit()" />
                    </div>
        
                    <!-- Reset Button -->
                    <div class="flex items-center">
                        <a href="{{ route('vet.veterinarian.profile', $veterinarian->user_id) }}" class="inline-flex items-center justify-center w-full px-6 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Reset Filters
                        </a>
                    </div>
                </form>
            </div>
        
            <!-- Transactions Table -->
            <div class="overflow-x-auto p-6">
                <table class="min-w-full table-auto">
                    <thead>
                        <tr class="text-sm font-semibold text-gray-700 bg-gray-100 rounded-lg">
                            <th class="px-4 py-3 text-left rounded-tl-lg">Animal Owner Name</th>
                            <th class="px-4 py-3 text-left">Animal Name</th>
                            <th class="px-4 py-3 text-left">Transaction</th>
                            <th class="px-4 py-3 text-left">Animal Species</th>
                            <th class="px-4 py-3 text-left">Animal Breed</th>
                            <th class="px-4 py-3 text-left">Transaction Date</th>
                            <th class="px-4 py-3 text-left">Technician</th>
                            <th class="px-4 py-3 text-left">Transaction Details</th>
                            <th class="px-4 py-3 text-left rounded-tr-lg">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transactions as $transaction)
                        @php
                            $animal = $transaction->animal; // Assuming the 'animal' relationship is defined in the Transaction model
                        @endphp
                    
                        @if ($animal)
                            <tr class="text-sm border-b hover:bg-blue-50 transition duration-300">
                                <td class="px-6 py-3 text-start">
                                    <!-- Owner Profile Image -->
                                    <a href="{{ route('vet.profile-owner', ['owner_id' => $animal->owner->owner_id]) }}" class="flex items-center text-blue-600 hover:text-blue-800">
                                        <img src="{{ $animal->owner->user->profile_image ? Storage::url($animal->owner->user->profile_image) : 
                                                  ($animal->owner->user->gender === 'Female' ? asset('assets/female-default.png') : asset('assets/male-default.png')) }}" 
                                             alt="{{ $animal->owner->user->complete_name }}" 
                                             class="w-8 h-8 object-cover rounded-full border-2 border-gray-300 mr-2">
                                        <span class="font-medium">{{ $animal->owner->user->complete_name ?? 'Unknown Owner' }}</span>
                                    </a>
                                </td>

                                <td class="px-6 py-3 text-start">
                                    <!-- Animal Photo Front -->
                                    <a href="{{ route('vet.profile', ['animal_id' => $animal->animal_id]) }}" class="flex items-center text-blue-600 hover:text-blue-800">
                                        <img src="{{ $animal->photo_front ? Storage::url($animal->photo_front) : asset('assets/animal-default.png') }}" 
                                            alt="Animal Photo" class="w-8 h-8 object-cover rounded-full border-2 border-gray-300 mr-2">
                                        <span class="font-medium">{{ $animal->name ?? 'Unknown Animal' }}</span>
                                    </a>
                                </td>

                                <td class="px-4 py-2 text-gray-700">
                                    {{ $transaction->transactionType->type_name ?? 'Unknown' }}
                                    @if($transaction->transactionSubtype)
                                        - {{ $transaction->transactionSubtype->subtype_name ?? 'Unknown' }}
                                    @endif
                                </td>
                            
                                <td class="px-4 py-2 text-gray-700">{{ $transaction->animal->species->name ?? 'Unknown Species' }}</td>
                                <td class="px-4 py-2 text-gray-700">{{ $transaction->animal->breed->name ?? 'Unknown Breed' }}</td>
                                <td class="px-4 py-2 text-gray-700">{{ $transaction->created_at->format('F j, Y') }}</td>

                                <td class="px-4 py-2 text-gray-700">
                                    <form action="{{ route('update.details', $transaction->transaction_id) }}" method="POST" class="flex items-center" id="technicianForm-{{ $transaction->transaction_id }}">
                                        @csrf
                                        @method('PUT')
                                        <select 
                                            name="technician_id" 
                                            class="px-8 py-2 bg-gray-100 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none text-gray-700 font-medium transition-all duration-200 ease-in-out" 
                                            onchange="confirmTechnicianChange(event, {{ $transaction->transaction_id }})"
                                            {{ $transaction->status == 1 ? 'disabled' : '' }}>  {{-- Disable if status is 1 --}}
                                            
                                            <option value="" {{ $transaction->technician_id ? '' : 'selected' }}>Select Technician</option>
                                            @foreach ($technicians as $technician)
                                                <option value="{{ $technician->technician_id }}" 
                                                        {{ $transaction->technician_id == $technician->technician_id ? 'selected' : '' }}>
                                                    {{ $technician->full_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </form>
                                </td>
                                
                                <td class="px-4 py-2 text-gray-700">
                                    @if ($transaction->status == 1)
                                        {{-- Display details as read-only when status is "Completed" --}}
                                        <div class="w-full px-4 py-2 text-gray-700 bg-gray-100 border border-gray-300 rounded-md shadow-sm text-sm">
                                            <p class="text-gray-700">{{ $transaction->details ?? 'No details available.' }}</p>
                                        </div>
                                    @else
                                        {{-- Editable form when transaction is not completed --}}
                                        <form action="{{ route('update.details', $transaction->transaction_id) }}" method="POST" class="flex flex-col space-y-2">
                                            @csrf
                                            @method('PUT')
                                        
                                            <textarea 
                                                name="details" 
                                                rows="3" 
                                                class="w-full px-4 py-2 text-gray-700 bg-gray-100 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none text-sm transition-all duration-200 ease-in-out" 
                                                placeholder="Enter transaction details..."
                                            >{{ old('details', $transaction->details) }}</textarea>
                                        
                                            <button type="submit" class="inline-flex items-center px-6 py-2 text-white bg-blue-500 hover:bg-blue-600 rounded-lg shadow-md text-sm">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                                Update Details
                                            </button>
                                        </form>
                                    @endif
                                </td>
                                
                                <td class="px-4 py-2 text-gray-700">
                                    @if ($transaction->status == 1)
                                        <!-- If status is 'Completed', show a button instead of dropdown -->
                                        <a href="#" 
                                           onclick="openTransactionModal('{{ $transaction->transaction_id }}')"
                                           class="inline-flex items-center flex-col gap-2 px-4 py-2 bg-green-600 text-white font-semibold rounded-lg shadow-md transition-all duration-200 ease-in-out hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2">
                                            <span class="text-white font-mono font-semibold">✔ Successful</span>
                                            <span class="text-sm">See Transaction Details</span>
                                        </a>
                                    @else
                                        <!-- If not 'Completed', show dropdown -->
                                        <form action="{{ route('vet.updateStatus', $transaction->transaction_id) }}" method="POST" class="flex items-center" id="statusForm-{{ $transaction->transaction_id }}">
                                            @csrf
                                            @method('PUT')
                                            <select 
                                                name="status" 
                                                class="px-8 py-2 bg-gray-100 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none text-gray-700 font-medium transition-all duration-200 ease-in-out" 
                                                onchange="confirmStatusChange(event, {{ $transaction->transaction_id }})">
                                                
                                                @if ($transaction->status == 0)
                                                    <option value="0" selected disabled>Pending</option>
                                                    <option value="1">Completed</option>
                                                    <option value="2">Cancelled</option>
                                                @elseif ($transaction->status == 2)
                                                    <option value="2" selected>Cancelled</option>
                                                    <option value="1">Completed</option>
                                                @endif
                                            </select>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endif
                    @endforeach
                    </tbody>
                </table>
            </div>
        
            <!-- Pagination Links -->
            <div class="p-6 border-t border-gray-200">
                {{ $transactions->links() }}
            </div>
        </div>
    </div>

    <!-- Add Font Awesome CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <script>
        function confirmStatusChange(event, transactionId) {
            const selectedOption = event.target.value;

            let message = '';
            if (selectedOption == 1) {
                message = "Are you sure you want to mark this transaction as Completed?";
            } else if (selectedOption == 2) {
                message = "Are you sure you want to cancel this transaction?";
            }

            if (message) {
                if (!confirm(message)) {
                    // If the user cancels, reset the dropdown to its previous value
                    event.target.selectedIndex = [...event.target.options].findIndex(option => option.defaultSelected);
                } else {
                    // If confirmed, submit the form
                    document.getElementById(`statusForm-${transactionId}`).submit();
                }
            }
        }

        function confirmTechnicianChange(event, transactionId) {
            if (confirm('Are you sure you want to update the technician for this transaction?')) {
                document.getElementById(`technicianForm-${transactionId}`).submit();
            } else {
                // Revert selection if user cancels
                event.target.value = event.target.dataset.previousValue || "";
            }
        }

        // Store the previous value of the dropdown on focus for technician changes
        document.querySelectorAll('select[name="technician_id"]').forEach((dropdown) => {
            dropdown.addEventListener('focus', function () {
                this.dataset.previousValue = this.value;
            });
        });
    </script>

    <!-- Transaction Modal -->
    <div id="transactionModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <!-- Modal panel -->
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-5xl sm:w-full">
                <div class="absolute top-0 right-0 pt-4 pr-4">
                    <button type="button" onclick="closeTransactionModal()" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                        <span class="sr-only">Close</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <!-- Modal content will be loaded here -->
                <div id="transactionModalContent" class="p-6">
                    <div class="flex justify-center">
                        <svg class="animate-spin -ml-1 mr-3 h-10 w-10 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <p class="text-lg font-medium text-gray-700">Loading transaction details...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openTransactionModal(transactionId) {
            // Show modal
            document.getElementById('transactionModal').classList.remove('hidden');
            
            // Fetch transaction details via AJAX
            fetch(`/admin/transactions/${transactionId}/details-partial`)
                .then(response => response.text())
                .then(html => {
                    document.getElementById('transactionModalContent').innerHTML = html;
                })
                .catch(error => {
                    document.getElementById('transactionModalContent').innerHTML = `
                        <div class="text-center text-red-500">
                            <p>Error loading transaction details. Please try again.</p>
                        </div>
                    `;
                    console.error('Error fetching transaction details:', error);
                });
        }
        
        function closeTransactionModal() {
            document.getElementById('transactionModal').classList.add('hidden');
            // Reset content to loading state for next time
            document.getElementById('transactionModalContent').innerHTML = `
                <div class="flex justify-center">
                    <svg class="animate-spin -ml-1 mr-3 h-10 w-10 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <p class="text-lg font-medium text-gray-700">Loading transaction details...</p>
                </div>
            `;
        }
    </script>
</x-app-layout>