<x-app-layout>
    <div class="bg-gradient-to-b from-green-50 to-white min-h-screen">
        <div class="container mx-auto px-4 py-8">
            <!-- Header Section -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-6">
                <div class="bg-gradient-to-r from-green-600 to-green-400 h-16"></div>
                <div class="px-6 py-5 -mt-1 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <h1 class="text-2xl font-bold text-gray-800 flex items-center">
                        <i class="fas fa-map-marker-alt text-red-500 mr-3"></i>Barangays
                    </h1>
                    <a href="{{ route('create-barangay') }}" 
                       class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg transition duration-200 flex items-center gap-2 shadow-md">
                        <i class="fas fa-plus text-sm"></i>
                        <span>Add Barangay</span>
                    </a>
                </div>
            </div>

            <!-- Table Section -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Barangay Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($barangays as $barangay)
                                    <tr class="hover:bg-blue-50 transition-colors duration-200">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-medium">
                                                {{ $loop->iteration }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10 mr-3">
                                                    <div class="h-10 w-10 rounded-full bg-orange-100 text-red-600 flex items-center justify-center">
                                                        <i class="fas fa-map-pin"></i>
                                                    </div>
                                                </div>
                                                <div class="text-sm font-medium text-gray-900">{{ $barangay->barangay_name }}</div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-base text-gray-500">
                                            <div class="flex justify-center gap-3">
                                                <a href="{{ route('barangays.edit', $barangay) }}"
                                                class="bg-blue-100 text-blue-700 hover:bg-blue-200 px-4 py-2 rounded-lg flex items-center gap-2 transition-colors duration-200">
                                                    <i class="fas fa-edit"></i>
                                                    <span>Edit</span>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-8 whitespace-nowrap text-sm text-gray-500 text-center">
                                            <div class="flex flex-col items-center">
                                                <i class="fas fa-map-marked-alt text-gray-400 text-3xl mb-2"></i>
                                                <p>No barangays found.</p>
                                                <a href="{{ route('create-barangay') }}" class="mt-4 text-blue-600 hover:text-blue-800">
                                                    Add your first barangay
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination (if available) -->
                    @if(method_exists($barangays, 'links') && $barangays->hasPages())
                    <div class="mt-4 border-t border-gray-200 pt-4">
                        {{ $barangays->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- Add Font Awesome CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
</x-app-layout>