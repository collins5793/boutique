@extends('layouts.apli')

@section('title', 'AkuesleyStore - Votre Boutique en Ligne')

@section('content')
<style>
    /* Hero Section with Animated Background */
    .hero { 
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.9), rgba(118, 75, 162, 0.9)), 
                    url('https://images.unsplash.com/photo-1607082348824-0a96f2a4b9da?auto=format&fit=crop&w=1920&q=80') center/cover no-repeat;
        color: white; 
        padding: 150px 0; 
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: radial-gradient(circle at 30% 20%, rgba(120, 119, 198, 0.3) 0%, transparent 50%),
                    radial-gradient(circle at 80% 80%, rgba(255, 119, 198, 0.3) 0%, transparent 50%);
        animation: float 6s ease-in-out infinite;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-20px) rotate(2deg); }
    }

    .hero-content {
        position: relative;
        z-index: 2;
    }

    .hero h1 { 
        font-size: 4rem; 
        font-weight: 800;
        margin-bottom: 24px;
        text-shadow: 0 4px 20px rgba(0,0,0,0.3);
        animation: slideInUp 1s ease-out;
    }

    .hero p { 
        font-size: 1.4rem; 
        margin-bottom: 40px;
        opacity: 0.95;
        animation: slideInUp 1s ease-out 0.2s both;
    }

    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(50px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Feature Boxes with Glassmorphism */
    .features-section {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        padding: 100px 0;
    }

    .feature-box { 
        background: var(--glass-bg);
        backdrop-filter: blur(20px);
        border: 1px solid var(--glass-border);
        text-align: center; 
        padding: 40px 30px; 
        border-radius: 20px;
        transition: all 0.4s ease;
        box-shadow: 0 8px 32px rgba(31, 38, 135, 0.37);
        height: 100%;
    }

    .feature-box:hover { 
        transform: translateY(-10px) scale(1.02);
        box-shadow: 0 20px 50px rgba(31, 38, 135, 0.5);
    }

    .feature-icon { 
        font-size: 3rem;
        background: var(--primary-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 25px;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }

    /* Product Cards with Modern Design */
    .product-card { 
        background: white;
        border: none !important;
        border-radius: 20px;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        margin-bottom: 40px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        position: relative;
    }

    .product-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: var(--primary-gradient);
        opacity: 0;
        transition: opacity 0.3s ease;
        z-index: 0;
    }

    .product-card:hover::before {
        opacity: 0.03;
    }

    .product-card:hover { 
        transform: translateY(-15px) rotateX(5deg);
        box-shadow: 0 25px 50px rgba(0,0,0,0.2);
    }

    .product-image { 
        height: 280px; 
        object-fit: cover;
        transition: transform 0.4s ease;
    }

    .product-card:hover .product-image {
        transform: scale(1.1);
    }

    .badge-new { 
        position: absolute; 
        top: 15px; 
        left: 15px;
        background: var(--accent-gradient);
        color: white; 
        padding: 8px 15px; 
        font-size: 12px; 
        font-weight: 600;
        border-radius: 20px;
        z-index: 2;
        box-shadow: 0 4px 15px rgba(245, 87, 108, 0.3);
    }

    .badge-promo {
        background: linear-gradient(135deg, #ff6b6b, #ee5a24) !important;
    }

    .price-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 15px;
    }

    .price-main {
        font-weight: 800;
        font-size: 1.2rem;
        background: var(--primary-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .cart-btn {
        background: var(--primary-gradient);
        border: none;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        color: white;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .cart-btn:hover {
        transform: scale(1.1) rotate(360deg);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
    }

    /* Loading Animation */
    @keyframes shimmer {
        0% { background-position: -200px 0; }
        100% { background-position: calc(200px + 100%) 0; }
    }

    .loading-shimmer {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200px 100%;
        animation: shimmer 1.5s infinite;
    }

    @media (max-width: 768px) {
        .hero h1 { font-size: 2.5rem; }
        .hero p { font-size: 1.1rem; }
        .feature-box { margin-bottom: 30px; }
    }
</style>

<!-- Hero -->
<section class="hero d-flex flex-column justify-content-center align-items-center">
    <div class="hero-content">
        <h1>Bienvenue chez AkuesleyStore</h1>
        <p>Découvrez les meilleurs produits à prix imbattables.<br>Livraison rapide et service client 24/7.</p>
        <a href="#products-popular" class="btn btn-modern btn-lg">
            Découvrir nos produits
            <i class="fas fa-arrow-right ms-2"></i>
        </a>
    </div>
</section>

<!-- Features -->
<section class="features-section">
<div class="container">
    <div class="section-header animate-on-scroll">
        <h2 class="section-title">Pourquoi nous choisir ?</h2>
        <p class="section-subtitle">Des services exceptionnels pour une expérience d'achat incomparable</p>
    </div>
    <div class="row g-4">
        <div class="col-md-4 animate-on-scroll">
            <div class="feature-box">
                <div class="feature-icon"><i class="fas fa-truck-fast"></i></div>
                <h4>Livraison Express</h4>
                <p>Recevez vos commandes en 24-48h partout au Bénin avec notre service de livraison premium.</p>
            </div>
        </div>
        <div class="col-md-4 animate-on-scroll">
            <div class="feature-box">
                <div class="feature-icon"><i class="fas fa-shield-check"></i></div>
                <h4>Paiement Sécurisé</h4>
                <p>Transactions 100% sécurisées via Mobile Money, Carte Bancaire ou Virement.</p>
            </div>
        </div>
        <div class="col-md-4 animate-on-scroll">
            <div class="feature-box">
                <div class="feature-icon"><i class="fas fa-headset"></i></div>
                <h4>Support Premium</h4>
                <p>Assistance client personnalisée 24/7 par chat, téléphone ou email.</p>
            </div>
        </div>
    </div>
</div>
</section>

<!-- Produits Populaires -->
<section id="products-popular" class="py-5">
<div class="container">
    <div class="section-header animate-on-scroll">
        <h2 class="section-title">Produits Populaires</h2>
        <p class="section-subtitle">Découvrez nos bestsellers plébiscités par nos clients</p>
    </div>
    <div class="row">
        @foreach($popularProducts as $product)
        <div class="col-lg-3 col-md-6 animate-on-scroll">
            <div class="card product-card">
                <img src="{{ asset('storage/'.$product->image) }}" class="card-img-top product-image" alt="{{ $product->name }}">
                <div class="card-body">
                    <h5 class="card-title">{{ $product->name }}</h5>
                    <p class="card-text text-muted small">{{ Str::limit($product->description, 80) }}</p>
                    <div class="price-container">
                        <span class="price-main">{{ number_format($product->price,0,'',' ') }} FCFA</span>
                        <button class="cart-btn">
                            <i class="fas fa-cart-plus"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
</section>

<!-- Nouveaux Produits -->
<section id="products-new" class="py-5 bg-light">
<div class="container">
    <div class="section-header animate-on-scroll">
        <h2 class="section-title">Nouveautés</h2>
        <p class="section-subtitle">Les dernières arrivées dans notre catalogue</p>
    </div>
    <div class="row">
        @foreach($newProducts as $product)
        <div class="col-lg-3 col-md-6 animate-on-scroll">
            <div class="card product-card">
                <span class="badge-new">Nouveau</span>
                <img src="{{ asset('storage/'.$product->image) }}" class="card-img-top product-image" alt="{{ $product->name }}">
                <div class="card-body">
                    <h5 class="card-title">{{ $product->name }}</h5>
                    <p class="card-text text-muted small">{{ Str::limit($product->description, 80) }}</p>
                    <div class="price-container">
                        <span class="price-main">{{ number_format($product->price,0,'',' ') }} FCFA</span>
                        <button class="cart-btn">
                            <i class="fas fa-cart-plus"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
</section>

<!-- Promotions -->
<section id="products-promotions" class="py-5">
<div class="container">
    <div class="section-header animate-on-scroll">
        <h2 class="section-title">Promotions Exceptionnelles</h2>
        <p class="section-subtitle">Profitez de nos offres limitées avant qu'il ne soit trop tard !</p>
    </div>
    <div class="row">
        @foreach($promotions as $product)
        <div class="col-lg-3 col-md-6 animate-on-scroll">
            <div class="card product-card">
                @php
                    $discountPercent = round((($product->price - $product->discount_price)/$product->price)*100);
                @endphp
                <span class="badge-new badge-promo">-{{ $discountPercent }}%</span>
                <img src="{{ asset('storage/'.$product->image) }}" class="card-img-top product-image" alt="{{ $product->name }}">
                <div class="card-body">
                    <h5 class="card-title">{{ $product->name }}</h5>
                    <p class="card-text text-muted small">{{ Str::limit($product->description, 80) }}</p>
                    <div class="price-container">
                        <div>
                            <span class="price-main">{{ number_format($product->discount_price,0,'',' ') }} FCFA</span>
                            <br>
                            <span class="text-muted text-decoration-line-through small">{{ number_format($product->price,0,'',' ') }} FCFA</span>
                        </div>
                        <button class="cart-btn">
                            <i class="fas fa-cart-plus"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
</section>
@endsection

@push('scripts')
<script>
    // Add loading animation to images
    document.querySelectorAll('.product-image').forEach(img => {
        img.addEventListener('load', function() {
            this.style.opacity = '1';
        });
        img.style.opacity = '0';
        img.style.transition = 'opacity 0.3s ease';
    });
</script>
@endpush