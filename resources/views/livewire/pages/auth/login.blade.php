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
                <!-- Email/Username Field -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email or Username</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                            </svg>
                        </div>
                        <input wire:model="form.email" id="email" 
                               class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 transition duration-200" 
                               type="text" 
                               name="email" 
                               required 
                               autofocus 
                               autocomplete="username" 
                               placeholder="Email or Username">
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
                            class="w-full flex justify-center items-center py-2 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                        </svg>
                        Sign in
                    </button>
                </div>
            </form>
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