<div class="bg-white p-8 rounded-lg shadow-lg max-w-4xl mx-auto">
    <div class="flex items-center justify-between mb-4">
        <div>
            <img src="{{ asset('assets/logo2.png') }}" alt="City Seal" class="h-16 w-16 mr-2">
        </div>
        <div class="text-center mx-auto">
            <h2 class="text-lg font-bold text-gray-800">Republic of the Philippines</h2>
            <h3 class="text-base font-medium text-gray-700">Province of Bukidnon</h3>
            <h3 class="text-base font-medium text-gray-700">City of Valencia</h3>
            <p class="text-xs text-gray-600">City Veterinarians Office</p>
        </div>
        <div>
            <img src="{{ asset('assets/val.png') }}" alt="Secondary Logo" class="h-16 w-16">
        </div>
    </div>
    
    <div class="text-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900 uppercase">Veterinary Health Certificate</h1>
    </div>

    <div class="border border-gray-300 p-4 mb-6">
        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <p class="text-sm"><span class="font-semibold">No.:</span> {{ sprintf('%04d', $animal->animal_id) }}</p>
                <p class="text-sm"><span class="font-semibold">Date:</span> {{ date('F d, Y') }}</p>
                <p class="text-sm"><span class="font-semibold">Station:</span> {{ 'City Veterinary Office' }}</p>
            </div>
            <div>
                <p class="text-sm"><span class="font-semibold">Health Condition:</span> {{ 'APPARENTLY HEALTHY' }}</p>
                <p class="text-sm"><span class="font-semibold">Purpose:</span> {{ 'GENERAL HEALTH CHECK' }}</p>
                <p class="text-sm"><span class="font-semibold">Mode of Transport:</span> {{ 'N/A' }}</p>
            </div>
        </div>

        <div class="grid grid-cols-5 gap-2 border border-gray-400">
            <div class="col-span-1 p-2 border-r border-gray-400">
                <p class="text-sm font-semibold">Species</p>
            </div>
            <div class="col-span-1 p-2 border-r border-gray-400">
                <p class="text-sm font-semibold">Qty</p>
            </div>
            <div class="col-span-1 p-2 border-r border-gray-400">
                <p class="text-sm font-semibold">Sex</p>
            </div>
            <div class="col-span-1 p-2 border-r border-gray-400">
                <p class="text-sm font-semibold">Age</p>
            </div>
            <div class="col-span-1 p-2">
                <p class="text-sm font-semibold">Cert of Ownership/Transfer No.</p>
            </div>
            
            <!-- Data Row -->
            <div class="col-span-1 p-2 border-r border-t border-gray-400">
                <p class="text-sm">{{ strtoupper($animal->species->name) }}</p>
            </div>
            <div class="col-span-1 p-2 border-r border-t border-gray-400">
                <p class="text-sm">{{ $animal->is_group ? $animal->group_count : '1' }}</p>
            </div>
            <div class="col-span-1 p-2 border-r border-t border-gray-400">
                <p class="text-sm">{{ $animal->gender ?? 'N/A' }}</p>
            </div>
            <div class="col-span-1 p-2 border-r border-t border-gray-400">
                <p class="text-sm">{{ $animal->birth_date ? $animal->birth_date->age . ' years' : 'N/A' }}</p>
            </div>
            <div class="col-span-1 p-2 border-t border-gray-400">
                <p class="text-sm">{{ $animal->animal_id }}</p>
            </div>
        </div>

        <div class="mt-6">
            <p class="text-sm">This permit will expire on {{ date('F d, Y', strtotime('+30 days')) }} and is subject to cancellation should any dangerous or contagious communicable animal disease breakout of the place of origin or maybe revoked any time before the said date if interests of the public so require.</p>
        </div>
    </div>

    <div class="flex justify-between items-start mb-8">
        <div class="text-sm">
            <p><span class="font-semibold">Inspected by:</span></p>
            <div class="mt-12">
                <div class="border-t border-gray-800 w-48">
                    <p class="font-semibold text-center">DR. {{ strtoupper(auth()->user()->complete_name ?? 'VETERINARIAN') }}</p>
                    <p class="text-center">Veterinarian IV</p>
                    <p class="text-xs text-center">License No: {{ $licenseNumber }}</p>
                    <p class="text-xs text-center">Valid until: {{ $licenseValidUntil }}</p>
                </div>
            </div>
        </div>
        <div class="text-sm mt-10">
            <p class="font-semibold">OR No.: <span class="underline">{{ $orNumber }}</span></p>
            <p class="font-semibold">Amount: <span class="underline">PHP 200.00</span></p>
            <p class="font-semibold">DST: <span class="underline">PHP 30.00</span></p>
        </div>
    </div>

    <div class="mt-6 text-center">
        <a href="{{ route('animal.health-certificate.pdf', $animal->animal_id) }}" 
           class="inline-flex items-center px-4 py-2 bg-amber-600 text-white rounded-md hover:bg-amber-700 transition-colors duration-200 shadow-sm font-medium">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Download PDF
        </a>
    </div>
</div> 