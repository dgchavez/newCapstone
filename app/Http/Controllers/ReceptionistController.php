<?php

namespace App\Http\Controllers;

use App\Models\VeterinaryTechnician; // Import the Species model
use App\Models\Breed; // Import the Species model
use App\Models\Vaccine; // Import the Species model
use App\Models\Technician; // Import the Species model
use App\Models\Designation;
use Illuminate\Support\Facades\Storage;

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



class ReceptionistController extends Controller
{
    public function loadReceptionistDashboard(Request $request)
    {
        // Retrieve filter and search inputs
        $search = $request->input('search');
        $statusFilter = $request->input('status');
        $veterinarianFilter = $request->input('veterinarian');
        $technicianFilter = $request->input('technician');

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
        $lastWeekTransactions = Transaction::whereBetween('created_at', [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()])
            ->count();

        return view('receptionist.dashboard', compact(
            'veterinarians',
            'technicians',
            'recentTransactions',
            'statuses',
            'totalOwners',
            'successfulTransactions',
            'totalAnimals',
            'lastWeekTransactions'
        ));
    }

    
    
    
    
private function getRecentActivities()
{
    // Fetch recent transactions
    $recentTransactions = Transaction::latest()
        ->take(5)
        ->get()
        ->map(function ($transaction) {
            $animalName = $transaction->animal->name ?? 'Unknown';
            $speciesName = $transaction->animal->species->name ?? 'Unknown';
            return [
                'description' => "Handled transaction for $animalName ($speciesName).",
                'time_ago' => $transaction->created_at->diffForHumans(),
            ];
        });

    // Fetch recently added clients
    $recentClients = Owner::latest()
        ->take(5)
        ->get()
        ->map(function ($owner) {
            $ownerName = $owner->user->complete_name ?? 'Unknown';
            return [
                'description' => "Added a new client: $ownerName.",
                'time_ago' => $owner->created_at->diffForHumans(),
            ];
        });

    // Fetch recently registered animals
    $recentAnimals = Animal::latest()
        ->take(5)
        ->get()
        ->map(function ($animal) {
            $animalName = $animal->name ?? 'Unknown';
            $speciesName = $animal->species->name ?? 'Unknown';
            return [
                'description' => "Registered a new pet: $animalName ($speciesName).",
                'time_ago' => $animal->created_at->diffForHumans(),
            ];
        });

    // Combine and sort activities by creation time
    $recentActivities = collect()
        ->merge($recentTransactions)
        ->merge($recentClients)
        ->merge($recentAnimals)
        ->sortByDesc(fn ($activity) => $activity['time_ago']);

    return $recentActivities->values()->all();
}

}
