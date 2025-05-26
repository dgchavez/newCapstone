<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Main Profile Section -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <!-- Profile Header with Banner -->
            <div class="relative h-48 bg-gradient-to-r from-green-800 to-green-600">
                <div class="absolute inset-0 bg-green/20"></div>
                <!-- Action Buttons -->
                <div class="absolute top-4 right-4 flex space-x-3">
                    <a href="/admin/users" 
                       class="inline-flex items-center px-4 py-2 bg-gray-500/90 backdrop-blur-sm rounded-lg text-sm font-medium text-white hover:bg-gray-500 transition-all">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Users List
                    </a>
                    <a href="{{ route('users.edit-form', ['id' => $user->user_id]) }}" 
                       class="inline-flex items-center px-4 py-2 bg-yellow-500/90 backdrop-blur-sm rounded-lg text-sm font-medium text-white hover:bg-yellow-500 transition-all">
                        <i class="fas fa-edit mr-2"></i>
                        Edit Profile
                    </a>
                </div>
            </div>

            <!-- Profile Info -->
            <div class="relative px-6 pb-6">
                <!-- Profile Image -->
                <div class="absolute -top-16 left-6">
                    <div class="relative">
                        <img class="w-32 h-32 rounded-xl object-cover border-4 border-white shadow-lg" 
                             src="{{ $user->profile_image ? Storage::url($user->profile_image) : 
                                  ($user->gender === 'Female' ? asset('assets/female-default.png') : asset('assets/male-default.png')) }}" 
                             alt="{{ $user->complete_name }}'s Profile Image">
                        <div class="absolute bottom-2 right-2 w-4 h-4 rounded-full {{ $user->status == 1 ? 'bg-green-500' : 'bg-red-500' }} border-2 border-white"></div>
                    </div>
                </div>

                <!-- Profile Details -->
                <div class="pt-20">
                    <div class="flex justify-between items-start">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">{{ $user->complete_name }}</h1>
                            <p class="text-sm text-gray-500 flex items-center mt-1">
                                <i class="fas fa-envelope mr-2"></i>
                                {{ $user->email }}
                            </p>
                            <p class="text-sm text-gray-500 flex items-center mt-1">
                                <i class="fas fa-map-marker-alt mr-2"></i>
                                {{ $user->address->street ?? 'No street information' }}, {{ $user->address->barangay->barangay_name ?? 'No barangay information' }}
                            </p>
                        </div>
                        
                        <!-- User Role & Status Badge -->
                        <div class="flex flex-col space-y-2">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                {{ $user->role == 0 ? 'bg-purple-100 text-purple-800' :
                                   ($user->role == 1 ? 'bg-blue-100 text-blue-800' :
                                   ($user->role == 2 ? 'bg-green-100 text-green-800' : 'bg-orange-100 text-orange-800')) }}">
                                <i class="fas fa-{{ $user->role == 0 ? 'shield-alt' : 
                                                   ($user->role == 1 ? 'user' : 
                                                   ($user->role == 2 ? 'user-md' : 'user-nurse')) }} mr-2"></i>
                                @switch($user->role)
                                    @case(0) Admin @break
                                    @case(1) Animal Owner @break
                                    @case(2) Veterinarian @break
                                    @case(3) Veterinary Receptionist @break
                                    @default Unknown
                                @endswitch
                            </span>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                {{ $user->status == 1 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                @switch($user->status)
                                    @case(1) Active @break
                                    @case(2) Disabled @break
                                    @default Unknown
                                @endswitch
                            </span>
                        </div>
                    </div>

                    <!-- Enhanced Personal Information Grid -->
                    <div class="mt-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                            <i class="fas fa-user-circle mr-2 text-indigo-600"></i>
                            Personal Information
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                                <div class="flex items-center space-x-3">
                                    <div class="p-2 bg-blue-100 rounded-lg">
                                        <i class="fas fa-phone text-blue-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Contact</p>
                                        <p class="text-sm font-medium text-gray-900">{{ $user->contact_no }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                                <div class="flex items-center space-x-3">
                                    <div class="p-2 bg-purple-100 rounded-lg">
                                        <i class="fas fa-venus-mars text-purple-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Gender</p>
                                        <p class="text-sm font-medium text-gray-900">{{ $user->gender }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                                <div class="flex items-center space-x-3">
                                    <div class="p-2 bg-red-100 rounded-lg">
                                        <i class="fas fa-birthday-cake text-red-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Birth Date</p>
                                        <p class="text-sm font-medium text-gray-900">{{ $user->birth_date }}</p>
                                    </div>
                                </div>
                            </div>

                            @if ($user->role == 1)
                                <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                                    <div class="flex items-center space-x-3">
                                        <div class="p-2 bg-pink-100 rounded-lg">
                                            <i class="fas fa-heart text-pink-600"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500">Civil Status</p>
                                            <p class="text-sm font-medium text-gray-900">{{ $user->owner->civil_status ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                                <div class="flex items-center space-x-3">
                                    <div class="p-2 bg-green-100 rounded-lg">
                                        <i class="fas fa-map-marker-alt text-green-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Full Address</p>
                                        <p class="text-sm font-medium text-gray-900">
                                            {{ $user->address->street ?? 'No street information' }}, {{ $user->address->barangay->barangay_name ?? 'No barangay information' }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            @if ($user->role == 1)
                                <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                                    <div class="flex items-center space-x-3">
                                        <div class="p-2 bg-indigo-100 rounded-lg">
                                            <i class="fas fa-tags text-indigo-600"></i>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-xs text-gray-500">Categories</p>
                                            <div class="text-sm font-medium text-gray-900">
                                                @if($user->categories && $user->categories->count() > 0)
                                                    {{ $user->categories->pluck('name')->take(2)->join(', ') }}
                                                    @if($user->categories->count() > 2)
                                                        <span class="text-xs text-gray-500">+{{ $user->categories->count() - 2 }} more</span>
                                                    @endif
                                                @else
                                                    No categories
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    @if ($user->role == 1 && $user->categories && $user->categories->count() > 0)
                        <!-- Categories Section (Full List) -->
                        <div class="mt-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                                <i class="fas fa-tags mr-2 text-indigo-600"></i>
                                All Categories
                            </h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach($user->categories as $category)
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
                                    
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $classes }}">
                                        {{ $category->name }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>