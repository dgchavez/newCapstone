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
    <link rel="preload" href="{{ asset('assets/login.jpg') }}" as="image">
    <link rel="preload" href="{{ asset('assets/reg.jpg') }}" as="image">
    <link rel="preload" href="{{ asset('assets/forgot-password.jpg') }}" as="image">

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
                <h1 class="mt-2 text-xl font-bold text-white bg-green-600/90 px-4 py-1 rounded-full shadow-md">
                    {{ config('app.name', 'Veterinary Office') }}
                </h1>
            </a>
        </div>

        <!-- Form Container with glass morphism effect -->
        <div class="w-full sm:max-w-4xl mt-6 px-6 py-8 bg-white/90 backdrop-blur-sm shadow-2xl overflow-hidden sm:rounded-lg transition-all duration-300 hover:shadow-xl animate-fade-in" style="animation-delay: 0.2s;">
            <div class="flex flex-col md:flex-row gap-8">
                <!-- Image Column (Hidden on mobile) -->
                <div class="hidden md:block md:w-1/2 relative rounded-lg overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-t from-blue-600/20 to-transparent"></div>
                    <img src="@if(request()->routeIs('password.request')){{ asset('assets/forgot.jpg') }}
                             @elseif(request()->routeIs('login')){{ asset('assets/login.jpg') }}
                             @else{{ asset('assets/reg.jpg') }}@endif" 
                         alt="@if(request()->routeIs('password.request'))Password Reset
                             @elseif(request()->routeIs('login'))Login
                             @elseRegistration @endif Illustration" 
                         class="w-full h-full object-cover transition-transform duration-500 hover:scale-105">
                    <div class="absolute bottom-0 left-0 right-0 p-4 text-white bg-gradient-to-t from-black/70 to-transparent">
                        <h3 class="font-bold text-lg">
                            @if(request()->routeIs('password.request'))
                                Reset Your Password
                            @elseif(request()->routeIs('login'))
                                Welcome Back
                            @else
                                Join Our Community
                            @endif
                        </h3>
                        <p class="text-sm">
                            @if(request()->routeIs('password.request'))
                                Get back to your account
                            @elseif(request()->routeIs('login'))
                                Sign in to continue
                            @else
                                Register to access premium veterinary services
                            @endif
                        </p>
                    </div>
                </div>
                
                <!-- Form Column -->
                <div class="w-full md:w-1/2">
                    <div class="text-center mb-6">
                        <h2 class="text-3xl font-bold text-gray-800 mb-1 bg-gradient-to-r from-blue-600 to-green-600 bg-clip-text text-transparent">
                            @if(request()->routeIs('password.request'))
                                Forgot Password
                            @elseif(request()->routeIs('login'))
                                Login Account
                            @else
                                Create your account
                            @endif
                        </h2>
                        <p class="text-sm text-gray-600 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                            </svg>
                            @if(request()->routeIs('password.request'))
                                Recover your account access
                            @elseif(request()->routeIs('login'))
                                Access your dashboard
                            @else
                                Register as an Animal Owner
                            @endif
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

<!-- Policy Modal -->
<div x-data="policyModal()" class="fixed inset-0 z-[60] pointer-events-none">
    <div x-show="showPolicyModal" 
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         x-cloak
         class="fixed inset-0 overflow-y-auto">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-black/50" @click="showPolicyModal = false"></div>
        
        <!-- Modal Content -->
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl mx-auto relative pointer-events-auto"
                 @click.stop>
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <h3 class="text-xl font-bold text-gray-900" x-text="currentPolicy?.title"></h3>
                        <button @click="showPolicyModal = false" class="text-gray-400 hover:text-gray-500">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    <div class="prose max-w-none max-h-[60vh] overflow-y-auto" x-html="currentPolicy?.content"></div>
                    <div class="mt-6 flex justify-end">
                        <button @click="showPolicyModal = false" 
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('policyModal', () => ({
        showPolicyModal: false,
        currentPolicy: null,
        
        init() {
            window.addEventListener('open-policy-modal', (event) => {
                this.currentPolicy = event.detail;
                this.showPolicyModal = true;
                document.body.classList.add('overflow-hidden');
            });
        },
        
        closePolicyModal() {
            this.showPolicyModal = false;
            document.body.classList.remove('overflow-hidden');
        }
    }));
});
</script>


</html>