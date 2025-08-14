@extends('layouts.apli')

@section('content')
<div class="container mt-4">
    <h1>Choisir l'adresse sur la carte</h1>
    <p>Cliquez sur la carte ou recherchez une adresse pour sélectionner votre lieu de livraison.</p>

    <!-- Barre de recherche -->
    <input type="text" id="searchAddress" class="form-control mb-3" placeholder="Rechercher une adresse...">
    <div id="searchResults" class="list-group mb-3" style="display:none; max-height:200px; overflow-y:auto;"></div>

    <div id="map" style="height: 500px; border:1px solid #ccc; border-radius:8px;"></div>

    <div class="mt-3">
        <p><strong>Adresse sélectionnée :</strong> <span id="selectedAddress">Aucune</span></p>
        <button id="confirmAddressBtn" class="btn btn-success mt-2" disabled>Confirmer cette adresse</button>
        <a href="{{ url()->previous() }}" class="btn btn-secondary mt-2">Annuler</a>
    </div>

    {{-- Popup choix paiement --}}
    <div id="paymentChoicePopup" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; 
                background:rgba(0,0,0,0.5); z-index:9999;">
        <div style="background:#fff; padding:20px; border-radius:10px; max-width:400px; margin:10% auto;">
            <h3>Choisissez votre mode de paiement</h3>
            <button id="cashOnDeliveryBtn" class="btn btn-primary" style="margin-right:10px;">Paiement à la livraison</button>
            <button id="onlinePaymentBtn" class="btn btn-warning">Paiement en ligne</button>
            <br><br>
            <button id="closePaymentPopup" class="btn btn-secondary">Annuler</button>
        </div>
    </div>
</div>

<!-- Leaflet JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const csrfToken = '{{ csrf_token() }}';
    let selectedLat = null, selectedLng = null, selectedAddress = null, selectedAddressId = null, marker = null;

    const paymentPopup = document.getElementById('paymentChoicePopup');
    const cashBtn = document.getElementById('cashOnDeliveryBtn');
    const onlineBtn = document.getElementById('onlinePaymentBtn');
    const closePaymentPopup = document.getElementById('closePaymentPopup');

    // Initialisation carte Leaflet centrée sur Cotonou
    var map = L.map('map').setView([6.370293, 2.391236], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    // Fonction pour définir l'adresse sélectionnée
    function setSelectedLocation(lat, lng, address) {
        selectedLat = lat;
        selectedLng = lng;
        selectedAddress = address;
        document.getElementById('selectedAddress').textContent = selectedAddress;
        document.getElementById('confirmAddressBtn').disabled = false;

        if(marker) map.removeLayer(marker);
        marker = L.marker([lat, lng]).addTo(map);
        map.setView([lat, lng], 15);
    }

    // Clique sur la carte → géocodage inversé
    map.on('click', function(e) {
        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${e.latlng.lat}&lon=${e.latlng.lng}`)
            .then(res => res.json())
            .then(data => setSelectedLocation(e.latlng.lat, e.latlng.lng, data.display_name))
            .catch(err => alert("Impossible de récupérer l'adresse: " + err));
    });

    // Recherche d'adresse
    document.getElementById('searchAddress').addEventListener('input', function() {
        const query = this.value.trim();
        const resultsDiv = document.getElementById('searchResults');

        if(query.length < 3) {
            resultsDiv.style.display = 'none';
            resultsDiv.innerHTML = '';
            return;
        }

        fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&addressdetails=1&limit=5`)
            .then(res => res.json())
            .then(data => {
                resultsDiv.innerHTML = '';
                if(data.length > 0) {
                    data.forEach(item => {
                        const div = document.createElement('a');
                        div.href = '#';
                        div.className = 'list-group-item list-group-item-action';
                        div.textContent = item.display_name;
                        div.addEventListener('click', (e) => {
                            e.preventDefault();
                            setSelectedLocation(item.lat, item.lon, item.display_name);
                            resultsDiv.style.display = 'none';
                            resultsDiv.innerHTML = '';
                        });
                        resultsDiv.appendChild(div);
                    });
                    resultsDiv.style.display = 'block';
                } else {
                    resultsDiv.style.display = 'none';
                }
            });
    });

    // Confirmer l'adresse → enregistrer puis ouvrir popup paiement
    document.getElementById('confirmAddressBtn').addEventListener('click', function() {
        if(!selectedLat || !selectedLng || !selectedAddress) return;

        fetch("{{ route('addresses.store') }}", {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify({
                type: 'map',
                full_address: selectedAddress,
                latitude: selectedLat,
                longitude: selectedLng
            })
        })
        .then(res => res.json())
        .then(data => {
            if(data.success){
                selectedAddressId = data.delivery_addresses_id;
                paymentPopup.style.display = 'block';
            } else {
                alert(data.message || 'Erreur lors de l\'enregistrement');
            }
        })
        .catch(err => console.error(err));
    });

    // Fermeture popup paiement
    if(closePaymentPopup) closePaymentPopup.addEventListener('click', () => paymentPopup.style.display = 'none');

    // Choix mode de paiement
    [cashBtn, onlineBtn].forEach(btn => {
        if(btn){
            btn.addEventListener('click', () => {
                const method = btn.id === 'cashOnDeliveryBtn' ? 'cash_on_delivery' : 'mobile_money';
                passerCommande(method, selectedAddressId);
            });
        }
    });

    // Passer la commande
    function passerCommande(paymentMethod, addressId){
        if(!addressId){
            alert("Adresse non sélectionnée !");
            return;
        }

        fetch(`{{ route('orders.store') }}`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
            body: JSON.stringify({ payment_method: paymentMethod, delivery_address_id: addressId })
        })
        .then(res => res.json())
        .then(data => {
            alert(data.message || 'Commande enregistrée');
            if(data.success){
                window.location.href = '/mes-commandes';
            }
        })
        .catch(err => console.error(err));
    }
});
</script>
@endsection
