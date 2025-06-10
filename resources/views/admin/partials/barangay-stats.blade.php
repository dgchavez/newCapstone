<!-- Enhanced Filters -->
<div class="p-4 bg-gray-50 border-b border-gray-200">
    <form id="barangayFilterForm" class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Show Only Unvaccinated -->
            <div class="flex items-center space-x-2">
                <input type="checkbox" name="show_unvaccinated_only" id="show_unvaccinated_only" 
                       class="rounded border-gray-300 text-green-600 focus:ring-green-500 filter-input"
                       {{ request('show_unvaccinated_only') ? 'checked' : '' }}>
                <label for="show_unvaccinated_only" class="text-sm text-gray-700">
                    Show Only Barangays with Unvaccinated Animals
                </label>
            </div>

            <!-- Minimum Unvaccinated Filter -->
            <div class="flex items-center space-x-2">
                <label for="min_unvaccinated" class="text-sm text-gray-700">Minimum Unvaccinated:</label>
                <input type="number" name="min_unvaccinated" id="min_unvaccinated" 
                       class="rounded-lg border-gray-300 text-sm w-20 filter-input"
                       value="{{ request('min_unvaccinated') }}" min="0">
            </div>

            <!-- Sort Options -->
            <div class="flex items-center space-x-4">
                <label for="sort_by" class="text-sm text-gray-700">Sort by:</label>
                <select name="sort_by" id="sort_by" class="rounded-lg border-gray-300 text-sm filter-input">
                    <option value="unvaccinated_animals" {{ request('sort_by') == 'unvaccinated_animals' ? 'selected' : '' }}>
                        Unvaccinated Count
                    </option>
                    <option value="vaccination_rate" {{ request('sort_by') == 'vaccination_rate' ? 'selected' : '' }}>
                        Vaccination Rate
                    </option>
                    <option value="total_animals" {{ request('sort_by') == 'total_animals' ? 'selected' : '' }}>
                        Total Animals
                    </option>
                    <option value="barangay_name" {{ request('sort_by') == 'barangay_name' ? 'selected' : '' }}>
                        Barangay Name
                    </option>
                </select>

                <select name="sort_dir" id="sort_dir" class="rounded-lg border-gray-300 text-sm filter-input">
                    <option value="asc" {{ request('sort_dir') == 'asc' ? 'selected' : '' }}>Ascending</option>
                    <option value="desc" {{ request('sort_dir', 'desc') == 'desc' ? 'selected' : '' }}>Descending</option>
                </select>

                <button type="button" onclick="resetFilters()" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 text-sm">
                    Reset Filters
                </button>
            </div>
        </div>
    </form>
</div>

<div id="barangayStatsContent" class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Barangay</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Animals</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vaccinated</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unvaccinated</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last 30 Days</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last 7 Days</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Species Present</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vaccination Rate</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach($barangayStats as $stat)
                <tr class="hover:bg-gray-50 {{ $stat->unvaccinated_animals > 0 ? 'bg-red-50' : '' }}">
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
                        <div class="text-sm text-red-600 font-semibold">{{ $stat->unvaccinated_animals }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-blue-600">{{ $stat->vaccinated_last_30_days }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-blue-600">{{ $stat->vaccinated_last_7_days }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900">
                            @foreach($stat->animal_species_array as $species)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mr-1">
                                    {{ $species }}
                                </span>
                            @endforeach
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="relative w-32 h-2 bg-gray-200 rounded">
                                <div class="absolute top-0 left-0 h-2 {{ $stat->vaccination_rate >= 70 ? 'bg-green-500' : ($stat->vaccination_rate >= 40 ? 'bg-yellow-500' : 'bg-red-500') }} rounded" 
                                     style="width: {{ $stat->vaccination_rate }}%"></div>
                            </div>
                            <span class="ml-2 text-sm text-gray-600">{{ $stat->vaccination_rate }}%</span>
                        </div>
                        @if($stat->vaccinated_last_30_days > 0)
                            <div class="text-xs text-green-600 mt-1">
                                +{{ $stat->recent_vaccination_rate }}% in last 30 days
                            </div>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add event listeners to all filter inputs
    document.querySelectorAll('.filter-input').forEach(input => {
        input.addEventListener('change', updateBarangayStats);
    });
});

function updateBarangayStats() {
    const formData = new FormData(document.getElementById('barangayFilterForm'));
    const queryString = new URLSearchParams(formData).toString();

    // Show loading state
    document.getElementById('barangayStatsContent').innerHTML = `
        <div class="flex justify-center items-center p-8">
            <svg class="animate-spin h-8 w-8 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="ml-2 text-gray-600">Updating statistics...</span>
        </div>
    `;

    // Fetch updated data
    fetch(`/admin/barangay-stats?${queryString}`)
        .then(response => response.text())
        .then(html => {
            document.getElementById('barangayStatsContent').innerHTML = html;
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('barangayStatsContent').innerHTML = `
                <div class="text-center text-red-500 p-4">
                    An error occurred while updating the statistics. Please try again.
                </div>
            `;
        });
}

function resetFilters() {
    document.getElementById('barangayFilterForm').reset();
    updateBarangayStats();
}
</script> 