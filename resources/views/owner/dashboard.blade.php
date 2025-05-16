<x-app-layout>
    <!-- Hero Section with Welcome Banner -->
    <div class="relative bg-gradient-to-r from-green-800 to-green-600 shadow-xl mb-8">
        <div class="absolute inset-0 opacity-10">
            <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                <path d="M0,0 L100,0 L100,100 L0,100 Z" fill="url(#pet-pattern)" />
            </svg>
            <defs>
                <pattern id="pet-pattern" width="10" height="10" patternUnits="userSpaceOnUse">
                    <path d="M5,2 C7,2 8,3 8,5 C8,7 7,8 5,8 C3,8 2,7 2,5 C2,3 3,2 5,2 Z" fill="currentColor" />
                </pattern>
            </defs>
        </div>
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="flex flex-col md:flex-row items-center justify-between">
                <div class="mb-6 md:mb-0">
                    <h1 class="text-3xl md:text-4xl font-bold text-white">Welcome, {{ Auth::user()->complete_name }}!</h1>
                    <p class="mt-2 text-blue-100 text-lg">Manage your pets and track their health records</p>
                </div>
                <div class="flex space-x-4">
                    <a href="{{ route('owner.createAnimalForm', ['owner_id' => Auth::user()->owner->owner_id]) }}" 
                       class="inline-flex items-center px-5 py-3 bg-white text-green-700 rounded-lg shadow-md hover:bg-green-50 transition-all duration-300">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Register New Pet
                    </a>
                    <a href="{{ route('owners.profile', ['owner_id' => Auth::user()->owner->owner_id]) }}" 
                       class="inline-flex items-center px-5 py-3 bg-blue-500 text-white rounded-lg shadow-md hover:bg-blue-800 transition-all duration-300">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        My Profile
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Dashboard Content -->
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats Overview -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Card 1 - Animals Owned -->
                <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 overflow-hidden border-b-4 border-blue-500">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Pets Registered</p>
                                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $animalsOwned }}</p>
                            </div>
                            <div class="p-3 bg-blue-100 rounded-full">
                                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('owners.profile', ['owner_id' => Auth::user()->owner->owner_id]) }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">View all pets →</a>
                        </div>
                    </div>
                </div>

                <!-- Card 2 - Successful Transactions -->
                <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 overflow-hidden border-b-4 border-green-500">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Completed Services</p>
                                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $successfulTransactions }}</p>
                            </div>
                            <div class="p-3 bg-green-100 rounded-full">
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('owner.Newtransactions', ['owner_id' => Auth::user()->owner->owner_id]) }}" class="text-sm text-green-600 hover:text-green-800 font-medium">View transaction history →</a>
                        </div>
                    </div>
                </div>

                <!-- Card 3 - Pending Transactions -->
                <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 overflow-hidden border-b-4 border-yellow-500">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Pending Services</p>
                                <p class="text-3xl font-bold text-gray-900 mt-2">
                                    {{ $pendingTransactions->count() }}
                                </p>
                            </div>
                            <div class="p-3 bg-yellow-100 rounded-full">
                                <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('owner.Newtransactions', ['owner_id' => Auth::user()->owner->owner_id, 'status' => 'zero']) }}" class="text-sm text-yellow-600 hover:text-yellow-800 font-medium">View pending services →</a>
                        </div>
                    </div>
                </div>

                <!-- Card 4 - Vaccination Status -->
                <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 overflow-hidden border-b-4 border-purple-500">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Vaccinated Pets</p>
                                <p class="text-3xl font-bold text-gray-900 mt-2">
                                    {{ $vaccinatedAnimals ?? 0 }}
                                </p>
                            </div>
                            <div class="p-3 bg-purple-100 rounded-full">
                                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('owners.profile', ['owner_id' => Auth::user()->owner->owner_id]) }}" class="text-sm text-purple-600 hover:text-purple-800 font-medium">Check vaccination status →</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column - Main Content -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Pending Appointments Section -->
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                        <div class="p-6 border-b border-gray-100">
                            <div class="flex items-center justify-between">
                                <h2 class="text-xl font-bold text-gray-800">Pending Appointments</h2>
                                <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full">
                                    {{ $pendingTransactions->count() }} Pending
                                </span>
                            </div>
                        </div>
                        
                    
                    </div>

                    <!-- Recent Pets Section -->
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                        <div class="p-6 border-b border-gray-100">
                            <h2 class="text-xl font-bold text-gray-800">Your Pets</h2>
                        </div>
                        
                        @if($recentAnimals && $recentAnimals->count() > 0)
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 p-6">
                                @foreach($recentAnimals as $animal)
                                    <div class="flex items-center space-x-4 p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                        <img class="h-16 w-16 rounded-full object-cover" 
                                             src="{{ $animal->photo_front ? asset('storage/' . $animal->photo_front) : asset('assets/default-avatar.png') }}"
                                             alt="{{ $animal->name }}">
                                        <div>
                                            <h3 class="text-lg font-medium text-gray-900">{{ $animal->name }}</h3>
                                            <p class="text-sm text-gray-500">{{ $animal->species ? $animal->species->name : 'Unknown species' }}</p>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $animal->is_vaccinated == 1 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $animal->is_vaccinated == 1 ? 'Vaccinated' : 'Not vaccinated' }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="p-4 border-t border-gray-100 bg-gray-50">
                                <a href="{{ route('owners.profile', ['owner_id' => Auth::user()->owner->owner_id]) }}" class="text-sm font-medium text-blue-600 hover:text-blue-800 flex items-center justify-center">
                                    View all pets
                                    <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>
                        @else
                            <div class="p-8 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No pets registered yet</h3>
                                <p class="mt-1 text-sm text-gray-500">Register your pets to keep track of their health records.</p>
                                <div class="mt-6">
                                    <a href="{{ route('owner.createAnimalForm', ['owner_id' => Auth::user()->owner->owner_id]) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                        </svg>
                                        Register a pet
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Veterinary Services Section -->
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                        <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-green-50 to-blue-50">
                            <h2 class="text-xl font-bold text-gray-800">Available Veterinary Services</h2>
                            <p class="text-sm text-gray-600 mt-1">Comprehensive care for your pets</p>
                        </div>
                        
                        <div class="p-6">
                            <!-- LPPD Services -->
                            <div class="mb-8">
                                <h3 class="text-lg font-semibold text-green-700 mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                                    </svg>
                                    LPPD Services
                                </h3>
                                <p class="text-sm text-gray-600 mb-4">Livestock Production and Pest Diagnosis services focused on maintaining healthy livestock.</p>
                                
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div class="bg-green-50 p-4 rounded-lg">
                                        <h4 class="font-medium text-green-800">Pregnancy Diagnosis</h4>
                                        <p class="text-xs text-gray-600 mt-1">Professional pregnancy testing for livestock</p>
                                    </div>
                                    <div class="bg-green-50 p-4 rounded-lg">
                                        <h4 class="font-medium text-green-800">Artificial Insemination</h4>
                                        <p class="text-xs text-gray-600 mt-1">Professional breeding services</p>
                                    </div>
                                    <div class="bg-green-50 p-4 rounded-lg">
                                        <h4 class="font-medium text-green-800">Technical Services</h4>
                                        <p class="text-xs text-gray-600 mt-1">Expert technical support and consultation</p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- AHWD Services -->
                            <div>
                                <h3 class="text-lg font-semibold text-blue-700 mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                    </svg>
                                    AHWD Services
                                </h3>
                                <p class="text-sm text-gray-600 mb-4">Animal Health and Welfare Division services ensuring comprehensive healthcare for all animals.</p>
                                
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                    <div class="bg-blue-50 p-3 rounded-lg">
                                        <h4 class="font-medium text-blue-800">Surveillance</h4>
                                        <p class="text-xs text-gray-600 mt-1">Health monitoring</p>
                                    </div>
                                    <div class="bg-blue-50 p-3 rounded-lg">
                                        <h4 class="font-medium text-blue-800">Farm Visit</h4>
                                        <p class="text-xs text-gray-600 mt-1">On-site evaluation</p>
                                    </div>
                                    <div class="bg-blue-50 p-3 rounded-lg">
                                        <h4 class="font-medium text-blue-800">Vaccination</h4>
                                        <p class="text-xs text-gray-600 mt-1">Preventive care</p>
                                    </div>
                                    <div class="bg-blue-50 p-3 rounded-lg">
                                        <h4 class="font-medium text-blue-800">Treatment</h4>
                                        <p class="text-xs text-gray-600 mt-1">Medical care</p>
                                    </div>
                                    <div class="bg-blue-50 p-3 rounded-lg">
                                        <h4 class="font-medium text-blue-800">Vitamin Supplementation</h4>
                                        <p class="text-xs text-gray-600 mt-1">Essential nutrients</p>
                                    </div>
                                    <div class="bg-blue-50 p-3 rounded-lg">
                                        <h4 class="font-medium text-blue-800">Health Certificate</h4>
                                        <p class="text-xs text-gray-600 mt-1">Official documentation</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                     
                    </div>
                </div>
                
                <!-- Right Column - Sidebar -->
                <div class="space-y-8">
                    <!-- Health Tips Card -->
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                        <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-indigo-50">
                            <h2 class="text-xl font-bold text-gray-800 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Pet Health Tips
                            </h2>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0">
                                        <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center">
                                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-900">Regular Vaccinations</h4>
                                        <p class="text-xs text-gray-500 mt-1">Keep your pet's vaccinations up to date to prevent common diseases.</p>
                                    </div>
                                </div>
                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0">
                                        <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center">
                                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-900">Proper Nutrition</h4>
                                        <p class="text-xs text-gray-500 mt-1">Feed your pet a balanced diet appropriate for their species, age, and health needs.</p>
                                    </div>
                                </div>
                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0">
                                        <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center">
                                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-900">Regular Exercise</h4>
                                        <p class="text-xs text-gray-500 mt-1">Ensure your pet gets appropriate exercise to maintain a healthy weight and mental stimulation.</p>
                                    </div>
                                </div>
                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0">
                                        <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center">
                                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-900">Annual Check-ups</h4>
                                        <p class="text-xs text-gray-500 mt-1">Schedule regular veterinary check-ups even when your pet seems healthy.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Quick Links -->
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                        <div class="p-6 border-b border-gray-100">
                            <h2 class="text-xl font-bold text-gray-800">Quick Links</h2>
                        </div>
                        <div class="p-6">
                            <nav class="space-y-3">
                                <a href="{{ route('owners.profile', ['owner_id' => Auth::user()->owner->owner_id]) }}" class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                    <svg class="w-5 h-5 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    <span class="text-sm font-medium text-gray-700">My Profile</span>
                                </a>
                                <a href="{{ route('owner.Newtransactions', ['owner_id' => Auth::user()->owner->owner_id]) }}" class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                    <svg class="w-5 h-5 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                    <span class="text-sm font-medium text-gray-700">Transaction History</span>
                                </a>
                                <a href="{{ route('owner.createAnimalForm', ['owner_id' => Auth::user()->owner->owner_id]) }}" class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                    <svg class="w-5 h-5 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    <span class="text-sm font-medium text-gray-700">Register New Pet</span>
                                </a>
                            </nav>
                        </div>
                    </div>
                    
                    <!-- Contact Card -->
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                        <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-purple-50 to-pink-50">
                            <h2 class="text-xl font-bold text-gray-800">Need Help?</h2>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-gray-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                    <span class="text-sm text-gray-600">+63 912 345 6789</span>
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-gray-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    <span class="text-sm text-gray-600">valenciacityvet@gmail.com</span>
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-gray-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <span class="text-sm text-gray-600"> Valencia City, Bukidnon</span>
                                </div>
                            </div>
                            <div class="mt-6">
                                <a href="#" class="inline-flex items-center justify-center w-full px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                    </svg>
                                    Contact Support
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>