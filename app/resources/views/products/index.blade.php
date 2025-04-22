<div>
    @if ($products->isEmpty())
        <p>No Results .. </p>
    @else
        @foreach($products as $product)
            <div>
                <h3>{{ $product->name }}</h3>
                <p>{{ $product->description }}</p>
                @foreach($product->images as $image)
                    <img src="{{ asset('storage/'.$image->image_url) }}" alt="{{ $product->name }}">
                @endforeach
            </div>
        @endforeach
    @endif
</div>

