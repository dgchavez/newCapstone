<x-app-layout>
    <!-- Main Container -->
        <!-- Navigation Bar with Greeting -->
        <nav class="bg-white border-b border-gray-200 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-end h-16">
                    <div class="flex items-center">
                        @php
                            $hour = date('H');
                            $greeting = 'Good evening';
                            if($hour < 12) $greeting = 'Good morning';
                            elseif($hour < 17) $greeting = 'Good afternoon';
                        @endphp
                        <span class="text-sm text-gray-600 mr-4">{{ $greeting }}, {{ auth()->user()->complete_name }}</span>
                        <span class="text-sm bg-yellow-100 text-yellow-800 py-1 px-3 rounded-full font-semibold">Receptionist</span>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Dashboard Header -->
        <div class="bg-gradient-to-r from-green-800 to-green-600 shadow-xl">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 relative">
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
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold text-white">Dashboard</h1>
                        <p class="text-blue-100 mt-1">Manage transactions and monitor clinic activity</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl px-4 py-8 sm:px-6 lg:px-8 mx-auto space-y-8">
            <!-- Statistics Section -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div class="bg-white rounded-xl shadow-lg overflow-hidden border-b-4 border-blue-500 hover:shadow-xl transition-all duration-300">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Owners</p>
                                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalOwners }}</p>
                            </div>
                            <div class="p-3 bg-blue-100 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="#" class="text-sm text-blue-600 hover:text-blue-800 font-medium">Total number of Owners </a>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg overflow-hidden border-b-4 border-green-500 hover:shadow-xl transition-all duration-300">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Completed Transactions</p>
                                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $successfulTransactions }}</p>
                            </div>
                            <div class="p-3 bg-green-100 rounded-full">
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="#" class="text-sm text-green-600 hover:text-green-800 font-medium">Total number of Transaction</a>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg overflow-hidden border-b-4 border-yellow-500 hover:shadow-xl transition-all duration-300">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Animals</p>
                                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalAnimals }}</p>
                            </div>
                            <div class="p-3 bg-yellow-100 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-yellow-600" viewBox="0 0 512 512" fill="currentColor">
                                    <path d="M256 224c-79.41 0-192 122.76-192 200.25 0 34.9 26.81 55.75 71.74 55.75 48.84 0 81.09-25.08 120.26-25.08 39.51 0 71.85 25.08 120.26 25.08 44.93 0 71.74-20.85 71.74-55.75C448 346.76 335.41 224 256 224zm-147.28-12.61c-10.4-34.65-42.44-57.09-71.56-50.13-29.12 6.96-44.29 40.69-33.89 75.34 10.4 34.65 42.44 57.09 71.56 50.13 29.12-6.96 44.29-40.69 33.89-75.34zm84.72-20.78c30.94-8.14 46.42-49.94 34.58-93.36s-46.52-72.01-77.46-63.87-46.42 49.94-34.58 93.36c11.84 43.42 46.53 72.02 77.46 63.87zm281.39-29.34c-29.12-6.96-61.15 15.48-71.56 50.13-10.4 34.65 4.77 68.38 33.89 75.34 29.12 6.96 61.15-15.48 71.56-50.13 10.4-34.65-4.77-68.38-33.89-75.34zm-156.27 29.34c30.94 8.14 65.62-20.45 77.46-63.87 11.84-43.42-3.64-85.21-34.58-93.36s-65.62 20.45-77.46 63.87c-11.84 43.42 3.64 85.22 34.58 93.36z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="#" class="text-sm text-yellow-600 hover:text-yellow-800 font-medium">Total number of Animals</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters and Search Section -->
            <div class="bg-white rounded-xl shadow-lg p-6 border-t-4 border-green-500">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Filters & Search
                </h2>
                <div class="flex flex-col lg:flex-row justify-between gap-6">
                    <!-- Search Input -->
                    <div class="flex-1">
                        <form method="GET" action="{{ route('receptionist-dashboard') }}" class="flex gap-2">
                            <input name="search" value="{{ request('search') }}" 
                                   class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-300"
                                   placeholder="Search by Owner or Animal">
                            <button type="submit" class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-all duration-300 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                Search
                            </button>
                        </form>
                    </div>

                    <!-- Filters -->
                    <div class="flex flex-wrap gap-4">
                        <!-- Status Filter -->
                        <form method="GET" action="{{ route('receptionist-dashboard') }}">
                            <select name="status" class="p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-300" onchange="this.form.submit()">
                                <option value="" {{ request('status') === null || request('status') === '' ? 'selected' : '' }}>All Status</option>
                                @foreach($statuses as $key => $status)
                                    <option value="{{ $key }}" {{ request('status') !== null && request('status') !== '' && (string)request('status') === (string)$key ? 'selected' : '' }}>
                                        {{ $status }}
                                    </option>
                                @endforeach
                            </select>
                        </form>

                        <!-- Veterinarian Filter -->
                        <form method="GET" action="{{ route('receptionist-dashboard') }}">
                            <select name="veterinarian" class="p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-300" onchange="this.form.submit()">
                                <option value="">All Veterinarians</option>
                                @foreach($veterinarians as $veterinarian)
                                    <option value="{{ $veterinarian->user_id }}" 
                                            {{ request('veterinarian') == $veterinarian->user_id ? 'selected' : '' }}>
                                        {{ $veterinarian->complete_name }}
                                    </option>
                                @endforeach
                            </select>
                        </form>

                        <!-- Technician Filter -->
                        <form method="GET" action="{{ route('receptionist-dashboard') }}">
                            <select name="technician" class="p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-300" onchange="this.form.submit()">
                                <option value="">All Technicians</option>
                                @foreach($technicians as $technician)
                                    <option value="{{ $technician->technician_id }}" 
                                            {{ request('technician') == $technician->technician_id ? 'selected' : '' }}>
                                        {{ $technician->full_name }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Recent Transactions Section -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                    <h2 class="text-xl font-bold text-gray-800 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        Recent Transactions
                    </h2>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left text-gray-700">
                        <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                            <tr>
                                <th class="px-6 py-4 rounded-tl-lg"></th>
                                <th class="px-6 py-4">Owner</th>
                                <th class="px-6 py-4">Animal</th>
                                <th class="px-6 py-4">Veterinarian</th>
                                <th class="px-6 py-4">Technician</th>
                                <th class="px-6 py-4">Transaction Type</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4 rounded-tr-lg">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentTransactions as $transaction)
                                <tr class="border-b hover:bg-gray-50 transition-colors duration-200">
                                    <td class="px-6 py-4">
                                        @if($transaction->owner->user->profile_image)
                                            <img src="{{ asset('storage/' . $transaction->owner->user->profile_image) }}" alt="Profile Image" class="w-10 h-10 rounded-full object-cover hover:scale-105 transition-all duration-300">
                                        @else
                                            <img src="{{asset('assets/default-avatar.png') }}" class="w-10 h-10 rounded-full object-cover hover:scale-105 transition-all duration-300" alt="Profile">
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        <a href="{{ route('rec.profile-owner', $transaction->owner->owner_id) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                            {{ $transaction->owner->user->complete_name ?? 'N/A' }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4">
                                        <a href="{{ route('rec.profile', ['animal_id' => $transaction->animal->animal_id]) }}" 
                                        class="text-sm font-medium text-blue-600 hover:text-blue-800 hover:underline">
                                            <strong>{{ $transaction->animal->name ?? 'N/A' }}</strong>
                                        </a>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($transaction->vet)
                                            <a href="{{ route('rec.veterinarian.profile', $transaction->vet->user_id) }}" 
                                            class="text-blue-600 hover:text-blue-800 hover:underline font-medium">
                                                {{ $transaction->vet->complete_name }}
                                            </a>
                                        @else
                                            <span class="text-gray-500">No Veterinarian Selected</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">{{ $transaction->technician->full_name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4">
                                        {{ $transaction->transactionType->type_name ?? 'Unknown' }}
                                        @if($transaction->transactionSubtype)
                                            - {{ $transaction->transactionSubtype->subtype_name ?? 'Unknown' }}
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                            {{ $transaction->status == 0 ? 'bg-yellow-100 text-yellow-800' : 
                                            ($transaction->status == 1 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') }}">
                                            {{ $transaction->status == 0 ? 'Pending' : 
                                            ($transaction->status == 1 ? 'Completed' : 'Canceled') }}
                                        </span>
                                        
                                        @if ($transaction->status == 1)
                                            <!-- Button for Completed Transactions -->
                                            <div class="mt-2">
                                                <button type="button"
                                                        onclick="openTransactionModal('{{ $transaction->transaction_id }}')"
                                                        class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white font-semibold rounded-lg shadow-md 
                                                            transition-all duration-200 ease-in-out hover:bg-green-700 focus:outline-none focus:ring-2 
                                                            focus:ring-green-400 focus:ring-offset-2 text-xs">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                    See Details
                                                </button>
                                            </div>
                                        @endif
                                    </td>
                                    
                                    <td class="px-6 py-4">{{ $transaction->created_at->format('M d, Y') }}</td>
                                
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                                        No transactions found. Try adjusting your filters or search criteria.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $recentTransactions->appends(request()->query())->links() }}
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
            <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-5xl sm:w-full">
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
                        <svg class="animate-spin -ml-1 mr-3 h-10 w-10 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
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
                <div class="flex justify-center">
                    <svg class="animate-spin -ml-1 mr-3 h-10 w-10 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <p class="text-lg font-medium text-gray-700">Loading transaction details...</p>
                </div>
            `;
        }
    </script>
</x-app-layout>