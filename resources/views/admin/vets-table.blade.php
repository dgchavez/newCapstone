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
                        <i class="fas fa-user-md text-blue-500 mr-3"></i>Veterinarians Management
                    </h1>
                    <p class="text-gray-600 mt-1 sm:mt-0 sm:ml-3">
                        Manage veterinarians and track their activities
                    </p>
                </div>
            </div>

            <!-- Stats Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="p-5 flex items-center">
                        <div class="flex-shrink-0 bg-blue-100 rounded-full p-3 mr-4">
                            <i class="fas fa-user-md text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Total Veterinarians</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $veterinarians->total() }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="p-5 flex items-center">
                        <div class="flex-shrink-0 bg-green-100 rounded-full p-3 mr-4">
                            <i class="fas fa-clipboard-check text-green-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Total Transactions</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $veterinarians->sum('transaction_count') }}</p>
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
                                {{ $veterinarians->where('created_at', '>=', \Carbon\Carbon::now()->subDays(30))->count() }}
                            </p>
                            <p class="text-xs text-gray-500">in the last 30 days</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('veterinarians.create') }}" 
                       class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200 shadow-md">
                        <i class="fas fa-plus text-sm mr-2"></i>
                        Add Veterinarian
                    </a>
                    <a href="{{ route('admin-veterinarians') }}" 
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
                        <span>Filter Veterinarians</span>
                    </h2>
                </div>
                <div class="p-6">
                    <form method="GET" action="{{ route('admin-veterinarians') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
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
                                       placeholder="Search veterinarians..." 
                                       id="searchInput">
                            </div>
                        </div>

                        <!-- Gender Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Gender</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-venus-mars text-gray-400"></i>
                                </div>
                                <select name="gender" 
                                        class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 border-gray-300 rounded-lg text-sm appearance-none"
                                        id="genderFilter">
                                    <option value="" {{ request('gender') == '' ? 'selected' : '' }}>All Genders</option>
                                    <option value="male" {{ request('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ request('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Designation Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Designation</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-tag text-gray-400"></i>
                                </div>
                                <select name="designation" 
                                        class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 border-gray-300 rounded-lg text-sm appearance-none"
                                        id="designationFilter">
                                    <option value="">All Designations</option>
                                    @foreach ($designations as $designation)
                                        <option value="{{ $designation->designation_id }}" {{ request('designation') == $designation->designation_id ? 'selected' : '' }}>
                                            {{ $designation->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Date Range -->
                        <div class="col-span-1 sm:col-span-2 lg:col-span-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Registration Date</label>
                            <div class="mt-1 grid grid-cols-2 gap-4">
                                <div class="relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-calendar-alt text-gray-400"></i>
                                    </div>
                                    <input type="date" 
                                           name="fromDate" 
                                           class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 border-gray-300 rounded-lg text-sm" 
                                           value="{{ request('fromDate') }}"
                                           id="fromDate"
                                           placeholder="From date">
                                </div>
                                <div class="relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-calendar-alt text-gray-400"></i>
                                    </div>
                                    <input type="date" 
                                           name="toDate" 
                                           class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 border-gray-300 rounded-lg text-sm" 
                                           value="{{ request('toDate') }}"
                                           id="toDate"
                                           placeholder="To date">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Veterinarians Table -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                    <h2 class="text-lg font-medium text-gray-800 flex items-center">
                        <i class="fas fa-list text-blue-500 mr-2"></i>
                        <span>Veterinarians List</span>
                    </h2>
                    <span class="text-sm text-gray-500">{{ $veterinarians->total() }} results found</span>
                </div>
                
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Profile</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Full Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Designation</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact #</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gender</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transactions</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Created</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($veterinarians as $vet)
                                    <tr class="hover:bg-blue-50 transition-colors duration-200">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                @if($vet->profile_image)
                                                    <img class="h-10 w-10 rounded-full object-cover" 
                                                         src="{{ asset('storage/' . $vet->profile_image) }}" 
                                                         alt="{{ $vet->complete_name }}">
                                                @else
                                                    <div class="h-10 w-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                                                        <i class="fas fa-user-md"></i>
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">
                                                        <a href="{{ route('admin.veterinarian.profile', $vet->user_id) }}" class="hover:text-blue-600 transition-colors duration-200">
                                                            {{ $vet->complete_name }}
                                                        </a>
                                                    </div>
                                                    <div class="text-xs text-gray-500 mt-1">
                                                        ID: #{{ $vet->user_id }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            @if($vet->designation_name)
                                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-medium rounded-full bg-blue-100 text-blue-800">
                                                    {{ $vet->designation_name }}
                                                </span>
                                            @else
                                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-medium rounded-full bg-gray-100 text-gray-800">
                                                    No designation
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <div class="flex items-center">
                                                <i class="fas fa-phone-alt text-gray-400 mr-2"></i>
                                                {{ $vet->contact_no ?: 'Not provided' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <div class="flex items-center">
                                                @if($vet->gender == 'male')
                                                    <i class="fas fa-mars text-blue-500 mr-2"></i>
                                                @else
                                                    <i class="fas fa-venus text-pink-500 mr-2"></i>
                                                @endif
                                                {{ ucfirst($vet->gender) }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            @if($vet->transaction_count > 0)
                                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-medium rounded-full bg-green-100 text-green-800">
                                                    {{ $vet->transaction_count }} transactions
                                                </span>
                                            @else
                                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-medium rounded-full bg-gray-100 text-gray-800">
                                                    No transactions
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <div class="flex items-center">
                                                <i class="fas fa-calendar-day text-gray-400 mr-2"></i>
                                                {{ \Carbon\Carbon::parse($vet->created_at)->format('M d, Y') }}
                                            </div>
                                            <div class="text-xs text-gray-500 mt-1 ml-5">
                                                {{ \Carbon\Carbon::parse($vet->created_at)->diffForHumans() }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex justify-center gap-3">
                                                <a href="{{ route('admin-veterinarians.edit', $vet->user_id) }}" 
                                                   class="text-blue-600 hover:text-blue-900 flex items-center gap-1">
                                                    <i class="fas fa-edit"></i>
                                                    <span>Edit</span>
                                                </a>

                                                <form action="{{ route('admin-veterinarians.destroy', $vet->user_id) }}" 
                                                      method="POST" 
                                                      class="inline-block"
                                                      onsubmit="return confirm('Are you sure you want to delete this veterinarian?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="text-red-600 hover:text-red-900 flex items-center gap-1">
                                                        <i class="fas fa-trash-alt"></i>
                                                        <span>Delete</span>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-8 whitespace-nowrap text-sm text-gray-500 text-center">
                                            <div class="flex flex-col items-center">
                                                <i class="fas fa-user-md text-gray-400 text-3xl mb-2"></i>
                                                <p>No veterinarians found.</p>
                                                <a href="{{ route('veterinarians.create') }}" class="mt-4 text-blue-600 hover:text-blue-800">
                                                    Add your first veterinarian
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination (if available) -->
                    @if($veterinarians->hasPages())
                    <div class="mt-4 border-t border-gray-200 pt-4 flex items-center justify-between">
                        <div class="text-sm text-gray-500">
                            Showing {{ $veterinarians->firstItem() }} to {{ $veterinarians->lastItem() }} of {{ $veterinarians->total() }} veterinarians
                        </div>
                        <div>
                            {{ $veterinarians->appends(request()->query())->links() }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Help Box -->
            <div class="mt-6 bg-blue-50 rounded-lg border border-blue-200 overflow-hidden shadow-md">
                <div class="px-6 py-4 border-b border-blue-100 bg-blue-100">
                    <h3 class="text-lg font-medium text-blue-800 flex items-center">
                        <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                        <span>Managing Veterinarians</span>
                    </h3>
                </div>
                <div class="p-6">
                    <p class="text-blue-700 mb-3">Use this page to manage all veterinarians in your system. You can:</p>
                    <ul class="list-disc pl-5 text-blue-700 space-y-1">
                        <li>Add new veterinarians using the "Add Veterinarian" button</li>
                        <li>Filter the list by name, designation, gender, or date range</li>
                        <li>View detailed profiles by clicking on a veterinarian's name</li>
                        <li>Edit veterinarian details or remove them from the system</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for Auto Submitting Forms -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-submitting filters
            const autoSubmitElements = [
                document.getElementById('genderFilter'),
                document.getElementById('designationFilter'),
                document.getElementById('fromDate'),
                document.getElementById('toDate')
            ];
            
            // Add event listeners to elements that exist
            autoSubmitElements.forEach(element => {
                if (element) {
                    element.addEventListener('change', function() {
                        this.form.submit();
                    });
                }
            });
            
            // Debounced search input
            const searchInput = document.getElementById('searchInput');
            if (searchInput) {
                let timeout = null;
                searchInput.addEventListener('input', function() {
                    clearTimeout(timeout);
                    timeout = setTimeout(() => {
                        this.form.submit();
                    }, 500); // 500ms delay before submitting
                });
            }
        });
    </script>
    
    <!-- Animation for alerts -->
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-1rem); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .animate-fadeIn {
            animation: fadeIn 0.3s ease-out forwards;
        }
    </style>
    
    <!-- Add Font Awesome CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
</x-app-layout>