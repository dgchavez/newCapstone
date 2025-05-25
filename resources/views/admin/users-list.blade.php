<x-app-layout>
    <div>
        <!-- Page Wrapper -->
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
                
                @if (session()->has('error'))
                    <div class="mb-4 flex items-center p-4 bg-red-50 border-l-4 border-red-500 rounded-r-lg" role="alert">
                        <svg class="h-5 w-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <span class="text-red-800">{{ session('error') }}</span>
                    </div>
                @endif

                <!-- Page Header -->
                <div class="mx-auto flex flex-col max-w-sm items-center gap-x-4">
                    <img src="{{ asset('assets/logo2.png') }}" alt="logo" class="header-logo  w-16">
                    <h1 class="text-3xl font-bold text-gray-900">
                        Users Management
                    </h1>
                    <p class="text-sm text-gray-500">
                        Manage all users in the system
                </div>
               

                

                <!-- Action Buttons -->
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-3">
                        <a href="/admin/create/users" class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Add User
                        </a>
                        <a href="{{ route('admin-users') }}" class="inline-flex items-center px-4 py-2 bg-white text-gray-700 text-sm font-medium rounded-lg border border-gray-300 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Reset Filters
                        </a>
                    </div>
                </div>

                <!-- Filters Section -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 mb-6">
                    <form method="GET" action="{{ route('admin-users') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-6 gap-4" id="filter-form">
                        <!-- Search -->
                        <div class="col-span-1 sm:col-span-2 xl:col-span-1">
                            <label class="block text-xs font-medium text-gray-700 mb-1">Search</label>
                            <div class="relative">
                                <input type="text" name="search" 
                                       class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                       placeholder="Name, email, or contact" 
                                       value="{{ request('search') }}">
                                <svg class="absolute left-3 top-2.5 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                        </div>

                        <!-- Role -->
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Role</label>
                            <select name="role" class="w-full border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500" onchange="this.form.submit()">
                                <option value="">All Roles</option>
                                <option value="zero" {{ request('role') === 'zero' ? 'selected' : '' }}>Admin</option>
                                <option value="1" {{ request('role') === '1' ? 'selected' : '' }}>Animal Owner</option>
                                <option value="2" {{ request('role') === '2' ? 'selected' : '' }}>Veterinarian</option>
                                <option value="3" {{ request('role') === '3' ? 'selected' : '' }}>Receptionist</option>
                            </select>
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                            <select name="status" class="w-full border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500" onchange="this.form.submit()">
                                <option value="">All Status</option>
                                <option value="zero" {{ request('status') === 'zero' ? 'selected' : '' }}>Pending</option>
                                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                                <option value="2" {{ request('status') === '2' ? 'selected' : '' }}>Disabled</option>
                            </select>
                        </div>

                        <!-- Gender -->
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Gender</label>
                            <select name="gender" class="w-full border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500" onchange="this.form.submit()">
                                <option value="">All Genders</option>
                                <option value="male" {{ request('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ request('gender') == 'female' ? 'selected' : '' }}>Female</option>
                            </select>
                        </div>

                        <!-- Date Range -->
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">From Date</label>
                            <input type="date" name="fromDate" 
                                   class="w-full border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500" 
                                   value="{{ request('fromDate') }}"
                                   onchange="this.form.submit()">
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">To Date</label>
                            <input type="date" name="toDate" 
                                   class="w-full border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500" 
                                   value="{{ request('toDate') }}"
                                   onchange="this.form.submit()">
                        </div>
                    </form>
                </div>

                <!-- Users Table -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact Info</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($users as $user)
                                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <img class="h-10 w-10 rounded-full object-cover" 
                                                         src="{{ $user->profile_image ? asset('storage/' . $user->profile_image) : 
                                                              ($user->gender === 'Female' ? asset('assets/female-default.png') : asset('assets/male-default.png')) }}" 
                                                         alt="{{ $user->complete_name }}">
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-bold  text-blue-900">
                                                        <a href="{{ $user->user_id === auth()->user()->user_id 
                                                            ? route('users.nav-profile', ['id' => auth()->user()->user_id]) 
                                                            : route('users.profile-form', $user->user_id) }}" 
                                                           class="hover:text-green-600 transition-colors duration-200">
                                                            {{ $user->complete_name }}
                                                        </a>
                                                    </div>
                                                    <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                @php
                                                    $roles = [
                                                        0 => 'Admin',
                                                        1 => 'Animal Owner',
                                                        2 => 'Veterinarian',
                                                        3 => 'Receptionist'
                                                    ];
                                                @endphp
                                                {{ $roles[$user->role] ?? 'Unknown' }}
                                            </div>
                                            @if ($user->role === 2 && $user->designation)
                                                <div class="text-sm text-gray-500">{{ $user->designation->name }}</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $user->contact_no }}</div>
                                            <div class="text-sm text-gray-500">
                                                {{ $user->address && $user->address->barangay ? $user->address->barangay->barangay_name : 'N/A' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center space-x-2">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    {{ $user->status === 0 ? 'bg-yellow-100 text-yellow-800' : 
                                                        ($user->status === 1 ? 'bg-green-100 text-green-800' : 
                                                        'bg-red-100 text-red-800') }}">
                                                    @php
                                                        $status = [0 => 'Pending', 1 => 'Active', 2 => 'Disabled'];
                                                    @endphp
                                                    {{ $status[$user->status] ?? 'Unknown' }}
                                                </span>
                                                
                                                @if ($user->user_id !== auth()->id() && $user->status !== 0)
                                                    @if ($user->status === 1)
                                                        <!-- Disable User Button -->
                                                        <button type="button" 
                                                                onclick="showStatusToggleModal('{{ $user->user_id }}', 1, '{{ $user->complete_name }}')"
                                                                class="text-red-600 hover:text-red-900 transition-colors duration-200"
                                                                title="Disable User">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                                            </svg>
                                                        </button>
                                                    @elseif ($user->status === 2)
                                                        <!-- Enable User Button -->
                                                        <button type="button" 
                                                                onclick="showStatusToggleModal('{{ $user->user_id }}', 2, '{{ $user->complete_name }}')"
                                                                class="text-green-600 hover:text-green-900 transition-colors duration-200"
                                                                title="Enable User">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                        </button>
                                                    @endif
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            @if ($user->user_id !== auth()->id())
                                                <div class="flex items-center space-x-2">
                                                    <!-- Reset Password Button (only for email users) -->
                                                    @if(filter_var($user->email, FILTER_VALIDATE_EMAIL))
                                                    <form action="{{ route('users.reset-password', $user->user_id) }}" 
                                                          method="POST" 
                                                          class="inline-block" 
                                                          onsubmit="return confirmReset()">
                                                        @csrf
                                                        <button type="submit" 
                                                                class="text-yellow-600 hover:text-yellow-900 transition-colors duration-200"
                                                                title="Reset Password">
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                                </svg>
                                                        </button>
                                                    </form>
                                                    @endif

                                                    <!-- Show Credentials Button (for username users) -->
                                                    @if(!filter_var($user->email, FILTER_VALIDATE_EMAIL))
                                                    <button type="button" 
                                                            onclick="showCredentialsModal('{{ $user->user_id }}')"
                                                            class="text-purple-600 hover:text-purple-900 transition-colors duration-200"
                                                            title="Show Credentials">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                                                        </svg>
                                                    </button>
                                                    @endif

                                                    @if($user->status == 0)
                                                    <button type="button" 
                                                            onclick="showApproveUserModal('{{ $user->user_id }}', '{{ $user->complete_name }}')"
                                                            class="text-green-600 hover:text-green-900 transition-colors duration-200"
                                                            title="Approve User">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                        </svg>
                                                    </button>
                                                    @endif

                                                    <a href="{{ route('users.edit-form', $user->user_id) }}" 
                                                       class="text-blue-600 hover:text-blue-900 transition-colors duration-200"
                                                       title="Edit User">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                        </svg>
                                                    </a>

                                                   
                                                </div>
                                            @else
                                                <span class="text-gray-400">No actions available</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-10 text-center">
                                            <div class="flex flex-col items-center">
                                                <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                                </svg>
                                                <p class="text-gray-500 text-sm">No users found</p>
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
                    {{ $users->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Credentials Modal -->
    <div id="credentialsModal" class="fixed inset-0 bg-gray-900/70 backdrop-blur-sm flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg shadow-xl p-8 max-w-md w-full">
            <div class="text-center mb-6">
                <h3 class="text-2xl font-bold text-gray-800">User Credentials</h3>
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

    <!-- Status Toggle Confirmation Modal -->
    <div id="statusToggleModal" class="fixed inset-0 bg-gray-900/70 backdrop-blur-sm flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg shadow-xl p-6 max-w-md w-full">
            <div class="text-center mb-5">
                <div id="statusIcon" class="mx-auto flex items-center justify-center h-14 w-14 rounded-full mb-4">
                    <!-- Icon will be injected by JS -->
                </div>
                <h3 id="statusTitle" class="text-xl font-bold text-gray-800"></h3>
                <p id="statusMessage" class="text-sm text-gray-600 mt-2"></p>
            </div>
            
            <div class="flex space-x-3 justify-center">
                <form id="statusToggleForm" method="POST" class="inline-block">
                    @csrf
                    <input type="hidden" id="statusToggleValue" name="status" value="">
                    <button type="submit" id="confirmStatusBtn" class="font-medium py-2 px-4 rounded-lg flex items-center">
                        Confirm
                    </button>
                </form>
                <button id="cancelStatusBtn" type="button" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium py-2 px-4 rounded-lg">
                    Cancel
                </button>
            </div>
        </div>
    </div>

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

    <!-- Approve User Confirmation Modal -->
    <div id="approveUserModal" class="fixed inset-0 bg-gray-900/70 backdrop-blur-sm flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg shadow-xl p-6 max-w-md w-full">
            <div class="text-center mb-5">
                <div class="mx-auto flex items-center justify-center h-14 w-14 rounded-full bg-green-100 mb-4">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-800">Approve User</h3>
                <p id="approveUserMessage" class="text-sm text-gray-600 mt-2"></p>
            </div>
            
            <div class="flex space-x-3 justify-center">
                <button id="confirmApproveBtn" type="button" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg">
                    Approve
                </button>
                <button id="cancelApproveBtn" type="button" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium py-2 px-4 rounded-lg">
                    Cancel
                </button>
            </div>
        </div>
    </div>

    <!-- Delete User Confirmation Modal -->
    <div id="deleteUserModal" class="fixed inset-0 bg-gray-900/70 backdrop-blur-sm flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg shadow-xl p-6 max-w-md w-full">
            <div class="text-center mb-5">
                <div class="mx-auto flex items-center justify-center h-14 w-14 rounded-full bg-red-100 mb-4">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-800">Delete User</h3>
                <p id="deleteUserMessage" class="text-sm text-gray-600 mt-2"></p>
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
        // For confirming password reset
        function confirmReset() {
            // This function is called by the form's onsubmit
            // We'll prevent the default action and show our modal instead
            event.preventDefault();
            
            // Get the form that triggered this
            const form = event.target;
            
            // Extract the user ID from the form action URL
            const actionUrl = form.action;
            const userId = actionUrl.substring(actionUrl.lastIndexOf('/') + 1);
            
            // Find the username from the DOM (find the closest row and get the username)
            const row = form.closest('tr');
            const userName = row.querySelector('.text-sm.font-bold').textContent.trim();
            
            // Show the modal
            showResetPasswordModal(userId, userName, form);
            
            // Prevent the form from submitting
            return false;
        }
        
        // Function to show the reset password confirmation modal
        function showResetPasswordModal(userId, userName, form) {
            const modal = document.getElementById('resetPasswordModal');
            const message = document.getElementById('resetPasswordMessage');
            
            // Store the form reference to use it later
            modal.dataset.form = form;
            
            // Set the message
            message.textContent = `Are you sure you want to reset the password for ${userName}? A new password will be generated.`;
            
            // Setup confirm button
            document.getElementById('confirmResetBtn').onclick = function() {
                // Submit the original form
                form.submit();
            };
            
            // Setup cancel button
            document.getElementById('cancelResetBtn').onclick = closeResetPasswordModal;
            
            // Show the modal
            modal.classList.remove('hidden');
            
            // Close when clicking outside the modal
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeResetPasswordModal();
                }
            }, { once: true });
        }
        
        // Function to close the reset password modal
        function closeResetPasswordModal() {
            document.getElementById('resetPasswordModal').classList.add('hidden');
        }
        
        // Global function that will be called from HTML buttons
        function showCredentialsModal(userId) {
            console.log('Showing credentials for user:', userId);
            
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
                    alert('Error fetching user credentials');
                    console.error(error);
                });
        }
        
        // Function to setup all event handlers
        function setupCredentialModalHandlers() {
            console.log('Setting up credential modal handlers');
            
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
                    
                    if (confirm('Are you sure you want to reset this user\'s password? A new password will be generated.')) {
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
        
        // Add hooks for Livewire navigation events
        if (typeof window.Livewire !== 'undefined') {
            // For Livewire 3.x
            document.addEventListener('livewire:navigated', function() {
                console.log('Livewire navigation detected');
                setupCredentialModalHandlers();
            });
            
            // For Livewire 2.x
            document.addEventListener('livewire:load', function() {
                window.livewire.hook('message.processed', function() {
                    console.log('Livewire message processed');
                    setupCredentialModalHandlers();
                });
            });
        }
        
        // For direct navigation or if the script loads after the page
        if (document.readyState === 'complete' || document.readyState === 'interactive') {
            setupCredentialModalHandlers();
        }

        // Function to show the status toggle confirmation modal
        function showStatusToggleModal(userId, currentStatus, userName) {
            const statusModal = document.getElementById('statusToggleModal');
            const statusForm = document.getElementById('statusToggleForm');
            const statusValue = document.getElementById('statusToggleValue');
            const statusTitle = document.getElementById('statusTitle');
            const statusMessage = document.getElementById('statusMessage');
            const statusIcon = document.getElementById('statusIcon');
            const confirmBtn = document.getElementById('confirmStatusBtn');
            
            // Set the form action and status value
            statusForm.action = `/admin/users/${userId}/toggle-status`;
            
            if (currentStatus === 1) {
                // Disable user
                statusValue.value = "2";
                statusTitle.textContent = "Disable User";
                statusMessage.textContent = `Are you sure you want to disable ${userName}? They will no longer be able to access the system.`;
                statusIcon.innerHTML = `
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                    </svg>
                `;
                statusIcon.className = "mx-auto flex items-center justify-center h-14 w-14 rounded-full bg-red-100 mb-4";
                confirmBtn.className = "bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg flex items-center";
            } else {
                // Enable user
                statusValue.value = "1";
                statusTitle.textContent = "Enable User";
                statusMessage.textContent = `Are you sure you want to enable ${userName}? They will regain access to the system.`;
                statusIcon.innerHTML = `
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                `;
                statusIcon.className = "mx-auto flex items-center justify-center h-14 w-14 rounded-full bg-green-100 mb-4";
                confirmBtn.className = "bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg flex items-center";
            }
            
            // Show the modal
            statusModal.classList.remove('hidden');
            
            // Setup event handlers for closing
            document.getElementById('cancelStatusBtn').onclick = function() {
                statusModal.classList.add('hidden');
            };
            
            // Close when clicking outside the modal
            statusModal.addEventListener('click', function(e) {
                if (e.target === this) {
                    statusModal.classList.add('hidden');
                }
            }, { once: true });
        }

        // Update the setupAllModalHandlers function
        function setupAllModalHandlers() {
            setupCredentialModalHandlers();
            
            // Setup modal close when clicking outside
            const modals = [
                document.getElementById('resetPasswordModal'),
                document.getElementById('approveUserModal'),
                document.getElementById('deleteUserModal')
            ];
            
            modals.forEach(modal => {
                if (modal) {
                    modal.addEventListener('click', function(e) {
                        if (e.target === this) {
                            this.classList.add('hidden');
                        }
                    });
                }
            });
        }

        // Update your document.ready or event handlers to call setupAllModalHandlers
        document.addEventListener('DOMContentLoaded', setupAllModalHandlers);

        // Function to show the approve user confirmation modal
        function showApproveUserModal(userId, userName) {
            const modal = document.getElementById('approveUserModal');
            const message = document.getElementById('approveUserMessage');
            
            // Set the message
            message.textContent = `Are you sure you want to approve ${userName}? This will activate their account.`;
            
            // Setup confirm button
            document.getElementById('confirmApproveBtn').onclick = function() {
                // Create and submit a form programmatically
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/users/${userId}/approve`;
                
                // Add CSRF token
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = csrfToken;
                form.appendChild(csrfInput);
                
                // Append to body and submit
                document.body.appendChild(form);
                form.submit();
            };
            
            // Setup cancel button
            document.getElementById('cancelApproveBtn').onclick = closeApproveUserModal;
            
            // Show the modal
            modal.classList.remove('hidden');
            
            // Close when clicking outside the modal
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeApproveUserModal();
                }
            }, { once: true });
        }

        // Function to close the approve user modal
        function closeApproveUserModal() {
            document.getElementById('approveUserModal').classList.add('hidden');
        }

        // Function to show the delete user confirmation modal
        function showDeleteUserModal(userId, userName) {
            const modal = document.getElementById('deleteUserModal');
            const message = document.getElementById('deleteUserMessage');
            
            // Set the message
            message.textContent = `Are you sure you want to delete ${userName}? This action cannot be undone.`;
            
            // Setup confirm button
            document.getElementById('confirmDeleteBtn').onclick = function() {
                // Create and submit a form programmatically
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/users/${userId}`;
                
                // Add CSRF token
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = csrfToken;
                form.appendChild(csrfInput);
                
                // Add method DELETE
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';
                form.appendChild(methodInput);
                
                // Append to body and submit
                document.body.appendChild(form);
                form.submit();
            };
            
            // Setup cancel button
            document.getElementById('cancelDeleteBtn').onclick = closeDeleteUserModal;
            
            // Show the modal
            modal.classList.remove('hidden');
            
            // Close when clicking outside the modal
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeDeleteUserModal();
                }
            }, { once: true });
        }

        // Function to close the delete user modal
        function closeDeleteUserModal() {
            document.getElementById('deleteUserModal').classList.add('hidden');
        }
    </script>
</x-app-layout>
