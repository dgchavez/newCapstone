<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Transaction History for {{ $animal->name }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #222;
            margin: 30px;
        }
        .header {
            border-bottom: 2px solid #4CAF50;
            margin-bottom: 18px;
            padding-bottom: 8px;
        }
        .header h2 {
            margin: 0 0 4px 0;
            color: #4CAF50;
            font-size: 22px;
        }
        .header .meta {
            font-size: 12px;
            color: #555;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }
        th, td {
            border: 1px solid #bbb;
            padding: 5px 7px;
            text-align: left;
        }
        th {
            background: #f5f5f5;
            color: #333;
            font-weight: bold;
            font-size: 12px;
        }
        tr:nth-child(even) td {
            background: #f9f9f9;
        }
        .status-completed {
            color: #388e3c;
            font-weight: bold;
        }
        .status-pending {
            color: #fbc02d;
            font-weight: bold;
        }
        .status-cancelled {
            color: #d32f2f;
            font-weight: bold;
        }
        .no-data {
            text-align: center;
            color: #888;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Transaction History: {{ $animal->name }}</h2>
        <div class="meta">
            <span><strong>Owner:</strong> {{ optional($animal->owner->user)->complete_name ?? 'N/A' }}</span> |
            <span><strong>Species:</strong> {{ $animal->species->name ?? 'N/A' }}</span> |
            <span><strong>Breed:</strong> {{ $animal->breed->name ?? 'N/A' }}</span> |
            <span><strong>Generated:</strong> {{ now()->format('Y-m-d H:i') }}</span>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Type</th>
                <th>Subtype</th>
                <th>Veterinarian</th>
                <th>Status</th>
                <th>Notes</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $txn)
            <tr>
                <td>{{ $txn->created_at ? $txn->created_at->format('Y-m-d') : '' }}</td>
                <td>{{ $txn->transactionType->type_name ?? '' }}</td>
                <td>{{ $txn->transactionSubtype->subtype_name ?? '' }}</td>
                <td>{{ $txn->vet->complete_name ?? '' }}</td>
                <td>
                    @if($txn->status == 1)
                        <span class="status-completed">Completed</span>
                    @elseif($txn->status == 0)
                        <span class="status-pending">Pending</span>
                    @elseif($txn->status == 2)
                        <span class="status-cancelled">Cancelled</span>
                    @else
                        <span>Unknown</span>
                    @endif
                </td>
                <td>{{ $txn->details ?? 'No details added' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="no-data">No transactions found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>