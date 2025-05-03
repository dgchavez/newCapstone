<?php

use App\Models\User;
use App\Models\Owner;
use App\Models\Barangay;
use App\Models\Address;
use App\Models\Category;

use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    // Basic user information
    public string $complete_name = '';
    public int $role = 1; // Default role: owner
    public string $contact_no = '';
    public string $gender = '';
    public ?string $birth_date = null;
    public int $status = 0; // Pending status by default
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    // Owner-specific fields
    public ?string $civil_status = null;
    public array $selected_categories = [];
    public $categories = [];

    // Address-related fields
    public int $barangay_id = 0;
    public string $street = '';
    public $barangays = [];

    // Form state tracking
    public int $currentStep = 1;
    public int $totalSteps = 3;
    
    /**
     * Mount function to initialize data
     */
    public function mount()
    {
        $this->barangays = Barangay::orderBy('barangay_name')->get();
        $this->categories = Category::orderBy('name')->get();
    }

    /**
     * Go to the next step in the multi-step form
     */
    public function nextStep()
    {
        if ($this->currentStep === 1) {
            $this->validate([
                'complete_name' => ['required', 'string', 'max:255', 'regex:/^[\pL\s\-]+$/u'], // Only letters and spaces
                'gender' => ['required', 'string', 'in:Male,Female'],
                'birth_date' => ['required', 'date', 'before:-5 years'], // Must be at least 5 years old
                'contact_no' => ['required', 'string', 'max:15', 'regex:/^9\d{9}$/'], // Philippine mobile number format
                'civil_status' => ['required', 'string', 'in:Single,Married,Separated,Widow'],
            ]);
        } elseif ($this->currentStep === 2) {
            $this->validate([
                'barangay_id' => ['required', 'exists:barangays,id'],
                'street' => ['required', 'string', 'max:255'],
                'selected_categories' => ['required', 'array', 'min:1'],
                'selected_categories.*' => ['exists:categories,id'],
            ]);
        }

        if ($this->currentStep < $this->totalSteps) {
            $this->currentStep++;
        }
    }

    /**
     * Go back to the previous step
     */
    public function previousStep()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        // Validate inputs with more specific rules
        $validated = $this->validate([
            'complete_name' => ['required', 'string', 'max:255', 'regex:/^[\pL\s\-]+$/u'],
            'role' => ['required', 'integer', 'in:1'], 
            'contact_no' => ['required', 'string', 'max:15', 'regex:/^9\d{9}$/'],
            'gender' => ['required', 'string', 'in:Male,Female'],
            'birth_date' => ['required', 'date', 'before:-5 years'],
            'status' => ['required', 'integer'],
            'email' => ['required', 'string', 'email:rfc,dns', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()->mixedCase()->numbers()->symbols()],
            'civil_status' => ['required', 'string', 'in:Single,Married,Separated,Widow'],
            'selected_categories' => ['required', 'array', 'min:1'],
            'selected_categories.*' => ['exists:categories,id'],
            'barangay_id' => ['required', 'exists:barangays,id'],
            'street' => ['required', 'string', 'max:255'],
        ]);

        // Create the user first
        $user = User::create([
            'complete_name' => $this->complete_name,
            'contact_no' => $this->contact_no,
            'gender' => $this->gender,
            'birth_date' => $this->birth_date,
            'status' => $this->status,
            'email' => $this->email,
            'role' => $this->role,
            'password' => Hash::make($this->password)
        ]);

        // Attach categories directly to the user
        $user->categories()->attach($this->selected_categories);

        // Create owner record
        Owner::create([
            'user_id' => $user->user_id,
            'civil_status' => $this->civil_status,
            'permit' => 1
        ]);

        // Insert the address record
        Address::create([
            'user_id' => $user->user_id,
            'barangay_id' => $this->barangay_id,
            'street' => $this->street,
        ]);

        // Fire the Registered event
        event(new Registered($user));

        // Check if the user is an owner and their account is pending approval
        if ($user->role === 1 && $user->status === 0) {
            // Log the user out immediately
            Auth::logout();
            session()->invalidate();
            session()->regenerateToken();

            // Redirect to login page with a message
            redirect()->route('login')->with('message', 'Your registration is pending approval by admin. You will receive an email once your account is approved.');
        } else {
            // If active, login the user and redirect
            Auth::login($user);
            redirect()->intended(route('dashboard', absolute: false));
        }
    }
}
?>

<div class="w-full">
    <!-- Progress Bar -->
    <div class="w-full bg-gray-200 h-1.5 rounded-full mb-6">
        <div class="bg-gradient-to-r from-blue-500 to-green-500 h-1.5 rounded-full transition-all duration-300 ease-out" 
             style="width: {{ ($currentStep / $totalSteps) * 100 }}%"></div>
    </div>

    <!-- Step Indicators -->
    <div class="flex justify-between mb-8">
        @foreach(range(1, $totalSteps) as $step)
            <div class="flex flex-col items-center w-1/3">
                <div class="w-10 h-10 rounded-full flex items-center justify-center mb-2
                    @if($currentStep > $step) bg-green-100 text-green-600 border-2 border-green-500
                    @elseif($currentStep == $step) bg-blue-100 text-blue-600 border-2 border-blue-500
                    @else bg-gray-100 text-gray-400 border-2 border-gray-300 @endif
                    font-semibold transition-all duration-300">
                    @if($currentStep > $step)
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    @else
                        {{ $step }}
                    @endif
                </div>
                <span class="text-xs font-medium text-center
                    @if($currentStep >= $step) text-gray-700 @else text-gray-400 @endif">
                    @if($step === 1) Personal Info
                    @elseif($step === 2) Address & Pets
                    @else Account
                    @endif
                </span>
            </div>
            @if($step < $totalSteps)
                <div class="flex-1 flex items-center justify-center -mt-5">
                    <div class="h-0.5 w-full bg-gray-200 relative">
                        <div class="absolute left-0 top-0 h-full bg-gradient-to-r from-blue-500 to-green-500 transition-all duration-500 ease-out"
                             style="width: {{ $currentStep > $step ? '100%' : '0%' }}"></div>
                    </div>
                </div>
            @endif
        @endforeach
    </div>

    <!-- Form Container -->
    <form wire:submit.prevent="register" class="space-y-6">
        <!-- Step 1: Personal Information -->
        @if ($currentStep === 1)
        <div class="space-y-5">
            <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                </svg>
                Personal Information
            </h3>
            
            <!-- Full Name -->
            <div>
                <label for="complete_name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                <div class="relative">
                    <input type="text" wire:model="complete_name" id="complete_name" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200" 
                           placeholder="Juan Dela Cruz" required>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
                @error('complete_name') <span class="text-xs text-red-600 mt-1">{{ $message }}</span> @enderror
            </div>

            <!-- Gender and Birth Date in one row -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Gender -->
                <div>
                    <label for="gender" class="block text-sm font-medium text-gray-700 mb-1">Sex</label>
                    <div class="relative">
                        <select wire:model="gender" id="gender" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 appearance-none transition duration-200">
                            <option value="">Select </option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <!-- <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg> -->
                        </div>
                    </div>
                    @error('gender') <span class="text-xs text-red-600 mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Birth Date -->
                <div>
                    <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-1">Birth Date</label>
                    <div class="relative">
                        <input type="date" wire:model="birth_date" id="birth_date" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200" 
                               required>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <!-- <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                            </svg> -->
                        </div>
                    </div>
                    @error('birth_date') <span class="text-xs text-red-600 mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Contact Number -->
            <div>
                <label for="contact_no" class="block text-sm font-medium text-gray-700 mb-1">Contact Number</label>
                <div class="flex">
                    <span class="inline-flex items-center px-4 py-2 rounded-l-lg border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm font-medium">
                        +63
                    </span>
                    <input type="tel" wire:model="contact_no" id="contact_no" 
                           class="flex-1 px-4 py-2 border border-gray-300 rounded-r-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200" 
                           placeholder="912 345 6789" pattern="[0-9]{10}" required>
                </div>
                @error('contact_no') <span class="text-xs text-red-600 mt-1">{{ $message }}</span> @enderror
                <p class="mt-1 text-xs text-gray-500">Philippine mobile number format (e.g. 9123456789)</p>
            </div>

            <!-- Civil Status -->
            <div>
                <label for="civil_status" class="block text-sm font-medium text-gray-700 mb-1">Civil Status</label>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                    @foreach(['Single', 'Married', 'Separated', 'Widow'] as $status)
                        <label class="flex items-center">
                            <input type="radio" wire:model="civil_status" value="{{ $status }}" 
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                            <span class="ml-2 text-sm text-gray-700">{{ $status }}</span>
                        </label>
                    @endforeach
                </div>
                @error('civil_status') <span class="text-xs text-red-600 mt-1">{{ $message }}</span> @enderror
            </div>
        </div>
        @endif

        <!-- Step 2: Address and Categories -->
        @if ($currentStep === 2)
        <div class="space-y-5">
            <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                </svg>
                Address & Pet Information
            </h3>
            
            <!-- Barangay Selection -->
            <div>
                <label for="barangay_id" class="block text-sm font-medium text-gray-700 mb-1">Barangay</label>
                <div class="relative">
                    <select wire:model="barangay_id" id="barangay_id" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 appearance-none transition duration-200">
                        <option value="">Select Barangay</option>
                        @foreach($barangays as $barangay)
                            <option value="{{ $barangay->id }}">{{ $barangay->barangay_name }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
                @error('barangay_id') <span class="text-xs text-red-600 mt-1">{{ $message }}</span> @enderror
            </div>

            <!-- Street Name -->
            <div>
                <label for="street" class="block text-sm font-medium text-gray-700 mb-1">Street Address</label>
                <div class="relative">
                    <input type="text" wire:model="street" id="street" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200" 
                           placeholder="123 Main Street" required>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                        </svg>
                    </div>
                </div>
                @error('street') <span class="text-xs text-red-600 mt-1">{{ $message }}</span> @enderror
            </div>

            <!-- Categories Selection -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Pet Categories (Select all that apply)</label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach($categories as $category)
                        <label class="flex items-start p-3 border border-gray-200 rounded-lg hover:border-blue-400 hover:bg-blue-50 transition-colors duration-200 cursor-pointer">
                            <input type="checkbox" 
                                wire:model="selected_categories" 
                                value="{{ $category->id }}" 
                                class="mt-1 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <div class="ml-3">
                                <span class="block text-sm font-medium text-gray-700">{{ $category->name }}</span>
                                <span class="block text-xs text-gray-500 mt-1">Common pets in this category</span>
                            </div>
                        </label>
                    @endforeach
                </div>
                @error('selected_categories') <span class="text-xs text-red-600 mt-1">{{ $message }}</span> @enderror
            </div>
        </div>
        @endif

        <!-- Step 3: Account Information -->
        @if ($currentStep === 3)
        <div class="space-y-5">
            <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                </svg>
                Account Details
            </h3>
            
            <!-- Email Address -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                <div class="relative">
                    <input type="email" wire:model="email" id="email" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200" 
                           placeholder="your@email.com" required>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                        </svg>
                    </div>
                </div>
                @error('email') <span class="text-xs text-red-600 mt-1">{{ $message }}</span> @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <div class="relative">
                    <input type="password" wire:model="password" id="password" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200" 
                           required>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
                @error('password') <span class="text-xs text-red-600 mt-1">{{ $message }}</span> @enderror
                <div class="mt-1">
                    <div class="grid grid-cols-4 gap-1">
                        <div class="h-1 rounded @if(strlen($password) > 0) @if(strlen($password) < 4) bg-red-500 @elseif(strlen($password) < 8) bg-yellow-500 @else bg-green-500 @endif @else bg-gray-200 @endif"></div>
                        <div class="h-1 rounded @if(strlen($password) > 3) @if(strlen($password) < 8) bg-yellow-500 @else bg-green-500 @endif @else bg-gray-200 @endif"></div>
                        <div class="h-1 rounded @if(strlen($password) > 7) bg-green-500 @else bg-gray-200 @endif"></div>
                        <div class="h-1 rounded @if(strlen($password) > 11) bg-green-500 @else bg-gray-200 @endif"></div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">
                        @if(strlen($password) > 0)
                            @if(strlen($password) < 4) Weak
                            @elseif(strlen($password) < 8) Moderate
                            @else Strong
                            @endif
                        @else
                            Password strength
                        @endif
                    </p>
                </div>
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                <div class="relative">
                    <input type="password" wire:model="password_confirmation" id="password_confirmation" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200" 
                           required>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
                @error('password_confirmation') <span class="text-xs text-red-600 mt-1">{{ $message }}</span> @enderror
                <div class="mt-1">
                    @if($password && $password_confirmation)
                        @if($password === $password_confirmation)
                            <p class="text-xs text-green-600 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                                Passwords match
                            </p>
                        @else
                            <p class="text-xs text-red-600 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                                Passwords don't match
                            </p>
                        @endif
                    @endif
                </div>
            </div>

            <!-- Agreement Checkbox -->
            <div class="mt-4">
                <label class="flex items-start">
                    <input type="checkbox" class="mt-1 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" required>
                    <span class="ml-2 text-sm text-gray-600">
                        I agree to the <a href="#" class="text-blue-600 hover:underline font-medium">Terms and Conditions</a> and <a href="#" class="text-blue-600 hover:underline font-medium">Privacy Policy</a>
                    </span>
                </label>
            </div>
        </div>
        @endif

        <!-- Navigation Buttons -->
        <div class="flex justify-between items-center pt-6 border-t border-gray-200">
            @if ($currentStep > 1)
                <button type="button" wire:click="previousStep" 
                        class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                    Previous
                </button>
            @else
                <div></div>
            @endif

            @if ($currentStep < $totalSteps)
                <button type="button" wire:click="nextStep" 
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200">
                    Next
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </button>
            @else
                <button type="submit" 
                        class="w-full inline-flex justify-center items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg shadow-sm text-white bg-gradient-to-r from-green-600 to-green-500 hover:from-green-700 hover:to-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    Complete Registration
                </button>
            @endif
        </div>
    </form>

    <!-- Login Link -->
    <div class="text-center mt-6">
        <p class="text-sm text-gray-600">
            Already have an account? 
            <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:text-blue-500 hover:underline transition duration-200">
                Sign in here
            </a>
        </p>
    </div>
</div>