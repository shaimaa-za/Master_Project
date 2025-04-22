@extends('Home.layout.master')
@section('content')


        @if(session('success'))
            <p style="color: green;">{{ session('success') }}</p>
        @endif

        <div style="display: flex; justify-content: center; align-items: center; height: 100vh; background-color: #f2f2f2;">
            <form action="{{ route('product.uploadImages') }}" method="POST" enctype="multipart/form-data" 
                  style="width: 90%; max-width: 450px; padding: 36px; border: 1px solid #ddd; border-radius: 10px; 
                         background-color: #fff; box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1); text-align: center;">
                
                @csrf
                
                <!-- عنوان منسق -->
                <h2 style="margin-bottom: 20px; font-size: 24px; font-weight: bold; color: #333;">Upload Product Images</h2>
        
                <label for="product_id" style="display: block; text-align:left; font-size: 18px; font-weight: bold; margin-bottom: 8px;">Product ID:</label>
                <input type="number" name="product_id" required
                       style="width: 100%; padding: 12px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 5px; font-size: 16px;">
        
                <label for="images" style="display: block; text-align:left; font-size: 18px; font-weight: bold; margin-bottom: 8px;">Select Images:</label>
                <input type="file" name="images[]" multiple required
                       style="width: 100%; padding: 12px; margin-bottom: 20px; border: 1px solid #ccc; border-radius: 5px; font-size: 16px;">
        
                <button type="submit"
                        style="background-color: #007bff; color: white; font-size: 18px; padding: 12px 20px; border: none; 
                               border-radius: 5px; cursor: pointer; transition: 0.3s; width: 100%;">
                    Upload Images
                </button>
            </form>
        </div>
        
  

@endsection