@extends('layouts.apli')

@section('content')
<div class="container mt-4">
    <h2>🛒 Mon Panier</h2>

    @if($cartItems->isEmpty())
        <p>Votre panier est vide.</p>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Produit</th>
                    <th>Variante</th>
                    <th>Quantité</th>
                    <th>Prix Unitaire</th>
                    <th>Total</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="cartTableBody">
                @php $grandTotal = 0; @endphp
                @foreach($cartItems as $item)
                    @php
                        $price = $item->price;
                        $total = $price * $item->quantity;
                        $grandTotal += $total;
                        $formattedPrice = number_format($price, 2, '.', '');
                        $formattedTotal = number_format($total, 2, '.', '');
                    @endphp
                    <tr data-id="{{ $item->id }}">
                        <td>{{ $item->product->name }}</td>
                        <td>{{ $item->variant ? $item->variant->name : '-' }}</td>
                        <td>
                            <input type="number" min="1" value="{{ $item->quantity }}" class="form-control quantity-input">
                        </td>
                        <td>{{ number_format($formattedPrice, 2, ',', ' ') }} FCFA</td>
                        <td>{{ number_format($formattedTotal, 2, ',', ' ') }} FCFA</td>
                        <td>
                            <button class="btn btn-sm btn-success updateBtn">Mettre à jour</button>
                            <button class="btn btn-sm btn-danger removeBtn">Supprimer</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="d-flex justify-content-end">
            <h4>Total général : 
                <span style="color: green;">
                    {{ number_format($grandTotal, 0, ',', ' ') }} FCFA
                </span>
            </h4>
        </div>

        <button id="clearCartBtn" class="btn btn-warning mt-3">Vider le panier</button>
        <button id="checkoutBtn" class="btn btn-success">Passer la commande</button>

        <!-- Popup choix paiement -->
        <div id="paymentChoicePopup" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5);">
            <div style="background:#fff; padding:20px; border-radius:10px; max-width:400px; margin:10% auto;">
                <h3>Choisissez votre mode de paiement</h3>
                <button id="cashOnDeliveryBtn" class="btn btn-primary" style="margin-right:10px;">Paiement à la livraison</button>
                <button id="onlinePaymentBtn" class="btn btn-warning">Paiement en ligne</button>
                <br><br>
                <button id="closePopup" class="btn btn-secondary">Annuler</button>
            </div>
        </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    console.log("✅ DOM chargé, initialisation des listeners...");

    const csrfToken = '{{ csrf_token() }}';

    // Boutons du popup commande
    const checkoutBtn = document.getElementById('checkoutBtn');
    const popup = document.getElementById('paymentChoicePopup');
    const closePopup = document.getElementById('closePopup');
    const cashBtn = document.getElementById('cashOnDeliveryBtn');
    const onlineBtn = document.getElementById('onlinePaymentBtn');

    if (checkoutBtn) {
        checkoutBtn.addEventListener('click', () => {
            console.log("🛒 Bouton 'Passer la commande' cliqué");
            popup.style.display = 'block';
        });
    }

    if (closePopup) {
        closePopup.addEventListener('click', () => {
            console.log("❌ Fermeture popup paiement");
            popup.style.display = 'none';
        });
    }

    if (cashBtn) {
        cashBtn.addEventListener('click', () => {
            console.log("💵 Paiement à la livraison choisi");
            passerCommande('cash_on_delivery');
        });
    }

    if (onlineBtn) {
        onlineBtn.addEventListener('click', () => {
            console.log("📱 Paiement mobile money choisi");
            passerCommande('mobile_money');
        });
    }

    function passerCommande(paymentMethod) {
        console.log(`📤 Envoi de la commande (méthode: ${paymentMethod})`);
        fetch(`{{ route('orders.store') }}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ payment_method: paymentMethod })
        })
        .then(res => {
            console.log("📥 Réponse brute:", res.status, res.statusText);
            return res.text();
        })
        .then(text => {
            console.log("📦 Contenu brut:", text);
            try {
                const data = JSON.parse(text);
                alert(data.message || 'Commande traitée');
                if (data.success) {
                    window.location.href = '/mes-commandes';
                }
            } catch (e) {
                console.error("⚠️ Réponse non-JSON, peut-être une erreur Laravel:", e);
            }
        })
        .catch(err => console.error("❌ Erreur fetch:", err));
    }

    // Boutons mise à jour panier
    document.querySelectorAll(".updateBtn").forEach(btn => {
        btn.addEventListener("click", function () {
            let row = this.closest("tr");
            let id = row.dataset.id;
            let quantity = row.querySelector(".quantity-input").value;
            console.log(`✏️ Mise à jour panier: ID=${id}, Qte=${quantity}`);

            fetch(`/cart/update/${id}`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ quantity })
            })
            .then(res => res.json())
            .then(data => {
                console.log("📥 Réponse update:", data);
                alert(data.message);
                location.reload();
            })
            .catch(err => console.error("❌ Erreur maj:", err));
        });
    });

    // Boutons suppression produit
    document.querySelectorAll(".removeBtn").forEach(btn => {
        btn.addEventListener("click", function () {
            if (!confirm("Supprimer ce produit du panier ?")) return;

            let row = this.closest("tr");
            let id = row.dataset.id;
            console.log(`🗑 Suppression produit: ID=${id}`);

            fetch(`/cart/remove/${id}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': csrfToken }
            })
            .then(res => res.json())
            .then(data => {
                console.log("📥 Réponse suppression:", data);
                alert(data.message);
                row.remove();
                location.reload();
            })
            .catch(err => console.error("❌ Erreur suppression:", err));
        });
    });

    // Bouton vider panier
    const clearBtn = document.getElementById("clearCartBtn");
    if (clearBtn) {
        clearBtn.addEventListener("click", function () {
            if (!confirm("Vider tout le panier ?")) return;
            console.log("🗑 Vider tout le panier");

            fetch(`/cart/clear`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': csrfToken }
            })
            .then(res => res.json())
            .then(data => {
                console.log("📥 Réponse vider panier:", data);
                alert(data.message);
                location.reload();
            })
            .catch(err => console.error("❌ Erreur vider panier:", err));
        });
    }
});
</script>
@endsection
