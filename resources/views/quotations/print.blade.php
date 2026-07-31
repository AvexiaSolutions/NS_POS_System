<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $quote->is_fake_bill ? 'Loyalty Bill' : 'Quotation' }} - {{ $shop->shop_name ?? 'POS' }}</title>
    <style>
        @page { margin: 0; size: auto; }
        body {
            font-family: 'Courier New', Courier, monospace;
            width: 78mm;
            margin: 0;
            padding: 10px;
            font-size: 14px;
            color: #000;
            background: #fff;
            line-height: 1.2;
        }
        
        .text-center { text-align: center; }
        .text-end { text-align: right; }
        .text-start { text-align: left; }
        .fw-bold { font-weight: bold; }
        
        .logo {
            max-width: 70px;
            height: auto;
            display: block;
            margin: 0 auto 5px auto;
            filter: grayscale(100%);
        }

        .dashed-line { border-bottom: 1px dashed #000; margin: 8px 0; }
        .solid-line { border-bottom: 1px solid #000; margin: 8px 0; }
        
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 3px 0; vertical-align: top; }
        
        .fs-5 { font-size: 18px; }
        .fs-6 { font-size: 16px; }

        @media print {
            body { width: 78mm; margin: 0; padding: 5px; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>

    <div class="text-center">
        @if(isset($shop->logo) && $shop->logo)
            <img src="{{ asset('storage/' . $shop->logo) }}" class="logo" alt="Logo">
        @endif
        <div class="fw-bold fs-5" style="text-transform: uppercase;">{{ $shop->shop_name ?? 'NS Enterprises' }}</div>
        
        <div style="font-size: 12px; margin-top: 2px;">
            @if(isset($shop->address) && $shop->address)
                <div>{{ $shop->address }}</div>
            @endif
            @if(isset($shop->phone) && $shop->phone)
                <div>Tel: {{ $shop->phone }}</div>
            @endif
        </div>
    </div>

    <div class="dashed-line"></div>
    
    <div style="font-size: 12px;">
        <div style="display: flex; justify-content: space-between;">
            <span>Date: {{ \Carbon\Carbon::parse($quote->created_at)->format('d/m/Y H:i') }}</span>
        </div>
        <div style="display: flex; justify-content: space-between;">
            <span>No: #{{ strtoupper(substr($quote->id, 0, 8)) }}</span>
            <span>Cashier: {{ auth()->user()->name ?? 'Admin' }}</span>
        </div>
        @if(!empty($quote->customer_name))
            <div>Customer: {{ $quote->customer_name }}</div>
        @endif
    </div>

    <div class="dashed-line"></div>

    <div class="text-center fw-bold fs-6" style="margin: 5px 0; text-transform: uppercase;">
        {{ $quote->is_fake_bill ? 'LOYALTY BILL' : 'QUOTATION' }}
    </div>

    <div class="solid-line" style="border-bottom-width: 1px;"></div>

    <table>
        <thead>
            <tr style="text-align: left; font-size: 13px;">
                <th style="width: 45%">Item</th>
                <th style="width: 20%" class="text-center">Qty</th>
                <th style="width: 35%" class="text-end">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
            <tr>
                <td colspan="3" class="fw-bold" style="padding-top: 6px; font-size: 14px;">{{ $item->name }}</td>
            </tr>
            <tr>
                <td style="font-size: 13px;">{{ number_format($item->price, 2) }}</td>
                <td class="text-center" style="font-size: 13px;">x{{ $item->qty }}</td>
                <td class="text-end" style="font-size: 13px;">{{ number_format($item->qty * $item->price, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="dashed-line"></div>

    <div style="font-size: 14px;">
        <div style="display: flex; justify-content: space-between;">
            <span>Subtotal:</span>
            <span>{{ number_format($quote->total_amount, 2) }}</span>
        </div>
    </div>

    <div class="solid-line"></div>

    <div style="display: flex; justify-content: space-between; font-size: 18px; font-weight: bold; margin: 5px 0;">
        <span>NET TOTAL:</span>
        <span>Rs. {{ number_format($quote->total_amount, 2) }}</span>
    </div>

    <div class="solid-line"></div>

    <div class="text-center" style="margin-top: 20px; margin-bottom: 30px;">
        @if($quote->is_fake_bill)
            <div class="fw-bold" style="font-size: 15px;">THANK YOU COME AGAIN!</div>
            <div style="font-size: 10px; margin-top: 5px; color: #555;">Software by: Binuka Saumyajith</div>
        @else
            <div class="fw-bold" style="font-size: 15px;">*** QUOTATION ONLY ***</div>
            <div style="font-size: 12px; margin-top: 5px;">Valid for 14 days from issued date.</div>
            <div style="font-size: 10px; margin-top: 5px; color: #555;">Software by: Binuka Saumyajith</div>
        @endif
    </div>

    <script>
        window.onload = function() {
            window.print();
            setTimeout(function() {
                window.close();
            }, 1000);
        }
    </script>
</body>
</html>
