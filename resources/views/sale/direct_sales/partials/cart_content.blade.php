@if($cartItems->isEmpty())
        <div class="empty-cart">
            <div class="empty-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <h2>Votre panier est vide</h2>
            <p>Parcourez nos produits et ajoutez des articles à votre panier</p>
            <a href="{{ route('client.shop') }}" class="btn btn-primary">Découvrir nos produits</a>
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
                @php
                    $productData = [
                        "id" => $item->product->id,
                        "name" => $item->product->name,
                        "price" => $item->product->price,
                        "discount_price" => $item->product->discount_price,
                        "discounts" => $item->product->discounts->map(function($d){
                            return ["min_quantity" => $d->min_quantity, "price" => $d->price];
                        })->toArray(),
                    ];
                @endphp

   
            @endforeach
        </section>

        <!-- Résumé commande -->
        <aside class="order-summary">
                            <h3>Résumé de la commande</h3>

            <div class="summary-card">

                <div class="summary-line">
                    <span>Sous-total ({{ $cartItems->count() }} article{{ $cartItems->count() > 1 ? 's' : '' }})</span>
                    <span>{{ number_format($subtotale, 0, ',', ' ') }} FCFA</span>
                </div>

                <div class="summary-line discount">
                    <span>Réduction</span>
                    <span>-{{ number_format($subtotale - $subtotal, 0, ',', ' ') }} FCFA</span>
                </div>

                <div class="summary-divider"></div>

                
                
            </div>
            <div class="summary-total">
                    <span>Total</span>
                    <span>{{ number_format($subtotal, 0, ',', ' ') }} FCFA</span>
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
            
            
        </aside>
    </div>
    
   
    <!-- Popup choix paiement -->
    <div id="paymentChoicePopup" class="popup-overlay">
        <div class="popup-content">
            <div class="popup-header">
                <h3>Choisissez votre mode de paiement</h3>
            </div>
            <div class="popup-body">
                <button id="cashOnDeliveryBtn" class="btn btn-primary">Paiement en cash</button>
                <button id="onlinePaymentBtn" class="btn btn-warning">Paiement en ligne</button>
            </div>
            <div class="popup-actions">
                <button id="closePopup" class="btn btn-secondary">Annuler</button>
            </div>
        </div>
    </div>
    @endif


<style>
/* ----- Container principal ----- */
.order-summary {
    width: 76.3%;
    margin: 0 auto 20px;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    padding: 10px;
    display: flex;
    flex-direction: column;
    gap: 20px;
    position: fixed;       /* Fixe l'élément */
    top: 85px;
    
}

/* ----- Carte ----- */
.summary-card h3 {
    font-size: 1.3rem;
    font-weight: 600;
    margin-bottom: 5px;
    color: #333;
}

/* Ligne sous-total / réduction */
.summary-line {
    display: flex;
    justify-content: space-between;
    margin: 0;
    font-size: 0.95rem;
    color: #444;
}

.summary-line.discount {
    color: #e53935; /* rouge pour réduction */
}
/* Conteneur des lignes */
.order-summary .summary-card {
    display: flex;
    flex-wrap: wrap; /* pour que ça passe en colonne sur mobile */
    gap: 20px;       /* espace entre les lignes */
}

/* Chaque ligne garde sa structure interne */
.summary-line {
    flex: 1; /* chaque div prend la moitié de la largeur si deux divs */
    display: flex;
    justify-content: space-between; /* texte à gauche, prix à droite */
    align-items: center;
    padding: 5px 0;
    background: #f9f9f9; /* optionnel : pour mieux voir */
    border-radius: 6px;
}


/* Séparateur */
.summary-divider {
    border-top: 1px solid #ddd;
    margin: 2px 0;
}

/* Ligne total */
.summary-total {
    display: flex;
    justify-content: space-between;
    font-size: 1.1rem;
    font-weight: 700;
    color: #222;
}

/* ----- Boutons ----- */
.summary-actions {
    display: flex;
    justify-content: space-between;
    gap: 12px;
}

.summary-actions button {
    flex: 1;
    padding: 8px 10px;
    border-radius: 8px;
    font-size: 0.95rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    cursor: pointer;
    border: none;
    transition: 0.3s ease;
}

/* Bouton vider */
.btn-clear {
    background: #f5f5f5;
    color: #e53935;
    border: 1px solid #ddd;
}

.btn-clear:hover {
    background: #ffeaea;
}

/* Bouton commander */
.btn-checkout {
    background: #4caf50;
    color: #fff;
}

.btn-checkout:hover {
    background: #43a047;
}

/* ----- Responsive ----- */
@media (max-width: 768px) {
    .order-summary {
        max-width: 100%;
        padding: 15px;
    }

    .summary-actions {
        flex-direction: column;
    }

    .summary-actions button {
        width: 100%;
    }
}


        /* Popups */
        .popup-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.6);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s;
        }

        .popup-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .popup-content {
            background: #fff;
            border-radius: 12px;
            padding: 25px;
            max-width: 450px;
            width: 90%;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            transform: translateY(20px);
            transition: transform 0.3s ease;
        }

        .popup-overlay.active .popup-content {
            transform: translateY(0);
        }

        .popup-header {
            margin-bottom: 15px;
        }

        .popup-header h3 {
            font-size: 1.3rem;
        }

        .popup-body {
            margin-bottom: 20px;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .popup-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        .btn-secondary {
            background: var(--gray-200);
            color: var(--dark);
        }

        .btn-secondary:hover {
            background: var(--gray-300);
        }

        /* Notifications */
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 20px;
            border-radius: 8px;
            color: white;
            font-weight: 500;
            z-index: 1100;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            transform: translateX(100%);
            transition: transform 0.3s ease;
        }

        .notification.success {
            background: var(--success);
        }

        .notification.error {
            background: var(--danger);
        }

        .notification.active {
            transform: translateX(0);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .cart-item {
                flex-direction: column;
            }
            
            .item-image {
                width: 100%;
                height: 200px;
            }
            
            .popup-actions {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
                justify-content: center;
            }
        }

        @media (max-width: 480px) {
            .container {
                padding: 10px;
            }
            
            .order-summary {
                padding: 20px 15px;
            }
            
            .popup-content {
                padding: 20px 15px;
            }
        }
    </style>

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        // Variables globales
        let selectedAddressId = null;
        
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
        
        // Rafraîchir le panier (simulation)
        function refreshCart() {
            // Cette fonction pourrait recharger les données du panier depuis le serveur
            console.log("Panier rafraîchi");
        }
        
        
        // Fonction pour recalculer le total
        function updateTotals() {
            let subtotal = 0;
            let originalTotal = 0;

            document.querySelectorAll(".cart-item").forEach(item => {
                let qty = parseInt(item.querySelector(".quantity-count").textContent);
                let product = JSON.parse(item.dataset.product);
                
                let unitPrice = product.discount_price || product.price;
                let originalPrice = product.price;

                // Vérifier les réductions par quantité
                if (product.discounts && product.discounts.length > 0) {
                    // Trier par quantité minimale (décroissant) pour trouver la meilleure offre
                    let applicableDiscount = product.discounts
                        .filter(d => qty >= d.min_quantity)
                        .sort((a, b) => b.min_quantity - a.min_quantity)[0];
                    
                    if (applicableDiscount) {
                        unitPrice = applicableDiscount.price;
                    }
                }

                subtotal += unitPrice * qty;
                originalTotal += originalPrice * qty;

                // Mettre à jour le total de cet item
                let itemTotalEl = item.querySelector(".item-total strong");
                if (itemTotalEl) {
                    itemTotalEl.textContent = (unitPrice * qty).toLocaleString("fr-FR") + " FCFA";
                }
            });

            let discount = originalTotal - subtotal;

            // Mettre à jour les éléments du résumé
            document.querySelector(".summary-line span:last-child").textContent = subtotal.toLocaleString("fr-FR") + " FCFA";
            document.querySelector(".discount span:last-child").textContent = "-" + discount.toLocaleString("fr-FR") + " FCFA";
            document.querySelector(".summary-total span:last-child").textContent = subtotal.toLocaleString("fr-FR") + " FCFA";

            // Mettre à jour le message d'économie
            let savingsEl = document.querySelector(".savings-notice");
            if (savingsEl) {
                if (discount > 0) {
                    savingsEl.textContent = "Vous économisez " + discount.toLocaleString("fr-FR") + " FCFA sur cette commande!";
                    savingsEl.style.display = "block";
                } else {
                    savingsEl.style.display = "none";
                }
            }
        }

        // Fonction pour afficher les notifications
        function showNotification(message, type) {
            const notification = document.createElement('div');
            notification.className = `notification ${type}`;
            notification.textContent = message;
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.classList.add('active');
            }, 100);
            
            setTimeout(() => {
                notification.classList.remove('active');
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }

        // Gestion du processus de commande avec délégation
document.addEventListener("click", function(e) {
    if (e.target.closest("#checkoutBtn")) {
        showPopup("paymentChoicePopup");
    }
});

// Fermer les popups
document.addEventListener("click", function(e) {
    if (e.target.closest("#closePopup")) {
        hidePopup("paymentChoicePopup");
    }
});

// Boutons paiement
document.addEventListener("click", function(e) {
    if (e.target.closest("#cashOnDeliveryBtn")) {
        passerCommande("cash");
    }
    if (e.target.closest("#onlinePaymentBtn")) {
        passerCommande("mobile_money");
    }
});


        // Passer la commande
        function passerCommande(paymentMethod){
        
        fetch(`{{ route('sale.direct_sales') }}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ 
                payment_method: paymentMethod, 
            })
        })
        .then(res => res.json())
        .then(data => {
            hidePopup('paymentChoicePopup');
            showNotification(data.message, data.success ? 'success' : 'error');
            if(data.success){
                setTimeout(() => window.location.href = '/sales/vente', 1500);
            }
        })
        .catch(err => console.error("Erreur fetch:", err));
    }
    });
    </script>