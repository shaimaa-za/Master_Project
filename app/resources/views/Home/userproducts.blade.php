@extends('Home.layout.master') 
@section('meta')
    <title>Shop - Luxury Jewelry </title>
    <meta name="description" content="Browse our exclusive collection of luxury jewelry. Shop from a wide range of designs crafted with the finest materials including gold, silver, and precious stones. Find your perfect piece today.">
    <meta name="keywords" content="luxury jewelry, shop, gold jewelry, silver jewelry, precious stones, elegant watches, jewelry collection">
@endsection
@section('content')


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
    .product-card {
        cursor: pointer;
        border: 1px solid transparent;
        transition: border 0.3s;
    }
    .product-card:hover {
        border: 1px solid blue;
    }
    .favorite-btn {
        font-size: 1.5rem;
    }
</style>

        <!-- breadcrumb -->
        <div class="container">
            <div class="bread-crumb flex-w p-l-25 p-r-15 p-t-90 p-lr-0-lg">
                <a href="/" class="stext-109 cl8 hov-cl1 trans-04">
                    Home
                    <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
                </a>

                <span class="stext-109 cl4">
                    Product Overview
                </span>
            </div>
        </div>
            

        <!-- favorites productes  -->
        <div class="bg0 p-t-30 p-b-55">
            <div class="container">
                <div class="row">
                    <div class="col-lg-11 col-xl-11 m-lr-auto m-b-50">
                        <div class="m-l-17 m-r--30 m-lr-0-xl">
                           

                            <!-- Tabs -->
                            <ul class="nav nav-tabs" id="categoryTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="tab-all" 
                                            data-bs-toggle="tab" data-bs-target="#content-all" 
                                            type="button" role="tab" aria-controls="content-all" 
                                            aria-selected="true">
                                        All Products
                                    </button>
                                </li>
                                @foreach($categories as $category)
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="tab-{{ $category->id }}" 
                                                data-bs-toggle="tab" data-bs-target="#content-{{ $category->id }}" 
                                                type="button" role="tab" aria-controls="content-{{ $category->id }}" 
                                                aria-selected="false">
                                            {{ $category->name }}
                                        </button>
                                    </li>
                                @endforeach
                            </ul>

                            <!-- Tab Content -->
                            <div class="tab-content mt-4" id="categoryTabsContent">
                                <!-- All Products Tab -->
                                <div class="tab-pane fade show active" id="content-all" role="tabpanel" aria-labelledby="tab-all">
                                    <div class="row">
                                        @foreach($allProducts as $product)
                                            <div class="col-md-3 mb-4">
                                                <div class="card h-100 product-card" data-url="{{ route('userproducts.details', $product->id) }}">
                                                    <div class="position-relative">
                                                        <!-- Product Image -->
                                                        @php
                                                            $primaryImage = $product->images->where('is_primary', true)->first();
                                                        @endphp
                                                        @if ($primaryImage)
                                                            <div class="product-image" style="height: 180px; overflow: hidden;">
                                                                <img src="{{ asset('storage/' . $primaryImage->image_url) }}" alt="{{ $product->name }}" 
                                                                    class="w-100 h-100" style="object-fit: cover;">
                                                            </div>
                                                        @else
                                                            <div class="product-image text-center" style="height: 180px; display: flex; align-items: center; justify-content: center;">
                                                                <p>No image available</p>
                                                            </div>
                                                        @endif
                                                        
                                                        <!-- Favorite Button -->
                                                        <button class="favorite-btn position-absolute top-0 end-0 m-2 border-0 bg-transparent" 
                                                                data-product-id="{{ $product->id }}">
                                                            <i class="heart-icon {{ auth()->check() && auth()->user()->favorites->contains('product_id', $product->id) ? 'fas fa-heart text-danger' : 'far fa-heart text-secondary' }}"></i>
                                                        </button>
                                                    </div>
                                                    
                                                    <!-- Product Details -->
                                                    <div class="card-body d-flex flex-column">
                                                        <h6 class="card-title">{{ $product->name }}</h6>
                                                        <p class="card-text text-truncate">{{ $product->description }}</p>
                                                        <p class="card-text"><strong>Price:</strong> ${{ $product->price }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Product Categories -->
                                @foreach($categories as $category)
                                    <div class="tab-pane fade" id="content-{{ $category->id }}" role="tabpanel" aria-labelledby="tab-{{ $category->id }}">
                                        <div class="row">
                                            @foreach($category->products as $product)
                                                <div class="col-md-3 mb-4">
                                                    <div class="card h-100 product-card" data-url="{{ route('userproducts.details', $product->id) }}">
                                                        <div class="position-relative">
                                                            <!-- Product Image -->
                                                            @php
                                                                $primaryImage = $product->images->where('is_primary', true)->first();
                                                            @endphp
                                                            @if ($primaryImage)
                                                                <div class="product-image" style="height: 180px; overflow: hidden;">
                                                                    <img src="{{ asset('storage/' . $primaryImage->image_url) }}" alt="{{ $product->name }}" 
                                                                        class="w-100 h-100" style="object-fit: cover;">
                                                                </div>
                                                            @else
                                                                <div class="product-image text-center" style="height: 180px; display: flex; align-items: center; justify-content: center;">
                                                                    <p>No image available</p>
                                                                </div>
                                                            @endif
                                                            
                                                            <!-- Favorite Button -->
                                                            <button class="favorite-btn position-absolute top-0 end-0 m-2 border-0 bg-transparent" 
                                                                    data-product-id="{{ $product->id }}">
                                                                <i class="heart-icon {{ auth()->check() && auth()->user()->favorites->contains('product_id', $product->id) ? 'fas fa-heart text-danger' : 'far fa-heart text-secondary' }}"></i>
                                                            </button>
                                                        </div>
                                                        
                                                        <!-- Product Details -->
                                                        <div class="card-body d-flex flex-column">
                                                            <h6 class="card-title">{{ $product->name }}</h6>
                                                            <p class="card-text text-truncate">{{ $product->description }}</p>
                                                            <p class="card-text"><strong>Price:</strong> ${{ $product->price }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.favorite-btn').click(function(event) {
            event.stopPropagation(); 
            let button = $(this);
            let productId = button.data('product-id');
            let icon = button.find('.heart-icon');

            @if(auth()->check())
                $.ajax({
                    url: "{{ route('favorites.toggle') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        product_id: productId
                    },
                    success: function(response) {
                        if (response.status === 'added') {
                            icon.removeClass('far fa-heart text-secondary').addClass('fas fa-heart text-danger');
                        } else {
                            icon.removeClass('fas fa-heart text-danger').addClass('far fa-heart text-secondary');
                        }
                    }
                });
            @else
                window.location.href = "{{ route('login') }}";
            @endif
        });
        
        $('.product-card').click(function() {
            window.location.href = $(this).data('url');
        });
    });
</script>

@endsection
