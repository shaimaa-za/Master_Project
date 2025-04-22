<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Confirmation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background: rgba(190, 181, 181, 0.3);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .modal-content {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            padding: 20px;
        }
        .modal-header {
            border-bottom: none;
        }
        .modal-title {
            font-size: 1.5rem;
            font-weight: bold;
            color: blue;
        }
        .table th, .table td {
            text-align: left;
            padding: 8px;
        }
        .modal-footer {
            border-top: none;
            justify-content: center;
        }
        .btn-pay {
            background-color: #0070ba;
            color: white;
            padding: 10px 20px;
            font-size: 1.1em;
            border-radius: 5px;
            border: none;
        }
        .btn-pay:hover {
            background-color: #005ea6;
        }
    </style>
</head>
<body>

<!-- Payment Confirmation Modal -->
<div class="modal fade show" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="false" style="display: block;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentModalLabel">Payment Confirmation</h5>
                <button type="button" class="btn-close" onclick="closeModal()"></button>
            </div>
            <div class="modal-body">
                <p><strong>Order ID:</strong> {{ $order_id }}</p>

                @php
                    $order = App\Models\Order::find($order_id);
                @endphp

                @if($order)
                    <h5 class="mt-3">Order Details</h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->orderItems as $item)
                                <tr>
                                    <td>{{ $item->product->name }}</td>
                                    <td>${{ number_format($item->price, 2) }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>${{ number_format($item->price * $item->quantity, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <form action="{{ route('paymentpro', ['order_id' => $order->id]) }}" method="POST">
                        @csrf
                        <div class="text-center">
                            <button type="submit" class="btn btn-pay">Pay with PayPal</button>
                        </div>
                    </form>
                @else
                    <p class="text-danger">Order not found.</p>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    function closeModal() {
        document.getElementById('paymentModal').style.display = 'none';
        window.location.href = "/customer/dashboard"; 
    }
</script>

</body>
</html>
