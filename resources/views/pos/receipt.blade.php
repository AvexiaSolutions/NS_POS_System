<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt #{{ $sale->invoice_no }}</title>
    <style>
        @page { margin: 0; size: auto; }
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 14px;
            margin: 0;
            padding: 10px;
            width: 78mm;
            background: #fff;
            color: #000;
        }
        .text-center { text-align: center; }
        .text-end { text-align: right; }
        .fw-bold { font-weight: bold; }
        .d-flex { display: flex; justify-content: space-between; }
        .logo { max-width: 70px; display: block; margin: 0 auto 5px auto; filter: grayscale(100%); }
        .dashed-line { border-bottom: 1px dashed #000; margin: 8px 0; }
        .solid-line { border-bottom: 1px solid #000; margin: 8px 0; }
        table { width: 100%; border-collapse: collapse; }
        td, th { padding: 3px 0; }
        .old-price { text-decoration: line-through; font-size: 12px; color: #555; }
        .saved-box { border: 2px solid #000; padding: 8px; margin-top: 10px; font-weight: bold; font-size: 16px; text-align: center; }

        .no-print { display: none !important; }
        
        @media print { body { margin: 0; padding: 5px; } }
    </style>
</head>
<body>
    <div class="text-center">
        @if(isset($shop->logo))
            <img src="{{ asset('storage/' . $shop->logo) }}" class="logo" alt="Logo">
        @endif
        <h2 style="margin: 0; font-size: 18px;">{{ $shop->shop_name ?? 'NS POS' }}</h2>
        <p style="margin: 2px 0; font-size: 12px;">{{ $shop->shop_address ?? '' }}</p>
        <p style="margin: 20 0; font-size: 12px;">Tel: {{ $shop->shop_phone ?? '' }}</p>
    </div>

    <div class="dashed-line"></div>
    <div style="font-size: 12px;">
        <div class="d-flex"><span>Inv: {{ $sale->invoice_no }}</span> <span>{{ date('d/m/Y H:i', strtotime($sale->created_at)) }}</span></div>
        <div class="d-flex"><span>Cashier:</span> <span>{{ auth()->user()->name ?? 'Admin' }}</span></div>
    </div>
    <div class="dashed-line"></div>

    <table>
        <thead>
            <tr style="font-size: 12px; border-bottom: 1px solid #000;">
                <th style="text-align: left;">Item</th>
                <th class="text-center">Qty</th>
                <th class="text-end">Amount</th>
            </tr>
        </thead>
        <tbody>
            @php $totalItemSavings = 0; @endphp
            @foreach($saleItems as $item)
                @php
                    $soldPrice = $item->price;
                    $originalPrice = $item->original_price;
                    $discountAmount = ($originalPrice > $soldPrice) ? $originalPrice - $soldPrice : 0;
                    if($discountAmount > 0) $totalItemSavings += ($discountAmount * $item->qty);
                @endphp
                <tr><td colspan="3" class="fw-bold" style="padding-top: 5px;">{{ $item->product_name }}</td></tr>
                <tr>
                    <td>
                        @if($discountAmount > 0)
                            <span class="old-price">{{ number_format($originalPrice, 2) }}</span><br>
                        @endif
                        <span>{{ number_format($soldPrice, 2) }}</span>
                    </td>
                    <td class="text-center">x{{ $item->qty + 0 }}</td>
                    <td class="text-end">{{ number_format($item->total, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="dashed-line"></div>
    <div class="d-flex"><span>Subtotal:</span> <span>{{ number_format($sale->sub_total, 2) }}</span></div>
    @if($sale->discount > 0)
        <div class="d-flex"><span>Bill Discount:</span> <span>-{{ number_format($sale->discount, 2) }}</span></div>
    @endif
    <div class="solid-line"></div>
    <div class="d-flex" style="font-size: 18px; font-weight: bold;"><span>NET TOTAL:</span> <span>Rs.{{ number_format($sale->total_amount, 2) }}</span></div>
    <div class="solid-line"></div>

    @if(($totalItemSavings + $sale->discount) > 0)
        <div class="saved-box">YOU SAVED: Rs. {{ number_format($totalItemSavings + $sale->discount, 2) }}</div>
    @endif

    <div class="text-center" style="margin-top: 20px;">
        <p class="fw-bold">THANK YOU COME AGAIN!</p>
        <p style="font-size: 10px;">Software by: Binuka Saumyajith</p>
    </div>

    <script>
        window.onload = function() {
            window.print();
            setTimeout(function() {
                window.close();
            }, 1000);
        };
    </script>
</body>
</html>
