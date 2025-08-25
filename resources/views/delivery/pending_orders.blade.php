@extends('layouts.livreurs.livreur')

@section('content')
<style>
        :root {
            --primary: #7c3aed;
            --primary-light: #8b5cf6;
            --secondary: #0ea5e9;
            --dark: #1e293b;
            --light-bg: #f8fafc;
            --radius: 12px;
            --shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        
        body {
            background-color: #f5f7fb;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #334155;
        }
        
        .page-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .page-header {
            background: white;
            border-radius: var(--radius);
            padding: 20px;
            margin-bottom: 25px;
            box-shadow: var(--shadow);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .page-title {
            color: var(--dark);
            font-weight: 700;
            margin: 0;
        }
        
        .order-card {
            background: white;
            border-radius: var(--radius);
            overflow: hidden;
            margin-bottom: 20px;
            box-shadow: var(--shadow);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .order-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }
        
        .order-header {
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            color: white;
            padding: 15px 20px;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .order-header h5 {
            margin: 0;
            font-weight: 600;
        }
        
        .order-date {
            font-size: 0.9rem;
            opacity: 0.9;
        }
        
        .order-content {
            padding: 20px;
        }
        
        .order-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .info-item {
            display: flex;
            flex-direction: column;
        }
        
        .info-label {
            font-size: 0.85rem;
            color: #64748b;
            margin-bottom: 5px;
        }
        
        .info-value {
            font-weight: 500;
        }
        
        .products-list {
            background: var(--light-bg);
            border-radius: var(--radius);
            padding: 15px;
            margin-bottom: 20px;
        }
        
        .product-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .product-item:last-child {
            border-bottom: none;
        }
        
        .action-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        
        .btn-deliver {
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            color: white;
            border: none;
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn-route {
            background: var(--secondary);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
        }
        
        .empty-state i {
            font-size: 4rem;
            color: #cbd5e1;
            margin-bottom: 20px;
        }
        
        .empty-state p {
            color: #64748b;
            font-size: 1.2rem;
            margin-bottom: 30px;
        }
        
        /* Popup Itinéraire */
        .route-popup {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.7);
            z-index: 9999;
            padding: 20px;
        }
        
        .route-container {
            position: relative;
            width: 100%;
            height: 100%;
            max-width: 1000px;
            max-height: 80vh;
            margin: 5% auto;
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 25px 50px rgba(0,0,0,0.3);
        }
        
        .route-header {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            padding: 15px 20px;
            background: rgba(255,255,255,0.95);
            z-index: 1001;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .route-title {
            font-weight: 600;
            color: var(--dark);
        }
        
        .close-route {
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }
        
        #routeMap {
            width: 100%;
            height: 100%;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
            
            .order-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
            
            .order-info {
                grid-template-columns: 1fr;
            }
            
            .action-buttons {
                flex-direction: column;
            }
            
            .btn-deliver, .btn-route {
                width: 100%;
                justify-content: center;
            }
            
            .route-container {
                margin: 0;
                max-height: 100%;
                border-radius: 0;
            }
        }
        
        @media (max-width: 576px) {
            .page-container {
                padding: 15px;
            }
            
            .order-content {
                padding: 15px;
            }
            
            .product-item {
                flex-direction: column;
                gap: 5px;
            }
        }
        
        /* Animation pour l'accordéon */
        .collapse:not(.show) {
            display: none;
        }
        
        .collapsing {
            height: 0;
            overflow: hidden;
            transition: height 0.3s ease;
        }
    </style>
    <div class="page-container">
        <div class="page-header">
            <h1 class="page-title">Commandes en attente</h1>
            <div class="badge bg-primary rounded-pill p-2">{{ $orders->count() }} commande(s)</div>
        </div>

        @if($orders->isEmpty())
            <div class="empty-state">
                <i class="fas fa-clipboard-list"></i>
                <p>Aucune commande en attente pour le moment.</p>
            </div>
        @else
            <div class="accordion" id="ordersAccordion">
                @foreach($orders as $order)
                    @php
                        $addr = $order->deliveryAddress;
                        $canRoute = $addr && in_array($addr->address_type ?? '', ['current','map']) && $addr->latitude && $addr->longitude;
                    @endphp

                    <div class="order-card">
                        <div class="order-header" data-bs-toggle="collapse" data-bs-target="#collapse{{ $order->id }}" aria-expanded="false" aria-controls="collapse{{ $order->id }}">
                            <h5>{{ $order->order_number }} - {{ number_format($order->total_amount, 2, ',', ' ') }} FCFA</h5>
                            <div class="order-date">{{ $order->created_at->format('d/m/Y H:i') }}</div>
                        </div>

                        <div id="collapse{{ $order->id }}" class="collapse" aria-labelledby="heading{{ $order->id }}" data-bs-parent="#ordersAccordion">
                            <div class="order-content">
                                <div class="order-info">
                                    <div class="info-item">
                                        <span class="info-label">Client</span>
                                        <span class="info-value">{{ $order->user->name ?? 'Inconnu' }}</span>
                                    </div>
                                    <div class="info-item">
                                        <span class="info-label">Adresse</span>
                                        <span class="info-value">{{ $addr->full_address ?? 'Non renseignée' }}</span>
                                    </div>
                                    <div class="info-item">
                                        <span class="info-label">Mode de paiement</span>
                                        <span class="info-value">{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</span>
                                    </div>
                                    @if($order->notes)
                                    <div class="info-item">
                                        <span class="info-label">Notes</span>
                                        <span class="info-value">{{ $order->notes }}</span>
                                    </div>
                                    @endif
                                </div>

                                <div class="products-list">
                                    <h6>Produits :</h6>
                                    @foreach($order->orderItems as $item)
                                        <div class="product-item">
                                            <span>{{ $item->product->name ?? 'Produit supprimé' }} × {{ $item->quantity }}</span>
                                            <span class="fw-semibold">{{ number_format($item->total, 2, ',', ' ') }} FCFA</span>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="action-buttons">
                                    

                                    @if($canRoute)
                                    <button class="btn btn-deliver start-delivery-btn" data-order-id="{{ $order->id }}">
                                        <i class="fas fa-truck"></i> Commencer la livraison
                                    </button>
                                        <button class="btn btn-route view-route-btn"
                                                data-lat="{{ $addr->latitude }}"
                                                data-lng="{{ $addr->longitude }}"
                                                data-order="{{ $order->order_number }}"
                                                data-address="{{ $addr->full_address }}">
                                            <i class="fas fa-route"></i> Voir l'itinéraire
                                        </button>
                                        @else
                                        <button class="btn btn-deliver start-delive-btn" data-order-id="{{ $order->id }}">
                                        <i class="fas fa-truck"></i> Commencer la livraison
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
    <div id="routePopup" class="route-popup">
        <div class="route-container">
            <div class="route-header">
                <div id="routeTitle" class="route-title"></div>
                <button id="closeRoutePopup" class="close-route">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="routeMap"></div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.min.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const csrfToken = '{{ csrf_token() }}';

        // Gestion des boutons de livraison
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
                .then(res => res.json())
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
                .then(res => res.json())
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

        // Popup Itinéraire
        const routePopup = document.getElementById('routePopup');
        const closeRoute = document.getElementById('closeRoutePopup');
        const routeMapId = 'routeMap';
        const routeTitleEl = document.getElementById('routeTitle');
        let routeMap = null;
        let routingCtrl = null;

        // Ouvrir le popup d'itinéraire
        function openRoutePopup() {
            routePopup.style.display = 'block';
            document.body.style.overflow = 'hidden';
        }
        
        // Fermer le popup d'itinéraire
        function closeRoutePopup() {
            routePopup.style.display = 'none';
            document.body.style.overflow = '';
            if (routingCtrl) { routingCtrl.remove(); routingCtrl = null; }
            if (routeMap) { routeMap.remove(); routeMap = null; }
        }

        closeRoute.addEventListener('click', closeRoutePopup);

        // Bouton "Voir l'itinéraire"
        document.querySelectorAll('.view-route-btn').forEach(btn => {
            btn.addEventListener('click', function() {
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

                // Redimensionner la carte après l'affichage
                setTimeout(() => routeMap.invalidateSize(), 100);

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
                closeRoute.addEventListener('click', function() {
                    if (watchId) navigator.geolocation.clearWatch(watchId);
                });
            });
        });

        // Fermer le popup en cliquant à l'extérieur
        routePopup.addEventListener('click', function(e) {
            if (e.target === routePopup) {
                closeRoutePopup();
            }
        });
    });
    </script>
@endsection
