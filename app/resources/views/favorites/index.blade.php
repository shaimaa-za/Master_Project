<!DOCTYPE html>
<html>
    <head>
       
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
                    Favorites
                </span>
            </div>
        </div>
            

        <!-- favorites productes  -->
        <div class="bg0 p-t-55 p-b-55">
            <div class="container">
                <div class="row">
                    <div class="col-lg-10 col-xl-10 m-lr-auto m-b-50">
                        <div class="m-l-17 m-r--30 m-lr-0-xl">
                           

                                
                                    @if($favorites->isEmpty())
                                        <div class="empty-cart-message">No favorite products yet.</div>
                                    @else
                                        <div class="row">
                                            @foreach($favorites as $favorite)
                                                <div class="col-md-4 lg-4 xl-4 mb-4">
                                                    <div class="card shadow-sm">
                                                        <!-- عند النقر على البطاقة سيتم التوجيه إلى صفحة التفاصيل -->
                                                        <a href="{{ route('userproducts.details', $favorite->product->id) }}" class="product-link">
                                                            <img src="{{ asset('storage/' . $favorite->product->images->first()->image_url) }}" class="card-img-top img-fluid" alt="{{ $favorite->product->name }}">
                                                            <div class=" text-center"  style="color: black">
                                                                <h6 class="card-title">{{ $favorite->product->name }}</h6>
                                                                <p class="card-text">${{ $favorite->product->price }}</p>
                                                            </div>
                                                        </a>
                        
                                                       
                                                        <div class="card-footer text-center">
                                                           
                                                            <!-- إضافة المنتج إلى السلة -->
                                                            <form action="{{ route('favorites.addToCart', $favorite->product->id) }}" method="POST">
                                                                @csrf
                                                                <button type="submit" class="btn btn-primary btn-sm">
                                                                    🛒 Add to Cart
                                                                </button>
                                                            </form>

                                                            <hr/>
                                                             <!-- حذف المنتج من المفضلة -->
                                                             <form action="{{ route('favorites.remove', $favorite->product->id) }}" method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-outline-danger btn-sm">
                                                                    ❌ Remove
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                

                          


                        </div>
                    </div>


                </div>
            </div>
        </div>
    </body>
</html>


