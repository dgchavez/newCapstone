<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Animal;
use App\Models\Barangay;
use App\Models\Category;
use App\Models\Designation;
use App\Models\Owner;
use App\Models\Report;
use App\Models\Species;
use App\Models\Transaction;
use App\Models\TransactionType;
use App\Models\User;
use App\Models\Vaccine;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\TransactionSubtype;
use PDF;


class VetReportController extends Controller
{
    public function index()
    {
        // Get data needed for report forms
        $recentReports = Report::where('user_id', auth()->user()->user_id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $transactionTypes = TransactionType::with('subtypes')->get();
        $species = Species::with('breeds')->get();
        $vaccines = Vaccine::all();
        $barangays = Barangay::all();
        $categories = Category::all();
        $designations = Designation::all();

        return view('receptionist.reportEngine', compact(
            'recentReports', 
            'transactionTypes', 
            'species', 
            'vaccines', 
            'barangays', 
            'categories', 
            'designations'
        ));
    }
    
    public function transactionReportView(Request $request)
    {
        $filters = $request->only([
            'transaction_type_id',
            'transaction_subtype_id',
            'status',
            'date_from',
            'date_to'
        ]);
    
        $dateFrom = \Carbon\Carbon::parse($filters['date_from'] ?? now()->subYear());
        $dateTo = \Carbon\Carbon::parse($filters['date_to'] ?? now());
    
        $transactions = Transaction::with('transactionType', 'transactionSubtype', 'owner', 'animal.species', 'animal.breed')
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->when($request->filled('transaction_type_id'), fn ($query) => $query->where('transaction_type_id', $filters['transaction_type_id']))
            ->when($request->filled('transaction_subtype_id'), fn ($query) => $query->where('transaction_subtype_id', $filters['transaction_subtype_id']))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $filters['status']))
            ->get();
    
        $summary = $this->generateSummaryStatistics($transactions);
    
        $report = Report::create([
            'user_id' => auth()->user()->user_id,
            'report_type' => 'transactions',
            'date_from' => $dateFrom->toDateString(),
            'date_to' => $dateTo->toDateString(),
            'parameters' => $filters,
            'generated_by' => auth()->user()->user_id,
            'status' => 'completed',
            'file_path' => '',
        ]);
    
        $pdf = PDF::loadView('reports.pdf.receptionist.transactions', [
            'veterinarian' => auth()->user(),
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'filters' => $filters,
            'summary' => $summary,
            'transactions' => $transactions,
        ]);
    
        $fileName = 'transaction_report_' . now()->format('Y_m_d_H_i_s') . '.pdf';
        $filePath = 'reports/' . $fileName;
    
        if (!Storage::disk('public')->put($filePath, $pdf->output())) {
            throw new \Exception('Failed to save the PDF file.');
        }
    
        $report->update(['file_path' => $filePath]);
    
        // ✅ Redirect to download route instead of returning the PDF directly
        return redirect()->route('reports.downloadfromRec', $report->id);
    }
    
    public function download($id)
    {
        $report = Report::findOrFail($id);
    
        if (Storage::disk('public')->exists($report->file_path)) {
            return response()->download(storage_path("app/public/{$report->file_path}"));
        }
    
        return back()->with('error', 'The requested report file does not exist.');
    }
    
    // New method to delete the report
    public function delete($id)
    {
        // Find the report by ID
        $report = Report::findOrFail($id);

        // Check if the file exists in storage and delete it
        if (Storage::disk('public')->exists($report->file_path)) {
            Storage::disk('public')->delete($report->file_path);
        }

        // Delete the report record from the database
        $report->delete();

        // Redirect back with a success message
        return back()->with('success', 'Report deleted successfully.');
    }

    private function generateSummaryStatistics($transactions)
    {
        $summary = [
            'total' => $transactions->count(),
            'byStatus' => [
                0 => 0,  // Pending
                1 => 0,  // Completed
                2 => 0,  // Cancelled
            ],
            'byType' => [],
        ];
    
        foreach ($transactions as $transaction) {
            $status = $transaction->status;
            $typeName = $transaction->transactionType->name ?? 'Unknown';
    
            // Count by status using the correct numeric values (0, 1, 2)
            if (isset($summary['byStatus'][$status])) {
                $summary['byStatus'][$status]++;
            }
    
            // Count by type
            if (!isset($summary['byType'][$typeName])) {
                $summary['byType'][$typeName] = [
                    'count' => 0,
                    'completed' => 0,
                    'pending' => 0,
                    'cancelled' => 0,
                ];
            }
    
            $summary['byType'][$typeName]['count']++;
    
            // Count status-specific by type
            if (isset($summary['byType'][$typeName][$status])) {
                $summary['byType'][$typeName][$status]++;
            }
        }
    
        return $summary;
    }
    
    
    public function preview(Request $request)
    {
        try {
            // Determine which type of preview to generate based on the request type
            $previewType = $request->preview_type;
            
            switch ($previewType) {
                case 'transactions':
                    return $this->previewTransactions($request);
                case 'owners':
                    return $this->previewOwners($request);
                case 'animals':
                    return $this->previewAnimals($request);
                case 'vaccinations':
                    return $this->previewVaccinations($request);
                case 'users':
                    return $this->previewUsers($request);
                default:
                    return response()->json(['error' => 'Invalid preview type'], 400);
            }
        } catch (\Exception $e) {
            \Log::error('Report preview error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to generate preview: ' . $e->getMessage()
            ], 500);
        }
    }

    private function previewTransactions(Request $request)
    {
        $query = Transaction::query()
            ->whereBetween('created_at', [
                Carbon::parse($request->date_from)->startOfDay(),
                Carbon::parse($request->date_to)->endOfDay()
            ]);

        // Apply filters
        if ($request->transaction_type_id) {
            $query->where('transaction_type_id', $request->transaction_type_id);
        }
        
        if ($request->transaction_subtype_id) {
            $query->where('transaction_subtype_id', $request->transaction_subtype_id);
        }
        
        if ($request->status !== '' && $request->status !== null) {
            $query->where('status', $request->status);
        }

        // For receptionist specific report
        if ($request->receptionist_id) {
            $query->where('receptionist_id', $request->receptionist_id);
        }

        // Clone query for counts to avoid modifying the original query
        $totalQuery = clone $query;
        $completedQuery = clone $query;
        $pendingQuery = clone $query;
        $cancelledQuery = clone $query;

        // Get summary
        $summary = [
            'total' => $totalQuery->count(),
            'completed' => $completedQuery->where('status', 1)->count(),
            'pending' => $pendingQuery->where('status', 0)->count(),
            'cancelled' => $cancelledQuery->where('status', 2)->count()
        ];

        // Get sample data (latest 5 transactions)
        $samples = $query->latest()
            ->take(5)
            ->with(['transactionType', 'transactionSubtype', 'owner.user', 'animal'])
            ->get()
            ->map(function ($transaction) {
                return [
                    'created_at' => $transaction->created_at,
                    'type' => $transaction->transactionType->type_name . 
                        ($transaction->transactionSubtype ? ' - ' . $transaction->transactionSubtype->subtype_name : ''),
                    'status' => $transaction->status === 0 ? 'Pending' : 
                               ($transaction->status === 1 ? 'Completed' : 'Cancelled'),
                    'owner' => optional($transaction->owner)->user->complete_name ?? 'N/A',
                    'animal' => optional($transaction->animal)->name ?? 'N/A'
                ];
            });

        return response()->json([
            'summary' => $summary,
            'samples' => $samples
        ]);
    }
    

    private function previewOwners(Request $request)
    {
        $query = Owner::query()
            ->with('user', 'user.address.barangay')
            ->whereBetween('created_at', [
                Carbon::parse($request->date_from)->startOfDay(),
                Carbon::parse($request->date_to)->endOfDay()
            ]);

        if ($request->owner_category) {
            $query->where('category', $request->owner_category);
        }

        if ($request->barangay_id) {
            $query->whereHas('user.address', function ($q) use ($request) {
                $q->where('barangay_id', $request->barangay_id);
            });
        }

        // Execute the query once and store the results
        $ownersData = $query->get();
        
        // Create summary data
        $summary = [
            'total' => $ownersData->count(),
            'by_category' => $ownersData->groupBy('category')
                ->map(function ($item) {
                    return count($item);
                }),
            'by_barangay' => $ownersData->groupBy(function($owner) {
                return optional(optional($owner->user)->address)->barangay->barangay_name ?? 'Unknown';
            })
            ->map(function ($item) {
                return count($item);
            })
        ];

        // Get sample data
        $samples = $ownersData->take(5)
            ->map(function ($owner) {
                return [
                    'created_at' => $owner->created_at,
                    'name' => optional($owner->user)->complete_name ?? 'N/A',
                    'category' => $owner->category,
                    'barangay' => optional(optional($owner->user)->address)->barangay->barangay_name ?? 'N/A'
                ];
            });

        return response()->json([
            'summary' => $summary,
            'samples' => $samples
        ]);
    }

    private function previewAnimals(Request $request)
    {
        try {
            $filters = $request->only([
                'date_from',
                'date_to',
                'species_id',
                'breed_id',
                'is_vaccinated',
                'barangay_id',
            ]);

            $query = Animal::query()
                ->with([
                    'owner.user.address.barangay',
                    'species:id,name',
                    'breed:id,name'
                ])
                ->select('animals.*')
                ->whereBetween('created_at', [
                    Carbon::parse($request->date_from)->startOfDay(),
                    Carbon::parse($request->date_to)->endOfDay()
                ]);

            // Apply filters
            if (!empty($filters['species_id'])) {
                $query->where('species_id', $filters['species_id']);
            }

            if (!empty($filters['breed_id'])) {
                $query->where('breed_id', $filters['breed_id']);
            }

            // Modified vaccination status filter
            if (isset($filters['is_vaccinated']) && $filters['is_vaccinated'] !== '') {
                if ($filters['is_vaccinated'] == '2') {
                    // No Vaccination Required: not 0 and not 1
                    $query->whereNotIn('is_vaccinated', [0, 1]);
                } else {
                    $query->where('is_vaccinated', $filters['is_vaccinated']);
                }
            }

            if (!empty($filters['barangay_id'])) {
                $query->whereHas('owner.user.address', function ($q) use ($filters) {
                    $q->where('barangay_id', $filters['barangay_id']);
                });
            }

            $animals = $query->get();

            // Modified summary to include "not required" category
            $summary = [
                'total' => $animals->count(),
                'vaccinated' => $animals->where('is_vaccinated', 1)->count(),
                'not_vaccinated' => $animals->where('is_vaccinated', 0)->count(),
                'not_required' => $animals->whereNotIn('is_vaccinated', [0, 1])->count(),
                'by_species' => $animals->groupBy('species.name')
                    ->map(function ($group) {
                        return [
                            'count' => $group->count(),
                            'vaccinated' => $group->where('is_vaccinated', 1)->count(),
                            'not_vaccinated' => $group->where('is_vaccinated', 0)->count(),
                            'not_required' => $group->whereNotIn('is_vaccinated', [0, 1])->count(),
                        ];
                    })
            ];

            // Modified samples to handle vaccination status
            $samples = $animals->take(5)->map(function ($animal) {
                $barangayName = null;
                if ($animal->owner && 
                    $animal->owner->user && 
                    $animal->owner->user->address && 
                    $animal->owner->user->address->barangay) {
                    $barangayName = $animal->owner->user->address->barangay->barangay_name;
                }

                // Modified vaccination status logic
                $vaccinationStatus = 'Unknown';
                if ($animal->is_vaccinated === 1) {
                    $vaccinationStatus = 'Yes';
                } elseif ($animal->is_vaccinated === 0) {
                    $vaccinationStatus = 'No';
                } else {
                    $vaccinationStatus = 'Not Required';
                }

                return [
                    'name' => $animal->name,
                    'species' => $animal->species->name ?? 'Unknown',
                    'breed' => $animal->breed->name ?? 'Unknown',
                    'barangay' => $barangayName ?? 'Unknown',
                    'is_vaccinated' => $vaccinationStatus,
                    'created_at' => $animal->created_at
                ];
            });

            return response()->json([
                'summary' => $summary,
                'samples' => $samples
            ]);

        } catch (\Exception $e) {
            \Log::error('Animal Preview failed: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to generate preview: ' . $e->getMessage()
            ], 500);
        }
    }

    private function previewVaccinations(Request $request)
    {
        $query = Transaction::query()
            ->whereNotNull('vaccine_id')
            ->with('animal.species', 'animal.breed', 'vaccine', 'owner.user.address.barangay')
            ->whereBetween('created_at', [
                Carbon::parse($request->date_from)->startOfDay(),
                Carbon::parse($request->date_to)->endOfDay()
            ]);

        if ($request->vaccine_id) {
            $query->where('vaccine_id', $request->vaccine_id);
        }

        if ($request->species_id) {
            $query->whereHas('animal', function ($q) use ($request) {
                $q->where('species_id', $request->species_id);
            });
        }

        if ($request->status !== '' && $request->status !== null) {
            $query->where('status', $request->status);
        }

        // Add barangay filter
        if ($request->filled('barangay_id')) {
            $query->whereHas('owner.user.address', function($q) use ($request) {
                $q->where('barangay_id', $request->barangay_id);
            });
        }

        // Clone query for counts
        $totalQuery = clone $query;
        $completedQuery = clone $query;
        $pendingQuery = clone $query;

        // Get summary
        $summary = [
            'total' => $totalQuery->count(),
            'completed' => $completedQuery->where('status', 1)->count(),
            'pending' => $pendingQuery->where('status', 0)->count()
        ];

        // Get sample data
        $samples = $query->take(5)->get()->map(function ($transaction) {
            return [
                'created_at' => $transaction->created_at,
                'animal' => $transaction->animal->name ?? 'N/A',
                'vaccine' => $transaction->vaccine->vaccine_name ?? 'N/A',
                'barangay' => $transaction->owner->user->address->barangay->barangay_name ?? 'Unknown',
                'status' => $transaction->status
            ];
        });

        return response()->json([
            'summary' => $summary,
            'samples' => $samples
        ]);
    }

    private function previewUsers(Request $request)
    {
        try {
            // Query only veterinarians (role = 2)
            $query = User::query()
                ->where('role', 2) // Only veterinarians
                ->with('designation')
                ->whereBetween('created_at', [
                    Carbon::parse($request->date_from)->startOfDay(),
                    Carbon::parse($request->date_to)->endOfDay()
                ]);

            // Apply designation filter if provided
            if ($request->designation_id) {
                $query->where('designation_id', $request->designation_id);
            }

            // Get the veterinarians
            $vets = $query->get();
            
            // Create summary data
            $summary = [
                'total' => $vets->count(),
                'by_designation' => $vets->groupBy(function($vet) {
                    return $vet->designation ? $vet->designation->name : 'No Designation';
                })
                ->map(function ($group) {
                    return count($group);
                })
            ];

            // Prepare sample data
            $samples = $vets->take(5)
                ->map(function ($vet) {
                    return [
                        'created_at' => $vet->created_at,
                        'name' => $vet->complete_name,
                        'designation' => $vet->designation ? $vet->designation->name : 'No Designation',
                        'email' => $vet->email
                    ];
                });

            return response()->json([
                'summary' => $summary,
                'samples' => $samples
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in previewUsers: ' . $e->getMessage());
            throw $e; // Re-throw to be caught by the main preview method
        }
    }

    public function generateRecTransactionReport(Request $request)
    {
        try {
            $validated = $request->validate([
                'date_from' => 'required|date',
                'date_to' => 'required|date|after_or_equal:date_from',
                'transaction_type_id' => 'nullable|exists:transaction_types,id',
                'transaction_subtype_id' => 'nullable|exists:transaction_subtypes,id',
                'status' => 'nullable|in:0,1,2',
                'format' => 'nullable|in:pdf,excel'
            ]);

            // Get transactions
            $query = Transaction::query()
                ->whereBetween('created_at', [
                    Carbon::parse($validated['date_from']),
                    Carbon::parse($validated['date_to'])->endOfDay()
                ])
                ->with([
                    'owner.user',
                    'animal.species',
                    'animal.breed',
                    'vet',
                    'receptionist',
                    'transactionType',
                    'transactionSubtype'
                ]);

            // Filter by transaction type if selected
            if (!empty($validated['transaction_type_id'])) {
                $query->where('transaction_type_id', $validated['transaction_type_id']);
            }

            // Filter by transaction subtype if selected
            if (!empty($validated['transaction_subtype_id'])) {
                $query->where('transaction_subtype_id', $validated['transaction_subtype_id']);
            }

            // Filter by status if selected
            if (isset($validated['status'])) {
                $query->where('status', $validated['status']);
            }

            $transactions = $query->get();

            $data = [
                'transactions' => $transactions,
                'dateFrom' => Carbon::parse($validated['date_from']),
                'dateTo' => Carbon::parse($validated['date_to']),
                'summary' => [
                    'total' => $transactions->count(),
                    'byStatus' => [
                        'pending' => $transactions->where('status', 0)->count(),
                        'completed' => $transactions->where('status', 1)->count(),
                        'cancelled' => $transactions->where('status', 2)->count(),
                    ],
                    'byType' => $transactions->groupBy('transactionType.type_name')
                        ->map(function ($group) {
                            return [
                                'count' => $group->count(),
                                'completed' => $group->where('status', 1)->count(),
                                'pending' => $group->where('status', 0)->count(),
                                'cancelled' => $group->where('status', 2)->count(),
                                'bySubtype' => $group->groupBy('transactionSubtype.subtype_name')
                                    ->map(function ($subgroup) {
                                        return [
                                            'count' => $subgroup->count(),
                                            'completed' => $subgroup->where('status', 1)->count(),
                                            'pending' => $subgroup->where('status', 0)->count(),
                                            'cancelled' => $subgroup->where('status', 2)->count(),
                                        ];
                                    })
                            ];
                        }),
                ],
                'filters' => [
                    'type' => !empty($validated['transaction_type_id']) ? 
                        TransactionType::find($validated['transaction_type_id'])->type_name : 'All Types',
                    'subtype' => !empty($validated['transaction_subtype_id']) ? 
                        TransactionType::find($validated['transaction_subtype_id'])->subtype_name : 'All Subtypes',
                    'status' => isset($validated['status']) ? 
                        ['Pending', 'Completed', 'Cancelled'][$validated['status']] : 'All Statuses'
                ],
                'receptionist' => auth()->user()
            ];

            // Generate PDF
            $pdf = PDF::loadView('reports.pdf.receptionist.transactions', $data);
            
            // Create filename
            $fileName = "transaction-report-" . now()->format('Y-m-d-His') . '.pdf';
            $filePath = "reports/{$fileName}";
            
            // Save report record first
            $report = Report::create([
                'user_id' => auth()->user()->user_id,
                'report_type' => 'transactions',
                'date_from' => $validated['date_from'],
                'date_to' => $validated['date_to'],
                'parameters' => $validated,
                'generated_by' => auth()->user()->user_id,
                'status' => 'completed',
                'file_path' => $filePath // Save file path immediately
            ]);

            // Save the PDF file
            Storage::disk('public')->put($filePath, $pdf->output());

            // Redirect to the download route
            return redirect()->route('reports.download', $report);

        } catch (\Exception $e) {
            \Log::error('Transaction Report generation failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to generate report: ' . $e->getMessage());
        }
    }

    public function generateOwnerReport(Request $request)
    {
        // Optional filters from request
        $filters = $request->only([
            'barangay_id',
            'date_from',
            'date_to',
        ]);
        
        // Default to last year to now if dates are not provided
        $dateFrom = \Carbon\Carbon::parse($filters['date_from'] ?? now()->subYear());
        $dateTo = \Carbon\Carbon::parse($filters['date_to'] ?? now());
        
        // Query owners
        $query = Owner::query()
            ->with(['user', 'user.address.barangay', 'animals', 'transactions'])
            ->whereBetween('created_at', [$dateFrom->startOfDay(), $dateTo->endOfDay()]);
        
        // Apply barangay filter if provided
        if (!empty($filters['barangay_id'])) {
            $query->whereHas('user.address', function ($q) use ($filters) {
                $q->where('barangay_id', $filters['barangay_id']);
            });
        }
        
        // Get owners based on the filters
        $owners = $query->get();
        
        // Summary data
        $summary = [
            'total' => $owners->count(),
            'byBarangay' => $owners->groupBy('user.address.barangay.barangay_name')->map(function ($group) {
                return [
                    'count' => $group->count(),
                    'animalCount' => $group->flatMap(fn($owner) => $owner->animals)->count(),
                    'transactionCount' => $group->flatMap(fn($owner) => $owner->transactions)->count(),
                ];
            }),
        ];
    
        // Get the receptionist's information
        $receptionist = auth()->user(); // Assuming the receptionist is the authenticated user
        
        // Prepare data for PDF
        $pdf = PDF::loadView('reports.pdf.receptionist.owners', [
            'owners' => $owners,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'summary' => $summary,
            'filters' => [
                'barangay' => !empty($filters['barangay_id'])
                    ? Barangay::find($filters['barangay_id'])->barangay_name
                    : 'All Barangays',
            ],
            'receptionist' => $receptionist, // Pass the receptionist variable
        ]);
        
        // Save PDF
        $fileName = 'owners_report_' . now()->format('Y_m_d_H_i_s') . '.pdf';
        $filePath = 'reports/' . $fileName;
        
        // Store the PDF file
        if (!Storage::disk('public')->put($filePath, $pdf->output())) {
            throw new \Exception('Failed to save the PDF file.');
        }
        
        // Create report record
        $report = Report::create([
            'user_id' => auth()->user()->user_id,
            'report_type' => 'owners',
            'date_from' => $dateFrom->toDateString(),
            'date_to' => $dateTo->toDateString(),
            'parameters' => $filters,
            'generated_by' => auth()->user()->user_id,
            'status' => 'completed',
            'file_path' => $filePath,
        ]);
        
        $fileName = 'owners_report_' . now()->format('Y_m_d_H_i_s') . '.pdf';
        $filePath = 'reports/' . $fileName;
    
        if (!Storage::disk('public')->put($filePath, $pdf->output())) {
            throw new \Exception('Failed to save the PDF file.');
        }
    
        $report->update(['file_path' => $filePath]);
    
        // ✅ Redirect to download route instead of returning the PDF directly
        return redirect()->route('reports.downloadfromRec', $report->id);
    }
    
    public function generateAnimalReport(Request $request)
    {
        try {
            $filters = $request->only([
                'date_from',
                'date_to',
                'species_id',
                'breed_id',
                'is_vaccinated',
                'barangay_id',
            ]);

            $dateFrom = \Carbon\Carbon::parse($filters['date_from'] ?? now()->subYear());
            $dateTo = \Carbon\Carbon::parse($filters['date_to'] ?? now());

            // Get barangay name if barangay_id is provided
            $barangay_name = 'All Barangays';
            if (!empty($filters['barangay_id'])) {
                $barangay = \App\Models\Barangay::find($filters['barangay_id']);
                if ($barangay) {
                    $barangay_name = $barangay->barangay_name;
                }
            }

            $query = Animal::query()
                ->with([
                    'owner.user.address.barangay',
                    'species:id,name',
                    'breed:id,name'
                ])
                ->select('animals.*')
                ->whereBetween('created_at', [$dateFrom->startOfDay(), $dateTo->endOfDay()]);

            // Apply filters
            if (!empty($filters['species_id'])) {
                $query->where('species_id', $filters['species_id']);
            }

            if (!empty($filters['breed_id'])) {
                $query->where('breed_id', $filters['breed_id']);
            }

            if (isset($filters['is_vaccinated'])) {
                if ($filters['is_vaccinated'] === '') {
                    // Show all
                } elseif ($filters['is_vaccinated'] === 'not_required') {
                    $query->whereNull('is_vaccinated');
                } else {
                    $query->where('is_vaccinated', $filters['is_vaccinated']);
                }
            }

            if (!empty($filters['barangay_id'])) {
                $query->whereHas('owner.user.address', function ($q) use ($filters) {
                    $q->where('barangay_id', $filters['barangay_id']);
                });
            }

            $animals = $query->get();

            $data = [
                'animals' => $animals,
                'dateFrom' => $dateFrom,
                'dateTo' => $dateTo,
                'barangay_name' => $barangay_name,
                'summary' => [
                    'total' => $animals->count(),
                    'vaccinated' => $animals->where('is_vaccinated', 1)->count(),
                    'nonVaccinated' => $animals->where('is_vaccinated', 0)->count(),
                    'notRequired' => $animals->whereNull('is_vaccinated')->count(),
                    'bySpecies' => $animals->groupBy('species.name')->map(function ($group) {
                        return [
                            'count' => $group->count(),
                            'vaccinated' => $group->where('is_vaccinated', 1)->count(),
                            'nonVaccinated' => $group->where('is_vaccinated', 0)->count(),
                            'notRequired' => $group->whereNull('is_vaccinated')->count(),
                        ];
                    }),
                    'byBreed' => $animals->groupBy('breed.name')->map(function ($group) {
                        return [
                            'count' => $group->count(),
                            'vaccinated' => $group->where('is_vaccinated', 1)->count(),
                            'nonVaccinated' => $group->where('is_vaccinated', 0)->count(),
                            'notRequired' => $group->whereNull('is_vaccinated')->count(),
                        ];
                    }),
                ],
                'samples' => $animals->take(5)->map(function ($animal) {
                    return [
                        'name' => $animal->name,
                        'species' => $animal->species->name ?? 'Unknown',
                        'breed' => $animal->breed->name ?? 'Unknown',
                        'barangay' => $animal->owner?->user?->address?->barangay?->barangay_name ?? 'Unknown',
                        'is_vaccinated' => $animal->is_vaccinated === null ? 'Not Required' : 
                                         ($animal->is_vaccinated ? 'Yes' : 'No'),
                    ];
                }),
            ];
            
            $fileName = 'animals_report_' . now()->format('Y_m_d_H_i_s') . '.pdf';
            $filePath = 'reports/' . $fileName;

            $pdf = PDF::loadView('reports.pdf.receptionist.animals', $data);

            if (!Storage::disk('public')->put($filePath, $pdf->output())) {
                throw new \Exception('Failed to save the PDF file.');
            }

            $report = Report::create([
                'user_id' => auth()->user()->user_id,
                'report_type' => 'animals',
                'date_from' => $dateFrom->toDateString(),
                'date_to' => $dateTo->toDateString(),
                'parameters' => $filters,
                'generated_by' => auth()->user()->user_id,
                'status' => 'completed',
                'file_path' => $filePath
            ]);

            return redirect()->route('reports.downloadfromRec', $report->id);

        } catch (\Exception $e) {
            \Log::error('Animal Report generation failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to generate report: ' . $e->getMessage());
        }
    }

    public function generateVaccinationReport(Request $request)
    {
        try {
            // Validate required date fields
            $request->validate([
                'date_from' => 'required|date',
                'date_to' => 'required|date'
            ]);

            // Parse dates
            $dateFrom = Carbon::parse($request->date_from)->startOfDay();
            $dateTo = Carbon::parse($request->date_to)->endOfDay();

            // Get barangay name if barangay_id is provided
            $barangay_name = 'All Barangays';
            if ($request->filled('barangay_id')) {
                $barangay = \App\Models\Barangay::find($request->barangay_id);
                if ($barangay) {
                    $barangay_name = $barangay->barangay_name;
                }
            }

            // Base query with relationships
            $query = Transaction::with([
                'owner.user.address.barangay',  // Added address and barangay relationship
                'animal.species',
                'vaccine'
            ])
            ->whereNotNull('vaccine_id')
            ->whereBetween('created_at', [$dateFrom, $dateTo]);

            // Optional filters
            if ($request->filled('vaccine_id')) {
                $query->where('vaccine_id', $request->vaccine_id);
            }

            if ($request->filled('species_id')) {
                $query->whereHas('animal', function($q) use ($request) {
                    $q->where('species_id', $request->species_id);
                });
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            // Add barangay filter
            if ($request->filled('barangay_id')) {
                $query->whereHas('owner.user.address', function($q) use ($request) {
                    $q->where('barangay_id', $request->barangay_id);
                });
            }

            // Get transactions
            $transactions = $query->get();

            // Transform data for the view
            $vaccinations = $transactions->map(function ($transaction) {
                return [
                    'created_at' => $transaction->created_at,
                    'animal' => $transaction->animal->name ?? 'N/A',
                    'species' => $transaction->animal->species->name ?? 'N/A',
                    'owner' => $transaction->owner->user->complete_name ?? 'N/A',
                    'vaccine' => $transaction->vaccine->vaccine_name ?? 'N/A',
                    'status' => $transaction->status,
                    'barangay' => $transaction->owner->user->address->barangay->barangay_name ?? 'Unknown'
                ];
            });

            // Prepare summary data
            $byVaccine = [];
            $bySpecies = [];
            $byBarangay = []; // Added barangay summary

            foreach ($transactions as $transaction) {
                $vaccineName = $transaction->vaccine->vaccine_name ?? 'Unknown';
                $speciesName = $transaction->animal->species->name ?? 'Unknown';
                $barangayName = $transaction->owner->user->address->barangay->barangay_name ?? 'Unknown';

                // Initialize if not exists
                if (!isset($byVaccine[$vaccineName])) {
                    $byVaccine[$vaccineName] = ['count' => 0, 'completed' => 0, 'pending' => 0];
                }
                if (!isset($bySpecies[$speciesName])) {
                    $bySpecies[$speciesName] = ['count' => 0, 'completed' => 0, 'pending' => 0];
                }
                if (!isset($byBarangay[$barangayName])) {
                    $byBarangay[$barangayName] = ['count' => 0, 'completed' => 0, 'pending' => 0];
                }

                // Update counts
                $byVaccine[$vaccineName]['count']++;
                $bySpecies[$speciesName]['count']++;
                $byBarangay[$barangayName]['count']++;

                if ($transaction->status == 1) {
                    $byVaccine[$vaccineName]['completed']++;
                    $bySpecies[$speciesName]['completed']++;
                    $byBarangay[$barangayName]['completed']++;
                } elseif ($transaction->status == 0) {
                    $byVaccine[$vaccineName]['pending']++;
                    $bySpecies[$speciesName]['pending']++;
                    $byBarangay[$barangayName]['pending']++;
                }
            }

            $summary = [
                'total' => $transactions->count(),
                'completed' => $transactions->where('status', 1)->count(),
                'pending' => $transactions->where('status', 0)->count(),
                'byVaccine' => $byVaccine,
                'bySpecies' => $bySpecies,
                'byBarangay' => $byBarangay // Added barangay summary
            ];

            // Prepare view data
            $data = [
                'vaccinations' => $vaccinations,
                'dateFrom' => $dateFrom,
                'dateTo' => $dateTo,
                'summary' => $summary,
                'barangay_name' => $barangay_name
            ];

            // Generate PDF
            $pdf = PDF::loadView('reports.pdf.receptionist.vaccinations', $data);
            
            // Set paper size and orientation
            $pdf->setPaper('a4', 'portrait');

            // Generate filename
            $fileName = 'vaccination_report_' . now()->format('Y-m-d_His') . '.pdf';
            $filePath = 'reports/' . $fileName;

            // Save to storage
            Storage::disk('public')->put($filePath, $pdf->output());

            // Get current user ID
            $userId = auth()->id();

            // Create report record
            $report = Report::create([
                'user_id' => $userId,
                'report_type' => 'vaccinations',
                'date_from' => $dateFrom,
                'date_to' => $dateTo,
                'parameters' => json_encode([
                    'vaccine_id' => $request->vaccine_id,
                    'species_id' => $request->species_id,
                    'status' => $request->status,
                    'barangay_id' => $request->barangay_id
                ]),
                'file_path' => $filePath,
                'status' => 'completed',
                'generated_by' => $userId
            ]);

            // Redirect to download route
            return redirect()->route('reports.downloadfromRec', $report->id);

        } catch (\Exception $e) {
            \Log::error('Vaccination Report Generation Error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate report: ' . $e->getMessage()
            ], 500);
        }
    }
}