<?php

use App\Models\User;
use App\Models\Owner;
use App\Models\Category;

use App\Models\Barangay;
use App\Models\Designation;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeEmail; 
use App\Models\Address;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

new #[Layout('layouts.guest')] class extends Component
{
    public string $complete_name = '';
    public int $role = 1; // Default role: owner
    public string $contact_no = '';
    public string $gender = '';
    public ?string $birth_date = null; // Nullable for optional field
    public int $status = 1; // Active status by default
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public ?int $designation_id = null; // Nullable, optional designation field
    public bool $is_email_field = true; // Flag to track if email field contains an email or username

    // Owner-specific fields
    public ?string $civil_status = null;
    public ?string $category = null;
    public ?int $permit = 1; // Active status by default

    // Address-related fields
    public int $barangay_id = 0;
    public string $street = '';

    public $barangays = []; // Barangays list
    public $designations = []; // Designations list
    public $categories = []; // Barangays list

    public array $selectedCategories = [];
    public ?int $specialCategory = null;

    public bool $isProcessing = false;
    public bool $showCredentials = false; // Flag to show credentials modal
    public ?string $generatedPassword = null; // Generated password to show in modal
    public ?string $userIdentifier = null; // Username/email to show in modal

    public int $currentStep = 1;
    public int $totalSteps = 3;

    /**
     * Mount function to initialize barangays and designations.
     */
    public function mount()
    {
        // Load all barangays
        $this->barangays = Barangay::all();
        $this->designations = Designation::all();
        $this->categories = Category::all();
    }

    /**
     * Toggle between email and username for the identifier field
     */
    public function toggleIdentifierType()
    {
        $this->is_email_field = !$this->is_email_field;
        $this->email = ''; // Clear the field when switching types
    }

    /**
     * Handle an incoming registration request.
     */
    public function isConnectedToInternet()
    {
        try {
            // Check by sending a simple HTTP request to a known service (Google in this case)
            $response = Http::get('https://www.google.com');
            return $response->successful(); // Returns true if successful
        } catch (\Exception $e) {
            // If an error occurs, it means there is no internet connection
            return false;
        }
    }

    public function register()
    {
        $this->isProcessing = true;

        try {
            DB::beginTransaction();

            // Validation rules will change based on identifier type and role
            $validationRules = [
                'complete_name' => ['required', 'string', 'max:255'],
                'role' => ['required', 'integer', 'in:1,2,3'], // Validate roles
                'contact_no' => ['nullable', 'string', 'max:15'],
                'gender' => ['required', 'string'],
                'birth_date' => ['nullable', 'date', 'before_or_equal:today'],
                'status' => ['required', 'integer'],
                'designation_id' => ['nullable', 'exists:designations,designation_id'],
                'civil_status' => ['nullable', 'required_if:role,1', 'string', 'max:255'],
                'selectedCategories' => ['nullable', 'array'],
                'selectedCategories.*' => ['exists:categories,id'],
                'specialCategory' => ['nullable', 'required_if:role,1', 'integer', 'exists:categories,id'],
                'barangay_id' => ['required', 'exists:barangays,id'],
                'street' => ['nullable', 'string', 'max:255'],
            ];

            // Email validation based on field type and role
            if ($this->is_email_field) {
                // Field is used as email
                $validationRules['email'] = ['required', 'string', 'email', 'max:255', 'unique:users,email'];
            } else {
                // Field is used as username (for owners only)
                $validationRules['email'] = ['required', 'string', 'min:5', 'max:25', 'unique:users,email', 'regex:/^[a-zA-Z0-9_.]+$/'];
            }

            // Different validation for non-owner roles
            if ($this->role != 1) {
                // Non-owners (staff) must use email
                $this->is_email_field = true;
                $validationRules['email'] = ['required', 'string', 'email', 'max:255', 'unique:users,email'];
            }

            $validated = $this->validate($validationRules);

            // Generate password
            $randomPassword = Str::random(8);
            $hashedPassword = Hash::make($randomPassword);

            // Create user
            $user = User::create([
                'complete_name' => $validated['complete_name'],
                'role' => $validated['role'],
                'contact_no' => $validated['contact_no'],
                'gender' => $validated['gender'],
                'birth_date' => $validated['birth_date'],
                'status' => $validated['status'],
                'email' => $validated['email'], // Will be either email or username
                'password' => $hashedPassword,
                'designation_id' => $validated['designation_id'],
            ]);

            // Create address
            $user->address()->create([
                'barangay_id' => $this->barangay_id,
                'street' => $this->street,
            ]);

            // If owner, create owner record and attach categories
            if ($validated['role'] === 1) {
                $owner = $user->owner()->create([
                    'civil_status' => $this->civil_status,
                    'permit' => $this->permit,
                ]);

                // Process categories
                $categoriesToAttach = [];
                
                // Add regular categories (excluding pregnant and lactating for males)
                if (!empty($this->selectedCategories)) {
                    $categoriesToAttach = $this->selectedCategories;
                    
                    // Remove categories 4 and 6 if gender is Male
                    if ($this->gender === 'Male') {
                        $categoriesToAttach = array_filter($categoriesToAttach, function($categoryId) {
                            return !in_array($categoryId, [4, 6]);
                        });
                    }
                }
                
                // Add the special category if selected
                if (!empty($this->specialCategory)) {
                    $categoriesToAttach[] = $this->specialCategory;
                }
                
                // Attach the categories if any exist
                if (!empty($categoriesToAttach)) {
                    $user->categories()->attach(array_values(array_unique($categoriesToAttach)));
                }
            }

            DB::commit();

            // Send email only if it's a valid email address
            if ($this->is_email_field) {
                dispatch(function () use ($user, $randomPassword) {
                    Mail::to($user->email)->send(new WelcomeEmail($user, $randomPassword));
                })->afterResponse();
            }

            // Store credentials for display
            $this->generatedPassword = $randomPassword;
            $this->userIdentifier = $this->email;
            $this->showCredentials = true;
            $this->isProcessing = false;

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Cannot add user due to an issue. Please try again.');
            $this->isProcessing = false;
        }
    }

    public function closeCredentialsModal()
    {
        $this->showCredentials = false;
        session()->flash('message', 'User added successfully!');
        return redirect()->route('admin-users');
    }

    public function downloadCredentialsPDF()
    {
        // Generate a unique filename
        $filename = 'user_credentials_' . time() . '.pdf';
        
        // Create the PDF
        $pdf = PDF::loadView('pdf.user-credentials', [
            'username' => $this->userIdentifier,
            'password' => $this->generatedPassword,
            'isEmail' => $this->is_email_field,
            'userName' => $this->complete_name
        ]);
        
        // Store in temp storage
        Storage::put('public/temp/' . $filename, $pdf->output());
        
        // Return the URL to download
        $this->dispatch('download-pdf', url('storage/temp/' . $filename));
    }

    public function printCredentials()
    {
        // This will trigger browser print - handled by JavaScript
        $this->dispatch('print-credentials');
    }

    public function previousStep()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    public function nextStep()
    {
        if ($this->currentStep < $this->totalSteps) {
            $this->currentStep++;
        }
    }
}
?>

<div class="max-w-4xl mx-auto p-8 bg-white rounded-lg shadow-lg relative">
    <!-- Loading Overlay -->
    @if($isProcessing)
    <div class="absolute inset-0 bg-white/80 backdrop-blur-sm flex items-center justify-center z-50 rounded-lg">
        <div class="text-center">
            <div class="inline-block animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-600 mb-4"></div>
            <p class="text-lg font-semibold text-gray-700">Creating user account...</p>
            <p class="text-sm text-gray-500">Please wait while we process your request</p>
        </div>
    </div>
    @endif

    <!-- Credentials Modal -->
    @if($showCredentials)
    <div class="fixed inset-0 bg-gray-900/70 backdrop-blur-sm flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl p-8 max-w-md w-full" id="credentials-printable">
            <div class="text-center mb-6">
                <h3 class="text-2xl font-bold text-gray-800">User Credentials</h3>
                <p class="text-sm text-gray-600">Please provide these credentials to the user</p>
            </div>
            
            <div class="border border-gray-200 rounded-lg p-6 bg-gray-50 mb-6">
                <div class="mb-4">
                    <p class="text-sm text-gray-500">{{ $is_email_field ? 'Email' : 'Username' }}</p>
                    <p class="text-lg font-semibold text-gray-800">{{ $userIdentifier }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Password</p>
                    <p class="text-lg font-semibold text-gray-800">{{ $generatedPassword }}</p>
                </div>
            </div>
            
            <div class="text-center text-sm text-gray-500 mb-6">
                <p>User must change their password after first login</p>
            </div>
            
            <div class="flex space-x-3 justify-center">
                <button wire:click="printCredentials" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Print
                </button>
                <button wire:click="downloadCredentialsPDF" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Download PDF
                </button>
                <button wire:click="closeCredentialsModal" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium py-2 px-4 rounded-lg">
                    Close
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- Error Message for No Internet -->
    @if (session()->has('error'))
        <div class="text-center mb-6">
            <p class="text-lg text-red-500">{{ session('error') }}</p>
        </div>
    @endif

    <!-- Title with Gradient Background -->
    <div class="text-center mb-6 bg-gradient-to-r from-blue-600 to-green-600 p-6 rounded-t-lg -mt-8 -mx-8 shadow-md">
        <h2 class="text-3xl font-bold text-white">User Registration Form</h2>
        <p class="text-lg text-blue-100">Add a new user to the system</p>
    </div>

    <!-- Logo -->
    <div class="text-center mb-8">
        <a href="/">
            <img class="h-24 w-auto mx-auto hover:scale-105 transition-transform duration-300" src="{{ asset('assets/1.jpg') }}" alt="Your Logo">
        </a>
    </div>

    <form wire:submit.prevent="register" class="space-y-8">
        <!-- Disable all form inputs while processing -->
        <div wire:loading.class="opacity-50 pointer-events-none">
            
            <!-- Form Sections with Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- User Basic Info Section -->
                <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow duration-300">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center border-b pb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-600" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                        </svg>
                        Basic Information
                    </h3>
                    
                    <!-- Complete Name -->
                    <div class="mb-4">
                        <x-input-label for="complete_name" :value="__('Full Name')" class="text-gray-700 font-medium"/>
                        <x-text-input wire:model="complete_name" id="complete_name" class="block mt-1 w-full border border-gray-300 rounded-lg p-3 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" type="text" required autofocus />
                        <x-input-error :messages="$errors->get('complete_name')" class="mt-2 text-sm text-red-500" />
                    </div>

                    <!-- Role -->
                    <div class="mb-4">
                        <x-input-label for="role" :value="__('Role')" class="text-gray-700 font-medium"/>
                        <select wire:model="role" id="role" class="block mt-1 w-full border border-gray-300 rounded-lg p-3 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required wire:change="$refresh">
                            <option value="#">Select Role</option>
                            <option value="1">Animal Owner</option>
                            <option value="2">Veterinarian</option>
                            <option value="3">Veterinary Receptionist</option>
                        </select>
                        <x-input-error :messages="$errors->get('role')" class="mt-2 text-sm text-red-500" />
                    </div>

                    @if ($role == 2)
                    <div class="mb-4">
                        <x-input-label for="designation_id" :value="__('Designation')" class="text-gray-700 font-medium" />
                        <select wire:model="designation_id" id="designation_id" class="block mt-1 w-full border border-gray-300 rounded-lg p-3 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                            <option value="">Select Designation</option>
                            @foreach($designations as $designation)
                                <option value="{{ $designation->designation_id }}">{{ $designation->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('designation_id')" class="mt-2 text-sm text-red-500" />
                    </div>
                    @endif
                </div>

                <!-- Contact Info Section -->
                <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow duration-300">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center border-b pb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-600" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
                        </svg>
                        Contact Information
                    </h3>
                    
                    <!-- Contact Number -->
                    <div class="mb-4">
                        <x-input-label for="contact_no" :value="__('Contact Number')" class="text-gray-700 font-medium"/>
                        <div class="flex">
                            <span class="inline-flex items-center px-3 text-gray-500 bg-gray-100 border border-r-0 border-gray-300 rounded-l-lg">
                                +63
                            </span>
                            <x-text-input wire:model="contact_no" id="contact_no" class="block mt-0 w-full border border-gray-300 rounded-r-lg p-3 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" type="text" placeholder="9XXXXXXXXX" />
                        </div>
                        <x-input-error :messages="$errors->get('contact_no')" class="mt-2 text-sm text-red-500" />
                    </div>

                    <!-- Gender -->
                    <div class="mb-4">
                        <x-input-label for="gender" :value="__('Gender')" class="text-gray-700 font-medium"/>
                        <div class="mt-1 flex space-x-4">
                            <label class="inline-flex items-center">
                                <input type="radio" wire:model="gender" value="Male" class="text-blue-600 focus:ring-blue-500 h-5 w-5">
                                <span class="ml-2 text-gray-700">Male</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" wire:model="gender" value="Female" class="text-blue-600 focus:ring-blue-500 h-5 w-5">
                                <span class="ml-2 text-gray-700">Female</span>
                            </label>
                        </div>
                        <x-input-error :messages="$errors->get('gender')" class="mt-2 text-sm text-red-500" />
                    </div>

                    <!-- Birth Date -->
                    <div>
                        <x-input-label for="birth_date" :value="__('Birth Date')" class="text-gray-700 font-medium"/>
                        <x-text-input wire:model="birth_date" id="birth_date" class="block mt-1 w-full border border-gray-300 rounded-lg p-3 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" type="date" />
                        <x-input-error :messages="$errors->get('birth_date')" class="mt-2 text-sm text-red-500" />
                    </div>
                </div>
            </div>
            
            <!-- Address Section -->
            <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow duration-300 mb-8">
                <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center border-b pb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-600" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                    </svg>
                    Address Information
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Barangay Selection -->
                    <div>
                        <x-input-label for="barangay_id" :value="__('Barangay')" class="text-gray-700 font-medium"/>
                        <select wire:model="barangay_id" id="barangay_id" class="block mt-1 w-full border border-gray-300 rounded-lg p-3 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                            <option value="">Select Barangay</option>
                            @foreach($barangays as $barangay)
                                <option value="{{ $barangay->id }}">{{ $barangay->barangay_name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('barangay_id')" class="mt-2 text-sm text-red-500" />
                    </div>

                    <!-- Street Name -->
                    <div>
                        <x-input-label for="street" :value="__('Street Name')" class="text-gray-700 font-medium"/>
                        <x-text-input wire:model="street" id="street" class="block mt-1 w-full border border-gray-300 rounded-lg p-3 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" type="text" required />
                        <x-input-error :messages="$errors->get('street')" class="mt-2 text-sm text-red-500" />
                    </div>
                </div>
            </div>

            <!-- Owner-Specific Fields -->
            @if ($role == 1)
            <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow duration-300 mb-8">
                <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center border-b pb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-600" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 2a1 1 0 00-1 1v1a1 1 0 002 0V3a1 1 0 00-1-1zM4 4h3a3 3 0 006 0h3a2 2 0 012 2v9a2 2 0 01-2 2H4a2 2 0 01-2-2V6a2 2 0 012-2zm2.5 7a1.5 1.5 0 100-3 1.5 1.5 0 000 3zm2.45 4a2.5 2.5 0 10-4.9 0h4.9zM12 9a1 1 0 100 2h3a1 1 0 100-2h-3zm-1 4a1 1 0 011-1h2a1 1 0 110 2h-2a1 1 0 01-1-1z" clip-rule="evenodd" />
                    </svg>
                    Owner Details
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Civil Status -->
                    <div>
                        <x-input-label for="civil_status" :value="__('Civil Status')" class="text-gray-700 font-medium"/>
                        <select wire:model="civil_status" id="civil_status" class="block mt-1 w-full border border-gray-300 rounded-lg p-3 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                            <option value="">Select Civil Status</option>
                            <option value="Married">Married</option>
                            <option value="Separated">Separated</option>
                            <option value="Single">Single</option>
                            <option value="Widow">Widow</option>
                        </select>
                        <x-input-error :messages="$errors->get('civil_status')" class="mt-2 text-sm text-red-500" />
                    </div>

               
                </div>

                <!-- Categories Selection -->
                <div class="mt-4">
                    <x-input-label for="category" :value="__('Owner Categories')" class="text-gray-700 font-medium" />
                    <div class="mt-2 p-4 border border-gray-300 rounded-lg bg-white shadow-sm">
                        <!-- Special Categories (0, 8, 9) - Radio Buttons -->
                        <div class="mb-3 pb-3 border-b border-gray-200">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Select one of these categories:</label>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                @foreach($categories as $category)
                                    @if(in_array($category->id, [0, 8, 9]))
                                        <div class="flex items-center">
                                            <input 
                                                type="radio" 
                                                id="category_radio_{{ $category->id }}" 
                                                wire:model="specialCategory" 
                                                value="{{ $category->id }}" 
                                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300"
                                            >
                                            <label for="category_radio_{{ $category->id }}" class="ml-2 text-gray-700">{{ $category->name }}</label>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        
                        <!-- Regular Categories (Checkboxes) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Select additional categories:</label>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                @foreach($categories as $category)
                                    @if(!in_array($category->id, [0, 8, 9]))
                                        <div class="flex items-center" 
                                             x-data="{}"
                                             x-show="!(['4', '6'].includes('{{ $category->id }}') && '{{ $gender }}' === 'Male')">
                                            <input 
                                                type="checkbox" 
                                                id="category_{{ $category->id }}" 
                                                wire:model="selectedCategories" 
                                                value="{{ $category->id }}" 
                                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                            >
                                            <label for="category_{{ $category->id }}" class="ml-2 text-gray-700">{{ $category->name }}</label>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <x-input-error :messages="$errors->get('selectedCategories')" class="mt-2 text-sm text-red-500" />
                </div>
            </div>
            @endif

            <!-- Authentication Section -->
            <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow duration-300">
                <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center border-b pb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-600" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                    </svg>
                    Authentication
                </h3>
                     <!-- Toggle between Email/Username -->
                     @if ($role == 1)
                           <div>
                        <x-input-label for="identifier_type" :value="__('Authentication Method')" class="text-gray-700 font-medium mb-2"/>
                        <div class="p-3 bg-gray-50 rounded-lg border border-gray-200">
                            <div class="flex items-center justify-between">
                                <span class="{{ $is_email_field ? 'font-semibold text-blue-600' : 'text-gray-500' }}">Email</span>
                                <button 
                                    type="button" 
                                    wire:click="toggleIdentifierType" 
                                    class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 {{ $is_email_field ? 'bg-blue-600' : 'bg-gray-200' }}"
                                >
                                    <span class="sr-only">Toggle email/username</span>
                                    <span 
                                        class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform {{ $is_email_field ? 'translate-x-6' : 'translate-x-1' }}" 
                                        aria-hidden="true"
                                    ></span>
                                </button>
                                <span class="{{ !$is_email_field ? 'font-semibold text-blue-600' : 'text-gray-500' }}">Username</span>
                            </div>
                        </div>
                    </div>
                     @endif
                  
                
                <!-- Email/Username Field -->
                <div>
                    <x-input-label for="email" :value="$is_email_field || $role != 1 ? __('Email') : __('Username')" class="text-gray-700 font-medium"/>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                            </svg>
                        </div>
                        <x-text-input 
                            wire:model="email" 
                            id="email" 
                            class="block mt-1 w-full pl-10 border border-gray-300 rounded-lg p-3 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                            type="{{ $is_email_field ? 'email' : 'text' }}" 
                            required 
                            placeholder="{{ $is_email_field ? 'email@example.com' : 'username' }}"
                        />
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-500" />
                    @if ($role == 1 && !$is_email_field)
                        <p class="mt-1 text-sm text-gray-500">Username must be at least 5 characters and can only contain letters, numbers, underscore and dot.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Update submit button to show loading state -->
        <div class="flex items-center justify-center mt-8">
            <x-primary-button 
                class="bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 focus:outline-none text-white font-semibold rounded-lg px-8 py-3 shadow-md transition duration-300 ease-in-out transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed"
                wire:loading.attr="disabled"
                wire:target="register"
            >
                <span wire:loading.remove wire:target="register" class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z" />
                    </svg>
                    {{ __('Add User') }}
                </span>
                <span wire:loading wire:target="register" class="inline-flex items-center">
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Processing...
                </span>
            </x-primary-button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('livewire:init', () => {
        // For print credentials
        Livewire.on('print-credentials', () => {
            const printContents = document.getElementById('credentials-printable').innerHTML;
            const originalContents = document.body.innerHTML;
            
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
            
            // Re-initialize Livewire after printing
            Livewire.rescan();
        });

        // For downloading PDF (if applicable)
        Livewire.on('download-pdf', (url) => {
            // Create a temporary anchor element
            const link = document.createElement('a');
            link.href = url;
            link.download = 'user_credentials.pdf';
            link.target = '_blank';
            
            // Trigger the download
            document.body.appendChild(link);
            link.click();
            
            // Clean up
            setTimeout(() => {
                document.body.removeChild(link);
                // After some time, delete the file from server
                fetch(url, { method: 'DELETE' });
            }, 100);
        });
    });
</script> 
