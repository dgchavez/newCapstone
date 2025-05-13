<div class="bg-white p-8 rounded-lg shadow-lg max-w-4xl mx-auto">
    <div class="flex items-center justify-between border-b pb-4 mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Animal Travel Certificate</h1>
            <p class="text-gray-600">Certificate No: TC-{{ $animal->animal_id }}-{{ date('Ymd') }}</p>
        </div>
        <div>{!! $qrCode !!}</div>
    </div>
    
    <div class="grid grid-cols-2 gap-8 mb-8">
        <div>
            <h2 class="text-xl font-semibold mb-4 text-gray-700">Animal Information</h2>
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span class="font-medium text-gray-600">Name:</span>
                    <span class="text-gray-800">{{ $animal->name }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="font-medium text-gray-600">Species:</span>
                    <span class="text-gray-800">{{ $animal->species->name }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="font-medium text-gray-600">Breed:</span>
                    <span class="text-gray-800">{{ $animal->breed->name }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="font-medium text-gray-600">Gender:</span>
                    <span class="text-gray-800">{{ $animal->gender ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="font-medium text-gray-600">Color:</span>
                    <span class="text-gray-800">{{ $animal->color ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="font-medium text-gray-600">Birth Date:</span>
                    <span class="text-gray-800">{{ $animal->birth_date ? $animal->birth_date->format('M d, Y') : 'N/A' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="font-medium text-gray-600">Animal ID:</span>
                    <span class="text-gray-800">{{ $animal->animal_id }}</span>
                </div>
            </div>
        </div>
        
        <div>
            <h2 class="text-xl font-semibold mb-4 text-gray-700">Owner Information</h2>
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span class="font-medium text-gray-600">Owner Name:</span>
                    <span class="text-gray-800">{{ $animal->owner->user->complete_name }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="font-medium text-gray-600">Contact:</span>
                    <span class="text-gray-800">{{ $animal->owner->contact_number ?? 'N/A' }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="mb-8">
        <h2 class="text-xl font-semibold mb-4 text-gray-700">Vaccination Record</h2>
        @if($animal->transactions->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="py-2 px-4 text-left">Date</th>
                            <th class="py-2 px-4 text-left">Vaccine</th>
                            <th class="py-2 px-4 text-left">Veterinarian</th>
                            <th class="py-2 px-4 text-left">Validity</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($animal->transactions as $transaction)
                            <tr class="border-b">
                                <td class="py-2 px-4">{{ $transaction->created_at->format('M d, Y') }}</td>
                                <td class="py-2 px-4">{{ $transaction->vaccine->vaccine_name ?? 'General Vaccine' }}</td>
                                <td class="py-2 px-4">{{ $transaction->vet->complete_name ?? 'N/A' }}</td>
                                <td class="py-2 px-4">
                                    @if($transaction->vaccine && $transaction->vaccine->validity_period)
                                        {{ $transaction->created_at->addDays($transaction->vaccine->validity_period)->format('M d, Y') }}
                                    @else
                                        1 Year
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-600 italic">No vaccination records found.</p>
        @endif
    </div>

    <div class="mb-8">
        <h2 class="text-xl font-semibold mb-4 text-gray-700">Travel Information</h2>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <p class="font-medium text-gray-600">Valid From:</p>
                <p class="text-gray-800">{{ date('F d, Y') }}</p>
            </div>
            <div>
                <p class="font-medium text-gray-600">Valid Until:</p>
                <p class="text-gray-800">{{ date('F d, Y', strtotime('+30 days')) }}</p>
            </div>
        </div>
    </div>

    <div class="mb-8">
        <h2 class="text-xl font-semibold mb-4 text-gray-700">Certification</h2>
        <p class="text-gray-700 mb-4">
            This is to certify that the animal described above has been examined and found to be in good health, free from clinical signs of infectious or contagious disease, and fit for travel. The animal has been vaccinated against rabies and other relevant diseases as recorded above.
        </p>
    </div>

    <div class="flex justify-between items-center mt-12">
        <div>
            <div class="border-t-2 border-gray-400 w-48 pt-2">
                <p class="font-semibold text-gray-700">Veterinarian Signature</p>
            </div>
        </div>
        <div>
            <div class="border-t-2 border-gray-400 w-48 pt-2">
                <p class="font-semibold text-gray-700">Official Seal</p>
            </div>
        </div>
    </div>

    <div class="mt-8 pt-6 border-t text-gray-500 text-sm">
        <p>This certificate is valid for 30 days from the date of issue for domestic travel only.</p>
        <p>Certificate generated on: {{ date('F d, Y h:i A') }}</p>
    </div>

    <div class="mt-6 text-center">
        <a href="{{ route('animal.travel-certificate.pdf', $animal->animal_id) }}" 
           class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition-colors duration-200 shadow-sm font-medium">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="roun