<x-app-layout>
    <!-- Main Container -->
    <div >
        <!-- Dashboard Header with Greeting -->
        <div class="bg-gradient-to-r from-green-800 to-green-600 shadow-xl">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 relative">
                <!-- Greeting in Upper Right -->
                <div class="absolute top-4 right-4 sm:right-6 lg:right-8 z-20">
                    <div class="flex items-center space-x-3">
                        @php
                            $hour = date('H');
                            $greeting = 'Good evening';
                            if($hour < 12) $greeting = 'Good morning';
                            elseif($hour < 17) $greeting = 'Good afternoon';
                        @endphp
                        <span class="text-sm text-white/90">{{ $greeting }}, {{ auth()->user()->complete_name }}</span>
                        <span class="text-xs bg-white/20 text-white py-1 px-3 rounded-full font-semibold backdrop-blur-sm">Admin</span>
                    </div>
                </div>

                <!-- Background Pattern -->
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

                <!-- Main Header Content -->
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-center pt-8">
                    <div>
                        <h1 class="text-3xl font-bold text-white">Dashboard</h1>
                        <p class="text-blue-100 mt-1">Manage transactions and monitor clinic activity</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl px-4 py-8 sm:px-6 lg:px-8 mx-auto space-y-8">
            <!-- Enhanced Statistics Section -->
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-6">
                <!-- Existing Statistics Cards with Enhanced Design -->
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white p-6 rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm font-medium text-blue-100">Total Owners</p>
                            <p class="text-3xl font-bold mt-2">{{ $totalOwners }}</p>
                            <p class="text-sm text-blue-100 mt-2">
                                @if(isset($newOwnersThisMonth))
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M12 7a1 1 0 11-2 0 1 1 0 012 0zm-1 3a1 1 0 00-1 1v3a1 1 0 102 0v-3a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $newOwnersThisMonth }} new this month
                                </span>
                                @endif
                            </p>
                        </div>
                        <div class="p-3 bg-white bg-opacity-30 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-green-500 to-green-600 text-white p-6 rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm font-medium text-green-100">Completed Transactions</p>
                            <p class="text-3xl font-bold mt-2">{{ $successfulTransactions }}</p>
                            <p class="text-sm text-green-100 mt-2">
                                @if(isset($transactionsThisMonth))
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M12 7a1 1 0 11-2 0 1 1 0 012 0zm-1 3a1 1 0 00-1 1v3a1 1 0 102 0v-3a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $transactionsThisMonth }} this month
                                </span>
                                @endif
                            </p>
                        </div>
                        <div class="p-3 bg-white bg-opacity-30 rounded-full">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 text-white p-6 rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm font-medium text-yellow-100">Total Animals</p>
                            <p class="text-3xl font-bold mt-2">{{ $totalAnimals }}</p>
                            <p class="text-sm text-yellow-100 mt-2">
                                @if(isset($newAnimalsThisMonth))
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M12 7a1 1 0 11-2 0 1 1 0 012 0zm-1 3a1 1 0 00-1 1v3a1 1 0 102 0v-3a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $newAnimalsThisMonth }} new this month
                                </span>
                                @endif
                            </p>
                        </div>
                        <div class="p-3 bg-white bg-opacity-30 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" viewBox="0 0 512 512" fill="currentColor">
                                <path d="M256 224c-79.41 0-192 122.76-192 200.25 0 34.9 26.81 55.75 71.74 55.75 48.84 0 81.09-25.08 120.26-25.08 39.51 0 71.85 25.08 120.26 25.08 44.93 0 71.74-20.85 71.74-55.75C448 346.76 335.41 224 256 224z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- New Vaccination Statistics Card -->
                <div class="bg-gradient-to-r from-purple-500 to-purple-600 text-white p-6 rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm font-medium text-purple-100">Vaccinations</p>
                            <p class="text-3xl font-bold mt-2">{{ $totalVaccinations ?? 0 }}</p>
                            <p class="text-sm text-purple-100 mt-2">
                                @if(isset($vaccinationsThisMonth))
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M12 7a1 1 0 11-2 0 1 1 0 012 0zm-1 3a1 1 0 00-1 1v3a1 1 0 102 0v-3a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $vaccinationsThisMonth }} this month
                                </span>
                                @endif
                            </p>
                        </div>
                        <div class="p-3 bg-white bg-opacity-30 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Enhanced Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6" wire:ignore>
                <!-- Left Column -->
                <div class="space-y-6">
                    <!-- Transaction Status Distribution -->
                    <div class="bg-white p-4 rounded-xl shadow-lg">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-800">Transaction Status</h3>
                            <div class="flex items-center text-sm text-gray-500">
                                <span class="mr-2">Total: {{ $pendingTransactions + $completedTransactions + $canceledTransactions }}</span>
                            </div>
                        </div>
                        <div class="h-64">
                            <canvas id="transactionStatusChart"></canvas>
                        </div>
                    </div>

                    <!-- Animal Types Distribution -->
                    <div class="bg-white p-4 rounded-xl shadow-lg">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-800">Species Distribution</h3>
                            <div class="flex items-center text-sm text-gray-500">
                                <span class="mr-2">Total: {{ array_sum($animalTypeCounts) }}</span>
                            </div>
                        </div>
                        <div class="h-64">
                            <canvas id="animalTypesChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-6">
                    <!-- Vaccination Statistics -->
                    <div class="bg-white p-4 rounded-xl shadow-lg">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-800">Vaccination Trends</h3>
                            <div class="flex items-center space-x-2">
                                <form method="GET" action="{{ route('admin-dashboard') }}" class="flex items-center">
                                    <select name="period" class="text-sm p-1.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" onchange="this.form.submit()">
                                        <option value="weekly" {{ $period === 'weekly' ? 'selected' : '' }}>Weekly</option>
                                        <option value="monthly" {{ $period === 'monthly' ? 'selected' : '' }}>Monthly</option>
                                        <option value="yearly" {{ $period === 'yearly' ? 'selected' : '' }}>Yearly</option>
                                    </select>
                                </form>
                            </div>
                        </div>
                        <div class="h-64">
                            <canvas id="vaccinationChart"></canvas>
                        </div>
                        <div class="mt-4 grid grid-cols-3 gap-3">
                            <div class="bg-purple-50 p-3 rounded-lg">
                                <p class="text-xs font-semibold text-purple-800">Total</p>
                                <p class="text-lg font-bold text-purple-600">{{ $totalVaccinations }}</p>
                            </div>
                            <div class="bg-purple-50 p-3 rounded-lg">
                                <p class="text-xs font-semibold text-purple-800">This Month</p>
                                <p class="text-lg font-bold text-purple-600">{{ $vaccinationsThisMonth }}</p>
                            </div>
                            <div class="bg-purple-50 p-3 rounded-lg">
                                <p class="text-xs font-semibold text-purple-800">Avg/{{ $period }}</p>
                                <p class="text-lg font-bold text-purple-600">
                                    {{ count($vaccinationCounts) > 0 ? round(array_sum($vaccinationCounts) / count($vaccinationCounts), 1) : 0 }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Statistics Overview -->
                    <div class="bg-white p-4 rounded-xl shadow-lg">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-800">Overview</h3>
                            <div class="flex items-center text-sm text-gray-500">
                                <span>Monthly Summary</span>
                            </div>
                        </div>
                        <div class="h-64">
                            <canvas id="statisticsChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Species-wise Vaccination Distribution -->
            <div class="bg-white p-4 rounded-xl shadow-lg mt-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Vaccinations by Species</h3>
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
                    @foreach($vaccinationsBySpecies as $species)
                        <div class="bg-purple-50 p-3 rounded-lg">
                            <h4 class="text-sm font-semibold text-purple-800">{{ $species->name }}</h4>
                            <p class="text-xl font-bold text-purple-600">{{ $species->animals_count }}</p>
                        </div>
                    @endforeach
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
                <div class="bg-white p-6 rounded-2xl shadow-md space-y-6">
                    <div class="flex justify-between items-center mb-4">
                        <a href="{{ route('admin-dashboard') }}" 
                           class="px-4 py-2 bg-gray-500 text-white rounded-xl hover:bg-gray-600 transition-all duration-300">
                            Reset Filters
                        </a>
                    </div>

                    <!-- Enhanced Filters -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Existing Filters -->
                        <form method="GET" action="{{ route('admin-dashboard') }}" class="space-y-4">
                            <!-- Status Filter -->
                            <select name="status" class="w-full p-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-300">
                                <option value="" {{ request('status') === null ? 'selected' : '' }}>All Status</option>
                                @foreach($statuses as $key => $status)
                                    <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>
                                        {{ $status }}
                                    </option>
                                @endforeach
                            </select>

                            <!-- Barangay Filter -->
                            <select name="barangay" class="w-full p-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-300">
                                <option value="">All Barangays</option>
                                @foreach($barangays as $barangay)
                                    <option value="{{ $barangay->id }}" {{ $barangayFilter == $barangay->id ? 'selected' : '' }}>
                                        {{ $barangay->barangay_name }}
                                    </option>
                                @endforeach
                            </select>

                            <!-- Show Unvaccinated Checkbox -->
                            <div class="flex items-center space-x-2">
                                <input type="checkbox" name="show_unvaccinated" id="show_unvaccinated" 
                                       class="rounded border-gray-300 text-green-600 focus:ring-green-500"
                                       {{ $showUnvaccinated ? 'checked' : '' }}>
                                <label for="show_unvaccinated" class="text-sm text-gray-700">
                                    Show Unvaccinated Animals
                                </label>
                            </div>

                            <button type="submit" class="w-full px-4 py-2 bg-green-500 text-white rounded-xl hover:bg-green-600 transition-all duration-300">
                                Apply Filters
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Barangay Vaccination Statistics -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden mt-6">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-gray-800 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Barangay Vaccination Statistics
                    </h2>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Barangay</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Animals</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vaccinated</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unvaccinated</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vaccination Rate</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($barangayStats as $stat)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $stat->barangay_name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $stat->total_animals }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-green-600">{{ $stat->vaccinated_animals }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-red-600">{{ $stat->unvaccinated_animals }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="relative w-32 h-2 bg-gray-200 rounded">
                                                <div class="absolute top-0 left-0 h-2 bg-green-500 rounded" 
                                                     style="width: {{ $stat->vaccination_rate }}%"></div>
                                            </div>
                                            <span class="ml-2 text-sm text-gray-600">{{ $stat->vaccination_rate }}%</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            @if($showUnvaccinated)
            <!-- Unvaccinated Animals List -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden mt-6">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-gray-800 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        Unvaccinated Animals
                    </h2>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Animal Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Species</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Owner</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Barangay</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($unvaccinatedAnimals as $animal)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $animal->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $animal->species->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $animal->owner->user->complete_name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $animal->owner->user->address->barangay->barangay_name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <a href="{{ route('animals.profile', ['animal_id' => $animal->animal_id]) }}" 
                                           class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:border-green-700 focus:shadow-outline-green active:bg-green-700 transition duration-150 ease-in-out">
                                            View Profile
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

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
                                        <img src="{{ $transaction->owner->user->profile_image ? asset('storage/' . $transaction->owner->user->profile_image) : 
                                                  ($transaction->owner->user->gender === 'Female' ? asset('assets/female-default.png') : asset('assets/male-default.png')) }}" 
                                             alt="{{ $transaction->owner->user->complete_name }}" 
                                             class="w-10 h-10 rounded-full object-cover hover:scale-105 transition-all duration-300">
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        <a href="{{ route('owners.profile-owner', $transaction->owner->owner_id) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                            {{ $transaction->owner->user->complete_name ?? 'N/A' }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4">
                                        <a href="{{ route('animals.profile', ['animal_id' => $transaction->animal->animal_id]) }}" 
                                        class="text-sm font-medium text-blue-600 hover:text-blue-800 hover:underline">
                                            <strong>{{ $transaction->animal->name ?? 'N/A' }}</strong>
                                        </a>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($transaction->vet)
                                            <a href="{{ route('admin.veterinarian.profile', $transaction->vet->user_id) }}" 
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

    <!-- Add Chart.js Library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        let charts = {
            status: null,
            stats: null,
            vaccination: null,
            animalTypes: null
        };

        function destroyCharts() {
            Object.values(charts).forEach(chart => {
                if (chart) {
                    chart.destroy();
                }
            });
            charts = {
                status: null,
                stats: null,
                vaccination: null,
                animalTypes: null
            };
        }

        function createCharts() {
            if (!document.getElementById('transactionStatusChart') || 
                !document.getElementById('statisticsChart') || 
                !document.getElementById('vaccinationChart') || 
                !document.getElementById('animalTypesChart')) {
                console.log('Chart elements not found, waiting...');
                return;
            }

            destroyCharts();

            try {
                // Transaction Status Chart
                charts.status = new Chart(
                    document.getElementById('transactionStatusChart').getContext('2d'),
                    {
                        type: 'doughnut',
                        data: {
                            labels: ['Pending', 'Completed', 'Canceled'],
                            datasets: [{
                                data: [{{ $pendingTransactions }}, {{ $completedTransactions }}, {{ $canceledTransactions }}],
                                backgroundColor: ['#FCD34D', '#34D399', '#F87171']
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: {
                                        boxWidth: 12,
                                        padding: 15
                                    }
                                }
                            },
                            cutout: '65%'
                        }
                    }
                );

                // Vaccination Chart
                charts.vaccination = new Chart(
                    document.getElementById('vaccinationChart').getContext('2d'),
                    {
                        type: 'line',
                        data: {
                            labels: {!! json_encode($vaccinationLabels) !!},
                            datasets: [{
                                data: {!! json_encode($vaccinationCounts) !!},
                                borderColor: 'rgb(147, 51, 234)',
                                backgroundColor: 'rgba(147, 51, 234, 0.1)',
                                tension: 0.3,
                                fill: true
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    mode: 'index',
                                    intersect: false,
                                    backgroundColor: 'rgba(255, 255, 255, 0.9)',
                                    titleColor: '#1F2937',
                                    bodyColor: '#1F2937',
                                    borderColor: '#E5E7EB',
                                    borderWidth: 1,
                                    padding: 10,
                                    displayColors: false,
                                    callbacks: {
                                        label: function(context) {
                                            return `Vaccinations: ${context.parsed.y}`;
                                        }
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        stepSize: 1,
                                        callback: function(value) {
                                            if (Math.floor(value) === value) return value;
                                        }
                                    }
                                },
                                x: {
                                    grid: {
                                        display: false
                                    },
                                    ticks: {
                                        maxRotation: 45,
                                        minRotation: 45
                                    }
                                }
                            }
                        }
                    }
                );

                // Statistics Overview Chart
                charts.stats = new Chart(
                    document.getElementById('statisticsChart').getContext('2d'),
                    {
                        type: 'bar',
                        data: {
                            labels: ['Owners', 'Transactions', 'Animals', 'Vaccinations'],
                            datasets: [{
                                data: [
                                    {{ $totalOwners }},
                                    {{ $successfulTransactions }},
                                    {{ $totalAnimals }},
                                    {{ $totalVaccinations ?? 0 }}
                                ],
                                backgroundColor: [
                                    'rgba(59, 130, 246, 0.7)',
                                    'rgba(16, 185, 129, 0.7)',
                                    'rgba(245, 158, 11, 0.7)',
                                    'rgba(147, 51, 234, 0.7)'
                                ]
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true
                                },
                                x: {
                                    grid: {
                                        display: false
                                    }
                                }
                            }
                        }
                    }
                );

                // Animal Types Chart
                charts.animalTypes = new Chart(
                    document.getElementById('animalTypesChart').getContext('2d'),
                    {
                        type: 'pie',
                        data: {
                            labels: {!! json_encode($animalTypes) !!},
                            datasets: [{
                                data: {!! json_encode($animalTypeCounts) !!},
                                backgroundColor: [
                                    'rgba(59, 130, 246, 0.7)',
                                    'rgba(16, 185, 129, 0.7)',
                                    'rgba(245, 158, 11, 0.7)',
                                    'rgba(147, 51, 234, 0.7)',
                                    'rgba(239, 68, 68, 0.7)',
                                    'rgba(217, 70, 239, 0.7)'
                                ]
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: {
                                        boxWidth: 12,
                                        padding: 15
                                    }
                                }
                            }
                        }
                    }
                );

                console.log('All charts created successfully');
            } catch (error) {
                console.error('Error creating charts:', error);
            }
        }

        // Create charts when page loads
        document.addEventListener('DOMContentLoaded', createCharts);
        document.addEventListener('livewire:navigating', destroyCharts);
        document.addEventListener('livewire:navigated', createCharts);
        document.addEventListener('livewire:update', createCharts);

        // Additional check for Alpine.js initialization
        if (window.Alpine) {
            window.Alpine.nextTick(createCharts);
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