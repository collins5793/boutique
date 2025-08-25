@extends('layouts.livreurs.livreur')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.css" />
<style>
        :root {
            --primary: #7c3aed;
            --primary-light: #8b5cf6;
            --secondary: #0ea5e9;
            --success: #10b981;
            --dark: #1e293b;
            --light-bg: #f8fafc;
            --radius: 12px;
            --shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        
        body {
            background-color: #f5f7fb;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #334155;
            padding: 20px;
        }
        
        .page-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 25px;
        }
        
        .page-icon {
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.8rem;
        }
        
        .page-title {
            color: var(--dark);
            font-weight: 700;
            margin: 0;
        }
        
        .info-card {
            background: white;
            border-radius: var(--radius);
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: var(--shadow);
        }
        
        .info-card h4 {
            color: var(--primary);
            margin-bottom: 20px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .info-item {
            display: flex;
            margin-bottom: 15px;
        }
        
        .info-icon {
            width: 40px;
            height: 40px;
            background: var(--light-bg);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            color: var(--primary);
            flex-shrink: 0;
        }
        
        .info-content {
            flex: 1;
        }
        
        .info-label {
            font-size: 0.85rem;
            color: #64748b;
            margin-bottom: 5px;
        }
        
        .info-value {
            font-weight: 500;
            font-size: 1.05rem;
        }
        
        .action-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 25px;
        }
        
        .btn-deliver {
            background: linear-gradient(135deg, var(--success), #34d399);
            color: white;
            border: none;
            border-radius: 10px;
            padding: 15px 25px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
            flex: 1;
            justify-content: center;
            transition: all 0.3s;
            box-shadow: 0 4px 6px rgba(16, 185, 129, 0.3);
        }
        
        .btn-deliver:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(16, 185, 129, 0.4);
        }
        
        .btn-success {
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            color: white;
            border: none;
            border-radius: 10px;
            padding: 15px 25px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
            flex: 1;
            justify-content: center;
            transition: all 0.3s;
            box-shadow: 0 4px 6px rgba(124, 58, 237, 0.3);
        }
        
        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(124, 58, 237, 0.4);
        }
        
        .map-container {
            background: white;
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: var(--shadow);
            margin-bottom: 25px;
        }
        
        #map {
            height: 450px;
            width: 100%;
        }
        
        .delivery-info {
            background: white;
            border-radius: var(--radius);
            padding: 20px;
            box-shadow: var(--shadow);
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }
        
        .distance-info, .time-info {
            text-align: center;
            flex: 1;
        }
        
        .distance-value, .time-value {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary);
            line-height: 1;
        }
        
        .distance-label, .time-label {
            font-size: 0.9rem;
            color: #64748b;
            margin-top: 5px;
        }
        
        .divider {
            width: 1px;
            height: 50px;
            background: #e2e8f0;
            margin: 0 20px;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                text-align: center;
                gap: 10px;
            }
            
            .action-buttons {
                flex-direction: column;
            }
            
            .delivery-info {
                flex-direction: column;
                gap: 20px;
            }
            
            .divider {
                width: 100%;
                height: 1px;
                margin: 10px 0;
            }
            
            #map {
                height: 350px;
            }
        }
        
        @media (max-width: 576px) {
            body {
                padding: 15px;
            }
            
            .info-card {
                padding: 20px;
            }
            
            .info-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
            
            .info-icon {
                margin-right: 0;
            }
            
            .distance-value, .time-value {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="page-header">
        <div class="page-icon">
            <i class="fas fa-truck"></i>
        </div>
        <div>
            <h1 class="page-title">Livraison en cours</h1>
        </div>
    </div>

    <div class="info-card">
        <h4><i class="fas fa-user-circle"></i> Informations client</h4>
        
        <div class="info-item">
            <div class="info-icon">
                <i class="fas fa-user"></i>
            </div>
            <div class="info-content">
                <div class="info-label">Nom</div>
                <div class="info-value">{{ $order->user->name }}</div>
            </div>
        </div>
        
        <div class="info-item">
            <div class="info-icon">
                <i class="fas fa-phone"></i>
            </div>
            <div class="info-content">
                <div class="info-label">Téléphone</div>
                <div class="info-value">{{ $order->user->phone ?? 'Non renseigné' }}</div>
            </div>
        </div>
        
        <div class="info-item">
            <div class="info-icon">
                <i class="fas fa-map-marker-alt"></i>
            </div>
            <div class="info-content">
                <div class="info-label">Adresse</div>
                <div class="info-value">{{ $order->deliveryAddress->full_address ?? 'Adresse non précisée' }}</div>
            </div>
        </div>
    </div>

    <div class="action-buttons">
        <form action="{{ route('delivery.fin', $order->id) }}" method="POST" class="d-flex flex-fill">
            @csrf
            <button type="submit" class="btn btn-deliver">
                <i class="fas fa-check-circle"></i> Livrer cette commande
            </button>
        </form>

        <form action="{{ route('delivery.valide', $order->id) }}" method="POST" class="d-flex flex-fill">
            @csrf
            <button type="submit" class="btn btn-success">
                <i class="fas fa-clipboard-check"></i> Marquer comme terminée
            </button>
        </form>
    </div>

    <div class="map-container">
        <div id="map"></div>
    </div>
    
    <div class="delivery-info">
        <div class="distance-info">
            <div id="distance-value" class="distance-value">--</div>
            <div class="distance-label">Distance restante</div>
        </div>
        
        <div class="divider"></div>
        
        <div class="time-info">
            <div id="time-value" class="time-value">--</div>
            <div class="time-label">Temps estimé</div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>
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
        L.marker([clientLat, clientLng], {
            icon: L.icon({
                iconUrl: 'https://cdn-icons-png.flaticon.com/512/684/684908.png',
                iconSize: [40, 40],
                iconAnchor: [20, 40],
                popupAnchor: [0, -40]
            })
        })
        .addTo(map)
        .bindPopup(`<b>Client :</b> ${clientName}`)
        .openPopup();

        let livreurMarker = null;
        let routeControl = null;

        if (navigator.geolocation) {
            navigator.geolocation.watchPosition(pos => {
                const livreurLat = pos.coords.latitude;
                const livreurLng = pos.coords.longitude;

                // Ajout ou déplacement du marqueur
                if (!livreurMarker) {
                    livreurMarker = L.marker([livreurLat, livreurLng], {
                        icon: L.icon({
                            iconUrl: 'https://cdn-icons-png.flaticon.com/512/854/854894.png',
                            iconSize: [40, 40],
                            iconAnchor: [20, 40],
                            popupAnchor: [0, -40]
                        })
                    }).addTo(map).bindPopup("📍 Votre position");
                } else {
                    // Déplacement fluide du marqueur
                    livreurMarker.slideTo([livreurLat, livreurLng], {
                        duration: 1000,
                        keepAtCenter: false
                    });
                }

                // Centrer la carte sur les deux points
                const group = new L.featureGroup([livreurMarker, L.marker([clientLat, clientLng])]);
                map.fitBounds(group.getBounds().pad(0.2));

                // Calculer distance & temps estimé (vitesse moyenne 30 km/h)
                const dist = getDistance(livreurLat, livreurLng, clientLat, clientLng);
                const temps = Math.round((dist / 30) * 60); // en minutes
                
                // Mettre à jour l'affichage
                document.getElementById("distance-value").textContent = `${dist.toFixed(1)} km`;
                document.getElementById("time-value").textContent = `${temps} min`;

                // Dessiner la route seulement une fois
                if (!routeControl) {
                    routeControl = L.Routing.control({
                        waypoints: [
                            L.latLng(livreurLat, livreurLng),
                            L.latLng(clientLat, clientLng)
                        ],
                        addWaypoints: false,
                        draggableWaypoints: false,
                        createMarker: () => null,
                        language: 'fr',
                        lineOptions: {
                            styles: [{color: '#7c3aed', opacity: 0.8, weight: 6}]
                        },
                        routeWhileDragging: false
                    }).addTo(map);
                }

            }, (error) => {
                console.error("Erreur de géolocalisation:", error);
                alert("Impossible de récupérer votre position. Vérifiez que la géolocalisation est activée.");
            }, { 
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 0
            });
        } else {
            alert("La géolocalisation n'est pas supportée sur ce navigateur.");
        }
    });
    </script>
@endsection
