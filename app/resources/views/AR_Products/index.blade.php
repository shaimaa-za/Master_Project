@extends('Home.layout.master')
@section('meta')
    <title>Augmented Reality Jewelry Experience</title>
    <meta name="description" content="Experience our cutting-edge AR technology that lets you visualize luxury jewelry on yourself. Explore our AR products to see how our designs come to life in a virtual try-on.">
    <meta name="keywords" content="augmented reality, AR jewelry, virtual try-on, luxury jewelry, jewelry experience, AR products, innovative shopping">
@endsection
@section('content')

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title> Augmented Reality Products</title>
    <style>
        .faq-container {
            max-width: 100%;
            margin: 10px auto 0 ;
            
            padding: 10px;
          
        }
        body {
            font-family: Arial, sans-serif;
            
        }
        .product-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }
        .product-card {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px;
            margin: 10px;
            width: 280px;
            background-color: #f9f9f9;
            text-align: center;
            box-shadow: 2px 2px 8px rgba(0, 0, 0, 0.1);
        }
        .product-card img {
            width: 100%;
            height: auto;
            border-radius: 5px;
        }
        .product-card a {
            display: inline-block;
            margin: 20px 6px;
            padding: 6px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }
        .product-card a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    
    <!-- breadcrumb -->
    <div class="container">
        <div class="bread-crumb flex-w p-l-25 p-r-15 p-t-90 p-lr-0-lg">
            <a href="/" class="stext-109 cl8 hov-cl1 trans-04">
                Home
                <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
            </a>

            <span class="stext-109 cl4">
                Augmented Reality Products
            </span>
        </div>
    </div>
        


    <div class="bg0 p-t-5 p-b-5">
        <div class="container">

            <h4 style="padding: 10px"> View Face Filter:</h4>
            <div class="row">
                <div class="col-lg-4 col-xl-4 m-lr-auto m-b-50">
                    <div class="m-b-1 m-r--3 m-lr-0-xl">
                        
                        <div class="product-container">
                            <div class="product-card">
                                <img src="{{ asset('images/filter1.png') }}" alt="errings filter" style="width: 98%; height: 60%; object-fit: cover; border-radius: 2px;">
                                <br/>
                                <b><p>Zircon Earring 170943</p></b>
                                <p>Stone: Zircon, Earring Weight (Gms): 10,Earring Length (Cm): 5,  Earring Style: Hangings</p>
                                <p>Price: $1500</p>
                                <a href="{{ route('AR_Products.filter1') }}">View in Augmented Reality</a>
                            </div>
                        </div>
                        
                    </div>
                </div>

                <div class="col-lg-4 col-xl-4 m-lr-auto m-b-50">
                    <div class="m-l-1 m-r--3 m-lr-0-xl">
                        <div class="product-container">
                            <div class="product-card">
                                <img src="{{ asset('images/filter2.png') }}" alt="necklace filter" style="width: 98%; height: 60%; object-fit: cover; border-radius: 2px;">
                                <b><p>Gold Diamond Necklace</p></b>
                                <p> Weight :- 51.89 gm Approx. 18k Gold Weight :- 47.83 gm Approx. Diamond Weight :- 20.30 ct. Approx.</p>
                                <p>Price: $2500</p>
                                <a href="{{ route('AR_Products.filter2') }}">View in Augmented Reality</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-xl-4 m-lr-auto m-b-50">
                    <div class="m-l-1 m-r--3 m-lr-0-xl">
                        <div class="product-container">
                            <div class="product-card">
                                <img src="{{ asset('images/filter3.jpg') }}" alt="necklace filter" style="width: 98%; height: 60%; object-fit: cover; border-radius: 2px;">
                                <b><p>Toned Lightweight Gold Necklace</p></b>
                                <p> Gold Purity: 22 KT, Metal Color: Yellow,Item Gross Wt (gm): 18.248,Item Net Wt (gm): 18.248</p>                         
                                <p>Price: 300</p>
                                <a href="{{ route('AR_Products.filter3') }}">View in Augmented Reality</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            <div class="row">
                <div class="col-lg-12 col-xl-12 m-lr-auto m-b-50">
                    <div class="m-l-1 m-r--3 m-lr-0-xl">
                        <h4> View 3D Productes:</h4><br/>
                        @if($products->isEmpty())
                            <p>No products available at the moment.</p>
                        @else
                            <div class="product-container">
                                @foreach ($products as $product)
                                    <div class="product-card" style="margin: 50px">
                                        <img src="{{ asset('storage/' . $product->image_url) }}" alt="Image of {{ $product->name }}" style="width: 100%; height: 62%; object-fit: cover; border-radius: 2px;">
                                    <b><p>{{ $product->name }}</p></b>
                                        <p>{{ $product->description }}</p>
                                        <p>Price: ${{ $product->price }}</p>
                                        <a href="{{ route('AR_Products.view', $product->id) }}">View in Augmented Reality</a>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>




    </div>

</body>
</html>


@endsection
