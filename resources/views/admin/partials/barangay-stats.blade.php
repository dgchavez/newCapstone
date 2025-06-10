<!-- Enhanced Filters -->
<div class="p-4 bg-gray-50 border-b border-gray-200">
    <!-- Summary Cards -->
    @if(isset($barangayStats) && $barangayStats->isNotEmpty())
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        @php
            $totalAnimals = $barangayStats->sum('total_animals');
            $totalVaccinated = $barangayStats->sum('vaccinated_animals');
            $totalUnvaccinated = $barangayStats->sum('unvaccinated_animals');
            $overallCoverage = $totalAnimals > 0 ? ($totalVaccinated / $totalAnimals) * 100 : 0;
            $recentVaccinations = $barangayStats->sum('vaccinated_last_30_days');
        @endphp
        
        <!-- Total Animals Card -->
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Animals</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($totalAnimals) }}</p>
                </div>
                <div class="p-3 bg-blue-100 rounded-full">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-2">
                <p class="text-sm text-gray-600">Across {{ $barangayStats->count() }} barangays</p>
            </div>
        </div>

        <!-- Vaccination Coverage Card -->
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Overall Coverage</p>
                    <p class="text-2xl font-bold {{ $overallCoverage >= 70 ? 'text-green-600' : ($overallCoverage >= 40 ? 'text-yellow-600' : 'text-red-600') }}">
                        {{ number_format($overallCoverage, 1) }}%
                    </p>
                </div>
                <div class="p-3 rounded-full {{ $overallCoverage >= 70 ? 'bg-green-100' : ($overallCoverage >= 40 ? 'bg-yellow-100' : 'bg-red-100') }}">
                    <svg class="w-6 h-6 {{ $overallCoverage >= 70 ? 'text-green-600' : ($overallCoverage >= 40 ? 'text-yellow-600' : 'text-red-600') }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-2">
                <p class="text-sm text-gray-600">{{ number_format($totalVaccinated) }} vaccinated animals</p>
            </div>
        </div>

        <!-- Recent Activity Card -->
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Recent Vaccinations</p>
                    <p class="text-2xl font-bold text-purple-600">{{ number_format($recentVaccinations) }}</p>
                </div>
                <div class="p-3 bg-purple-100 rounded-full">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-2">
                <p class="text-sm text-gray-600">In the last 30 days</p>
            </div>
        </div>

        <!-- Attention Needed Card -->
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Need Vaccination</p>
                    <p class="text-2xl font-bold text-red-600">{{ number_format($totalUnvaccinated) }}</p>
                </div>
                <div class="p-3 bg-red-100 rounded-full">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-2">
                <p class="text-sm text-gray-600">Require immediate attention</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Filters Form -->
    <form id="barangayFilterForm" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label for="barangay" class="block text-sm font-medium text-gray-700">Barangay</label>
            <select id="barangay" name="barangay" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                <option value="">All Barangays</option>
                @foreach($barangayStats ?? [] as $stat)
                    @if(!empty($stat->barangay_name))
                        <option value="{{ $stat->barangay_name }}" {{ request('barangay') == $stat->barangay_name ? 'selected' : '' }}>
                            {{ $stat->barangay_name }}
                            @if($stat->total_animals > 0)
                                ({{ number_format($stat->total_animals) }} animals)
                            @endif
                        </option>
                    @endif
                @endforeach
            </select>
        </div>
        <div>
            <label for="dateRange" class="block text-sm font-medium text-gray-700">Date Range</label>
            <select id="dateRange" name="dateRange" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                <option value="7">Last 7 Days</option>
                <option value="30">Last 30 Days</option>
                <option value="90">Last 3 Months</option>
                <option value="180">Last 6 Months</option>
                <option value="365">Last Year</option>
                <option value="all">All Time</option>
            </select>
        </div>
        <div>
            <label for="species" class="block text-sm font-medium text-gray-700">Species</label>
            <select id="species" name="species" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                <option value="">All Species</option>
                @foreach($species ?? [] as $specie)
                    <option value="{{ $specie->id }}">{{ $specie->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="status" class="block text-sm font-medium text-gray-700">Vaccination Status</label>
            <select id="status" name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                <option value="">All Statuses</option>
                <option value="vaccinated">Vaccinated</option>
                <option value="unvaccinated">Unvaccinated</option>
            </select>
        </div>
    </form>
</div>

<div id="barangayStatsContent" class="overflow-x-auto">
    @if(isset($barangayStats) && $barangayStats->isNotEmpty())
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Barangay</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Animals</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vaccinated</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unvaccinated</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Recent Activity</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Species Present</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vaccines Used</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Coverage</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($barangayStats as $stat)
                    <tr class="hover:bg-gray-50 {{ $stat->unvaccinated_animals > 0 ? 'bg-red-50' : '' }}">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $stat->barangay_name }}</div>
                                    @if($stat->total_animals > 0)
                                        <div class="text-xs text-gray-500">
                                            Rank: {{ $loop->iteration }} of {{ $loop->count }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ number_format($stat->total_animals) }}</div>
                            <div class="text-xs text-gray-500">
                                {{ number_format(($stat->total_animals / $totalAnimals) * 100, 1) }}% of total
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-green-600 font-medium">{{ number_format($stat->vaccinated_animals) }}</div>
                            @if($stat->vaccinated_last_30_days > 0)
                                <div class="text-xs text-green-500">
                                    +{{ number_format($stat->vaccinated_last_30_days) }} in 30d
                                    <span class="text-gray-500">({{ number_format(($stat->vaccinated_last_30_days / $stat->vaccinated_animals) * 100, 1) }}%)</span>
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-red-600 font-medium">{{ number_format($stat->unvaccinated_animals) }}</div>
                            @if($stat->unvaccinated_animals > 0)
                                <div class="text-xs text-red-500">
                                    {{ number_format(($stat->unvaccinated_animals / $stat->total_animals) * 100, 1) }}% unprotected
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col space-y-1">
                                <div class="text-sm text-gray-600">
                                    Last 7d: {{ number_format($stat->vaccinated_last_7_days) }}
                                    @if($stat->vaccinated_last_7_days > 0)
                                        <span class="text-xs text-green-500">
                                            ({{ number_format(($stat->vaccinated_last_7_days / $stat->vaccinated_last_30_days) * 100, 1) }}% of 30d)
                                        </span>
                                    @endif
                                </div>
                                <div class="text-sm text-gray-600">
                                    Last 30d: {{ number_format($stat->vaccinated_last_30_days) }}
                                    @if($stat->vaccinated_last_30_days > 0)
                                        <span class="text-xs text-green-500">
                                            ({{ number_format(($stat->vaccinated_last_30_days / $stat->vaccinated_animals) * 100, 1) }}% of total)
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1">
                                @php
                                    $speciesArray = explode(',', $stat->animal_species ?? '');
                                    $speciesCount = count(array_filter($speciesArray));
                                @endphp
                                @forelse($speciesArray as $species)
                                    @if(!empty(trim($species)))
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ trim($species) }}
                                            @if(isset($stat->species_counts) && is_array($stat->species_counts))
                                                <span class="ml-1 text-blue-600">({{ $stat->species_counts[trim($species)] ?? 0 }})</span>
                                            @endif
                                        </span>
                                    @endif
                                @empty
                                    <span class="text-sm text-gray-500">No species data</span>
                                @endforelse
                                @if($speciesCount > 0)
                                    <div class="w-full mt-1 text-xs text-gray-500">
                                        {{ $speciesCount }} species present
                                    </div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1">
                                @if(isset($stat->vaccines_used))
                                    @foreach($stat->vaccines_used as $vaccine => $count)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            {{ $vaccine }}
                                            <span class="ml-1 text-green-600">({{ $count }})</span>
                                        </span>
                                    @endforeach
                                @else
                                    <span class="text-sm text-gray-500">No vaccine data</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center space-x-2">
                                @php
                                    $vaccinationRate = $stat->total_animals > 0 ? 
                                        ($stat->vaccinated_animals / $stat->total_animals) * 100 : 0;
                                @endphp
                                <div class="flex-grow">
                                    <div class="relative h-2 bg-gray-200 rounded-full">
                                        <div class="absolute top-0 left-0 h-2 rounded-full 
                                            {{ $vaccinationRate >= 70 ? 'bg-green-500' : 
                                               ($vaccinationRate >= 40 ? 'bg-yellow-500' : 'bg-red-500') }}"
                                             style="width: {{ $vaccinationRate }}%">
                                        </div>
                                    </div>
                                </div>
                                <span class="text-sm font-medium {{ 
                                    $vaccinationRate >= 70 ? 'text-green-600' : 
                                    ($vaccinationRate >= 40 ? 'text-yellow-600' : 'text-red-600') 
                                }}">
                                    {{ number_format($vaccinationRate, 1) }}%
                                </span>
                            </div>
                            <div class="mt-1 text-xs text-gray-500">
                                @if($vaccinationRate >= 70)
                                    Good coverage
                                @elseif($vaccinationRate >= 40)
                                    Needs improvement
                                @else
                                    Critical attention needed
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="text-center py-12">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No Statistics Available</h3>
            <p class="text-gray-500 mb-6">{{ $noDataMessage ?? 'No data found for the selected filters.' }}</p>
            <div class="flex justify-center space-x-4">
                <button onclick="resetFilters()" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Reset Filters
                </button>
            </div>
        </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('barangayFilterForm');
    const filters = form.querySelectorAll('select');

    filters.forEach(filter => {
        filter.addEventListener('change', () => {
            updateBarangayStats();
        });
    });
});

function resetFilters() {
    const form = document.getElementById('barangayFilterForm');
    form.reset();
    updateBarangayStats();
}

function updateBarangayStats() {
    const formData = new FormData(document.getElementById('barangayFilterForm'));
    const queryString = new URLSearchParams(formData).toString();

    // Show loading state
    document.getElementById('barangayStatsContent').innerHTML = `
        <div class="flex justify-center items-center p-8">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-green-600"></div>
            <span class="ml-2 text-gray-600">Updating statistics...</span>
        </div>
    `;

    // Add CSRF token to headers
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    // Get the base URL from meta tag or use a default
    const baseUrl = document.querySelector('meta[name="base-url"]')?.getAttribute('content') || '';

    // Fetch updated data with complete URL
    fetch(`${baseUrl}/admin/barangay-stats?${queryString}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            ...(csrfToken ? { 'X-CSRF-TOKEN': csrfToken } : {}),
            'Accept': 'text/html'
        },
        credentials: 'same-origin'
    })
    .then(async response => {
        const contentType = response.headers.get('content-type');
        if (!response.ok) {
            if (contentType && contentType.includes('application/json')) {
                const json = await response.json();
                throw new Error(json.error || 'Server error occurred');
            } else {
                throw new Error('Failed to load statistics');
            }
        }

        if (contentType && contentType.includes('application/json')) {
            const json = await response.json();
            throw new Error(json.error || 'Unexpected JSON response');
        }

        return response.text();
    })
    .then(html => {
        if (!html || html.trim() === '') {
            throw new Error('Empty response received');
        }

        try {
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = html.trim();
            
            // Look for the content
            const newContent = tempDiv.querySelector('#barangayStatsContent');
            if (!newContent) {
                console.error('Response HTML:', html);
                throw new Error('Could not find statistics content in response');
            }

            const targetElement = document.getElementById('barangayStatsContent');
            if (!targetElement) {
                throw new Error('Target element not found on page');
            }

            targetElement.innerHTML = newContent.innerHTML;
        } catch (error) {
            console.error('Error processing response:', error);
            throw new Error('Failed to process server response');
        }
    })
    .catch(error => {
        console.error('Error details:', error);
        document.getElementById('barangayStatsContent').innerHTML = `
            <div class="text-center py-12">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-red-100 mb-4">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Failed to Update Statistics</h3>
                <p class="text-gray-500 mb-6">${error.message || 'An unexpected error occurred'}</p>
                <div class="flex justify-center space-x-4">
                    <button onclick="updateBarangayStats()" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Try Again
                    </button>
                    <button onclick="resetFilters()" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        Reset Filters
                    </button>
                </div>
            </div>
        `;
    });
}
</script>

@push('scripts')
<script>
    // Ensure we have the CSRF token meta tag
    if (!document.querySelector('meta[name="csrf-token"]')) {
        const meta = document.createElement('meta');
        meta.name = 'csrf-token';
        meta.content = '{{ csrf_token() }}';
        document.head.appendChild(meta);
    }

    // Add base URL meta tag if not present
    if (!document.querySelector('meta[name="base-url"]')) {
        const meta = document.createElement('meta');
        meta.name = 'base-url';
        meta.content = '{{ url("") }}';
        document.head.appendChild(meta);
    }
</script>
@endpush 