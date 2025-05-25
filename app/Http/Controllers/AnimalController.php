<?php

namespace App\Http\Controllers;

use App\Models\Vaccine; // Import the Species model
use App\Models\VeterinaryTechnician; // Import the Species model
use App\Models\Breed; // Import the Species model
use App\Models\Transaction; // Import the Species model
use App\Models\TransactionSubtype; // Import the model
use App\Models\Animal; // Import the Species model
use App\Models\TransactionType; // Import the model
use App\Models\Species; // Import the Species model
use App\Models\Owner;
use App\Models\Address;
use App\Models\Barangay;
use App\Models\City;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; // ✅ ADD THIS LINE
use Illuminate\Support\Facades\Storage; // Add this line
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;



use Illuminate\Http\Request;

class AnimalController extends Controller
{
    public function showID($animal_id)
    {
        $animal = Animal::with([
            'owner.user',
            'species',
            'breed'
        ])->where('animal_id', $animal_id)->first();

        // Generate QR code with animal information
        $qrCode = QrCode::size(200)->generate(route('animal.id', $animal_id));

        return view('admin.animal_id', compact('animal', 'qrCode'));
    }

    public function downloadIDPdf($animal_id)
{
    $animal = Animal::with([
        'owner.user',
        'species',
        'breed'
    ])->where('animal_id', $animal_id)->first();

    if (!$animal) {
        abort(404);
    }

    // Generate QR code HTML
    $qrCode = QrCode::size(100)->generate(route('animal.id', $animal_id));

    // Load the same Blade view used for showing the ID
    $pdf = Pdf::loadView('admin.pdf_id', compact('animal', 'qrCode'))->setPaper('a4', 'portrait');

    return $pdf->download('animal-id-' . $animal->animal_id . '.pdf');
}

    public function showProfile($animal_id)
    {
        $animal = Animal::with([
            'owner.user',
            'species',
            'breed',
            'transactions.vet',

        ])->findOrFail($animal_id);
    
        $transactionTypes = TransactionType::all();
        $transactionSubtypes = TransactionSubtype::all();
        $vets = User::where('role', 2)->get();

    

    
        return view('admin.animal-profile', compact('animal', 'transactionTypes', 'transactionSubtypes', 'vets',));
    }
    
    
    public function store(Request $request, $animal_id)
    {
        // Validate incoming request
        $request->validate([
            'transaction_type_id' => 'required|exists:transaction_types,id',
            'transaction_subtype_id' => 'required|exists:transaction_subtypes,id',
            'vet_id' => 'nullable|exists:users,user_id', // Vet must be a valid user
            'technician_id' => 'nullable|exists:veterinary_technicians,technician_id', // Technician ID now from VeterinaryTechnician model
            'vaccine_id' => 'nullable|exists:vaccines,id', // Vaccine optional
            'notes' => 'nullable|string',
        ]);
    
        // Ensure the animal exists by animal_id
        $animal = Animal::where('animal_id', $animal_id)->first();
        if (!$animal) {
            return redirect()->route('animals.index')->with('error', 'Animal not found.');
        }
    
        // Create a new transaction
        $transaction = new Transaction();
        $transaction->animal_id = $animal_id;  // Use animal_id to associate with the transaction
        $transaction->transaction_type_id = $request->transaction_type_id;
        $transaction->transaction_subtype_id = $request->transaction_subtype_id;
        $transaction->owner_id = $animal->owner_id; // Assuming you get owner_id from the animal
        $transaction->vet_id = $request->vet_id;
        $transaction->technician_id = $request->technician_id;  // Associate technician from VeterinaryTechnician model
        $transaction->vaccine_id = $request->vaccine_id;  // Associate vaccine if provided
        $transaction->details = $request->notes;
        $transaction->status = 0; // Default status (Pending)
        $transaction->save();
    
        // Redirect or return response
        return redirect()->route('animals.profile', ['animal_id' => $animal_id])
            ->with('success', 'Transaction added successfully!');
    }

// Show the form for editing the animal's details
public function edit_animal($animal_id)
{
    // Fetch the animal by its ID
    $animal = Animal::where('animal_id', $animal_id)->firstOrFail();


    // Fetch all species, breeds, and owners for dropdown selections
    $species = Species::all();
    $breeds = Breed::all();
    $owners = Owner::all();
    $vaccines = Vaccine::all();

    // Return the view with the data to populate the form
    return view('admin.animal-edit', compact('animal', 'species', 'breeds', 'owners', 'vaccines'));
}

public function update(Request $request, $animal_id)
{
    $validationRules = [
        'species_id' => 'required|exists:species,id',
        'breed_id' => 'required|exists:breeds,id',
        'birth_date' => ['nullable', 'date', 'before_or_equal:today'], // Ensure birthdate is not in the future
        'color' => 'nullable|string|max:255', // Add validation for color
        'medical_condition' => 'nullable|string|max:255',
        'photo_front' => 'nullable|image|max:2048',
        'photo_back' => 'nullable|image|max:2048',
        'photo_left_side' => 'nullable|image|max:2048',
        'photo_right_side' => 'nullable|image|max:2048',
        'is_group' => 'required|boolean', // Add validation for is_group
        'is_vaccinated' => 'required|in:0,1,2', // Add validation for is_vaccinated

    ];

    // Apply conditional validation based on 'is_group'
    if ($request->is_group == 0) {
        // If it's not a group, name and gender are required
        $validationRules['name'] = 'required|string|max:255';
        $validationRules['gender'] = 'required|in:Male,Female';
        $validationRules['group_count'] = 'nullable'; // Don't require group_count for non-groups
    } else {
        // If it's a group, name and gender are not required
        $validationRules['name'] = 'nullable|string|max:255'; // Allow name to be null
        $validationRules['gender'] = 'nullable|in:Male,Female'; // Allow gender to be null
        $validationRules['group_count'] = 'required|integer|min:1'; // Require group_count for groups
    }

    // Validate the form input
    $request->validate($validationRules);

    // Fetch the existing animal record
    $animal = Animal::where('animal_id', $animal_id)->firstOrFail();

    // Prepare the animal data
    $data = $request->only([
        'name', 'species_id', 'breed_id', 'birth_date', 'gender', 'medical_condition', 'is_group', 'group_count', 'color', 'is_vaccinated',
    ]);

    // Handle photo uploads and update the existing photos
foreach (['photo_front', 'photo_back', 'photo_left_side', 'photo_right_side'] as $photo) {
    if ($request->hasFile($photo)) {
        // Delete the old photo if it exists
        if ($animal->{$photo}) {
            $oldPath = public_path('storage/' . $animal->{$photo});
            if (file_exists($oldPath)) {
                unlink($oldPath);
            }
        }
        // Create the directory if it doesn't exist
        $destinationPath = public_path('storage/animal_photos');
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }
        // Move the uploaded file
        $filename = time() . '_' . $request->file($photo)->getClientOriginalName();
        $request->file($photo)->move($destinationPath, $filename);
        $data[$photo] = 'animal_photos/' . $filename; // Save relative path
    } else {
        $data[$photo] = $animal->{$photo};
    }
}

    // If it's a group, handle the group-specific logic
    if ($data['is_group'] == 1) {
        // Set a default value for name (or empty string) when it's a group
        $data['name'] = $data['name'] ?? 'Group'; // Use 'Group' or leave it empty
        $data['gender'] = null; // Groups do not have a gender
    }

    try {
        // Update the animal record
        Animal::where('animal_id', $animal_id)->update($data);

        // Redirect with success message
        return redirect()->route('animals.profile', ['animal_id' => $animal->animal_id])
        ->with('success', 'Animal updated successfully.');

    } catch (\Exception $e) {
        // Handle exceptions and return the error message
        return back()->withErrors(['error' => $e->getMessage()]);
    }
}

//DELETE ANIMALS IN ANIMALS TABLE
    public function destroy($animal_id)
{
    $animal = Animal::where('animal_id', $animal_id)->firstOrFail();
    if ($animal->photo_front) {
        Storage::disk('public')->delete($animal->photo_front);
    }


        // Delete related transactions
    Transaction::where('animal_id', $animal_id)->delete();

    Animal::where('animal_id', $animal_id)->delete();

 

    return redirect()->route('admin-animals')->with('message', 'Animal deleted successfully.');
}

public function showAddAnimalForm()
{
    // Fetch all species and breeds
    $species = Species::all();
    $breeds = Breed::all();

    // Fetch all owners (users with role 1)
    $owners = User::where('role', 1)
                  ->join('owners', 'owners.user_id', '=', 'users.user_id')
                  ->select('users.user_id', 'owners.owner_id', 'users.complete_name')
                  ->get(); // Ensure you're getting the 'complete_name' as well

    // Fetch all veterinarians (users with role 2)
    $vets = User::where('role', 2)->get();

    // Fetch all transaction types and subtypes
    $transactionTypes = TransactionType::all();
    $transactionSubtypes = TransactionSubtype::all(); // Ensure this fetches all subtypes
    $technicians = VeterinaryTechnician::all();
    $vaccines = Vaccine::all();

    // Return the view with the necessary data
    return view('admin.plus-animal', compact(
        'species',
        'breeds',
        'owners',
        'vets',
        'transactionTypes',
        'transactionSubtypes',
        'technicians',
        'vaccines',
    ));
}

public function animalStore(Request $request)
{
    // Validate the form input
    $request->validate([
        'name' => 'required_if:is_group,false|string|max:255', // Required for individual animals
        'owner_id' => 'required|exists:owners,owner_id',
        'species_id' => 'required|exists:species,id',
        'breed_id' => 'required|exists:breeds,id',
        'group_count' => 'required_if:is_group,true|integer|min:1',
        'birth_date' => ['nullable', 'date', 'before_or_equal:today'], // Ensure birthdate is not in the future
        'gender' => 'nullable|required_if:is_group,false|in:Male,Female', // Gender required for individual animals
        'medical_condition' => 'nullable|string|max:255',
        'color' => 'required|string|max:100', // New color field, required for both
        'photo_front' => 'nullable|image|max:2048',
        'photo_back' => 'nullable|image|max:2048',
        'photo_left_side' => 'nullable|image|max:2048',
        'photo_right_side' => 'nullable|image|max:2048',
        'is_group' => 'required|boolean', // Determine if it's a group
        'is_vaccinated' => 'required|in:0,1,2', // Add validation for is_vaccinated

    ]);
    
    // Prepare the animal data
    $data = $request->only([
        'name','color', 'owner_id', 'species_id', 'breed_id', 'birth_date', 'gender', 'medical_condition', 'is_group', 'group_count','is_vaccinated',
    ]);

    // Convert is_group to boolean
    $data['is_group'] = $request->is_group == '1';

    // Handle group-specific logic
    if ($data['is_group']) {
        $data['gender'] = null; // Groups do not have a gender
        $data['group_count'] = max(1, $data['group_count'] ?? 1); // Ensure group_count is at least 1
    } else {
        $data['group_count'] = 1; // Individual animals do not have a group count
    }

foreach (['photo_front', 'photo_back', 'photo_left_side', 'photo_right_side'] as $photo) {
    if ($request->hasFile($photo)) {
        $filename = time() . '_' . $request->file($photo)->getClientOriginalName();
        $request->file($photo)->move(public_path('storage/animal_photos'), $filename);
        $data[$photo] = 'animal_photos/' . $filename;
    }
}
    try {
        // Save the animal record and get the created instance
        $animal = Animal::create([
            'name' => $data['name'], // Use default for groups
            'color' => $data['color'], // Use default for groups
            'owner_id' => $data['owner_id'],
            'species_id' => $data['species_id'],
            'breed_id' => $data['breed_id'],
            'birth_date' => $data['birth_date'],
            'gender' => $data['gender'], // Null for groups
            'medical_condition' => $data['medical_condition'],
            'is_group' => $data['is_group'],
            'group_count' => $data['group_count'] ?? 1, // Null for individual animals
            'is_vaccinated' => $data['is_vaccinated'],
            'photo_front' => $data['photo_front'] ?? null,
            'photo_back' => $data['photo_back'] ?? null,
            'photo_left_side' => $data['photo_left_side'] ?? null,
            'photo_right_side' => $data['photo_right_side'] ?? null,
        ]);

        // Redirect with success message
        return redirect()->route('admin-animals')
        ->with('message', 'Animal added successfully.');
    

    } catch (\Exception $e) {
        // Handle exceptions and return the error message
        return back()->withErrors(['error' => $e->getMessage()]);
    }
}

public function showEditAnimalForm($animal_id)
{
    // Fetch the existing animal by ID
    $animal = Animal::where('animal_id', $animal_id)->firstOrFail();

    // Fetch all species and breeds
    $species = Species::all();
    $breeds = Breed::all();

    // Fetch all owners (users with role 1)
    $owners = User::where('role', 1)
                  ->join('owners', 'owners.user_id', '=', 'users.user_id')
                  ->select('users.user_id', 'owners.owner_id', 'users.complete_name')
                  ->get(); // Ensure you're getting the 'complete_name' as well

    // Fetch all veterinarians (users with role 2)
    $vets = User::where('role', 2)->get();

    // Fetch all transaction types and subtypes
    $transactionTypes = TransactionType::all();
    $transactionSubtypes = TransactionSubtype::all(); // Ensure this fetches all subtypes

    // Fetch the related transaction (assuming the latest or the relevant one)
    $transaction = Transaction::where('animal_id', $animal_id)->latest()->first();

    // Return the view with the necessary data
    return view('admin.edit-animal-form', compact(
        'animal',
        'species',
        'breeds',
        'owners',
        'vets',
        'transactionTypes',
        'transactionSubtypes',
        'transaction'
    ));
}

public function animalUpdate(Request $request, $animal_id)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'owner_id' => 'required|exists:owners,owner_id',
        'species_id' => 'required|exists:species,id',
        'breed_id' => 'required|exists:breeds,id',
        'color' => 'nullable|string|max:255',
        'birth_date' => ['nullable', 'date', 'before_or_equal:today'],
        'gender' => 'nullable|in:Male,Female',
        'medical_condition' => 'nullable|string|max:255',
        'photo_front' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'photo_back' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'photo_left_side' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'photo_right_side' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'is_group' => 'nullable|boolean',
        'group_count' => 'nullable|integer|min:1|required_if:is_group,true',
        'is_vaccinated' => 'required|in:0,1,2',
    ]);

    $animal = Animal::where('animal_id', $animal_id)->firstOrFail();

    // Handle photo uploads
    $photos = [];
    foreach (['photo_front', 'photo_back', 'photo_left_side', 'photo_right_side'] as $photo) {
        if ($request->hasFile($photo)) {
            // Delete the old photo if it exists
            if ($animal->$photo) {
                $oldPath = public_path('storage/' . $animal->$photo);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }
            $filename = time() . '_' . $request->file($photo)->getClientOriginalName();
            $request->file($photo)->move(public_path('storage/animals/photos'), $filename);
            $photos[$photo] = 'animals/photos/' . $filename;
        } else {
            $photos[$photo] = $animal->$photo; // Retain the existing photo if no new upload
        }
    }

    // Update the animal record
    Animal::where('animal_id', $animal_id)->update([
        'name' => $request->name,
        'owner_id' => $request->owner_id,
        'species_id' => $request->species_id,
        'breed_id' => $request->breed_id,
        'color' => $request->color,
        'birth_date' => $request->birth_date,
        'gender' => $request->gender,
        'medical_condition' => $request->medical_condition,
        'photo_front' => $photos['photo_front'],
        'photo_back' => $photos['photo_back'],
        'photo_left_side' => $photos['photo_left_side'],
        'photo_right_side' => $photos['photo_right_side'],
        'is_group' => $request->is_group,
        'group_count' => $request->is_group ? $request->group_count : 1,
        'is_vaccinated' => $request->is_vaccinated,
    ]);

    return redirect()->route('admin-animals')
                     ->with('message', 'Animal updated successfully!');
}


public function getTransactionDetail(Request $request, $transaction_id)
{
    try {
        $transaction = DB::table('transactions as t')
            ->leftJoin('owners as o', 't.owner_id', '=', 'o.owner_id')
            ->leftJoin('users as u', 'o.user_id', '=', 'u.user_id')
            ->leftJoin('animals as a', 't.animal_id', '=', 'a.animal_id')
            ->leftJoin('species as s', 'a.species_id', '=', 's.species_id')
            ->leftJoin('breeds as b', 'a.breed_id', '=', 'b.breed_id')
            ->leftJoin('transaction_types as tt', 't.transaction_type_id', '=', 'tt.id')
            ->leftJoin('transaction_subtypes as ts', 't.transaction_subtype_id', '=', 'ts.id')
            ->leftJoin('veterinary_technicians as vt', 't.technician_id', '=', 'vt.technician_id')
            ->where('t.transaction_id', $transaction_id)
            ->select([
                't.*',
                'u.complete_name as owner_name',
                'u.profile_image as owner_image',
                'a.name as animal_name',
                'a.photo_front as animal_image',
                's.name as species_name',
                'b.name as breed_name',
                'tt.type_name as transaction_type',
                'ts.subtype_name as transaction_subtype',
                'vt.full_name as technician_name',
                'o.owner_id'
            ])
            ->first();

        if (!$transaction) {
            return back()->with('error', 'Transaction not found.');
        }

        // Add this debug line
        \Log::info('Transaction details:', ['transaction' => $transaction]);

        // Make sure this view exists at resources/views/admin/transaction-details.blade.php
        return view('admin.transaction-details', compact('transaction'));

    } catch (\Exception $e) {
        \Log::error('Error fetching transaction:', [
            'error' => $e->getMessage(),
            'transaction_id' => $transaction_id
        ]);
        return back()->with('error', 'Error loading transaction details.');
    }
}

public function historyPdf($animal_id)
{
    $animal = Animal::with([
        'transactions.transactionType',
        'transactions.transactionSubtype',
        'transactions.vet',
        'owner.user',
        'species',
        'breed'
    ])
    ->where('animal_id', $animal_id)
    ->firstOrFail();

    $transactions = $animal->transactions;

    $pdf = Pdf::loadView('pdf.animal_history', compact('animal', 'transactions'));
    return $pdf->download("animal-{$animal->animal_id}-history.pdf");
}

// Vaccination Card
public function showVaccinationCard($animal_id)
{
    $animal = Animal::with([
        'owner.user',
        'species',
        'breed',
        'transactions' => function($query) {
            $query->whereHas('transactionSubtype', function($q) {
                $q->where('subtype_name', 'like', '%vaccine%');
            })->with('vaccine', 'vet');
        }
    ])->where('animal_id', $animal_id)->first();

    if (!$animal) {
        abort(404);
    }

    // Generate QR code with animal information
    $qrCode = QrCode::size(150)->generate(route('animal.vaccination-card', $animal_id));

    return view('admin.vaccination_card', compact('animal', 'qrCode'));
}

public function downloadVaccinationCardPdf($animal_id)
{
    $animal = Animal::with([
        'owner.user',
        'species',
        'breed',
        'transactions' => function($query) {
            $query->whereHas('transactionSubtype', function($q) {
                $q->where('subtype_name', 'like', '%vaccine%');
            })->with('vaccine', 'vet');
        }
    ])->where('animal_id', $animal_id)->first();

    if (!$animal) {
        abort(404);
    }

    // Generate QR code HTML
    $qrCode = QrCode::size(100)->generate(route('animal.vaccination-card', $animal_id));

    // Load the view for PDF
    $pdf = Pdf::loadView('pdf.vaccination_card', compact('animal', 'qrCode'))->setPaper('a4', 'portrait');

    return $pdf->download('vaccination-card-' . $animal->animal_id . '.pdf');
}

// Travel Certificate
public function showTravelCertificate($animal_id)
{
    $animal = Animal::with([
        'owner.user',
        'species',
        'breed',
        'transactions' => function($query) {
            $query->whereHas('transactionType', function($q) {
                $q->where('type_name', 'like', '%vaccination%');
            })->with('vaccine', 'vet')
            ->orderBy('created_at', 'desc');
        }
    ])->where('animal_id', $animal_id)->first();

    if (!$animal) {
        abort(404);
    }

    // Generate QR code with animal information
    $qrCode = QrCode::size(150)->generate(route('animal.travel-certificate', $animal_id));

    return view('admin.travel_certificate', compact('animal', 'qrCode'));
}

public function downloadTravelCertificatePdf($animal_id)
{
    $animal = Animal::with([
        'owner.user',
        'species',
        'breed',
        'transactions' => function($query) {
            $query->whereHas('transactionType', function($q) {
                $q->where('type_name', 'like', '%vaccination%');
            })->with('vaccine', 'vet')
            ->orderBy('created_at', 'desc');
        }
    ])->where('animal_id', $animal_id)->first();

    if (!$animal) {
        abort(404);
    }

    // Generate QR code HTML
    $qrCode = QrCode::size(100)->generate(route('animal.travel-certificate', $animal_id));

    // Load the view for PDF
    $pdf = Pdf::loadView('pdf.travel_certificate', compact('animal', 'qrCode'))->setPaper('a4', 'portrait');

    return $pdf->download('travel-certificate-' . $animal->animal_id . '.pdf');
}

// Health Certificate
public function showHealthCertificate($animal_id)
{
    $animal = Animal::with([
        'owner.user',
        'species',
        'breed',
        'transactions' => function($query) {
            $query->whereHas('transactionType', function($q) {
                $q->where('type_name', 'like', '%health%');
            })->with('vet')
            ->orderBy('created_at', 'desc');
        }
    ])->where('animal_id', $animal_id)->first();

    if (!$animal) {
        abort(404);
    }

    // Generate QR code
    $qrCode = QrCode::size(150)->generate(route('animal.health-certificate', $animal_id));
    
    // Generate unique OR number (format: YYYY-XXXXXXX)
    $orNumber = date('Y') . '-' . sprintf('%07d', rand(1, 9999999));
    
    // Generate license number for the vet
    $licenseNumber = '0' . rand(1000, 9999);
    
    // Set license validity (1 year from now)
    $licenseValidUntil = date('m/d/Y', strtotime('+1 year'));

    return view('admin.health_certificate', compact('animal', 'qrCode', 'orNumber', 'licenseNumber', 'licenseValidUntil'));
}

public function downloadHealthCertificatePdf($animal_id)
{
    $animal = Animal::with([
        'owner.user',
        'species',
        'breed',
        'transactions' => function($query) {
            $query->whereHas('transactionType', function($q) {
                $q->where('type_name', 'like', '%health%');
            })->with('vet')
            ->orderBy('created_at', 'desc');
        }
    ])->where('animal_id', $animal_id)->first();

    if (!$animal) {
        abort(404);
    }

    // Generate QR code
    $qrCode = QrCode::size(100)->generate(route('animal.health-certificate', $animal_id));
    
    // Generate unique OR number (format: YYYY-XXXXXXX)
    $orNumber = date('Y') . '-' . sprintf('%07d', rand(1, 9999999));
    
    // Generate license number for the vet
    $licenseNumber = '0' . rand(1000, 9999);
    
    // Set license validity (1 year from now)
    $licenseValidUntil = date('m/d/Y', strtotime('+1 year'));

    // Load the view for PDF
    $pdf = Pdf::loadView('pdf.health_certificate', compact('animal', 'qrCode', 'orNumber', 'licenseNumber', 'licenseValidUntil'))->setPaper('a4', 'portrait');

    return $pdf->download('health-certificate-' . $animal->animal_id . '.pdf');
}

public function toggleStatus(Request $request, $animal_id)
{
    $animal = Animal::where('animal_id', $animal_id)->firstOrFail();
    
    // If currently alive, mark as deceased
    if ($animal->isAlive === true) {
        // Validate death date when marking as deceased
        $request->validate([
            'death_date' => 'required|date|before_or_equal:today',
        ]);

        DB::table('animals')
            ->where('animal_id', $animal_id)
            ->update([
                'isAlive' => false,
                'death_date' => $request->death_date,
                'updated_at' => now()
            ]);

        return back()->with('message', $animal->name . ' has been marked as deceased.');
    } 
    // If currently deceased or status not set (null), mark as alive
    else {
        // When marking as alive, clear death date
        DB::table('animals')
            ->where('animal_id', $animal_id)
            ->update([
                'isAlive' => true,
                'death_date' => null,
                'updated_at' => now()
            ]);

        return back()->with('message', $animal->name . ' has been marked as alive.');
    }
}
}