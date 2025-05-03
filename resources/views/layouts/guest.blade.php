<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Veterinary Office Management System">

    <title>{{ config('app.name', 'Veterinary Office') }}</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('assets/2.png') }}" type="image/png">
    
    <!-- Preload critical assets -->
    <link rel="preload" href="{{ asset('assets/bg.jpg') }}" as="image">
    <link rel="preload" href="{{ asset('assets/logo2.png') }}" as="image">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fadeIn 0.5s ease-out forwards;
        }
    </style>
</head>

<body class="font-sans text-gray-900 antialiased bg-gray-50 h-full">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-cover bg-center bg-no-repeat bg-fixed" 
         style="background-image: url('{{ asset('assets/bg.jpg') }}');">
        
        <!-- Logo with animation -->
        <div class="text-center mb-8 animate-fade-in" style="animation-delay: 0.1s;">
            <a href="/" wire:navigate class="inline-block transition-transform hover:scale-105 active:scale-95">
                <img class="h-24 w-auto mx-auto drop-shadow-lg" src="{{ asset('assets/logo2.png') }}" alt="Veterinary Office Logo">
                
            </a>
        </div>

        <!-- Form Container with glass morphism effect -->
        <div class="w-full sm:max-w-4xl mt-6 px-6 py-8 bg-white/90 backdrop-blur-sm shadow-2xl overflow-hidden sm:rounded-lg transition-all duration-300 hover:shadow-xl animate-fade-in" style="animation-delay: 0.2s;">
            <div class="flex flex-col md:flex-row gap-8">
                <!-- Image Column (Hidden on mobile) -->
                <div class="hidden md:block md:w-1/2 relative rounded-lg overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-t from-blue-600/20 to-transparent"></div>
                    <img src="{{ request()->routeIs('login') ? asset('assets/reg.jpg') : asset('assets/reg.jpg') }}" 
                         alt="{{ request()->routeIs('login') ? 'Login Illustration' : 'Registration Illustration' }}" 
                         class="w-full h-full object-cover transition-transform duration-500 hover:scale-105">
                    <div class="absolute bottom-0 left-0 right-0 p-4 text-white bg-gradient-to-t from-black/70 to-transparent">
                        <h3 class="font-bold text-lg">
                            {{ request()->routeIs('login') ? 'Welcome Back' : 'Join Our Community' }}
                        </h3>
                        <p class="text-sm">
                            {{ request()->routeIs('login') ? 'Sign in to continue' : 'Register to access premium veterinary services' }}
                        </p>
                    </div>
                </div>
                
                <!-- Form Column -->
                <div class="w-full md:w-1/2">
                    <div class="text-center mb-6">
                        <h2 class="text-3xl font-bold text-gray-800 mb-1 bg-gradient-to-r from-blue-600 to-green-600 bg-clip-text text-transparent">
                            {{ request()->routeIs('login') ? 'Login Account' : 'Create your account' }}
                        </h2>
                        <p class="text-sm text-gray-600 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                            </svg>
                            {{ request()->routeIs('login') ? 'Access your dashboard' : 'Register as an Animal Owner' }}
                        </p>
                    </div>
                    
                    {{ $slot }}
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-8 text-center text-sm text-white/90 animate-fade-in" style="animation-delay: 0.3s;">
            <p>&copy; {{ date('Y') }} {{ config('app.name', 'Veterinary Office') }}. All rights reserved.</p>
        </div>
    </div>

    <!-- Loading indicator for Livewire -->
    <div wire:loading.flex class="fixed inset-0 bg-black/30 z-50 items-center justify-center">
        <div class="bg-white p-6 rounded-lg shadow-xl flex items-center space-x-3">
            <svg class="animate-spin h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="text-gray-700 font-medium">Processing...</span>
        </div>
    </div>
</body>
</html>