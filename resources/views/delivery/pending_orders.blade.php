@extends('layouts.livreurs.livreur')

@section('content')
<div class="container mt-4">
    <h1>Commandes en attente</h1>

    @if($orders->isEmpty())
        <p>Aucune commande en attente pour le moment.</p>
    @else
        <div class="accordion" id="ordersAccordion">
            @foreach($orders as $order)
                @php
                    $addr = $order->deliveryAddress;
                    $canRoute = $addr && in_array($addr->address_type ?? '', ['current','map']) && $addr->latitude && $addr->longitude;
                @endphp

                <div class="card mb-2">
                    <div class="card-header" id="heading{{ $order->id }}">
                        <h5 class="mb-0 d-flex justify-content-between align-items-center">
                            <button class="btn btn-link" type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#collapse{{ $order->id }}"
                                    aria-expanded="false"
                                    aria-controls="collapse{{ $order->id }}">
                                {{ $order->order_number }} - {{ number_format($order->total_amount, 2, ',', ' ') }} FCFA
                            </button>
                            <span>{{ $order->created_at->format('d/m/Y H:i') }}</span>
                        </h5>
                    </div>

                    <div id="collapse{{ $order->id }}" class="collapse" aria-labelledby="heading{{ $order->id }}" data-bs-parent="#ordersAccordion">
                        <div class="card-body">
                            <p><strong>Client :</strong> {{ $order->user->name ?? 'Inconnu' }}</p>
                            <p><strong>Adresse :</strong> {{ $addr->full_address ?? 'Non renseignée' }}</p>
                            <p><strong>Mode de paiement :</strong> {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</p>
                            @if($order->notes)
                                <p><strong>Notes :</strong> {{ $order->notes }}</p>
                            @endif

                            <h6>Produits :</h6>
                            <ul>
                                @foreach($order->orderItems as $item)
                                    <li>
                                        {{ $item->product->name ?? 'Produit supprimé' }}
                                        × {{ $item->quantity }}
                                        → {{ number_format($item->total, 2, ',', ' ') }} FCFA
                                    </li>
                                @endforeach
                            </ul>

                            <div class="mt-2 d-flex gap-2">
                                <button class="btn btn-success start-delive-btn" data-order-id="{{ $order->id }}">
                                    Livrer
                                </button>

                                @if($canRoute)

                                <button class="btn btn-success start-delivery-btn" data-order-id="{{ $order->id }}">
                                    Livrer
                                </button>

                                    <button class="btn btn-primary view-route-btn"
                                            data-lat="{{ $addr->latitude }}"
                                            data-lng="{{ $addr->longitude }}"
                                            data-order="{{ $order->order_number }}"
                                            data-address="{{ $addr->full_address }}">
                                        Voir l'itinéraire
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

{{-- Popup itinéraire --}}
<div id="routePopup" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:9999;">
    <div style="position:relative; width:92%; height:84%; margin:3% auto; background:#fff; border-radius:12px; overflow:hidden; box-shadow:0 10px 30px rgba(0,0,0,.25);">
        <div style="position:absolute; top:10px; left:16px; right:16px; z-index:1001; display:flex; justify-content:space-between; align-items:center;">
            <div id="routeTitle" class="fw-semibold"></div>
            <button id="closeRoutePopup" class="btn btn-sm btn-secondary">Fermer</button>
        </div>
        <div id="routeMap" style="width:100%; height:100%;"></div>
    </div>
</div>

{{-- Leaflet & Routing --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css"/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const csrfToken = '{{ csrf_token() }}';

    document.querySelectorAll('.start-delivery-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const orderId = this.dataset.orderId;

            if(!confirm("Commencer la livraison de cette commande ?")) return;

            fetch(`/delivery/start/${orderId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(res => res.json()) // ici ça sera bien du JSON
            .then(data => {
                if (data.success) {
                    window.location.href = data.redirect_url;
                } else {
                    alert("Une erreur est survenue");
                }
            })
            .catch(err => console.error(err));

        });
    });

    document.querySelectorAll('.start-delive-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const orderId = this.dataset.orderId;

            if(!confirm("Commencer la livraison de cette commande ?")) return;

            fetch(`/delive/start/${orderId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(res => res.json()) // ici ça sera bien du JSON
            .then(data => {
                if (data.success) {
                    window.location.href = data.redirect_url;
                } else {
                    alert("Une erreur est survenue");
                }
            })
            .catch(err => console.error(err));

        });
    });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const csrfToken = '{{ csrf_token() }}';

    // --- Marquer livré ---
    
    // --- Popup Itinéraire ---
    const routePopup   = document.getElementById('routePopup');
    const closeRoute   = document.getElementById('closeRoutePopup');
    const routeMapId   = 'routeMap';
    const routeTitleEl = document.getElementById('routeTitle');
    let routeMap = null;
    let routingCtrl = null;

    // Helpers
    function openRoutePopup() {
        routePopup.style.display = 'block';
        document.body.style.overflow = 'hidden';
    }
    function closeRoutePopup() {
        routePopup.style.display = 'none';
        document.body.style.overflow = '';
        if (routingCtrl) { routingCtrl.remove(); routingCtrl = null; }
        if (routeMap)    { routeMap.remove(); routeMap = null; }
    }

    closeRoute.addEventListener('click', closeRoutePopup);

    // Bouton "Voir l'itinéraire"
    document.querySelectorAll('.view-route-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const destLat = parseFloat(this.dataset.lat);
            const destLng = parseFloat(this.dataset.lng);
            const orderNo = this.dataset.order || '';
            const destTxt = this.dataset.address || (destLat + ',' + destLng);

            if (!navigator.geolocation) {
                alert("La géolocalisation n'est pas supportée par votre navigateur.");
                window.open(`https://www.google.com/maps/dir/?api=1&destination=${destLat},${destLng}`, '_blank');
                return;
            }

            // Ouvrir le popup
            openRoutePopup();
            routeTitleEl.textContent = `Itinéraire — ${orderNo}`;

            // Initialiser la carte
            routeMap = L.map(routeMapId).setView([destLat, destLng], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(routeMap);

            setTimeout(() => routeMap.invalidateSize(), 0);

            // Marqueur destination
            const destMarker = L.marker([destLat, destLng]).addTo(routeMap).bindPopup(destTxt);

            let startMarker = null;
            let watchId = null;

            // Fonction pour mettre à jour la position et recalculer la route
            function updateRoute(lat, lng) {
                if (startMarker) startMarker.setLatLng([lat, lng]);
                else startMarker = L.marker([lat, lng]).addTo(routeMap).bindPopup('Votre position');

                if (routingCtrl) routingCtrl.remove();

                routingCtrl = L.Routing.control({
                    waypoints: [
                        L.latLng(lat, lng),
                        L.latLng(destLat, destLng)
                    ],
                    router: L.Routing.osrmv1({
                        serviceUrl: 'https://router.project-osrm.org/route/v1'
                    }),
                    lineOptions: { styles: [{ color: '#3388ff', opacity: 0.8, weight: 6 }] },
                    addWaypoints: false,
                    draggableWaypoints: false,
                    fitSelectedRoutes: true,
                    show: false
                }).addTo(routeMap);
            }

            // Suivi en temps réel
            watchId = navigator.geolocation.watchPosition(pos => {
                updateRoute(pos.coords.latitude, pos.coords.longitude);
            }, err => {
                alert("Erreur géolocalisation : " + err.message);
            }, {
                enableHighAccuracy: true,
                maximumAge: 1000,
                timeout: 5000
            });

            // Arrêt du suivi quand on ferme le popup
            closeRoute.addEventListener('click', function () {
                if (watchId) navigator.geolocation.clearWatch(watchId);
            });
        });
    });

});
</script>
@endsection
