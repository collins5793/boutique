@extends('layouts.clients.client')

@section('title', 'Mon Panier')

@section('content')
<div class="cart-container">
    <div class="cart-header">
        <h1 class="cart-title">VOTRE PANIER</h1>
        <div class="cart-progress">
            <div class="progress-step active">
                <span class="step-number">1</span>
                <span class="step-label">Panier</span>
            </div>
            <div class="progress-step">
                <span class="step-number">2</span>
                <span class="step-label">Livraison</span>
            </div>
            <div class="progress-step">
                <span class="step-number">3</span>
                <span class="step-label">Paiement</span>
            </div>
            <div class="progress-step">
                <span class="step-number">4</span>
                <span class="step-label">Confirmation</span>
            </div>
        </div>
    </div>

    @if($cartItems->isEmpty())
        <div class="empty-cart">
            <div class="empty-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <h2>Votre panier est vide</h2>
            <p>Parcourez nos produits et ajoutez des articles à votre panier</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary">Découvrir nos produits</a>
        </div>
    @else
    <div class="cart-grid">
        <!-- Liste des produits -->
        <section class="cart-items">
            @php $subtotal = 0; $subtotale = 0; @endphp
            @foreach($cartItems as $item)
                @php
                    $price = $item->price;
                    $total = $price * $item->quantity;
                    $totale = $item->product->price * $item->quantity;
                    $subtotal += $total;
                    $subtotale += $totale;
                @endphp

                <article class="cart-item" data-id="{{ $item->id }}">
                    <div class="item-image">
                        <img src="{{ asset('storage/'.$item->product->image) }}" alt="{{ $item->product->name }}" class="product-img">
                    </div>

                    <div class="item-details">
                        <div class="item-header">
                            <h3 class="product-name">{{ $item->product->name }}</h3>
                            <button class="delete-btn deleteProductBtn" data-id="{{ $item->id }}" title="Supprimer">
                                <svg viewBox="0 0 24 24" aria-hidden="true" class="delete">
                                    <path class="delete" d="M9 3h6a1 1 0 0 1 1 1v1h4v2h-1v12a3 3 0 0 1-3 3H8a3 3 0 0 1-3-3V7H4V5h4V4a1 1 0 0 1 1-1Zm8 4H7v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V7Zm-8 3h2v8H9v-8Zm4 0h2v8h-2v-8ZM10 5v0h4V5h-4Z"/>
                                </svg>
                            </button>
                        </div>
                        
                        <div class="product-variant">Variante : {{ $item->variant ? $item->variant->name : '-' }}</div>
                        <p class="product-description">{{ Str::limit($item->product->description, 100) }}</p>
                        
                        <div class="price-container">
                            @if($item->product->discount_price)
                                <span class="current-price">{{ number_format($item->product->discount_price, 0, ',', ' ') }} FCFA</span>
                                <span class="original-price">{{ number_format($item->product->price, 0, ',', ' ') }} FCFA</span>
                                <span class="discount-badge">Économisez {{ number_format($item->product->price - $item->product->discount_price, 0, ',', ' ') }} FCFA</span>
                            @else
                                <span class="current-price">{{ number_format($price, 0, ',', ' ') }} FCFA</span>
                            @endif
                        </div>

                        <div class="quantity-controls">
                            <div class="quantity-selector">
                                <button class="qty-btn decreaseQty" data-id="{{ $item->id }}">
                                    <svg viewBox="0 0 24 24" width="16" height="16">
                                        <path d="M19 13H5v-2h14v2z"/>
                                    </svg>
                                </button>
                                <span class="quantity-count" id="count-{{ $item->id }}">{{ $item->quantity }}</span>
                                <button class="qty-btn increaseQty" data-id="{{ $item->id }}">
                                    <svg viewBox="0 0 24 24" width="16" height="16">
                                        <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
                                    </svg>
                                </button>
                            </div>
                            <div class="item-total">
                                Total: <strong>{{ number_format($total, 0, ',', ' ') }} FCFA</strong>
                            </div>
                        </div>
                    </div>
                </article>
            @endforeach
        </section>

        <!-- Résumé commande -->
        <aside class="order-summary">
            <div class="summary-card">
                <h3>Résumé de la commande</h3>

                <div class="summary-line">
                    <span>Sous-total ({{ $cartItems->count() }} article{{ $cartItems->count() > 1 ? 's' : '' }})</span>
                    <span>{{ number_format($subtotal, 0, ',', ' ') }} FCFA</span>
                </div>

                <div class="summary-line discount">
                    <span>Réduction</span>
                    <span>-{{ number_format($subtotale - $subtotal, 0, ',', ' ') }} FCFA</span>
                </div>

                <div class="summary-line">
                    <span>Frais de livraison</span>
                    <span>13 FCFA</span>
                </div>

                <div class="summary-divider"></div>

                <div class="summary-total">
                    <span>Total</span>
                    <span>{{ number_format($subtotal + 13, 0, ',', ' ') }} FCFA</span>
                </div>
                
                <div class="savings-notice">
                    Vous économisez {{ number_format($subtotale - $subtotal, 0, ',', ' ') }} FCFA sur cette commande!
                </div>
            </div>
            
            <div class="summary-actions">
                <button id="clearCartBtn" class="btn-clear">
                    <svg viewBox="0 0 24 24" width="18" height="18">
                        <path d="M19 4h-3.5l-1-1h-5l-1 1H5v2h14V4zM6 7v12c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6z"/>
                    </svg>
                    Vider le panier
                </button>
                <button id="checkoutBtn" class="btn-checkout">
                    Passer la commande
                    <svg viewBox="0 0 24 24" width="18" height="18">
                        <path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/>
                    </svg>
                </button>
            </div>
            
            <div class="security-notice">
                <svg viewBox="0 0 24 24" width="20" height="20">
                    <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 10.99h7c-.53 4.12-3.28 7.79-7 8.94V12H5V6.3l7-3.11v8.8z"/>
                </svg>
                <span>Paiement sécurisé • Livraison garantie</span>
            </div>
        </aside>
    </div>
    
    <!-- Popup choix adresse -->
    <div id="addressChoicePopup" class="popup-overlay">
        <div class="popup-content">
            <div class="popup-header">
                <h3>Choisissez votre adresse de livraison</h3>
            </div>
            <div class="popup-body">
                <button class="addressOption btn btn-primary" data-type="current">Adresse actuelle</button>
                <button class="addressOption btn btn-warning" data-type="manual">Saisie manuelle</button>
                <button class="addressOption btn btn-success" data-type="map">Choisir sur la carte</button>
            </div>
            <div class="popup-actions">
                <button id="closeAddressPopup" class="btn btn-secondary">Annuler</button>
            </div>
        </div>
    </div>

    <!-- Popup saisie manuelle -->
    <div id="manualAddressPopup" class="popup-overlay">
        <div class="popup-content">
            <div class="popup-header">
                <h4>Entrez votre adresse</h4>
            </div>
            <div class="popup-body">
                <textarea id="manualAddress" class="form-control" rows="3" placeholder="Quartier, ville, repères..."></textarea>
                <input type="text" id="manualLandmarks" class="form-control mt-2" placeholder="Repères supplémentaires (optionnel)">
            </div>
            <div class="popup-actions">
                <button id="submitManualAddress" class="btn btn-success">Valider l'adresse</button>
                <button id="closeManualPopup" class="btn btn-secondary">Annuler</button>
            </div>
        </div>
    </div>

    <!-- Popup choix paiement -->
    <div id="paymentChoicePopup" class="popup-overlay">
        <div class="popup-content">
            <div class="popup-header">
                <h3>Choisissez votre mode de paiement</h3>
            </div>
            <div class="popup-body">
                <button id="cashOnDeliveryBtn" class="btn btn-primary">Paiement à la livraison</button>
                <button id="onlinePaymentBtn" class="btn btn-warning">Paiement en ligne</button>
            </div>
            <div class="popup-actions">
                <button id="closePopup" class="btn btn-secondary">Annuler</button>
            </div>
        </div>
    </div>
    @endif
</div>

<style>
:root {
    --primary: #f506c4;
    --primary-light: #ff33d1;
    --primary-dark: #c0049b;
    --secondary: #007bff;
    --accent: #ff7b00;
    --success: #10b981;
    --danger: #ef4444;
    --warning: #f59e0b;
    --dark: #1e293b;
    --dark-light: #334155;
    --light: #ffffff;
    --gray-100: #f8fafc;
    --gray-200: #e2e8f0;
    --gray-300: #cbd5e1;
    --gray-400: #94a3b8;
    --gray-500: #64748b;
    --radius: 12px;
    --shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    --transition: all 0.3s ease;
}

.cart-container {
    max-width: 1200px;
    margin: 2rem auto;
    padding: 0 1.5rem;
}

.cart-header {
    margin-bottom: 2.5rem;
    text-align: center;
}

.cart-title {
    font-size: 2.5rem;
    font-weight: 800;
    color: var(--dark);
    margin-bottom: 1.5rem;
    position: relative;
    display: inline-block;
}

.cart-title::after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 50%;
    transform: translateX(-50%);
    width: 60px;
    height: 4px;
    background: var(--primary);
    border-radius: 2px;
}

.cart-progress {
    display: flex;
    justify-content: center;
    margin: 2rem 0;
}

.progress-step {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 0 1.5rem;
    position: relative;
}

.progress-step:not(:last-child)::after {
    content: '';
    position: absolute;
    top: 20px;
    right: -10px;
    width: 40px;
    height: 2px;
    background: var(--gray-300);
}

.step-number {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: var(--gray-200);
    color: var(--gray-500);
    font-weight: 600;
    margin-bottom: 0.5rem;
    transition: var(--transition);
}

.step-label {
    font-size: 0.875rem;
    color: var(--gray-500);
    transition: var(--transition);
}

.progress-step.active .step-number {
    background: var(--primary);
    color: white;
    box-shadow: 0 4px 10px rgba(245, 6, 196, 0.3);
}

.progress-step.active .step-label {
    color: var(--dark);
    font-weight: 500;
}

.empty-cart {
    text-align: center;
    padding: 4rem 2rem;
    background: white;
    border-radius: var(--radius);
    box-shadow: var(--shadow);
}

.empty-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto 1.5rem;
    color: var(--gray-300);
}

.empty-cart h2 {
    font-size: 1.5rem;
    color: var(--dark);
    margin-bottom: 0.5rem;
}

.empty-cart p {
    color: var(--gray-500);
    margin-bottom: 2rem;
}

.cart-grid {
    display: grid;
    grid-template-columns: 1fr 350px;
    gap: 2rem;
}

@media (max-width: 968px) {
    .cart-grid {
        grid-template-columns: 1fr;
    }
}

.cart-items {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.cart-item {
    display: flex;
    background: white;
    border-radius: var(--radius);
    overflow: hidden;
    box-shadow: var(--shadow);
    transition: var(--transition);
    animation: slideIn 0.5s ease forwards;
    opacity: 0;
    transform: translateY(20px);
}

.cart-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
}

@keyframes slideIn {
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.item-image {
    width: 10rem;   /* largeur fixe pour la colonne image */
    height: 16rem;  /* hauteur fixe */
    flex-shrink: 0; /* évite que ça rétrécisse */
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #eee;
    border-radius: 8px;
    overflow: hidden;
    background: #fff;
}

.product-img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain; /* conserve les proportions */
    display: block;
}

.product-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.item-details {
    flex: 1;
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.item-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}

.product-name {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--dark);
    margin: 0;
}
.delete-btn {
    background: #f8d7da; /* rouge clair */
    border: none;
    border-radius: 50%;
    padding: 8px;
    cursor: pointer;
    transition: all 0.2s ease-in-out;
    display: flex;
    align-items: center;
    justify-content: center;
}

.delete-btn svg {
    width: 20px;
    height: 20px;
    fill: #dc3545; /* rouge vif */
    transition: transform 0.2s ease-in-out, fill 0.2s ease-in-out;
}

/* Hover */
.delete-btn:hover {
    background: #f5c2c7; /* un peu plus foncé */
}

.delete-btn:hover svg {
    transform: scale(1.15);
    fill: #b02a37; /* rouge foncé */
}

/* Effet clic */
.delete-btn:active {
    transform: scale(0.9);
}


.product-variant {
    font-size: 0.875rem;
    color: var(--gray-500);
}

.product-description {
    font-size: 0.9rem;
    color: var(--gray-500);
    margin: 0;
    line-height: 1.5;
}

.price-container {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    flex-wrap: wrap;
}

.current-price {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--dark);
}

.original-price {
    font-size: 1rem;
    color: var(--gray-400);
    text-decoration: line-through;
}

.discount-badge {
    background: var(--success);
    color: white;
    font-size: 0.75rem;
    font-weight: 500;
    padding: 0.25rem 0.5rem;
    border-radius: 20px;
}

.quantity-controls {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 0.5rem;
}

.quantity-selector {
    display: flex;
    align-items: center;
    border: 1px solid var(--gray-200);
    border-radius: 8px;
    overflow: hidden;
}

.qty-btn {
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--gray-100);
    border: none;
    cursor: pointer;
    transition: var(--transition);
}

.qty-btn:hover {
    background: var(--gray-200);
}

.quantity-count {
    width: 40px;
    text-align: center;
    font-weight: 600;
}

.item-total {
    font-size: 1rem;
    color: var(--dark);
}

.order-summary {
    position: sticky;
    top: 2rem;
    height: fit-content;
}

.summary-card {
    background: white;
    border-radius: var(--radius);
    padding: 1.5rem;
    box-shadow: var(--shadow);
    margin-bottom: 1.5rem;
}

.summary-card h3 {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--dark);
    margin: 0 0 1.25rem;
    padding-bottom: 0.75rem;
    border-bottom: 1px solid var(--gray-200);
}

.summary-line {
    display: flex;
    justify-content: space-between;
    margin-bottom: 0.75rem;
    font-size: 0.95rem;
}

.summary-line.discount {
    color: var(--success);
    font-weight: 500;
}

.summary-divider {
    height: 1px;
    background: var(--gray-200);
    margin: 1rem 0;
}

.summary-total {
    display: flex;
    justify-content: space-between;
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--dark);
}

.savings-notice {
    background: rgba(16, 185, 129, 0.1);
    color: var(--success);
    padding: 0.75rem;
    border-radius: 8px;
    margin-top: 1rem;
    font-size: 0.875rem;
    text-align: center;
}

.summary-actions {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.btn-clear, .btn-checkout {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 1rem 1.5rem;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition);
}

.btn-clear {
    background: var(--gray-100);
    color: var(--danger);
}

.btn-clear:hover {
    background: var(--danger);
    color: white;
}

.btn-checkout {
    background: var(--primary);
    color: white;
    box-shadow: 0 4px 10px rgba(245, 6, 196, 0.3);
}

.btn-checkout:hover {
    background: var(--primary-dark);
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(245, 6, 196, 0.4);
}

.security-notice {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    margin-top: 1.5rem;
    padding: 1rem;
    background: var(--gray-100);
    border-radius: 8px;
    color: var(--gray-500);
    font-size: 0.875rem;
}

/* Styles pour les popups */
.popup-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
    opacity: 0;
    visibility: hidden;
    transition: var(--transition);
}

.popup-overlay.active {
    opacity: 1;
    visibility: visible;
}

.popup-content {
    background: white;
    border-radius: var(--radius);
    padding: 2rem;
    max-width: 500px;
    width: 90%;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
    transform: translateY(20px);
    transition: var(--transition);
}

.popup-overlay.active .popup-content {
    transform: translateY(0);
}

.popup-header {
    margin-bottom: 1.5rem;
}

.popup-header h3 {
    font-size: 1.5rem;
    margin: 0;
    color: var(--dark);
}

.popup-body {
    margin-bottom: 2rem;
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.popup-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
}

.btn {
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition);
}

.btn-primary {
    background: var(--primary);
    color: white;
}

.btn-primary:hover {
    background: var(--primary-dark);
}

.btn-secondary {
    background: var(--gray-200);
    color: var(--dark);
}

.btn-secondary:hover {
    background: var(--gray-300);
}

.btn-success {
    background: var(--success);
    color: white;
}

.btn-success:hover {
    background: #0da271;
}

.btn-warning {
    background: var(--warning);
    color: white;
}

.btn-warning:hover {
    background: #e58c08;
}

.form-control {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid var(--gray-300);
    border-radius: 8px;
    margin-bottom: 1rem;
    transition: var(--transition);
}

.form-control:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(245, 6, 196, 0.1);
}

/* Animation pour l'apparition des articles */
.cart-item:nth-child(1) { animation-delay: 0.1s; }
.cart-item:nth-child(2) { animation-delay: 0.2s; }
.cart-item:nth-child(3) { animation-delay: 0.3s; }
.cart-item:nth-child(4) { animation-delay: 0.4s; }
.cart-item:nth-child(5) { animation-delay: 0.5s; }

/* Responsive */
@media (max-width: 768px) {
    .cart-container {
        padding: 0 1rem;
    }
    
    .cart-title {
        font-size: 2rem;
    }
    
    .cart-item {
        flex-direction: column;
    }
    
    .item-image {
        width: 100%;
        height: 200px;
    }
    
    .quantity-controls {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .progress-step:not(:last-child)::after {
        display: none;
    }
    
    .progress-step {
        padding: 0 0.5rem;
    }
    
    .step-label {
        font-size: 0.75rem;
    }
}
</style>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Animation d'entrée des éléments
    const animateElements = () => {
        const cartItems = document.querySelectorAll('.cart-item');
        cartItems.forEach((item, index) => {
            item.style.animationDelay = `${index * 0.1}s`;
        });
    };
    
    animateElements();
    
    // Gestion des popups
    const showPopup = (id) => {
        document.getElementById(id).classList.add('active');
    };
    
    const hidePopup = (id) => {
        document.getElementById(id).classList.remove('active');
    };
    
    // Supprimer un produit
    document.querySelectorAll(".deleteProductBtn").forEach(btn => {
        btn.addEventListener("click", function(e) {
            e.preventDefault();
            let id = this.dataset.id;
            const item = this.closest('.cart-item');
            
            // Animation de suppression
            item.style.transform = 'translateX(100px)';
            item.style.opacity = '0';
            item.style.transition = 'all 0.3s ease';
            
            setTimeout(() => {
                fetch(`/cart/remove/${id}`, {
                    method: "DELETE",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Accept": "application/json"
                    }
                })
                .then(res => res.json())
                .then(data => {
                    item.remove();
                    showNotification(data.message, 'success');
                    if (document.querySelectorAll('.cart-item').length === 0) {
                        location.reload();
                    }
                })
                .catch(err => console.error(err));
            }, 300);
        });
    });

    // Vider le panier
    document.getElementById("clearCartBtn")?.addEventListener("click", function() {
        if(!confirm("Voulez-vous vraiment vider le panier ?")) return;
        
        const items = document.querySelectorAll('.cart-item');
        items.forEach((item, index) => {
            setTimeout(() => {
                item.style.transform = 'translateX(100px)';
                item.style.opacity = '0';
            }, index * 100);
        });
        
        setTimeout(() => {
            fetch(`/cart/clear`, {
                method: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Accept": "application/json"
                }
            })
            .then(res => res.json())
            .then(data => {
                showNotification(data.message, 'success');
                setTimeout(() => location.reload(), 500);
            })
            .catch(err => console.error(err));
        }, items.length * 100 + 300);
    });

    // Augmenter quantité
    document.querySelectorAll(".increaseQty").forEach(btn => {
        btn.addEventListener("click", function() {
            let id = this.dataset.id;

            fetch(`/cart/update/${id}`, {
                method: "PUT",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Content-Type": "application/json",
                    "Accept": "application/json"
                },
                body: JSON.stringify({ action: "increase" })
            })
            .then(res => res.json())
            .then(data => {
                document.getElementById(`count-${id}`).textContent = data.quantity;
                updateTotals();
            })
            .catch(err => console.error(err));
        });
    });

    // Diminuer quantité
    document.querySelectorAll(".decreaseQty").forEach(btn => {
        btn.addEventListener("click", function() {
            let id = this.dataset.id;

            fetch(`/cart/update/${id}`, {
                method: "PUT",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Content-Type": "application/json",
                    "Accept": "application/json"
                },
                body: JSON.stringify({ action: "decrease" })
            })
            .then(res => res.json())
            .then(data => {
                document.getElementById(`count-${id}`).textContent = data.quantity;
                updateTotals();
            })
            .catch(err => console.error(err));
        });
    });

    // Fonction pour recalculer le total
    function updateTotals() {
        let subtotal = 0;
        let originalTotal = 0;

        document.querySelectorAll(".cart-item").forEach(item => {
            let priceEl = item.querySelector(".current-price");
            let originalPriceEl = item.querySelector(".original-price");
            let qty = parseInt(item.querySelector(".quantity-count").textContent);

            let price = parseFloat(priceEl.textContent.replace(/[^0-9]/g, ""));
            let originalPrice = originalPriceEl ? parseFloat(originalPriceEl.textContent.replace(/[^0-9]/g, "")) : price;

            // total pour cet item
            subtotal += price * qty;
            originalTotal += originalPrice * qty;

            // mettre à jour le total affiché de l'article
            let itemTotalEl = item.querySelector(".item-total strong");
            if (itemTotalEl) {
                itemTotalEl.textContent = (price * qty).toLocaleString("fr-FR") + " FCFA";
            }
        });

        // Calcul de la réduction
        let discount = originalTotal - subtotal;
        let delivery = 13; // fixe pour l'instant

        // mettre à jour les éléments du résumé
        document.querySelector(".summary-line span:last-child").textContent = subtotal.toLocaleString("fr-FR") + " FCFA";

        document.querySelector(".discount span:last-child").textContent = "-" + discount.toLocaleString("fr-FR") + " FCFA";

        document.querySelector(".summary-total span:last-child").textContent = (subtotal + delivery).toLocaleString("fr-FR") + " FCFA";

        document.querySelector(".savings-notice").textContent = "Vous économisez " + discount.toLocaleString("fr-FR") + " FCFA sur cette commande!";
    }

    // Fonction pour afficher les notifications
    function showNotification(message, type) {
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        notification.textContent = message;
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 12px 20px;
            border-radius: 8px;
            color: white;
            font-weight: 500;
            z-index: 10000;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            transform: translateX(100%);
            transition: transform 0.3s ease;
        `;
        
        if (type === 'success') {
            notification.style.background = 'var(--success)';
        } else if (type === 'error') {
            notification.style.background = 'var(--danger)';
        }
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.style.transform = 'translateX(0)';
        }, 100);
        
        setTimeout(() => {
            notification.style.transform = 'translateX(100%)';
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }

    // Gestion du processus de commande
    const checkoutBtn = document.getElementById('checkoutBtn');
    const addressPopup = document.getElementById('addressChoicePopup');
    const manualPopup = document.getElementById('manualAddressPopup');
    const paymentPopup = document.getElementById('paymentChoicePopup');

    if (checkoutBtn) {
        checkoutBtn.addEventListener('click', () => {
            showPopup('addressChoicePopup');
        });
    }

    // Fermer les popups
    document.getElementById('closeAddressPopup')?.addEventListener('click', () => {
        hidePopup('addressChoicePopup');
    });

    document.getElementById('closeManualPopup')?.addEventListener('click', () => {
        hidePopup('manualAddressPopup');
    });

    document.getElementById('closePopup')?.addEventListener('click', () => {
        hidePopup('paymentChoicePopup');
    });

    // Gestion des options d'adresse
    document.querySelectorAll('.addressOption').forEach(btn => {
        btn.addEventListener('click', () => {
            const type = btn.dataset.type;

            if (type === 'current') {
                navigator.geolocation.getCurrentPosition(
                    pos => {
                        const lat = pos.coords.latitude;
                        const lng = pos.coords.longitude;

                        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
                            .then(res => res.json())
                            .then(data => {
                                const fullAddress = data.display_name;
                                enregistrerAdresse({type:'current', full_address:fullAddress, latitude:lat, longitude:lng});
                            })
                            .catch(err => alert("Impossible de récupérer l'adresse: " + err));
                    },
                    err => alert("Impossible d'obtenir votre position: " + err.message)
                );
            } else if (type === 'manual') {
                hidePopup('addressChoicePopup');
                showPopup('manualAddressPopup');
            } else if (type === 'map') {
                window.location.href = "{{ route('address.map') }}";
            }
        });
    });

    // Saisie manuelle d'adresse
    document.getElementById('submitManualAddress')?.addEventListener('click', () => {
        const address = document.getElementById('manualAddress').value;
        const landmarks = document.getElementById('manualLandmarks').value;
        if(!address) { 
            alert("Veuillez saisir l'adresse"); 
            return; 
        }
        enregistrerAdresse({type:'manual', full_address:address, landmarks:landmarks});
    });

    // Enregistrer adresse
    function enregistrerAdresse(data){
        fetch("{{ route('addresses.store') }}", {
            method: 'POST',
            headers: { 
                'Content-Type':'application/json', 
                'X-CSRF-TOKEN': '{{ csrf_token() }}' 
            },
            body: JSON.stringify(data)
        })
        .then(res => res.json())
        .then(resp => {
            if(resp.success){
                hidePopup('manualAddressPopup');
                showPopup('paymentChoicePopup');
            } else {
                alert(resp.message || 'Erreur lors de l\'enregistrement de l\'adresse');
            }
        })
        .catch(err => console.error(err));
    }

    // Boutons paiement
    document.getElementById('cashOnDeliveryBtn')?.addEventListener('click', () => {
        passerCommande('cash_on_delivery');
    });

    document.getElementById('onlinePaymentBtn')?.addEventListener('click', () => {
        passerCommande('mobile_money');
    });

    // Passer la commande
    function passerCommande(paymentMethod){
        fetch(`{{ route('orders.store') }}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ payment_method: paymentMethod })
        })
        .then(res => res.json())
        .then(data => {
            hidePopup('paymentChoicePopup');
            showNotification(data.message, data.success ? 'success' : 'error');
            if(data.success){
                setTimeout(() => {
                    window.location.href = '/mes-commandes';
                }, 1500);
            }
        })
        .catch(err => console.error("Erreur fetch:", err));
    }
});
</script>
@endsection