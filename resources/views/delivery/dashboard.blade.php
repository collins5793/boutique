@extends('layouts.livreurs.livreur')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --primary: #7c3aed;
            --primary-light: #8b5cf6;
            --secondary: #0ea5e9;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
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
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .welcome-banner {
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            color: white;
            border-radius: var(--radius);
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: var(--shadow);
        }
        
        .welcome-text h1 {
            font-weight: 700;
            margin-bottom: 5px;
        }
        
        .welcome-text p {
            opacity: 0.9;
            margin-bottom: 0;
        }
        
        .stats-grid {
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
            display: flex;
            align-items: center;
            gap: 15px;
            transition: transform 0.2s;
        }
        
        .stat-card:hover {
            transform: translateY(-3px);
        }
        
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }
        
        .icon-primary { background: rgba(124, 58, 237, 0.1); color: var(--primary); }
        .icon-success { background: rgba(16, 185, 129, 0.1); color: var(--success); }
        .icon-warning { background: rgba(245, 158, 11, 0.1); color: var(--warning); }
        .icon-info { background: rgba(14, 165, 233, 0.1); color: var(--secondary); }
        
        .stat-content h3 {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 5px;
            color: var(--dark);
        }
        
        .stat-content p {
            color: #64748b;
            margin-bottom: 0;
            font-size: 0.9rem;
        }
        
        .dashboard-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
            margin-bottom: 30px;
        }
        
        @media (max-width: 1200px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
            }
        }
        
        .card {
            background: white;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            margin-bottom: 0;
            border: none;
        }
        
        .card-header {
            background: transparent;
            border-bottom: 1px solid #e2e8f0;
            padding: 20px;
            font-weight: 600;
            color: var(--dark);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .card-header .badge {
            font-size: 0.75rem;
        }
        
        .card-body {
            padding: 20px;
        }
        
        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
        }
        
        .order-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #f1f5f9;
        }
        
        .order-item:last-child {
            border-bottom: none;
        }
        
        .order-info h6 {
            font-weight: 600;
            margin-bottom: 5px;
            color: var(--dark);
        }
        
        .order-info p {
            color: #64748b;
            font-size: 0.85rem;
            margin-bottom: 0;
        }
        
        .order-status {
            font-size: 0.8rem;
            padding: 4px 10px;
            border-radius: 20px;
            font-weight: 600;
        }
        
        .status-pending { background: #fef3c7; color: #d97706; }
        .status-progress { background: #dbeafe; color: #1d4ed8; }
        .status-delivered { background: #dcfce7; color: #15803d; }
        
        .performance-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .performance-item {
            text-align: center;
            padding: 15px;
            background: var(--light-bg);
            border-radius: var(--radius);
        }
        
        .performance-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 5px;
        }
        
        .performance-label {
            color: #64748b;
            font-size: 0.85rem;
        }
        
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }
        
        .action-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background: white;
            border: 2px solid #f1f5f9;
            border-radius: var(--radius);
            text-align: center;
            transition: all 0.3s;
            text-decoration: none;
            color: var(--dark);
        }
        
        .action-btn:hover {
            border-color: var(--primary);
            transform: translateY(-2px);
            text-decoration: none;
            color: var(--dark);
        }
        
        .action-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--light-bg);
            color: var(--primary);
            font-size: 1.2rem;
            margin-bottom: 10px;
        }
        
        .action-text {
            font-weight: 600;
            font-size: 0.9rem;
        }
        
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #64748b;
        }
        
        .empty-state i {
            font-size: 3rem;
            color: #cbd5e1;
            margin-bottom: 15px;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .quick-actions {
                grid-template-columns: 1fr;
            }
            
            .welcome-banner {
                text-align: center;
            }
            
            .dashboard-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="page-container">
        <!-- Bannière de bienvenue -->
        <div class="welcome-banner">
            <div class="welcome-text">
                <h1>Bonjour, {{ Auth::user()->name }} !</h1>
                <p>Voici votre activité aujourd'hui</p>
            </div>
        </div>
        
        <!-- Statistiques -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon icon-primary">
                    <i class="fas fa-truck"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $stats['today_deliveries'] }}</h3>
                    <p>Livraisons aujourd'hui</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon icon-success">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $stats['weekly_deliveries'] }}</h3>
                    <p>Cette semaine</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon icon-warning">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $stats['pending_deliveries'] }}</h3>
                    <p>En cours</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon icon-info">
                    <i class="fas fa-clipboard-check"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $stats['completed_deliveries'] }}</h3>
                    <p>Terminées</p>
                </div>
            </div>
        </div>
        
        <!-- Grid principal -->
        <div class="dashboard-grid">
            <!-- Colonne gauche - Graphique et Commandes -->
            <div>
                <!-- Graphique d'évolution -->
                <div class="card mb-4">
                    <div class="card-header">
                        <span>Évolution des livraisons (7 derniers jours)</span>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="deliveryChart"></canvas>
                        </div>
                    </div>
                </div>
                
                <!-- Commandes du jour -->
                <div class="card mb-4">
                    <div class="card-header">
                        <span>Livraisons du jour</span>
                        <span class="badge bg-primary">{{ $today_orders->count() }}</span>
                    </div>
                    <div class="card-body">
                        @if($today_orders->count() > 0)
                            @foreach($today_orders as $delivery)
                                <div class="order-item">
                                    <div class="order-info">
                                        <h6>Commande #{{ $delivery->order->order_number }}</h6>
                                        <p>{{ $delivery->order->deliveryAddress->full_address ?? 'Adresse non spécifiée' }}</p>
                                        <small class="text-muted">
                                            {{ $delivery->created_at->format('H:i') }} - {{ $delivery->order->user->name }}
                                        </small>
                                    </div>
                                    <div>
                                        <span class="order-status status-delivered">Livrée</span>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="empty-state">
                                <i class="fas fa-clipboard-list"></i>
                                <p>Aucune livraison aujourd'hui</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Colonne droite - Performances et Actions -->
            <div>
                <!-- Performances -->
                <div class="card mb-4">
                    <div class="card-header">
                        <span>Ma performance</span>
                    </div>
                    <div class="card-body">
                        <div class="performance-grid">
                            <div class="performance-item">
                                <div class="performance-value">{{ $performance['success_rate'] }}%</div>
                                <div class="performance-label">Taux de réussite</div>
                            </div>
                        </div>
                        
                        <div class="progress mb-3" style="height: 10px;">
                            <div class="progress-bar bg-success" role="progressbar" 
                                 style="width: {{ $performance['success_rate'] }}%"
                                 aria-valuenow="{{ $performance['success_rate'] }}" 
                                 aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <small class="text-muted">Votre taux de livraison réussie</small>
                    </div>
                </div>
                
                <!-- Prochaines livraisons -->
                <div class="card mb-4">
                    <div class="card-header">
                        <span>Prochaines livraisons</span>
                        <span class="badge bg-warning">{{ $upcoming_deliveries->count() }}</span>
                    </div>
                    <div class="card-body">
                        @if($upcoming_deliveries->count() > 0)
                            @foreach($upcoming_deliveries as $order)
                                <div class="order-item">
                                    <div class="order-info">
                                        <h6>Commande #{{ $order->order_number }}</h6>
                                        <p>{{ $order->deliveryAddress->full_address ?? 'Adresse non spécifiée' }}</p>
                                        <small class="text-muted">
                                            {{ $order->created_at->format('d/m H:i') }} - {{ $order->user->name }}
                                        </small>
                                    </div>
                                    <div>
                                        <span class="order-status status-pending">En attente</span>
                                    </div>
                                </div>
                            @endforeach
                            
                            <div class="text-center mt-3">
                                <a href="{{ route('delivery.pending') }}" class="btn btn-primary btn-sm">
                                    Voir toutes les commandes
                                </a>
                            </div>
                        @else
                            <div class="empty-state">
                                <i class="fas fa-check-circle"></i>
                                <p>Toutes les commandes sont attribuées</p>
                            </div>
                        @endif
                    </div>
                </div>
                
                <!-- Actions rapides -->
                <div class="card">
                    <div class="card-header">
                        <span>Actions rapides</span>
                    </div>
                    <div class="card-body">
                        <div class="quick-actions">
                            <a href="{{ route('delivery.pending') }}" class="action-btn">
                                <div class="action-icon">
                                    <i class="fas fa-list"></i>
                                </div>
                                <div class="action-text">Commandes en attente</div>
                            </a>
                            
                            <a href="{{ route('delivery.delivered-orders') }}" class="action-btn">
                                <div class="action-icon">
                                    <i class="fas fa-clipboard-check"></i>
                                </div>
                                <div class="action-text">Mes livraisons</div>
                            </a>
                            
                            <a href="#" class="action-btn">
                                <div class="action-icon">
                                    <i class="fas fa-map-marked-alt"></i>
                                </div>
                                <div class="action-text">Itinéraires</div>
                            </a>
                            
                            <a href="#" class="action-btn">
                                <div class="action-icon">
                                    <i class="fas fa-cog"></i>
                                </div>
                                <div class="action-text">Paramètres</div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Données du graphique depuis le contrôleur
        const deliveryData = {
            labels: {!! json_encode($chartData['labels']) !!},
            datasets: {!! json_encode($chartData['datasets']) !!}
        };

        // Configuration du graphique
        const config = {
            type: 'line',
            data: deliveryData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + context.parsed.y + ' livraison(s)';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            drawBorder: false
                        },
                        ticks: {
                            stepSize: 1
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        };

        // Création du graphique
        const ctx = document.getElementById('deliveryChart').getContext('2d');
        new Chart(ctx, config);
    });
</script>
@endsection