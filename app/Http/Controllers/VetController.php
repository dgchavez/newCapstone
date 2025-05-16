<?php

namespace App\Http\Controllers;

use App\Models\VeterinaryTechnician; // Import the Species model
use App\Models\Breed; // Import the Species model
use App\Models\Vaccine; // Import the Species model
use App\Models\Technician; // Import the Species model
use App\Models\Designation;
use App\Models\Category;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth; // Add this at the top of your controller
use Illuminate\Support\Facades\Hash; // Add this at the top of your controller

use App\Models\Transaction; // Import the Species model
use Illuminate\Support\Facades\Log;
use App\Models\TransactionSubtype; // Import the model
use App\Models\Animal; // Import the Species model
use App\Models\TransactionType; // Import the model
use App\Models\Species; // Import the Species model
use App\Models\Owner;
use App\Models\Address;
use App\Models\Barangay;
use App\Models\City;
use App\Models\User;
use DB; 

use Illuminate\Http\Request;

class VetController extends Controller
{


    public function loadVetDashboard()
    {
        // Fetch the logged-in veterinarian
        $user = Auth::user();
        
        // Fetch dynamic data
        $assignedTransactions = Transaction::where('vet_id', $user->user_id)->count(); // Count transactions assigned to the vet
        $successfulTransactions = Transaction::where('vet_id', $user->user_id)
            ->where('status', 1) // Status of 1 for successful transactions
            ->count(); // Count successful transactions
        
        $recentTransactions = Transaction::where('vet_id', $user->user_id)
            ->orderBy('created_at', 'desc') // Sort by most recent
            ->limit(5) // Get only the most recent 5
            ->get();
    
        // Prepare report data (example: number of transactions in the last month)
        $lastMonthTransactions = Transaction::where('vet_id', $user->user_id)
            ->whereBetween('created_at', [now()->subMonth(), now()])
            ->count();
    
        // Fetch other relevant data (e.g., pending transactions, or specific date range)
        $pendingTransactions = Transaction::where('vet_id', $user->user_id)
            ->where('status', 0) // Status of 0 for pending transactions
            ->count();
    
        return view('vet.dashboard', [
            'assignedTransactions' => $assignedTransactions,
            'successfulTransactions' => $successfulTransactions,
            'recentTransactions' => $recentTransactions,
            'lastMonthTransactions' => $lastMonthTransactions,
            'pendingTransactions' => $pendingTransactions,
        ]);
    }
    
       public function loadOwnersList(Request $request)
   {
       // Fetch both categories and barangays
       $categories = Category::all();
       $barangays = Barangay::select('id', 'barangay_name')->orderBy('barangay_name')->get();
       
       $search = $request->input('search', '');
       $gender = $request->input('gender', '');
       $selectedCategory = $request->input('category');
       $civil_status = $request->input('civil_status', '');
       $barangay_id = $request->input('barangay', '');
       $fromDate = $request->input('fromDate', '');
       $toDate = $request->input('toDate', '');

       $owners = Owner::with([
               'user',
               'user.categories',
               'animals.species',
               'animals.breed',
               'transactions',
               'address.barangay'
           ])
           ->join('users', 'owners.user_id', '=', 'users.user_id')
           ->leftJoin('addresses', 'users.user_id', '=', 'addresses.user_id')
           ->leftJoin('barangays', 'addresses.barangay_id', '=', 'barangays.id')
           ->leftJoin('transactions', 'owners.owner_id', '=', 'transactions.owner_id')
           ->leftJoin('transaction_types', 'transactions.transaction_type_id', '=', 'transaction_types.id')
           ->where('users.role', 1)
           ->where(function ($query) use ($search) {
               $query->where('users.complete_name', 'like', '%' . $search . '%')
                     ->orWhere('users.contact_no', 'like', '%' . $search . '%')
                     ->orWhere('addresses.street', 'like', '%' . $search . '%')
                     ->orWhere('barangays.barangay_name', 'like', '%' . $search . '%');
           })
           ->when($selectedCategory !== '' && $selectedCategory !== null, function ($query) use ($selectedCategory) {
               return $query->whereHas('user.categories', function ($q) use ($selectedCategory) {
                   $q->where('categories.id', $selectedCategory);
               });
           })
           ->when($gender, function ($query) use ($gender) {
               return $query->where('users.gender', $gender);
           })
           ->when($civil_status, function ($query) use ($civil_status) {
               return $query->where('owners.civil_status', $civil_status);
           })
           ->when($barangay_id, function ($query) use ($barangay_id) {
               return $query->where('barangays.id', $barangay_id);
           })
           ->when($fromDate, function ($query) use ($fromDate) {
               return $query->whereDate('owners.created_at', '>=', $fromDate);
           })
           ->when($toDate, function ($query) use ($toDate) {
               return $query->whereDate('owners.created_at', '<=', $toDate);
           })
           ->select(
               'owners.owner_id', 
               'owners.civil_status',
               'owners.category',
               'owners.created_at',
               'users.complete_name',
               'users.profile_image',
               'users.contact_no',
               'users.gender',
               'users.birth_date',
               'users.user_id as user_id',
               'users.status',
               'users.email',
               'addresses.street',
               'barangays.barangay_name',
               DB::raw('GROUP_CONCAT(transactions.transaction_type_id) as transaction_type_ids'),
               DB::raw('GROUP_CONCAT(transaction_types.type_name) as transaction_types'),
               DB::raw('MAX(transactions.created_at) as transaction_created_at')
           )
           ->groupBy(
               'owners.owner_id',
               'owners.civil_status',
               'owners.category',
               'owners.created_at',
               'users.complete_name',
               'users.profile_image',
               'users.contact_no',
               'users.gender',
               'users.birth_date',
               'users.status',
               'users.user_id',
               'users.email',
               'addresses.street',
               'barangays.barangay_name'
           )
           ->orderBy('users.created_at', 'desc')
           ->paginate(15);

       // Return view with all necessary variables
       return view('vet.animal-owners', compact(
           'owners',
           'search',
           'gender',
           'selectedCategory',
           'civil_status',
           'barangays',
           'barangay_id',
           'categories',
           'fromDate',
           'toDate'
       ));
       
   }
   
    
    public function showVeterinarianProfile(Request $request, $user_id)
    {
        // Fetch query parameters for filters
        $search = $request->input('search');
        $status = $request->input('status');
        $transactionType = $request->input('transaction_type');
        $transactionSubtype = $request->input('transaction_subtype');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
    
        // Fetch veterinarian profile
        $veterinarian = User::with([
            'vetTransactions.animal.species',
            'vetTransactions.animal.breed',
            'vetTransactions.transactionType',
            'animals.species',
            'animals.breed',
            'address.barangay',
            'designation',
        ])
        ->where('users.user_id', $user_id)
        ->where('users.role', 2)
        ->first();
    
        // Build the transactions query
        $transactions = Transaction::with([
            'animal.owner.user',
            'animal.species',
            'animal.breed',
            'transactionType',
            'transactionSubtype',
            'technician',
        ])->where('vet_id', $user_id);
    
        // Apply filters
        if ($search) {
            $transactions->whereHas('animal.owner.user', function ($query) use ($search) {
                $query->where('complete_name', 'like', "%$search%");
            })->orWhereHas('animal', function ($query) use ($search) {
                $query->where('name', 'like', "%$search%");
            });
        }
    
        if ($status !== null) {
            $transactions->where('status', $status);
        }
    
        if ($transactionType) {
            $transactions->where('transaction_type_id', $transactionType);
        }
    
        if ($transactionSubtype) {
            $transactions->where('transaction_subtype_id', $transactionSubtype);
        }
    
        if ($startDate) {
            $transactions->whereDate('created_at', '>=', $startDate);
        }
    
        // Apply the end date filter if it exists
        if ($endDate) {
            $transactions->whereDate('created_at', '<=', $endDate);
        }
    
        // Transaction Count
        $transactionCount = $transactions->count();
        // Paginate the transactions
        $transactions = $transactions->orderBy('created_at', 'desc')->paginate(10);
    
        // Fetch other needed data
        $technicians = VeterinaryTechnician::all();
        $transactionTypes = TransactionType::all();
        $transactionSubtypes = TransactionSubtype::all();
        $vet = User::findOrFail($user_id);
        $animals = Animal::whereIn('animal_id', $transactions->pluck('animal_id'))->get();
    
        // Return the view with the filtered transactions
        return view('vet.veterinarian-profile', compact(
            'veterinarian', 'transactions', 'technicians', 'transactionTypes', 
            'transactionSubtypes', 'transactionCount', 'animals', 'vet'
        ));
    }
    
   public function updateTechnician(Request $request, $transaction_id)
   {
       // Find the transaction by its custom primary key 'transaction_id'
       $transaction = Transaction::where('transaction_id', $transaction_id)->first();
   
       // If the transaction exists, update the technician_id
       if ($transaction) {
           $transaction->technician_id = $request->technician_id; // Update the technician_id with the selected value
           // Update the transaction by explicitly targeting 'transaction_id' as the key
           Transaction::where('transaction_id', $transaction_id)->update(['technician_id' => $request->technician_id]); // Using the update method to target the 'transaction_id' column
       }
   
       // Redirect back with a success message
       return back()->with('success', 'Technician updated successfully.');
   }

   public function updateDetails(Request $request, $transaction_id)
   {
       // Validate the input
       $request->validate([
           'details' => 'required|string|max:1000', // Adjust max length as needed
       ]);
   
       // Find the transaction
       $transaction = Transaction::where('transaction_id', $transaction_id)->first();
   
       // Update the details field
       if ($transaction) {
        $transaction->details = $request->details; // Update the technician_id with the selected value
        // Update the transaction by explicitly targeting 'transaction_id' as the key
        Transaction::where('transaction_id', $transaction_id)->update(['details' => $request->details]); // Using the update method to target the 'transaction_id' column
    }
   
       // Redirect with success message
       return redirect()->back()->with('success', 'Transaction details updated successfully.');
   }
   
   public function vet_edit($user_id)
{
    // Fetch veterinarian (user with role = 2)
    $veterinarian = User::with([
            'animals.species',  // Load species for animals
            'animals.breed',    // Load breed for animals
            'transactions',     // Load transactions
            'address.barangay', // Load address with barangay
            'designation'       // Load designation for veterinarian
        ])
        ->where('role', 2)
        ->where('user_id', $user_id)
        ->firstOrFail();

    // Fetch all designations for the dropdown
    $designations = Designation::all();

    // Fetch all barangays for the dropdown
    $barangays = Barangay::orderBy('barangay_name')->get();

    return view('vet.vet-edit', compact('veterinarian', 'designations', 'barangays'));
}

public function vet_update(Request $request, $user_id)
{
    // Validate the input
    $request->validate([
        'complete_name' => 'required|string|max:255',
        'contact_no' => 'required|string|max:20',
        'gender' => 'required|in:Male,Female',
        'birth_date' => ['nullable', 'date', 'before_or_equal:today'], // Ensure birthdate is not in the future
        'designation_id' => 'nullable|exists:designations,designation_id',
        'barangay_id' => 'required|exists:barangays,id',
        'street' => 'nullable|string|max:255',
        'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    // Find the veterinarian to be updated
    $veterinarian = User::findOrFail($user_id);

    // Update the veterinarian's personal info
    $veterinarian->complete_name = $request->complete_name;
    $veterinarian->contact_no = $request->contact_no;
    $veterinarian->gender = $request->gender;
    $veterinarian->birth_date = $request->birth_date;
    $veterinarian->designation_id = $request->designation_id;

    // Update the address information
    $veterinarian->address->barangay_id = $request->barangay_id;
    $veterinarian->address->street = $request->street;
    $veterinarian->address->save();

if ($request->hasFile('profile_image')) {
    // Delete old image if it exists
    if ($veterinarian->profile_image) {
        $oldPath = public_path('storage/' . $veterinarian->profile_image);
        if (file_exists($oldPath)) {
            unlink($oldPath);
        }
    }

    // Store the new image directly in public/storage/profile_images
    $filename = time() . '_' . $request->file('profile_image')->getClientOriginalName();
    $request->file('profile_image')->move(public_path('storage/profile_images'), $filename);
    $veterinarian->profile_image = 'profile_images/' . $filename;
}

    // Save the updated veterinarian details
    $veterinarian->save();

    return redirect()-> route('vet.veterinarian.profile', $veterinarian->user_id)->with('success', 'Veterinarian updated successfully');
}
   

public function settings()
{
    return view('vet.settings');
}

public function deleteImage($id)
{
    $user = User::findOrFail($id);

    if ($user->profile_image) {
        // Delete the file from storage
        Storage::delete($user->profile_image);

        // Remove the path from the database
        $user->profile_image = null;
        $user->save();
    }

    return back()->with('status', 'Profile image deleted successfully!');
}


    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        // Check if current password matches
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }

        // Update the password
        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return back()->with('status', 'Password updated successfully!');
    }

   
public function updateStatus(Request $request, $transaction_id)
{
    // Find the transaction by its custom primary key 'transaction_id'
    $transaction = Transaction::where('transaction_id', $transaction_id)->first();
    
    // If the transaction exists, proceed
    if ($transaction) {
        // Update the status of the transaction
        Transaction::where('transaction_id', $transaction_id)->update(['status' => $request->status]);

        // Check if the transaction_subtype_id is 8 (vaccination) and the status is 1 (completed)
        if ($transaction->transaction_subtype_id == 8 && $request->status == 1) {
            // Find the related animal using the animal_id from the transaction
            $animal = Animal::where('animal_id', $transaction->animal_id)->first();

            // Update the is_vaccinated field in the animals table
            if ($animal) {
                Animal::where('animal_id', $transaction->animal_id)->update(['is_vaccinated' => 1]);
            }
        }
    }

    // Redirect back with a success message
    return back()->with('message', 'Transaction status updated successfully!');
}

public function showProfile(Request $request, $owner_id)
{
    // Ensure the $owner_id is valid
    if (!$owner_id) {
        return abort(404, 'Owner ID is missing.');
    }

    // Retrieve owner details with relationships, including user info, address, barangay, transactions, and animals
    $owner = Owner::with([
        'user', // Eager load related User model (for complete_name, profile_image, etc.)
        'animals' => function ($query) {
            // Eager load species and breed for animals, ordered by created_at descending
            $query->with(['species', 'breed'])
                  ->orderBy('created_at', 'desc'); // Order animals by newest first
        },
        'transactions' => function ($query) {
        // Eager load transactions and order them by created_at descending (newest first)
        $query->orderBy('created_at', 'desc'); // Order transactions by newest first
    },
        'address' => function ($query) {
            // Eager load address and related barangay
            $query->with('barangay');
        }
    ])
    ->join('users', 'owners.user_id', '=', 'users.user_id') // Join the 'users' table using user_id
    ->leftJoin('transactions', 'owners.owner_id', '=', 'transactions.owner_id') // Join transactions by owner_id
    ->leftJoin('transaction_types', 'transactions.transaction_type_id', '=', 'transaction_types.id') // Join for transaction types
    ->leftJoin('transaction_subtypes', 'transactions.transaction_subtype_id', '=', 'transaction_subtypes.id') // Join for transaction subtypes
    ->leftJoin('addresses', 'users.user_id', '=', 'addresses.user_id') // Left join for addresses
    ->leftJoin('barangays', 'addresses.barangay_id', '=', 'barangays.id') // Left join for barangays
    ->select(
        'owners.owner_id',  // Use owner_id as the primary key
        'owners.civil_status',
        'owners.category',
        'owners.created_at',
        'users.complete_name',  // User information to display in the owner's list
        'users.profile_image',
        'users.contact_no',
        'users.gender',
        'users.email',  // User information to display in the owner's list
        'users.birth_date',
        'users.user_id as user_id',  // This can be used for routing to the profile
        'addresses.street',
        'barangays.barangay_name',
        'transactions.transaction_type_id',
        'transactions.technician_id',
        'transaction_types.type_name',
        'transactions.transaction_subtype_id',
        'transaction_subtypes.subtype_name as transaction_subtype_name',
        'transactions.created_at as transaction_created_at'
    )
    ->where('owners.owner_id', $owner_id) // Get the specific owner by owner_id
    ->first(); // We only need one owner, so use first()

    // Check if the owner is found, if not, abort with a 404 error
    if (!$owner) {
        return abort(404, 'Owner profile not found.');
    }

    // Search and filter animals
    $query = Animal::with(['species', 'breed'])
        ->where('owner_id', $owner_id)
        ->orderBy('created_at', 'desc'); // Order animals by newest first

    // Apply search (by name)
    if ($request->filled('search')) {
        $query->where('name', 'like', '%' . $request->search . '%');
    }

    // Apply species filter
    if ($request->filled('species_id')) {
        $query->where('species_id', $request->species_id);
    }

    // Apply breed filter
    if ($request->filled('breed_id')) {
        $query->where('breed_id', $request->breed_id);
    }

    // Paginate results
    $animals = $query->paginate(10); // Adjust the pagination size if needed

    // Fetch species and breeds for filters
    $species = Species::all();
    $breeds = Breed::all();

    // Check if there are any animals to display
    if ($animals->isEmpty() && !$request->filled('search') && !$request->filled('species_id') && !$request->filled('breed_id')) {
        $message = 'No animals registered for this owner yet.';
    } else if ($animals->isEmpty()) {
        $message = 'No animals match your search or filter criteria.';
    } else {
        $message = null;
    }

    // Return the owner profile view with all the necessary data
    return view('vet.owner-profile', compact('owner', 'animals', 'species', 'breeds', 'message'));
}


public function showAnimalProfile(Request $request, $animal_id)
{
    // Fetch the animal with its related data using eager loading
    $animal = Animal::with([
        'owner.user',        // Load owner and related user details
        'species',           // Load species details
        'breed',             // Load breed details
        'transactions' => function ($query) use ($request) {
            // Apply filters to the transactions query
            if ($request->has('search_date') && $request->search_date != '') {
                $query->whereDate('created_at', $request->search_date);
            }

            if ($request->has('transaction_type') && $request->transaction_type != '') {
                $query->where('transaction_type_id', $request->transaction_type);
            }

            if ($request->has('transaction_status') && $request->transaction_status != '') {
                $query->where('status', $request->transaction_status);
            }

            // Order by newest transaction first (sort by created_at descending)
            $query->latest();

            // Load the related models for each transaction
            $query->with([
                'transactionType',  // Load transaction types
                'vet',               // Load veterinarians (vets)
                'transactionSubtype' => function ($subQuery) {
                    $subQuery->with('transactionType');  // Load related transaction type for subtypes
                }
            ]);
        }
    ])
    ->where('animal_id', $animal_id) // Find the animal by animal_id
    ->firstOrFail(); // Ensure it exists or return a 404 error

    // Fetch additional data for transaction forms (if needed for dropdowns, etc.)
    $transactionTypes = TransactionType::all(); // Get all transaction types
    $transactionSubtypes = TransactionSubtype::all(); // Get all transaction subtypes
    $vets = User::where('role', 2)->get(); // Get all veterinarians (role 2 = Veterinarian)
    
    // Fetch vaccines and technicians
    $vaccines = Vaccine::all();  // Fetch all vaccines
    $technicians = VeterinaryTechnician::all();  // Fetch all veterinary technicians

    // Paginate the transactions for the given animal
    $transactions = $animal->transactions()
        ->orderBy('created_at', 'desc')
        ->paginate(10); // Apply pagination to transactions (10 per page)

    // Return the view with the necessary data
    return view('vet.animal-profile', compact(
        'animal', 
        'transactionTypes', 
        'transactionSubtypes', 
        'vets', 
        'transactions', 
        'vaccines', // Pass vaccines to the view
        'technicians' // Pass technicians to the view
    ));
}



public function showRegistrationForm()
{
    $categories = Category::all();
    $barangays = Barangay::all(); // Get all barangays for selection
    return view('vet.add-owners', compact('barangays', 'categories'));
}
  /**
     * Handle the user registration.
     */
    public function register(Request $request)
    {
        $validated = $request->validate($this->validationRules($request));

        try {
            DB::beginTransaction();

               // Determine email/username value
    $identifier = $validated['is_email_field'] 
    ? $validated['email']
    : $validated['username'];
            // Generate password
            $randomPassword = Str::random(8);
            
            // Create user
            $user = User::create([
                'complete_name' => $validated['complete_name'],
                'role' => $validated['role'],
                'contact_no' => $validated['contact_no'],
                'gender' => $validated['gender'],
                'birth_date' => $validated['birth_date'],
                'status' => 1, // Default active status
                'email' => $identifier,
                'password' => Hash::make($randomPassword),
                'designation_id' => $validated['designation_id'] ?? null,
            ]);

            // Create address
            $user->address()->create([
                'barangay_id' => $validated['barangay_id'],
                'street' => $validated['street'],
            ]);

            // If owner, create owner record and attach categories
            if ($validated['role'] == 1) {
                $owner = $user->owner()->create([
                    'civil_status' => $validated['civil_status'],
                    'permit' => 1, // Default permit status
                ]);

                if (!empty($validated['selectedCategories'])) {
                    $user->categories()->attach($validated['selectedCategories']);
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Cannot add user due to an issue. Please try again.')
                         ->withInput();
        }
    
        // Email sending outside transaction
        try {
            if ($validated['is_email_field']) {
                Mail::to($user->email)->send(new WelcomeEmail($user, $randomPassword));
            }
        } catch (\Exception $e) {
            \Log::error('Email sending failed: ' . $e->getMessage());
        }
    
        return redirect()->route('vet-owners')->with([
            'credentials' => [
                'identifier' => $user->email,
                'password' => $randomPassword,
                'is_email' => $validated['is_email_field']
            ]
        ])->with('message', 'User registered successfully! Password has been sent to their email.');
            }

    private function validationRules(Request $request)
    {
        $rules = [
            'complete_name' => 'required|string|max:255',
            'role' => 'required|integer|in:1,2,3',
            'contact_no' => 'nullable|string|max:15',
            'gender' => 'required|string|in:Male,Female',
            'birth_date' => 'nullable|date|before_or_equal:today',
            'barangay_id' => 'required|exists:barangays,id',
            'street' => 'required|string|max:255',
            'is_email_field' => 'required|boolean',
        ];

        // Role-specific rules
        if ($request->role == 1) {
            $rules['civil_status'] = 'required|string|in:Married,Separated,Single,Widow';
            $rules['selectedCategories'] = 'required|array|min:1';
            $rules['selectedCategories.*'] = 'exists:categories,id';

            if ($request->role == 1) {
                if ($request->is_email_field) {
                    $rules['email'] = 'required|email|max:255|unique:users,email';
                    $rules['username'] = 'nullable|string|min:5|max:25|regex:/^[a-zA-Z0-9_.]+$/';
                } else {
                    $rules['username'] = 'required|string|min:5|max:25|regex:/^[a-zA-Z0-9_.]+$/|unique:users,email';
                    $rules['email'] = 'nullable|email';
                }
            }
        
            return $rules;
    }
}




public function ownerList_edit($owner_id)
{
    // Fetch the owner details using the `user_id` foreign key
    $owner = Owner::where('user_id', $owner_id)->firstOrFail();

    // Fetch the user with their related address
    $user = User::with('address')->findOrFail($owner_id);

    // Fetch all barangays for the dropdown list
    $barangays = Barangay::all();

    $categories = Category::all();

    // Pass the data to the view
    return view('vet.owner-edit', compact('user', 'barangays', 'owner','categories'));
}

       public function ownerList_update(Request $request, $owner_id)
    {
        try {
            // Log the incoming request data for debugging
            Log::info('Owner update request data:', $request->all());
            
            // Validation
            $validated = $request->validate([
                'complete_name' => 'required|string|max:100',
                'contact_no' => 'required|string|max:15',
                'gender' => 'required|string|max:10',
                'birth_date' => ['nullable', 'date'],
                'status' => 'required|integer',
                'is_email_field' => 'required|boolean',
                'identifier' => [
                    'required',
                    'string',
                    'max:255',
                    function ($attribute, $value, $fail) use ($request, $owner_id) {
                        if ($request->is_email_field) {
                            // Email validation
                            if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                                $fail('The email address is invalid.');
                            }
                        } else {
                            // Username validation
                            if (strlen($value) < 5) {
                                $fail('The username must be at least 5 characters.');
                            }
                            if (!preg_match('/^[a-zA-Z0-9_.]+$/', $value)) {
                                $fail('The username can only contain letters, numbers, underscore and dot.');
                            }
                        }
                        
                        // Check uniqueness (using the email field for both)
                        if (User::where('email', $value)
                                ->where('user_id', '!=', $owner_id)
                                ->exists()) {
                            $fail('This ' . ($request->is_email_field ? 'email' : 'username') . ' is already taken.');
                        }
                    }
                ],
                'barangay_id' => 'required|exists:barangays,id',
                'street' => 'nullable|string|max:255',
                'profile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                'civil_status' => 'nullable|string|max:50',
                'selectedCategories' => 'nullable|array',
                'selectedCategories.*' => 'exists:categories,id'
            ]);

            DB::beginTransaction();

            // Find the user
            $user = User::findOrFail($owner_id);

            // Handle profile image upload
            if ($request->hasFile('profile_image')) {
                // Delete the old profile image if it exists
                if ($user->profile_image) {
                    $oldPath = public_path('storage/' . $user->profile_image);
                    if (file_exists($oldPath)) {
                        unlink($oldPath);
                    }
                }
                $filename = time() . '_' . $request->file('profile_image')->getClientOriginalName();
                $request->file('profile_image')->move(public_path('storage/profile_images'), $filename);
                $user->profile_image = 'profile_images/' . $filename;
            }

            // Update user
            $user->update([
                'complete_name' => $validated['complete_name'],
                'contact_no' => $validated['contact_no'],
                'gender' => $validated['gender'],
                'birth_date' => $validated['birth_date'],
                'status' => $validated['status'],
                'role' => 1,
                'profile_image' => $user->profile_image,
                'is_email_field' => $validated['is_email_field'],
                'email' => $validated['identifier'],
            ]);

            // Update address
            $user->address()->updateOrCreate(
                ['user_id' => $user->user_id],
                [
                    'barangay_id' => $validated['barangay_id'],
                    'street' => $validated['street'] ?? '',
                ]
            );

            // Update owner
            $owner = $user->owner()->updateOrCreate(
                ['user_id' => $user->user_id],
                [
                    'civil_status' => $validated['civil_status'] ?? '',
                    'permit' => 1,
                ]
            );

            // Handle categories with special rules
            if (isset($validated['selectedCategories'])) {
                $selectedCategories = $validated['selectedCategories'];
                
                // If gender is Male, remove categories 4 and 6 (pregnancy and lactating related)
                if ($validated['gender'] === 'Male') {
                    $selectedCategories = array_values(array_filter($selectedCategories, function($categoryId) {
                        return !in_array((int)$categoryId, [4, 6]);
                    }));
                }
                
                // Handle special categories (0, 8, 9) - ensure only one is selected
                $specialCategoryIds = [0, 8, 9];
                $hasSpecialCategory = false;
                $selectedSpecialCategory = null;
                
                // Check if any special category is selected
                foreach ($selectedCategories as $categoryId) {
                    if (in_array((int)$categoryId, $specialCategoryIds)) {
                        if (!$hasSpecialCategory) {
                            $hasSpecialCategory = true;
                            $selectedSpecialCategory = (int)$categoryId;
                        } else {
                            // If multiple special categories are found, keep only the last one
                            $selectedSpecialCategory = (int)$categoryId;
                        }
                    }
                }
                
                // Filter out all special categories
                $filteredCategories = array_values(array_filter($selectedCategories, function($categoryId) use ($specialCategoryIds) {
                    return !in_array((int)$categoryId, $specialCategoryIds);
                }));
                
                // Add back the selected special category if one was chosen
                if ($selectedSpecialCategory !== null) {
                    $filteredCategories[] = (string)$selectedSpecialCategory;
                }
                
                // Convert all values to integers
                $categories = array_map(function($categoryId) {
                    return (int)$categoryId;
                }, $filteredCategories);
                
                // Log the categories being processed
                Log::info('Categories being synced:', $categories);
                
                // Sync the categories
                $user->categories()->sync($categories);
            } else {
                // If no categories selected, detach all
                $user->categories()->detach();
            }

            // Verify that categories were updated correctly
            $updatedCategories = $user->categories()->pluck('categories.id')->toArray();
            Log::info('Updated categories for user ' . $user->user_id, [
                'updated_categories' => $updatedCategories
            ]);

            DB::commit();
            
            return redirect()->route('vet-owners')->with('success', 'Profile updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating owner: ' . $e->getMessage(), [
                'user_id' => $owner_id,
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'An error occurred while updating the profile: ' . $e->getMessage()]);
        }
    }
    

}
