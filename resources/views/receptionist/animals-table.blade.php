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
                        <a href="{{ route('rec.add-animal-form') }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Add Animal
                        </a>
                        <a href="{{ route('rec-animals') }}" class="inline-flex items-center px-4 py-2 bg-white text-gray-700 text-sm font-medium rounded-lg border border-gray-300 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Reset Filters
                        </a>
                    </div>
                </div>

                <!-- Filters Section -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 mb-6">
                    <form method="GET" action="{{ route('rec-animals') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-6 gap-4" id="filterForm">
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

                        <!-- Life Status Filter -->
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Life Status</label>
                            <select name="isAlive" 
                                    class="w-full border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                    id="lifeStatusFilter">
                                <option value="">All Status</option>
                                <option value="1" {{ request('isAlive') === '1' ? 'selected' : '' }}>
                                    <span class="text-green-800">Alive</span>
                                </option>
                                <option value="0" {{ request('isAlive') === '0' ? 'selected' : '' }}>
                                    <span class="text-red-800">Deceased</span>
                                </option>
                                <option value="null" {{ request('isAlive') === 'null' ? 'selected' : '' }}>
                                    <span class="text-gray-800">Status Not Set</span>
                                </option>
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
                                                            src="{{ asset($animal->photo_front ? 'storage/' . $animal->photo_front : 'assets/animal-default.png') }}" 
                                                            alt="Animal Photo">
                                                    </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-blue-900">
                                                        <a href="{{ route('rec.profile', ['animal_id' => $animal->animal_id]) }}" 
                                                           class="hover:text-green-600 transition-colors duration-200">
                                                            {{ $animal->name }}
                                                        </a>
                                                    </div>
                                                    <!-- Vaccination Status -->
                                                    <div class="mt-1">
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                                            {{ $animal->is_vaccinated == 1 ? 'bg-green-100 text-green-800' : 
                                                               ($animal->is_vaccinated == 2 ? 'bg-gray-100 text-gray-800' : 
                                                               'bg-yellow-100 text-yellow-800') }}">
                                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                @if($animal->is_vaccinated == 1)
                                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                                                                @elseif($animal->is_vaccinated == 2)
                                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM7 9a1 1 0 000 2h6a1 1 0 100-2H7z"/>
                                                                @else
                                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v4a1 1 0 102 0V7zm0 6a1 1 0 10-2 0 1 1 0 102 0z"/>
                                                                @endif
                                                            </svg>
                                                            {{ $animal->is_vaccinated == 1 ? 'Vaccinated' : 
                                                               ($animal->is_vaccinated == 2 ? 'Not Required' : 'Not Vaccinated') }}
                                                        </span>
                                                    </div>
                                                    <div class="text-xs text-gray-500 mt-1">
                                                        Created {{ $animal->created_at->format('m/d/Y') }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-blue-900">
                                                <a href="{{ route('rec.profile-owner', ['owner_id' => $animal->owner->owner_id]) }}" 
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
                                                        <a href="{{ route('rec.veterinarian.profile', $latestTransaction->vet->user_id) }}" 
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
                                                {{ $animal->isAlive === null ? 'bg-gray-100 text-gray-800' : 
                                                   ($animal->isAlive ? 'bg-green-100 text-green-800' : 
                                                   'bg-red-100 text-red-800') }}">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    @if($animal->isAlive === null)
                                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z"/>
                                                    @elseif($animal->isAlive)
                                                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"/>
                                                    @else
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"/>
                                                    @endif
                                                </svg>
                                                {{ $animal->isAlive === null ? 'Status Not Set' : 
                                                   ($animal->isAlive ? 'Alive' : 'Deceased') }}
                                                @if(!$animal->isAlive && $animal->death_date)
                                                    <span class="ml-1 text-xs text-gray-500">• {{ \Carbon\Carbon::parse($animal->death_date)->format('M d, Y') }}</span>
                                                @endif
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex items-center space-x-2">
                                                <a href="{{ route('rec-animals.edit', ['animal_id' => $animal->animal_id]) }}" 
                                                   class="text-blue-600 hover:text-blue-900 transition-colors duration-200"
                                                   title="Edit Animal">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                    </svg>
                                                </a>

                                              
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

        <script>
            // Auto-submit form when filters change
            ['ownerFilter', 'fromDateFilter', 'toDateFilter', 'lifeStatusFilter'].forEach(function(id) {
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
        </script>
    </x-app-layout>
