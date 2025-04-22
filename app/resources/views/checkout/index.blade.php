<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
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
    </style>
</head>
<body>

<div class="modal fade show" id="checkoutModal" tabindex="-1" aria-labelledby="checkoutModalLabel" aria-hidden="false" style="display: block;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="checkoutModalLabel">Checkout</h5>
                <button type="button" class="btn-close" onclick="closeModal()"></button>
            </div>
            <div class="modal-body">
                
                <!-- عرض المنتجات في السلة -->
                <h5>Your Cart Items</h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cartItems as $item)
                        <tr>
                            <td>{{ $item->product->name }}</td>
                            <td>${{ number_format($item->product->price, 2) }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>${{ number_format($item->product->price * $item->quantity, 2) }}</td>
                        </tr>
                        @endforeach
                        <tr>
                            <td colspan="3" class="text-end"><strong>Total Price</strong></td>
                            <td>${{ number_format($total, 2) }}</td>
                        </tr>
                    </tbody>
                </table>

                <!-- نموذج الدفع -->
                <form action="{{ route('checkout.store') }}" method="POST">
                    @csrf
                    <h5>Shipping Address</h5>
                    <div class="mb-3">
                        <input type="text" name="full_name" class="form-control" placeholder="Full Name" required>
                        <input type="text" name="phone" class="form-control" placeholder="Phone Number" required>
                        <input type="text" name="address_line1" class="form-control" placeholder="Address Line 1" required>
                        <input type="text" name="address_line2" class="form-control" placeholder="Address Line 2">
                        <input type="text" name="city" class="form-control" placeholder="City" required>
                        <input type="text" name="state" class="form-control" placeholder="State" required>
                        <input type="text" name="country" class="form-control" placeholder="Country" required>
                        <input type="text" name="zip_code" class="form-control" placeholder="Zip Code" required>
                    </div>

                    <h5>Shipping Method</h5>
                    <select name="carrier" id="carrier" class="form-select" required onchange="updateShippingFee()">
                        <option value="DHL_Express">DHL - Express (Fast) - $12</option>
                        <option value="DHL_Standard">DHL - Standard (Slow) - $6</option>
                        <option value="FedEx_Express">FedEx - Express (Fast) - $15</option>
                        <option value="FedEx_Standard">FedEx - Standard (Slow) - $7</option>
                    </select>
                    <input type="hidden" name="shipping_fee" id="shipping_fee" value="12">

                    <h5>Payment Information</h5>
                    <select name="payment_method" id="payment_method" class="form-select" required>
                        <option value="cod">Cash on Delivery</option>
                        <option value="paypal">PayPal</option>
                    </select>              

                    <div class="modal-footer mt-3">
                        <button type="submit" class="btn btn-primary">Confirm Order</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function closeModal() {
        document.getElementById('checkoutModal').style.display = 'none';
        window.history.back();
    }

    function updateShippingFee() {
        let carrier = document.getElementById("carrier").value;
        let shippingFee = 12; 

        if (carrier === "DHL_Standard") shippingFee = 6;
        else if (carrier === "FedEx_Express") shippingFee = 15;
        else if (carrier === "FedEx_Standard") shippingFee = 7;

        document.getElementById("shipping_fee").value = shippingFee;
    }
</script>

</body>
</html>
