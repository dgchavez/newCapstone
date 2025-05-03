<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();
        
        // Redirect based on role with dashboard URL
        $dashboardUrls = [
            0 => 'admin-dashboard',
            1 => 'owner-dashboard',
            2 => 'vet-dashboard',
            3 => 'receptionist-dashboard'
        ];
        
        $this->redirectIntended(
            default: route($dashboardUrls[auth()->user()->role] ?? 'dashboard', absolute: false), 
            navigate: true
        );
    }
}; ?>

<div class="w-full max-w-md mx-auto">
    <!-- Card with glass morphism effect -->
    <div class="bg-white/90 backdrop-blur-sm rounded-xl shadow-2xl overflow-hidden transition-all duration-300 hover:shadow-lg">
        <!-- Header with Logo -->
        <div class="bg-gradient-to-r from-blue-600 to-green-600 p-6 text-center">
            <a href="/" class="inline-block">
                <img class="h-16 w-auto mx-auto hover:scale-105 transition-transform duration-300" 
                     src="{{ asset('assets/logo2.png') }}" 
                     alt="Veterinary Office Logo">
            </a>
            <h2 class="mt-3 text-2xl font-bold text-white">Welcome Back</h2>
            <p class="text-blue-100 text-sm">Sign in to your account</p>
        </div>

        <!-- Form Content -->
        <div class="px-8 py-6">
            <!-- Session Status Message -->
            @if (session('message'))
                <div class="mb-4 p-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
                    {{ session('message') }}
                </div>
            @endif

            @if (session('status'))
                <div class="mb-4 p-3 bg-blue-50 border border-blue-200 text-blue-700 rounded-lg text-sm">
                    {{ session('status') }}
                </div>
            @endif

            <form wire:submit="login" class="space-y-5">
                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                            </svg>
                        </div>
                        <input wire:model="form.email" id="email" 
                               class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 transition duration-200" 
                               type="email" 
                               name="email" 
                               required 
                               autofocus 
                               autocomplete="username" 
                               placeholder="your@email.com">
                    </div>
                    @error('form.email') <span class="text-xs text-red-600 mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input wire:model="form.password" id="password" 
                               class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 transition duration-200" 
                               type="password" 
                               name="password" 
                               required 
                               autocomplete="current-password" 
                               placeholder="••••••••">
                    </div>
                    @error('form.password') <span class="text-xs text-red-600 mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between">
                    <!-- <div class="flex items-center">
                        <input wire:model="form.remember" id="remember" 
                               type="checkbox" 
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded transition duration-200">
                        <label for="remember" class="ml-2 block text-sm text-gray-700">Remember me</label>
                    </div> -->

                    @if (Route::has('password.request'))
                        <a class="text-sm text-blue-600 hover:text-blue-500 hover:underline transition duration-200" 
                           href="{{ route('password.request') }}" 
                           wire:navigate>
                            Forgot password?
                        </a>
                    @endif
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit" 
                            class="w-full flex justify-center items-center py-2 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                        </svg>
                        Sign in
                    </button>
                </div>
            </form>

            <!-- Social Login (Optional) -->
            <!-- <div class="mt-6">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-gray-500">Or continue with</span>
                    </div>
                </div>

                <div class="mt-4 grid grid-cols-2 gap-3">
                    <a href="#" class="w-full inline-flex justify-center items-center py-2 px-4 border border-gray-300 rounded-lg shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                            <path fill-rule="evenodd" d="M10 0C4.477 0 0 4.477 0 10c0 4.42 2.865 8.166 6.839 9.489.5.092.682-.217.682-.482 0-.237-.008-.866-.013-1.7-2.782.603-3.369-1.342-3.369-1.342-.454-1.155-1.11-1.462-1.11-1.462-.908-.62.069-.608.069-.608 1.003.07 1.531 1.03 1.531 1.03.892 1.529 2.341 1.087 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.11-4.555-4.943 0-1.091.39-1.984 1.029-2.683-.103-.253-.446-1.27.098-2.647 0 0 .84-.269 2.75 1.025A9.564 9.564 0 0110 4.844c.85.004 1.705.114 2.504.336 1.909-1.294 2.747-1.025 2.747-1.025.546 1.377.203 2.394.1 2.647.64.699 1.028 1.592 1.028 2.683 0 3.842-2.339 4.687-4.566 4.933.359.309.678.919.678 1.852 0 1.336-.012 2.415-.012 2.743 0 .267.18.578.688.48C17.14 18.163 20 14.418 20 10c0-5.523-4.477-10-10-10z" clip-rule="evenodd" />
                        </svg>
                    </a>
                    <a href="#" class="w-full inline-flex justify-center items-center py-2 px-4 border border-gray-300 rounded-lg shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                            <path d="M6.29 18.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0020 3.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.073 4.073 0 01.8 7.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 010 16.407a11.616 11.616 0 006.29 1.84" />
                        </svg>
                    </a>
                </div>
            </div> -->

            <!-- Registration Link -->
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    Don't have an account? 
                    <a href="{{ route('register') }}" class="font-medium text-blue-600 hover:text-blue-500 hover:underline transition duration-200">
                        Sign up
                    </a>
                </p>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="mt-8 text-center text-sm text-gray-500">
        <p>&copy; {{ date('Y') }} {{ config('app.name', 'Veterinary Office') }}. All rights reserved.</p>
    </div>
</div>