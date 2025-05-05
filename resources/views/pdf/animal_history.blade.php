<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Transaction History for {{ $animal->name }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        h2 { margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #333; padding: 8px; font-size: 12px; }
        th { background: #f0f0f0; }
    </style>
</head>
<body>
    <h2>Transaction History for {{ $animal->name }} (ID: {{ $animal->animal_id }})</h2>
    <p>Owner: {{ optional($animal->owner->user)->complete_name ?? 'N/A' }}</p>
    <p>Species: {{ $animal->species->name ?? 'N/A' }}</p>
    <p>Breed: {{ $animal->breed->name ?? 'N/A' }}</p>
    <p>Generated: {{ now()->format('Y-m-d H:i') }}</p>

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
                <td>{{ $txn->transactionType->name ?? '' }}</td>
                <td>{{ $txn->transactionSubtype->name ?? '' }}</td>
                <td>{{ $txn->vet->complete_name ?? '' }}</td>
                <td>
                    @if($txn->status == 1)
                        Completed
                    @elseif($txn->status == 0)
                        Pending
                    @elseif($txn->status == 2)
                        Cancelled
                    @else
                        Unknown
                    @endif
                </td>
                <td>{{ $txn->details ?? '' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center;">No transactions found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>