@extends('layouts.apli')

@section('content')
<style>
/* ====== Styles cartes produits ====== */
.product-grid { display: flex; flex-wrap: wrap; gap: 20px; }
.product-card { background: #fff; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); overflow: hidden; width: 220px; cursor: pointer; transition: transform 0.2s ease; }
.product-card:hover { transform: translateY(-5px); }
.product-card img { width: 100%; height: 180px; object-fit: cover; }
.product-card .info { padding: 10px; }
.product-card .price { font-weight: bold; color: #27ae60; }
.product-card .old-price { text-decoration: line-through; color: #888; font-size: 0.9em; margin-left: 5px; }

/* ====== Modal ====== */
.modal-custom { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); justify-content: center; align-items: center; z-index: 1000; }
.modal-content-custom { background: white; padding: 20px; border-radius: 10px; max-width: 800px; width: 90%; position: relative; }
.close-btn { position: absolute; top: 10px; right: 15px; font-size: 1.5em; cursor: pointer; color: #333; }

/* ====== Carousel ====== */
.carousel-container { position: relative; overflow: hidden; margin-bottom: 15px; }
.carousel-track { display: flex; gap: 10px; transition: transform 0.4s ease; }
.carousel-slide { min-width: 70%; flex-shrink: 0; border-radius: 8px; overflow: hidden; cursor: pointer; }
.carousel-slide img { width: 100%; height: 300px; object-fit: cover; }
.carousel-btn { position: absolute; top: 50%; transform: translateY(-50%); background: rgba(255,255,255,0.7); border: none; padding: 8px; cursor: pointer; font-size: 1.5em; border-radius: 50%; }
.carousel-btn.prev { left: 10px; }
.carousel-btn.next { right: 10px; }

/* ====== Variantes ====== */
.variants-container { margin: 10px 0; }
.variant-item { display: inline-block; background: #f1f1f1; padding: 5px 10px; margin: 5px; border-radius: 5px; cursor: pointer; }
.variant-item.selected { background: #27ae60; color: white; }

/* ====== Quantité & prix total ====== */
.quantity-container { margin: 10px 0; display: flex; align-items: center; gap: 10px; }
.quantity-container input { width: 60px; padding: 5px; border-radius: 5px; border: 1px solid #ccc; text-align: center; }
.total-price { font-weight: bold; color: #e74c3c; margin-top: 10px; }
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

{{-- Modal 1 : Détails produit avec variantes --}}
<div id="productModal" class="modal-custom">
    <div class="modal-content-custom">
        <span class="close-btn" onclick="closeModal('productModal')">×</span>
        <h3 id="modalProductName"></h3>

        <div class="carousel-container">
            <button class="carousel-btn prev" onclick="moveSlide(-1)">‹</button>
            <div class="carousel-track" id="carouselTrack"></div>
            <button class="carousel-btn next" onclick="moveSlide(1)">›</button>
        </div>

        <p id="modalProductDesc"></p>
        <p><strong>Prix :</strong> <span id="modalProductPrice"></span></p>

        <div class="variants-container" id="modalVariants"></div>

        <button style="background:#27ae60;color:white;padding:10px 15px;border:none;border-radius:5px;cursor:pointer;" onclick="openQuantityModal()">🛒 Ajouter au panier</button>
    </div>
</div>

{{-- Modal 2 : Ajouter quantité avec variante --}}
<div id="quantityModal" class="modal-custom">
    <div class="modal-content-custom">
        <span class="close-btn" onclick="closeModal('quantityModal')">×</span>
        <h3 id="qtyModalProductName"></h3>

        <div class="variants-container" id="qtyModalVariants"></div>

        <div class="quantity-container">
            <label for="productQuantity">Quantité:</label>
            <input type="number" id="productQuantity" min="1" value="1" onchange="updateTotalPrice()">
        </div>

        <p><strong>Total: </strong><span class="total-price" id="totalPrice"></span> FCFA</p>
        <button style="background:#27ae60;color:white;padding:10px 15px;border:none;border-radius:5px;cursor:pointer;" onclick="addToCart()">🛒 Confirmer</button>
    </div>
</div>

<script>
let currentSlide = 0;
let currentProduct = null;
let selectedVariant = null;

// Modal 1
function showProductDetails(product) {
    currentProduct = product;
    selectedVariant = null;
    document.getElementById('modalProductName').textContent = product.name;
    document.getElementById('modalProductDesc').textContent = product.description ?? '';

    // Prix
    let priceHtml = product.discount_price 
        ? `<span style="text-decoration:line-through;color:#888;">${parseInt(product.price).toLocaleString()} FCFA</span> 
           <span style="color:#27ae60;font-weight:bold;">${parseInt(product.discount_price).toLocaleString()} FCFA</span>`
        : `<span style="color:#27ae60;font-weight:bold;">${parseInt(product.price).toLocaleString()} FCFA</span>`;
    document.getElementById('modalProductPrice').innerHTML = priceHtml;

    // Galerie
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

    // Variantes
    let variantsDiv = document.getElementById('modalVariants');
    variantsDiv.innerHTML = '';
    if (product.variants && product.variants.length > 0) {
        product.variants.forEach(v => {
            let div = document.createElement('div');
            div.classList.add('variant-item');
            div.textContent = `${v.attribute_name}: ${v.attribute_value}`;
            variantsDiv.appendChild(div);
        });
    }

    currentSlide = 0;
    updateCarousel();
    document.getElementById('productModal').style.display = 'flex';
}

function closeModal(modalId) { document.getElementById(modalId).style.display = 'none'; }
function moveSlide(direction) { let slides = document.querySelectorAll('.carousel-slide'); currentSlide = (currentSlide + direction + slides.length) % slides.length; updateCarousel(); }
function updateCarousel() { let track = document.getElementById('carouselTrack'); track.style.transform = `translateX(-${currentSlide * 75}%)`; }
function zoomImage(src) { window.open(src, '_blank'); }

// Modal 2
function openQuantityModal() {
    document.getElementById('productModal').style.display = 'none';
    document.getElementById('qtyModalProductName').textContent = currentProduct.name;

    // Variantes
    let variantsDiv = document.getElementById('qtyModalVariants');
    variantsDiv.innerHTML = '';
    if (currentProduct.variants && currentProduct.variants.length > 0) {
        currentProduct.variants.forEach(v => {
            let div = document.createElement('div');
            div.classList.add('variant-item');
            div.textContent = `${v.attribute_name}: ${v.attribute_value}`;
            div.onclick = function() {
                document.querySelectorAll('#qtyModalVariants .variant-item').forEach(el => el.classList.remove('selected'));
                div.classList.add('selected');
                selectedVariant = v;
                updateTotalPrice();
            };
            variantsDiv.appendChild(div);
        });
    } else {
        selectedVariant = null;
    }

    document.getElementById('productQuantity').value = 1;
    updateTotalPrice();
    document.getElementById('quantityModal').style.display = 'flex';
}

// Calcul dynamique du prix
function updateTotalPrice() {
    let qty = parseInt(document.getElementById('productQuantity').value);
    let unitPrice = selectedVariant ? (selectedVariant.price ?? currentProduct.discount_price ?? currentProduct.price) 
                                    : (currentProduct.discount_price ?? currentProduct.price);
    let total = qty * unitPrice;
    document.getElementById('totalPrice').textContent = total.toLocaleString();
}

// Ajouter au panier
function addToCart() {
    let quantity = parseInt(document.getElementById('productQuantity').value);
    let variantId = selectedVariant ? selectedVariant.id : null;

    fetch('/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json', // important
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            product_id: currentProduct.id,
            variant_id: variantId,
            quantity: quantity
        })
    })
    .then(async res => {
        // Si le serveur renvoie autre chose que JSON (ex : HTML login)
        if (!res.headers.get('content-type')?.includes('application/json')) {
            let text = await res.text();
            console.error('Réponse non-JSON :', text);
            alert("Erreur : réponse invalide (peut-être non connecté)");
            return;
        }
        return res.json();
    })
    .then(data => {
        if (!data) return; // déjà géré plus haut
        alert(data.message);
        closeModal('quantityModal');
        if (typeof updateCartCounter === "function") updateCartCounter();
    })
    .catch(err => console.error(err));
}


</script>
@endsection
