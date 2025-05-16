<x-app-layout>
    <div class="bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Page Header with Breadcrumbs -->
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <div class="flex items-center space-x-2 text-sm text-gray-500 mb-2">
                        <a href="{{ route('owner-dashboard') }}" class="hover:text-blue-600">Dashboard</a>
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                        <span>Transactions</span>
                    </div>
                    <h1 class="text-2xl font-bold text-gray-900 sm:text-3xl">
                        {{ $owner->user->complete_name }}'s Transactions
                    </h1>
                </div>
                <button onclick="window.history.back()" class="bg-gray-500 hover:bg-gray-600 focus:ring-4 focus:ring-gray-300 focus:outline-none text-white font-semibold rounded-lg px-8 py-3 shadow-md transition duration-300 ease-in-out transform hover:scale-105">
                    back
                </button>
            </div>

            <!-- Main Content Card -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <!-- Filters Section -->
                <div class="p-6 border-b border-gray-200 bg-white">
                    <div class="flex flex-col md:flex-row md:items-end md:justify-between space-y-4 md:space-y-0">
                        <h2 class="text-lg font-medium text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            Filter Transactions
                        </h2>
                        <button type="button" onclick="resetFilters()" 
                                class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Reset Filters
                        </button>
                    </div>
                    
                    <form method="GET" action="{{ route('owner.Newtransactions', ['owner_id' => $owner->owner_id]) }}" id="filterForm" class="mt-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                            <!-- Search Input -->
                            <div class="relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <input type="text" name="search" value="{{ request()->get('search') }}" placeholder="Search..." 
                                       class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md" 
                                       onchange="submitForm()">
                            </div>

                            <!-- Transaction Type Filter -->
                            <div>
                                <label for="transaction_type" class="block text-xs font-medium text-gray-500 mb-1">Transaction Type</label>
                                <select name="transaction_type" id="transaction_type" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md" onchange="submitForm()">
                                    <option value="">All Transaction Types</option>
                                    @foreach($transactionTypes as $type)
                                        <option value="{{ $type->type_name }}" {{ request()->get('transaction_type') == $type->type_name ? 'selected' : '' }}>
                                            {{ $type->type_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Transaction Subtype Filter -->
                            <div>
                                <label for="transaction_subtype" class="block text-xs font-medium text-gray-500 mb-1">Service Type</label>
                                <select name="transaction_subtype" id="transaction_subtype" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md" onchange="submitForm()">
                                    <option value="">All Service Types</option>
                                    @foreach($transactionSubtypes as $subtype)
                                        <option value="{{ $subtype->subtype_name }}" {{ request()->get('transaction_subtype') == $subtype->subtype_name ? 'selected' : '' }}>
                                            {{ $subtype->subtype_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Status Filter -->
                            <div>
                                <label for="status" class="block text-xs font-medium text-gray-500 mb-1">Status</label>
                                <select name="status" id="status" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md" onchange="submitForm()">
                                    <option value="">All Status</option>
                                    <option value="zero" {{ request()->get('status') == 'zero' ? 'selected' : '' }}>Pending</option>
                                    <option value="1" {{ request()->get('status') == '1' ? 'selected' : '' }}>Completed</option>
                                    <option value="2" {{ request()->get('status') == '2' ? 'selected' : '' }}>Canceled</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Transactions List -->
                <div class="overflow-x-auto">
                    @if($transactions->isNotEmpty())
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service Details</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Staff</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pet Information</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Status</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($transactions as $transaction)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <!-- Service Details -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                                    <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                    </svg>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        @if ($transaction->transactionSubtype && $transaction->transactionSubtype->subtype_name == 'Vaccination')
                                                            {{ $transaction->transactionSubtype->subtype_name }}
                                                        @else
                                                            {{ $transaction->transactionSubtype ? $transaction->transactionSubtype->subtype_name : 'N/A' }}
                                                        @endif
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        @if ($transaction->transactionSubtype && $transaction->transactionSubtype->subtype_name == 'Vaccination')
                                                            {{ $transaction->vaccine_name ?? 'No Vaccine Selected' }}
                                                        @else
                                                            {{ $transaction->transactionType ? $transaction->transactionType->type_name : 'N/A' }}
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        
                                        <!-- Staff -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                <span class="font-medium">Vet:</span> {{ $transaction->vet ? $transaction->vet->complete_name : 'Not assigned' }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                <span class="font-medium">Tech:</span> {{ $transaction->technician_name ?: 'Not assigned' }}
                                            </div>
                                        </td>
                                        
                                        <!-- Pet Information -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900 font-medium">{{ $transaction->animal ? $transaction->animal->name : 'N/A' }}</div>
                                            <div class="text-sm text-gray-500">
                                                {{ $transaction->animal ? $transaction->animal->species->name : 'N/A' }} / 
                                                {{ $transaction->animal ? $transaction->animal->breed->name : 'N/A' }}
                                            </div>
                                        </td>
                                        
                                        <!-- Date & Status -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500">{{ $transaction->created_at->format('F d, Y') }}</div>
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @if($transaction->status == 0) bg-yellow-100 text-yellow-800
                                                @elseif($transaction->status == 1) bg-green-100 text-green-800
                                                @elseif($transaction->status == 2) bg-red-100 text-red-800
                                                @else bg-gray-100 text-gray-800 @endif">
                                                @if($transaction->status == 0) Pending 
                                                @elseif($transaction->status == 1) Completed 
                                                @elseif($transaction->status == 2) Canceled 
                                                @else Unknown @endif
                                            </span>
                                        </td>
                                        
                                        <!-- Actions -->
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            @if ($transaction->status == 1)
                                                <button type="button"
                                                        onclick="openTransactionModal('{{ $transaction->transaction_id }}')"
                                                        class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                    <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                    Details
                                                </button>
                                            @else
                                                <a href="{{ route('rec.editTransactionForm', ['transaction_id' => $transaction->transaction_id]) }}" 
                                                   class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                    <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                    Update
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        
                        <!-- Pagination -->
                        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                            {{ $transactions->links() }}
                        </div>
                    @else
                        <div class="py-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No transactions found</h3>
                            <p class="mt-1 text-sm text-gray-500">No transactions match your current filters.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Transaction Modal -->
    <div id="transactionModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <!-- Modal panel -->
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-5xl sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="absolute top-0 right-0 pt-4 pr-4">
                        <button type="button" onclick="closeTransactionModal()" class="bg-white rounded-md text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <span class="sr-only">Close</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    
                    <!-- Modal content will be loaded here -->
                    <div id="transactionModalContent" class="p-6">
                        <div class="flex items-center justify-center">
                            <svg class="animate-spin h-10 w-10 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <p class="ml-3 text-lg font-medium text-gray-700">Loading transaction details...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Reset Filters function
        function resetFilters() {
            // Redirect to the base URL without query parameters
            window.location.href = "{{ route('owner.Newtransactions', ['owner_id' => $owner->owner_id]) }}";
        }
        
        function submitForm() {
            document.getElementById('filterForm').submit();
        }
        
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
                <div class="flex items-center justify-center">
                    <svg class="animate-spin h-10 w-10 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <p class="ml-3 text-lg font-medium text-gray-700">Loading transaction details...</p>
                </div>
            `;
        }
    </script>
</x-app-layout>
