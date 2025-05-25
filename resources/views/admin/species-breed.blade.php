<x-app-layout>
    <div class="bg-gradient-to-b from-green-50 to-white min-h-screen">
        <div class="container mx-auto px-4 py-8">
            <!-- Header Section -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-6">
                <div class="bg-gradient-to-r from-green-600 to-green-400 h-16"></div>
                <div class="px-6 py-5 -mt-1">
                    <h1 class="text-2xl font-bold text-gray-800 flex items-center">
                        <i class="fas fa-paw text-blue-500 mr-3"></i>Species and Breeds Management
                    </h1>
                </div>
            </div>

            <!-- Flex container for tables -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                <!-- Species Table -->
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-xl font-semibold text-gray-700 flex items-center">
                                <i class="fas fa-atlas text-blue-500 mr-2"></i>Species
                            </h2>
                            <a href="{{ route('species.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-200 flex items-center gap-2">
                                <i class="fas fa-plus"></i>
                                Add Species
                            </a>
                        </div>
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead>
                                    <tr class="bg-gray-50">
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Species Name</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach ($species as $specie)
                                        <tr class="hover:bg-blue-50 transition duration-200">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10 mr-3">
                                                        <div class="h-10 w-10 rounded-full 
                                                            @php
                                                                // Use the first 8 characters of MD5 hash converted to integer to get a more uniform distribution
                                                                $hash = hexdec(substr(md5($specie->name), 0, 8));
                                                                $colorIndex = $hash % 6; // Use 6 distinct colors
                                                                
                                                                // Array of distinct color classes
                                                                $colors = [
                                                                    'bg-blue-500',
                                                                    'bg-green-500',
                                                                    'bg-purple-500',
                                                                    'bg-red-500',
                                                                    'bg-yellow-500',
                                                                    'bg-indigo-500'
                                                                ];
                                                                
                                                                echo $colors[$colorIndex];
                                                            @endphp 
                                                            flex items-center justify-center text-white font-bold">
                                                            {{ strtoupper(substr($specie->name, 0, 1)) }}
                                                        </div>
                                                    </div>
                                                    <div class="text-sm font-medium text-gray-900">{{ $specie->name }}</div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <a href="{{ route('species.edit', $specie) }}" class="bg-blue-100 text-blue-700 hover:bg-blue-200 px-3 py-1 rounded-lg mr-2 inline-flex items-center">
                                                    <i class="fas fa-edit mr-1"></i> Edit
                                                </a>
                                            
                                            </td>
                                        </tr>
                                    @endforeach
                                    
                                    @if(count($species) == 0)
                                        <tr>
                                            <td colspan="2" class="px-6 py-8 text-center text-gray-500">
                                                No species found. Start by adding your first species.
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Breeds Table -->
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-xl font-semibold text-gray-700 flex items-center">
                                <i class="fas fa-dog text-blue-500 mr-2"></i>Breeds
                            </h2>
                            <a href="{{ route('breeds.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-200 flex items-center gap-2">
                                <i class="fas fa-plus"></i>
                                Add Breed
                            </a>
                        </div>

                        <!-- Filter Form -->
                        <form id="filterForm" action="{{ route('species.breed') }}" method="GET" class="mb-6 bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-search text-gray-400"></i>
                                    </div>
                                    <input type="text" name="name" id="nameFilter" 
                                        placeholder="Search by breed name" 
                                        value="{{ request()->input('name') }}" 
                                        class="pl-10 w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-filter text-gray-400"></i>
                                    </div>
                                    <select name="species_id" id="speciesFilter" 
                                        class="pl-10 w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">All Species</option>
                                        @foreach ($species as $specie)
                                            <option value="{{ $specie->id }}" {{ request()->input('species_id') == $specie->id ? 'selected' : '' }}>
                                                {{ $specie->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="relative">
                                    <button type="button" id="resetFiltersBtn"
                                            class="w-full px-4 py-2 bg-gray-200 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-300 focus:outline-none transition-colors duration-200 flex items-center justify-center">
                                        <i class="fas fa-undo-alt mr-2"></i>
                                        Reset Filters
                                    </button>
                                </div>
                            </div>
                        </form>

                        <!-- Breeds Table -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead>
                                    <tr class="bg-gray-50">
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Breed Name</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Species</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach ($breeds as $breed)
                                        <tr class="hover:bg-blue-50 transition duration-200">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="text-sm font-medium text-gray-900">{{ $breed->name }}</div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 py-1 text-xs inline-flex items-center rounded-full text-white
                                                    @php
                                                        // Use the same color determination as in the species list
                                                        $speciesName = $breed->species->name;
                                                        $hash = hexdec(substr(md5($speciesName), 0, 8));
                                                        $colorIndex = $hash % 6;
                                                        
                                                        $colors = [
                                                            'bg-blue-500',
                                                            'bg-green-500',
                                                            'bg-purple-500',
                                                            'bg-red-500',
                                                            'bg-yellow-500',
                                                            'bg-indigo-500'
                                                        ];
                                                        
                                                        echo $colors[$colorIndex];
                                                    @endphp">
                                                    {{ $breed->species->name }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <a href="{{ route('breeds.edit', $breed) }}" class="bg-blue-100 text-blue-700 hover:bg-blue-200 px-3 py-1 rounded-lg mr-2 inline-flex items-center">
                                                    <i class="fas fa-edit mr-1"></i> Edit
                                                </a>
                                           
                                            </td>
                                        </tr>
                                    @endforeach
                                    
                                    @if(count($breeds) == 0)
                                        <tr>
                                            <td colspan="3" class="px-6 py-8 text-center text-gray-500">
                                                No breeds found matching your filters.
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination Links -->
                        <div class="mt-6">
                            {{ $breeds->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Font Awesome CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">

    <script>
        // Function to initialize filters
        function initializeFilters() {
            // Get form elements
            const filterForm = document.getElementById('filterForm');
            const nameFilter = document.getElementById('nameFilter');
            const speciesFilter = document.getElementById('speciesFilter');
            const resetFiltersBtn = document.getElementById('resetFiltersBtn');
            
            if (!filterForm || !nameFilter || !speciesFilter || !resetFiltersBtn) {
                return; // Exit if elements don't exist
            }

            // Remove existing event listeners if any
            nameFilter.removeEventListener('input', nameFilter.inputHandler);
            speciesFilter.removeEventListener('change', speciesFilter.changeHandler);
            resetFiltersBtn.removeEventListener('click', resetFiltersBtn.clickHandler);
            
            // Debounce function
            function debounce(func, delay) {
                let timeout;
                return function() {
                    const context = this;
                    const args = arguments;
                    clearTimeout(timeout);
                    timeout = setTimeout(function() {
                        func.apply(context, args);
                    }, delay);
                };
            }

            // Create named handlers so we can remove them later if needed
            nameFilter.inputHandler = debounce(function() {
                filterForm.submit();
            }, 500);
            
            speciesFilter.changeHandler = function() {
                filterForm.submit();
            };
            
            resetFiltersBtn.clickHandler = function() {
                nameFilter.value = '';
                speciesFilter.value = '';
                filterForm.submit();
            };
            
            // Add event listeners
            nameFilter.addEventListener('input', nameFilter.inputHandler);
            speciesFilter.addEventListener('change', speciesFilter.changeHandler);
            resetFiltersBtn.addEventListener('click', resetFiltersBtn.clickHandler);
        }

        // Initialize on DOMContentLoaded
        document.addEventListener('DOMContentLoaded', initializeFilters);

        // Initialize on Livewire page updates
        document.addEventListener('livewire:navigated', initializeFilters);

        // Initialize on Turbolinks navigation (if using Turbolinks)
        document.addEventListener('turbolinks:load', initializeFilters);

        // Initialize immediately in case the page is already loaded
        initializeFilters();
    </script>
</x-app-layout>