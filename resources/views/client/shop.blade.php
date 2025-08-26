@extends('layouts.clients.client')

@section('title', 'Boutique')

@section('content')

<header class="shop-header">
  <div class="banner">
    <h1>🛍 Bienvenue dans notre Boutique</h1>
    <p>Découvrez nos meilleures offres et nouveautés</p>
  </div>

  <div class="filters-bar">
    <form id="searchForm" class="search-form" method="GET" action="{{ route('client.shop') }}">
    <input type="text" id="searchInput" name="search" placeholder="Rechercher un produit..." value="{{ request('search') }}">
    <button type="submit">Rechercher</button>
</form>


    <select id="sortSelect" onchange="applySorting()">
    <option value="">Trier par catégorie</option>
    @foreach($categories as $category)
        <option value="category_{{ $category->id }}" 
            {{ request('sort') == 'category_'.$category->id ? 'selected' : '' }}>
            {{ $category->name }}
        </option>
    @endforeach
</select>

</div>
</header>

<div class="shop-container">
  {{-- Sidebar filtres --}}
  
  </aside>

  {{-- Produits --}}
  <main class="products-section">
    @if($products->count() > 0)
      <div class="results-info">
        <p>{{ $products->total() }} produit(s) trouvé(s)</p>
      </div>
      
      <div class="product-grid">
    @foreach($products as $product)
    @php
        // Calcul du pourcentage de réduction
        $discountPercent = 0;
        if($product->discount_price){
            $discountPercent = round(($product->price - $product->discount_price) / $product->price * 100);
        }

        // Définir le nombre d'étoiles selon la règle
        if($discountPercent < 5 && $discountPercent > 0){
            $stars = 3; // Cas rare
        } elseif($discountPercent >= 5 && $discountPercent <= 20){
            $stars = 4;
        } elseif($discountPercent > 20){
            $stars = 5;
        } else {
            $stars = 4; // produit sans réduction → 4 étoiles par défaut
        }
    @endphp

    <div class="product-card" data-product-id="{{ $product->id }}">
      <div class="product-image-container" onclick='showProductDetails(@json($product))'>
        <div class="product-image">
          <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" loading="lazy">
          <div class="product-badges">
            @if($product->discount_price)
              <span class="badge discount">-{{ $discountPercent }}%</span>
            @endif
            @if($product->created_at->diffInDays(now()) < 30)
              <span class="badge new">Nouveau</span>
            @endif
            @if($stars == 5)
              <span class="badge bestseller">Best-seller</span>
            @endif
          </div>
        </div>
        <div class="product-actions">
          <button class="action-btn wishlist-btn" data-product-id="{{ $product->id }}">♥</button>
          <button class="action-btn quick-view-btn" onclick='showProductDetails(@json($product)); event.stopPropagation();'>👁</button>
        </div>
      </div>
      
      <div class="product-info">
        <h3>{{ $product->name }}</h3>
        <p class="product-description">{{ \Illuminate\Support\Str::limit(strip_tags($product->description), 100, '...') }}</p>
        
        <div class="product-rating">
          <div class="stars">
            @for($i = 1; $i <= 5; $i++)
              <span class="star {{ $i <= $stars ? 'filled' : '' }}">★</span>
            @endfor
          </div>
          <span class="rating-count">({{ $product->reviews_count }})</span>
        </div>
        
        <div class="product-price">
          @if($product->discount_price)
            <span class="current-price">{{ number_format($product->discount_price, 0, ',', ' ') }} FCFA</span>
            <span class="old-price">{{ number_format($product->price, 0, ',', ' ') }} FCFA</span>
          @else
            <span class="current-price">{{ number_format($product->price, 0, ',', ' ') }} FCFA</span>
          @endif
        </div>
      </div>
      
<button class="quick-add-btn" 
        data-product='@json($product)' 
        onclick="openQuantityModal(JSON.parse(this.dataset.product)); event.stopPropagation();">
   🛒 Ajouter
</button>

    </div>
    @endforeach
</div>

      
      <div class="pagination-container">
        {{ $products->appends(request()->query())->links() }}
      </div>
    @else
      <div class="no-products">
        <p>Aucun produit ne correspond à votre recherche.</p>
        <button onclick="resetFilters()">Réinitialiser les filtres</button>
      </div>
    @endif
  </main>
</div>

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

<style>
/* ----------- VARIABLES ----------- */
:root {
    --primary: #f506c4;
    --primary-light: #ff33d1;
    --primary-dark: #c0049b;
    --secondary: #007bff;
    --accent: #ff7b00;
    --dark: #1e293b;
    --dark-light: #334155;
    --light: #ffffff;
    --sidebar-width: 280px;
    --sidebar-collapsed: 85px;
    --header-height: 80px;
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    --radius: 12px;
    --shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    --mobile-breakpoint: 1024px;
    --tablet-breakpoint: 768px;
    --phone-breakpoint: 576px;
}

/* ----------- STYLES DE BASE ----------- */
.shop-header {
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
    color: var(--light);
    padding: 2rem 1.5rem;
    margin-bottom: 2rem;
    border-radius: 0 0 var(--radius) var(--radius);
}

.banner {
    text-align: center;
    margin-bottom: 2rem;
}

.banner h1 {
    font-size: 2.5rem;
    margin-bottom: 0.5rem;
    font-weight: 700;
}

.banner p {
    font-size: 1.1rem;
    opacity: 0.9;
}

.filters-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
}

.search-form {
    display: flex;
    flex: 1;
    min-width: 250px;
    max-width: 500px;
}

.search-form input {
    flex: 1;
    padding: 0.75rem 1rem;
    border: none;
    border-radius: var(--radius) 0 0 var(--radius);
    font-size: 1rem;
    outline: none;
}

.search-form button {
    padding: 0.75rem 1.5rem;
    background: var(--dark);
    color: var(--light);
    border: none;
    border-radius: 0 var(--radius) var(--radius) 0;
    cursor: pointer;
    transition: var(--transition);
}

.search-form button:hover {
    background: var(--dark-light);
}

#sortSelect {
    padding: 0.75rem 1rem;
    border: none;
    border-radius: var(--radius);
    font-size: 1rem;
    background: var(--light);
    color: var(--dark);
    cursor: pointer;
    min-width: 180px;
}

/* ----------- CONTENEUR PRINCIPAL ----------- */
.shop-container {
    display: flex;
    gap: 2rem;
    padding: 0 1.5rem;
    max-width: 1400px;
    margin: 0 auto;
}

/* ----------- SECTION PRODUITS ----------- */
/* Styles pour la section produits */
.products-section {
    padding: 2rem;
    max-width: 1400px;
    margin: 0 auto;
}

/* Information des résultats */
.results-info {
    margin-bottom: 1.5rem;
}

.results-info p {
    color: var(--dark-light);
    font-size: 0.9rem;
}

/* Grille de produits */
.product-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 2rem;
    margin-bottom: 3rem;
}

/* Carte produit */
.product-card {
    background: var(--light);
    border-radius: var(--radius);
    overflow: hidden;
    box-shadow: var(--shadow);
    transition: var(--transition);
    position: relative;
    display: flex;
    flex-direction: column;
    height: 80%;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
}

/* Conteneur d'image */
.product-image-container {
    position: relative;
    overflow: hidden;
    aspect-ratio: 1/1;
    cursor: pointer;
}

.product-image {
    width: 100%;
    height: 100%;
    position: relative;
}

.product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.product-image-container:hover img {
    transform: scale(1.05);
}

/* Badges produit */
.product-badges {
    position: absolute;
    top: 10px;
    left: 10px;
    display: flex;
    flex-direction: column;
    gap: 5px;
    z-index: 2;
}

.badge {
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 0.7rem;
    font-weight: bold;
    text-transform: uppercase;
}

.badge.discount {
    background-color: var(--accent);
    color: white;
}

.badge.new {
    background-color: var(--secondary);
    color: white;
}

.badge.bestseller {
    background-color: var(--primary);
    color: white;
}

/* Actions produit */
.product-actions {
    position: absolute;
    top: 10px;
    right: 10px;
    display: flex;
    flex-direction: column;
    gap: 8px;
    opacity: 0;
    transform: translateX(10px);
    transition: var(--transition);
    z-index: 2;
}

.product-card:hover .product-actions {
    opacity: 1;
    transform: translateX(0);
}

.action-btn {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    border: none;
    background: var(--light);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: var(--transition);
}

.action-btn:hover {
    background: var(--primary);
    color: white;
    transform: scale(1.1);
}

/* Informations produit */
.product-info {
    padding: 1.2rem;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}

.product-info h3 {
    margin: 0 0 0.5rem 0;
    font-size: 1.1rem;
    color: var(--dark);
    line-height: 1.3;
}

.product-description {
    color: var(--dark-light);
    font-size: 0.85rem;
    margin: 0 0 1rem 0;
    line-height: 1.4;
    flex-grow: 1;
}

/* Évaluation produit */
.product-rating {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 1rem;
}

.stars {
    display: flex;
    gap: 2px;
}

.star {
    color: #ddd;
    font-size: 0.9rem;
}

.star.filled {
    color: #ffc107;
}

.rating-count {
    font-size: 0.8rem;
    color: var(--dark-light);
}

/* Prix produit */
.product-price {
    margin-bottom: 1rem;
}

.current-price {
    font-size: 1.2rem;
    font-weight: bold;
    color: var(--primary-dark);
}

.old-price {
    font-size: 0.9rem;
    color: var(--dark-light);
    text-decoration: line-through;
    margin-left: 0.5rem;
}

/* Bouton d'ajout */
.quick-add-btn {
    background: var(--primary);
    color: white;
    border: none;
    padding: 0.8rem;
    border-radius: 0 0 var(--radius) var(--radius);
    font-weight: bold;
    cursor: pointer;
    transition: var(--transition);
    margin-top: auto;
}

.quick-add-btn:hover {
    background: var(--primary-dark);
}

/* Pagination */
.pagination-container {
    display: flex;
    justify-content: center;
    margin-top: 2rem;
}

.pagination-container nav {
    display: flex;
    gap: 0.5rem;
}

.pagination-container .pagination {
    list-style: none;
    display: flex;
    gap: 0.5rem;
    padding: 0;
    margin: 0;
}

.pagination-container .page-item {
    display: flex;
}

.pagination-container .page-link {
    padding: 0.5rem 1rem;
    border-radius: var(--radius);
    border: 1px solid #e2e8f0;
    color: var(--dark);
    text-decoration: none;
    transition: var(--transition);
}

.pagination-container .page-link:hover {
    background: var(--primary-light);
    color: white;
    border-color: var(--primary-light);
}

.pagination-container .page-item.active .page-link {
    background: var(--primary);
    color: white;
    border-color: var(--primary);
}

.pagination-container .page-item.disabled .page-link {
    opacity: 0.5;
    cursor: not-allowed;
}

/* Aucun produit */
.no-products {
    text-align: center;
    padding: 3rem;
    background: var(--light);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
}

.no-products p {
    margin-bottom: 1.5rem;
    color: var(--dark-light);
    font-size: 1.1rem;
}

.no-products button {
    background: var(--primary);
    color: white;
    border: none;
    padding: 0.8rem 1.5rem;
    border-radius: var(--radius);
    font-weight: bold;
    cursor: pointer;
    transition: var(--transition);
}

.no-products button:hover {
    background: var(--primary-dark);
}

/* Responsivité */
@media (max-width: var(--mobile-breakpoint)) {
    .products-section {
        padding: 1.5rem;
    }
    
    .product-grid {
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 1.5rem;
    }
}

@media (max-width: var(--tablet-breakpoint)) {
    .products-section {
        padding: 1rem;
    }
    
    .product-grid {
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 1.2rem;
    }
    
    .product-info {
        padding: 1rem;
    }
    
    .product-info h3 {
        font-size: 1rem;
    }
}

@media (max-width: var(--phone-breakpoint)) {
    .product-grid {
        grid-template-columns: 1fr;
        max-width: 350px;
        margin-left: auto;
        margin-right: auto;
    }
    
    .pagination-container .pagination {
        flex-wrap: wrap;
        justify-content: center;
    }
    
    .product-actions {
        opacity: 1;
        transform: translateX(0);
    }
}

/* Styles pour le carrousel d'images (si applicable) */
.image-carousel {
    display: flex;
    overflow-x: auto;
    scroll-snap-type: x mandatory;
    gap: 10px;
    scrollbar-width: none;
    -ms-overflow-style: none;
}

.image-carousel::-webkit-scrollbar {
    display: none;
}

.image-carousel img {
    scroll-snap-align: start;
    flex: 0 0 auto;
    width: 100%;
    height: 300px;
    object-fit: cover;
    border-radius: var(--radius);
}

/* Styles pour les popups (à adapter selon votre implémentation) */
.product-popup {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.8);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.3s ease, visibility 0.3s ease;
}

.product-popup.active {
    opacity: 1;
    visibility: visible;
}

.popup-content {
    background: white;
    border-radius: var(--radius);
    max-width: 900px;
    width: 90%;
    max-height: 90vh;
    overflow-y: auto;
    position: relative;
    padding: 2rem;
}

.close-popup {
    position: absolute;
    top: 1rem;
    right: 1rem;
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
    z-index: 10;
}

/* Animation pour les cartes produits */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.product-card {
    animation: fadeInUp 0.5s ease forwards;
}

.product-card:nth-child(2) { animation-delay: 0.1s; }
.product-card:nth-child(3) { animation-delay: 0.2s; }
.product-card:nth-child(4) { animation-delay: 0.3s; }
.product-card:nth-child(5) { animation-delay: 0.4s; }
.product-card:nth-child(6) { animation-delay: 0.5s; }
/* ----------- AUCUN PRODUIT ----------- */
.no-products {
    text-align: center;
    padding: 3rem;
    color: var(--dark-light);
}

.no-products button {
    margin-top: 1rem;
    padding: 0.75rem 1.5rem;
    background: var(--primary);
    color: var(--light);
    border: none;
    border-radius: var(--radius);
    cursor: pointer;
    transition: var(--transition);
}

.no-products button:hover {
    background: var(--primary-dark);
}

/* ----------- MODALS ----------- */
.modal-custom {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.7);
    z-index: 1000;
    align-items: center;
    justify-content: center;
    padding: 1rem;
    overflow-y: auto;
}

.modal-content-custom {
    background: var(--light);
    border-radius: var(--radius);
    width: 100%;
    max-width: 900px;
    max-height: 90vh;
    overflow-y: auto;
    position: relative;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    animation: modalFadeIn 0.3s ease;
}

@keyframes modalFadeIn {
    from { opacity: 0; transform: translateY(-20px); }
    to { opacity: 1; transform: translateY(0); }
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
}

.close-btn:hover {
    background: rgba(0, 0, 0, 0.1);
    color: var(--dark);
}

.modal-content-custom h2,
.modal-content-custom h3 {
    padding: 1.5rem 1.5rem 0.5rem;
    color: var(--dark);
    margin: 0;
}

/* ----------- CAROUSEL D'IMAGES ----------- */
.carousel-container {
    position: relative;
    width: 100%;
    margin: 1rem 0;
    overflow: hidden;
    aspect-ratio: 16/9;
}

.carousel-track {
    display: flex;
    transition: transform 0.5s ease;
    height: 100%;
}

.carousel-slide {
    min-width: 100%;
    height: 100%;
}

.carousel-slide img {
    width: 100%;
    height: 100%;
    object-fit: contain;
}

.carousel-btn {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(255, 255, 255, 0.8);
    border: none;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    font-size: 1.5rem;
    cursor: pointer;
    z-index: 5;
    transition: var(--transition);
    display: flex;
    align-items: center;
    justify-content: center;
}

.carousel-btn:hover {
    background: var(--light);
}

.carousel-btn.prev {
    left: 1rem;
}

.carousel-btn.next {
    right: 1rem;
}

/* ----------- CONTENU MODAL ----------- */
.modal-product-info {
    padding: 0 1.5rem 1.5rem;
}

.modal-price-container {
    margin: 1rem 0;
    font-size: 1.2rem;
}

.variants-container {
    margin: 1.5rem 0;
    display: flex;
    flex-wrap: wrap;
    gap: 0.75rem;
}

.variant-item {
    padding: 0.5rem 1rem;
    border: 1px solid #ddd;
    border-radius: var(--radius);
    cursor: pointer;
    transition: var(--transition);
}

.variant-item:hover {
    border-color: var(--primary-light);
}

.variant-item.selected {
    background: var(--primary);
    color: var(--light);
    border-color: var(--primary);
}

.add-to-cart-btn {
    width: 100%;
    padding: 1rem;
    background: var(--primary);
    color: var(--light);
    border: none;
    border-radius: var(--radius);
    font-size: 1.1rem;
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition);
    margin-top: 1rem;
}

.add-to-cart-btn:hover {
    background: var(--primary-dark);
}

/* ----------- MODAL QUANTITÉ ----------- */
<>
/* Modal de quantité avec variantes */
.modal-custom {
  display: none;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.7);
  z-index: 1000;
  opacity: 0;
  transition: opacity 0.3s ease;
  backdrop-filter: blur(5px);
}

.modal-custom.active {
  display: flex;
  justify-content: center;
  align-items: center;
  opacity: 1;
}

.modal-content-custom {
  background-color: var(--light);
  border-radius: var(--radius);
  width: 90%;
  max-width: 500px;
  max-height: 90vh;
  overflow-y: auto;
  padding: 2rem;
  position: relative;
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
  transform: translateY(-30px);
  transition: transform 0.3s ease;
  animation: modalAppear 0.3s ease forwards;
}

@keyframes modalAppear {
  to {
    transform: translateY(0);
  }
}

.close-btn {
  position: absolute;
  top: 1rem;
  right: 1.2rem;
  font-size: 2rem;
  cursor: pointer;
  color: var(--dark-light);
  transition: var(--transition);
  line-height: 1;
  background: none;
  border: none;
  padding: 0;
  width: 30px;
  height: 30px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 50%;
}

.close-btn:hover {
  color: var(--primary);
  background-color: rgba(245, 6, 196, 0.1);
  transform: rotate(90deg);
}

.modal-content-custom h3 {
  margin: 0 0 1.5rem 0;
  color: var(--dark);
  font-size: 1.4rem;
  padding-right: 2rem;
  line-height: 1.3;
}

/* Conteneur des variantes */
.variants-container {
  margin-bottom: 1.5rem;
}

.variant-option {
  margin-bottom: 1rem;
}

.variant-option:last-child {
  margin-bottom: 0;
}

.variant-option h4 {
  margin: 0 0 0.5rem 0;
  font-size: 1rem;
  color: var(--dark);
  font-weight: 600;
}

.variant-buttons {
  display: flex;
  flex-wrap: wrap;
  gap: 0.5rem;
}

.variant-btn {
  padding: 0.5rem 1rem;
  border: 1px solid #ddd;
  background-color: #f8f8f8;
  border-radius: 6px;
  cursor: pointer;
  transition: var(--transition);
  font-size: 0.9rem;
}

.variant-btn:hover {
  border-color: var(--primary-light);
  background-color: rgba(245, 6, 196, 0.05);
}

.variant-btn.selected {
  background-color: var(--primary);
  color: white;
  border-color: var(--primary);
}

/* Conteneur de quantité */
.quantity-container {
  margin-bottom: 1.5rem;
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.quantity-container label {
  font-weight: 600;
  color: var(--dark);
}

.quantity-controls {
  display: flex;
  align-items: center;
  width: fit-content;
  border: 1px solid #ddd;
  border-radius: var(--radius);
  overflow: hidden;
}

.quantity-controls button {
  width: 40px;
  height: 40px;
  background-color: #f8f8f8;
  border: none;
  font-size: 1.2rem;
  cursor: pointer;
  transition: var(--transition);
  display: flex;
  align-items: center;
  justify-content: center;
}

.quantity-controls button:hover {
  background-color: var(--primary-light);
  color: white;
}

.quantity-controls input {
  width: 60px;
  height: 40px;
  text-align: center;
  border: none;
  border-left: 1px solid #ddd;
  border-right: 1px solid #ddd;
  font-size: 1rem;
  -moz-appearance: textfield;
}

.quantity-controls input::-webkit-outer-spin-button,
.quantity-controls input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

/* Prix total */
.total-price-container {
  margin-bottom: 1.5rem;
  font-size: 1.1rem;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.total-price-container strong {
  color: var(--dark);
}

.total-price {
  color: var(--primary-dark);
  font-weight: bold;
  font-size: 1.2rem;
}

/* Bouton de confirmation */
.confirm-add-btn {
  width: 100%;
  padding: 1rem;
  background-color: var(--primary);
  color: white;
  border: none;
  border-radius: var(--radius);
  font-size: 1rem;
  font-weight: 600;
  cursor: pointer;
  transition: var(--transition);
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
}

.confirm-add-btn:hover {
  background-color: var(--primary-dark);
  transform: translateY(-2px);
  box-shadow: 0 5px 15px rgba(245, 6, 196, 0.3);
}

/* Responsivité */
@media (max-width: var(--tablet-breakpoint)) {
  .modal-content-custom {
    padding: 1.5rem;
    width: 95%;
  }
  
  .modal-content-custom h3 {
    font-size: 1.2rem;
  }
  
  .variant-buttons {
    gap: 0.4rem;
  }
  
  .variant-btn {
    padding: 0.4rem 0.8rem;
    font-size: 0.85rem;
  }
}

@media (max-width: var(--phone-breakpoint)) {
  .modal-content-custom {
    padding: 1.2rem;
    width: 100%;
    margin: 1rem;
    max-height: 95vh;
  }
  
  .modal-content-custom h3 {
    font-size: 1.1rem;
    margin-bottom: 1rem;
  }
  
  .quantity-controls {
    width: 100%;
  }
  
  .quantity-controls button {
    width: 35px;
    height: 35px;
  }
  
  .quantity-controls input {
    width: 50px;
    height: 35px;
    flex-grow: 1;
  }
  
  .variant-buttons {
    justify-content: space-between;
  }
  
  .variant-btn {
    flex: 1;
    min-width: calc(50% - 0.4rem);
    text-align: center;
  }
}

/* Animation d'entrée */
@keyframes slideIn {
  from {
    opacity: 0;
    transform: translateY(-50px) scale(0.9);
  }
  to {
    opacity: 1;
    transform: translateY(0) scale(1);
  }
}

.modal-content-custom {
  animation: slideIn 0.3s ease-out forwards;
}

/* ----------- RESPONSIVE ----------- */
@media (max-width: 1024px) {
    .shop-container {
        flex-direction: column;
    }
    
    .product-grid {
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    }
    
    .modal-content-custom {
        max-width: 95%;
    }
}

@media (max-width: 768px) {
    .shop-header {
        padding: 1.5rem 1rem;
    }
    
    .banner h1 {
        font-size: 2rem;
    }
    
    .filters-bar {
        flex-direction: column;
        align-items: stretch;
    }
    
    .search-form {
        max-width: 100%;
    }
    
    .product-grid {
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 1rem;
    }
    
    .carousel-container {
        aspect-ratio: 4/3;
    }
    
    .carousel-btn {
        width: 40px;
        height: 40px;
    }
}

@media (max-width: 576px) {
    .shop-container {
        padding: 0 1rem;
    }
    
    .product-grid {
        grid-template-columns: 1fr;
    }
    
    .modal-content-custom {
        padding: 0.5rem;
    }
    
    .modal-product-info {
        padding: 0 1rem 1rem;
    }
    
    .carousel-btn {
        width: 35px;
        height: 35px;
        font-size: 1.2rem;
    }
    
    .variants-container {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .variant-item {
        width: 100%;
    }
}
</style>
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
    let unitPrice;
    
    if (selectedVariant && selectedVariant.price) {
        unitPrice = selectedVariant.price;
    } else if (currentProduct.discount_price) {
        unitPrice = currentProduct.discount_price;
    } else {
        unitPrice = currentProduct.price;
    }
    
    let total = qty * unitPrice;
    document.getElementById('totalPrice').textContent = total.toLocaleString();
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