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
        <button id="checkoutBtn" class="btn btn-success mt-3">Passer la commande</button>

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
