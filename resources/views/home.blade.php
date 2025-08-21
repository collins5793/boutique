@extends('layouts.apli')
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>AkuesleyStore - Votre Boutique en Ligne</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
body { font-family: 'Segoe UI', sans-serif; }
.hero { background: url('https://images.unsplash.com/photo-1607082348824-0a96f2a4b9da?auto=format&fit=crop&w=1920&q=80') center/cover no-repeat; color: white; padding: 120px 0; text-align: center; }
.hero h1 { font-size: 3rem; font-weight: 700; }
.hero p { font-size: 1.2rem; margin-bottom: 30px; }
.product-card { transition: transform 0.3s, box-shadow 0.3s; margin-bottom: 30px; }
.product-card:hover { transform: translateY(-5px); box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
.product-image { height: 250px; object-fit: cover; }
.badge-new { position: absolute; top: 10px; left: 10px; background: #e74c3c; color: white; padding: 5px 10px; font-size: 12px; border-radius: 3px; }
.feature-box { text-align: center; padding: 30px 20px; border-radius: 8px; transition: transform 0.3s ease; }
.feature-box:hover { transform: translateY(-5px); }
.feature-icon { font-size: 2.5rem; color: #3498db; margin-bottom: 20px; }
footer { background: #2c3e50; color: white; padding: 60px 0 30px; }
footer a { color: #bdc3c7; text-decoration: none; }
footer a:hover { color: white; }
.navbar-brand img { width:40px; height:40px; border-radius:50%; margin-right:10px; }
</style>
</head>
<body>

<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold fs-3 text-primary" href="#">
            <img src="{{ asset('logoAkues.jpg') }}" alt="Logo Akues">
            AkuesleyStore
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link active" href="#">Accueil</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Produits</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Catégories</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Promotions</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Contact</a></li>
            </ul>
            <div class="d-flex align-items-center">
                <a href="{{ route('login') }}" class="btn btn-outline-primary me-2">Connexion</a>
                <a href="{{ route('register') }}" class="btn btn-primary">Inscription</a>
            </div>
        </div>
    </div>
</nav>

<!-- Hero -->
<section class="hero d-flex flex-column justify-content-center align-items-center">
    <h1>Bienvenue chez AkuesleyStore</h1>
    <p>Découvrez les meilleurs produits à prix imbattables. Livraison rapide et service client 24/7.</p>
    <a href="#products-popular" class="btn btn-primary btn-lg">Voir les produits</a>
</section>

<!-- Features -->
<section class="py-5 bg-light">
<div class="container">
    <div class="row g-4 text-center">
        <div class="col-md-4">
            <div class="feature-box">
                <div class="feature-icon"><i class="fas fa-truck"></i></div>
                <h4>Livraison Rapide</h4>
                <p>Recevez vos commandes en 24-48h partout au Bénin.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="feature-box">
                <div class="feature-icon"><i class="fas fa-shield-alt"></i></div>
                <h4>Paiement Sécurisé</h4>
                <p>Paiement 100% sécurisé via Mobile Money ou Carte Bancaire.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="feature-box">
                <div class="feature-icon"><i class="fas fa-headset"></i></div>
                <h4>Support 24/7</h4>
                <p>Notre équipe répond à toutes vos questions 7j/7.</p>
            </div>
        </div>
    </div>
</div>
</section>

<!-- Produits Populaires -->
<section id="products-popular" class="py-5">
<div class="container">
    <h2 class="text-center mb-5">Produits Populaires</h2>
    <div class="row">
        @foreach($popularProducts as $product)
        <div class="col-md-3">
            <div class="card product-card position-relative">
                <img src="{{ asset('storage/'.$product->image) }}" class="card-img-top product-image" alt="{{ $product->name }}">
                <div class="card-body">
                    <h5 class="card-title">{{ $product->name }}</h5>
                    <p class="card-text text-muted small">{{ $product->description }}</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold text-primary">{{ number_format($product->price,0,'',' ') }} FCFA</span>
                        <a href="#" class="btn btn-sm btn-outline-primary"><i class="fas fa-cart-plus"></i></a>
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
    <h2 class="text-center mb-5">Nouveaux Produits</h2>
    <div class="row">
        @foreach($newProducts as $product)
        <div class="col-md-3">
            <div class="card product-card position-relative">
                <span class="badge-new">Nouveau</span>
                <img src="{{ asset('storage/'.$product->image) }}" class="card-img-top product-image" alt="{{ $product->name }}">
                <div class="card-body">
                    <h5 class="card-title">{{ $product->name }}</h5>
                    <p class="card-text text-muted small">{{ $product->description }}</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold text-primary">{{ number_format($product->price,0,'',' ') }} FCFA</span>
                        <a href="#" class="btn btn-sm btn-outline-primary"><i class="fas fa-cart-plus"></i></a>
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
    <h2 class="text-center mb-5">Promotions en cours</h2>
    <div class="row">
        @foreach($promotions as $product)
        <div class="col-md-3">
            <div class="card product-card position-relative">
                @php
                    $discountPercent = round((($product->price - $product->discount_price)/$product->price)*100);
                @endphp
                <span class="badge-new bg-danger">-{{ $discountPercent }}%</span>
                <img src="{{ asset('storage/'.$product->image) }}" class="card-img-top product-image" alt="{{ $product->name }}">
                <div class="card-body">
                    <h5 class="card-title">{{ $product->name }}</h5>
                    <p class="card-text text-muted small">{{ $product->description }}</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold text-primary">{{ number_format($product->discount_price,0,'',' ') }} FCFA</span>
                        <span class="text-muted text-decoration-line-through">{{ number_format($product->price,0,'',' ') }} FCFA</span>
                        <a href="#" class="btn btn-sm btn-outline-primary"><i class="fas fa-cart-plus"></i></a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
</section>

<!-- Footer -->
<footer class="mt-5">
<div class="container">
    <div class="row text-center text-md-start">
        <div class="col-md-4 mb-4">
            <h5>AkuesleyStore</h5>
            <p>La meilleure boutique en ligne pour tous vos besoins. Livraison rapide et paiement sécurisé.</p>
        </div>
        <div class="col-md-4 mb-4">
            <h5>Liens utiles</h5>
            <ul class="list-unstyled">
                <li><a href="#">Accueil</a></li>
                <li><a href="#">Produits</a></li>
                <li><a href="#">Promotions</a></li>
                <li><a href="#">Contact</a></li>
            </ul>
        </div>
        <div class="col-md-4 mb-4">
            <h5>Newsletter</h5>
            <form>
                <div class="mb-3">
                    <input type="email" class="form-control" placeholder="Votre email">
                </div>
                <button class="btn btn-primary w-100" type="submit">S’inscrire</button>
            </form>
            <div class="mt-3">
                <a href="#" class="me-2"><i class="fab fa-facebook fa-lg"></i></a>
                <a href="#" class="me-2"><i class="fab fa-twitter fa-lg"></i></a>
                <a href="#" class="me-2"><i class="fab fa-instagram fa-lg"></i></a>
            </div>
        </div>
    </div>
    <hr class="bg-secondary">
    <p class="text-center mb-0">&copy; {{ date('Y') }} AkuesleyStore. Tous droits réservés.</p>
</div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
