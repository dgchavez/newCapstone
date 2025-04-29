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
    public string $complete_name = '';
    public int $role = 1; // Default role: owner
    public string $contact_no = '';
    public string $gender = '';
    public ?string $birth_date = null; // Nullable for optional field
    public int $status = 0; // Active status by default
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    // Owner-specific fields
    public ?string $civil_status = null;
    public array $selected_categories = []; // For storing multiple category IDs
    public $categories = []; // For storing the list of categories

    // Address-related fields
    public int $barangay_id = 0;
    public string $street = '';

    public $barangays = []; // Barangays list

    /**
     * Mount function to initialize barangays and categories.
     */
    public function mount()
    {
        // Load all barangays
        $this->barangays = Barangay::all();
        $this->categories = Category::all(); // Load all categories
    }

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        // Validate inputs
        $validated = $this->validate([
            'complete_name' => ['required', 'string', 'max:255'],
            'role' => ['required', 'integer', 'in:0,1,2,3'], // Validate roles
            'contact_no' => ['nullable', 'string', 'max:15'],
            'gender' => ['required', 'string'],
            'birth_date' => ['nullable', 'date'], // Allow null for birth_date
            'status' => ['required', 'integer'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
            // Validate owner-specific fields only if role is "Owner"
            'civil_status' => ['nullable', 'required_if:role,1', 'string', 'max:255'],
            'selected_categories' => ['required', 'array'],
            'selected_categories.*' => ['exists:categories,id'],
            // Validate address fields
            'barangay_id' => ['required', 'exists:barangays,id'],
            'street' => ['nullable', 'string', 'max:255'],
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

        // Attach categories directly to the user through the category_user pivot table
        $user->categories()->attach($this->selected_categories);

        // Create owner record
        if ($validated['role'] === 1) {
            Owner::create([
                'user_id' => $user->user_id,
                'civil_status' => $this->civil_status,
                'permit' => 1
            ]);
        }

        // Insert the address record
        Address::create([
            'user_id' => $user->user_id, // Foreign key to user
            'barangay_id' => $this->barangay_id, // Foreign key to barangay
            'street' => $this->street, // Street field from form input
        ]);

        // Fire the Registered event
        event(new Registered($user));

        // Check if the user is an owner and their account is pending approval
        if ($user->role === 1 && $user->status === 0) {
            // Log the user out immediately
            Auth::logout();

            // Invalidate the session to ensure the user is logged out
            session()->invalidate();
            session()->regenerateToken();

            // Redirect to login page with a message
           redirect()->route('login')->with('message', 'Wait for admin to enable you to login');
        }
    }
}


?><div class="relative min-h-screen flex items-center justify-center">
    
    <!-- Background Image (Fixed Full-Page) -->
    <div class="fixed inset-0 bg-cover bg-center z-0" 
         style="background-image: url('{{ asset('assets/bg.jpg') }}');">
    </div>
    
    <!-- Form Container with Gradient Overlay -->
    <div class="flex justify-center items-center h-full">
        <div class="w-full max-w-md space-y-8 bg-gradient-to-t from-white via-white/90 to-white/50 z-10 rounded-lg shadow-xl p-8">
            
            <!-- Logo and Header -->
            <div class="text-center mb-8">
                <a href="/" class="inline-block">
                    <img class="h-24 w-auto mx-auto" src="{{ asset('assets/logo2.png') }}" alt="Veterinary Office Logo">
                </a>
                <h2 class="mt-6 text-3xl font-bold text-gray-900">
                    Create your account
                </h2>
                <p class="mt-2 text-sm text-gray-600">
                    Register as an Animal Owner
                </p>
            </div>

            <form wire:submit.prevent="register" class="space-y-6">
                <!-- Complete Name -->
                <div>
                    <x-input-label for="complete_name" :value="__('Full Name')" />
                    <x-text-input wire:model="complete_name" id="complete_name" class="block mt-1 w-full" type="text" required autofocus />
                    <x-input-error :messages="$errors->get('complete_name')" class="mt-2" />
                </div>

                <!-- Role -->
                <div class="mt-4">
                    <x-input-label for="role" :value="__('Role')" />
                    <select wire:model="role" id="role" class="block mt-1 w-full" required wire:change="$refresh">
                        <option value="1">Animal Owner</option>
                        <!-- Add other roles here if needed -->
                    </select>
                    <x-input-error :messages="$errors->get('role')" class="mt-2" />
                </div>

                <!-- Barangay Selection -->
                <div class="mt-4">
                    <x-input-label for="barangay_id" :value="__('Barangay')" />
                    <select wire:model="barangay_id" id="barangay_id" class="block mt-1 w-full" required>
                        <option value="">Select Barangay</option>
                        @foreach($barangays as $barangay)
                            <option value="{{ $barangay->id }}">{{ $barangay->barangay_name }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('barangay_id')" class="mt-2" />
                </div>

                <!-- Street Name -->
                <div class="mt-4">
                    <x-input-label for="street" :value="__('Street Name')" />
                    <x-text-input wire:model="street" id="street" class="block mt-1 w-full" type="text" required />
                    <x-input-error :messages="$errors->get('street')" class="mt-2" />
                </div>

                <!-- Contact Number -->
                <div class="mt-4">
                    <x-input-label for="contact_no" :value="__('Contact Number')" />
                    <x-text-input wire:model="contact_no" id="contact_no" class="block mt-1 w-full" type="text" />
                    <x-input-error :messages="$errors->get('contact_no')" class="mt-2" />
                </div>

                <!-- Gender -->
                <div class="mt-4">
                    <x-input-label for="gender" :value="__('Gender')" />
                    <select wire:model="gender" id="gender" class="block mt-1 w-full" required>
                        <option value="">Select Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                    <x-input-error :messages="$errors->get('gender')" class="mt-2" />
                </div>

                <!-- Birth Date -->
                <div class="mt-4">
                    <x-input-label for="birth_date" :value="__('Birth Date')" />
                    <x-text-input wire:model="birth_date" id="birth_date" class="block mt-1 w-full" type="date" />
                    <x-input-error :messages="$errors->get('birth_date')" class="mt-2" />
                </div>

                <!-- Owner-Specific Fields -->
                @if ($role == 1)
                    <div class="mt-4">
                        <x-input-label for="civil_status" :value="__('Civil Status')" />
                        <select wire:model="civil_status" id="civil_status" class="block mt-1 w-full" required>
                            <option value="">Select Civil Status</option>
                            <option value="Married">Married</option>
                            <option value="Separated">Separated</option>
                            <option value="Single">Single</option>
                            <option value="Widow">Widow</option>
                        </select>
                        <x-input-error :messages="$errors->get('civil_status')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label :value="__('Categories (Select all that apply)')" class="mb-3" />
                        <div class="space-y-3 bg-white/50 p-4 rounded-lg border border-gray-200">
                            @foreach($categories as $category)
                                <label class="flex items-center">
                                    <input type="checkbox" 
                                        wire:model="selected_categories" 
                                        value="{{ $category->id }}" 
                                        class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 mr-3">
                                    <span class="text-gray-700">{{ $category->name }}</span>
                                </label>
                            @endforeach
                        </div>
                        <x-input-error :messages="$errors->get('selected_categories')" class="mt-2" />
                        <p class="mt-2 text-sm text-gray-500">You can select multiple categories if applicable.</p>
                    </div>
                @endif

                <!-- Email Address -->
                <div class="mt-4">
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input wire:model="email" id="email" class="block mt-1 w-full" type="email" required />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <x-input-label for="password" :value="__('Password')" />
                    <x-text-input wire:model="password" id="password" class="block mt-1 w-full" type="password" required />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Confirm Password -->
                <div class="mt-4">
                    <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                    <x-text-input wire:model="password_confirmation" id="password_confirmation" class="block mt-1 w-full" type="password" required />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <div class="flex items-center justify-end mt-4">
                    <x-primary-button>
                        {{ __('Register') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</div>
