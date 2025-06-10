<!-- Enhanced Filters -->
<div class="p-4 bg-gray-50 border-b border-gray-200">
    <form id="barangayFilterForm" class="grid grid-cols-1 md:grid-cols-3 gap-4">
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
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Coverage</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($barangayStats as $stat)
                    <tr class="hover:bg-gray-50 {{ $stat->unvaccinated_animals > 0 ? 'bg-red-50' : '' }}">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="text-sm font-medium text-gray-900">{{ $stat->barangay_name }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $stat->total_animals }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-green-600 font-medium">{{ $stat->vaccinated_animals }}</div>
                            @if($stat->vaccinated_last_30_days > 0)
                                <div class="text-xs text-green-500">+{{ $stat->vaccinated_last_30_days }} in 30d</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-red-600 font-medium">{{ $stat->unvaccinated_animals }}</div>
                            @if($stat->unvaccinated_animals > 0)
                                <div class="text-xs text-red-500">Needs attention</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col space-y-1">
                                <div class="text-sm text-gray-600">
                                    Last 7d: {{ $stat->vaccinated_last_7_days }}
                                </div>
                                <div class="text-sm text-gray-600">
                                    Last 30d: {{ $stat->vaccinated_last_30_days }}
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1">
                                @php
                                    $speciesArray = explode(',', $stat->animal_species ?? '');
                                @endphp
                                @forelse($speciesArray as $species)
                                    @if(!empty(trim($species)))
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ trim($species) }}
                                        </span>
                                    @endif
                                @empty
                                    <span class="text-sm text-gray-500">No species data</span>
                                @endforelse
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