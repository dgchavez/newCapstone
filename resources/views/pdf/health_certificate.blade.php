<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Health Certificate</title>
    <style>
        @font-face {
            font-family: 'DejaVu Sans';
            src: url({{ storage_path('fonts/DejaVuSans.ttf') }}) format('truetype');
            font-weight: normal;
            font-style: normal;
        }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            line-height: 1.3;
            margin: 0;
            padding: 20px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .header-center {
            text-align: center;
            width: 70%;
            margin: 0 auto;
        }
        .title {
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 18px;
        }
        .main-content {
            border: 1px solid #777;
            padding: 15px;
            margin-bottom: 20px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }
        .info-item {
            margin-bottom: 5px;
            font-size: 12px;
        }
        .info-label {
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        th, td {
            border: 1px solid #777;
            padding: 5px;
            font-size: 12px;
            text-align: left;
        }
        th {
            font-weight: bold;
        }
        .note {
            font-size: 12px;
            margin-top: 15px;
        }
        .footer {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }
        .signature {
            width: 45%;
        }
        .signature-line {
            border-top: 1px solid #000;
            margin-top: 40px;
            width: 200px;
        }
        .signature-name {
            font-weight: bold;
            text-align: center;
            margin: 3px 0;
        }
        .payment {
            width: 45%;
            text-align: right;
            padding-top: 40px;
        }
        .payment-item {
            margin-bottom: 5px;
            font-weight: bold;
            font-size: 12px;
        }
        .underline {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <!-- Header with logos and titles -->
    <table style="width: 100%; border: none; margin-bottom: 20px;">
        <tr style="border: none;">
            <td style="width: 15%; text-align: left; border: none; vertical-align: top;">
                <img src="{{ public_path('assets/logo2.png') }}" style="width: 60px; height: 60px;">
            </td>
            <td style="width: 70%; text-align: center; border: none; vertical-align: top;">
                <div style="font-weight: bold; font-size: 14px;">Republic of the Philippines</div>
                <div style="font-size: 13px;">Province of Bukidnon</div>
                <div style="font-size: 13px;">City of Valencia</div>
                <div style="font-size: 10px;">City Veterinarians Office</div>
            </td>
            <td style="width: 15%; text-align: right; border: none; vertical-align: top;">
                <img src="{{ public_path('assets/val.png') }}" style="width: 60px; height: 60px;">
            </td>
        </tr>
    </table>
    
    <div class="title">
        Veterinary Health Certificate
    </div>
    
    <div class="main-content">
        <!-- Certificate Info -->
        <table style="border: none; margin-bottom: 15px;">
            <tr style="border: none;">
                <td style="width: 50%; border: none; vertical-align: top;">
                    <div class="info-item">
                        <span class="info-label">No.:</span> {{ sprintf('%04d', $animal->animal_id) }}
                    </div>
                    <div class="info-item">
                        <span class="info-label">Date:</span> {{ date('F d, Y') }}
                    </div>
                    <div class="info-item">
                        <span class="info-label">Station:</span> City Veterinary Office
                    </div>
                </td>
                <td style="width: 50%; border: none; vertical-align: top;">
                    <div class="info-item">
                        <span class="info-label">Health Condition:</span> APPARENTLY HEALTHY
                    </div>
                    <div class="info-item">
                        <span class="info-label">Purpose:</span> GENERAL HEALTH CHECK
                    </div>
                    <div class="info-item">
                        <span class="info-label">Mode of Transport:</span> N/A
                    </div>
                </td>
            </tr>
        </table>
            
        <!-- Animal Data Table -->
        <table>
            <thead>
                <tr>
                    <th style="width: 20%;">Species</th>
                    <th style="width: 10%;">Qty</th>
                    <th style="width: 15%;">Sex</th>
                    <th style="width: 15%;">Age</th>
                    <th style="width: 40%;">Cert of Ownership/Transfer No.</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ strtoupper($animal->species->name) }}</td>
                    <td>{{ $animal->is_group ? $animal->group_count : '1' }}</td>
                    <td>{{ $animal->gender ?? 'N/A' }}</td>
                    <td>{{ $animal->birth_date ? $animal->birth_date->age . ' years' : 'N/A' }}</td>
                    <td>{{ $animal->animal_id }}</td>
                </tr>
            </tbody>
        </table>
            
        <div class="note">
            This permit will expire on {{ date('F d, Y', strtotime('+30 days')) }} and is subject to cancellation should any dangerous or contagious communicable animal disease breakout of the place of origin or maybe revoked any time before the said date if interests of the public so require.
        </div>
    </div>
    
    <!-- Footer with signature and payment info -->
    <table style="width: 100%; border: none;">
        <tr style="border: none;">
            <td style="width: 50%; border: none; vertical-align: top;">
                <div style="font-size: 12px; font-weight: bold;">Inspected by:</div>
                <div style="margin-top: 40px; border-top: 1px solid #000; width: 200px;">
                    <div style="font-weight: bold; text-align: center; font-size: 12px;">DR. {{ strtoupper(auth()->user()->complete_name ?? 'VETERINARIAN') }}</div>
                    <div style="text-align: center; font-size: 11px;">Veterinarian IV</div>
                    <div style="text-align: center; font-size: 10px;">License No: {{ '0' . rand(1000, 9999) }}</div>
                    <div style="text-align: center; font-size: 10px;">Valid until: {{ date('m/d/Y', strtotime('+1 year')) }}</div>
                </div>
            </td>
            <td style="width: 50%; border: none; vertical-align: top; text-align: right; padding-top: 40px;">
                <div style="font-size: 12px; font-weight: bold;">OR No.: <span style="text-decoration: underline;">{{ date('Y') . '-' . sprintf('%07d', rand(1, 9999999)) }}</span></div>
                <div style="font-size: 12px; font-weight: bold;">Amount: <span style="text-decoration: underline;">PHP 200.00</span></div>
                <div style="font-size: 12px; font-weight: bold;">DST: <span style="text-decoration: underline;">PHP 30.00</span></div>
            </td>
        </tr>
    </table>
</body>
</html> 