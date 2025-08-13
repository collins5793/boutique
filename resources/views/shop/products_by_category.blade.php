@extends('layouts.apli')

@section('content')
<style>
/* ====== Styles cartes produits ====== */
.product-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
}
.product-card {
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    overflow: hidden;
    width: 220px;
    cursor: pointer;
    transition: transform 0.2s ease;
}
.product-card:hover {
    transform: translateY(-5px);
}
.product-card img {
    width: 100%;
    height: 180px;
    object-fit: cover;
}
.product-card .info {
    padding: 10px;
}
.product-card .price {
    font-weight: bold;
    color: #27ae60;
}
.product-card .old-price {
    text-decoration: line-through;
    color: #888;
    font-size: 0.9em;
    margin-left: 5px;
}

/* ====== Modal personnalisé ====== */
.modal-custom {
    display: none;
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: rgba(0,0,0,0.6);
    justify-content: center;
    align-items: center;
    z-index: 1000;
}
.modal-content-custom {
    background: white;
    padding: 20px;
    border-radius: 10px;
    max-width: 800px;
    width: 90%;
    position: relative;
}
.close-btn {
    position: absolute;
    top: 10px; right: 15px;
    font-size: 1.5em;
    cursor: pointer;
    color: #333;
}

/* ====== Carousel ====== */
.carousel-container {
    position: relative;
    overflow: hidden;
}
.carousel-track {
    display: flex;
    gap: 10px;
    transition: transform 0.4s ease;
}
.carousel-slide {
    min-width: 70%;
    flex-shrink: 0;
    border-radius: 8px;
    overflow: hidden;
    cursor: pointer;
}
.carousel-slide img {
    width: 100%;
    height: 300px;
    object-fit: cover;
}
.carousel-btn {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(255,255,255,0.7);
    border: none;
    padding: 8px;
    cursor: pointer;
    font-size: 1.5em;
    border-radius: 50%;
}
.carousel-btn.prev { left: 10px; }
.carousel-btn.next { right: 10px; }
</style>

<div class="container">
    @foreach($categories as $category)
        <h2>{{ $category->name }}</h2>
        <div class="product-grid">
            @foreach($category->products as $product)
                <div class="product-card" onclick='showProductDetails(@json($product))'>
                    <img src="/storage/{{ $product->image }}" alt="{{ $product->name }}">
                    <div class="info">
                        <h4>{{ $product->name }}</h4>
                        @if($product->discount_price)
                            <span class="price">{{ number_format($product->discount_price, 0, ',', ' ') }} FCFA</span>
                            <span class="old-price">{{ number_format($product->price, 0, ',', ' ') }} FCFA</span>
                        @else
                            <span class="price">{{ number_format($product->price, 0, ',', ' ') }} FCFA</span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endforeach
</div>

{{-- Modal --}}
<div id="productModal" class="modal-custom">
    <div class="modal-content-custom">
        <span class="close-btn" onclick="closeModal()">×</span>
        <h3 id="modalProductName"></h3>
        
        <div class="carousel-container">
            <button class="carousel-btn prev" onclick="moveSlide(-1)">‹</button>
            <div class="carousel-track" id="carouselTrack"></div>
            <button class="carousel-btn next" onclick="moveSlide(1)">›</button>
        </div>

        <p id="modalProductDesc"></p>
        <p><strong>Prix :</strong> <span id="modalProductPrice"></span></p>
        <button style="background:#27ae60;color:white;padding:10px 15px;border:none;border-radius:5px;cursor:pointer;">🛒 Ajouter au panier</button>
    </div>
</div>

<script>
let currentSlide = 0;

function showProductDetails(product) {
    document.getElementById('modalProductName').textContent = product.name;
    document.getElementById('modalProductDesc').textContent = product.description ?? '';

    if (product.discount_price) {
        document.getElementById('modalProductPrice').innerHTML = 
            `<span style="text-decoration:line-through;color:#888;">${parseInt(product.price).toLocaleString()} FCFA</span> 
             <span style="color:#27ae60;font-weight:bold;">${parseInt(product.discount_price).toLocaleString()} FCFA</span>`;
    } else {
        document.getElementById('modalProductPrice').innerHTML = 
            `<span style="color:#27ae60;font-weight:bold;">${parseInt(product.price).toLocaleString()} FCFA</span>`;
    }

    let gallery = JSON.parse(product.gallery || '[]');
    gallery.unshift(product.image);
    let track = document.getElementById('carouselTrack');
    track.innerHTML = '';
    gallery.forEach(img => {
        let slide = document.createElement('div');
        slide.classList.add('carousel-slide');
        slide.innerHTML = `<img src="/storage/${img}" onclick="zoomImage('/storage/${img}')">`;
        track.appendChild(slide);
    });

    currentSlide = 0;
    updateCarousel();

    document.getElementById('productModal').style.display = 'flex';
}

function closeModal() {
    document.getElementById('productModal').style.display = 'none';
}

function moveSlide(direction) {
    let slides = document.querySelectorAll('.carousel-slide');
    currentSlide = (currentSlide + direction + slides.length) % slides.length;
    updateCarousel();
}

function updateCarousel() {
    let track = document.getElementById('carouselTrack');
    track.style.transform = `translateX(-${currentSlide * 75}%)`; // 70% + gap
}

function zoomImage(src) {
    window.open(src, '_blank');
}
</script>
@endsection
