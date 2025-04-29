<x-app-layout>
    <div class="container mx-auto px-4 py-6 bg-gray-50 rounded-lg shadow-lg">
        <!-- Profile Section -->
        <div class="flex items-center space-x-6 mb-8 bg-white p-6 rounded-lg shadow-md hover:shadow-xl transition duration-300 ease-in-out">
            <img class="w-36 h-36 object-cover rounded-full border-4 border-green-500 shadow-lg hover:scale-105 transition-transform duration-300" 
            src="{{ $veterinarian->profile_image ? Storage::url($veterinarian->profile_image) : asset('assets/default-avatar.png') }}" 
            alt="Profile Image">            
            <div>
                <h2 class="text-2xl font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-user-md text-blue-500 mr-2"></i>{{ $veterinarian->complete_name }}'s Profile
                </h2>
                <p class="text-lg text-gray-600">
                    <strong>Designation:</strong>
                    @if($veterinarian->designation)
                        {{ $veterinarian->designation->name }}
                    @else
                        No designation
                    @endif
                </p>
                                <p class="text-sm text-gray-500"><i class="fas fa-phone-alt text-green-500"></i> Contact: {{ $veterinarian->contact_no ?? 'N/A' }}</p>
                <p class="text-sm text-gray-500 mt-2"><strong>Transactions Handled:</strong> {{ $transactionCount }}</p>
        
                <!-- Edit Profile Button -->
              
            </div>
        </div>
        

        <hr class="my-6 border-t-2 border-gray-200">

        <!-- Transactions Section -->
        <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-clipboard-list text-blue-500 mr-2"></i>Transactions
        </h2>

     <!-- Search and Filters Section -->

        <div class="container mx-auto px-4 py-6 bg-gray-50 rounded-lg shadow-lg">
            <!-- Search and Filters Section -->
            <div class="flex items-center justify-between mb-6 bg-white p-4 rounded-lg shadow-md">
                <form action="{{ route('rec.veterinarian.profile', $veterinarian->user_id) }}" method="GET" id="filtersForm" class="flex items-center space-x-4">
                    <!-- Search Input -->
                    <div class="flex items-center">
                        <input 
                            type="text" 
                            name="search" 
                            value="{{ request('search') }}" 
                            placeholder="Search by Animal/Owner Name" 
                            class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                            id="searchInput" />
                    </div>
                    <!-- Status Filter -->
                    <div class="flex items-center">
                        <select 
                            name="status" 
                            class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                            onchange="document.getElementById('filtersForm').submit()">
                            <option value="">All Statuses</option>
                            <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Pending</option>
                            <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Completed</option>
                            <option value="2" {{ request('status') == '2' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
    
                    <!-- Transaction Type Filter -->
                    <div class="flex items-center">
                        <select 
                            name="transaction_type" 
                            class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                            onchange="document.getElementById('filtersForm').submit()">
                            <option value="">All Transaction Types</option>
                            @foreach ($transactionTypes as $type)
                                <option value="{{ $type->id }}" {{ request('transaction_type') == $type->id ? 'selected' : '' }}>
                                    {{ $type->type_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
    
                    <!-- Transaction Subtype Filter -->
                    <div class="flex items-center">
                        <select 
                            name="transaction_subtype" 
                            class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                            onchange="document.getElementById('filtersForm').submit()">
                            <option value="">All Subtypes</option>
                            @foreach ($transactionSubtypes as $subtype)
                                <option value="{{ $subtype->id }}" {{ request('transaction_subtype') == $subtype->id ? 'selected' : '' }}>
                                    {{ $subtype->subtype_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
    
                    <!-- Date Filter -->
<form id="filtersForm" method="GET" action="{{ url()->current() }}">
    <div class="flex items-center">
        <label for="start_date" class="mr-2 text-gray-700">From:</label>
        <input 
            type="date" 
            name="start_date" 
            value="{{ request('start_date') }}" 
            class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
            onchange="document.getElementById('filtersForm').submit()" />
    </div>

    <span class="mx-4">TO:</span>

    <div class="flex items-center">
        <label for="end_date" class="mr-2 text-gray-700"></label>
        <input 
            type="date" 
            name="end_date" 
            value="{{ request('end_date') }}" 
            class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
            onchange="document.getElementById('filtersForm').submit()" />
    </div>
</form>

                                        <!-- Transaction Counter -->

    
                    <!-- Reset Button -->
                    <div class="flex items-center">
                        <a href="{{ route('rec.veterinarian.profile', $veterinarian->user_id) }}" class="px-6 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition duration-200">
                            Reset
                        </a>
                    </div>
                </form>
            </div>
    
            <!-- Transactions Table -->
            <div class="overflow-x-auto bg-white p-4 rounded-lg shadow-md">
                <table class="min-w-full table-auto">
                    <thead>
                        <tr class="text-sm font-semibold text-gray-700 bg-gray-100 border-b">
                            <th class="px-4 py-2 text-left">Animal Owner Name</th>
                            <th class="px-4 py-2 text-left">Animal Name</th>
                            <th class="px-4 py-2 text-left">Transaction</th>
                            <th class="px-4 py-2 text-left">Animal Species</th>
                            <th class="px-4 py-2 text-left">Animal Breed</th>
                            <th class="px-4 py-2 text-left">Transaction Date</th>
                            <th class="px-4 py-2 text-left">Technician</th>
                            <th class="px-4 py-2 text-left">Transaction Details</th>

                            <th class="px-4 py-2 text-left">Status</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transactions as $transaction)
                        @php
                            $animal = $transaction->animal; // Assuming the 'animal' relationship is defined in the Transaction model
                        @endphp
                    
                        @if ($animal)
                            <tr class="text-sm border-b hover:bg-blue-50 transition duration-300">
                                <td class="px-6 py-3 text-start">
    <!-- Owner Profile Image -->
    <a href="{{ route('rec.profile-owner', ['owner_id' => $animal->owner->owner_id]) }}" class="text-blue-500 hover:text-blue-700 font-bold">
        <img src="{{ $animal->owner->user->profile_image ? Storage::url($animal->owner->user->profile_image) : asset('assets/default-avatar.png') }}" 
             alt="Owner Image" class="w-8 h-8 object-cover rounded-full border-2 border-gray-300 mr-2">
        {{ $animal->owner->user->complete_name ?? 'Unknown Owner' }}
    </a><br>
</td>

<td class="px-6 py-3 text-start">
    <!-- Animal Photo Front -->
    <a href="{{ route('rec.profile', ['animal_id' => $animal->animal_id]) }}" class="text-sm font-medium text-blue-600 dark:text-blue-400 hover:underline">
        <img src="{{ $animal->photo_front ? Storage::url($animal->photo_front) : asset('assets/default-avatar.png') }}" 
             alt="Animal Photo" class="w-8 h-8 object-cover rounded-full border-2 border-gray-300 mr-2">
        <strong>{{ $animal->name ?? 'Unknown Animal' }}</strong>
    </a>
</td>


                                <td class="px-4 py-2 text-gray-700">
                                    {{ $transaction->transactionType->type_name ?? 'Unknown' }}
                                    @if($transaction->transactionSubtype)
                                        - {{ $transaction->transactionSubtype->subtype_name ?? 'Unknown' }}
                                    @endif
                                </td>
                               
                                                                <td class="px-4 py-2 text-gray-700">{{ $transaction->animal->species->name ?? 'Unknown Species' }}</td>
                                <td class="px-4 py-2 text-gray-700">{{ $transaction->animal->breed->name ?? 'Unknown Breed' }}</td>
                                <td class="px-4 py-2 text-gray-700">{{ $transaction->created_at->format('F j, Y') }}</td>

                               <!-- Technician Information -->
<td class="px-4 py-2 text-gray-700">
    {{ $transaction->technician ? $transaction->technician->full_name : 'No Technician Assigned' }}
</td>

<!-- Transaction Details -->
<td class="px-4 py-2 text-gray-700">
    {{ $transaction->details ?? 'No Details Provided' }}
</td>

<!-- Transaction Status -->
<td class="px-4 py-2 text-gray-700">
    @if ($transaction->status == 0)
        Pending
    @elseif ($transaction->status == 1)
        Completed
    @elseif ($transaction->status == 2)
        Cancelled
    @else
        Unknown Status
    @endif
</td>

                                
                            </tr>
                        @else
                          
                        @endif
                    @endforeach
                    
                    </tbody>
                </table>
            </div>
    
            <!-- Pagination Links -->
            <div class="mt-4">
                {{ $transactions->links() }}
            </div>
        </div>

    

    <hr class="my-6 border-t-2 border-gray-200">
</div>

<!-- Add Font Awesome CDN -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
<script>
    function confirmStatusChange(event, transactionId) {
        const selectedOption = event.target.value;

        let message = '';
        if (selectedOption == 1) {
            message = "Are you sure you want to mark this transaction as Completed?";
        } else if (selectedOption == 2) {
            message = "Are you sure you want to cancel this transaction?";
        }

        if (message) {
            if (!confirm(message)) {
                // If the user cancels, reset the dropdown to its previous value
                event.target.selectedIndex = [...event.target.options].findIndex(option => option.defaultSelected);
            } else {
                // If confirmed, submit the form
                document.getElementById(`statusForm-${transactionId}`).submit();
            }
        }
    }

    function confirmTechnicianChange(event, transactionId) {
        if (confirm('Are you sure you want to update the technician for this transaction?')) {
            document.getElementById(`technicianForm-${transactionId}`).submit();
        } else {
            // Revert selection if user cancels
            event.target.value = event.target.dataset.previousValue || "";
        }
    }

    // Store the previous value of the dropdown on focus for technician changes
    document.querySelectorAll('select[name="technician_id"]').forEach((dropdown) => {
        dropdown.addEventListener('focus', function () {
            this.dataset.previousValue = this.value;
        });
    });
</script>

</x-app-layout>