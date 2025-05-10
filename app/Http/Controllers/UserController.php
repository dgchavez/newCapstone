<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeEmail; 
use Illuminate\Http\Request; // Add this import statement
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\Rules\Password;
use App\Models\Barangay;
use Illuminate\Support\Facades\Storage;
use App\Models\Designation;
use App\Models\Category;

use App\Models\Owner;
use App\Models\Address;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;




class UserController extends Controller
{   
    public function settings()
    {
        return view('admin.settings');
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

    public function profile($id)
    {
        $user = User::with(['address', 'categories']) // Add categories to eager loading
            ->findOrFail($id);
        $barangays = Barangay::all();
        return view('admin.profile', compact('user', 'barangays'));
    }

    public function navProfile($id)
    {
        $user = User::with('address')->findOrFail($id); // Fetch the user with their address
        $barangays = Barangay::all(); // Fetch all barangays
        return view('admin.user-profile', compact('user', 'barangays')); // Pass data to the view
    }
    public function edit($id)
    {
        $user = User::with(['owner', 'address', 'categories'])->findOrFail($id);
        
        return view('admin.edit', [
            'user' => $user,
            'barangays' => Barangay::all(),
            'designations' => Designation::all(),
            'categories' => Category::all(),
        ]);
    }
    

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        // Base validation rules
        $validationRules = [
            'complete_name' => 'required|string|max:100',
            'role' => 'required|integer',
            'contact_no' => 'required|string|max:15',
            'gender' => 'required|string|max:10',
            'birth_date' => ['nullable', 'date', 'before_or_equal:today'],
            'status' => 'required|integer',
            'barangay_id' => 'required|exists:barangays,id',
            'street' => 'nullable|string|max:255',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'designation_id' => 'nullable|exists:designations,designation_id',
            'selectedCategories' => 'required_if:role,1|array',
            'civil_status' => 'required_if:role,1|string|nullable',
            'is_email_field' => 'required|boolean',
            'identifier' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) use ($request, $user) {
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
                            ->where('user_id', '!=', $user->user_id)
                            ->exists()) {
                        $fail('This ' . ($request->is_email_field ? 'email' : 'username') . ' is already taken.');
                    }
                }
            ],
        ];

        $validated = $request->validate($validationRules);

        try {
            DB::beginTransaction();

            // Handle profile image upload if a file is provided
            if ($request->hasFile('profile_image')) {
                if ($user->profile_image) {
                    Storage::disk('public')->delete($user->profile_image);
                }
                $imagePath = $request->file('profile_image')->store('profile_images', 'public');
                copy(storage_path('app/public/' . $imagePath), public_path('storage/' . $imagePath));
                $user->profile_image = $imagePath;
            }

            // Prepare user data for update
            $userData = [
                'complete_name' => $validated['complete_name'],
                'role' => $validated['role'],
                'contact_no' => $validated['contact_no'],
                'gender' => $validated['gender'],
                'birth_date' => $validated['birth_date'],
                'status' => $validated['status'],
                'is_email_field' => $validated['is_email_field'],
                'email' => $validated['identifier'], // Using email field for both email and username
            ];

            // Add designation_id if it exists in validated data
            if (isset($validated['designation_id'])) {
                $userData['designation_id'] = $validated['designation_id'];
            }

            // Update user data
            $user->update($userData);

            // Update or create the address
            $user->address()->updateOrCreate(
                ['user_id' => $user->user_id],
                $request->only(['barangay_id', 'street'])
            );
    
            // Handle owner-specific data if role is owner (1)
            if ($request->role == 1) {
                // Update or create owner data
                $user->owner()->updateOrCreate(
                    ['user_id' => $user->user_id],
                    [
                        'civil_status' => $request->civil_status,
                        'permit' => 1
                    ]
                );
        
                // Update categories
                if (isset($request->selectedCategories)) {
                    // Convert all values to integers and filter out invalid ones
                    $categories = [];
                    foreach ($request->selectedCategories as $categoryId) {
                        // Include the category if it's a valid number (including 0)
                        if (is_numeric($categoryId) || $categoryId === '0') {
                            $categories[] = (int)$categoryId;
                        }
                    }
                    
                    // Log the categories being processed
                    Log::info('Categories being synced:', $categories);
                    
                    // Sync the categories
                    $user->categories()->sync($categories);
                } else {
                    // If no categories selected, detach all
                    $user->categories()->detach();
                }
            } else {
                // If user is not an owner, remove owner data and category associations
                if ($user->owner) {
                    $user->owner->delete();
                }
                $user->categories()->detach();
            }

            DB::commit();

            return redirect()->route('admin-users')
                ->with('message', 'User details updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating user: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'An error occurred while updating the user.')
                ->withInput();
        }
    }
    

    //Controller for  profile update

    public function profile_edit($id)
    {
        $user = User::with('address')->findOrFail($id); // Fetch the user with their address
        $barangays = Barangay::all(); // Fetch all barangays
        return view('admin.profile-edit', compact('user', 'barangays')); // Pass data to the view
    }

    public function profile_update(Request $request, $id)
    {
        $request->validate([
            'complete_name' => 'required|string|max:100',
            'contact_no' => 'required|string|max:15',
            'gender' => 'required|string|max:10',
            'birth_date' => 'required|date',
            'identifier' => 'required|string|max:255',
            'barangay_id' => 'required|exists:barangays,id',
            'street' => 'nullable|string|max:255',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // Added validation for image
          
    ]);

        $user = User::findOrFail($id);
    
        // Handle profile image upload if a file is provided
      // Handle profile image upload if a file is provided
        if ($request->hasFile('profile_image')) {
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }
            $imagePath = $request->file('profile_image')->store('profile_images', 'public');
            // Copy to public/storage for web access
            copy(storage_path('app/public/' . $imagePath), public_path('storage/' . $imagePath));
            $user->profile_image = $imagePath;
        }
    
        // Update user data
        $user->update($request->only([
            'complete_name',
            'contact_no',
            'gender',
            'birth_date',
            'identifier',
        ]));
    
        // Update the password if provided

    
        // Update or create the address
        $user->address()->updateOrCreate(
            ['user_id' => $user->user_id], // Condition to find the address
            $request->only(['barangay_id', 'street']) // Address fields to update
        );
    
        // Update or create the owner's data
        $user->owner()->updateOrCreate(
            ['user_id' => $user->user_id], // Match condition
            $request->only(['civil_status', 'category']) + ['permit' => 1] // Data to update, with permit added
        );
    
        return redirect()->route('users.profile-form', ['id' => $user->user_id])
            ->with('message', 'Profile updated successfully.');
    }
    
    public function resetPassword(User $user)
    {
        try {
            // Generate a random password
            $randomPassword = \Illuminate\Support\Str::random(8);
    
            // Hash the password and update the user's record
            $user->update([
                'password' => bcrypt($randomPassword),
            ]);
    
            // Send the email with the new password
            \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\ResetPasswordEmail($user, $randomPassword));
    
            // Redirect back with a success message
            return redirect()->back()->with('message', "Password for user {$user->complete_name} has been reset and emailed successfully.");
        } catch (\Exception $e) {
            // Redirect back with an error message in case of failure
            return redirect()->back()->with('error', 'An error occurred while resetting the password.');
        }
    }
    


    public function showRegistrationForm()
    {
        return view('admin.add-owners', [
            'barangays' => Barangay::all(),
            'designations' => Designation::all(),
            'categories' => Category::all(),
            'is_email_field' => true
        ]);
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
    
        return redirect()->route('admin-owners')->with([
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
        return view('admin.ownerlist-edit', compact('user', 'barangays', 'owner','categories'));
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

            // Update categories
            if (isset($validated['selectedCategories'])) {
                // Convert all values to integers and filter out invalid ones
                $categories = [];
                foreach ($validated['selectedCategories'] as $categoryId) {
                    // Include the category if it's a valid number (including 0)
                    if (is_numeric($categoryId) || $categoryId === '0') {
                        $categories[] = (int)$categoryId;
                    }
                }
                
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
            
            return redirect()->route('admin-owners')->with('success', 'Profile updated successfully.');

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
    
    public function create_vet()
    {
        $designations = Designation::all(); // Get all designations
        $barangays = Barangay::all(); // Get all barangays
        return view('admin.veterinarians-create', compact('designations', 'barangays'));
    }
    
    public function store_vet(Request $request)
{
    $request->validate([
        'complete_name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'contact_no' => 'required|string|max:20',
        'gender' => 'required|string',
        'birth_date' => ['nullable', 'date', 'before_or_equal:today'], // Ensure birthdate is not in the future
        'designation_id' => 'required|exists:designations,designation_id',
        'barangay_id' => 'required|exists:barangays,id', // Barangay validation
        'street' => 'required|string|max:255', // Street is required
        'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Profile image validation
    ]);

    // Generate a random password
    $randomPassword = \Illuminate\Support\Str::random(8);

    // Hash the password
    $hashedPassword = bcrypt($randomPassword);

    // Create the veterinarian (user)
    $user = \App\Models\User::create([
        'complete_name' => $request->complete_name,
        'email' => $request->email,
        'contact_no' => $request->contact_no,
        'gender' => $request->gender,
        'birth_date' => $request->birth_date,
        'password' => $hashedPassword, // Store the hashed password
        'role' => 2, // Veterinarian role
        'designation_id' => $request->designation_id,
        'status' => 1, // Set status to 1 (active)
    ]);

    // Save address and associate it with the user and barangay
    \App\Models\Address::create([
        'user_id' => $user->user_id,
        'barangay_id' => $request->barangay_id,
        'street' => $request->street, // Save the street address
    ]);

if ($request->hasFile('profile_image')) {
    // Optionally delete the old profile image if it exists
    if ($user->profile_image) {
        $oldPath = public_path('storage/' . $user->profile_image);
        if (file_exists($oldPath)) {
            unlink($oldPath);
        }
    }
    $filename = time() . '_' . $request->file('profile_image')->getClientOriginalName();
    $request->file('profile_image')->move(public_path('storage/profile_images'), $filename);
    $user->profile_image = 'profile_images/' . $filename;
    $user->save();
}
    // Send the email with the generated password
    \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\WelcomeEmail($user, $randomPassword));

    return redirect()->route('admin-veterinarians')->with('message', 'Veterinarian registered successfully! Password has been sent to their email.');
}

    public function deleteProfileImage_vet($id)
    {
        $veterinarian = \App\Models\User::findOrFail($id);

        // Delete the image file from storage
        if ($veterinarian->profile_image && \Storage::exists('public/' . $veterinarian->profile_image)) {
            \Storage::delete('public/' . $veterinarian->profile_image);
        }

        // Update the database to null the profile image
        $veterinarian->profile_image = null;
        $veterinarian->save();

        return redirect()->route('veterinarians.index')->with('success', 'Profile image deleted successfully!');
    }
    //edit vet list
    public function edit_veterinarian($id)
{
    $veterinarian = User::findOrFail($id); // Find the veterinarian by ID

    // Fetch necessary data for the form, such as designations
    $designations = Designation::all();

    return view('admin.veterinarians-edit', compact('veterinarian', 'designations'));
}

public function update_veterinarian(Request $request, $id)
{
    $request->validate([
        'complete_name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $id . ',user_id',
        'contact_no' => 'required|string|max:20',
        'gender' => 'required|string',
        'birth_date' => ['nullable', 'date', 'before_or_equal:today'], // Ensure birthdate is not in the future
        'designation_id' => 'required|exists:designations,designation_id',
        'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    // Find the veterinarian by user_id
    $veterinarian = User::where('user_id', $id)->firstOrFail();

    // Update veterinarian details
    $veterinarian->complete_name = $request->complete_name;
    $veterinarian->email = $request->email;
    $veterinarian->contact_no = $request->contact_no;
    $veterinarian->gender = $request->gender;
    $veterinarian->birth_date = $request->birth_date;
    $veterinarian->designation_id = $request->designation_id;

if ($request->hasFile('profile_image')) {
    // Optionally delete the old profile image if it exists
    if ($veterinarian->profile_image) {
        $oldPath = public_path('storage/' . $veterinarian->profile_image);
        if (file_exists($oldPath)) {
            unlink($oldPath);
        }
    }
    $filename = time() . '_' . $request->file('profile_image')->getClientOriginalName();
    $request->file('profile_image')->move(public_path('storage/profile_images'), $filename);
    $veterinarian->profile_image = 'profile_images/' . $filename;
}
    // Save the updated veterinarian information
    $veterinarian->save();

    // Redirect with success message
    return redirect()->route('admin-veterinarians')->with('message', 'Veterinarian updated successfully!');
}


public function destroy_veterinarian($user_id)
{
    // Find the veterinarian by their user_id
    $veterinarian = User::findOrFail($user_id);

    // If there is additional logic needed, such as deleting associated records, do it here

    // Delete the veterinarian
    $veterinarian->delete();

    // Redirect or return a response
    return redirect()->route('admin-veterinarians')->with('message', 'Veterinarian deleted successfully.');
}

    
    /**
 * Get credentials for a user with username (not email)
 */
public function getUserCredentials(User $user)
{
    // Validate that the current user is an admin
    if (auth()->user()->role !== 0) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }
    
    return response()->json([
        'username' => $user->email // Your app stores usernames in the email field
    ]);
}

/**
 * Reset a password and return it via AJAX
 */
public function resetPasswordAjax(User $user)
{
    try {
        // Generate a random password
        $randomPassword = \Illuminate\Support\Str::random(8);
    
        // Hash the password and update the user's record
        $user->update([
            'password' => bcrypt($randomPassword),
        ]);
    
        // For username users, we don't send an email, just return the password
        return response()->json([
            'success' => true,
            'password' => $randomPassword
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}

/**
 * Approve a pending user
 */
public function approveUser(User $user)
{
    if ($user->status === 0) {
        $user->update(['status' => 1]); // Change from pending (0) to enabled (1)
        return redirect()->back()->with('message', "User {$user->complete_name} has been approved.");
    }
    
    return redirect()->back()->with('error', "User is not in pending status.");
}

/**
 * Get the password for a username-based user (for admin use only)
 */
public function getUserPassword(User $user)
{
    // Validate that the current user is an admin
    if (auth()->user()->role !== 0) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }
    
    // For security reasons, we need to reset the password first
    // since we don't store plain text passwords
    $randomPassword = \Illuminate\Support\Str::random(8);
    
    // Hash the password and update the user's record
    $user->update([
        'password' => bcrypt($randomPassword),
    ]);
    
    return response()->json([
        'success' => true,
        'password' => $randomPassword
    ]);
}

/**
 * Toggle user status between active and disabled
 */
public function toggleUserStatus(Request $request, User $user)
{
    // Don't allow toggling your own account
    if ($user->user_id === auth()->id()) {
        return redirect()->back()->with('error', 'You cannot change your own account status.');
    }
    
    // Validate the status
    $request->validate([
        'status' => 'required|in:1,2', // Only allow active (1) or disabled (2)
    ]);
    
    // Update the user status
    $user->update(['status' => $request->status]);
    
    // Get the status name for the message
    $statusName = $request->status == 1 ? 'enabled' : 'disabled';
    
    return redirect()->back()->with('message', "User {$user->complete_name} has been {$statusName}.");
}

   }

   
