<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Vaccination Card</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            font-size: 10px;
            line-height: 1.2;
        }
        .page {
            width: 100%;
            height: 100%;
        }
        .card {
            width: 100%;
            border: 1px solid #777;
            margin-bottom: 15px;
        }
        .header {
            border-top: 1px solid #059669;
            border-bottom: 1px solid #059669;
            height: 40px;
            position: relative;
            background-color: #fff;
        }
        .logos {
            position: absolute;
            left: 5px;
            top: 5px;
        }
        .logo {
            width: 18px;
            height: 18px;
            display: inline-block;
            margin-right: 3px;
        }
        .right-header {
            position: absolute;
            right: 5px;
            top: 2px;
            text-align: right;
        }
        .city-logo {
            width: 35px;
            height: 35px;
            display: inline-block;
            vertical-align: middle;
        }
        .header-text {
            display: inline-block;
            vertical-align: middle;
            margin-left: 5px;
        }
        .content {
            width: 100%;
            display: table;
        }
        .left-side {
            display: table-cell;
            width: 50%;
            background-color: #fff;
            padding: 5px;
            vertical-align: top;
            border-right: 1px solid #e5e7eb;
        }
        .right-side {
            display: table-cell;
            width: 50%;
            background-color: #fff;
            padding: 5px;
            vertical-align: top;
        }
        .center {
            text-align: center;
        }
        .bold {
            font-weight: bold;
        }
        .green {
            color: #047857;
        }
        .dark-green {
            color: #065f46;
        }
        .icons {
            text-align: center;
            padding: 5px 0;
        }
        .icon {
            width: 35px;
            height: 35px;
            display: inline-block;
            margin: 0 5px;
        }
        .fines-container {
            width: 100%;
            margin-top: 5px;
        }
        .fine-row {
            width: 100%;
            clear: both;
            margin-bottom: 5px;
        }
        .fine {
            width: 46%;
            float: left;
            margin: 0 2% 8px 2%;
            height: 30px;
        }
        .fine-icon {
            width: 16px;
            height: 16px;
            float: left;
            margin-right: 4px;
        }
        .fine-text {
            margin-left: 20px;
        }
        .fine-amount {
            font-weight: bold;
            font-size: 6.5px;
            margin-bottom: 2px;
        }
        .fine-desc {
            font-size: 5.5px;
            line-height: 1.1;
        }
        .contact {
            clear: both;
            text-align: center;
            font-size: 6px;
            padding-top: 5px;
            display: flex;
            justify-content: center;
        }
        .contact-item {
            display: inline-block;
            margin: 0 3px;
            white-space: nowrap;
        }
        .contact-icon {
            width: 10px;
            height: 10px;
            display: inline-block;
            vertical-align: middle;
            margin-right: 2px;
        }
        .contact-text {
            display: inline-block;
            max-width: 65px;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .form-field {
            margin-bottom: 5px;
        }
        .form-label {
            width: 33%;
            display: inline-block;
            vertical-align: top;
            color: #047857;
            font-weight: 500;
        }
        .form-value {
            width: 65%;
            display: inline-block;
            border-bottom: 1px solid #d1d5db;
            vertical-align: top;
        }
        .split-field {
            clear: both;
            margin-bottom: 5px;
        }
        .split-field-label {
            width: 25%;
            float: left;
            color: #047857;
            font-weight: 500;
        }
        .split-field-value {
            width: 25%;
            float: left;
            border-bottom: 1px solid #d1d5db;
        }
        .pet-name {
            border-top: 1px solid #d1d5db;
            border-bottom: 1px solid #d1d5db;
            padding: 5px 0;
            margin: 10px 0;
        }
        .vaccination-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        .vaccination-table th, 
        .vaccination-table td {
            border: 1px solid #d1d5db;
            padding: 3px;
            text-align: left;
            font-size: 9px;
        }
        .vaccination-table th {
            background-color: #fff;
            color: #065f46;
            border-bottom: 2px solid #10b981;
        }
        .vaccination-table tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .empty-row td {
            height: 15px;
        }
        .clearfix:after {
            content: "";
            display: table;
            clear: both;
        }
        .motto {
            clear: both;
            text-align: center;
            margin-top: 6px;
            padding: 3px 0;
            border-top: 1px dotted #d1d5db;
            font-size: 7px;
        }
        .tagline {
            font-size: 6px;
            line-height: 1.5;
            margin: 4px 0;
        }
    </style>
</head>
<body>
    <div class="page">
        <!-- Front side of vaccination card -->
        <div class="card">
            <div class="header">
                <div class="logos">
                    <img src="{{ public_path('assets/who.png') }}" class="logo">
                    <img src="{{ public_path('assets/doh.png') }}" class="logo">
                    <img src="{{ public_path('assets/buk.png') }}" class="logo">
                    <img src="{{ public_path('assets/val.png') }}" class="logo">
                    <img src="{{ public_path('assets/logo2.png') }}" class="logo">
                </div>
                <div class="right-header">
                    <img src="{{ public_path('assets/val.png') }}" class="city-logo">
                    <div class="header-text">
                        <div class="bold">Republic of the Philippines</div>
                        <div>Province of Bukidnon</div>
                        <div class="bold">CITY OF VALENCIA</div>
                    </div>
                </div>
            </div>
            
            <div class="content">
                <!-- Left side - Rabies information -->
                <div class="left-side">
                    <div class="center">
                        <div class="green bold">AS PER REPUBLIC ACT 9482</div>
                        <div class="green bold">(ANTI-RABIES ACT OF 2007)</div>
                    </div>
                    
                    <div style="margin: 8px 0;">
                        <div class="tagline">Iro ko, <span class="green bold">HIKTAN</span> ko</div>
                        <div class="tagline">Paak sa iro ko, <span class="green bold">GASTOS</span> ko</div>
                        <div class="tagline">Hugaw sa iro ko, <span class="green bold">LIMPYO</span> ko</div>
                    </div>
                    
                    <div class="icons">
                        <img src="{{ public_path('assets/walkingdog.png') }}" class="icon">
                        <img src="{{ public_path('assets/runningdog.png') }}" class="icon">
                        <img src="{{ public_path('assets/cleaningdog.jpg') }}" class="icon">
                    </div>
                    
                    <div class="center" style="margin: 5px 0;">
                        <div class="green bold">AS PER REPUBLIC ACT 9482</div>
                        <div class="green" style="font-size: 6px;">MULTA SA IRESPONSABLE NGA MGA TAG-IYA SA GINAHING IRO UG IRING</div>
                    </div>
                    
                    <!-- Completely redesigned fines section with 2x2 layout -->
                    <div class="fines-container">
                        <div class="fine-row clearfix">
                            <div class="fine">
                                <img src="{{ public_path('assets/doggo.png') }}" class="fine-icon">
                                <div class="fine-text">
                                    <div class="fine-amount">₱2,000.00</div>
                                    <div class="fine-desc">Dili magparehistro o magpabakuna sa ilang binuhi!</div>
                                </div>
                            </div>
                            <div class="fine">
                                <img src="{{ public_path('assets/doggo1.png') }}" class="fine-icon">
                                <div class="fine-text">
                                    <div class="fine-amount">₱25,000.00</div>
                                    <div class="fine-desc">Madumili nga ipasubos sa obserbasyon sa ilang iro o iring ug dili mo-abaga sa gasto.</div>
                                </div>
                            </div>
                        </div>
                        <div class="fine-row clearfix">
                            <div class="fine">
                                <img src="{{ public_path('assets/doggo3.png') }}" class="fine-icon">
                                <div class="fine-text">
                                    <div class="fine-amount">₱10,000.00</div>
                                    <div class="fine-desc">Nagdumili sa pagobserbar sa ilang iro pagkahuman sa makaplak.</div>
                                </div>
                            </div>
                            <div class="fine">
                                <img src="{{ public_path('assets/doggo4.png') }}" class="fine-icon">
                                <div class="fine-text">
                                    <div class="fine-amount">₱500.00 sa matag akaldente</div>
                                    <div class="fine-desc">Motumili sa pagbutang ug higot sa ilang mga iro kung gawas.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Motto in separate section for better spacing -->
                    <div class="motto">
                        <div class="dark-green bold">"MAHIMUNG RESPONSABLE SA ATONG BINUHING HAYOP!"</div>
                    </div>
                    
                    <div class="contact">
                        <div class="contact-item">
                            <img src="{{ public_path('assets/call.jpg') }}" class="contact-icon">
                            <span class="contact-text">088-828-4273</span>
                        </div>
                        <div class="contact-item">
                            <img src="{{ public_path('assets/gmail.png') }}" class="contact-icon">
                            <span class="contact-text">valenciacityvet@gmail.com</span>
                        </div>
                        <div class="contact-item">
                            <img src="{{ public_path('assets/facebook.png') }}" class="contact-icon">
                            <span class="contact-text">Vet Valencia</span>
                        </div>
                    </div>
                </div>
                
                <!-- Right side - Registration info -->
                <div class="right-side">
                    <div class="center" style="margin-bottom: 5px;">
                        <div style="font-size: 10px; color: #047857; font-weight: bold;">CITY VETERINARIAN'S OFFICE</div>
                        <div style="font-size: 8px; font-style: italic;">Purok 2 Pinahilan, Valencia City, Bukidnon</div>
                    </div>
                    
                    <div class="center" style="margin-bottom: 10px;">
                        <div style="font-size: 12px; font-weight: bold; color: #065f46;">CERTIFICATE OF REGISTRATION</div>
                        <div>Reg. No.: <span style="text-decoration: underline;">{{ sprintf('%06d', $animal->animal_id) }}</span></div>
                    </div>
                    
                    <div class="pet-name">
                        <div class="center" style="color: #065f46; font-weight: bold;">{{ $animal->name }}</div>
                        <div class="center" style="font-size: 8px; color: #6b7280;">PET'S NAME</div>
                    </div>
                    
                    <div class="form-field">
                        <div class="form-label">BIRTH DATE:</div>
                        <div class="form-value">{{ $animal->birth_date ? $animal->birth_date->format('m/d/Y') : '_________' }}</div>
                    </div>
                    
                    <div class="form-field">
                        <div class="form-label">AGE:</div>
                        <div class="form-value">{{ $animal->birth_date ? $animal->birth_date->age : '_________' }}</div>
                    </div>
                    
                    <div class="form-field">
                        <div class="form-label">SEX:</div>
                        <div class="form-value">{{ $animal->gender ?? '_________' }}</div>
                    </div>
                    
                    <div class="split-field clearfix">
                        <div class="split-field-label">SPECIES:</div>
                        <div class="split-field-value">{{ $animal->species->name ?? '_________' }}</div>
                        <div class="split-field-label" style="padding-left: 5px;">BREED:</div>
                        <div class="split-field-value">{{ $animal->breed->name ?? '_________' }}</div>
                    </div>
                    
                    <div class="form-field">
                        <div class="form-label">COLOR:</div>
                        <div class="form-value">{{ $animal->color ?? '_________' }}</div>
                    </div>
                    
                    <div class="form-field">
                        <div class="form-label">OWNER:</div>
                        <div class="form-value">{{ $animal->owner->user->complete_name ?? '_________' }}</div>
                    </div>
                    
                    <div class="form-field">
                        <div class="form-label" style="vertical-align: top;">ADDRESS:</div>
                        <div class="form-value">
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Vaccination Records Table - on the same page -->
        <table class="vaccination-table">
            <thead>
                <tr>
                    <th width="20%">DATE</th>
                    <th width="25%">VACCINE USED</th>
                    <th width="20%">BOOSTER DATE</th>
                    <th width="20%">VETERINARIAN</th>
                    <th width="15%">DETAILS</th>
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
                    <tr>
                        <td>{{ $record->created_at->format('m/d/Y') }}</td>
                        <td>
                            @if($record->vaccine)
                                {{ $record->vaccine->vaccine_name ?? 'N/A' }}
                            @elseif($record->transactionSubtype)
                                {{ $record->transactionSubtype->subtype_name ?? 'N/A' }}
                            @else
                                {{ $record->transactionType->type_name ?? 'General Vaccine' }}
                            @endif
                        </td>
                        <td>
                            @if($record->vaccine && $record->vaccine->validity_period)
                                {{ $record->created_at->addDays($record->vaccine->validity_period)->format('m/d/Y') }}
                            @elseif($record->vaccine)
                                {{ $record->created_at->addYear()->format('m/d/Y') }}
                            @else
                                {{ $record->created_at->addYear()->format('m/d/Y') }}
                            @endif
                        </td>
                        <td>{{ $record->vet->complete_name ?? 'N/A' }}</td>
                        <td>{{ $record->details ?? 'N/A' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; color: #777;">No vaccination records found</td>
                    </tr>
                @endforelse
                
                @php
                    $remainingRows = max(0, 6 - count($vaccinationRecords)); // Reduced number of empty rows
                @endphp
                
                @for($i = 0; $i < $remainingRows; $i++)
                    <tr class="empty-row {{ $i % 2 == 0 ? 'bg-even' : '' }}">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                @endfor
            </tbody>
        </table>
    </div>
</body>
</html> 