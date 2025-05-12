<div class="bg-white p-6 rounded-lg shadow-lg max-w-4xl mx-auto">
    <div class="flex items-center justify-center mb-4">
        <!-- Front side of card -->
        <div class="border border-gray-300 rounded-md overflow-hidden" style="width: 600px;">
            <!-- Card content -->
            <div class="relative">
                <!-- Top header with logos and thin lines -->
                <div class="border-t border-b border-green-600 py-1 px-2 flex justify-between items-center">
                    <div class="flex space-x-2">
                        <img src="{{ asset('assets/who.png') }}" alt="WHO" class="h-6 w-6">
                        <img src="{{ asset('assets/doh.png') }}" alt="DOH" class="h-6 w-6">
                        <img src="{{ asset('assets/buk.png') }}" alt="Animal Welfare" class="h-6 w-6">
                        <img src="{{ asset('assets/val.png') }}" alt="Bukidnon" class="h-6 w-6">
                        <img src="{{ asset('assets/logo2.png') }}" alt="Vet" class="h-6 w-6">
                    </div>
                    <div class="flex items-center">
                        <img src="{{ asset('assets/val.png') }}" alt="Valencia City Logo" class="h-12 w-12">
                        <div class="text-right ml-2">
                            <p class="text-xs font-medium">Republic of the Philippines</p>
                            <p class="text-xs">Province of Bukidnon</p>
                            <p class="text-xs font-medium">CITY OF VALENCIA</p>
                        </div>
                    </div>
                </div>
                
                <!-- Main content -->
                <div class="flex">
                    <!-- Left side (Anti-rabies info) -->
                    <div class="w-1/2 bg-white p-3">
                        <div class="text-center mb-2">
                            <p class="text-green-700 font-bold text-sm">AS PER REPUBLIC ACT 9482</p>
                            <p class="text-green-700 font-bold text-sm">(ANTI-RABIES ACT OF 2007)</p>
                        </div>
                        
                        <div class="mb-4">
                            <p class="text-gray-700 font-medium">Iro ko, <span class="text-green-700 font-bold">HIKTAN</span> ko</p>
                            <p class="text-gray-700 font-medium">Paak sa iro ko, <span class="text-green-700 font-bold">GASTOS</span> ko</p>
                            <p class="text-gray-700 font-medium">Hugaw sa iro ko, <span class="text-green-700 font-bold">LIMPYO</span> ko</p>
                        </div>
                        
                        <div class="flex justify-center space-x-4 mb-4">
                            <img src="{{ asset('assets/walkingdog.png') }}" alt="Walking dog" class="h-12">
                            <img src="{{ asset('assets/runningdog.png') }}" alt="Running with dog" class="h-12">
                            <img src="{{ asset('assets/cleaningdog.jpg') }}" alt="Clean up" class="h-12">
                        </div>
                        
                        <div class="text-center mb-3">
                            <p class="text-green-700 font-bold text-xs">AS PER REPUBLIC ACT 9482</p>
                            <p class="text-green-700 text-xs">MULTA SA IRESPONSABLE NGA MGA TAG-IYA SA GINAHING IRO UG IRING</p>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-2 text-xs">
                            <div class="flex items-start">
                                <img src="{{ asset('assets/doggo.png') }}" alt="Fine" class="h-8 mr-1">
                                <p class="text-gray-700">
                                    <span class="font-bold">2,000.00</span><br>
                                    Dili magparehistro o magpabakuna sa ilang binuhi!
                                </p>
                            </div>
                            <div class="flex items-start">
                                <img src="{{ asset('assets/doggo1.png') }}" alt="Fine" class="h-8 mr-1">
                                <p class="text-gray-700">
                                    <span class="font-bold">25,000.00</span><br>
                                    Madumili nga ipasubos sa obserbasyon sa ilang iro o iring ug dili mo-abaga sa gasto sa pagpanambal sa tawo nga napakan sa ilang iro.
                                </p>
                            </div>
                            <div class="flex items-start">
                                <img src="{{ asset('assets/doggo3.png') }}" alt="Fine" class="h-8 mr-1">
                                <p class="text-gray-700">
                                    <span class="font-bold">10,000.00</span><br>
                                    Nagdumili sa pagobserbar sa ilang iro pagkahuman sa makaplak.
                                </p>
                            </div>
                            <div class="flex items-start">
                                <img src="{{ asset('assets/doggo4.png') }}" alt="Fine" class="h-8 mr-1">
                                <p class="text-gray-700">
                                    <span class="font-bold">500.00 sa matag akaldente</span><br>
                                    Motumili sa pagbutang ug higot sa ilang mga iro kung sila dad-on sa gawas sa balay.
                                </p>
                            </div>
                        </div>
                        
                        <div class="text-center mt-3">
                            <p class="text-emerald-800 font-bold text-xs">"MAHIMUNG RESPONSABLE SA ATONG BINUHING HAYOP!"</p>
                        </div>
                        
                        <div class="flex justify-center items-center space-x-2 mt-2 text-xs">
                            <div class="flex items-center">
                                <img src="{{ asset('assets/call.jpg') }}" alt="Phone" class="h-4 w-4 mr-1">
                                <p class="text-[9px]">088-828-4273</p>
                            </div>
                            <div class="flex items-center">
                                <img src="{{ asset('assets/gmail.png') }}" alt="Email" class="h-4 w-4 mr-1">
                                <p class="text-[9px] truncate max-w-[100px]">valenciacityvet@gmail.com</p>
                            </div>
                            <div class="flex items-center">
                                <img src="{{ asset('assets/facebook.png') }}" alt="Facebook" class="h-4 w-4 mr-1">
                                <p class="text-[9px]">Vet Valencia</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Right side (Registration info) -->
                    <div class="w-1/2 p-3 bg-white border-l border-gray-200">
                        <div class="text-center mb-2">
                            <p class="text-green-700 text-sm font-semibold">CITY VETERINARIAN'S OFFICE</p>
                            <p class="text-gray-600 text-xs italic">Purok 2 Pinahilan, Valencia City, Bukidnon</p>
                        </div>
                        
                        <div class="text-center mb-3">
                            <p class="text-emerald-800 font-bold">CERTIFICATE OF REGISTRATION</p>
                            <p class="text-gray-700">Reg. No.: <span class="underline">{{ sprintf('%06d', $animal->animal_id) }}</span></p>
                        </div>
                        
                        <div class="border-t border-b border-green-300 py-2 mb-3">
                            <p class="text-center text-green-800 font-semibold">{{ $animal->name }}</p>
                            <p class="text-center text-green-600 text-xs">PET'S NAME</p>
                        </div>
                        
                        <div class="space-y-2">
                            <div class="flex items-center">
                                <p class="w-1/3 text-green-700 font-medium">BIRTH DATE:</p>
                                <p class="w-2/3 border-b border-gray-300">{{ $animal->birth_date ? $animal->birth_date->format('m/d/Y') : '_________' }}</p>
                            </div>
                            
                            <div class="flex items-center">
                                <p class="w-1/3 text-green-700 font-medium">AGE:</p>
                                <p class="w-2/3 border-b border-gray-300">{{ $animal->birth_date ? $animal->birth_date->age : '_________' }}</p>
                            </div>
                            
                            <div class="flex items-center">
                                <p class="w-1/3 text-green-700 font-medium">SEX:</p>
                                <p class="w-2/3 border-b border-gray-300">{{ $animal->gender ?? '_________' }}</p>
                            </div>
                            
                            <div class="flex">
                                <p class="w-1/4 text-green-700 font-medium">SPECIES:</p>
                                <p class="w-1/4 border-b border-gray-300">{{ $animal->species->name ?? '_________' }}</p>
                                <p class="w-1/4 text-green-700 font-medium pl-2">BREED:</p>
                                <p class="w-1/4 border-b border-gray-300">{{ $animal->breed->name ?? '_________' }}</p>
                            </div>
                            
                            <div class="flex items-center">
                                <p class="w-1/3 text-green-700 font-medium">COLOR:</p>
                                <p class="w-2/3 border-b border-gray-300">{{ $animal->color ?? '_________' }}</p>
                            </div>
                            
                            <div class="flex items-center">
                                <p class="w-1/4 text-green-700 font-medium">OWNER:</p>
                                <p class="w-3/4 border-b border-gray-300">{{ $animal->owner->user->complete_name ?? '_________' }}</p>
                            </div>
                            
                            <div class="flex items-start">
                                <p class="w-1/4 text-green-700 font-medium">ADDRESS:</p>
                                <p class="w-3/4 border-b border-gray-300">
                                    @php
                                        $ownerAddress = 'N/A';
                                        
                                        if ($animal->owner && $animal->owner->user && $animal->owner->user->address) {
                                            $address = $animal->owner->user->address;
                                            $barangay = $address->barangay ?? null;
                                            
                                            if ($address && $barangay) {
                                                $street = $address->street ?? '';
                                                $barangayName = $barangay->barangay_name ?? '';
                                                $ownerAddress = $street . ', ' . $barangayName . ', Valencia City, Bukidnon';
                                            }
                                        }
                                        
                                        echo $ownerAddress;
                                    @endphp
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Back side of card -->
    <div class="flex items-center justify-center mt-8">
        <div class="border border-gray-300 rounded-md overflow-hidden" style="width: 600px;">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-white border-b border-green-300">
                        <th class="border border-gray-300 p-2 w-1/5 text-green-800 text-sm">DATE</th>
                        <th class="border border-gray-300 p-2 w-1/5 text-green-800 text-sm">VACCINE USED</th>
                        <th class="border border-gray-300 p-2 w-1/5 text-green-800 text-sm">BOOSTER DATE</th>
                        <th class="border border-gray-300 p-2 w-1/5 text-green-800 text-sm">VETERINARIAN</th>
                        <th class="border border-gray-300 p-2 w-1/5 text-green-800 text-sm">DETAILS</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        // Fetch only vaccination-related transactions for THIS specific animal
                        $vaccinationRecords = $animal->transactions()
                            ->where('animal_id', $animal->animal_id) // Ensure it's only for this animal
                            ->where(function($query) {
                                $query->whereHas('transactionType', function($subQuery) {
                                    $subQuery->where('type_name', 'like', '%vaccination%')
                                        ->orWhere('type_name', 'like', '%vaccine%');
                                })
                                ->orWhereHas('transactionSubtype', function($subQuery) {
                                    $subQuery->where('subtype_name', 'like', '%vaccination%')
                                        ->orWhere('subtype_name', 'like', '%vaccine%');
                                })
                                ->orWhereNotNull('vaccine_id');
                            })
                            ->where('status', 1) // Only completed transactions
                            ->with(['vaccine', 'vet', 'transactionType', 'transactionSubtype'])
                            ->orderBy('created_at', 'desc')
                            ->get();
                    @endphp
                    
                    @forelse($vaccinationRecords as $record)
                        <tr class="{{ $loop->even ? 'bg-gray-50' : 'bg-white' }}">
                            <td class="border border-gray-300 p-2 text-xs">{{ $record->created_at->format('m/d/Y') }}</td>
                            <td class="border border-gray-300 p-2 text-xs">
                                @if($record->vaccine)
                                    {{ $record->vaccine->vaccine_name ?? 'N/A' }}
                                @elseif($record->transactionSubtype)
                                    {{ $record->transactionSubtype->subtype_name ?? 'N/A' }}
                                @else
                                    {{ $record->transactionType->type_name ?? 'General Vaccine' }}
                                @endif
                            </td>
                            <td class="border border-gray-300 p-2 text-xs">
                                @if($record->vaccine && $record->vaccine->validity_period)
                                    {{ $record->created_at->addDays($record->vaccine->validity_period)->format('m/d/Y') }}
                                @elseif($record->vaccine)
                                    {{ $record->created_at->addYear()->format('m/d/Y') }}
                                @else
                                    {{ $record->created_at->addYear()->format('m/d/Y') }}
                                @endif
                            </td>
                            <td class="border border-gray-300 p-2 text-xs">
                                {{ $record->vet->complete_name ?? 'N/A' }}
                            </td>
                            <td class="border border-gray-300 p-2 text-xs">
                                {{ $record->details ?? 'N/A' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="border border-gray-300 p-2 text-xs text-center text-gray-500">
                                No vaccination records found
                            </td>
                        </tr>
                    @endforelse
                    
                    @php
                        $remainingRows = max(0, 10 - count($vaccinationRecords));
                    @endphp
                    
                    @for($i = 0; $i < $remainingRows; $i++)
                        <tr class="{{ $i % 2 == 0 ? 'bg-gray-50' : 'bg-white' }}">
                            <td class="border border-gray-300 p-2" style="height: 25px;"></td>
                            <td class="border border-gray-300 p-2"></td>
                            <td class="border border-gray-300 p-2"></td>
                            <td class="border border-gray-300 p-2"></td>
                            <td class="border border-gray-300 p-2"></td>
                        </tr>
                    @endfor
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6 text-center">
        <a href="{{ route('animal.vaccination-card.pdf', $animal->animal_id) }}" 
           class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors duration-200 shadow-sm font-medium">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Download PDF
        </a>
    </div>
</div> 