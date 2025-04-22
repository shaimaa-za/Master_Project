<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .empty-orders-message {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            color: #333;
            margin-top: 20px;
        }
    </style>
</head>
<body>
        <!-- breadcrumb -->
        <div class="container">
            <div class="bread-crumb flex-w p-l-25 p-r-15 p-t-30 p-lr-0-lg">
                <a href="#" class="stext-109 cl8 hov-cl1 trans-04">
                    Dashboard
                    <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
                </a>

                <span class="stext-109 cl4">
                    Orders
                </span>
            </div>
        </div>

        <div class="bg0 p-t-55 p-b-55">
            <div class="container">
                <div class="row">
                    <div class="col-lg-10 col-xl-10 m-lr-auto m-b-50">
                        <div class="m-l-17 m-r--30 m-lr-0-xl">


                            <div class="row">
                                @if($orders->isEmpty())
                                    <div class="empty-orders-message">
                                        No pending orders at the moment.
                                    </div>
                                @else
                                    @foreach($orders as $order)
                                        <div class="col-md-6 mb-4">
                                            <div class="card shadow-sm">
                                                <div class="card-body">
                                                    <h5 class="card-title">Order {{ $order->id }}</h5>
                                                    <p><strong>Total Price:</strong> ${{ number_format($order->total_price, 2) }}</p>
                                                    
                                                    <!-- عرض حالة الدفع -->
                                                    @if($order->payment)
                                                        <p><strong>Payment Status:</strong> 
                                                            <span class="badge {{ $order->payment->status == 'pending' ? 'bg-warning text-dark' : 'bg-success' }}">
                                                                {{ ucfirst($order->payment->status) }}
                                                            </span>
                                                        </p>
                                                    @else
                                                        <p><strong>Payment Status:</strong> <span class="badge bg-secondary">Not Available</span></p>
                                                    @endif
                                                    
                                                    @if($order->shipping)
                                                        <p><strong>Shipping Status:</strong> 
                                                            <span class="badge {{ $order->shipping->status == 'pending' ? 'bg-warning text-dark' : 'bg-info' }}">
                                                                {{ ucfirst($order->shipping->status) }}
                                                            </span>
                                                        </p>
                                                        <p><strong>Tracking Number:</strong> {{ $order->shipping->tracking_number ?? 'Not Available' }}</p>
                                                    @else
                                                        <p><strong>Shipping Status:</strong> <span class="badge bg-secondary">Not Available</span></p>
                                                        <p><strong>Tracking Number:</strong> Not Available</p>
                                                    @endif
                                                    <br/>
                                                    <a href="{{ route('orders.show', ['order' => $order->id]) }}" class="btn btn-primary">View Details</a>
                                                    
                                                    <!-- زر تسجيل الطلب كمرتجع -->
                                                    @if($order->status !== 'canceled' && $order->status !== 'shipped' && $order->status !== 'delivered' && $order->shipping && $order->shipping->status === 'pending' && $order->payment && $order->payment->status !== 'completed')
                                                    <form action="{{ route('orders.return', ['order' => $order->id]) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit" class="btn btn-secondary" onclick="return confirm('Are you sure you want to return this order?');">
                                                            Request Return
                                                        </button>
                                                    </form>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>



                        </div>
                    </div>
   
</body>
</html>
