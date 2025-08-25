@extends('layouts.clients.client')

@section('title', 'Mon Panier')

@section('content')
<div class="wrap">
    <div class="title">YOUR CART</div>

    @if($cartItems->isEmpty())
        <p>Votre panier est vide.</p>
    @else
    <div class="grid">
        <!-- Liste des produits -->
        <section class="card cart">
            @php $subtotal = 0; $subtotale = 0; @endphp
            @foreach($cartItems as $item)
                @php
                    $price = $item->price;
                    $total = $price * $item->quantity;
                    $totale = $item->product->price * $item->quantity;
                    $subtotal += $total;
                    $subtotale += $totale;
                @endphp

                <article class="item">
                    <div class="thumb">
                        <img src="{{ asset('storage/'.$item->product->image) }}" alt="{{ $item->product->name }}">
                    </div>

                    <div class="meta">
                        <div>
                            <h4 class="name">{{ $item->product->name }}</h4>
                            <div class="sub">variant : {{ $item->variant ? $item->variant->name : '-' }}</div>
                            <div class="row">
                                <span class="sub">description : {{ $item->product->description }}</span>
                            </div>
                            @if($item->product->discount_price)
                            <span class="price">{{ number_format($item->product->discount_price, 0, ',', ' ') }} FCFA</span>
                            <span class="old-price">{{ number_format($item->product->price, 0, ',', ' ') }} FCFA</span>
                        @else
                            <span class="old-price">{{ number_format($price, 0, ',', ' ') }} FCFA</span>

                        @endif
                        </div>

                        <button class="trash deleteProductBtn" data-id="{{ $item->id }}" title="Supprimer">
    <svg viewBox="0 0 24 24" aria-hidden="true">
        <path d="M9 3h6a1 1 0 0 1 1 1v1h4v2h-1v12a3 3 0 0 1-3 3H8a3 3 0 0 1-3-3V7H4V5h4V4a1 1 0 0 1 1-1Zm8 4H7v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V7Zm-8 3h2v8H9v-8Zm4 0h2v8h-2v-8ZM10 5v0h4V5h-4Z"/>
    </svg>
</button>


                        <div class="qty" style="grid-column: 1 / -1;">
    <div class="pill">
        <button class="btn decreaseQty" data-id="{{ $item->id }}">−</button>
        <span class="count" id="count-{{ $item->id }}">{{ $item->quantity }}</span>
        <button class="btn increaseQty" data-id="{{ $item->id }}">+</button>
    </div>
</div>

                    </div>
                </article>
            @endforeach
        </section>

        <!-- Résumé commande -->
        <aside class="card summary">
            <div class="sum-card">
                <h3>Order Summary</h3>

                <div class="line">
                    <span>Subtotal</span>
                    <strong>{{ number_format($subtotal, 0, ',', ' ') }} FCFA</strong>
                </div>

                <div class="line">
                    <span>Discount</span>
                    <strong style="color:#16a34a">{{ (($subtotale - $subtotal)/$subtotale)*100 }}%</strong>
                </div>

                <div class="line">
                    <span>Delivery Fee</span>
                    <strong>13 FCFA</strong>
                </div>

                <div class="divider"></div>

                <div class="total">
                    <span>Total</span>
                    <span>{{ number_format($subtotal, 0, ',', ' ') }} FCFA</span>
                </div>
            </div>
            <div class="actions" style="margin-top:20px; display:flex; justify-content:space-between; gap:10px;">
    <button id="clearCartBtn" class="btn-danger" style="padding:10px 18px; border:none; border-radius:12px; background:#ef4444; color:#fff; cursor:pointer;">
        Vider le panier
    </button>
            <button id="checkoutBtn" class="btn btn-success mt-3" style="padding:10px 18px; border:none; border-radius:12px; background:#44ef6f; color:#fff; cursor:pointer;">Passer la commande</button>

</div>


        </aside>
    </div>
     <!-- Popup choix adresse -->
        <div id="addressChoicePopup" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5);">
            <div style="background:#fff; padding:20px; border-radius:10px; max-width:400px; margin:10% auto;">
                <h3>Choisissez votre adresse de livraison</h3>
                <button class="addressOption btn btn-primary" data-type="current">Adresse actuelle</button>
                <button class="addressOption btn btn-warning" data-type="manual">Saisie manuelle</button>
                <button class="addressOption btn btn-success" data-type="map">Choisir sur la carte</button>
                <br><br>
                <button id="closeAddressPopup" class="btn btn-secondary">Annuler</button>
            </div>
        </div>

        <!-- Popup saisie manuelle -->
        <div id="manualAddressPopup" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5);">
            <div style="background:#fff; padding:20px; border-radius:10px; max-width:500px; margin:10% auto;">
                <h4>Entrez votre adresse</h4>
                <textarea id="manualAddress" class="form-control" rows="3" placeholder="Quartier, ville, repères..."></textarea>
                <input type="text" id="manualLandmarks" class="form-control mt-2" placeholder="Repères supplémentaires (optionnel)">
                <button id="submitManualAddress" class="btn btn-success mt-2">Valider l'adresse</button>
                <button id="closeManualPopup" class="btn btn-secondary mt-2">Annuler</button>
            </div>
        </div>

        {{-- validation commande --}}
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


{{-- === CSS === --}}
<style>
  
  

  body{
    font-family: 'Inter', sans-serif;
    background: var(--bg);
    margin:0;
  }
  .old-price { text-decoration: line-through; color: #888; font-size: 0.9em; margin-left: 5px; }

  .wrap{ max-width: 1100px; margin: 40px auto; padding: 0 18px; }
  .title{ font-size: 32px; font-weight: 800; }

  .grid{ display:grid; grid-template-columns: 1.4fr .9fr; gap:32px; margin-top:18px; }
  @media(max-width:920px){ .grid{ grid-template-columns:1fr; } }

  .card{ background:var(--card); border-radius:var(--radius); box-shadow:var(--shadow); }

  .cart{ padding:16px; }
  .item{ display:grid; grid-template-columns:92px 1fr; gap:16px; padding:14px; border-radius:18px; }
  .item + .item{ border-top:1px solid var(--line); margin-top:10px; padding-top:24px; }

  .thumb{ width:92px; height:92px; border-radius:18px; overflow:hidden; }
  .thumb img{ width:100%; height:100%; object-fit:cover; }

  .meta{ display:grid; grid-template-columns:1fr auto; gap:10px 16px; }
  .name{ font-weight:700; margin:0; }
  .sub{ color:var(--sub); font-size:14px; }
  .row{ display:flex; align-items:center; gap:10px; }
  .swatch{ width:28px; height:28px; border-radius:999px; background:var(--ok); display:inline-block; }

  .price{ font-size:20px; font-weight:700; margin-top:4px; }

  .trash{ background:none; border:none; cursor:pointer; }
  .trash svg{ width:18px; height:18px; fill:var(--danger); }

  .pill{ display:flex; align-items:center; justify-content:space-between; background:var(--pill); border-radius:999px; padding:8px 12px; width:140px; }
  .btn{ width:34px; height:34px; border:none; border-radius:50%; background:#fff; box-shadow:0 1px 2px rgba(0,0,0,.1); font-size:20px; cursor:pointer; }
  .count{ font-weight:600; }

  .summary{ padding:18px; }
  .sum-card{ border-radius:var(--radius); padding:18px 16px; border:1px solid var(--line); box-shadow:var(--shadow); }
  .line{ display:flex; justify-content:space-between; padding:10px 6px; }
  .divider{ height:1px; background:var(--line); margin:12px 6px; }
  .total{ display:flex; justify-content:space-between; font-size:22px; font-weight:800; }
</style>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Supprimer un produit
    document.querySelectorAll(".deleteProductBtn").forEach(btn => {
        btn.addEventListener("click", function(e) {
            e.preventDefault();
            let id = this.dataset.id;

            fetch(`/cart/remove/${id}`, {
                method: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Accept": "application/json"
                }
            })
            .then(res => res.json())
            .then(data => {
                this.closest(".item").remove(); // enlève l'article du DOM
                alert(data.message);
                location.reload(); // recharge pour mettre à jour total (optionnel)
            })
            .catch(err => console.error(err));
        });
    });

    // Vider le panier
    document.getElementById("clearCartBtn")?.addEventListener("click", function() {
        if(!confirm("Voulez-vous vraiment vider le panier ?")) return;

        fetch(`/cart/clear`, {
            method: "DELETE",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Accept": "application/json"
            }
        })
        .then(res => res.json())
        .then(data => {
            alert(data.message);
            location.reload(); // recharge pour voir le panier vide
        })
        .catch(err => console.error(err));
    });
});
</script>

<script>
document.addEventListener("DOMContentLoaded", function() {
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

    // 🔹 Fonction pour recalculer le total
    function updateTotals() {
        let total = 0;
        document.querySelectorAll(".item").forEach(item => {
            let price = parseFloat(item.querySelector(".price").textContent.replace(/[^0-9]/g, ""));
            let qty = parseInt(item.querySelector(".count").textContent);
            total += price * qty;
        });
        document.querySelector(".total span:last-child").textContent = total.toLocaleString() + " FCFA";
        document.querySelector(".line strong").textContent = total.toLocaleString() + " FCFA";
    }
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const csrfToken = '{{ csrf_token() }}';
    
    const checkoutBtn = document.getElementById('checkoutBtn');

    const paymentPopup = document.getElementById('paymentChoicePopup');
    const closePaymentPopup = document.getElementById('closePopup');
    const cashBtn = document.getElementById('cashOnDeliveryBtn');
    const onlineBtn = document.getElementById('onlinePaymentBtn');

    const addressPopup = document.getElementById('addressChoicePopup');
    const manualPopup = document.getElementById('manualAddressPopup');

    let selectedAddressId = null; // On stockera l'ID de l'adresse ici

    // --- 1️⃣ Ouverture popup adresse ---
    checkoutBtn.addEventListener('click', () => {
        addressPopup.style.display = 'block';
    });

    // --- 2️⃣ Fermer popup adresse ---
    document.getElementById('closeAddressPopup').addEventListener('click', () => {
        addressPopup.style.display = 'none';
    });

    // --- 3️⃣ Choix option adresse ---
    document.querySelectorAll('.addressOption').forEach(btn => {
        btn.addEventListener('click', () => {
            const type = btn.dataset.type;

            if(type === 'current'){
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
            } else if(type === 'manual'){
                manualPopup.style.display = 'block';
                addressPopup.style.display = 'none';
            } else if(type === 'map'){
                window.location.href = "{{ route('address.map') }}";
            }
        });
    });

    // --- 4️⃣ Saisie manuelle ---
    document.getElementById('submitManualAddress').addEventListener('click', () => {
        const address = document.getElementById('manualAddress').value;
        const landmarks = document.getElementById('manualLandmarks').value;
        if(!address) { alert("Veuillez saisir l'adresse"); return; }
        enregistrerAdresse({type:'manual', full_address:address, landmarks:landmarks});
    });

    document.getElementById('closeManualPopup').addEventListener('click', () => {
        manualPopup.style.display = 'none';
    });

    // --- 5️⃣ Enregistrer adresse ---
    function enregistrerAdresse(data){
        fetch("{{ route('addresses.store') }}", {
            method: 'POST',
            headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify(data)
        })
        .then(res => res.json())
        .then(resp => {
            if(resp.success){
                selectedAddressId = resp.delivery_addresses_id; // On récupère l'ID
                addressPopup.style.display = 'none';
                manualPopup.style.display = 'none';
                paymentPopup.style.display = 'block'; // On ouvre le popup paiement
            } else {
                alert(resp.message || 'Erreur lors de l\'enregistrement de l\'adresse');
            }
        })
        .catch(err => console.error(err));
    }

    // --- 6️⃣ Boutons paiement ---
    if(closePaymentPopup){
        closePaymentPopup.addEventListener('click', () => {
            paymentPopup.style.display = 'none';
        });
    }

    [cashBtn, onlineBtn].forEach(btn => {
        if(btn){
            btn.addEventListener('click', () => {
                const paymentMethod = btn.id === 'cashOnDeliveryBtn' ? 'cash_on_delivery' : 'mobile_money';
                passerCommande(paymentMethod, selectedAddressId);
            });
        }
    });

    // --- 7️⃣ Passer la commande avec l'adresse ---
    function passerCommande(paymentMethod, addressId){
        if(!addressId){
            alert("Adresse non sélectionnée !");
            return;
        }

        fetch(`{{ route('orders.store') }}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ payment_method: paymentMethod, delivery_address_id: addressId })
        })
        .then(res => res.json())
        .then(data => {
            alert(data.message || 'Commande traitée');
            if(data.success){
                window.location.href = '/mes-commandes';
            }
        })
        .catch(err => console.error("Erreur fetch:", err));
    }
});
</script>


@endsection

