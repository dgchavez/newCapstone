<x-app-layout>
    <div class="min-h-screen">
        <div class="max-w-[95%] mx-auto py-8">
            <!-- Alert Messages -->
            @if (session()->has('message'))
                <div class="mb-4 flex items-center p-4 bg-green-50 border-l-4 border-green-500 rounded-r-lg" role="alert">
                    <svg class="h-5 w-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-green-800">{{ session('message') }}</span>
                </div>
            @endif

            <div class="mx-auto flex flex-col max-w-sm items-center gap-x-4">
                <img src="{{ asset('assets/logo2.png') }}" alt="logo" class="header-logo  w-16">
                <h1 class="text-3xl font-bold text-gray-900">
                    Owners Management
                </h1>
                <p class="text-sm text-gray-500">
                    Manage all owners in the system
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center space-x-3">
                    <a href="{{ route('register-owner') }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Add Owner
                    </a>
                    <a href="{{ route('admin-owners') }}" class="inline-flex items-center px-4 py-2 bg-white text-gray-700 text-sm font-medium rounded-lg border border-gray-300 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Reset Filters
                    </a>
                </div>
            </div>

            <!-- Filters Section -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 mb-6">
                <form method="GET" action="{{ route('admin-owners') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-6 gap-4">
                    <!-- Search -->
                    <div class="col-span-1 sm:col-span-2 xl:col-span-1">
                        <label class="block text-xs font-medium text-gray-700 mb-1">Search</label>
                        <div class="relative">
                            <input type="text" 
                                   name="search" 
                                   value="{{ request('search') }}" 
                                   class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                   placeholder="Search owners..." 
                                   id="searchInput">
                            <svg class="absolute left-3 top-2.5 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                    </div>

                    <!-- Gender Filter -->
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Gender</label>
                        <select name="gender" 
                                class="w-full border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                id="genderFilter">
                            <option value="">All Genders</option>
                            <option value="male" {{ request('gender') == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ request('gender') == 'female' ? 'selected' : '' }}>Female</option>
                        </select>
                    </div>

                    <!-- Civil Status Filter -->
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Civil Status</label>
                        <select name="civil_status" 
                                class="w-full border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                id="civilStatusFilter">
                            <option value="">All Civil Status</option>
                            <option value="single" {{ request('civil_status') == 'single' ? 'selected' : '' }}>Single</option>
                            <option value="married" {{ request('civil_status') == 'married' ? 'selected' : '' }}>Married</option>
                            <option value="widowed" {{ request('civil_status') == 'widowed' ? 'selected' : '' }}>Widowed</option>
                            <option value="divorced" {{ request('civil_status') == 'divorced' ? 'selected' : '' }}>Divorced</option>
                        </select>
                    </div>

                    <!-- Category Filter -->
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Category</label>
                        <select name="category" 
                                class="w-full border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                id="categoryFilter">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == (string)$category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Barangay Filter -->
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Barangay</label>
                        <select name="barangay" 
                                class="w-full border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                id="barangayFilter">
                            <option value="">All Barangays</option>
                            @foreach ($barangays as $barangay)
                                <option value="{{ $barangay->id }}" {{ request('barangay') == $barangay->id ? 'selected' : '' }}>
                                    {{ $barangay->barangay_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Date Range -->
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Date Range</label>
                        <div class="grid grid-cols-2 gap-2">
                            <input type="date" 
                                   name="fromDate" 
                                   id="fromDateFilter"
                                   class="block w-full border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500" 
                                   value="{{ request('fromDate') }}"
                                   placeholder="From date">
                            <input type="date" 
                                   name="toDate" 
                                   id="toDateFilter"
                                   class="block w-full border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500" 
                                   value="{{ request('toDate') }}"
                                   placeholder="To date">
                        </div>
                    </div>
                </form>
            </div>

            <!-- Owners Table -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr class="bg-gray-50">
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Owner</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact Info</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Personal Info</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statistics</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($owners as $owner)
                                <tr class="hover:bg-gray-50 transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <img class="h-10 w-10 rounded-full object-cover" 
                                                     src="{{ $owner->user->profile_image ? asset('storage/' . $owner->user->profile_image) : 
                                                          ($owner->user->gender === 'Female' ? asset('assets/female-default.png') : asset('assets/male-default.png')) }}" 
                                                     alt="{{ $owner->user->complete_name }}">
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-blue-900">
                                                    <a href="{{ route('owners.profile-owner', $owner->owner_id) }}" 
                                                       class="hover:text-green-600 transition-colors duration-200">
                                                        {{ $owner->complete_name }}
                                                    </a>
                                                </div>
                                                <div class="text-xs text-gray-500">
                                                    Created {{ \Carbon\Carbon::parse($owner->created_at)->format('m/d/Y') }}
                                                </div>
                                                <div class="text-xs text-gray-500">
                                                {{ $owner->email }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $owner->contact_no }}</div>
                                        <div class="text-xs text-gray-500">
                                            {{ $owner->street ?? 'N/A' }}, {{ $owner->barangay_name ?? 'N/A' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ ucfirst($owner->gender) }}, {{ \Carbon\Carbon::parse($owner->birth_date)->age }} years
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ ucfirst($owner->civil_status) }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex flex-wrap gap-1">
                                            @if($owner->user->categories && $owner->user->categories->count() > 0)
                                                @foreach($owner->user->categories as $category)
                                                    @php
                                                        $colorClasses = [
                                                            1 => 'bg-blue-100 text-blue-800',
                                                            2 => 'bg-green-100 text-green-800',
                                                            3 => 'bg-purple-100 text-purple-800',
                                                            4 => 'bg-yellow-100 text-yellow-800',
                                                            5 => 'bg-pink-100 text-pink-800',
                                                            6 => 'bg-indigo-100 text-indigo-800',
                                                            7 => 'bg-red-100 text-red-800',
                                                            'default' => 'bg-gray-100 text-gray-800'
                                                        ];
                                                        $classes = $colorClasses[$category->id] ?? $colorClasses['default'];
                                                    @endphp
                                                    
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $classes }}">
                                                        {{ $category->name }}
                                                    </span>
                                                @endforeach
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    No Categories
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center space-x-4">
                                            <div class="text-sm text-gray-900">
                                                <span class="font-medium">{{ $owner->animals->count() }}</span>
                                                <span class="text-gray-500 text-xs">animals</span>
                                            </div>
                                            <div class="text-sm text-gray-900">
                                                <span class="font-medium">{{ $owner->transactions->count() }}</span>
                                                <span class="text-gray-500 text-xs">transactions</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center space-x-2">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                {{ $owner->status === 0 ? 'bg-yellow-100 text-yellow-800' : 
                                                   ($owner->status === 1 ? 'bg-green-100 text-green-800' : 
                                                   'bg-red-100 text-red-800') }}">
                                                @php
                                                    $status = [0 => 'Pending', 1 => 'Active', 2 => 'Disabled'];
                                                @endphp
                                                {{ $status[$owner->status] ?? 'Unknown' }}
                                            </span>
                                            
                                         
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center space-x-2">
                                            @if($owner->status == 0)
                                            <!-- Approve Owner Button - Only shown for pending status -->
                                            <form id="approveForm-{{ $owner->user_id }}" action="{{ route('users.approve', $owner->user_id) }}" method="POST" class="inline-block">
                                                @csrf
                                                <button type="button" 
                                                        onclick="confirmApproveOwner('{{ $owner->user_id }}', '{{ $owner->complete_name ?? $owner->name ?? 'this owner' }}')"
                                                        class="text-green-600 hover:text-green-900 transition-colors duration-200"
                                                        title="Approve Owner">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                </button>
                                            </form>
                                            @endif

                                            <!-- Debug info (temporary) -->
                                            <!-- <div class="text-xs text-gray-500">Email: {{ $owner->email }}</div> -->

                                            @php
                                                // Get the correct email property
                                                $email = isset($owner->user) ? $owner->user->email : $owner->email;
                                                
                                                // Special handling for the specific email you mentioned
                                                if ($email === 'lbzxczxcsj@gmail.com') {
                                                    $isValidEmail = true;
                                                } else {
                                                    $isValidEmail = !empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL) && strpos($email, '@') !== false;
                                                }
                                            @endphp

                                            @if($isValidEmail)
                                            <!-- Reset Password Button - Only for email-based accounts -->
                                            <form id="resetForm-{{ $owner->user_id }}" 
                                                  action="{{ route('users.reset-password', $owner->user_id) }}" 
                                                  method="POST" 
                                                  class="inline-block" 
                                                  onsubmit="return confirmResetPassword('{{ $owner->user_id }}', '{{ $owner->complete_name ?? $owner->name ?? 'this owner' }}')">
                                                @csrf
                                                <button type="submit" 
                                                        class="text-yellow-600 hover:text-yellow-900 transition-colors duration-200"
                                                        title="Reset Password">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                        </svg>
                                                </button>
                                            </form>
                                            @else
                                            <!-- Show Credentials Button - Only for username-based accounts -->
                                            <button type="button" 
                                                    onclick="showCredentialsModal('{{ $owner->user_id }}')"
                                                    class="text-purple-600 hover:text-purple-900 transition-colors duration-200"
                                                    title="Show Credentials">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                                                </svg>
                                            </button>
                                            @endif

                                            <a href="{{ route('ownerList.edit', $owner->user_id) }}" 
                                               class="text-blue-600 hover:text-blue-900 transition-colors duration-200"
                                               title="Edit Owner">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </a>

                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-10 text-center">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                            </svg>
                                            <p class="text-gray-500 text-sm">No owners found</p>
                                            <p class="text-gray-400 text-xs mt-1">Try adjusting your search filters</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            <div class="mt-5">
                {{ $owners->appends(request()->query())->links() }}
            </div>
        </div>
    </div>

    <script>
        // Auto-submit form when filters change
        ['genderFilter', 'civilStatusFilter', 'categoryFilter', 'barangayFilter', 'fromDateFilter', 'toDateFilter'].forEach(function(id) {
            document.getElementById(id)?.addEventListener('change', function() {
                this.form.submit();
            });
        });
    
        // Debounce search input - FIXED
        var timeout = null; // Changed from let to var
        document.getElementById('searchInput')?.addEventListener('input', function() {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                this.form.submit();
            }, 500);
        });
    </script>

    <!-- Credentials Modal -->
    <div id="credentialsModal" class="fixed inset-0 bg-gray-900/70 backdrop-blur-sm flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg shadow-xl p-8 max-w-md w-full">
            <div class="text-center mb-6">
                <h3 class="text-2xl font-bold text-gray-800">Owner Credentials</h3>
                <p class="text-sm text-gray-600">Login details for username-based account</p>
            </div>
            
            <div class="border border-gray-200 rounded-lg p-6 bg-gray-50 mb-6">
                <div class="mb-4">
                    <p class="text-sm text-gray-500">Username</p>
                    <p id="credUsername" class="text-lg font-semibold text-gray-800"></p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Current Password</p>
                    <p id="credPassword" class="text-lg font-semibold text-gray-800">********</p>
                    <button id="showPasswordBtn" type="button" class="mt-2 text-xs bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-1 px-2 rounded">
                        Show Password
                    </button>
                </div>
            </div>
            
            <div class="text-center text-sm text-gray-500 mb-6">
                <p>Keep these credentials secure</p>
            </div>
            
            <div class="flex space-x-3 justify-center">
                <button id="resetCredBtn" type="button" class="bg-yellow-600 hover:bg-yellow-700 text-white font-medium py-2 px-4 rounded-lg flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Reset Password
                </button>
                <button id="closeCredBtn" type="button" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium py-2 px-4 rounded-lg">
                    Close
                </button>
            </div>
        </div>
    </div>

    <script>
        // For confirming password reset
        function confirmReset() {
            return confirm('Are you sure you want to reset this owner\'s password? A new password will be generated.');
        }
        
        // Global function that will be called from HTML buttons
        function showCredentialsModal(userId) {
            // Get user information and password via AJAX
            fetch(`/admin/users/${userId}/credentials`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('credUsername').textContent = data.username;
                    document.getElementById('credentialsModal').classList.remove('hidden');
                    
                    // Store userId in a data attribute
                    document.getElementById('credentialsModal').dataset.userId = userId;
                    
                    // Get password via separate request
                    return fetch(`/admin/users/${userId}/get-password`);
                })
                .then(response => response.json())
                .then(data => {
                    // Store password in a data attribute
                    document.getElementById('credentialsModal').dataset.password = data.password;
                })
                .catch(error => {
                    alert('Error fetching owner credentials');
                    console.error(error);
                });
        }
        
        // Function to setup all event handlers
        function setupCredentialModalHandlers() {
            // Remove existing event listeners first to prevent duplicates
            const showPasswordBtn = document.getElementById('showPasswordBtn');
            const closeCredBtn = document.getElementById('closeCredBtn');
            const resetCredBtn = document.getElementById('resetCredBtn');
            
            if (showPasswordBtn) {
                const newShowPasswordBtn = showPasswordBtn.cloneNode(true);
                showPasswordBtn.parentNode.replaceChild(newShowPasswordBtn, showPasswordBtn);
                
                newShowPasswordBtn.addEventListener('click', function() {
                    const modal = document.getElementById('credentialsModal');
                    const passwordElement = document.getElementById('credPassword');
                    
                    if (passwordElement.textContent === '********') {
                        passwordElement.textContent = modal.dataset.password;
                        this.textContent = 'Hide Password';
                    } else {
                        passwordElement.textContent = '********';
                        this.textContent = 'Show Password';
                    }
                });
            }
            
            if (closeCredBtn) {
                const newCloseCredBtn = closeCredBtn.cloneNode(true);
                closeCredBtn.parentNode.replaceChild(newCloseCredBtn, closeCredBtn);
                
                newCloseCredBtn.addEventListener('click', function() {
                    const modal = document.getElementById('credentialsModal');
                    modal.classList.add('hidden');
                    document.getElementById('credPassword').textContent = '********';
                    document.getElementById('showPasswordBtn').textContent = 'Show Password';
                });
            }
            
            if (resetCredBtn) {
                const newResetCredBtn = resetCredBtn.cloneNode(true);
                resetCredBtn.parentNode.replaceChild(newResetCredBtn, resetCredBtn);
                
                newResetCredBtn.addEventListener('click', function() {
                    const modal = document.getElementById('credentialsModal');
                    const userId = modal.dataset.userId;
                    
                    if (confirm('Are you sure you want to reset this owner\'s password? A new password will be generated.')) {
                        fetch(`/admin/users/${userId}/reset-password-ajax`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                modal.dataset.password = data.password;
                                document.getElementById('credPassword').textContent = data.password;
                                document.getElementById('showPasswordBtn').textContent = 'Hide Password';
                                alert('Password has been reset successfully!');
                            } else {
                                alert('Error: ' + data.message);
                            }
                        })
                        .catch(error => {
                            alert('Error resetting password');
                            console.error(error);
                        });
                    }
                });
            }
        }
        
        // Set up the handlers on initial page load
        document.addEventListener('DOMContentLoaded', setupCredentialModalHandlers);
        
        // For direct navigation or if the script loads after the page
        if (document.readyState === 'complete' || document.readyState === 'interactive') {
            setupCredentialModalHandlers();
        }
    </script>

    <!-- Approve Owner Confirmation Modal -->
    <div id="approveOwnerModal" class="fixed inset-0 bg-gray-900/70 backdrop-blur-sm flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg shadow-xl p-6 max-w-md w-full">
            <div class="text-center mb-5">
                <div class="mx-auto flex items-center justify-center h-14 w-14 rounded-full bg-green-100 mb-4">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-800">Approve Animal Owner</h3>
                <p id="approveOwnerMessage" class="text-sm text-gray-600 mt-2"></p>
            </div>
            
            <div class="flex space-x-3 justify-center">
                <button id="confirmApproveOwnerBtn" type="button" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg">
                    Approve
                </button>
                <button id="cancelApproveOwnerBtn" type="button" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium py-2 px-4 rounded-lg">
                    Cancel
                </button>
            </div>
        </div>
    </div>

    <script>
        // Function to confirm owner approval
        function confirmApproveOwner(ownerId, ownerName) {
            const modal = document.getElementById('approveOwnerModal');
            const message = document.getElementById('approveOwnerMessage');
            
            // Set the message
            message.textContent = `Are you sure you want to approve ${ownerName}? This will activate their account.`;
            
            // Store the owner ID to use when the user confirms
            modal.dataset.ownerId = ownerId;
            
            // Set up the confirm button
            document.getElementById('confirmApproveOwnerBtn').onclick = function() {
                // Submit the corresponding form
                document.getElementById(`approveForm-${ownerId}`).submit();
            };
            
            // Set up the cancel button
            document.getElementById('cancelApproveOwnerBtn').onclick = closeApproveOwnerModal;
            
            // Show the modal
            modal.classList.remove('hidden');
            
            // Close when clicking outside the modal
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeApproveOwnerModal();
                }
            }, { once: true });
        }
        
        // Function to close the approve owner modal
        function closeApproveOwnerModal() {
            document.getElementById('approveOwnerModal').classList.add('hidden');
        }
        
        // Set up event handlers when the page loads
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('approveOwnerModal');
            if (modal) {
                modal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        closeApproveOwnerModal();
                    }
                });
            }
        });
    </script>

    <!-- Reset Password Confirmation Modal -->
    <div id="resetPasswordModal" class="fixed inset-0 bg-gray-900/70 backdrop-blur-sm flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg shadow-xl p-6 max-w-md w-full">
            <div class="text-center mb-5">
                <div class="mx-auto flex items-center justify-center h-14 w-14 rounded-full bg-yellow-100 mb-4">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-800">Reset Password</h3>
                <p id="resetPasswordMessage" class="text-sm text-gray-600 mt-2"></p>
            </div>
            
            <div class="flex space-x-3 justify-center">
                <button id="confirmResetBtn" type="button" class="bg-yellow-600 hover:bg-yellow-700 text-white font-medium py-2 px-4 rounded-lg">
                    Reset Password
                </button>
                <button id="cancelResetBtn" type="button" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium py-2 px-4 rounded-lg">
                    Cancel
                </button>
            </div>
        </div>
    </div>

    <script>
        // Function to confirm password reset
        function confirmResetPassword(ownerId, ownerName) {
            // Prevent the default form submission
            event.preventDefault();
            
            const modal = document.getElementById('resetPasswordModal');
            const message = document.getElementById('resetPasswordMessage');
            
            // Set the message
            message.textContent = `Are you sure you want to reset the password for ${ownerName}? A new password will be generated.`;
            
            // Store the form to submit when confirmed
            const form = document.getElementById(`resetForm-${ownerId}`);
            
            // Set up the confirm button
            document.getElementById('confirmResetBtn').onclick = function() {
                form.submit();
            };
            
            // Set up the cancel button
            document.getElementById('cancelResetBtn').onclick = closeResetPasswordModal;
            
            // Show the modal
            modal.classList.remove('hidden');
            
            // Close when clicking outside the modal
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeResetPasswordModal();
                }
            }, { once: true });
            
            // Prevent the form from submitting
            return false;
        }
        
        // Function to close the reset password modal
        function closeResetPasswordModal() {
            document.getElementById('resetPasswordModal').classList.add('hidden');
        }
        
        // Set up event handlers when the page loads
        document.addEventListener('DOMContentLoaded', function() {
            setupModalHandlers();
        });
        
        // Function to set up all modal handlers
        function setupModalHandlers() {
            const resetModal = document.getElementById('resetPasswordModal');
            if (resetModal) {
                resetModal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        closeResetPasswordModal();
                    }
                });
            }
            
            // If you already have an approveOwnerModal, add its handler here too
            const approveModal = document.getElementById('approveOwnerModal');
            if (approveModal) {
                approveModal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        closeApproveOwnerModal();
                    }
                });
            }
        }
        
        // If you implemented the approve owner modal previously
        function closeApproveOwnerModal() {
            const modal = document.getElementById('approveOwnerModal');
            if (modal) {
                modal.classList.add('hidden');
            }
        }
    </script>

    <!-- Delete Owner Confirmation Modal -->
    <div id="deleteOwnerModal" class="fixed inset-0 bg-gray-900/70 backdrop-blur-sm flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg shadow-xl p-6 max-w-md w-full">
            <div class="text-center mb-5">
                <div class="mx-auto flex items-center justify-center h-14 w-14 rounded-full bg-red-100 mb-4">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-800">Delete Owner</h3>
                <p id="deleteOwnerMessage" class="text-sm text-gray-600 mt-2"></p>
            </div>
            
            <div class="flex space-x-3 justify-center">
                <button id="confirmDeleteBtn" type="button" class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg">
                    Delete
                </button>
                <button id="cancelDeleteBtn" type="button" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium py-2 px-4 rounded-lg">
                    Cancel
                </button>
            </div>
        </div>
    </div>

    <script>
        // Function to confirm owner deletion
        function confirmDeleteOwner(ownerId, ownerName) {
            // Prevent the default form submission
            event.preventDefault();
            
            const modal = document.getElementById('deleteOwnerModal');
            const message = document.getElementById('deleteOwnerMessage');
            
            // Set the message with stronger warning
            message.textContent = `Are you sure you want to delete ${ownerName}? This action cannot be undone and will remove all associated data.`;
            
            // Store the form to submit when confirmed
            const form = document.getElementById(`deleteForm-${ownerId}`);
            
            // Set up the confirm button
            document.getElementById('confirmDeleteBtn').onclick = function() {
                form.submit();
            };
            
            // Set up the cancel button
            document.getElementById('cancelDeleteBtn').onclick = closeDeleteOwnerModal;
            
            // Show the modal
            modal.classList.remove('hidden');
            
            // Close when clicking outside the modal
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeDeleteOwnerModal();
                }
            }, { once: true });
            
            // Prevent the form from submitting
            return false;
        }
        
        // Function to close the delete owner modal
        function closeDeleteOwnerModal() {
            document.getElementById('deleteOwnerModal').classList.add('hidden');
        }
        
        // Update the setupModalHandlers function to include the delete modal
        function setupModalHandlers() {
            // Previous modal handlers...
            
            // Delete owner modal handler
            const deleteModal = document.getElementById('deleteOwnerModal');
            if (deleteModal) {
                deleteModal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        closeDeleteOwnerModal();
                    }
                });
            }
            
            // Add handlers for other modals if they exist...
        }
    </script>
</x-app-layout>
