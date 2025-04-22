@extends('Home.layout.master')
@section('content')

<!-- Modal Search -->
        <!-- breadcrumb -->
        <div class="container">
            <div class="bread-crumb flex-w p-l-25 p-r-15 p-t-90 p-lr-0-lg">
                <a href="/" class="stext-109 cl8 hov-cl1 trans-04">
                    Home
                    <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
                </a>

                <span class="stext-109 cl4">
                    Search
                </span>
            </div>
        </div>
            

        <!-- favorites productes  -->
        <div class="bg0 p-t-55 p-b-55">
            <div class="container">
                <div class="row">
                    <div class="col-lg-10 col-xl-10 m-lr-auto m-b-50">
                        <div class="m-l-17 m-r--30 m-lr-0-xl">
                           
                            <div class="container-search-header">
                            
                                <!-- نموذج البحث بالنص -->
                                <form class="wrap-search-header flex-w p-l-15 " action="{{ route('product.searchByName') }}" method="GET">
                                    @csrf
                                    <button class="flex-c-m trans-04" style=" font-size: 30px;">
                                        <i class="zmdi zmdi-search" style=" font-size: 25px;"></i>
                                    </button>
                                    <input class="plh3" style=" font-size: 25px;" type="text" name="search" placeholder="Search..." required>
                                </form>
                                <br/>
                                <!-- نموذج البحث بالصورة -->
                                <form class="wrap-search-header flex-w p-l-15" action="{{ route('product.searchByImage') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <button type="button" class="flex-m trans-04" style=" font-size: 25px; gap: 30px;" onclick="document.getElementById('image').click();">
                                        <i class="zmdi zmdi-camera" style=" font-size: 25px; padding-left:20px"></i>
                                        <span>Search_by_Image</span>
                                    </button>
                                    <input type="file" name="image" id="image" style="display: none;" required onchange="this.form.submit();">
                                </form>

                                <!-- عرض المنتجات المبحوث عنها -->
                                <div style="width: 100%;">
                                    @if (isset($products))  
                                        @if (!$products->isEmpty())  
                                            <p style="margin: 17px; font-size: 38px; text-align: left;">Search Results:</p>
                                            <div style="margin-top: 20px; width: 100%; text-align: center;">
                                                @foreach($products as $product)
                                                    <div style="border: 1px solid #ddd; padding: 15px; margin-bottom: 15px; border-radius: 5px; display: inline-block; width: 80%;">
                                                        <h3 style="margin-bottom: 14px; font-size: 22px; color: #333;">{{ $product->name }}</h3>
                                                        <p style="font-size: 16px; color: #777;">{{ $product->description }}</p>
                                                        
                                                        <!-- عرض صور المنتج -->
                                                        @foreach($product->images as $image)
                                                            <img src="{{ asset('storage/'.$image->image_url) }}" alt="{{ $product->name }}" style="width: 240px; height: auto; margin-top: 13px; border-radius: 5px;">
                                                        @endforeach
                                                        
                                                        <!-- زر الانتقال إلى صفحة المنتج -->
                                                        <div style="margin-top: 15px;">
                                                            <a href="{{ route('userproducts.details', $product->id) }}" 
                                                            style="text-decoration: none; width: 200px; font-size: 16px; color: white; background-color: #007bff; padding: 10px 15px; border-radius: 5px; display: inline-block;">
                                                                View Product
                                                            </a>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else  
                                            <!-- عرض رسالة في حالة عدم وجود نتائج -->
                                            <p style="color: red; font-size: 18px; text-align: center; margin-top: 20px;">No Results Found</p>
                                        @endif  
                                    @endif  
                                </div>

                                
                            </div>

                            
		                </div>
                    </div>
                </div>
            </div>
        </div>

@endsection