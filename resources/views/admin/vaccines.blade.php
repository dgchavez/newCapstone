<x-app-layout>
    <div class="bg-gradient-to-b from-green-50 to-white min-h-screen">
        <div class="container mx-auto px-4 py-8">
            <!-- Header Section -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-6">
                <div class="bg-gradient-to-r from-green-600 to-green-400 h-16"></div>
                <div class="px-6 py-5 -mt-1 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <h1 class="text-2xl font-bold text-gray-800 flex items-center">
                        <i class="fas fa-syringe text-blue-500 mr-3"></i>Vaccines Management
                    </h1>
                    <a href="{{ route('vaccines.create') }}" 
                       class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg transition duration-200 flex items-center gap-2 shadow-md">
                        <i class="fas fa-plus text-sm"></i>
                        <span>Add New Vaccine</span>
                    </a>
                </div>
            </div>

            <!-- Status Messages -->
            @if (session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Table Section -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vaccine Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($vaccines as $vaccine)
                                    <tr class="hover:bg-blue-50 transition-colors duration-200">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-medium">
                                                {{ $loop->iteration }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10 mr-3">
                                                    <div class="h-10 w-10 rounded-full bg-orange-100 text-blue-600 flex items-center justify-center">
                                                        <i class="fas fa-syringe"></i>
                                                    </div>
                                                </div>
                                                <div class="text-sm font-medium text-gray-900">{{ $vaccine->vaccine_name }}</div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500">{{ $vaccine->description }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('vaccines.edit', $vaccine) }}" 
                                                   class="bg-blue-100 text-blue-700 hover:bg-blue-200 px-4 py-2 rounded-lg flex items-center gap-2 transition-colors duration-200">
                                                    <i class="fas fa-edit"></i>
                                                    <span>Edit</span>
                                                </a>
                                             
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Add Font Awesome CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
</x-app-layout>