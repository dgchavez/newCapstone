<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Main Profile Card -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <!-- Profile Header -->
            <div class="relative h-48 bg-gradient-to-r from-green-600 to-green-800">
                <div class="absolute inset-0 bg-black/20"></div>
                <!-- Action Button -->
                <div class="absolute top-4 right-4">
                    <a href="{{ route('newanimals.edit', $animal->animal_id) }}" 
                       class="inline-flex items-center px-4 py-2 bg-white/90 backdrop-blur-sm rounded-lg text-sm font-medium text-gray-700 hover:bg-white transition-all">
                        <i class="fas fa-edit mr-2"></i>
                        Update Info
                    </a>
                     <!-- ID Card Generation Button -->
                     <div class="mt-4">
                        <a href="#"
                           onclick="openIdModal('{{ $animal->animal_id }}')"
                           class="inline-flex items-center px-4 py-2 bg-green-700 text-white rounded-md hover:bg-green-800 transition-colors duration-200 shadow-sm font-medium text-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/>
                            </svg>
                            Generate ID Card
                        </a>
                    </div>
                 
                </div>
            </div>

            <!-- Animal Info Section -->
            <div class="relative px-8 pb-8">
                <!-- Animal Photo -->
                <div class="absolute -top-16 left-8">
                    <div class="relative">
                        <img class="w-32 h-32 rounded-xl object-cover border-4 border-white shadow-lg" 
                             src="{{ $animal->photo_front ? asset('storage/' . $animal->photo_front) : asset('assets/default-avatar.png') }}" 
                             alt="{{ $animal->name }}">
                        <div class="absolute bottom-2 right-2 w-4 h-4 rounded-full 
                            {{ $animal->is_vaccinated == 1 ? 'bg-green-500' : 
                              ($animal->is_vaccinated == 0 ? 'bg-red-500' : 'bg-yellow-500') }} 
                            border-2 border-white">
                        </div>
                        
                    </div>
                </div>

                <!-- Animal Details -->
                <div class="pt-20">
                    <div class="flex justify-between items-start">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">{{ $animal->name }}</h1>
                            <div class="mt-2 flex items-center space-x-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    {{ $animal->species ? $animal->species->name : 'Species not specified' }}
                                </span>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                                    {{ $animal->breed ? $animal->breed->name : 'Breed not specified' }}
                                </span>
                            </div>
                        </div>
                    </div>
                           <!-- Photo Gallery (New) -->
                    <div class="mt-8">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Animal Photos</h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            @if($animal->photo_front)
                                <div class="relative group overflow-hidden rounded-lg shadow">
                                    <img src="{{ asset('storage/' . $animal->photo_front) }}" alt="Front view" class="w-full h-40 object-cover transition-transform duration-300 group-hover:scale-110">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-3">
                                        <span class="text-white text-sm font-medium">Front View</span>
                                    </div>
                                </div>
                            @endif
                            
                            @if($animal->photo_back)
                                <div class="relative group overflow-hidden rounded-lg shadow">
                                    <img src="{{ asset('storage/' . $animal->photo_back) }}" alt="Back view" class="w-full h-40 object-cover transition-transform duration-300 group-hover:scale-110">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-3">
                                        <span class="text-white text-sm font-medium">Back View</span>
                                    </div>
                                </div>
                            @endif
                            
                            @if($animal->photo_left_side)
                                <div class="relative group overflow-hidden rounded-lg shadow">
                                    <img src="{{ asset('storage/' . $animal->photo_left_side) }}" alt="Left side view" class="w-full h-40 object-cover transition-transform duration-300 group-hover:scale-110">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-3">
                                        <span class="text-white text-sm font-medium">Left Side</span>
                                    </div>
                                </div>
                            @endif
                            
                            @if($animal->photo_right_side)
                                <div class="relative group overflow-hidden rounded-lg shadow">
                                    <img src="{{ asset('storage/' . $animal->photo_right_side) }}" alt="Right side view" class="w-full h-40 object-cover transition-transform duration-300 group-hover:scale-110">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-3">
                                        <span class="text-white text-sm font-medium">Right Side</span>
                                    </div>
                                </div>
                            @endif
                            
                            @if(!$animal->photo_front && !$animal->photo_back && !$animal->photo_left_side && !$animal->photo_right_side)
                                <div class="col-span-4 p-6 bg-gray-100 rounded-lg text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <p class="mt-2 text-sm text-gray-500">No photos available</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Info Grid -->
                    <div class="mt-8 grid grid-cols-1 md:grid-cols-4 gap-6">
                        <!-- Owner Info -->
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                            <div class="flex items-center space-x-3">
                                <div class="p-2 bg-blue-100 rounded-lg">
                                    <i class="fas fa-user text-blue-600"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Owner</p>
                                    <a href="{{ route('rec.profile-owner', ['owner_id' => $animal->owner->owner_id]) }}" 
                                       class="text-sm font-medium text-blue-600 hover:text-blue-800">
                                        {{ $animal->owner->user->complete_name }}
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Life Status -->
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                            <div class="flex items-center space-x-3">
                                <div class="p-2 rounded-lg {{ $animal->isAlive ? 'bg-green-100' : ($animal->isAlive === false ? 'bg-red-100' : 'bg-gray-100') }}">
                                    <i class="fas {{ $animal->isAlive ? 'fa-heartbeat' : ($animal->isAlive === false ? 'fa-heart-broken' : 'fa-question-circle') }} 
                                        {{ $animal->isAlive ? 'text-green-600' : ($animal->isAlive === false ? 'text-red-600' : 'text-gray-600') }}"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Life Status</p>
                                    <div class="flex items-center space-x-2">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ $animal->isAlive ? 'bg-green-100 text-green-800' : 
                                               ($animal->isAlive === false ? 'bg-red-100 text-red-800' : 
                                               'bg-gray-100 text-gray-800') }}">
                                            {{ $animal->isAlive ? 'Alive' : 
                                               ($animal->isAlive === false ? 'Deceased' : 'Status Not Set') }}
                                        </span>
                                        @if($animal->isAlive === false && $animal->death_date)
                                            <span class="text-xs text-gray-500">
                                                ({{ \Carbon\Carbon::parse($animal->death_date)->format('M d, Y') }})
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Age Info -->
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                            <div class="flex items-center space-x-3">
                                <div class="p-2 bg-green-100 rounded-lg">
                                    <i class="fas fa-birthday-cake text-green-600"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Age</p>
                                    <p class="text-sm font-medium text-gray-900">
                                        @if ($animal->birth_date)
                                            {{ $animal->birth_date->age }} years old
                                        @else
                                            Not Available
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Vaccination Status -->
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                            <div class="flex items-center space-x-3">
                                <div class="p-2 bg-yellow-100 rounded-lg">
                                    <i class="fas fa-syringe text-yellow-600"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Vaccination Status</p>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $animal->is_vaccinated == 1 ? 'bg-green-100 text-green-800' : 
                                          ($animal->is_vaccinated == 0 ? 'bg-red-100 text-red-800' : 
                                          'bg-yellow-100 text-yellow-800') }}">
                                        @if ($animal->is_vaccinated == 1)
                                            Vaccinated
                                        @elseif ($animal->is_vaccinated == 0)
                                            Not Vaccinated
                                        @else
                                            No Vaccination Required
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Details -->
                    @if($animal->medical_condition)
                        <div class="mt-6 p-4 bg-red-50 rounded-lg border border-red-100">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-exclamation-circle text-red-500"></i>
                                <span class="text-sm font-medium text-red-800">Medical Condition:</span>
                                <span class="text-sm text-red-600">{{ $animal->medical_condition }}</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Transactions Section -->
        <div class="mt-8 bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <h2 class="text-xl font-semibold text-gray-900">Transaction History</h2>
            </div>

            <!-- Filters -->
            <div class="p-6 bg-gray-50 border-b border-gray-100">
                <form id="filterForm" method="GET" action="{{ route('rec.profile', ['animal_id' => $animal->animal_id]) }}" 
                      class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Date</label>
                        <input type="date" name="search_date" value="{{ request('search_date') }}" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Transaction Type</label>
                        <select name="transaction_type" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">All Types</option>
                            @foreach($transactionTypes as $type)
                                <option value="{{ $type->id }}" {{ request('transaction_type') == $type->id ? 'selected' : '' }}>
                                    {{ $type->type_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="transaction_status" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">All Status</option>
                            <option value="0" {{ request('transaction_status') == '0' ? 'selected' : '' }}>Pending</option>
                            <option value="1" {{ request('transaction_status') == '1' ? 'selected' : '' }}>Completed</option>
                            <option value="2" {{ request('transaction_status') == '2' ? 'selected' : '' }}>Canceled</option>
                        </select>
                    </div>
                </form>
            </div>

            <!-- Transactions Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtype</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Veterinarian</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notes</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($animal->transactions as $transaction)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $transaction->created_at->format('Y-m-d') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $transaction->transactionType->type_name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $transaction->transactionSubtype->subtype_name ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $transaction->vet->complete_name ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $transaction->status == 0 ? 'bg-yellow-100 text-yellow-800' : 
                                          ($transaction->status == 1 ? 'bg-green-100 text-green-800' : 
                                          'bg-red-100 text-red-800') }}">
                                        {{ $transaction->status == 0 ? 'Pending' : 
                                           ($transaction->status == 1 ? 'Completed' : 'Canceled') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ Str::limit($transaction->details ?? 'No additional details provided.', 50) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-3">
                                        @if($transaction->status == 1)
                                            <!-- For Completed Transactions -->
                                            <a href="#" 
                                               onclick="openTransactionModal('{{ $transaction->transaction_id }}')"
                                               class="inline-flex items-center px-3 py-1 bg-green-100 text-green-700 rounded-md hover:bg-green-200 transition-colors duration-200">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                                See Details
                                            </a>
                                        @else
                                            <!-- For Pending or Cancelled Transactions -->
                                            <a href="#" 
                                               onclick="openTransactionModal('{{ $transaction->transaction_id }}')"
                                               class="inline-flex items-center px-3 py-1 bg-yellow-100 text-yellow-700 rounded-md hover:bg-yellow-200 transition-colors duration-200">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                                Pending
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                    No transactions available.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
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
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                <!-- Close button -->
                <div class="absolute top-0 right-0 pt-4 pr-4">
                    <button type="button" onclick="closeTransactionModal()" class="text-gray-400 hover:text-gray-500">
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
    
    <!-- ID Modal -->
    <div id="idModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <!-- Modal panel -->
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                <!-- Close button -->
                <div class="absolute top-0 right-0 pt-4 pr-4">
                    <button type="button" onclick="closeIdModal()" class="text-gray-400 hover:text-gray-500">
                        <span class="sr-only">Close</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <!-- Modal content will be loaded here -->
                <div id="IdModalContent" class="p-6">
                    <div class="flex justify-center">
                        <svg class="animate-spin -ml-1 mr-3 h-10 w-10 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <p class="text-lg font-medium text-gray-700">Loading ID details...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const transactionSubtypes = @json($transactionSubtypes);

        function confirmDelete() {
            return confirm('Are you sure you want to delete this transaction?');
        }

        document.addEventListener('DOMContentLoaded', function() {
            const typeSelect = document.getElementById('transaction_type_id');
            const subtypeSelect = document.getElementById('transaction_subtype_id');
            const vaccineField = document.getElementById('vaccine_field');

            typeSelect.addEventListener('change', function() {
                const selectedTypeId = this.value;
                
                // Clear and reset subtype dropdown
                subtypeSelect.innerHTML = '<option value="" selected disabled>Select Type</option>';
                
                if (selectedTypeId) {
                    const filteredSubtypes = transactionSubtypes.filter(
                        subtype => subtype.transaction_type_id == selectedTypeId
                    );
                    
                    filteredSubtypes.forEach(subtype => {
                        const option = document.createElement('option');
                        option.value = subtype.id;
                        option.textContent = subtype.subtype_name;
                        subtypeSelect.appendChild(option);
                    });
                }
            });

            subtypeSelect.addEventListener('change', function() {
                const selectedSubtypeId = this.value;
                vaccineField.classList.toggle('hidden', selectedSubtypeId != 8);
            });

            // Auto-submit filter form on change
            document.querySelectorAll('#filterForm select, #filterForm input').forEach(element => {
                element.addEventListener('change', () => document.getElementById('filterForm').submit());
            });
        });

        function openTransactionModal(transactionId) {
            // Show modal
            document.getElementById('transactionModal').classList.remove('hidden');
            
            // Fetch transaction details
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
                    console.error('Error:', error);
                });
        }

        function closeTransactionModal() {
            // Hide modal
            document.getElementById('transactionModal').classList.add('hidden');
            // Reset content to loading state
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
        // Close modal when clicking outside
        document.addEventListener('click', function(event) {
            const modal = document.getElementById('transactionModal');
            const modalContent = modal.querySelector('.inline-block');
            if (event.target === modal) {
                closeTransactionModal();
            }
        });

        function openIdModal(animalId) {
            // Show modal
            document.getElementById('idModal').classList.remove('hidden');
            
            // Fetch transaction details
            fetch(`/animal-id/${animalId}`)
                .then(response => response.text())
                .then(html => {
                    document.getElementById('IdModalContent').innerHTML = html;
                })
                .catch(error => {
                    document.getElementById('IdModalContent').innerHTML = `
                        <div class="text-center text-red-500">
                            <p>Error loading transaction details. Please try again.</p>
                        </div>
                    `;
                    console.error('Error:', error);
                });
        }

        function closeIdModal() {
            // Hide modal
            document.getElementById('idModal').classList.add('hidden');
            // Reset content to loading state
            document.getElementById('idModalContent').innerHTML = `
                <div class="flex justify-center">
                    <svg class="animate-spin -ml-1 mr-3 h-10 w-10 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <p class="text-lg font-medium text-gray-700">Loading transaction details...</p>
                </div>
            `;
        }
        // Close modal when clicking outside
        document.addEventListener('click', function(event) {
            const modal = document.getElementById('transactionModal');
            const modalContent = modal.querySelector('.inline-block');
            if (event.target === modal) {
                closeIdModal();
            }
        });
            </script>
</x-app-layout>
