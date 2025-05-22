<x-app-layout>
    <div class="min-h-screen">
        <div class="max-w-[95%] mx-auto py-8">
            <!-- Alert Messages -->
            @if (session()->has('message'))
                <div class="mb-4 flex items-center p-4 bg-green-50 border-l-4 border-green-500 rounded-r-lg" role="alert">
                    <svg class="h-5 w-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-green-800">{{ session('message') }}</span>
                </div>
            @endif

            <!-- Page Header -->
            <div class="mx-auto flex flex-col max-w-sm items-center gap-x-4">
                <img src="{{ asset('assets/logo2.png') }}" alt="logo" class="header-logo  w-16">
                <h1 class="text-3xl font-bold text-gray-900">
                    Animals Management
                </h1>
                <p class="text-sm text-gray-500">
                    Manage all animals in the system
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center space-x-3">
                    <a href="{{ route('animals.add-animal-form') }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Add Animal
                    </a>
                    <a href="{{ route('admin-animals') }}" class="inline-flex items-center px-4 py-2 bg-white text-gray-700 text-sm font-medium rounded-lg border border-gray-300 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Reset Filters
                    </a>
                </div>
            </div>

            <!-- Filters Section -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 mb-6">
                <form method="GET" action="{{ route('admin-animals') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-6 gap-4" id="filterForm">
                    <!-- Search -->
                    <div class="col-span-1 sm:col-span-2 xl:col-span-2">
                        <label class="block text-xs font-medium text-gray-700 mb-1">Search</label>
                        <div class="relative">
                            <input type="text" 
                                   name="search" 
                                   value="{{ request('search') }}" 
                                   class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                   placeholder="Search animals..." 
                                   id="searchInput">
                            <svg class="absolute left-3 top-2.5 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                    </div>

                    <!-- Species and Breed Filter Group -->
                    <div class="col-span-1 sm:col-span-2 lg:col-span-2">
                        <div class="flex flex-col space-y-3">
                            <!-- Species Filter -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Species</label>
                                <select name="species_id" 
                                        class="w-full border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                        id="speciesFilter">
                                    <option value="">All Species</option>
                                    @foreach($species as $specie)
                                        <option value="{{ $specie->id }}" {{ request('species_id') == $specie->id ? 'selected' : '' }}>{{ $specie->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Breed Filter -->
                            <div id="breedFilterContainer" style="{{ request('species_id') ? '' : 'display: none;' }}">
                                <label class="block text-xs font-medium text-gray-700 mb-1">Breed</label>
                                <select name="breed_id" 
                                        class="w-full border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                        id="breedFilter">
                                    <option value="">All Breeds</option>
                                    @foreach($breeds as $breed)
                                        @if(!request('species_id') || $breed->species_id == request('species_id'))
                                            <option value="{{ $breed->id }}" {{ request('breed_id') == $breed->id ? 'selected' : '' }}>{{ $breed->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Owner Filter -->
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Owner</label>
                        <select name="owner_id" 
                                class="w-full border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                id="ownerFilter">
                            <option value="">All Owners</option>
                            @foreach($owners as $owner)
                                <option value="{{ $owner->owner_id }}" {{ request('owner_id') == $owner->owner_id ? 'selected' : '' }}>{{ $owner->user->complete_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Date Range -->
                    <div class="col-span-1">
                        <label class="block text-xs font-medium text-gray-700 mb-1">Date Range</label>
                        <div class="grid grid-cols-2 gap-2">
                            <input type="date" 
                                   name="fromDate" 
                                   id="fromDateFilter"
                                   class="block w-full border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500" 
                                   value="{{ request('fromDate') }}"
                                   placeholder="From date">
                            <input type="date" 
                                   name="toDate" 
                                   id="toDateFilter"
                                   class="block w-full border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500" 
                                   value="{{ request('toDate') }}"
                                   placeholder="To date">
                        </div>
                    </div>
                </form>
            </div>

            <!-- Animals Table -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr class="bg-gray-50">
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Animal</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Owner Info</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Species & Breed</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Latest Veterinarian</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Latest Transaction</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($animals as $animal)
                                <tr class="hover:bg-gray-50 transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <img class="h-10 w-10 rounded-full object-cover" 
                                                     src="{{ asset($animal->photo_front ? 'storage/' . $animal->photo_front : 'assets/default-avatar.png') }}" 
                                                     alt="Animal Photo">
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-blue-900">
                                                    <a href="{{ route('animals.profile', ['animal_id' => $animal->animal_id]) }}" 
                                                       class="hover:text-green-600 transition-colors duration-200">
                                                        {{ $animal->name }}
                                                    </a>
                                                </div>
                                                <div class="text-xs text-gray-500">
                                                    Created {{ $animal->created_at->format('m/d/Y') }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-blue-900">
                                            <a href="{{ route('owners.profile-owner', ['owner_id' => $animal->owner->owner_id]) }}" 
                                               class="hover:text-green-600 transition-colors duration-200">
                                                {{ $animal->owner->user->complete_name }}
                                            </a>
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ $animal->owner->user->contact_no }}<br>
                                            {{ $animal->owner->user->address && $animal->owner->user->address->barangay ? $animal->owner->user->address->barangay->barangay_name : 'No Barangay Available' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $animal->species->name ?? 'Species not specified' }}</div>
                                        <div class="text-xs text-gray-500">{{ $animal->breed->name ?? 'Breed not specified' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($animal->transactions->isNotEmpty())
                                            @php
                                                $latestTransaction = $animal->transactions->sortByDesc('created_at')->first();
                                            @endphp
                                            @if($latestTransaction && $latestTransaction->vet)
                                                <div class="text-sm text-blue-900">
                                                    <a href="{{ route('admin.veterinarian.profile', $latestTransaction->vet->user_id) }}" 
                                                       class="hover:text-green-600 transition-colors duration-200">
                                                        {{ $latestTransaction->vet->complete_name }}
                                                    </a>
                                                </div>
                                                <div class="text-xs text-gray-500">{{ $latestTransaction->vet->contact_no }}</div>
                                                @if($latestTransaction->technician)
                                                    <div class="text-xs text-gray-500 mt-1">
                                                        Tech: {{ $latestTransaction->technician->full_name }}
                                                    </div>
                                                @endif
                                            @else
                                                <span class="text-sm text-gray-500">No veterinarian assigned</span>
                                            @endif
                                        @else
                                            <span class="text-sm text-gray-500">No transactions</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($animal->transactions->isNotEmpty())
                                            @php
                                                $latestTransaction = $animal->transactions->sortByDesc('created_at')->first();
                                            @endphp
                                            <div class="text-sm text-gray-900">
                                                @if($latestTransaction->transactionSubtype && $latestTransaction->transactionSubtype->id == 8)
                                                    {{ $latestTransaction->transactionSubtype->subtype_name }}
                                                    <div class="text-xs text-gray-500">
                                                        {{ $latestTransaction->vaccine ? $latestTransaction->vaccine->vaccine_name : 'No Vaccine Selected' }}
                                                    </div>
                                                @else
                                                    {{ $latestTransaction->transactionSubtype ? $latestTransaction->transactionSubtype->subtype_name : 'N/A' }}
                                                @endif
                                            </div>
                                            <span class="inline-flex mt-1 items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                {{ $latestTransaction->status === 0 ? 'bg-yellow-100 text-yellow-800' : 
                                                   ($latestTransaction->status === 1 ? 'bg-green-100 text-green-800' : 
                                                   'bg-red-100 text-red-800') }}">
                                                {{ ['Pending', 'Completed', 'Canceled'][$latestTransaction->status] ?? 'Unknown' }}
                                            </span>
                                        @else
                                            <span class="text-sm text-gray-500">No transactions</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ $animal->is_vaccinated == 1 ? 'bg-green-100 text-green-800' : 
                                               ($animal->is_vaccinated == 2 ? 'bg-gray-100 text-gray-800' : 
                                               'bg-red-100 text-red-800') }}">
                                            {{ $animal->is_vaccinated == 1 ? 'Vaccinated' : 
                                               ($animal->is_vaccinated == 2 ? 'Not Required' : 'Not Vaccinated') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('admin-animals.edit', ['animal_id' => $animal->animal_id]) }}" 
                                               class="text-blue-600 hover:text-blue-900 transition-colors duration-200"
                                               title="Edit Animal">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </a>

                                            <form id="deleteAnimalForm-{{ $animal->animal_id }}" 
                                                  action="{{ route('animals.delete', ['animal_id' => $animal->animal_id]) }}" 
                                                  method="POST" 
                                                  class="inline-block"
                                                  onsubmit="return confirmAnimalDelete('{{ $animal->animal_id }}', '{{ $animal->name }}')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="text-red-600 hover:text-red-900 transition-colors duration-200"
                                                        title="Delete Animal">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-10 text-center">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                                            </svg>
                                            <p class="text-gray-500 text-sm">No animals found</p>
                                            <p class="text-gray-400 text-xs mt-1">Try adjusting your search filters</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            <div class="mt-5">
                {{ $animals->appends(request()->query())->links() }}
            </div>
        </div>
    </div>

    <!-- Delete Animal Confirmation Modal -->
    <div id="deleteAnimalModal" class="fixed inset-0 bg-gray-900/70 backdrop-blur-sm flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg shadow-xl p-6 max-w-md w-full">
            <div class="text-center mb-5">
                <div class="mx-auto flex items-center justify-center h-14 w-14 rounded-full bg-red-100 mb-4">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-800">Delete Animal</h3>
                <p id="deleteAnimalMessage" class="text-sm text-gray-600 mt-2"></p>
            </div>
            
            <div class="flex space-x-3 justify-center">
                <button id="confirmAnimalDeleteBtn" type="button" class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg">
                    Delete
                </button>
                <button id="cancelAnimalDeleteBtn" type="button" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium py-2 px-4 rounded-lg">
                    Cancel
                </button>
            </div>
        </div>
    </div>

    <script>
        // Auto-submit form when filters change
        ['ownerFilter', 'fromDateFilter', 'toDateFilter'].forEach(function(id) {
            document.getElementById(id)?.addEventListener('change', function() {
                this.form.submit();
            });
        });
    
        // Debounce search input
        var searchTimeout = null;
    
        document.getElementById('searchInput')?.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                this.form.submit();
            }, 500);
        });

        // Load breeds based on selected species
        document.getElementById('speciesFilter')?.addEventListener('change', function() {
            const speciesId = this.value;
            const breedSelect = document.getElementById('breedFilter');
            const breedContainer = document.getElementById('breedFilterContainer');
            
            // Clear current breed options
            breedSelect.innerHTML = '<option value="">All Breeds</option>';
            
            if (speciesId) {
                // Show the breed dropdown
                breedContainer.style.display = 'block';
                
                // Fetch breeds for the selected species
                fetch(`/get-breeds/${speciesId}`)
                    .then(response => response.json())
                    .then(data => {
                        // Add the breed options
                        data.breeds.forEach(breed => {
                            const option = document.createElement('option');
                            option.value = breed.id;
                            option.textContent = breed.name;
                            breedSelect.appendChild(option);
                        });
                        
                        // Auto-submit the form to show all animals of the selected species
                        this.form.submit();
                    })
                    .catch(error => console.error('Error fetching breeds:', error));
            } else {
                // Hide the breed dropdown if no species is selected
                breedContainer.style.display = 'none';
                
                // If no species selected, submit to show all animals
                this.form.submit();
            }
        });
        
        // Submit form when breed is selected
        document.getElementById('breedFilter')?.addEventListener('change', function() {
            this.form.submit();
        });

        // Function to confirm animal deletion
        function confirmAnimalDelete(animalId, animalName) {
            // Prevent the default form submission
            event.preventDefault();
            
            const modal = document.getElementById('deleteAnimalModal');
            const message = document.getElementById('deleteAnimalMessage');
            
            // Set the message with stronger warning
            message.textContent = `Are you sure you want to delete ${animalName}? This action cannot be undone and will remove all associated records.`;
            
            // Store the form to submit when confirmed
            const form = document.getElementById(`deleteAnimalForm-${animalId}`);
            
            // Set up the confirm button
            document.getElementById('confirmAnimalDeleteBtn').onclick = function() {
                form.submit();
            };
            
            // Set up the cancel button
            document.getElementById('cancelAnimalDeleteBtn').onclick = closeDeleteAnimalModal;
            
            // Show the modal
            modal.classList.remove('hidden');
            
            // Close when clicking outside the modal
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeDeleteAnimalModal();
                }
            }, { once: true });
            
            // Prevent the form from submitting
            return false;
        }
        
        // Function to close the delete animal modal
        function closeDeleteAnimalModal() {
            document.getElementById('deleteAnimalModal').classList.add('hidden');
        }

        // Set up event handlers when the page loads
        document.addEventListener('DOMContentLoaded', function() {
            const deleteModal = document.getElementById('deleteAnimalModal');
            if (deleteModal) {
                deleteModal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        closeDeleteAnimalModal();
                    }
                });
            }
        });
    </script>
</x-app-layout>
