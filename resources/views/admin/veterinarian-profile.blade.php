<x-app-layout>
    <div class=" from-green-50 to-white min-h-screen">
        <div class="container mx-auto px-4 py-8">
            <!-- Profile Section -->
            <div class="bg-white rounded-xl shadow-xl overflow-hidden mb-8 transition-all duration-300 hover:shadow-2xl">
                <div class="bg-gradient-to-r from-green-600 to-green-400 h-24"></div>
                <div class="px-8 -mt-12 pb-6">
                    <div class="flex flex-col md:flex-row items-center md:items-start gap-6">
                        <!-- Profile Image -->
                        <div class="relative">
                            <img class="w-32 h-32 md:w-40 md:h-40 object-cover rounded-full border-4 border-white shadow-xl transform transition-transform duration-300 hover:scale-105" 
                                src="{{ $veterinarian->profile_image ? Storage::url($veterinarian->profile_image) : 
                                     ($veterinarian->gender === 'Female' ? asset('assets/female-default.png') : asset('assets/male-default.png')) }}" 
                                alt="{{ $veterinarian->complete_name }}'s Profile Image">
                            <div class="absolute bottom-0 right-0 bg-green-500 text-white rounded-full w-8 h-8 flex items-center justify-center border-2 border-white">
                                <i class="fas fa-user-md"></i>
                            </div>
                        </div>
                        
                        <!-- Profile Info -->
                        <div class="text-center md:text-left mt-4 md:mt-12">
                            <h2 class="text-3xl font-bold text-gray-800 flex items-center justify-center md:justify-start">
                                {{ $veterinarian->complete_name }}
                                <span class="ml-2 bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">Veterinarian</span>
                            </h2>
                            <p class="text-lg text-gray-600 mt-1">
                                <i class="fas fa-tag text-blue-500 mr-2"></i>
                                @if($veterinarian->designation)
                                    {{ $veterinarian->designation->name }}
                                @else
                                    No designation
                                @endif
                            </p>
                            <div class="flex items-center justify-center md:justify-start mt-2 text-gray-600">
                                <i class="fas fa-phone-alt text-green-500 mr-2"></i>
                                <span>{{ $veterinarian->contact_no ?? 'N/A' }}</span>
                            </div>
                            
                            <div class="mt-4 flex flex-wrap items-center justify-center md:justify-start gap-3">
                                <div class="bg-blue-50 rounded-lg py-2 px-4 flex items-center">
                                    <div class="mr-2 bg-blue-100 rounded-full p-2">
                                        <i class="fas fa-clipboard-list text-blue-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Transactions</p>
                                        <p class="text-lg font-bold text-gray-800">{{ $transactionCount }}</p>
                                    </div>
                                </div>
                                
                                <!-- Edit Profile Button -->
                                <a href="{{ route('vets.edit', $veterinarian->user_id) }}" class="flex items-center gap-2 px-5 py-2.5 text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow-md transition-all duration-200">
                                    <i class="fas fa-user-edit"></i>
                                    <span>Edit Profile</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Transactions Header -->
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-clipboard-list text-blue-600 mr-3"></i>Transactions
                </h2>
                <div class="text-sm font-medium text-gray-500">
                    Showing <span class="text-blue-600">{{ $transactions->count() }}</span> of <span class="text-blue-600">{{ $transactionCount }}</span> transactions
                </div>
            </div>

            <!-- Search and Filters Section -->
            <div class="bg-white rounded-xl shadow-lg mb-6 overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-700 mb-4 flex items-center">
                        <i class="fas fa-filter text-blue-500 mr-2"></i>Filters & Search
                    </h3>
                    
                    <form action="{{ route('admin.veterinarian.profile', $veterinarian->user_id) }}" method="GET" id="filtersForm" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <!-- Search Input -->
                        <div class="relative">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <input 
                                type="text" 
                                name="search" 
                                value="{{ request('search') }}" 
                                placeholder="Search by Animal/Owner Name" 
                                class="pl-10 w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50" 
                                id="searchInput" />
                        </div>
                        
                        <!-- Status Filter -->
                        <div class="relative">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                <i class="fas fa-tasks text-gray-400"></i>
                            </div>
                            <select 
                                name="status" 
                                class="pl-10 w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50" 
                                onchange="document.getElementById('filtersForm').submit()">
                                <option value="">All Statuses</option>
                                <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Pending</option>
                                <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Completed</option>
                                <option value="2" {{ request('status') == '2' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        
                        <!-- Transaction Type Filter -->
                        <div class="relative">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                <i class="fas fa-tag text-gray-400"></i>
                            </div>
                            <select 
                                name="transaction_type" 
                                class="pl-10 w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50" 
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
                        <div class="relative">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                <i class="fas fa-tags text-gray-400"></i>
                            </div>
                            <select 
                                name="transaction_subtype" 
                                class="pl-10 w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50" 
                                onchange="document.getElementById('filtersForm').submit()">
                                <option value="">All Subtypes</option>
                                @foreach ($transactionSubtypes as $subtype)
                                    <option value="{{ $subtype->id }}" {{ request('transaction_subtype') == $subtype->id ? 'selected' : '' }}>
                                        {{ $subtype->subtype_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Date Filter - From -->
                        <div class="relative">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                <i class="fas fa-calendar-alt text-gray-400"></i>
                            </div>
                            <input 
                                type="date" 
                                name="start_date" 
                                value="{{ request('start_date') }}" 
                                class="pl-10 w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50" 
                                onchange="document.getElementById('filtersForm').submit()" />
                        </div>
                        
                        <!-- Date Filter - To -->
                        <div class="relative">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                <i class="fas fa-calendar-alt text-gray-400"></i>
                            </div>
                            <input 
                                type="date" 
                                name="end_date" 
                                value="{{ request('end_date') }}" 
                                class="pl-10 w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50" 
                                onchange="document.getElementById('filtersForm').submit()" />
                        </div>
                        
                        <!-- Reset Button -->
                        <div class="flex items-center">
                            <a href="{{ route('admin.veterinarian.profile', $veterinarian->user_id) }}" class="w-full flex items-center justify-center gap-2 px-5 py-2.5 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition duration-200">
                                <i class="fas fa-undo-alt"></i>
                                <span>Reset Filters</span>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Transactions Table -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full table-auto">
                        <thead>
                            <tr class="text-sm font-semibold text-gray-700 bg-gray-50 border-b">
                                <th class="px-6 py-4 text-left">Animal Owner</th>
                                <th class="px-6 py-4 text-left">Animal</th>
                                <th class="px-6 py-4 text-left">Transaction</th>
                                <th class="px-6 py-4 text-left">Species & Breed</th>
                                <th class="px-6 py-4 text-left">Date</th>
                                <th class="px-6 py-4 text-left">Technician</th>
                                <th class="px-6 py-4 text-left">Details</th>
                                <th class="px-6 py-4 text-left">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transactions as $transaction)
                            @php
                                $animal = $transaction->animal; // Assuming the 'animal' relationship is defined in the Transaction model
                            @endphp
                        
                            @if ($animal)
                                <tr class="text-sm border-b hover:bg-blue-50 transition duration-300">
                                    <td class="px-6 py-4">
                                        <a href="{{ route('owners.profile-owner', ['owner_id' => $animal->owner->owner_id]) }}" class="flex items-center group">
                                            <div class="relative mr-3">
                                                <img src="{{ $animal->owner->user->profile_image ? Storage::url($animal->owner->user->profile_image) : 
                                                  ($animal->owner->user->gender === 'Female' ? asset('assets/female-default.png') : asset('assets/male-default.png')) }}" 
                                                     alt="{{ $animal->owner->user->complete_name }}" 
                                                     class="w-10 h-10 object-cover rounded-full border-2 border-gray-200 group-hover:border-blue-400 transition-all duration-200">
                                                <div class="absolute -bottom-1 -right-1 h-4 w-4 bg-green-400 rounded-full border-2 border-white"></div>
                                            </div>
                                            <div>
                                                <p class="font-medium text-gray-800 group-hover:text-blue-600 transition-colors duration-200">{{ $animal->owner->user->complete_name ?? 'Unknown Owner' }}</p>
                                                <p class="text-xs text-gray-500">Owner</p>
                                            </div>
                                        </a>
                                    </td>

                                    <td class="px-6 py-4">
                                        <a href="{{ route('animals.profile', ['animal_id' => $animal->animal_id]) }}" class="flex items-center group">
                                            <div class="relative mr-3">
                                                <img src="{{ $animal->photo_front ? Storage::url($animal->photo_front) : asset('assets/animal-default.png') }}" 
                                                    alt="Animal Photo" class="w-10 h-10 object-cover rounded-full border-2 border-gray-200 group-hover:border-blue-400 transition-all duration-200">
                                            </div>
                                            <div>
                                                <p class="font-medium text-gray-800 group-hover:text-blue-600 transition-colors duration-200">{{ $animal->name ?? 'Unknown Animal' }}</p>
                                                <p class="text-xs text-gray-500">Animal ID: #{{ $animal->animal_id }}</p>
                                            </div>
                                        </a>
                                    </td>

                                    <td class="px-6 py-4">
                                        <div class="bg-blue-50 rounded-lg px-3 py-1 inline-block">
                                            <p class="font-medium text-blue-700">{{ $transaction->transactionType->type_name ?? 'Unknown' }}</p>
                                            @if($transaction->transactionSubtype)
                                            <p class="text-xs text-blue-500">{{ $transaction->transactionSubtype->subtype_name ?? 'Unknown' }}</p>
                                            @endif
                                        </div>
                                    </td>
                                
                                    <td class="px-6 py-4">
                                        <p class="font-medium text-gray-800">{{ $transaction->animal->species->name ?? 'Unknown Species' }}</p>
                                        <p class="text-xs text-gray-500">{{ $transaction->animal->breed->name ?? 'Unknown Breed' }}</p>
                                    </td>
                                    
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="mr-2 bg-indigo-100 text-indigo-800 p-1 rounded-full">
                                                <i class="fas fa-calendar-day"></i>
                                            </div>
                                            <span>{{ $transaction->created_at->format('F j, Y') }}</span>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4">
                                        <form action="{{ route('updateTech', $transaction->transaction_id) }}" method="POST" class="relative" id="technicianForm-{{ $transaction->transaction_id }}">
                                            @csrf
                                            @method('PUT')
                                            
                                            <select 
                                                name="technician_id" 
                                                class="w-full pl-8 pr-10 py-2 bg-gray-50 border border-gray-300 rounded-lg text-gray-700 focus:ring-2 focus:ring-blue-500 focus:outline-none appearance-none {{ $transaction->status == 1 ? 'opacity-80' : '' }}" 
                                                onchange="confirmTechnicianChange(event, {{ $transaction->transaction_id }})"
                                                {{ $transaction->status == 1 ? 'disabled' : '' }}>
                                                
                                                <option value="" {{ $transaction->technician_id ? '' : 'selected' }}>Select Technician</option>
                                                @foreach ($technicians as $technician)
                                                    <option value="{{ $technician->technician_id }}" 
                                                            {{ $transaction->technician_id == $technician->technician_id ? 'selected' : '' }}>
                                                        {{ $technician->full_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            
                                            <div class="absolute inset-y-0 left-0 flex items-center pl-2 pointer-events-none">
                                                <i class="fas fa-user-nurse text-gray-400"></i>
                                            </div>
                                            
                                        </form>
                                    </td>
                                    
                                    <td class="px-6 py-4">
                                        @if ($transaction->status == 1)
                                            <div class="w-full px-4 py-3 text-gray-700 bg-gray-50 border border-gray-200 rounded-lg shadow-sm">
                                                <p class="text-gray-700">{{ $transaction->details ?? 'No details available.' }}</p>
                                            </div>
                                        @else
                                            <form action="{{ route('update.dets', $transaction->transaction_id) }}" method="POST" class="space-y-2">
                                                @csrf
                                                @method('PUT')
                                            
                                                <div class="relative">
                                                    <textarea 
                                                        name="details" 
                                                        rows="3" 
                                                        class="w-full px-4 py-3 text-gray-700 bg-gray-50 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none text-sm" 
                                                        placeholder="Enter transaction details..."
                                                    >{{ old('details', $transaction->details) }}</textarea>
                                                </div>
                                            
                                                <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2 text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow-sm text-sm transition-colors duration-200">
                                                    <i class="fas fa-save"></i>
                                                    <span>Update Details</span>
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                    
                                    <td class="px-6 py-4">
                                        @if ($transaction->status == 1)
                                            <a href="#" 
                                               onclick="openTransactionModal('{{ $transaction->transaction_id }}')"
                                               class="flex flex-col items-center px-4 py-2 bg-green-100 border border-green-300 text-green-800 font-medium rounded-lg hover:bg-green-200 transition-all duration-200 ease-in-out">
                                                <div class="flex items-center gap-2">
                                                    <i class="fas fa-check-circle text-green-600"></i>
                                                    <span>Completed</span>
                                                </div>
                                                <span class="text-xs mt-1">View Details</span>
                                            </a>
                                        @else
                                            <form action="{{ route('updateStatus', $transaction->transaction_id) }}" method="POST" id="statusForm-{{ $transaction->transaction_id }}">
                                                @csrf
                                                @method('PUT')
                                                <div class="relative">
                                                    <select 
                                                        name="status" 
                                                        class="w-full pl-10 pr-10 py-3 {{ $transaction->status == 0 ? 'bg-yellow-50 border-yellow-300 text-yellow-800' : 'bg-red-50 border-red-300 text-red-800' }} border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none appearance-none" 
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
                                                    
                                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                        @if ($transaction->status == 0)
                                                            <i class="fas fa-clock text-yellow-600"></i>
                                                        @elseif ($transaction->status == 2)
                                                            <i class="fas fa-ban text-red-600"></i>
                                                        @endif
                                                    </div>
                                                </div>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Empty State (when no transactions) -->
                @if(count($transactions) == 0)
                <div class="text-center py-12">
                    <div class="mb-4 mx-auto w-16 h-16 bg-gray-100 text-gray-400 rounded-full flex items-center justify-center">
                        <i class="fas fa-clipboard-list text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900">No transactions found</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        Try adjusting your search or filter criteria to find what you're looking for.
                    </p>
                    <div class="mt-6">
                        <a href="{{ route('admin.veterinarian.profile', $veterinarian->user_id) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200">
                            <i class="fas fa-redo mr-2"></i>
                            Reset Filters
                        </a>
                    </div>
                </div>
                @endif
                
                <!-- Pagination Links -->
                <div class="p-6 border-t border-gray-200">
                    {{ $transactions->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Add Font Awesome CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    
    <!-- Transaction Modal -->
    <div id="transactionModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay with blur effect -->
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 bg-opacity-75 backdrop-blur-sm"></div>
            </div>

            <!-- Modal panel -->
            <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-5xl sm:w-full">
                <div class="bg-gradient-to-r from-blue-600 to-blue-400 px-6 py-4 flex justify-between items-center">
                    <h3 class="text-xl font-bold text-white">Transaction Details</h3>
                    <button type="button" onclick="closeTransactionModal()" class="text-white hover:text-gray-200 focus:outline-none transition-colors duration-200">
                        <span class="sr-only">Close</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <!-- Modal content will be loaded here -->
                <div id="transactionModalContent" class="p-6">
                    <div class="flex flex-col items-center justify-center p-8">
                        <div class="animate-spin mb-4 w-12 h-12 border-4 border-blue-600 border-t-transparent rounded-full"></div>
                        <p class="text-lg font-medium text-gray-700">Loading transaction details...</p>
                        <p class="text-sm text-gray-500 mt-2">This may take a moment</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
        
        function openTransactionModal(transactionId) {
            // Show modal with fancy entrance
            const modal = document.getElementById('transactionModal');
            modal.classList.remove('hidden');
            modal.querySelector('.inline-block').classList.add('animate-fadeInUp');
            
            // Fetch transaction details via AJAX
            fetch(`/admin/transactions/${transactionId}/details-partial`)
                .then(response => response.text())
                .then(html => {
                    document.getElementById('transactionModalContent').innerHTML = html;
                })
                .catch(error => {
                    document.getElementById('transactionModalContent').innerHTML = `
                        <div class="text-center p-8">
                            <div class="mb-4 mx-auto w-16 h-16 bg-red-100 text-red-500 rounded-full flex items-center justify-center">
                                <i class="fas fa-exclamation-triangle text-2xl"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900">Error loading details</h3>
                            <p class="mt-1 text-sm text-gray-500">
                                Unable to load transaction details. Please try again later.
                            </p>
                            <button onclick="closeTransactionModal()" class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200">
                                <i class="fas fa-times mr-2"></i>
                                Close
                            </button>
                        </div>
                    `;
                    console.error('Error fetching transaction details:', error);
                });
        }
        
        function closeTransactionModal() {
            // Add exit animation
            const modalContent = document.querySelector('#transactionModal .inline-block');
            modalContent.classList.remove('animate-fadeInUp');
            modalContent.classList.add('animate-fadeOutDown');
            
            // Wait for animation to finish then hide
            setTimeout(() => {
                document.getElementById('transactionModal').classList.add('hidden');
                modalContent.classList.remove('animate-fadeOutDown');
                
                // Reset content to loading state for next time
                document.getElementById('transactionModalContent').innerHTML = `
                    <div class="flex flex-col items-center justify-center p-8">
                        <div class="animate-spin mb-4 w-12 h-12 border-4 border-blue-600 border-t-transparent rounded-full"></div>
                        <p class="text-lg font-medium text-gray-700">Loading transaction details...</p>
                        <p class="text-sm text-gray-500 mt-2">This may take a moment</p>
                    </div>
                `;
            }, 300);
        }
    </script>
    
    <style>
        /* Custom animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translate3d(0, 40px, 0);
            }
            to {
                opacity: 1;
                transform: translate3d(0, 0, 0);
            }
        }
        
        @keyframes fadeOutDown {
            from {
                opacity: 1;
                transform: translate3d(0, 0, 0);
            }
            to {
                opacity: 0;
                transform: translate3d(0, 40px, 0);
            }
        }
        
        .animate-fadeInUp {
            animation: fadeInUp 0.3s ease-out forwards;
        }
        
        .animate-fadeOutDown {
            animation: fadeOutDown 0.3s ease-in forwards;
        }
        
        /* Hover effects */
        .table-row-hover:hover {
            background-color: rgba(59, 130, 246, 0.05);
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        
        /* Table striping */
        tbody tr:nth-child(even) {
            background-color: rgba(249, 250, 251, 0.5);
        }
        
        /* Responsive improvements */
        @media (max-width: 768px) {
            .responsive-table {
                display: block;
                width: 100%;
                overflow-x: auto;
            }
            
            .mobile-stack {
                flex-direction: column;
            }
            
            .mobile-full-width {
                width: 100%;
            }
        }
    </style>
</x-app-layout>