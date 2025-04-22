@extends('Home.layout.master')
@section('meta')
    <title>Product Details - Luxury Jewelry</title>
    <meta name="description" content="View detailed specifications, features, and high-quality images of our luxury jewelry pieces. Get all the information you need to make an informed purchase decision.">
    <meta name="keywords" content="product details, jewelry specifications, luxury jewelry, product features, jewelry images, purchase information, detailed view">
@endsection
@section('content')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">


        <!-- breadcrumb -->
        <div class="container">
            <div class="bread-crumb flex-w p-l-25 p-r-15 p-t-80 p-lr-0-lg">
                <a href="/" class="stext-109 cl8 hov-cl1 trans-04">
                    Home
                    <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
                </a>

                <a href="{{ route('userproducts.index') }}" class="stext-109 cl4">
                    Shop
                    <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
                </a>
                <span class="stext-109 cl4">
                 {{ $product->name }}
                </span>
            </div>
        </div>
        
        <div class="bg0 p-t-5 p-b-5">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 col-xl-12 m-lr-auto m-b-5">
                        <div class="m-l-17 m-r--30 m-lr-0-xl">
                          

                            <div class="container-fluid my-5">
                                <div style="width: 90%; margin: 0 auto 2%;">
                                    <div class="row">
                                        <!-- عرض صور المنتج -->
                                        <div class="col-md-6">
                                            <div id="productCarousel" class="carousel slide" data-bs-ride="carousel">
                                                <div class="carousel-inner">
                                                    @foreach($product->images as $image)
                                                        <div class="carousel-item @if($loop->first) active @endif">
                                                            <img src="{{ asset('storage/' . $image->image_url) }}" class="d-block w-100" alt="{{ $product->name }}" style="max-height: 400px; object-fit: cover;">
                                                        </div>
                                                    @endforeach
                                                </div>
                                                <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                    <span class="visually-hidden">Previous</span>
                                                </button>
                                                <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                    <span class="visually-hidden">Next</span>
                                                </button>
                                            </div>

                                            <!-- عرض الصور المصغرة -->
                                            <div class="mt-3">
                                                <div class="d-flex justify-content-start">
                                                    @foreach($product->images as $image)
                                                        <img src="{{ asset('storage/' . $image->image_url) }}" class="img-thumbnail me-2" alt="{{ $product->name }}" style="width: 80px; height: 80px; object-fit: cover;" data-bs-target="#productCarousel" data-bs-slide-to="{{ $loop->index }}">
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- تفاصيل المنتج -->
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center">
                                                <h2 class="me-3">{{ $product->name }}</h2>
                                                
                                                @auth
                                                    <!-- أيقونة المفضلة -->
                                                    <button class="btn btn-link p-0 favorite-btn" data-product-id="{{ $product->id }}">
                                                        @if(auth()->user()->favorites->contains('product_id', $product->id))
                                                            <i class="fas fa-heart text-danger favorite-icon"></i>
                                                        @else
                                                            <i class="far fa-heart text-secondary favorite-icon"></i>
                                                        @endif
                                                    </button>
                                                @else
                                                    <!-- للمستخدم غير المسجل -->
                                                    <a href="{{ route('login') }}" class="btn btn-link p-0">
                                                        <i class="far fa-heart text-secondary"></i>
                                                    </a>
                                                @endauth
                                            </div>

                                            <p class="text-muted">${{ $product->price }}</p> <br/>
                                            <p>{{ $product->description }}</p> <br/>
                                            <p>Stock: {{ $product->stock }}</p> <br/>

                                            @if($product->attributes->isNotEmpty())
                                                <div class="mb-3">
                                                    @foreach($product->attributes as $attribute)
                                                        <div class="mb-2">
                                                            <p>{{ $attribute->name }}</p>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif

                                            <!-- زر الإضافة إلى السلة -->
                                            <form action="{{ route('details.addToCart', ['productId' => $product->id]) }}" method="POST" class="mt-4">
                                                @csrf
                                                <div class="mb-3 d-flex align-items-center">
                                                    <label for="quantity" class="me-3"><strong>Quantity:</strong></label>
                                                    <input type="number" name="quantity" id="quantity" class="form-control" value="1" min="1" style="width: 90px;">
                                                </div>
                                                <button type="submit" class="btn btn-primary w-100">Add to Cart</button>
                                            </form>
                                            <br/>
                                            @if(session('success'))
                                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                                {{ session('success') }}
                                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                            </div>
                                        @endif
                                        </div>
                                
                                    </div>
                                </div>
                                
                                <!-- Nav tabs -->
                                <ul class="nav nav-tabs" role="tablist">
                                    <li class="nav-item p-b-10">
                                        <a class="nav-link active" data-toggle="tab" href="#information" role="tab">Related Products</a>
                                    </li>
                
                                    <li class="nav-item p-b-10">
                                        <a class="nav-link" data-toggle="tab" href="#description" role="tab">Customer Reviews</a>
                                    </li>
                                </ul>
                                
                                <!-- Tab panes -->
                                <div class="tab-content p-t-43">
                                    
                                    <!-- Related Products Tab  -->
                                    <div class="tab-pane fade show active" id="information" role="tabpanel">
                                        <div class="row">
                                            <div class=" col-12 ">
                                                
                                                    <!-- breadcrumb -->
                                                <div class="container">
                                                    <div class="bread-crumb flex-w p-l-10 p-r-15 p-t-1 p-lr-0-lg">
                                                        <a href="/" class="stext-109 cl8 hov-cl1 trans-04">
                                                            Home
                                                            <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
                                                        </a>

                                                        <span class="stext-109 cl4">
                                                            Related Products
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    @if(isset($recommendedProducts) && $recommendedProducts->isNotEmpty())
                                                        @foreach ($recommendedProducts as $recommendedProduct)
                                                            <div class="col-12 col-sm-6 col-lg-3 my-3">
                                                                <div class="product-card card mx-3 my-4" style=" box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); transition: transform 0.3s ease;">
                                                                    <div class="product-image" style="height: 200px; overflow: hidden;">
                                                                        @foreach($recommendedProduct->images as $image)
                                                                            <img src="{{ asset('storage/' . $image->image_url) }}" class="d-block w-100" alt="{{ $recommendedProduct->name }}" style="height: 100%; width: 100%; object-fit: cover;">
                                                                        @endforeach
                                                                    </div>
                                                                    <div class="card-body text-center">
                                                                        <h5 class="card-title">{{ $recommendedProduct->name }}</h5>
                                                                        <a href="{{ route('userproducts.show', $recommendedProduct->id) }}" class="btn btn-primary btn-sm">View Product</a>
                                                                    </div>
                                                                </div>
                                                            </div>   
                                                        @endforeach
                                                    @else
                                                
                                                        @php
                                                            $relatedProducts = \App\Models\Product::where('category_id', $product->category_id)
                                                                                ->where('id', '!=', $product->id)
                                                                                ->take(4)
                                                                                ->get();
                                                        @endphp
                                                        <div class="row">
                                                            @if($relatedProducts->isNotEmpty())
                                                                @foreach ($relatedProducts as $relatedProduct)
                                                                <div class="col-12 col-sm-6 col-lg-3 my-3">
                                                                        <div class="product-card card mx-3 my-4" style=" box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); transition: transform 0.3s ease;">
                                                                            <div class="product-image" style="height: 200px; overflow: hidden;">
                                                                                @foreach($relatedProduct->images as $image)
                                                                                    <img src="{{ asset('storage/' . $image->image_url) }}" class="d-block w-100" alt="{{ $relatedProduct->name }}" style="height: 100%; width: 100%; object-fit: cover;">
                                                                                @endforeach
                                                                            </div>
                                                                            <div class="card-body text-center">
                                                                                <h5 class="card-title">{{ $relatedProduct->name }}</h5>
                                                                                <a href="{{ route('userproducts.show', $relatedProduct->id) }}" class="btn btn-primary btn-sm">View Product</a>
                                                                            </div>
                                                                        </div>
                                                                </div>
                                                            @endforeach
                                                            @else
                                                                <p>No Related Products</p>
                                                            @endif
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                
                                    <!-- Customer Reviews Tab -->
                                    <div class="tab-pane fade" id="description" role="tabpanel">
                                        <div class="how-pos2 p-lr-15-md">
                                            
                                            <!-- breadcrumb -->
                                            <div class="container">
                                                <div class="bread-crumb flex-w p-l-10 p-r-15 p-t-1 p-lr-0-lg">
                                                    <a href="/" class="stext-109 cl8 hov-cl1 trans-04">
                                                        Home
                                                        <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
                                                    </a>

                                                    <span class="stext-109 cl4">
                                                        Customer Reviews
                                                    </span>
                                                </div>
                                            </div>
                                            @if($product->reviews->count() > 0)
                                                @foreach($product->reviews as $review)
                                                    <div class="card my-3">
                                                        <div class="card-body">
                                                            <p><strong><i class="fas fa-user-circle text-primary"></i></strong> {{ $review->user->name }}</p>
                                                            <p><strong>Rating:</strong>
                                                                @for($i = 0; $i < $review->rating; $i++)
                                                                    ⭐
                                                                @endfor
                                                            </p>
                                                            <p><strong>Comment:</strong> {{ $review->comment }}</p>
                                                            <p class="text-muted">Reviewed on {{ $review->created_at->format('d M Y') }}</p>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @else
                                                <p>No reviews yet. Be the first to review this product!</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                          
                                      
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                document.querySelectorAll('.favorite-btn').forEach(button => {
                    button.addEventListener('click', function() {
                        const productId = this.getAttribute('data-product-id');
                        const icon = this.querySelector('.favorite-icon');

                        fetch(`/favorites/toggle/${productId}`, {
                            method: "POST",
                            headers: {
                                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                "Content-Type": "application/json"
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === "added") {
                                icon.classList.remove("far", "text-secondary");
                                icon.classList.add("fas", "text-danger");
                            } else {
                                icon.classList.remove("fas", "text-danger");
                                icon.classList.add("far", "text-secondary");
                            }
                        })
                        .catch(error => console.error("Error:", error));
                    });
                });
            });
        </script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
        $(document).ready(function() {
            var productId = {{ $product->id }};
            
            // إرسال طلب تسجيل الزيارة إلى السيرفر باستخدام AJAX
            $.ajax({
                url: '/track-product-view/' + productId,
                method: 'GET',
                success: function(response) {
                    console.log('Product view tracked successfully');
                },
                error: function() {
                    console.log('Error tracking product view');
                }
            });
        });
        </script>

@endsection
