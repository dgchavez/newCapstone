<x-app-layout>
    <div class="bg-gradient-to-b from-green-50 to-white min-h-screen">
        <div class="max-w-[90%] mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-6">
                <div class="bg-gradient-to-r from-green-600 to-green-400 h-16"></div>
                <div class="px-6 py-5 -mt-1 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                        <i class="fas fa-user-nurse text-blue-500 mr-3"></i>Veterinary Technicians
                    </h1>
                    <a href="{{ route('veterinary-technicians.create') }}" 
                       class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg transition duration-200 flex items-center gap-2 shadow-md">
                        <i class="fas fa-plus text-sm"></i>
                        <span>Add Technician</span>
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
                                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">#</th>
                                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Full Name</th>
                                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Contact Number</th>
                                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                    <th class="px-6 py-3 text-center text-sm font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($technicians as $technician)
                                    <tr class="hover:bg-blue-50 transition-colors duration-200">
                                        <td class="px-6 py-4 whitespace-nowrap text-base text-gray-500">{{ $loop->iteration }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10 mr-3">
                                                    <div class="h-10 w-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                                                        <i class="fas fa-user-nurse"></i>
                                                    </div>
                                                </div>
                                                <div class="text-base font-medium text-gray-900">{{ $technician->full_name }}</div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-base text-gray-500">
                                            <div class="flex items-center">
                                                <i class="fas fa-phone-alt text-gray-400 mr-2"></i>
                                                {{ $technician->contact_number }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-base text-gray-500">
                                            <div class="flex items-center">
                                                <i class="fas fa-envelope text-gray-400 mr-2"></i>
                                                {{ $technician->email }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-base text-gray-500">
                                            <div class="flex justify-center gap-3">
                                                <a href="{{ route('veterinary-technicians.edit', $technician->technician_id) }}" 
                                                class="bg-blue-100 text-blue-700 hover:bg-blue-200 px-4 py-2 rounded-lg flex items-center gap-2 transition-colors duration-200">
                                                    <i class="fas fa-edit"></i>
                                                    <span>Edit</span>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-8 whitespace-nowrap text-base text-gray-500 text-center">
                                            <div class="flex flex-col items-center">
                                                <i class="fas fa-user-nurse text-gray-400 text-3xl mb-2"></i>
                                                <p>No technicians found.</p>
                                                <a href="{{ route('veterinary-technicians.create') }}" class="mt-4 text-blue-600 hover:text-blue-800">
                                                    Add your first technician
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination (if available) -->
                    @if(method_exists($technicians, 'links') && $technicians->hasPages())
                    <div class="mt-4 border-t border-gray-200 pt-4">
                        {{ $technicians->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- Add Font Awesome CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
</x-app-layout>