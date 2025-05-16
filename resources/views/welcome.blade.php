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
        
        <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.12.0/cdn.min.js" defer></script>
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
    <body class="antialiased">
        <!-- Background with parallax effect -->
        <div class="fixed inset-0 -z-10 parallax-bg" style="background-image: url('{{ asset('assets/bg.jpg') }}');"></div>
        <div class="fixed inset-0 -z-10 bg-black/20 backdrop-blur-sm"></div>

        <!-- ========== HEADER ========== -->
        <header class="sticky top-0 inset-x-0 z-50 backdrop-blur-md shadow-sm">
            <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-20">
                    <!-- Logo -->
                    <div class="flex-shrink-0">
                        <a href="/" class="flex items-center group gap-3" aria-label="Brand">
                            <div class="relative">
                                <img class="w-30 h-12 object-cover transition-all duration-300 group-hover:scale-110"
                                    src="{{ asset('assets/vet_logo.png') }}"
                                    alt="Brand Image">
                                <div class="absolute inset-0 border-2 border-white/30 transition-colors duration-300"></div>
                            </div>
                            <div class="flex flex-col justify-center">
                                <span class="text-xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-green-500 to-emerald-600 transition-all duration-300 group-hover:scale-110">VCAPIMS</span>
                            </div>
                        </a>
                    </div>

                    <!-- Desktop Navigation -->
                    <div class="hidden md:flex md:items-center md:space-x-1">
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
        <section class="relative py-16 sm:py-24 lg:py-32 overflow-hidden from-gray-50 via-white to-green-50">
  <div class="absolute inset-0 bg-pattern opacity-5 z-0"></div>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
    <div class="bg-white/90 backdrop-blur-xl overflow-hidden shadow-2xl rounded-3xl border border-green-100">
      <div class="grid lg:grid-cols-2 items-center">
        <!-- Left Column Content -->
        <div class="p-8 lg:p-12 lg:pl-16 space-y-8">
          <div class="space-y-4">
            <div class="inline-block px-3 py-1 bg-blue-100 text-green-700 rounded-full text-sm font-medium">
              Animal Welfare Initiative
            </div>
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-gray-900 leading-tight">
              Your Animal's Health is 
              <span class="text-transparent bg-clip-text bg-gradient-to-r from-green-500 to-emerald-600">
                Our Priority
              </span>
            </h1>
            <p class="mt-6 text-xl text-gray-600 leading-relaxed">
              "Tracking for Good — Enhancing Animal Welfare."
            </p>
          </div>
          <div class="flex flex-col sm:flex-row gap-4 pt-4">
            <a href="/register" class="inline-flex items-center justify-center px-8 py-4 text-lg font-semibold text-white bg-gradient-to-r from-green-600 to-emerald-500 rounded-xl hover:from-green-700 hover:to-emerald-600 transform hover:scale-105 transition-all duration-300 shadow-lg">
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
        
        <!-- Right Column Content -->
        <div class="bg-gradient-to-br from-green-50 to-emerald-50 p-8 lg:p-16 h-full flex flex-col items-center justify-center relative overflow-hidden">
          <!-- Decorative Elements -->
          <div class="absolute top-0 right-0 w-24 h-24 bg-green-200 rounded-full opacity-20 -mt-8 -mr-8"></div>
          <div class="absolute bottom-0 left-0 w-32 h-32 bg-emerald-200 rounded-full opacity-20 -mb-12 -ml-12"></div>
          
          <!-- VCAPIMS Content -->
          <h2 class="text-6xl lg:text-7xl xl:text-8xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-green-500 to-emerald-600 tracking-wider text-center mb-4">
            VCAPIMS
          </h2>
          
          <div class="h-1 w-48 bg-gradient-to-r from-green-500 to-emerald-500 rounded-full mb-6"></div>
          
          <p class="text-xl text-gray-700 leading-relaxed text-center max-w-md">
            Valencia City Animal Profiling Information Management System
          </p>
          
          <!-- Decorative Icons -->
          <div class="grid grid-cols-3 gap-8 w-full mt-8 opacity-20">
            
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<style>
.bg-pattern {
  background-image: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%2322c55e' fill-opacity='0.1' fill-rule='evenodd'/%3E%3C/svg%3E");
}

.glow-on-hover:hover {
  box-shadow: 0 0 15px rgba(16, 185, 129, 0.5);
}
</style>

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
                    <style>
                        .modal-backdrop {
                            background-color: rgba(0, 0, 0, 0.5);
                            transition: opacity 0.3s ease;
                        }
                        .modal-content {
                            transform: translateY(-50px);
                            transition: transform 0.3s ease;
                        }
                        .modal-open .modal-content {
                            transform: translateY(0);
                        }
                        /* Updated image preview styles with blur */
                        .image-preview {
                            height: 200px;
                            overflow: hidden;
                            position: relative;
                        }
                        .image-preview img {
                            width: 100%;
                            height: 100%;
                            object-fit: cover;
                            transform: scale(1.1);
                            transition: all 0.5s ease;
                            filter: blur(5px);
                        }
                        .facility-card:hover .image-preview img {
                            transform: scale(1);
                            filter: blur(0);
                        }
                        .image-overlay {
                            position: absolute;
                            inset: 0;
                            background: linear-gradient(to top, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.2) 100%);
                            opacity: 0;
                            transition: all 0.5s ease;
                        }
                        .facility-card:hover .image-overlay {
                            opacity: 1;
                        }
                        /* Add text overlay for "Click to view" */
                        .preview-text {
                            position: absolute;
                            top: 50%;
                            left: 50%;
                            transform: translate(-50%, -50%);
                            color: white;
                            font-weight: 600;
                            text-align: center;
                            opacity: 1;
                            transition: opacity 0.5s ease;
                            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
                            z-index: 2;
                        }
                        .facility-card:hover .preview-text {
                            opacity: 0;
                        }
                    </style>

                    <div x-data="{
                        facilities: [
                            {
                                icon: 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10',
                                title: 'Treatment Room',
                                description: 'State-of-the-art equipment for precise diagnosis and treatment',
                                image: '/assets/stakeholder/r/r1.jpg' 
                            },
                            {
                                icon: 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2',
                                title: 'Professional Care',
                                description: 'Expert veterinary care with a compassionate touch',
                                image: '/assets/stakeholder/ds/ds3.jpg' 
                            },
                            {
                                icon: 'M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z',
                                title: 'Advanced Equipment',
                                description: 'Latest medical technology for optimal treatment',
                                image: '/assets/stakeholder/e/e1.jpg' 
                            },
                            {
                                icon: 'M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z',
                                title: 'Recovery Area',
                                description: 'Comfortable spaces for post-treatment care',
                                image: '/assets/stakeholder/r/r2.jpg' 
                            },
                            {
                                icon: 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                                title: 'Consultation Room',
                                description: 'Private spaces for thorough medical consultations',
                                image: '/assets/stakeholder/r/1.png' 
                            },
                            {
                                icon: 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z',
                                title: 'Emergency Care Unit',
                                description: 'Ready for immediate medical attention',
                                image: '/assets/stakeholder/t/t5.jpg' 
                            }
                        ],
                        selectedFacility: null,
                        showModal: false,
                        openModal(facility) {
                            this.selectedFacility = facility;
                            this.showModal = true;
                            document.body.classList.add('overflow-hidden');
                        },
                        closeModal() {
                            this.showModal = false;
                            document.body.classList.remove('overflow-hidden');
                        }
                    }">
                        <!-- Facility Cards Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <template x-for="(facility, index) in facilities" :key="index">
                                <div 
                                    class="facility-card group relative bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-100 cursor-pointer"
                                    @click="openModal(facility)"
                                >
                                    <!-- Image Preview Section -->
                                    <div class="image-preview">
                                        <div class="preview-text">Click to view full image</div>
                                        <img :src="facility.image" :alt="facility.title" class="w-full h-full object-cover">
                                        <div class="image-overlay flex items-end p-4">
                                            <span class="text-white text-sm font-medium" x-text="facility.title"></span>
                                        </div>
                                    </div>

                                    <!-- Facility Info Section -->
                                    <div class="p-6 bg-white">
                                        <div class="flex flex-col items-center">
                                            <div class="w-12 h-12 bg-green-50 rounded-full flex items-center justify-center shadow-sm mb-4 group-hover:scale-110 transition-transform duration-300">
                                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" x-bind:d="facility.icon"/>
                                                </svg>
                                            </div>
                                            <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-green-600 transition-colors duration-200" x-text="facility.title"></h3>
                                            <p class="text-gray-600 text-center text-sm" x-text="facility.description"></p>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <!-- Modal for Image Popup -->
                        <div 
                            x-show="showModal" 
                            x-cloak 
                            class="fixed inset-0 z-50 overflow-y-auto" 
                            @click.away="closeModal()" 
                            @keydown.escape.window="closeModal()"
                        >
                            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
                                <div 
                                    x-show="showModal" 
                                    x-transition:enter="ease-out duration-300" 
                                    x-transition:enter-start="opacity-0" 
                                    x-transition:enter-end="opacity-100" 
                                    x-transition:leave="ease-in duration-200" 
                                    x-transition:leave-start="opacity-100" 
                                    x-transition:leave-end="opacity-0" 
                                    class="fixed inset-0 transition-opacity modal-backdrop"
                                >
                                </div>

                                <div 
                                    x-show="showModal" 
                                    x-transition:enter="ease-out duration-300" 
                                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                                    x-transition:leave="ease-in duration-200" 
                                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                                    class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6 modal-content"
                                >
                                    <div class="absolute top-0 right-0 pt-4 pr-4">
                                        <button @click="closeModal()" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="sm:flex sm:items-start">
                                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" x-text="selectedFacility?.title"></h3>
                                            <div class="mt-2">
                                                <img x-bind:src="selectedFacility?.image" alt="Facility Image" class="w-full h-auto rounded-lg">
                                                <p class="mt-4 text-sm text-gray-500" x-text="selectedFacility?.description"></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                                        <button 
                                            type="button" 
                                            @click="closeModal()" 
                                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm"
                                        >
                                            Close
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Team section -->
            <section id="team" class="relative bg-gray-50 py-24">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-16">
                        <span class="inline-block px-4 py-2 rounded-full bg-gradient-to-r from-green-100 to-emerald-100 text-green-600 text-sm font-semibold mb-4 shadow-sm">Our Team</span>
                        <h2 class="text-4xl font-bold text-gray-900 mb-6">Meet Our Expert Team</h2>
                        <p class="text-xl text-gray-600 leading-relaxed">Dedicated professionals committed to your animal's health</p>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                        @foreach ($veterinarians as $vet)
                        <div class="bg-white rounded-xl shadow-lg p-6 transform hover:scale-105 transition-all duration-300 border border-gray-100">
                        <div class="relative">
                            <img class="w-32 h-32 mx-auto rounded-full object-cover border-4 border-white shadow-md" 
                                src="{{ $vet->profile_image ? Storage::url($vet->profile_image) : 
                                    ($vet->gender === 'Female' ? asset('assets/f-vet.png') : asset('assets/m-vet.png')) }}" 
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
                <!-- Background image with 50% opacity -->
                <div class="absolute inset-0 overflow-hidden">
                    <img src="{{ asset('assets/stakeholder/t/t8.jpg') }}" 
                        alt="Background" 
                        class="w-full h-full object-cover opacity-20">
                </div>
                
                <!-- Pattern overlay -->
                <div class="absolute inset-0 bg-[url('{{ asset('assets/pattern.svg') }}')] opacity-10"></div>
                
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
                    <div class="text-center">
                        <h2 class="text-3xl font-bold text-white mb-6">Ready to Give Your Animal the Best Care?</h2>
                        <p class="text-xl text-green-100 mb-8 max-w-2xl mx-auto">Join our family of happy animal owners today and experience the difference in veterinary care</p>
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
                        <style>
                            .modal-backdrop {
                                background-color: rgba(0, 0, 0, 0.5);
                                transition: opacity 0.3s ease;
                            }
                            .modal-content {
                                transform: translateY(-50px);
                                transition: transform 0.3s ease;
                            }
                            .modal-open .modal-content {
                                transform: translateY(0);
                            }
                            /* New styles for service cards with blur effect */
                            .service-card {
                                position: relative;
                                overflow: hidden;
                            }
                            .service-image {
                                position: absolute;
                                top: 0;
                                left: 0;
                                width: 100%;
                                height: 100%;
                                z-index: 0;
                            }
                            .service-image img {
                                width: 100%;
                                height: 100%;
                                object-fit: cover;
                                filter: blur(5px);
                                transform: scale(1.1);
                                transition: all 0.5s ease;
                                opacity: 0.15;
                            }
                            .service-card:hover .service-image img {
                                filter: blur(0);
                                transform: scale(1);
                                opacity: 0.25;
                            }
                            .service-content {
                                position: relative;
                                z-index: 1;
                                background: transparent;
                            }
                            .preview-hint {
                                position: absolute;
                                bottom: 10px;
                                right: 10px;
                                color: #059669;
                                font-size: 0.875rem;
                                font-weight: 500;
                                opacity: 0;
                                transition: opacity 0.3s ease;
                            }
                            .service-card:hover .preview-hint {
                                opacity: 1;
                            }
                        </style>

                        <div x-data="{
                            selectedService: null,
                            showModal: false,
                            openModal(service) {
                                this.selectedService = service;
                                this.showModal = true;
                                document.body.classList.add('overflow-hidden');
                            },
                            closeModal() {
                                this.showModal = false;
                                document.body.classList.remove('overflow-hidden');
                            }
                        }">
                            <!-- Service Cards Grid -->
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach ([
                                    [
                                        'title' => 'Surveillance & Investigation',
                                        'description' => 'Monitoring and investigation of animal health issues',
                                        'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                                        'color' => 'emerald',
                                        'image' => '/assets/stakeholder/t/t9.jpg' 
                                    ],
                                    [
                                        'title' => 'Farm Visit',
                                        'description' => 'On-site evaluation and consultation services',
                                        'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
                                        'color' => 'green',
                                        'image' => '/assets/stakeholder/t/t2.jpg'
                                    ],
                                    [
                                        'title' => 'Vaccination',
                                        'description' => 'Preventive care and immunization programs',
                                        'icon' => 'M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z',
                                        'color' => 'teal',
                                        'image' => '/assets/stakeholder/ra/ra1.jpg'
                                    ],
                                    [
                                        'title' => 'Treatment',
                                        'description' => 'Professional medical care for all conditions',
                                        'icon' => 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z',
                                        'color' => 'green',
                                        'image' => '/assets/stakeholder/ai/ai16.jpg'
                                    ],
                                    [
                                        'title' => 'Vitamin Supplementation',
                                        'description' => 'Essential nutrients for optimal health',
                                        'icon' => 'M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z',
                                        'color' => 'emerald',
                                        'image' => '/assets/stakeholder/ai/ai1.jpg'
                                    ],
                                    [
                                        'title' => 'Health Certificate',
                                        'description' => 'Official documentation for transport',
                                        'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
                                        'color' => 'teal',
                                        'image' => '/assets/stakeholder/cert.jpg'
                                    ]
                                ] as $index => $service)
                                <div 
                                    class="service-card group relative bg-white rounded-xl overflow-hidden hover:shadow-lg transition-all duration-300 border border-gray-100 hover:border-green-100 cursor-pointer"
                                    @click="openModal({
                                        title: '{{ $service['title'] }}',
                                        description: '{{ $service['description'] }}',
                                        image: '{{ $service['image'] }}'
                                    })"
                                >
                                    <!-- Background Image with Blur -->
                                    <div class="service-image">
                                        <img src="{{ $service['image'] }}" alt="{{ $service['title'] }}">
                                    </div>

                                    <!-- Service Content -->
                                    <div class="service-content p-6">
                                        <div class="flex items-start space-x-4">
                                            <div class="flex-shrink-0">
                                                <div class="w-12 h-12 bg-gradient-to-r from-green-50/90 to-emerald-50/90 backdrop-blur-sm rounded-lg flex items-center justify-center group-hover:from-green-100/90 group-hover:to-emerald-100/90 transition-colors duration-200 shadow-sm">
                                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $service['icon'] }}"/>
                                                    </svg>
                                                </div>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <h4 class="text-lg font-semibold text-gray-900 group-hover:text-green-600 transition-colors duration-200">
                                                    {{ $service['title'] }}
                                                </h4>
                                                <p class="mt-2 text-sm text-gray-700 leading-relaxed backdrop-blur-sm bg-white/50 p-2 rounded">
                                                    {{ $service['description'] }}
                                                </p>
                                            </div>
                                        </div>
                                        <span class="preview-hint">Click to view details →</span>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <!-- Modal for Image Popup -->
                            <div 
                                x-show="showModal" 
                                x-cloak 
                                class="fixed inset-0 z-50 overflow-y-auto" 
                                @click.away="closeModal()" 
                                @keydown.escape.window="closeModal()"
                            >
                                <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
                                    <div 
                                        x-show="showModal" 
                                        x-transition:enter="ease-out duration-300" 
                                        x-transition:enter-start="opacity-0" 
                                        x-transition:enter-end="opacity-100" 
                                        x-transition:leave="ease-in duration-200" 
                                        x-transition:leave-start="opacity-100" 
                                        x-transition:leave-end="opacity-0" 
                                        class="fixed inset-0 transition-opacity modal-backdrop"
                                    >
                                    </div>

                                    <div 
                                        x-show="showModal" 
                                        x-transition:enter="ease-out duration-300" 
                                        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                                        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                                        x-transition:leave="ease-in duration-200" 
                                        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                                        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                                        class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6 modal-content"
                                    >
                                        <div class="absolute top-0 right-0 pt-4 pr-4">
                                            <button @click="closeModal()" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>
                                        <div class="sm:flex sm:items-start">
                                            <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                                                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" x-text="selectedService?.title"></h3>
                                                <div class="mt-2">
                                                    <img x-bind:src="selectedService?.image" alt="Service Image" class="w-full h-auto rounded-lg">
                                                    <p class="mt-4 text-sm text-gray-500" x-text="selectedService?.description"></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                                            <button 
                                                type="button" 
                                                @click="closeModal()" 
                                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm"
                                            >
                                                Close
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                

        <!-- ========== HERO SECTION ========== -->
        <section class="relative">
            <div class="max-w-6xl mx-auto px-4 sm:px-6">                
            </div>
        </div>
        </section>
        </div>

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
                        <div id="qrModal" class="fixed inset-0 z-50 flex items-center justify-center transition-opacity duration-300 ease-in-out opacity-0 pointer-events-none">
                            <!-- Backdrop -->
                            <div class="absolute inset-0 bg-black bg-opacity-50 transition-opacity duration-300"></div>
                            
                            <!-- Modal Content -->
                            <div class="relative bg-white rounded-xl p-6 max-w-sm mx-auto shadow-2xl transform transition-all duration-300 ease-out scale-95">
                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="text-xl font-bold text-gray-800">Scan QR Code</h3>
                                    <button onclick="closeQrModal()" class="text-gray-500 hover:text-gray-700 focus:outline-none">
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
                                const modal = document.getElementById('qrModal');
                                modal.classList.remove('pointer-events-none');
                                modal.classList.remove('opacity-0');
                                modal.querySelector('.transform').classList.remove('scale-95');
                                document.body.classList.add('overflow-hidden');
                            }

                            function closeQrModal() {
                                const modal = document.getElementById('qrModal');
                                modal.classList.add('opacity-0');
                                modal.classList.add('pointer-events-none');
                                modal.querySelector('.transform').classList.add('scale-95');
                                document.body.classList.remove('overflow-hidden');
                            }

                            // Close modal when clicking outside
                            document.getElementById('qrModal').addEventListener('click', function(e) {
                                if (e.target === this) {
                                    closeQrModal();
                                }
                            });

                            // Close modal with ESC key
                            document.addEventListener('keydown', function(e) {
                                if (e.key === 'Escape') {
                                    closeQrModal();
                                }
                            });
                        </script>
                    </div>
                    <h3 class="text-2xl font-semibold mb-2">VCAPIMS</h3>
                    <p class="text-gray-400 max-w-md mx-auto">Providing exceptional care for your beloved animals with compassion and expertise</p>
                    <div class="mt-8 flex justify-center space-x-6">
                        <a href="https://www.facebook.com/profile.php?id=100089808807446" 
                        target="_blank" 
                        rel="noopener noreferrer"
                        class="text-gray-400 hover:text-white transition-colors duration-200">
                            <span class="sr-only">Facebook</span>
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd"/>
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

    
</html>