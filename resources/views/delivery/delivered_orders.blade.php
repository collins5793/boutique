@extends('layouts.livreurs.livreur')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
        
        .page-container {
            max-width: 1200px;
            margin: 0 auto;
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
        
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            border-radius: var(--radius);
            padding: 20px;
            box-shadow: var(--shadow);
            text-align: center;
        }
        
        .stat-icon {
            font-size: 2.5rem;
            color: var(--primary);
            margin-bottom: 10px;
        }
        
        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 5px;
        }
        
        .stat-label {
            color: #64748b;
            font-size: 0.9rem;
        }
        
        .delivery-card {
            background: white;
            border-radius: var(--radius);
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: var(--shadow);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .delivery-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }
        
        .delivery-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 15px;
            flex-wrap: wrap;
            gap: 10px;
        }
        
        .order-number {
            font-weight: 700;
            color: var(--primary);
            font-size: 1.2rem;
        }
        
        .delivery-date {
            background: var(--light-bg);
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            color: #64748b;
        }
        
        .delivery-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 15px;
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
            margin-bottom: 15px;
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
        
        .pagination-container {
            display: flex;
            justify-content: center;
            margin-top: 30px;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                text-align: center;
                gap: 10px;
            }
            
            .delivery-header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .delivery-info {
                grid-template-columns: 1fr;
            }
        }
        
        @media (max-width: 576px) {
            body {
                padding: 15px;
            }
            
            .stats-container {
                grid-template-columns: 1fr;
            }
            
            .delivery-card {
                padding: 15px;
            }
            
            .product-item {
                flex-direction: column;
                gap: 5px;
            }
        }
    </style>
</head>
<body>
    <div class="page-container">
        <div class="page-header">
            <div class="page-icon">
                <i class="fas fa-clipboard-check"></i>
            </div>
            <div>
                <h1 class="page-title">Mes Livraisons Effectuées</h1>
                <p class="text-muted">Historique de toutes vos livraisons terminées</p>
            </div>
        </div>

        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-truck"></i>
                </div>
                <div class="stat-value">{{ $deliveries->total() }}</div>
                <div class="stat-label">Total des livraisons</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="stat-value">{{ $deliveries->count() }}</div>
                <div class="stat-label">Livraisons sur cette page</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-value">
                    @if($deliveries->count() > 0)
                        {{ $deliveries->first()->delivered_at->format('d/m/Y') }}
                    @else
                        --
                    @endif
                </div>
                <div class="stat-label">Dernière livraison</div>
            </div>
        </div>

        @if($deliveries->isEmpty())
            <div class="empty-state">
                <i class="fas fa-clipboard-list"></i>
                <p>Vous n'avez effectué aucune livraison pour le moment.</p>
                <a href="{{ route('delivery.pending') }}" class="btn btn-primary">
                    <i class="fas fa-list"></i> Voir les commandes en attente
                </a>
            </div>
        @else
            @foreach($deliveries as $delivery)
                <div class="delivery-card">
                    <div class="delivery-header">
                        <div class="order-number">
                            Commande #{{ $delivery->order->order_number }}
                        </div>
                        <div class="delivery-date">
                            <i class="fas fa-calendar"></i> 
                            Livrée le {{ $delivery->delivered_at->format('d/m/Y à H:i') }}
                        </div>
                    </div>
                    
                    <div class="delivery-info">
                        <div class="info-item">
                            <span class="info-label">Client</span>
                            <span class="info-value">{{ $delivery->order->user->name }}</span>
                        </div>
                        
                        <div class="info-item">
                            <span class="info-label">Adresse de livraison</span>
                            <span class="info-value">{{ $delivery->order->deliveryAddress->full_address ?? 'Non spécifiée' }}</span>
                        </div>
                        
                        <div class="info-item">
                            <span class="info-label">Numéro de suivi</span>
                            <span class="info-value">{{ $delivery->tracking_number ?? 'N/A' }}</span>
                        </div>
                        
                        <div class="info-item">
                            <span class="info-label">Montant total</span>
                            <span class="info-value">{{ number_format($delivery->order->total_amount, 2, ',', ' ') }} FCFA</span>
                        </div>
                    </div>
                    
                    <div class="products-list">
                        <h6>Produits livrés :</h6>
                        @foreach($delivery->order->orderItems as $item)
                            <div class="product-item">
                                <span>{{ $item->product->name ?? 'Produit supprimé' }} × {{ $item->quantity }}</span>
                                <span class="fw-semibold">{{ number_format($item->total, 2, ',', ' ') }} FCFA</span>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="d-flex justify-content-end">
                        <span class="badge bg-success">
                            <i class="fas fa-check-circle"></i> Livraison terminée
                        </span>
                    </div>
                </div>
            @endforeach
            
            <div class="pagination-container">
                {{ $deliveries->links() }}
            </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection