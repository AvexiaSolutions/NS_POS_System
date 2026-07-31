<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Barcodes - {{ $product->product_name }}</title>
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.0/dist/JsBarcode.all.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
        }
        .barcode-container {
            display: inline-block;
            text-align: center;
            border: 1px dotted #ccc;
            padding: 10px;
            margin: 10px;
            width: 200px;
        }
        .product-name {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 5px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .price {
            font-size: 16px;
            font-weight: bold;
            margin-top: 5px;
        }

        @media print {
            .no-print { display: none; }
            .barcode-container { border: none; }
        }
    </style>
</head>
<body>

    <div class="no-print" style="margin-bottom: 20px; padding: 10px; background: #eee;">
        <button onclick="window.print()" style="padding: 10px 20px; font-size: 16px; cursor: pointer; background: #007bff; color: white; border: none; border-radius: 5px;">Print Now</button>
        <button onclick="window.close()" style="padding: 10px 20px; font-size: 16px; cursor: pointer; background: #6c757d; color: white; border: none; border-radius: 5px; margin-left: 10px;">Close</button>
    </div>

    @for($i = 0; $i < $qty; $i++)
    <div class="barcode-container">
        <div class="product-name">{{ $product->product_name }}</div>
        
        <svg id="barcode{{ $i }}"></svg>
        
        <div class="price">Rs. {{ number_format($product->selling_price, 2) }}</div>
    </div>
    @endfor

    <script>
        window.onload = function() {
            var qty = {{ $qty }};
            var barcodeValue = "{{ $product->barcode }}";
            if(!barcodeValue) {
                barcodeValue = "{{ $product->id }}";
            }

            for(var i = 0; i < qty; i++) {
                JsBarcode("#barcode" + i, barcodeValue, {
                    format: "CODE128",
                    width: 2,
                    height: 40,
                    displayValue: true
                });
            }
        };
    </script>

</body>
</html>
