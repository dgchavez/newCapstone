<x-app-layout>
    <div class="bg-gradient-to-b from-green-50 to-white min-h-screen">
        <div class="container mx-auto px-4 py-8">
            <!-- Header Section -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-6">
                <div class="bg-gradient-to-r from-green-600 to-green-400 h-16"></div>
                <div class="px-6 py-5 -mt-1 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <h1 class="text-2xl font-bold text-gray-800 flex items-center">
                        <i class="fas fa-list-alt text-blue-500 mr-3"></i>Transaction Subtypes
                    </h1>
                    <a href="{{ route('transaction-subtypes.create') }}" 
                       class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg transition duration-200 flex items-center gap-2 shadow-md">
                        <i class="fas fa-plus text-sm"></i>
                        <span>Add New Subtype</span>
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
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transaction Type</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($transactionSubtypes as $subtype)
                                    <tr class="hover:bg-blue-50 transition-colors duration-200">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10 mr-3">
                                                    <div class="h-10 w-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                                                        <i class="fas fa-clipboard-list"></i>
                                                    </div>
                                                </div>
                                                <div class="text-sm font-medium text-gray-900">{{ $subtype->subtype_name }}</div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <span class="px-3 py-1 text-sm inline-flex items-center rounded-full text-white
                                                    @php
                                                        // Use the first 8 characters of MD5 hash converted to integer
                                                        $hash = hexdec(substr(md5($subtype->transactionType->type_name), 0, 8));
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
                                                    @endphp">
                                                    {{ $subtype->transactionType->type_name }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <div class="flex justify-center gap-3">
                                                <a href="{{ route('transaction-subtypes.edit', $subtype) }}" 
                                                   class="bg-blue-100 text-blue-700 hover:bg-blue-200 px-3 py-1 rounded-lg inline-flex items-center">
                                                    <i class="fas fa-edit mr-1"></i> Edit
                                                </a>
                                                
                                             
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-8 text-center text-gray-500">
                                            <div class="flex flex-col items-center">
                                                <i class="fas fa-list-alt text-gray-400 text-3xl mb-2"></i>
                                                <p>No transaction subtypes found.</p>
                                                <a href="{{ route('transaction-subtypes.create') }}" class="mt-4 text-blue-600 hover:text-blue-800">
                                                    Add your first transaction subtype
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination (if available) -->
                    @if(method_exists($transactionSubtypes, 'links') && $transactionSubtypes->hasPages())
                    <div class="mt-4 border-t border-gray-200 pt-4">
                        {{ $transactionSubtypes->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- Add Font Awesome CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
</x-app-layout>