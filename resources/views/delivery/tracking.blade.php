@extends('layouts.apli')

@section('content')
<h1>🚚 Livraison en cours</h1>

<div class="card p-3 mb-3">
    <h4>Informations client</h4>
    <p><strong>Nom :</strong> {{ $order->user->name }}</p>
    <p><strong>Téléphone :</strong> {{ $order->user->phone ?? 'Non renseigné' }}</p>
    <p><strong>Adresse :</strong> {{ $order->deliveryAddress->full_address ?? 'Adresse non précisée' }}</p>
</div>

<form action="{{ route('delivery.fin', $order->id) }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-success">🚚 Livrer cette commande</button>
</form>

    <form action="{{ route('delivery.valide', $order->id) }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-success">success</button>
    </form>


<div id="map" style="height: 500px; border-radius:8px; overflow:hidden;"></div>
<p id="infos" class="mt-3 text-primary fw-bold"></p>

{{-- Leaflet Core --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

{{-- Routing Machine --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.css" />
<script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>

{{-- Plugin pour mouvement fluide du marqueur --}}
<script src="https://unpkg.com/leaflet.marker.slideto/leaflet.marker.slideto.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const clientLat = {{ $order->deliveryAddress->latitude }};
    const clientLng = {{ $order->deliveryAddress->longitude }};
    const clientName = @json($order->user->name);

    // Fonction Haversine pour distance (km)
    function getDistance(lat1, lon1, lat2, lon2) {
        const R = 6371;
        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLon = (lon2 - lon1) * Math.PI / 180;
        const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                  Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                  Math.sin(dLon/2) * Math.sin(dLon/2);
        return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    }

    // Initialisation carte
    const map = L.map('map').setView([clientLat, clientLng], 14);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap'
    }).addTo(map);

    // Marqueur client
    L.marker([clientLat, clientLng])
        .addTo(map)
        .bindPopup(`<b>Client :</b> ${clientName}`)
        .openPopup();

    let livreurMarker = null;
    let routeDrawn = false;

    if (navigator.geolocation) {
        navigator.geolocation.watchPosition(pos => {
            const livreurLat = pos.coords.latitude;
            const livreurLng = pos.coords.longitude;

            // Ajout ou déplacement du marqueur
            if (!livreurMarker) {
                livreurMarker = L.marker([livreurLat, livreurLng], {
                    icon: L.icon({
                        iconUrl: 'https://cdn-icons-png.flaticon.com/512/854/854894.png',
                        iconSize: [40, 40]
                    })
                }).addTo(map).bindPopup("📍 Vous êtes ici");
            } else {
                // Déplacement fluide du marqueur
                livreurMarker.slideTo([livreurLat, livreurLng], {
                    duration: 1000, // 1 seconde pour glisser
                    keepAtCenter: false
                });
            }

            // Dessiner la route seulement une fois
            if (!routeDrawn) {
                L.Routing.control({
                    waypoints: [
                        L.latLng(livreurLat, livreurLng),
                        L.latLng(clientLat, clientLng)
                    ],
                    addWaypoints: false,
                    draggableWaypoints: false,
                    createMarker: () => null, // pas de marqueurs automatiques
                    language: 'fr'
                }).addTo(map);
                routeDrawn = true;
            }

            // Calculer distance & temps estimé (vitesse moyenne 30 km/h)
            const dist = getDistance(livreurLat, livreurLng, clientLat, clientLng);
            const temps = Math.round((dist / 30) * 60); // en minutes
            document.getElementById("infos").innerHTML =
                `🚗 Distance restante : <b>${dist.toFixed(2)} km</b> — ⏱ Temps estimé : <b>${temps} min</b>`;

        }, () => {
            alert("Impossible de récupérer votre position.");
        }, { enableHighAccuracy: true });
    } else {
        alert("La géolocalisation n'est pas supportée sur ce navigateur.");
    }
});
</script>
@endsection
