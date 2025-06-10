<?php

namespace App\Http\Controllers;

use App\Models\VeterinaryTechnician; // Import the Species model
use App\Models\Breed; // Import the Species model
use App\Models\Vaccine; // Import the Species model
use App\Models\Technician; // Import the Species model
use App\Models\Designation;
use Illuminate\Support\Facades\Storage;
use App\Models\Document;

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
use DB; 
use App\Models\Category; // Add this import

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function loadAdminDashboard(Request $request)
    {
        // Get all species for the filter dropdown
        $species = Species::select('id', 'name')->orderBy('name')->get();

        // Retrieve filter and search inputs
        $search = $request->input('search');
        $statusFilter = $request->input('status');
        $veterinarianFilter = $request->input('veterinarian');
        $technicianFilter = $request->input('technician');
        $period = $request->input('period', 'monthly');
        $barangayFilter = $request->input('barangay');
        $showUnvaccinated = $request->boolean('show_unvaccinated', false);

        // Get all barangays for the filter dropdown
        $barangays = Barangay::orderBy('barangay_name')->get();

        // Base query for animals with vaccination status
        $animalsQuery = DB::table('animals')
            ->select('animals.*')
            ->leftJoin('transactions', 'animals.animal_id', '=', 'transactions.animal_id')
            ->leftJoin('transaction_types', 'transactions.transaction_type_id', '=', 'transaction_types.id')
            ->where(function($query) {
                $query->whereNotNull('transactions.vaccine_id')
                    ->orWhere('transaction_types.type_name', 'like', '%vaccination%')
                    ->orWhere('transaction_types.type_name', 'like', '%vaccine%');
            });

        // Apply barangay filter if selected
        if ($barangayFilter) {
            $animalsQuery->join('owners', 'animals.owner_id', '=', 'owners.owner_id')
                ->join('users', 'owners.user_id', '=', 'users.user_id')
                ->join('addresses', 'users.user_id', '=', 'addresses.user_id')
                ->where('addresses.barangay_id', $barangayFilter);
        }

        // Get vaccination statistics by barangay with detailed counts
        $barangayStats = DB::table('barangays')
            ->select(
                'barangays.id',
                'barangays.barangay_name',
                DB::raw('COUNT(DISTINCT animals.animal_id) as total_animals'),
                DB::raw('COUNT(DISTINCT CASE 
                    WHEN transactions.vaccine_id IS NOT NULL 
                    OR transaction_types.type_name LIKE "%vaccination%" 
                    OR transaction_types.type_name LIKE "%vaccine%" 
                    THEN animals.animal_id END) as vaccinated_animals'),
                DB::raw('COUNT(DISTINCT CASE 
                    WHEN animals.animal_id IS NOT NULL 
                    AND NOT EXISTS (
                        SELECT 1 FROM transactions t 
                        JOIN transaction_types tt ON t.transaction_type_id = tt.id 
                        WHERE t.animal_id = animals.animal_id 
                        AND (t.vaccine_id IS NOT NULL 
                            OR tt.type_name LIKE "%vaccination%" 
                            OR tt.type_name LIKE "%vaccine%")
                    )
                    THEN animals.animal_id END) as unvaccinated_animals'),
                DB::raw('COUNT(DISTINCT CASE 
                    WHEN transactions.created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                    AND (transactions.vaccine_id IS NOT NULL 
                        OR transaction_types.type_name LIKE "%vaccination%" 
                        OR transaction_types.type_name LIKE "%vaccine%")
                    THEN animals.animal_id END) as vaccinated_last_30_days'),
                DB::raw('COUNT(DISTINCT CASE 
                    WHEN transactions.created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
                    AND (transactions.vaccine_id IS NOT NULL 
                        OR transaction_types.type_name LIKE "%vaccination%" 
                        OR transaction_types.type_name LIKE "%vaccine%")
                    THEN animals.animal_id END) as vaccinated_last_7_days'),
                DB::raw('GROUP_CONCAT(DISTINCT species.name) as animal_species')
            )
            ->leftJoin('addresses', 'barangays.id', '=', 'addresses.barangay_id')
            ->leftJoin('users', 'addresses.user_id', '=', 'users.user_id')
            ->leftJoin('owners', 'users.user_id', '=', 'owners.user_id')
            ->leftJoin('animals', 'owners.owner_id', '=', 'animals.owner_id')
            ->leftJoin('transactions', 'animals.animal_id', '=', 'transactions.animal_id')
            ->leftJoin('transaction_types', 'transactions.transaction_type_id', '=', 'transaction_types.id')
            ->leftJoin('species', 'animals.species_id', '=', 'species.id')
            ->when($request->input('show_unvaccinated_only'), function($query) {
                return $query->having('unvaccinated_animals', '>', 0);
            })
            ->when($request->input('min_unvaccinated'), function($query) use ($request) {
                return $query->having('unvaccinated_animals', '>=', $request->input('min_unvaccinated'));
            })
            ->when($request->input('species_filter'), function($query) use ($request) {
                return $query->whereIn('species.id', (array)$request->input('species_filter'));
            })
            ->when($request->input('barangay'), function($query) use ($request) {
                return $query->where('barangays.barangay_name', $request->input('barangay'));
            })
            ->when($request->input('dateRange'), function($query) use ($request) {
                $days = $request->input('dateRange');
                if ($days != 'all') {
                    return $query->where('transactions.created_at', '>=', now()->subDays($days));
                }
                return $query;
            })
            ->groupBy('barangays.id', 'barangays.barangay_name')
            ->get()
            ->map(function ($barangay) use ($request) {
                // Calculate vaccination rate
                $barangay->vaccination_rate = $barangay->total_animals > 0
                    ? round(($barangay->vaccinated_animals / $barangay->total_animals) * 100, 1)
                    : 0;

                // Process species
                $barangay->animal_species_array = array_filter(explode(',', $barangay->animal_species));

                // Get vaccine counts for this barangay
                $vaccineQuery = DB::table('vaccines')
                    ->select(
                        'vaccines.vaccine_name',
                        DB::raw('COUNT(DISTINCT animals.animal_id) as animal_count')
                    )
                    ->join('transactions', 'vaccines.id', '=', 'transactions.vaccine_id')
                    ->join('animals', 'transactions.animal_id', '=', 'animals.animal_id')
                    ->join('owners', 'animals.owner_id', '=', 'owners.owner_id')
                    ->join('users', 'owners.user_id', '=', 'users.user_id')
                    ->join('addresses', 'users.user_id', '=', 'addresses.user_id')
                    ->where('addresses.barangay_id', $barangay->id)
                    ->whereNotNull('transactions.vaccine_id');

                // Apply the same date filter to vaccine query if specified
                if ($request->input('dateRange') && $request->input('dateRange') != 'all') {
                    $days = $request->input('dateRange');
                    $vaccineQuery->where('transactions.created_at', '>=', now()->subDays($days));
                }

                // Apply species filter to vaccine query if specified
                if ($request->input('species_filter')) {
                    $vaccineQuery->whereIn('animals.species_id', (array)$request->input('species_filter'));
                }

                $barangay->vaccines_used = $vaccineQuery
                    ->groupBy('vaccines.id', 'vaccines.vaccine_name')
                    ->get()
                    ->mapWithKeys(function ($item) {
                        return [$item->vaccine_name => $item->animal_count];
                    })
                    ->toArray();

                return $barangay;
            });

        // Get unvaccinated animals if filter is active
        $unvaccinatedAnimals = collect();
        if ($showUnvaccinated) {
            $unvaccinatedAnimals = DB::table('animals')
                ->select(
                    'animals.*',
                    'species.name as species_name',
                    'users.complete_name',
                    'barangays.barangay_name'
                )
                ->join('species', 'animals.species_id', '=', 'species.id')
                ->join('owners', 'animals.owner_id', '=', 'owners.owner_id')
                ->join('users', 'owners.user_id', '=', 'users.user_id')
                ->join('addresses', 'users.user_id', '=', 'addresses.user_id')
                ->join('barangays', 'addresses.barangay_id', '=', 'barangays.id')
                ->whereNotExists(function($query) {
                    $query->select(DB::raw(1))
                        ->from('transactions')
                        ->join('transaction_types', 'transactions.transaction_type_id', '=', 'transaction_types.id')
                        ->whereRaw('transactions.animal_id = animals.animal_id')
                        ->where(function($q) {
                            $q->whereNotNull('transactions.vaccine_id')
                                ->orWhere('transaction_types.type_name', 'like', '%vaccination%')
                                ->orWhere('transaction_types.type_name', 'like', '%vaccine%');
                        });
                })
                ->when($barangayFilter, function($query) use ($barangayFilter) {
                    return $query->where('barangays.id', $barangayFilter);
                })
                ->get();
        }

        // Query for transactions with filters
        $transactionsQuery = Transaction::with(['transactionSubtype', 'owner.user', 'animal', 'vet', 'technician']);

        if ($search) {
            $transactionsQuery->whereHas('owner.user', function ($query) use ($search) {
                $query->where('complete_name', 'like', '%' . $search . '%');
            })->orWhereHas('animal', function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            });
        }

        // Modified status filter logic
        if ($statusFilter !== null && $statusFilter !== '') {
            $transactionsQuery->where('status', (int)$statusFilter);
        }

        if ($veterinarianFilter) {
            $transactionsQuery->where('vet_id', $veterinarianFilter);
        }

        if ($technicianFilter) {
            $transactionsQuery->where('technician_id', $technicianFilter);
        }

        $recentTransactions = $transactionsQuery->latest()->paginate(5);

        // Fetch data for filters
        $veterinarians = User::where('role', 2)->get(); // Veterinarians have role = 2
        $technicians = VeterinaryTechnician::all(); // Fetch all technicians
        $statuses = [
            0 => 'Pending',
            1 => 'Completed',
            2 => 'Canceled',
        ];

        // Dashboard statistics
        $totalOwners = User::where('role', 1)->count();
        $successfulTransactions = Transaction::where('status', 1)->count();
        $totalAnimals = Animal::count();

        // New statistics
        $newOwnersThisMonth = User::where('role', 1)
            ->whereMonth('created_at', now()->month)
            ->count();

        $transactionsThisMonth = Transaction::where('status', 1)
            ->whereMonth('created_at', now()->month)
            ->count();

        $newAnimalsThisMonth = Animal::whereMonth('created_at', now()->month)
            ->count();

        // Get vaccination statistics
        $vaccinationQuery = DB::table('animals')
            ->leftJoin('transactions', 'animals.animal_id', '=', 'transactions.animal_id')
            ->leftJoin('transaction_types', 'transactions.transaction_type_id', '=', 'transaction_types.id')
            ->where(function($query) {
                $query->whereNotNull('transactions.vaccine_id')
                    ->orWhere('transaction_types.type_name', 'like', '%vaccination%')
                    ->orWhere('transaction_types.type_name', 'like', '%vaccine%');
            });

        // Get total vaccinations
        $totalVaccinations = (clone $vaccinationQuery)->count();

        // Get vaccinations for current month
        $vaccinationsThisMonth = (clone $vaccinationQuery)
            ->whereYear('transactions.created_at', now()->year)
            ->whereMonth('transactions.created_at', now()->month)
            ->count();

        // Get vaccination trends based on period
        $vaccinationLabels = [];
        $vaccinationCounts = [];

        switch($period) {
            case 'weekly':
                for($i = 11; $i >= 0; $i--) {
                    $startDate = now()->subWeeks($i)->startOfWeek();
                    $endDate = now()->subWeeks($i)->endOfWeek();
                    
                    $count = (clone $vaccinationQuery)
                        ->whereBetween('transactions.created_at', [$startDate, $endDate])
                        ->count();
                    
                    $vaccinationLabels[] = $startDate->format('M d');
                    $vaccinationCounts[] = $count;
                }
                break;

            case 'yearly':
                for($i = 4; $i >= 0; $i--) {
                    $year = now()->subYears($i)->year;
                    
                    $count = (clone $vaccinationQuery)
                        ->whereYear('transactions.created_at', $year)
                        ->count();
                    
                    $vaccinationLabels[] = $year;
                    $vaccinationCounts[] = $count;
                }
                break;

            default: // monthly
                for($i = 11; $i >= 0; $i--) {
                    $date = now()->subMonths($i);
                    
                    $count = (clone $vaccinationQuery)
                        ->whereYear('transactions.created_at', $date->year)
                        ->whereMonth('transactions.created_at', $date->month)
                        ->count();
                    
                    $vaccinationLabels[] = $date->format('M Y');
                    $vaccinationCounts[] = $count;
                }
        }

        // Get vaccination statistics by species
        $vaccinationsBySpecies = Species::withCount(['animals' => function ($query) {
            $query->whereHas('transactions', function ($q) {
                $q->where(function ($q) {
                    $q->whereNotNull('vaccine_id')
                        ->orWhereHas('transactionType', function ($q) {
                            $q->where('type_name', 'like', '%vaccination%')
                                ->orWhere('type_name', 'like', '%vaccine%');
                        });
                });
            });
        }])->get();

        // Get animal types distribution
        $animalTypes = Species::pluck('name')->toArray();
        $animalTypeCounts = Species::withCount('animals')->pluck('animals_count')->toArray();

        // Transaction status counts
        $pendingTransactions = Transaction::where('status', 0)->count();
        $completedTransactions = Transaction::where('status', 1)->count();
        $canceledTransactions = Transaction::where('status', 2)->count();

        // Get species for filter dropdown
        $species = Species::select('id', 'name')
            ->orderBy('name')
            ->get();

        return view('admin.dashboard', compact(
            'veterinarians',
            'technicians',
            'recentTransactions',
            'statuses',
            'totalOwners',
            'successfulTransactions',
            'totalAnimals',
            'pendingTransactions',
            'completedTransactions',
            'canceledTransactions',
            'newOwnersThisMonth',
            'transactionsThisMonth',
            'newAnimalsThisMonth',
            'totalVaccinations',
            'vaccinationsThisMonth',
            'vaccinationLabels',
            'vaccinationCounts',
            'animalTypes',
            'animalTypeCounts',
            'period',
            'vaccinationsBySpecies',
            'barangays',
            'barangayStats',
            'barangayFilter',
            'showUnvaccinated',
            'unvaccinatedAnimals',
            'species'
        ));
    }

    

   public function loadAnimalsList(Request $request)
{
    $query = Animal::with([
        'owner.user.address.barangay',
        'species',
        'breed',
        'transactions' => function ($query) {
            $query->with(['vet', 'technician', 'transactionSubtype', 'vaccine'])
                  ->latest();
        }
    ]);

    // Search filter
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhereHas('owner.user', function ($q) use ($search) {
                  $q->where('complete_name', 'like', "%{$search}%");
              });
        });
    }

    // Species filter
    if ($request->filled('species_id')) {
        $query->where('species_id', $request->species_id);
    }

    // Breed filter
    if ($request->filled('breed_id')) {
        $query->where('breed_id', $request->breed_id);
    }

    // Owner filter
    if ($request->filled('owner_id')) {
        $query->where('owner_id', $request->owner_id);
    }

    // Date range filter
    if ($request->filled('fromDate')) {
        $query->whereDate('created_at', '>=', $request->fromDate);
    }
    if ($request->filled('toDate')) {
        $query->whereDate('created_at', '<=', $request->toDate);
    }

    // Life Status filter
    if ($request->filled('life_status')) {
        if ($request->life_status === 'null') {
            $query->whereNull('isAlive');
        } else {
            $query->where('isAlive', (bool)$request->life_status);
        }
    }

    $animals = $query->latest()->paginate(10)->withQueryString();
    $species = Species::all();
    $breeds = Breed::all();
    $owners = Owner::with('user')->get();

    return view('admin.animals-table', compact('animals', 'species', 'breeds', 'owners'));
}

   
   public function loadTransactionsList()
   {
        return view('admin.transactions-table');
   } 


   public function loadVeterinariansList(Request $request)
   {
       $search = $request->input('search', '');  // Search input
       $gender = $request->input('gender', '');  // Filter by gender
       $category = $request->input('category', '');  // Filter by category
       $civil_status = $request->input('civil_status', '');  // Filter by civil status
       $barangay_id = $request->input('barangay', '');  // Filter by barangay
   
       // Fetch barangays and designations for the dropdown
       $barangays = Barangay::select('id', 'barangay_name')->orderBy('barangay_name')->get();
       $designations = Designation::select('designation_id', 'name')->orderBy('name')->get();
       $designation_id = request('designation'); // Assign the designation id from the request

       // Fetch veterinarians (users with role = 2) with necessary relationships and filters
       $veterinarians = User::with([
               'animals.species', // Load species for animals
               'animals.breed',   // Load breed for animals
               'transactions',    // Load transactions
               'address.barangay', // Load address with barangay
               'designation' // Load designation for veterinarian
           ])
           ->leftJoin('addresses', 'users.user_id', '=', 'addresses.user_id')
           ->leftJoin('barangays', 'addresses.barangay_id', '=', 'barangays.id')
           ->leftJoin('transactions', 'users.user_id', '=', 'transactions.vet_id')
           ->leftJoin('transaction_types', 'transactions.transaction_type_id', '=', 'transaction_types.id')
           ->leftJoin('designations', 'users.designation_id', '=', 'designations.designation_id')  // Join designations table
        
           // Filter: Only veterinarians (role = 2)
           ->where('users.role', 2)
   
           // Apply search filter
           ->where(function ($query) use ($search) {
               $query->where('users.complete_name', 'like', '%' . $search . '%')
                     ->orWhere('users.contact_no', 'like', '%' . $search . '%')
                     ->orWhere('addresses.street', 'like', '%' . $search . '%')
                     ->orWhere('barangays.barangay_name', 'like', '%' . $search . '%');
           })
   
           // Apply gender filter
           ->when($gender, function ($query) use ($gender) {
               return $query->where('users.gender', $gender);
           })
   
           // Apply category filter (specific to veterinarians)
           ->when($category, function ($query) use ($category) {
               return $query->where('users.category', $category);
           })
   
           // Apply civil status filter
           ->when($civil_status, function ($query) use ($civil_status) {
               return $query->where('users.civil_status', $civil_status);
           })
   
           // Apply barangay filter
           ->when($barangay_id, function ($query) use ($barangay_id) {
               return $query->where('barangays.id', $barangay_id);
           })
   
           // Apply date range filter for transactions
           ->when(request('fromDate'), function ($query) {
               return $query->whereDate('transactions.created_at', '>=', request('fromDate'));
           })
           ->when(request('toDate'), function ($query) {
               return $query->whereDate('transactions.created_at', '<=', request('toDate'));
           })
            // Apply designation filter
        ->when($designation_id, function ($query) use ($designation_id) {
            return $query->where('users.designation_id', $designation_id);
        })
   
           // Select necessary fields for veterinarians
           ->select(
               'users.user_id',
               'users.complete_name',
               'users.profile_image',
               'users.contact_no',
               'users.gender',
               'users.birth_date',
               'users.created_at',
               'addresses.street',
               'barangays.barangay_name',
               'designations.name as designation_name', // Include the designation name
               DB::raw('COUNT(transactions.transaction_id) as transaction_count'), // Count transactions for the veterinarian
               DB::raw('MAX(transactions.created_at) as transaction_created_at')
           )
   
           // Group by veterinarian's attributes
           ->groupBy(
               'users.user_id',
               'users.complete_name',
               'users.profile_image',
               'users.contact_no',
               'users.gender',
               'users.birth_date',
               'users.created_at', // Add this line

               'addresses.street',
               'barangays.barangay_name',
               'designations.name'
           )
   
           // Order by creation date
           ->orderBy('users.created_at', 'desc')
   
           // Pagination: 15 items per page
           ->paginate(15);
   
       // Return the view with veterinarians' data, filters, and designations
       return view('admin.vets-table', compact('veterinarians', 'search', 'gender', 'category', 'civil_status', 'barangays', 'barangay_id', 'designations'));
   }
   
   public function showVeterinarianProfile(Request $request, $user_id)
   {
       // Fetch query parameters for filters
       $search = $request->input('search');
       $status = $request->input('status');
       $transactionType = $request->input('transaction_type');
       $transactionSubtype = $request->input('transaction_subtype');
       $date = $request->input('date');
   
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
   
       if ($date) {
           $transactions->whereDate('created_at', $date);
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
       return view('admin.veterinarian-profile', compact(
           'veterinarian', 'transactions', 'technicians', 'transactionTypes', 
           'transactionSubtypes', 'transactionCount', 'animals', 'vet'
       ));
   }
   
   
   public function resetAnimalSpeciesAndBreeds()
   {
       // Set breed_id and species_id to null for all animals
       Animal::query()->update([
           'breed_id' => null,
           'species_id' => null,
       ]);
   
       return redirect()->back()->with('success', 'All animals\' species and breeds have been reset.');
   }

   public function loadUsersList(Request $request)
   {
       // Fetch filter inputs from the request
       $search = $request->input('search', '');
       $perPage = $request->input('perPage', 25); // Default to 25 items per page
       $gender = $request->input('gender', '');
       $role = $request->input('role', '');
       $status = $request->input('status', '');
       $barangay_id = $request->input('barangay_id', ''); // Barangay filter
       $fromDate = $request->input('fromDate', '');
       $toDate = $request->input('toDate', '');
       
       // Fetch Barangays for the filter dropdown
       $barangays = Barangay::all();
       
       // Fetch Owners for the filter dropdown (users with role = 1)
       $owners = User::where('role', 1)->get(); 
   
       // Build query with eager loading for related address and barangay
       $query = User::with(['address.barangay']) // Fix eager loading to load address.barangay
           ->when($role, function ($q) use ($role) {
               $q->where('role', $role);  // Filter by role
           })
           ->when($gender, function ($q) use ($gender) {
               $q->where('gender', $gender);  // Filter by gender
           })
           ->when($status, function ($q) use ($status) {
               $q->where('status', $status);  // Filter by status
           })
           ->when($barangay_id, function ($q) use ($barangay_id) {
               $q->whereHas('address', function ($q) use ($barangay_id) {
                   // Filter by barangay_id in the address
                   $q->where('barangay_id', $barangay_id);
               });
           })
           ->when($search, function ($q) use ($search) {
               $q->where(function ($q) use ($search) {
                   $q->where('complete_name', 'LIKE', "%{$search}%")
                     ->orWhere('email', 'LIKE', "%{$search}%")
                     ->orWhere('contact_no', 'LIKE', "%{$search}%");
               });
           })
           ->when($fromDate, function ($q) use ($fromDate) {
               $q->whereDate('created_at', '>=', $fromDate);  // Filter by creation date (from)
           })
           ->when($toDate, function ($q) use ($toDate) {
               $q->whereDate('created_at', '<=', $toDate);  // Filter by creation date (to)
           })
           ->orderBy('created_at', 'desc'); // Default ordering by creation date
       
       // Paginate results
       $users = $query->paginate($perPage);
       
       // Return the view with users, owners, and barangays data
       return view('admin.users-list', compact('users', 'barangays', 'owners'));
   }
   

   public function destroy(User $user)
   {
       try {
           // Check if the user is an owner
           if ($user->role === 1 && $user->owner) {
               // Delete related transactions
               $user->owner->transactions()->delete();
               
               // Delete related animals
               $user->owner->animals()->delete();
   
               // Delete the owner details
               $user->owner->delete();
           }
   
           // Delete the user
           $user->delete();
   
           return redirect()->back()->with('message', 'User, their owner details, transactions, and animals deleted successfully!');
       } catch (\Exception $e) {
           return redirect()->back()->with('error', 'An error occurred while deleting the user: ' . $e->getMessage());
       }
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
       return view('admin.animal-owners', compact(
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
   
   
   public function showAddAnimalForm($owner_id)
   {
       // Fetch necessary data
       $owner = User::where('role', 1)->whereHas('owner', function ($query) use ($owner_id) {
        $query->where('owner_id', $owner_id);
    })->first(); // Assuming one owner for a given owner_id
    
       $species = Species::all(); // Get all species
       $breeds = Breed::all(); // Get all breeds
       $vets = User::where('role', 2)->get(); // Get all veterinarians (role = 2)
       $transactionTypes = TransactionType::with('subtypes')->get(); // Fetch transaction types with their subtypes
       $technicians = VeterinaryTechnician::all(); // Fetch all veterinary technicians
       $vaccines = Vaccine::all(); // Fetch all veterinary technicians

   
       // Pass the data to the view
       return view('admin.add-animal', [
           'species' => $species,
           'breeds' => $breeds,
           'vets' => $vets,
           'transactionTypes' => $transactionTypes,
           'owner_id' => $owner_id,
           'technicians' => $technicians, // Include the technicians here
           'vaccines' => $vaccines, // Include the technicians here
           'owner' => $owner, // Get all species

           

       ]);
   }
   
   public function store(Request $request)
   {
       // If is_group is false, set group_count to 1 before validation
       if (!$request->is_group) {
           $request->merge(['group_count' => 1]);
       }
   
       // If is_group is true, set gender to "N/A"
       if ($request->is_group) {
           $request->merge(['gender' => 'N/A']);
       }
   
       // Set default isAlive status to 1 (Alive)
       $request->merge(['isAlive' => 1]);
   
       // Validate the form input
       $request->validate([
           'name' => 'required|string|max:255',
           'owner_id' => 'required|exists:owners,owner_id',
           'species_id' => 'required|exists:species,id',
           'breed_id' => 'required|exists:breeds,id',
           'color' => 'nullable|string|max:255',
           'birth_date' => ['nullable', 'date', 'before_or_equal:today'],
           'gender' => 'nullable|in:Male,Female,N/A',
           'medical_condition' => 'nullable|string|max:255',
           'photo_front' => 'nullable|image|max:2048',
           'photo_back' => 'nullable|image|max:2048',
           'photo_left_side' => 'nullable|image|max:2048',
           'photo_right_side' => 'nullable|image|max:2048',
           'is_group' => 'required|boolean',
           'group_count' => 'required|integer|min:1',
           'is_vaccinated' => 'required|in:0,1,2',
           'isAlive' => 'required|boolean', // Add validation for isAlive
       ]);
   
       // Prepare the data for the animal
       $data = $request->only([
           'name',
           'owner_id',
           'species_id',
           'breed_id',
           'color',
           'birth_date',
           'gender',
           'medical_condition',
           'is_group',
           'group_count',
           'is_vaccinated',
           'isAlive', // Include isAlive in the data
       ]);
   
       // Handle file uploads for photos
       foreach (['photo_front', 'photo_back', 'photo_left_side', 'photo_right_side'] as $photo) {
           if ($request->hasFile($photo)) {
               $filename = time() . '_' . $request->file($photo)->getClientOriginalName();
               $request->file($photo)->move(public_path('storage/animal_photos'), $filename);
               $data[$photo] = 'animal_photos/' . $filename;
           } else {
               $data[$photo] = null;
           }
       }
   
       try {
           // Save the animal record
           $animal = Animal::create($data);
   
           return redirect()->route('owners.profile-owner', ['owner_id' => $request->owner_id])
                            ->with('success', 'Animal added successfully.');
       } catch (\Exception $e) {
           // Handle any errors during the insertion
           return back()->withErrors(['error' => $e->getMessage()]);
       }
   }
   
   


public function getBreeds($species_id)
    {
        // Get the species by its ID
        $species = Species::find($species_id);
        
        // Check if species exists
        if (!$species) {
            return response()->json(['error' => 'Species not found'], 404);
        }

        // Get breeds related to the species
        $breeds = $species->breeds; // Using the defined relationship

        // Return breeds as JSON
        return response()->json([
            'breeds' => $breeds
        ]);
    }

    public function getTransactionSubtypes($transactionTypeId)
{
    // Ensure the TransactionType exists, then get the associated subtypes
    $transactionType = TransactionType::with('subtypes')->find($transactionTypeId);

    if ($transactionType) {
        return response()->json([
            'subtypes' => $transactionType->subtypes
        ]);
    }

    return response()->json([
        'subtypes' => []
    ]);
}


   public function loadAddUsers() 
   {
        return view('admin.add-users');
   }

   public function loadAddOwners()
   {
       $role = auth()->user()->role; // Assuming roles are stored in the 'users' table
       $barangays = Barangay::all(); // Load barangay options for the dropdown
   
       return view('admin.add-owners', [
           'role' => $role,
           'barangays' => $barangays,
       ]);
   }
   
   public function owner_edit($id)
   {
       $user = User::with(['address', 'owner'])->findOrFail($id);
       $barangays = Barangay::all();
       $categories = Category::all();
       return view('admin.owner-edit', compact('user', 'barangays', 'categories'));
   }

   public function owner_update(Request $request, $id)
   {
       // Validation rules
       $validationRules = [
           'complete_name' => 'required|string|max:100',
           'contact_no' => 'required|string|max:15',
           'gender' => 'required|string|max:10',
           'birth_date' => 'required|date',
           'status' => 'required|integer',
           'is_email_field' => 'required|boolean',
           'email' => [
               'required',
               'string',
               'max:255',
               function ($attribute, $value, $fail) use ($request, $id) {
                   if ($request->is_email_field == 1) {
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
                           ->where('user_id', '!=', $id)
                           ->exists()) {
                       $fail('This ' . ($request->is_email_field == 1 ? 'email' : 'username') . ' is already taken.');
                   }
               }
           ],
           'barangay_id' => 'required|exists:barangays,id',
           'street' => 'nullable|string|max:255',
           'profile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
           'selectedCategories' => 'required|array',
           'selectedCategories.*' => 'exists:categories,id',
           'civil_status' => 'required|string|max:50',
       ];

       // Validate the request
       $validated = $request->validate($validationRules);

       // Find the user and transaction
       $user = User::findOrFail($id);
       $transaction = Owner::where('user_id', $user->user_id)->first();

       // Handle profile image upload
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
       $userData = [
           'complete_name' => $validated['complete_name'],
           'role' => 1, // Always set to owner
           'contact_no' => $validated['contact_no'],
           'gender' => $validated['gender'],
           'birth_date' => $validated['birth_date'],
           'status' => $validated['status'],
           'is_email_field' => $validated['is_email_field'],
           'email' => $validated['email'], // Use for both email and username
       ];

       $user->update($userData);

       // Update address
       $user->address()->updateOrCreate(
           ['user_id' => $user->user_id],
           [
               'barangay_id' => $validated['barangay_id'],
               'street' => $validated['street'] ?? '',
           ]
       );

       // Update owner
       $user->owner()->updateOrCreate(
           ['user_id' => $user->user_id],
           [
               'civil_status' => $validated['civil_status'],
               'permit' => 1
           ]
       );

       // Handle categories with special rules
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
                   // This shouldn't happen with the radio button UI, but we handle it just in case
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

       // Sync the filtered categories
       $user->categories()->sync($filteredCategories);

       return redirect()->route('owners.profile-owner', ['owner_id' => $transaction->owner_id])
           ->with('message', 'Profile updated successfully.');
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
       return view('admin.animal-profile', compact(
           'animal', 
           'transactionTypes', 
           'transactionSubtypes', 
           'vets', 
           'transactions', 
           'vaccines', // Pass vaccines to the view
           'technicians' // Pass technicians to the view
       ));
   }
   
   
public function getSubtypes($transactionTypeId)
{
    // Get subtypes where the transaction_type_id matches the provided ID
    $subtypes = TransactionSubtype::where('transaction_type_id', $transactionTypeId)->get();
    
    // Check if any subtypes were found
    if ($subtypes->isEmpty()) {
        return response()->json(['subtypes' => []]);
    }

    // Return the relevant fields (id and subtype_name)
    return response()->json([
        'subtypes' => $subtypes->map(function($subtype) {
            return [
                'id' => $subtype->id,
                'subtype_name' => $subtype->subtype_name
            ];
        })
    ]);
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

    return view('admin.vet-edit', compact('veterinarian', 'designations', 'barangays'));
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

    return redirect()-> route('admin.veterinarian.profile', $veterinarian->user_id)->with('success', 'Veterinarian updated successfully');
}

public function showOwnerProfile(Request $request, $owner_id)
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
    return view('owner.owner-profile', compact('owner', 'animals', 'species', 'breeds', 'message'));
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

public function index(Request $request)
{
    $query = Document::query()
        ->where('user_id', auth()->id());

    if ($request->search) {
        $query->where(function($q) use ($request) {
            $q->where('title', 'like', '%' . $request->search . '%')
              ->orWhere('description', 'like', '%' . $request->search . '%');
        });
    }

    if ($request->category) {
        $query->where('category', $request->category);
    }

    $documents = $query->latest()->paginate(10);
    $categories = Document::distinct('category')->pluck('category');

    // Add file type helpers for each document
    $documents->each(function ($document) {
        $document->file_type = strtolower(pathinfo($document->file_name, PATHINFO_EXTENSION));
    });

    return view('admin.documents.index', [
        'documents' => $documents,
        'categories' => $categories
    ]);
}

public function storeDoc(Request $request)
{
    $request->validate([
        'file' => 'required|file|max:10240',
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'category' => 'required|string',
        'is_private' => 'boolean'
    ]);

    $file = $request->file('file');
    $path = $file->store('documents', 'public');

    Document::create([
        'title' => $request->title,
        'description' => $request->description,
        'file_path' => $path,
        'file_name' => $file->getClientOriginalName(),
        'file_type' => $file->getClientOriginalExtension(),
        'file_size' => $file->getSize(),
        'category' => $request->category,
        'user_id' => auth()->id(),
        'is_private' => $request->is_private ?? true
    ]);

    return redirect()->route('admin.documents.index')
        ->with('success', 'Document uploaded successfully.');
}

public function destroyDoc(Document $document)
{
    if ($document->user_id !== auth()->id()) {
        abort(403);
    }

    Storage::disk('public')->delete($document->file_path);
    $document->delete();

    return redirect()->route('admin.documents.index')
        ->with('success', 'Document deleted successfully.');
}

public function download(Document $document)
{
    if ($document->user_id !== auth()->id()) {
        abort(403);
    }

    return Storage::disk('public')->download(
        $document->file_path, 
        $document->file_name
    );
}

public function getBarangayStats(Request $request)
{
    try {
        \Log::info('Barangay Stats Request:', $request->all());

        // Get all species for the filter dropdown
        $species = \App\Models\Species::select('id', 'name')->orderBy('name')->get();

        $query = Barangay::select(
            'barangays.id',
            'barangays.barangay_name',
            DB::raw('COUNT(DISTINCT animals.animal_id) as total_animals'),
            DB::raw('COUNT(DISTINCT CASE 
                WHEN transactions.vaccine_id IS NOT NULL 
                OR transaction_types.type_name LIKE "%vaccination%" 
                OR transaction_types.type_name LIKE "%vaccine%" 
                THEN animals.animal_id END) as vaccinated_animals'),
            DB::raw('COUNT(DISTINCT CASE 
                WHEN animals.animal_id IS NOT NULL 
                AND NOT EXISTS (
                    SELECT 1 FROM transactions t 
                    JOIN transaction_types tt ON t.transaction_type_id = tt.id 
                    WHERE t.animal_id = animals.animal_id 
                    AND (t.vaccine_id IS NOT NULL 
                        OR tt.type_name LIKE "%vaccination%" 
                        OR tt.type_name LIKE "%vaccine%")
                )
                THEN animals.animal_id END) as unvaccinated_animals'),
            DB::raw('COUNT(DISTINCT CASE 
                WHEN transactions.created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                AND (transactions.vaccine_id IS NOT NULL 
                    OR transaction_types.type_name LIKE "%vaccination%" 
                    OR transaction_types.type_name LIKE "%vaccine%")
                THEN animals.animal_id END) as vaccinated_last_30_days'),
            DB::raw('COUNT(DISTINCT CASE 
                WHEN transactions.created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
                AND (transactions.vaccine_id IS NOT NULL 
                    OR transaction_types.type_name LIKE "%vaccination%" 
                    OR transaction_types.type_name LIKE "%vaccine%")
                THEN animals.animal_id END) as vaccinated_last_7_days'),
            DB::raw('GROUP_CONCAT(DISTINCT species.name) as animal_species')
        )
        ->leftJoin('addresses', 'barangays.id', '=', 'addresses.barangay_id')
        ->leftJoin('users', 'addresses.user_id', '=', 'users.user_id')
        ->leftJoin('owners', 'users.user_id', '=', 'owners.user_id')
        ->leftJoin('animals', 'owners.owner_id', '=', 'animals.owner_id')
        ->leftJoin('transactions', 'animals.animal_id', '=', 'transactions.animal_id')
        ->leftJoin('transaction_types', 'transactions.transaction_type_id', '=', 'transaction_types.id')
        ->leftJoin('species', 'animals.species_id', '=', 'species.id');

        // Apply filters
        if ($request->filled('barangay')) {
            $query->where('barangays.barangay_name', $request->input('barangay'));
        }

        if ($request->filled('dateRange') && $request->input('dateRange') !== 'all') {
            $days = (int)$request->input('dateRange');
            $query->where('transactions.created_at', '>=', now()->subDays($days));
        }

        if ($request->filled('species')) {
            $query->where('species.id', $request->input('species'));
        }

        if ($request->filled('status')) {
            if ($request->input('status') === 'vaccinated') {
                $query->having('vaccinated_animals', '>', 0);
            } elseif ($request->input('status') === 'unvaccinated') {
                $query->having('unvaccinated_animals', '>', 0);
            }
        }

        $barangayStats = $query->groupBy('barangays.id', 'barangays.barangay_name')
            ->get()
            ->map(function ($barangay) use ($request) {
                // Calculate vaccination rate
                $barangay->vaccination_rate = $barangay->total_animals > 0
                    ? round(($barangay->vaccinated_animals / $barangay->total_animals) * 100, 1)
                    : 0;

                // Process species
                $barangay->animal_species_array = array_filter(explode(',', $barangay->animal_species));

                // Get vaccine counts for this barangay
                try {
                    $vaccineQuery = DB::table('vaccines')
                        ->select(
                            'vaccines.vaccine_name',
                            DB::raw('COUNT(DISTINCT animals.animal_id) as animal_count')
                        )
                        ->join('transactions', 'vaccines.id', '=', 'transactions.vaccine_id')
                        ->join('animals', 'transactions.animal_id', '=', 'animals.animal_id')
                        ->join('owners', 'animals.owner_id', '=', 'owners.owner_id')
                        ->join('users', 'owners.user_id', '=', 'users.user_id')
                        ->join('addresses', 'users.user_id', '=', 'addresses.user_id')
                        ->where('addresses.barangay_id', $barangay->id)
                        ->whereNotNull('transactions.vaccine_id');

                    // Apply date range filter to vaccine query
                    if ($request->filled('dateRange') && $request->input('dateRange') !== 'all') {
                        $days = (int)$request->input('dateRange');
                        $vaccineQuery->where('transactions.created_at', '>=', now()->subDays($days));
                    }

                    // Apply species filter to vaccine query
                    if ($request->filled('species')) {
                        $vaccineQuery->where('animals.species_id', $request->input('species'));
                    }

                    $barangay->vaccines_used = $vaccineQuery
                        ->groupBy('vaccines.id', 'vaccines.vaccine_name')
                        ->get()
                        ->mapWithKeys(function ($item) {
                            return [$item->vaccine_name => $item->animal_count];
                        })
                        ->toArray();
                } catch (\Exception $e) {
                    \Log::error('Error in vaccine query: ' . $e->getMessage());
                    $barangay->vaccines_used = [];
                }

                return $barangay;
            });

        if ($request->ajax()) {
            $view = view('admin.partials.barangay-stats', compact('barangayStats', 'species'))->render();
            return response($view)->header('Content-Type', 'text/html');
        }

        return view('admin.partials.barangay-stats', compact('barangayStats', 'species'));

    } catch (\Exception $e) {
        \Log::error('Error in getBarangayStats: ' . $e->getMessage());
        \Log::error('Stack trace: ' . $e->getTraceAsString());
        
        if ($request->ajax()) {
            return response()->json([
                'error' => 'Failed to load barangay statistics',
                'message' => $e->getMessage()
            ], 500);
        }
        
        throw $e;
    }
}
}
