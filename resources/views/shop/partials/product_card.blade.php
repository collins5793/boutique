<div class="col-md-3 mb-4">
    <div class="card h-100 shadow-sm">
        <img src="{{ asset('storage/' . $product->image) }}" 
             class="card-img-top" 
             alt="{{ $product->name }}" 
             style="cursor:pointer;" 
             onclick='showProductDetails(@json($product))'>
        
        <div class="card-body text-center">
            <h5 class="card-title">{{ $product->name }}</h5>

            @if($product->discount_price)
                <p class="mb-1">
                    <span class="text-muted text-decoration-line-through">
                        {{ number_format($product->price, 0, ',', ' ') }} FCFA
                    </span>
                </p>
                <p class="text-success fw-bold">
                    {{ number_format($product->discount_price, 0, ',', ' ') }} FCFA
                </p>
            @else
                <p class="text-success fw-bold">
                    {{ number_format($product->price, 0, ',', ' ') }} FCFA
                </p>
            @endif

            <button class="btn btn-primary btn-sm" onclick='showProductDetails(@json($product))'>
                Voir détails
            </button>
            <button class="btn btn-warning btn-sm">🛒</button>
        </div>
    </div>
</div>
