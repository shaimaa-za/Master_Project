<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
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
        .stars {
            color: #ffcc00;
            font-size: 1.5rem;
        }
    </style>
</head>
<body>

<div class="modal fade show" id="orderModal" tabindex="-1" aria-labelledby="orderModalLabel" aria-hidden="false" style="display: block;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="orderModalLabel">Order Details</h5>
                <button type="button" class="btn-close" onclick="closeModal()"></button>
            </div>
            <div class="modal-body">
                <p><strong>Order ID:</strong> {{ $order->id }}</p>
                <p><strong>Total Price:</strong> ${{ number_format($order->total_price, 2) }}</p>

                <!-- عرض معلومات الدفع -->
                <p><strong>Payment Method:</strong> {{ ucfirst($order->payment->payment_method) }}</p>
                <p><strong>Status Payment:</strong> {{ ucfirst($order->payment->status) }}</p>

                <!-- عرض معلومات الشحن -->
                <p><strong>Shipping Carrier:</strong> {{ ucfirst($order->shipping->company_shipping) }}</p>
                <p><strong>Status Shipping:</strong> {{ ucfirst($order->shipping->status) }}</p>
                <p><strong>Shipping Address:</strong> {{ $order->shipping->address->full_name }}, {{ $order->shipping->address->address_line1 }}, {{ $order->shipping->address->city }}, {{ $order->shipping->address->state }}, {{ $order->shipping->address->country }} - {{ $order->shipping->address->zip_code }}</p>
                <p><strong>Tracking Number:</strong> {{ $order->shipping->tracking_number ?? 'Not Available' }}</p>

                <h5 class="mt-4">Order Items:</h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Total</th>
                            @if(strtolower($order->shipping->status) === 'delivered')
                                <th>Rating & Comment</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->orderItems as $item)
                            <tr>
                                <td>{{ $item->product->name }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>${{ number_format($item->price, 2) }}</td>
                                <td>${{ number_format($item->subtotal, 2) }}</td>

                                @if(strtolower($order->shipping->status) === 'delivered')
                                    <td>
                                        <!-- عرض التقييمات إذا كانت موجودة -->
                                        @php
                                            $review = $item->product->reviews()->where('order_id', $order->id)->first();
                                        @endphp

                                        @if($review)
                                            <!-- عرض التقييم الموجود -->
                                            <p>
                                                @for($i = 1; $i <= $review->rating; $i++)
                                                    ★
                                                @endfor
                                            </p>
                                            <p>{{ $review->comment }}</p>
                                        @else
                                            <!-- نموذج التقييم -->
                                            <form action="{{ route('reviews.store') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="order_id" value="{{ $order->id }}">
                                                <input type="hidden" name="product_id" value="{{ $item->product->id }}">
                                                <div class="mb-3">
                                                    <label for="rating" class="form-label">Rating</label>
                                                    <select name="rating" id="rating" class="form-control" required>
                                                        <option value="1">★</option>
                                                        <option value="2">★★</option>
                                                        <option value="3">★★★</option>
                                                        <option value="4">★★★★</option>
                                                        <option value="5">★★★★★</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="comment" class="form-label">Comment</label>
                                                    <textarea name="comment" id="comment" class="form-control" rows="3"></textarea>
                                                </div>
                                                <button type="submit" class="btn btn-primary">Submit Review</button>
                                            </form>
                                        @endif
                                    </td>
                                @else
                                    <td>Order not delivered yet. You can review after delivery.</td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="closeModal()">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    function closeModal() {
        document.getElementById('orderModal').style.display = 'none';
        window.location.href = "/customer/dashboard"; 
    }
</script>


</body>
</html>
