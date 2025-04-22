<!DOCTYPE html>
<html>
    <head>
        <style>
            .table-shopping-cart th,
            .table-shopping-cart td {
                text-align: center; /* توسيط النص أفقيًا */
                vertical-align: middle; /* توسيط المحتوى عموديًا */
            }

            .table-shopping-cart .column-1 {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
            }

            .table-shopping-cart img {
                max-width: 80px; /* حجم مناسب للصورة */
                height: auto;
                margin-bottom: 5px;
            }

            .empty-cart-message {
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
                        Cart
                    </span>
                </div>
            </div>       
        <!-- Shopping Cart -->
        <form class="bg0 p-t-45 p-b-45">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-xl-7 m-lr-auto m-b-50">
                        <div class="m-l-25 m-r--38 m-lr-0-xl">
                            <div class="wrap-table-shopping-cart">
                                @if($isCartEmpty)
                                    <!-- إذا كانت السلة فارغة -->
                                    <div class="empty-cart-message">
                                        Add your favorite products to the cart.
                                    </div>
                                @else
                                    <!-- إذا كانت السلة تحتوي على منتجات -->
                                    <table class="table-shopping-cart">
                                        <tr class="table_head">
                                            <th class="column-1">Product</th>
                                            <th class="column-2">Price</th>
                                            <th class="column-3">Quantity</th>
                                            <th class="column-4">Total</th>
                                            <th class="column-5">Action</th>
                                        </tr>

                                        @foreach($cartItems as $item)
                                        <tr class="table_row">
                                            <td class="column-1">
                                                <div class="d-flex flex-column align-items-center">
                                                    <div class="how-itemcart1">
                                                        <img src="{{ asset('storage/' . $item->product->images->first()->image_url) }}" alt="{{ $item->product->name }}">
                                                    </div>
                                                    <span class="mt-2 text-center">{{ $item->product->name }}</span>
                                                </div>
                                            </td>
                                            
                                            <td class="column-2"> &nbsp;&nbsp; ${{ number_format($item->product->price, 2) }}</td>
                                            <td class="column-3">
                                                <form action="{{ route('cart.updateQuantity', $item->product->id) }}" method="POST">
                                                    @csrf
                                                    <div class="wrap-num-product flex-w m-l-auto m-r-0">
                                                        <button type="submit" name="quantity" value="{{ $item->quantity - 1 }}" class="btn-num-product-down cl8 hov-btn3 trans-04 flex-c-m">
                                                            <i class="fs-16 zmdi zmdi-minus"></i>
                                                        </button>

                                                        <input class="mtext-104 cl3 txt-center num-product" type="number" name="quantity" value="{{ $item->quantity }}" min="0" readonly>

                                                        <button type="submit" name="quantity" value="{{ $item->quantity + 1 }}" class="btn-num-product-up cl8 hov-btn3 trans-04 flex-c-m">
                                                            <i class="fs-16 zmdi zmdi-plus"></i>
                                                        </button>
                                                    </div>
                                                </form>
                                            </td>
                                            <td class="column-4">${{ number_format($item->product->price * $item->quantity, 2) }}</td>
                                            <td class="column-5">
                                                <form action="{{ route('cart.remove', $item->product->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">❌ Remove</button>
                                                </form>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </table>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-10 col-lg-4 col-xl-5 m-lr-auto m-b-50">
                        <div class="bor10 p-lr-40 p-t-30 p-b-40 m-l-63 m-r-40 m-lr-0-xl p-lr-15-sm">
                            <h4 class="mtext-109 cl2 p-b-30">Cart Totals</h4>

                            <div class="flex-w flex-t bor12 p-b-13">
                                
                            </div>

                            <div class="flex-w flex-t p-t-27 p-b-33">
                                <div class="size-208">
                                    <span class="mtext-101 cl2">Total:</span>
                                </div>
                                <div class="size-209 p-t-1">
                                    <span class="mtext-110 cl2">${{ number_format($total, 2) }}</span>
                                </div>
                            </div>

                            <!-- Button -->
                            @if(!$isCartEmpty)
                                <a href="{{ route('checkout.index') }}" 
                                   class="flex-c-m stext-101 cl0 size-116 bg3 bor14 hov-btn3 p-lr-15 trans-04 pointer">
                                    Proceed to Checkout
                                </a>   
                            @endif                            
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </body>
</html>
