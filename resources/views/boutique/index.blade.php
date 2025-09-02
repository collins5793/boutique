@extends('layouts.apli')

@section('title', 'Boutique')

@section('content')

<div class="products-container">
    <!-- En-tête moderne avec animation -->
    <div class="header-section text-center mb-5">
        <h1 class="section-title">
            <span class="title-decoration">🛍️</span>
            Bienvenue dans notre Boutique
            <span class="title-decoration">✨</span>
        </h1>
        <p class="section-subtitle">Découvrez nos meilleures offres et nouveautés</p>
        <div class="title-underline"></div>
    </div>

    <!-- Barre de filtres modernisée -->
    <div class="filters-section">
        <div class="container">
            <div class="filters-bar">
                <form id="searchForm" class="search-form" method="GET" action="{{ route('client.shop') }}">
                    <input type="text" id="searchInput" name="search" placeholder="🔍 Rechercher un produit..." value="{{ request('search') }}">
                    <button type="submit" class="search-btn">
                        <span>Rechercher</span>
                        <div class="btn-shine"></div>
                    </button>
                </form>

                <select id="sortSelect" onchange="applySorting()" class="sort-select">
                    <option value="">📂 Trier par catégorie</option>
                    @foreach($categories as $category)
                        <option value="category_{{ $category->id }}" 
                            {{ request('sort') == 'category_'.$category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="container py-5">
        @if($products->count() > 0)
            <!-- Info résultats -->
            <div class="results-info">
                <p>{{ $products->total() }} produit(s) trouvé(s)</p>
            </div>
            
            <!-- Grille de produits moderne -->
            <div class="products-grid">
                @foreach($products as $product)
                @php
                    // Calcul du pourcentage de réduction
                    $discountPercent = 0;
                    if($product->discount_price){
                        $discountPercent = round(($product->price - $product->discount_price) / $product->price * 100);
                    }

                    // Définir le nombre d'étoiles selon la règle
                    if($discountPercent < 5 && $discountPercent > 0){
                        $stars = 3;
                    } elseif($discountPercent >= 5 && $discountPercent <= 20){
                        $stars = 4;
                    } elseif($discountPercent > 20){
                        $stars = 5;
                    } else {
                        $stars = 4;
                    }
                @endphp

                <div class="product-item">
                    <div class="product-card" data-product-id="{{ $product->id }}">
                        <!-- Badges de réduction modernisés -->
                        @if($product->discount_price)
                        <div class="discount-badge">
                            -{{ $discountPercent }}%
                        </div>
                        @endif

                        @if($product->discounts->count() > 0)
                        <div class="bulk-discount-badge" title="@foreach($product->discounts as $d) À partir de {{ $d->min_quantity }}: {{ number_format($d->price,0,',',' ') }} FCFA @if(!$loop->last), @endif @endforeach">
                            📦 Réductions quantité
                        </div>
                        @endif

                        @if($product->created_at->diffInDays(now()) < 30)
                        <div class="new-badge">
                            🆕 Nouveau
                        </div>
                        @endif

                        @if($stars == 5)
                        <div class="bestseller-badge">
                            ⭐ Best-seller
                        </div>
                        @endif
                        
                        <!-- Image produit avec overlay -->
                        <a href="javascript:void(0)" onclick='showProductDetails(@json($product))' class="product-link">
                            <div class="product-image-container">
                                <div class="product-image" 
                                     style="background-image: url('{{ $product->image ? asset('storage/'.$product->image) : 'https://via.placeholder.com/400x300/6c63ff/white?text=Produit' }}');">
                                </div>
                                <div class="image-overlay">
                                    <div class="overlay-icon">👁️</div>
                                    <div class="overlay-text">Voir détails</div>
                                </div>
                            </div>
                        </a>

                        <!-- Actions produit -->
                        <div class="product-actions">
                            <button class="action-btn wishlist-btn" data-product-id="{{ $product->id }}">
                                ♥
                            </button>
                            <button class="action-btn quick-view-btn" onclick='showProductDetails(@json($product)); event.stopPropagation();'>
                                👁
                            </button>
                        </div>
                        
                        <!-- Contenu produit -->
                        <div class="product-content">
                            <div class="product-info">
                                <h5 class="product-name">{{ $product->name }}</h5>
                                <p class="product-description">{{ \Illuminate\Support\Str::limit(strip_tags($product->description), 100, '...') }}</p>
                                
                                <!-- Étoiles modernisées -->
                                <div class="product-rating">
                                    <div class="stars">
                                        @for($i = 1; $i <= 5; $i++)
                                            <span class="star {{ $i <= $stars ? 'filled' : '' }}">⭐</span>
                                        @endfor
                                    </div>
                                </div>
                                
                                <!-- Prix modernisé -->
                                <div class="product-price-container">
                                    @if($product->discount_price)
                                        <span class="old-price">{{ number_format($product->price,0,',',' ') }} F</span>
                                        <span class="current-price">{{ number_format($product->discount_price,0,',',' ') }} F</span>
                                    @else
                                        <span class="current-price">{{ number_format($product->price,0,',',' ') }} F</span>
                                    @endif

                                    {{-- Réductions par quantité --}}
                                    @if($product->discounts->count() > 0)
                                        <div class="bulk-discount-info">
                                            @foreach($product->discounts as $d)
                                                <small>À partir de {{ $d->min_quantity }}: {{ number_format($d->price, 0, ',', ' ') }} F</small>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Bouton commander modernisé -->
                            <div class="product-action">
                                <button class="btn-order" 
                                        data-product='@json($product)' 
                                        onclick="openQuantityModal(JSON.parse(this.dataset.product)); event.stopPropagation();">
                                    <span class="btn-icon">🛒</span>
                                    <span class="btn-text">Commander</span>
                                    <div class="btn-shine"></div>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination modernisée -->
            <div class="pagination-container">
                {{ $products->appends(request()->query())->links() }}
            </div>
        @else
            <div class="no-products">
                <div class="no-products-icon">😔</div>
                <p>Aucun produit ne correspond à votre recherche.</p>
                <button onclick="resetFilters()" class="reset-btn">🔄 Réinitialiser les filtres</button>
            </div>
        @endif
    </div>
</div>

<style>
/* Variables CSS */
:root {
    --primary-gradient: linear-gradient(135deg, #f506c4 0%, #c0049b 100%);
    --secondary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --accent-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    --shadow-light: 0 8px 32px rgba(0, 0, 0, 0.1);
    --shadow-medium: 0 12px 40px rgba(0, 0, 0, 0.15);
    --shadow-heavy: 0 20px 60px rgba(0, 0, 0, 0.2);
    --border-radius: 20px;
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    --primary: #f506c4;
    --primary-light: #ff33d1;
    --primary-dark: #c0049b;
    --dark: #1e293b;
    --dark-light: #334155;
    --light: #ffffff;
}

/* Conteneur principal */
.products-container {
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    min-height: 100vh;
    position: relative;
}

.products-container::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: 
        radial-gradient(circle at 20% 80%, rgba(245, 6, 196, 0.15) 0%, transparent 50%),
        radial-gradient(circle at 80% 20%, rgba(102, 126, 234, 0.15) 0%, transparent 50%);
    pointer-events: none;
}

/* En-tête modernisé */
.header-section {
    position: relative;
    z-index: 2;
    padding: 3rem 1.5rem 2rem;
}

.section-title {
    font-size: 3.5rem;
    font-weight: 800;
    background: var(--primary-gradient);
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
    margin-bottom: 20px;
    text-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.title-decoration {
    animation: bounce 2s infinite;
    display: inline-block;
}

.title-decoration:nth-child(3) {
    animation-delay: 0.5s;
}

.section-subtitle {
    font-size: 1.3rem;
    color: var(--dark-light);
    margin-bottom: 2rem;
    opacity: 0.8;
}

.title-underline {
    width: 100px;
    height: 4px;
    background: var(--accent-gradient);
    margin: 0 auto;
    border-radius: 2px;
    animation: pulse 2s infinite;
}

/* Section filtres */
.filters-section {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    box-shadow: var(--shadow-light);
    margin-bottom: 2rem;
    position: relative;
    z-index: 2;
}

.filters-bar {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 2rem;
    padding: 1.5rem;
    flex-wrap: wrap;
}

.search-form {
    display: flex;
    flex: 1;
    min-width: 300px;
    max-width: 500px;
    position: relative;
}

.search-form input {
    flex: 1;
    padding: 1rem 1.5rem;
    border: 2px solid rgba(245, 6, 196, 0.2);
    border-radius: 50px 0 0 50px;
    font-size: 1rem;
    outline: none;
    background: rgba(255, 255, 255, 0.9);
    transition: var(--transition);
}

.search-form input:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(245, 6, 196, 0.1);
}

.search-btn {
    padding: 1rem 2rem;
    background: var(--primary-gradient);
    color: white;
    border: none;
    border-radius: 0 50px 50px 0;
    cursor: pointer;
    font-weight: 600;
    position: relative;
    overflow: hidden;
    transition: var(--transition);
}

.search-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
    transition: var(--transition);
}

.search-btn:hover::before {
    left: 100%;
}

.sort-select {
    padding: 1rem 1.5rem;
    border: 2px solid rgba(245, 6, 196, 0.2);
    border-radius: 50px;
    font-size: 1rem;
    background: rgba(255, 255, 255, 0.9);
    color: var(--dark);
    cursor: pointer;
    min-width: 200px;
    transition: var(--transition);
}

.sort-select:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(245, 6, 196, 0.1);
}

/* Info résultats */
.results-info {
    margin-bottom: 2rem;
    text-align: center;
    position: relative;
    z-index: 2;
}

.results-info p {
    color: var(--dark-light);
    font-size: 1.1rem;
    font-weight: 500;
}

/* Grille de produits */
.products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 2rem;
    position: relative;
    z-index: 2;
    margin-bottom: 3rem;
}

/* Élément produit */
.product-item {
    animation: fadeInUp 0.6s ease-out;
}

.product-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: var(--border-radius);
    overflow: hidden;
    box-shadow: var(--shadow-light);
    transition: var(--transition);
    height: 100%;
    display: flex;
    flex-direction: column;
    position: relative;
}

.product-card:hover {
    transform: translateY(-15px) rotateX(2deg);
    box-shadow: var(--shadow-heavy);
    border-color: rgba(255, 255, 255, 0.4);
}

/* Badges modernisés */
.discount-badge, .bulk-discount-badge, .new-badge, .bestseller-badge {
    position: absolute;
    top: 15px;
    padding: 8px 15px;
    border-radius: 25px;
    font-size: 0.8rem;
    font-weight: 700;
    z-index: 3;
    box-shadow: var(--shadow-light);
    animation: pulse 2s infinite;
}

.discount-badge {
    right: 15px;
    background: var(--accent-gradient);
    color: white;
}

.bulk-discount-badge {
    right: 15px;
    top: 55px;
    background: var(--secondary-gradient);
    color: white;
    font-size: 0.7rem;
}

.new-badge {
    left: 15px;
    background: var(--success-gradient);
    color: white;
}

.bestseller-badge {
    left: 15px;
    top: 55px;
    background: var(--primary-gradient);
    color: white;
}

/* Image produit */
.product-image-container {
    position: relative;
    overflow: hidden;
    border-radius: var(--border-radius) var(--border-radius) 0 0;
}

.product-image {
    width: 100%;
    height: 280px;
    background-size: cover;
    background-position: center;
    transition: var(--transition);
    position: relative;
}

.product-link:hover .product-image {
    transform: scale(1.1);
}

/* Overlay sur l'image */
.image-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(245, 6, 196, 0.9), rgba(192, 4, 155, 0.9));
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    opacity: 0;
    transition: var(--transition);
    color: white;
}

.product-link:hover .image-overlay {
    opacity: 1;
}

.overlay-icon {
    font-size: 3rem;
    margin-bottom: 15px;
    animation: bounceIn 0.6s ease;
}

.overlay-text {
    font-size: 1.2rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 2px;
}

/* Actions produit */
.product-actions {
    position: absolute;
    top: 15px;
    left: 15px;
    display: flex;
    flex-direction: column;
    gap: 10px;
    opacity: 0;
    transform: translateX(-10px);
    transition: var(--transition);
    z-index: 2;
}

.product-card:hover .product-actions {
    opacity: 1;
    transform: translateX(0);
}

.action-btn {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    border: none;
    background: rgba(255, 255, 255, 0.9);
    box-shadow: var(--shadow-light);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: var(--transition);
    font-size: 1.2rem;
}

.action-btn:hover {
    background: var(--primary);
    color: white;
    transform: scale(1.1);
}

/* Contenu produit */
.product-content {
    padding: 1.5rem;
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.product-name {
    font-size: 1.3rem;
    font-weight: 700;
    color: var(--dark);
    margin-bottom: 10px;
    line-height: 1.4;
}

.product-description {
    color: var(--dark-light);
    font-size: 0.9rem;
    margin-bottom: 15px;
    line-height: 1.5;
    flex-grow: 1;
}

/* Étoiles */
.product-rating {
    margin-bottom: 15px;
}

.stars {
    display: flex;
    gap: 3px;
}

.star {
    font-size: 1rem;
    filter: grayscale(100%);
    transition: var(--transition);
}

.star.filled {
    filter: grayscale(0%);
    animation: twinkle 2s infinite;
}

/* Prix */
.product-price-container {
    margin-bottom: 20px;
}

.old-price {
    text-decoration: line-through;
    color: #95a5a6;
    font-size: 1rem;
    margin-right: 10px;
}

.current-price {
    color: var(--primary-dark);
    font-size: 1.4rem;
    font-weight: 800;
    background: var(--primary-gradient);
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
}

.bulk-discount-info {
    margin-top: 8px;
}

.bulk-discount-info small {
    display: block;
    color: var(--dark-light);
    font-size: 0.8rem;
    line-height: 1.3;
}

/* Bouton commander */
.btn-order {
    background: var(--accent-gradient);
    border: none;
    border-radius: 50px;
    color: white;
    padding: 15px 25px;
    font-size: 1rem;
    font-weight: 700;
    text-decoration: none;
    text-align: center;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    position: relative;
    overflow: hidden;
    transition: var(--transition);
    box-shadow: var(--shadow-light);
    text-transform: uppercase;
    letter-spacing: 1px;
    cursor: pointer;
}

.btn-order::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
    transition: var(--transition);
}

.btn-order:hover {
    transform: translateY(-3px);
    box-shadow: var(--shadow-medium);
    color: white;
    text-decoration: none;
}

.btn-order:hover::before {
    left: 100%;
}

.btn-icon {
    font-size: 1.2rem;
    animation: bounce 2s infinite;
}

/* Pagination */
.pagination-container {
    display: flex;
    justify-content: center;
    margin-top: 3rem;
    position: relative;
    z-index: 2;
}

/* Aucun produit */
.no-products {
    text-align: center;
    padding: 4rem;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-light);
    position: relative;
    z-index: 2;
}

.no-products-icon {
    font-size: 4rem;
    margin-bottom: 1.5rem;
}

.no-products p {
    margin-bottom: 2rem;
    color: var(--dark-light);
    font-size: 1.2rem;
}

.reset-btn {
    background: var(--primary-gradient);
    color: white;
    border: none;
    padding: 1rem 2rem;
    border-radius: 50px;
    font-weight: 700;
    cursor: pointer;
    transition: var(--transition);
    font-size: 1rem;
}

.reset-btn:hover {
    transform: translateY(-3px);
    box-shadow: var(--shadow-medium);
}

/* Modals */
.modal-custom {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.8);
    z-index: 1000;
    align-items: center;
    justify-content: center;
    padding: 1rem;
    overflow-y: auto;
    backdrop-filter: blur(5px);
}

.modal-content-custom {
    background: white;
    border-radius: var(--border-radius);
    width: 100%;
    max-width: 900px;
    max-height: 90vh;
    overflow-y: auto;
    position: relative;
    box-shadow: var(--shadow-heavy);
    animation: modalFadeIn 0.4s ease;
}

.close-btn {
    position: absolute;
    top: 1rem;
    right: 1rem;
    font-size: 2rem;
    color: var(--dark-light);
    cursor: pointer;
    z-index: 10;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    transition: var(--transition);
    background: none;
    border: none;
}

.close-btn:hover {
    background: rgba(245, 6, 196, 0.1);
    color: var(--primary);
    transform: rotate(90deg);
}

/* Animations */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes bounce {
    0%, 20%, 50%, 80%, 100% {
        transform: translateY(0);
    }
    40% {
        transform: translateY(-10px);
    }
    60% {
        transform: translateY(-5px);
    }
}

@keyframes bounceIn {
    0% {
        opacity: 0;
        transform: scale(0.3);
    }
    50% {
        opacity: 1;
        transform: scale(1.05);
    }
    70% {
        transform: scale(0.9);
    }
    100% {
        opacity: 1;
        transform: scale(1);
    }
}

@keyframes pulse {
    0% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.05);
    }
    100% {
        transform: scale(1);
    }
}

@keyframes twinkle {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.7;
    }
}

@keyframes modalFadeIn {
    from { 
        opacity: 0; 
        transform: translateY(-20px) scale(0.95); 
    }
    to { 
        opacity: 1; 
        transform: translateY(0) scale(1); 
    }
}

</style>

{{-- Modal 1 : Détails produit avec variantes --}}
<div id="productModal" class="modal-custom">
    <div class="modal-content-custom">
        <span class="close-btn" onclick="closeModal('productModal')">×</span>
        <h2 id="modalProductName"></h2>
        
        <div class="carousel-container">
            <button class="carousel-btn prev" onclick="moveSlide(-1)">‹</button>
            <div class="carousel-track" id="carouselTrack"></div>
            <button class="carousel-btn next" onclick="moveSlide(1)">›</button>
        </div>
        
        <div class="modal-product-info">
            <p id="modalProductDesc"></p>
            <div class="modal-price-container">
                <strong>Prix :</strong> <span id="modalProductPrice"></span>
            </div>
            
            <div class="variants-container" id="modalVariants"></div>
            <div class="modal-reduction">
                <strong>Réduction :</strong> <span id="modalProductreduction"></span>
            </div>
            
            <button class="add-to-cart-btn" onclick="openQuantityModal(currentProduct)">🛒 Ajouter au panier</button>
        </div>
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
            <div class="quantity-controls">
                <button onclick="decreaseQuantity()">-</button>
                <input type="number" id="productQuantity" min="1" value="1" onchange="updateTotalPrice()">
                <button onclick="increaseQuantity()">+</button>
            </div>
        </div>
        
        <div class="total-price-container">
            <strong>Total: </strong><span class="total-price" id="totalPrice"></span> FCFA
        </div>
        
        <button class="confirm-add-btn" onclick="addToCart()">🛒 Confirmer l'ajout</button>
    </div>
</div>
<script>
let currentSlide = 0;
let currentProduct = null;
let selectedVariant = null;

// Fonctions pour les modals
function showProductDetails(product) {
    currentProduct = product;
    selectedVariant = null;
    document.getElementById('modalProductName').textContent = product.name;
    document.getElementById('modalProductDesc').textContent = product.description ?? 'Aucune description disponible';

    // Prix
    let priceHtml = '';
    if (product.discount_price) {
        priceHtml = `<span style="text-decoration:line-through;color:#888;">${parseInt(product.price).toLocaleString()} FCFA</span> 
                     <span style="color:#27ae60;font-weight:bold;">${parseInt(product.discount_price).toLocaleString()} FCFA</span>`;
    } else {
        priceHtml = `<span style="color:#27ae60;font-weight:bold;">${parseInt(product.price).toLocaleString()} FCFA</span>`;
    }
    document.getElementById('modalProductPrice').innerHTML = priceHtml;
    
    let reductionHtml = '';

    if (product.discounts && product.discounts.length > 0) {
        reductionHtml = '<ul class="bulk-discount-list">';
        
        product.discounts.forEach(d => {
            reductionHtml += `<li>À partir de ${d.min_quantity} : ${Number(d.price).toLocaleString('fr-FR')} FCFA</li>`;
        });
        
        reductionHtml += '</ul>';
    }

    document.getElementById('modalProductreduction').innerHTML = reductionHtml;


    // Galerie
    let gallery = [];
    try {
        gallery = JSON.parse(product.gallery || '[]');
    } catch (e) {
        console.error("Erreur parsing gallery:", e);
    }
    
    // Toujours inclure l'image principale
    if (product.image) {
        gallery.unshift(product.image);
    }
    
    let track = document.getElementById('carouselTrack');
    track.innerHTML = '';
    
    if (gallery.length === 0) {
        track.innerHTML = '<div class="carousel-slide"><img src="/placeholder-product.jpg" alt="Image non disponible"></div>';
    } else {
        gallery.forEach(img => {
            let slide = document.createElement('div');
            slide.classList.add('carousel-slide');
            slide.innerHTML = `<img src="/storage/${img}" alt="${product.name}">`;
            track.appendChild(slide);
        });
    }

    // Variantes
    let variantsDiv = document.getElementById('modalVariants');
    variantsDiv.innerHTML = '';
    
    if (product.variants && product.variants.length > 0) {
        product.variants.forEach(v => {
            let div = document.createElement('div');
            div.classList.add('variant-item');
            div.textContent = `${v.attribute_name}: ${v.attribute_value}`;
            div.onclick = function() {
                document.querySelectorAll('#modalVariants .variant-item').forEach(el => el.classList.remove('selected'));
                div.classList.add('selected');
                selectedVariant = v;
            };
            variantsDiv.appendChild(div);
        });
    } else {
        variantsDiv.innerHTML = '<p>Aucune variante disponible pour ce produit.</p>';
    }

    currentSlide = 0;
    updateCarousel();
    document.getElementById('productModal').style.display = 'flex';
}

function closeModal(modalId) { 
    document.getElementById(modalId).style.display = 'none'; 
}

function moveSlide(direction) { 
    let slides = document.querySelectorAll('.carousel-slide'); 
    if (slides.length === 0) return;
    
    currentSlide = (currentSlide + direction + slides.length) % slides.length; 
    updateCarousel(); 
}

function updateCarousel() { 
    let track = document.getElementById('carouselTrack'); 
    if (!track) return;
    
    track.style.transform = `translateX(-${currentSlide * 100}%)`; 
}

// Modal de quantité
function openQuantityModal(product) {
    if (product) {
        currentProduct = product;
    }
    
    if (!currentProduct) return;
    
    document.getElementById('qtyModalProductName').textContent = currentProduct.name;

    // Variantes
    let variantsDiv = document.getElementById('qtyModalVariants');
    variantsDiv.innerHTML = '';
    selectedVariant = null;
    
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
        variantsDiv.innerHTML = '<p>Aucune variante disponible pour ce produit.</p>';
    }

    document.getElementById('productQuantity').value = 1;
    updateTotalPrice();
    
    // Fermer le modal de détail si ouvert
    closeModal('productModal');
    
    // Ouvrir le modal de quantité
    document.getElementById('quantityModal').style.display = 'flex';
}

function increaseQuantity() {
    let qtyInput = document.getElementById('productQuantity');
    qtyInput.value = parseInt(qtyInput.value) + 1;
    updateTotalPrice();
}

function decreaseQuantity() {
    let qtyInput = document.getElementById('productQuantity');
    if (parseInt(qtyInput.value) > 1) {
        qtyInput.value = parseInt(qtyInput.value) - 1;
        updateTotalPrice();
    }
}

// Calcul dynamique du prix
function updateTotalPrice() {
    let qty = parseInt(document.getElementById('productQuantity').value) || 1;
    let unitPrice = currentProduct.price; // prix par défaut

    // Vérifier si le produit a des réductions
    if (currentProduct.discounts && currentProduct.discounts.length > 0) {
        // Trier les réductions par min_quantity décroissante pour prendre la plus grande applicable
        let applicableDiscount = currentProduct.discounts
            .filter(d => qty >= d.min_quantity)
            .sort((a, b) => b.min_quantity - a.min_quantity)[0];

        if (applicableDiscount) {
            unitPrice = applicableDiscount.price;
        } else if (currentProduct.discount_price) {
            // Si aucune réduction par quantité, mais il y a un discount global
            unitPrice = currentProduct.discount_price;
        }
    } else if (currentProduct.discount_price) {
        // Produit avec juste un discount global
        unitPrice = currentProduct.discount_price;
    }

    let total = qty * unitPrice;
    document.getElementById('totalPrice').textContent = total.toLocaleString('fr-FR');
}

// Ajouter au panier
async function addToCart() {
    try {
        // lecture des valeurs
        const quantity = parseInt(document.getElementById('productQuantity').value) || 1;
        const variantId = selectedVariant ? selectedVariant.id : null;

        if (!currentProduct || !currentProduct.id) {
            throw new Error("Produit invalide.");
        }

        // Récupère le token CSRF depuis la meta si présente, sinon fallback blade
        const meta = document.querySelector('meta[name="csrf-token"]');
        const csrfToken = meta ? meta.getAttribute('content') : ('{{ csrf_token() }}' ?? '');

        // Désactiver le bouton de confirmation pour éviter double clic
        const confirmBtn = document.querySelector('.confirm-add-btn');
        if (confirmBtn) confirmBtn.disabled = true;

        const res = await fetch('/cart/add', {
            method: 'POST',
            credentials: 'same-origin', // envoie les cookies pour rester authentifié
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                product_id: currentProduct.id,
                variant_id: variantId,
                quantity: quantity
            })
        });

        const contentType = res.headers.get('content-type') || '';

        // Si la réponse n'est pas JSON (possible redirection vers login), on recharge la page
        if (!contentType.includes('application/json')) {
            // remet le bouton actif avant reload
            if (confirmBtn) confirmBtn.disabled = false;
            window.location.reload();
            return;
        }

        const data = await res.json();

        // Si code HTTP indique une erreur
        if (!res.ok) {
            // cas 401 (non auth)
            if (res.status === 401) {
                // message provenant du backend
                alert(data.message || 'Vous devez être connecté pour ajouter au panier.');
                // option : rediriger vers la page de login si nécessaire
                // window.location.href = '/login';
                if (confirmBtn) confirmBtn.disabled = false;
                return;
            }

            // autre erreur
            throw new Error(data.message || `Erreur serveur (${res.status})`);
        }

        // Succès (200)
        alert(data.message || 'Produit ajouté au panier.');
        closeModal('quantityModal');

        // Mettre à jour compteur / mini-cart si fonction dispo
        if (typeof updateCartCounter === 'function') {
            try { updateCartCounter(); } catch (e) { console.warn(e); }
        }

        // réactiver bouton
        if (confirmBtn) confirmBtn.disabled = false;

    } catch (err) {
        console.error('addToCart error:', err);
        // réactiver bouton en cas d'erreur
        const confirmBtn = document.querySelector('.confirm-add-btn');
        if (confirmBtn) confirmBtn.disabled = false;
        alert(err.message || "Une erreur s'est produite. Veuillez réessayer.");
    }
}

// Filtres et recherche


function updatePriceValue(value) {
    document.getElementById('priceValue').textContent = parseInt(value).toLocaleString() + ' FCFA';
}

function resetFilters() {
    window.location.href = '{{ url()->current() }}';
}

// Initialisation

</script>

<script>
function applySorting() {
    const sortValue = document.getElementById('sortSelect').value;
    const searchValue = document.getElementById('searchInput').value;
    const url = new URL(window.location.href);
    url.searchParams.set('sort', sortValue);
    url.searchParams.set('search', searchValue);
    window.location.href = url.toString();
}
</script>
@endsection