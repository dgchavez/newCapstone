<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name') }}</title>
        <!-- fav.ico -->
        <link rel="icon" href="{{ asset('assets/2.png') }}" type="image/png">
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
        <!-- Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            .gradient-text {
                background: linear-gradient(90deg, #059669 0%, #10b981 100%);
                -webkit-background-clip: text;
                background-clip: text;
                color: transparent;
            }
            .glow-on-hover {
                transition: box-shadow 0.3s ease;
            }
            .glow-on-hover:hover {
                box-shadow: 0 0 15px rgba(16, 185, 129, 0.6);
            }
            .floating {
                animation: floating 6s ease-in-out infinite;
            }
            @keyframes floating {
                0% { transform: translateY(0px); }
                50% { transform: translateY(-15px); }
                100% { transform: translateY(0px); }
            }
            .parallax-bg {
                background-attachment: fixed;
                background-position: center;
                background-repeat: no-repeat;
                background-size: cover;
            }
        </style>
    </head>
    <body class="antialiased font-sans min-h-screen flex flex-col bg-gray-50">
        <!-- Background with parallax effect -->
        <div class="fixed inset-0 -z-10 parallax-bg" style="background-image: url('{{ asset('assets/bg.jpg') }}');"></div>
        <div class="fixed inset-0 -z-10 bg-black/20 backdrop-blur-sm"></div>

        <!-- ========== HEADER ========== -->
        <header class="sticky top-0 inset-x-0 z-50 bg-white/80 backdrop-blur-md shadow-sm">
            <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-20">
                    <!-- Logo -->
                    <div class="flex-shrink-0">
                        <a href="/" class="flex items-center group" aria-label="Brand">
                            <div class="relative">
                                <img class="w-30 h-12 object-cover transition-all duration-300 group-hover:scale-110" 
                                    src="{{ asset('assets/vet_header.png') }}" 
                                    alt="Brand Image">
                                <div class="absolute inset-0 border-2 border-white/30  transition-colors duration-300"></div>
                            </div>
                        </a>
                    </div>

                    <!-- Desktop Navigation -->
                    <div class="hidden md:flex md:items-center md:space-x-1">
                        <a href="#" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-green-600 hover:bg-green-50 rounded-lg transition-all duration-200" aria-current="page">
                            Home
                        </a>
                        <a href="#team" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-green-600 hover:bg-green-50 rounded-lg transition-all duration-200">
                            Team
                        </a>
                        <a href="#features" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-green-600 hover:bg-green-50 rounded-lg transition-all duration-200">
                            Features
                        </a>
                        <a href="#gallery" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-green-600 hover:bg-green-50 rounded-lg transition-all duration-200">
                            Gallery
                        </a>
                        <a href="#services" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-green-600 hover:bg-green-50 rounded-lg transition-all duration-200">
                            Services
                        </a>
                    </div>

                    <!-- Auth Buttons -->
                    <div class="hidden md:flex md:items-center md:space-x-3">
                        <a href="/login" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-green-600 transition-colors duration-200">
                            Log in
                        </a>
                        <a href="/register" class="px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-green-600 to-emerald-500 rounded-lg hover:from-green-700 hover:to-emerald-600 transition-all duration-300 shadow-md hover:shadow-lg glow-on-hover">
                            Register
                        </a>
                    </div>

                    <!-- Mobile menu button -->
                    <div class="md:hidden">
                        <button type="button" 
                                class="inline-flex items-center justify-center p-2 rounded-md text-gray-700 hover:text-green-600 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-green-500 transition-all duration-200"
                                onclick="toggleMobileMenu()">
                            <svg id="menuOpen" class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                            <svg id="menuClose" class="h-6 w-6 hidden" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Mobile menu -->
                <div id="mobileMenu" class="hidden md:hidden pb-4">
                    <div class="px-2 pt-2 pb-3 space-y-1">
                        <a href="#" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-green-600 hover:bg-gray-50 rounded-md transition-colors duration-200">
                            Home
                        </a>
                        <a href="#team" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-green-600 hover:bg-gray-50 rounded-md transition-colors duration-200">
                            Team
                        </a>
                        <a href="#features" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-green-600 hover:bg-gray-50 rounded-md transition-colors duration-200">
                            Features
                        </a>
                        <a href="#gallery" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-green-600 hover:bg-gray-50 rounded-md transition-colors duration-200">
                            Gallery
                        </a>
                        <a href="#services" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-green-600 hover:bg-gray-50 rounded-md transition-colors duration-200">
                            Services
                        </a>
                        <!-- Mobile Auth Buttons -->
                        <div class="mt-4 space-y-2 pb-3">
                            <a href="/login" class="block w-full px-4 py-2 text-center text-base font-medium text-gray-700 hover:text-green-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                                Log in
                            </a>
                            <a href="/register" class="block w-full px-4 py-2 text-center text-base font-medium text-white bg-gradient-to-r from-green-600 to-emerald-500 rounded-lg hover:from-green-700 hover:to-emerald-600 transition-all duration-300 shadow-md">
                                Register
                            </a>
                        </div>
                    </div>
                </div>
            </nav>
        </header>
        <!-- ========== END HEADER ========== -->

        <!-- ========== HERO ========== -->
        <main class="flex-1">
            <section class="relative pt-24 pb-32 overflow-hidden">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="bg-white/90 backdrop-blur-sm overflow-hidden shadow-2xl rounded-3xl p-8 lg:p-12">
                        <div class="grid lg:grid-cols-2 gap-12 items-center">
                            <div class="space-y-8">
                                <div>
                                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-gray-900 leading-tight">
                                        Your Pet's Health is 
                                        <span class="gradient-text">Our Priority</span>
                                    </h1>
                                    <p class="mt-6 text-xl text-gray-600 leading-relaxed">
                                    "Tracking for Good — Enhancing Animal Welfare."
                                    </p>
                                </div>
                                <div class="flex flex-col sm:flex-row gap-4">
                                    <a href="/register" class="inline-flex items-center justify-center px-8 py-4 text-lg font-semibold text-white bg-gradient-to-r from-green-600 to-emerald-500 rounded-xl hover:from-green-700 hover:to-emerald-600 transform hover:scale-105 transition-all duration-300 shadow-lg glow-on-hover">
                                        Get Started
                                        <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                    <a href="#services" class="inline-flex items-center justify-center px-8 py-4 text-lg font-semibold text-green-600 bg-green-50 rounded-xl hover:bg-green-100 transform hover:scale-105 transition-all duration-300 shadow-md">
                                        Our Services
                                    </a>
                                </div>
                            </div>
                            <div class="hidden lg:block floating">
                                <img class="w-full rounded-2xl shadow-2xl" 
                                     src="{{ asset('assets/vet_logo-2.3.png') }}" 
                                     alt="Animal Care Image">
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Features Section -->
            <section id="features" class="relative bg-white py-24">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <!-- Section Header -->
                    <div class="text-center max-w-3xl mx-auto mb-20">
                        <span class="inline-block px-4 py-2 rounded-full bg-gradient-to-r from-green-100 to-emerald-100 text-green-600 text-sm font-semibold mb-4 shadow-sm">Our Features</span>
                        <h2 class="text-4xl font-bold text-gray-900 sm:text-5xl mb-6">Why Choose Our Services?</h2>
                        <p class="text-xl text-gray-600 leading-relaxed">Experience exceptional veterinary care with our comprehensive range of services and dedicated team</p>
                    </div>

                    <!-- Features Grid -->
                    <div class="grid md:grid-cols-3 gap-8">
                        <!-- Feature Cards -->
                        @foreach ([
                            [
                                'icon' => 'M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z',
                                'title' => 'Modern Equipment',
                                'description' => 'State-of-the-art facilities for accurate diagnosis and treatment'
                            ],
                            [
                                'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z',
                                'title' => 'Expert Team',
                                'description' => 'Experienced veterinarians dedicated to animal care'
                            ],
                            [
                                'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                                'title' => 'Weekdays: 8 AM - 5 PM',
                                'description' => 'Emergency veterinary services available during business hours.'
                            ]
                        ] as $feature)
                        <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transform hover:-translate-y-2 transition-all duration-300 border border-gray-100">
                            <div class="w-14 h-14 bg-gradient-to-r from-green-100 to-emerald-100 rounded-xl flex items-center justify-center mb-6 shadow-sm">
                                <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $feature['icon'] }}"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-3">{{ $feature['title'] }}</h3>
                            <p class="text-gray-600">{{ $feature['description'] }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
            </section>

            <!-- Gallery Section -->
            <section id="gallery" class="relative bg-gradient-to-b from-gray-50 to-white py-24">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <!-- Gallery Header -->
                    <div class="text-center max-w-3xl mx-auto mb-20">
                        <span class="inline-block px-4 py-2 rounded-full bg-gradient-to-r from-green-100 to-emerald-100 text-green-600 text-sm font-semibold mb-4 shadow-sm">Our Gallery</span>
                        <h2 class="text-4xl font-bold text-gray-900 sm:text-5xl mb-6">See Our Facility & Care</h2>
                        <p class="text-xl text-gray-600 leading-relaxed">Take a look at our modern facilities and the exceptional care we provide</p>
                    </div>

                    <!-- Gallery Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ([
                            [
                                'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10',
                                'title' => 'Modern Treatment Room',
                                'description' => 'State-of-the-art equipment for precise diagnosis and treatment'
                            ],
                            [
                                'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2',
                                'title' => 'Professional Care',
                                'description' => 'Expert veterinary care with a compassionate touch'
                            ],
                            [
                                'icon' => 'M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z',
                                'title' => 'Advanced Equipment',
                                'description' => 'Latest medical technology for optimal treatment'
                            ],
                            [
                                'icon' => 'M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z',
                                'title' => 'Recovery Area',
                                'description' => 'Comfortable spaces for post-treatment care'
                            ],
                            [
                                'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                                'title' => 'Consultation Room',
                                'description' => 'Private spaces for thorough medical consultations'
                            ],
                            [
                                'icon' => 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z',
                                'title' => 'Emergency Care Unit',
                                'description' => 'Ready for immediate medical attention'
                            ]
                        ] as $item)
                        <div class="group relative bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-100">
                            <div class="aspect-w-16 aspect-h-12 bg-gradient-to-br from-green-50 to-emerald-50 p-8 flex flex-col items-center justify-center">
                                <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center shadow-md mb-6 group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-green-600 transition-colors duration-200">
                                    {{ $item['title'] }}
                                </h3>
                                <p class="text-gray-600 text-center text-sm">
                                    {{ $item['description'] }}
                                </p>
                            </div>
                            <div class="absolute inset-0 bg-gradient-to-t from-green-600/80 via-green-500/0 opacity-0 group-hover:opacity-100 transition-all duration-300">
                                <div class="absolute bottom-0 left-0 right-0 p-6 text-white transform translate-y-full group-hover:translate-y-0 transition-transform duration-300">
                                    <p class="text-sm font-medium">Click to view more</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- View More Button -->
                    <div class="mt-16 text-center">
                        <button class="inline-flex items-center px-8 py-4 text-lg font-semibold text-white bg-gradient-to-r from-green-600 to-emerald-500 rounded-xl hover:from-green-700 hover:to-emerald-600 transform hover:scale-105 transition-all duration-300 shadow-lg glow-on-hover">
                            View Full Gallery
                            <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </section>

            <!-- Team section -->
            <section id="team" class="relative bg-gray-50 py-24">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-16">
                        <span class="inline-block px-4 py-2 rounded-full bg-gradient-to-r from-green-100 to-emerald-100 text-green-600 text-sm font-semibold mb-4 shadow-sm">Our Team</span>
                        <h2 class="text-4xl font-bold text-gray-900 mb-6">Meet Our Expert Team</h2>
                        <p class="text-xl text-gray-600 leading-relaxed">Dedicated professionals committed to your pet's health</p>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                        @foreach ($veterinarians as $vet)
                        <div class="bg-white rounded-xl shadow-lg p-6 transform hover:scale-105 transition-all duration-300 border border-gray-100">
                            <div class="relative">
                                <img class="w-32 h-32 mx-auto rounded-full object-cover border-4 border-white shadow-md" 
                                     src="{{ $vet->profile_image ? Storage::url($vet->profile_image) : asset('assets/default-avatar.png') }}" 
                                     alt="{{ $vet->complete_name }}">
                                <div class="absolute -bottom-2 -right-2 bg-green-500 text-white rounded-full p-2 shadow-lg">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="mt-6 text-center">
                                <h3 class="text-xl font-semibold text-gray-900">{{ $vet->complete_name }}</h3>
                                <p class="mt-2 text-green-600 font-medium">{{ $vet->designation->name ?? 'Veterinarian' }}</p>
                                <div class="mt-4 flex justify-center space-x-3">
                                    <a href="#" class="text-gray-400 hover:text-green-500 transition-colors duration-200">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                                        </svg>
                                    </a>
                                    <a href="#" class="text-gray-400 hover:text-green-500 transition-colors duration-200">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm-2 16h-2v-6h2v6zm-1-6.891c-.607 0-1.1-.496-1.1-1.109 0-.612.492-1.109 1.1-1.109s1.1.497 1.1 1.109c0 .613-.493 1.109-1.1 1.109zm8 6.891h-1.998v-2.861c0-1.881-2.002-1.722-2.002 0v2.861h-2v-6h2v1.093c.872-1.616 4-1.736 4 1.548v3.359z"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </section>
            
            <!-- Call to Action Section -->
            <div class="relative bg-gradient-to-r from-green-600 to-emerald-500 py-20">
                <div class="absolute inset-0 overflow-hidden">
                    <div class="absolute inset-0 bg-[url('{{ asset('assets/pattern.svg') }}'] opacity-10"></div>
                </div>
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
                    <div class="text-center">
                        <h2 class="text-3xl font-bold text-white mb-6">Ready to Give Your Pet the Best Care?</h2>
                        <p class="text-xl text-green-100 mb-8 max-w-2xl mx-auto">Join our family of happy pet owners today and experience the difference in veterinary care</p>
                        <a href="/register" class="inline-flex items-center px-8 py-4 text-lg font-semibold text-green-600 bg-white rounded-xl hover:bg-green-50 transform hover:scale-105 transition-all duration-300 shadow-lg glow-on-hover">
                            Get Started Now
                            <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </main>
        <!-- ========== END HERO ========== -->
        
        <!-- Services Section -->
        <section id="services" class="relative bg-gradient-to-b from-gray-50 to-white py-24">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-20">
                    <span class="inline-block px-4 py-2 rounded-full bg-gradient-to-r from-green-100 to-emerald-100 text-green-600 text-sm font-semibold mb-4 shadow-sm">Our Services</span>
                    <h2 class="text-4xl font-bold text-gray-900 mb-6">Comprehensive Veterinary Services</h2>
                    <p class="text-xl text-gray-600 leading-relaxed max-w-3xl mx-auto">Professional care for livestock and animal welfare with the highest standards of veterinary medicine</p>
                </div>
      
                <!-- LPPD Section -->
                <div class="mb-32">
                    <div class="flex items-center justify-center mb-12">
                        <div class="bg-gradient-to-r from-green-500 to-emerald-400 h-1 w-16 mr-4"></div>
                        <h3 class="text-3xl font-bold text-gray-900">LPPD Services</h3>
                        <div class="bg-gradient-to-r from-green-500 to-emerald-400 h-1 w-16 ml-4"></div>
                    </div>
                    <p class="text-center text-gray-600 mb-12 max-w-3xl mx-auto leading-relaxed">
                        Livestock Production and Pest Diagnosis services focused on maintaining healthy livestock and optimal breeding programs with cutting-edge technology
                    </p>

                    <div class="grid lg:grid-cols-3 gap-8">
                        <!-- LPPD Service 1 -->
                        <div class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-xl transform hover:-translate-y-2 transition-all duration-300 border border-gray-100">
                            <div class="relative h-64">
                                <div class="absolute inset-0 bg-gradient-to-r from-green-600 to-emerald-500 opacity-90"></div>
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <div class="text-center text-white p-6">
                                        <div class="w-16 h-16 mx-auto mb-4 bg-white/20 rounded-full flex items-center justify-center">
                                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <h4 class="text-2xl font-bold">Pregnancy Diagnosis</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="p-8">
                                <p class="text-gray-600 mb-6">Professional pregnancy testing and monitoring services for livestock with expert evaluation and health tracking.</p>
                                <ul class="space-y-3">
                                    <li class="flex items-center text-gray-700">
                                        <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Early Detection Systems
                                    </li>
                                    <li class="flex items-center text-gray-700">
                                        <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Regular Monitoring
                                    </li>
                                    <li class="flex items-center text-gray-700">
                                        <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Health Assessment
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- LPPD Service 2 -->
                        <div class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-xl transform hover:-translate-y-2 transition-all duration-300 border border-gray-100">
                            <div class="relative h-64">
                                <div class="absolute inset-0 bg-gradient-to-r from-green-600 to-emerald-500 opacity-90"></div>
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <div class="text-center text-white p-6">
                                        <div class="w-16 h-16 mx-auto mb-4 bg-white/20 rounded-full flex items-center justify-center">
                                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                                            </svg>
                                        </div>
                                        <h4 class="text-2xl font-bold">Artificial Insemination</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="p-8">
                                <p class="text-gray-600 mb-6">Professional breeding services with careful selection and timing for optimal results.</p>
                                <ul class="space-y-3">
                                    <li class="flex items-center text-gray-700">
                                        <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Breed Selection
                                    </li>
                                    <li class="flex items-center text-gray-700">
                                        <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Timing Optimization
                                    </li>
                                    <li class="flex items-center text-gray-700">
                                        <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Success Monitoring
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- LPPD Service 3 -->
                        <div class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-xl transform hover:-translate-y-2 transition-all duration-300 border border-gray-100">
                            <div class="relative h-64">
                                <div class="absolute inset-0 bg-gradient-to-r from-green-600 to-emerald-500 opacity-90"></div>
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <div class="text-center text-white p-6">
                                        <div class="w-16 h-16 mx-auto mb-4 bg-white/20 rounded-full flex items-center justify-center">
                                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                            </svg>
                                        </div>
                                        <h4 class="text-2xl font-bold">Technical Services</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="p-8">
                                <p class="text-gray-600 mb-6">Expert technical support and consultation for optimal livestock management.</p>
                                <ul class="space-y-3">
                                    <li class="flex items-center text-gray-700">
                                        <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Farm Consultation
                                    </li>
                                    <li class="flex items-center text-gray-700">
                                        <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Management Advice
                                    </li>
                                    <li class="flex items-center text-gray-700">
                                        <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Problem Solving
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- AHWD Section -->
                <div class="bg-white py-16 rounded-3xl shadow-sm">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <!-- Section Header -->
                        <div class="text-center mb-12">
                            <div class="inline-flex items-center justify-center space-x-4">
                                <span class="h-px w-16 bg-gradient-to-r from-green-500 to-emerald-400"></span>
                                <h3 class="text-2xl font-bold text-gray-900">AHWD Services</h3>
                                <span class="h-px w-16 bg-gradient-to-r from-green-500 to-emerald-400"></span>
                            </div>
                            <p class="mt-4 text-gray-600 max-w-2xl mx-auto leading-relaxed">
                                Animal Health and Welfare Division services ensuring comprehensive healthcare for all animals with compassion and expertise
                            </p>
                        </div>

                        <!-- Services Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach ([
                                [
                                    'title' => 'Surveillance & Investigation',
                                    'description' => 'Monitoring and investigation of animal health issues',
                                    'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                                    'color' => 'emerald'
                                ],
                                [
                                    'title' => 'Farm Visit',
                                    'description' => 'On-site evaluation and consultation services',
                                    'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
                                    'color' => 'green'
                                ],
                                [
                                    'title' => 'Vaccination',
                                    'description' => 'Preventive care and immunization programs',
                                    'icon' => 'M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z',
                                    'color' => 'teal'
                                ],
                                [
                                    'title' => 'Treatment',
                                    'description' => 'Professional medical care for all conditions',
                                    'icon' => 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z',
                                    'color' => 'green'
                                ],
                                [
                                    'title' => 'Vitamin Supplementation',
                                    'description' => 'Essential nutrients for optimal health',
                                    'icon' => 'M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z',
                                    'color' => 'emerald'
                                ],
                                [
                                    'title' => 'Health Certificate',
                                    'description' => 'Official documentation for transport',
                                    'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
                                    'color' => 'teal'
                                ]
                            ] as $service)
                            <div class="group relative bg-white rounded-xl p-6 hover:shadow-lg transition-all duration-300 border border-gray-100 hover:border-green-100">
                                <div class="flex items-start space-x-4">
                                    <div class="flex-shrink-0">
                                        <div class="w-12 h-12 bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg flex items-center justify-center group-hover:from-green-100 group-hover:to-emerald-100 transition-colors duration-200 shadow-sm">
                                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $service['icon'] }}"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-lg font-semibold text-gray-900 group-hover:text-green-600 transition-colors duration-200">
                                            {{ $service['title'] }}
                                        </h4>
                                        <p class="mt-2 text-sm text-gray-500 leading-relaxed">
                                            {{ $service['description'] }}
                                        </p>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <svg class="w-5 h-5 text-gray-400 group-hover:text-green-500 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <!-- Action Button -->
                        <div class="mt-12 text-center">
                            <a href="#contact" class="inline-flex items-center px-8 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-gradient-to-r from-green-600 to-emerald-500 hover:from-green-700 hover:to-emerald-600 transition-all duration-300 shadow-lg glow-on-hover">
                                Contact Us Today
                                <svg class="ml-2 -mr-1 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ========== FOOTER ========== -->
        <footer class="bg-gray-900 text-white pt-20 pb-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <div class="flex justify-center mb-8">
                        <!-- Logo with click handler -->
                        <div class="relative">
                            <button onclick="openQrModal()" class="focus:outline-none">
                                <img class="h-24 w-24 rounded-full object-cover border-4 border-white shadow-lg cursor-pointer hover:shadow-xl transition-shadow" 
                                    src="{{ asset('assets/logo2.png') }}" 
                                    alt="Logo">
                                <div class="absolute -bottom-2 -right-2 bg-green-500 text-white rounded-full p-2 shadow-lg">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            </button>
                        </div>

                        <!-- QR Code Modal -->
                        <div id="qrModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black bg-opacity-50">
                            <div class="bg-white rounded-xl p-6 max-w-sm mx-auto shadow-2xl">
                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="text-xl font-bold text-gray-800">Scan QR Code</h3>
                                    <button onclick="closeQrModal()" class="text-gray-500 hover:text-gray-700">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>
                                <div class="p-4 bg-white rounded-lg">
                                    <img src="{{ asset('assets/vet_qr.png') }}" alt="QR Code" class="w-full h-auto">
                                </div>
                                <p class="mt-4 text-center text-gray-600">Scan this code using your mobile device to visit our website</p>
                            </div>
                        </div>

                        <script>
                            function openQrModal() {
                                document.getElementById('qrModal').classList.remove('hidden');
                                document.body.classList.add('overflow-hidden');
                            }

                            function closeQrModal() {
                                document.getElementById('qrModal').classList.add('hidden');
                                document.body.classList.remove('overflow-hidden');
                            }

                            // Close modal when clicking outside
                            document.getElementById('qrModal').addEventListener('click', function(e) {
                                if (e.target === this) {
                                    closeQrModal();
                                }
                            });
                        </script>
                    </div>
                    <h3 class="text-2xl font-semibold mb-2">VCAPIMS</h3>
                    <p class="text-gray-400 max-w-md mx-auto">Providing exceptional care for your beloved pets with compassion and expertise</p>
                    <div class="mt-8 flex justify-center space-x-6">
                        <a href="#" class="text-gray-400 hover:text-white transition-colors duration-200">
                            <span class="sr-only">Facebook</span>
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd"/>
                            </svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors duration-200">
                            <span class="sr-only">Instagram</span>
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd"/>
                            </svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors duration-200">
                            <span class="sr-only">Twitter</span>
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"/>
                            </svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors duration-200">
                            <span class="sr-only">GitHub</span>
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd"/>
                            </svg>
                        </a>
                    </div>
                    <div class="mt-8 text-gray-400 text-sm">
                        <p>© 2024 VCAPIMS. All rights reserved.</p>
                        <p class="mt-2">Designed with ❤️ for animal welfare</p>
                    </div>
                </div>
            </div>
        </footer>
        <!-- ========== END FOOTER ========== -->

        <!-- Back to top button -->
        <button id="backToTop" class="fixed bottom-8 right-8 bg-green-600 text-white p-3 rounded-full shadow-lg opacity-0 invisible transition-all duration-300 hover:bg-green-700">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
            </svg>
        </button>

        <script>
            function toggleMobileMenu() {
                const mobileMenu = document.getElementById('mobileMenu');
                const menuOpen = document.getElementById('menuOpen');
                const menuClose = document.getElementById('menuClose');
                
                mobileMenu.classList.toggle('hidden');
                menuOpen.classList.toggle('hidden');
                menuClose.classList.toggle('hidden');
            }

            // Close mobile menu when clicking on a link
            document.querySelectorAll('#mobileMenu a').forEach(link => {
                link.addEventListener('click', () => {
                    document.getElementById('mobileMenu').classList.add('hidden');
                    document.getElementById('menuOpen').classList.remove('hidden');
                    document.getElementById('menuClose').classList.add('hidden');
                });
            });

            // Back to top button
            const backToTopButton = document.getElementById('backToTop');
            window.addEventListener('scroll', () => {
                if (window.pageYOffset > 300) {
                    backToTopButton.classList.remove('opacity-0', 'invisible');
                    backToTopButton.classList.add('opacity-100', 'visible');
                } else {
                    backToTopButton.classList.remove('opacity-100', 'visible');
                    backToTopButton.classList.add('opacity-0', 'invisible');
                }
            });

            backToTopButton.addEventListener('click', () => {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });

            // Smooth scrolling for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    document.querySelector(this.getAttribute('href')).scrollIntoView({
                        behavior: 'smooth'
                    });
                });
            });
        </script>
    </body>
</html>