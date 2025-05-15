<x-app-layout>
    <div class="bg-gradient-to-b from-green-50 to-white min-h-screen">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <!-- Alert Messages -->
            @if (session()->has('message'))
                <div class="mb-6 flex items-center p-4 bg-green-50 border-l-4 border-green-500 rounded-lg shadow-sm animate-fadeIn" role="alert">
                    <i class="fas fa-check-circle text-green-500 mr-3 text-lg"></i>
                    <span class="text-green-800 font-medium">{{ session('message') }}</span>
                </div>
            @endif

            <!-- Header Section -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-6">
                <div class="bg-gradient-to-r from-green-600 to-green-400 h-16"></div>
                <div class="px-6 py-5 -mt-1 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <h1 class="text-2xl font-bold text-gray-800 flex items-center">
                        <i class="fas fa-paw text-blue-500 mr-3"></i>Animals Management
                    </h1>
                    <p class="text-gray-600 mt-1 sm:mt-0">
                        Manage and monitor all animals in the veterinary system
                    </p>
                </div>
            </div>
            
            <!-- Stats Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="p-5 flex items-center">
                        <div class="flex-shrink-0 bg-blue-100 rounded-full p-3 mr-4">
                            <i class="fas fa-paw text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Total Animals</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $animals->total() }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="p-5 flex items-center">
                        <div class="flex-shrink-0 bg-green-100 rounded-full p-3 mr-4">
                            <i class="fas fa-syringe text-green-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Vaccinated</p>
                            <p class="text-2xl font-bold text-gray-900">
                                {{ $animals->where('is_vaccinated', 1)->count() }}
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="p-5 flex items-center">
                        <div class="flex-shrink-0 bg-purple-100 rounded-full p-3 mr-4">
                            <i class="fas fa-calendar-plus text-purple-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Recent Additions</p>
                            <p class="text-2xl font-bold text-gray-900">
                                {{ $animals->where('created_at', '>=', \Carbon\Carbon::now()->subDays(30))->count() }}
                            </p>
                            <p class="text-xs text-gray-500">in the last 30 days</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('animals.add-animal-form') }}" 
                       class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200 shadow-md">
                        <i class="fas fa-plus text-sm mr-2"></i>
                        Add Animal
                    </a>
                    <a href="{{ route('admin-animals') }}" 
                       class="inline-flex items-center justify-center px-4 py-2 bg-white text-gray-700 text-sm font-medium rounded-lg border border-gray-300 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200 shadow-sm">
                        <i class="fas fa-sync-alt text-sm mr-2"></i>
                        Reset Filters
                    </a>
                </div>
            </div>

            <!-- Filters Section -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h2 class="text-lg font-medium text-gray-800 flex items-center">
                        <i class="fas fa-filter text-blue-500 mr-2"></i>
                        <span>Filter Animals</span>
                    </h2>
                </div>
                <div class="p-6">
                    <form method="GET" action="{{ route('admin-animals') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4" id="filterForm">
                        <!-- Search -->
                        <div class="col-span-1 sm:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-search text-gray-400"></i>
                                </div>
                                <input type="text" 
                                       name="search" 
                                       value="{{ request('search') }}" 
                                       class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm"
                                       placeholder="Search animal name..." 
                                       id="searchInput">
                            </div>
                        </div>

                        <!-- Species and Breed Filter Group -->
                        <div class="col-span-1 sm:col-span-2 lg:col-span-2">
                            <div class="flex flex-col space-y-3">
                                <!-- Species Filter -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Species</label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-paw text-gray-400"></i>
                                        </div>
                                        <select name="species_id" 
                                                class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm appearance-none"
                                                id="speciesFilter">
                                            <option value="">All Species</option>
                                            @foreach($species as $specie)
                                                <option value="{{ $specie->id }}" {{ request('species_id') == $specie->id ? 'selected' : '' }}>{{ $specie->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                                        </div>
                                    </div>
                                </div>

                                <!-- Breed Filter -->
                                <div id="breedFilterContainer" style="{{ request('species_id') ? '' : 'display: none;' }}">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Breed</label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-tag text-gray-400"></i>
                                        </div>
                                        <select name="breed_id" 
                                                class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm appearance-none"
                                                id="breedFilter">
                                            <option value="">All Breeds</option>
                                            @foreach($breeds as $breed)
                                                @if(!request('species_id') || $breed->species_id == request('species_id'))
                                                    <option value="{{ $breed->id }}" {{ request('breed_id') == $breed->id ? 'selected' : '' }}>{{ $breed->name }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Owner Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Owner</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-user text-gray-400"></i>
                                </div>
                                <select name="owner_id" 
                                        class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm appearance-none"
                                        id="ownerFilter">
                                    <option value="">All Owners</option>
                                    @foreach($owners as $owner)
                                        <option value="{{ $owner->owner_id }}" {{ request('owner_id') == $owner->owner_id ? 'selected' : '' }}>{{ $owner->user->complete_name }}</option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Date Range -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Registration Date</label>
                            <div class="mt-1 grid grid-cols-2 gap-2">
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-calendar-alt text-gray-400"></i>
                                    </div>
                                    <input type="date" 
                                           name="fromDate" 
                                           id="fromDateFilter"
                                           class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm" 
                                           value="{{ request('fromDate') }}"
                                           placeholder="From date">
                                </div>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-calendar-alt text-gray-400"></i>
                                    </div>
                                    <input type="date" 
                                           name="toDate" 
                                           id="toDateFilter"
                                           class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm" 
                                           value="{{ request('toDate') }}"
                                           placeholder="To date">
                                </div>
                            </div>
                        </div>

                        <!-- Filter Buttons -->
                        <!-- <div class="col-span-1 sm:col-span-2 lg:col-span-4 flex justify-end">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition duration-200 shadow-sm">
                                <i class="fas fa-search mr-2"></i>
                                Apply Filters
                            </button>
                        </div> -->
                    </form>
                </div>
            </div>

            <!-- Animals Table -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                    <h2 class="text-lg font-medium text-gray-800 flex items-center">
                        <i class="fas fa-list text-blue-500 mr-2"></i>
                        <span>Animals List</span>
                    </h2>
                    <span class="text-sm text-gray-500">{{ $animals->total() }} results found</span>
                </div>
                
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Animal</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Owner Info</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Species & Breed</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Latest Veterinarian</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Latest Transaction</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($animals as $animal)
                                    <tr class="hover:bg-blue-50 transition-colors duration-200">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10 rounded-full overflow-hidden bg-gray-100 border border-gray-200">
                                                    <img class="h-10 w-10 object-cover" 
                                                         src="{{ asset($animal->photo_front ? 'storage/' . $animal->photo_front : 'assets/default-avatar.png') }}" 
                                                         alt="{{ $animal->name }}">
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        <a href="{{ route('animals.profile', ['animal_id' => $animal->animal_id]) }}" 
                                                           class="hover:text-blue-600 transition-colors duration-200">
                                                            {{ $animal->name }}
                                                        </a>
                                                    </div>
                                                    <div class="text-xs text-gray-500">
                                                        Created {{ $animal->created_at->format('M d, Y') }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                <a href="{{ route('owners.profile-owner', ['owner_id' => $animal->owner->owner_id]) }}" 
                                                   class="hover:text-blue-600 transition-colors duration-200 flex items-center">
                                                    <i class="fas fa-user-circle text-gray-400 mr-1.5"></i>
                                                    {{ $animal->owner->user->complete_name }}
                                                </a>
                                            </div>
                                            <div class="text-xs text-gray-500 ml-5 mt-1">
                                                <div class="flex items-center">
                                                    <i class="fas fa-phone-alt text-gray-400 mr-1.5"></i>
                                                    {{ $animal->owner->user->contact_no }}
                                                </div>
                                                <div class="flex items-center mt-1">
                                                    <i class="fas fa-map-marker-alt text-gray-400 mr-1.5"></i>
                                                    {{ $animal->owner->user->address && $animal->owner->user->address->barangay ? $animal->owner->user->address->barangay->barangay_name : 'No Barangay Available' }}
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-medium rounded-full bg-blue-100 text-blue-800">
                                                    {{ $animal->species->name ?? 'Species not specified' }}
                                                </span>
                                            </div>
                                            <div class="mt-1 text-xs text-gray-500">
                                                <i class="fas fa-tag text-gray-400 mr-1.5"></i>
                                                {{ $animal->breed->name ?? 'Breed not specified' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($animal->transactions->isNotEmpty())
                                                @php
                                                    $latestTransaction = $animal->transactions->sortByDesc('created_at')->first();
                                                @endphp
                                                @if($latestTransaction && $latestTransaction->vet)
                                                    <div class="text-sm font-medium text-gray-900">
                                                        <a href="{{ route('admin.veterinarian.profile', $latestTransaction->vet->user_id) }}" 
                                                           class="hover:text-blue-600 transition-colors duration-200 flex items-center">
                                                            <i class="fas fa-user-md text-gray-400 mr-1.5"></i>
                                                            {{ $latestTransaction->vet->complete_name }}
                                                        </a>
                                                    </div>
                                                    <div class="text-xs text-gray-500 ml-5 mt-1">
                                                        <div class="flex items-center">
                                                            <i class="fas fa-phone-alt text-gray-400 mr-1.5"></i>
                                                            {{ $latestTransaction->vet->contact_no }}
                                                        </div>
                                                        @if($latestTransaction->technician)
                                                            <div class="flex items-center mt-1">
                                                                <i class="fas fa-user-nurse text-gray-400 mr-1.5"></i>
                                                                {{ $latestTransaction->technician->full_name }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                @else
                                                    <span class="text-sm text-gray-500 flex items-center">
                                                        <i class="fas fa-exclamation-circle text-gray-400 mr-1.5"></i>
                                                        No veterinarian assigned
                                                    </span>
                                                @endif
                                            @else
                                                <span class="text-sm text-gray-500 flex items-center">
                                                    <i class="fas fa-exclamation-circle text-gray-400 mr-1.5"></i>
                                                    No transactions
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($animal->transactions->isNotEmpty())
                                                @php
                                                    $latestTransaction = $animal->transactions->sortByDesc('created_at')->first();
                                                @endphp
                                                <div class="text-sm text-gray-900">
                                                    @if($latestTransaction->transactionSubtype && $latestTransaction->transactionSubtype->id == 8)
                                                        <span class="flex items-center">
                                                            <i class="fas fa-syringe text-gray-400 mr-1.5"></i>
                                                            {{ $latestTransaction->transactionSubtype->subtype_name }}
                                                        </span>
                                                        <div class="text-xs text-gray-500 ml-5 mt-1">
                                                            {{ $latestTransaction->vaccine ? $latestTransaction->vaccine->vaccine_name : 'No Vaccine Selected' }}
                                                        </div>
                                                    @else
                                                        <span class="flex items-center">
                                                            <i class="fas fa-clipboard-list text-gray-400 mr-1.5"></i>
                                                            {{ $latestTransaction->transactionSubtype ? $latestTransaction->transactionSubtype->subtype_name : 'N/A' }}
                                                        </span>
                                                    @endif
                                                </div>
                                                <span class="inline-flex mt-1 items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    {{ $latestTransaction->status === 0 ? 'bg-yellow-100 text-yellow-800' : 
                                                       ($latestTransaction->status === 1 ? 'bg-green-100 text-green-800' : 
                                                       'bg-red-100 text-red-800') }}">
                                                    {{ ['Pending', 'Completed', 'Canceled'][$latestTransaction->status] ?? 'Unknown' }}
                                                </span>
                                            @else
                                                <span class="text-sm text-gray-500 flex items-center">
                                                    <i class="fas fa-exclamation-circle text-gray-400 mr-1.5"></i>
                                                    No transactions
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                {{ $animal->is_vaccinated == 1 ? 'bg-green-100 text-green-800' : 
                                                   ($animal->is_vaccinated == 2 ? 'bg-gray-100 text-gray-800' : 
                                                   'bg-red-100 text-red-800') }}">
                                                <i class="fas {{ $animal->is_vaccinated == 1 ? 'fa-check-circle' : 
                                                              ($animal->is_vaccinated == 2 ? 'fa-ban' : 'fa-times-circle') }} mr-1"></i>
                                                {{ $animal->is_vaccinated == 1 ? 'Vaccinated' : 
                                                   ($animal->is_vaccinated == 2 ? 'Not Required' : 'Not Vaccinated') }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex justify-center gap-3">
                                                <a href="{{ route('admin-animals.edit', ['animal_id' => $animal->animal_id]) }}" 
                                                   class="text-blue-600 hover:text-blue-900 flex items-center gap-1">
                                                    <i class="fas fa-edit"></i>
                                                    <span>Edit</span>
                                                </a>

                                                <form action="{{ route('animals.delete', ['animal_id' => $animal->animal_id]) }}" 
                                                      method="POST" 
                                                      class="inline-block"
                                                      onsubmit="return confirm('Are you sure you want to delete this animal?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="text-red-600 hover:text-red-900 flex items-center gap-1">
                                                        <i class="fas fa-trash-alt"></i>
                                                        <span>Delete</span>
                                                    </button>
                                                </form>
                                                
                                                <a href="{{ route('animals.profile', ['animal_id' => $animal->animal_id]) }}" 
                                                   class="text-green-600 hover:text-green-900 flex items-center gap-1">
                                                    <i class="fas fa-eye"></i>
                                                    <span>View</span>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-8 whitespace-nowrap text-sm text-gray-500 text-center">
                                            <div class="flex flex-col items-center">
                                                <i class="fas fa-paw text-gray-400 text-3xl mb-2"></i>
                                                <p>No animals found.</p>
                                                <p class="text-gray-400 text-xs mt-1">Try adjusting your search filters or adding a new animal</p>
                                                <a href="{{ route('animals.add-animal-form') }}" class="mt-4 text-blue-600 hover:text-blue-800 flex items-center">
                                                    <i class="fas fa-plus mr-1"></i>
                                                    Add your first animal
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                <!-- Pagination -->
                <div class="mt-5">
                    {{ $animals->appends(request()->query())->links() }}
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
        </script>
    </x-app-layout>
