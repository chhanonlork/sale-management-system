<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt #{{ $sale->invoice_number ?? $sale->id }}</title>
    <style>
        body { font-family: 'Battambang', sans-serif; padding: 20px; text-align: center; }
        .receipt-container { width: 300px; margin: 0 auto; border: 1px solid #ddd; padding: 10px; }
        .header h2 { margin: 0; }
        .info { text-align: left; margin-top: 10px; font-size: 14px; }
        .total { margin-top: 10px; border-top: 2px dashed #000; padding-top: 10px; font-weight: bold; font-size: 18px; }
        .footer { margin-top: 20px; font-size: 12px; color: #666; }
        @media print {
            body { padding: 0; }
            .receipt-container { width: 100%; border: none; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="receipt-container">
        <div class="header">
            <h2>POS SYSTEM</h2>
            <p>លេខទូរស័ព្ទ: 012 345 678</p>
        </div>

        <div class="info">
            <div><strong>លេខវិក្កយបត្រ:</strong> #{{ $sale->invoice_number ?? $sale->id }}</div>
            <div><strong>កាលបរិច្ឆេទ:</strong> {{ $sale->created_at->format('d-M-Y h:i A') }}</div>
            <div><strong>អ្នកលក់:</strong> {{ $sale->user->name ?? 'Staff' }}</div>
            <div><strong>អតិថិជន:</strong> {{ $sale->customer->name ?? 'General' }}</div>
        </div>

        <hr>

        {{-- បើសិនបងមាន Table SaleItems សូមដាក់ Loop នៅទីនេះ --}}
        {{-- 
        <div style="text-align: left;">
            @foreach($sale->items as $item)
                <div style="display: flex; justify-content: space-between;">
                    <span>{{ $item->product_name }} x {{ $item->qty }}</span>
                    <span>${{ number_format($item->price * $item->qty, 2) }}</span>
                </div>
            @endforeach
        </div> 
        --}}

        <div class="total">
            សរុប (Total): ${{ number_format($sale->total_amount, 2) }}
        </div>

        <div class="footer">
            <p>សូមអរគុណ! សូមអញ្ជើញមកម្តងទៀត។</p>
        </div>
    </div>

</body>
</html>