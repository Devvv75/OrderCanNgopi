<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 20px;
        }
        #cart-container {
            width: 50%;
            margin: auto;
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .cart-item {
            display: flex;
            justify-content: space-between;
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        .cart-controls {
            display: flex;
            align-items: center;
        }
        .cart-controls button {
            margin: 0 5px;
        }
        button {
            background-color: red;
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            border-radius: 5px;
        }
        button:hover {
            background-color: #ff6b81;
        }
    </style>
</head>
<body>
    <h1>Checkout</h1>
    <div id="cart-container">
        <h2>Keranjang Belanja</h2>
        <div id="cart-items">
            @php $total = 0; @endphp
            @foreach ($orderItems as $item)
                @php
                    $itemTotal = $item['price'] * $item['quantity'];
                    $total += $itemTotal;
                @endphp
                <div class="cart-item">
                    <span>{{ $item['name'] }} - Rp. {{ number_format($item['price'], 0, ',', '.') }} (x{{ $item['quantity'] }})</span>
                    <div class="cart-controls">
                        <span>Total: Rp. {{ number_format($itemTotal, 0, ',', '.') }}</span>
                    </div>
                </div>
            @endforeach
        </div>
        <p id="total-price">Total: Rp. {{ number_format($total, 0, ',', '.') }}</p>
        <button onclick="checkout()">Checkout</button>
    </div>

    <script>
        function checkout() {
            window.location.href = "{{ route('receipt') }}";
        }
    </script>
</body>
</html>
