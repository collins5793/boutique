@extends('layouts.sales.sale')

@section('title', 'Dashboard')

@section('content')
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Vendeur</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #eb770a;
            --primary-light: #ec8a2f;
            --primary-dark: #be6109;
            --secondary: #007bff;
            --accent: #ff7b00;
            --dark: #1e293b;
            --dark-light: #334155;
            --light: #ffffff;
            --sidebar-width: 280px;
            --sidebar-collapsed: 90px;
            --header-height: 80px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --radius: 12px;
            --shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            --card-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

      
        .dashboard-header {
            margin-bottom: 30px;
            animation: fadeInDown 0.6s ease-out;
        }

        .dashboard-header h1 {
            font-size: 28px;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 10px;
        }

        .dashboard-header p {
            color: var(--dark-light);
            font-size: 16px;
        }

        /* Cartes de statistiques */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: var(--light);
            border-radius: var(--radius);
            padding: 24px;
            box-shadow: var(--card-shadow);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
            animation: fadeInUp 0.5s ease-out;
            animation-fill-mode: backwards;
        }

        .stat-card:nth-child(1) { animation-delay: 0.1s; }
        .stat-card:nth-child(2) { animation-delay: 0.2s; }
        .stat-card:nth-child(3) { animation-delay: 0.3s; }
        .stat-card:nth-child(4) { animation-delay: 0.4s; }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 5px;
            height: 100%;
            background: var(--primary);
        }

        .stat-card h3 {
            font-size: 16px;
            font-weight: 600;
            color: var(--dark-light);
            margin-bottom: 12px;
        }

        .stat-card .value {
            font-size: 28px;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 8px;
        }

        .stat-card .trend {
            display: flex;
            align-items: center;
            font-size: 14px;
            color: var(--dark-light);
        }

        .trend.up {
            color: #10b981;
        }

        .trend.down {
            color: #ef4444;
        }

        /* Graphiques */
        .charts-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(500px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .chart-card {
            background: var(--light);
            border-radius: var(--radius);
            padding: 24px;
            box-shadow: var(--card-shadow);
            animation: fadeIn 0.7s ease-out;
        }

        .chart-card h2 {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 20px;
            color: var(--dark);
        }

        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
        }

        /* Tables */
        .tables-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(600px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .table-card {
            background: var(--light);
            border-radius: var(--radius);
            padding: 24px;
            box-shadow: var(--card-shadow);
            animation: fadeIn 0.8s ease-out;
        }

        .table-card h2 {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 20px;
            color: var(--dark);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .view-all {
            font-size: 14px;
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
        }

        .view-all:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table th {
            text-align: left;
            padding: 12px 15px;
            font-weight: 600;
            color: var(--dark-light);
            border-bottom: 2px solid #e2e8f0;
        }

        .data-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #e2e8f0;
            transition: var(--transition);
        }

        .data-table tr {
            transition: var(--transition);
        }

        .data-table tr:not(:first-child):hover {
            background-color: #f8fafc;
        }

        .data-table tr:not(:first-child):hover td {
            transform: translateX(5px);
        }

        .status-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }

        .status-pending {
            background-color: #fffbeb;
            color: #f59e0b;
        }

        .status-completed {
            background-color: #ecfdf5;
            color: #10b981;
        }

        .status-cancelled {
            background-color: #fef2f2;
            color: #ef4444;
        }

        /* Liste des produits */
        .product-list {
            list-style: none;
        }

        .product-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #e2e8f0;
            transition: var(--transition);
        }

        .product-item:hover {
            background-color: #f8fafc;
            padding-left: 10px;
        }

        .product-item:last-child {
            border-bottom: none;
        }

        .product-name {
            font-weight: 500;
        }

        .product-stock {
            font-size: 14px;
            color: var(--dark-light);
        }

        .stock-warning {
            color: #ef4444;
            font-weight: 500;
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .charts-grid,
            .tables-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .dashboard-container {
                padding: 15px;
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 576px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .data-table {
                display: block;
                overflow-x: auto;
            }
        }
    </style>
</head>
<body>
        <div class="dashboard-header">
            <h1>Tableau de bord du vendeur</h1>
            <p>Bienvenue dans votre espace de gestion des ventes</p>
        </div>

        <!-- Statistiques rapides -->
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total Commandes</h3>
                <div class="value">{{ $totalOrders }}</div>
                <div class="trend up">+12% ce mois-ci</div>
            </div>
            <div class="stat-card">
                <h3>Commandes en attente</h3>
                <div class="value">{{ $pendingOrders }}</div>
                <div class="trend down">-5% depuis hier</div>
            </div>
            <div class="stat-card">
                <h3>Chiffre d'affaires</h3>
                <div class="value">{{ number_format($totalRevenue, 2) }} FCFA</div>
                <div class="trend up">+8.3% ce mois-ci</div>
            </div>
            <div class="stat-card">
                <h3>Produits faibles en stock</h3>
                <div class="value">{{ $lowStockProducts }}</div>
                <div class="trend">À réapprovisionner</div>
            </div>
        </div>

        <!-- Graphiques -->
        <div class="charts-grid">
            <div class="chart-card">
                <h2>Ventes des 7 derniers jours</h2>
                <div class="chart-container">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>
            <div class="chart-card">
                <h2>Commandes par statut</h2>
                <div class="chart-container">
                    <canvas id="ordersStatusChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Dernières commandes -->
        <div class="tables-grid">
            <div class="table-card">
                <h2>
                    Dernières commandes
                    <a href="#" class="view-all">Voir tout</a>
                </h2>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Numéro</th>
                            <th>Client</th>
                            <th>Montant</th>
                            <th>Statut</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentOrders as $order)
                            <tr>
                                <td>{{ $order->order_number }}</td>
                                <td>{{ $order->user->name ?? 'Utilisateur supprimé' }}</td>
                                <td>{{ number_format($order->total_amount, 2) }} FCFA</td>
                                <td>
                                    @php
                                        $statusClass = '';
                                        if ($order->order_status == 'pending') {
                                            $statusClass = 'status-pending';
                                        } elseif ($order->order_status == 'completed') {
                                            $statusClass = 'status-completed';
                                        } elseif ($order->order_status == 'cancelled') {
                                            $statusClass = 'status-cancelled';
                                        }
                                    @endphp
                                    <span class="status-badge {{ $statusClass }}">{{ ucfirst($order->order_status) }}</span>
                                </td>
                                <td>{{ $order->created_at->format('d/m/Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Produits faibles en stock -->
            <div class="table-card">
                <h2>
                    Produits faibles en stock
                    <a href="#" class="view-all">Gérer le stock</a>
                </h2>
                <ul class="product-list">
                    @foreach($lowStockItems as $product)
                        <li class="product-item">
                            <span class="product-name">{{ $product->name }}</span>
                            <span class="product-stock {{ $product->stock_quantity < 5 ? 'stock-warning' : '' }}">
                                Stock: {{ $product->stock_quantity }}
                            </span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Animation des cartes de statistiques au scroll
            const statCards = document.querySelectorAll('.stat-card');
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = 1;
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, { threshold: 0.1 });

            statCards.forEach(card => {
                card.style.opacity = 0;
                card.style.transform = 'translateY(20px)';
                card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                observer.observe(card);
            });

            // Ventes 7 derniers jours
            const salesData = {
                labels: @json($salesChart->pluck('date')),
                datasets: [{
                    label: 'Ventes (FCFA)',
                    data: @json($salesChart->pluck('total')),
                    backgroundColor: 'rgba(235, 119, 10, 0.2)',
                    borderColor: 'rgba(235, 119, 10, 1)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true
                }]
            };
            
            new Chart(document.getElementById('salesChart'), {
                type: 'line',
                data: salesData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': ' + context.parsed.y.toLocaleString() + ' FCFA';
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
                                callback: function(value) {
                                    return value.toLocaleString() + ' FCFA';
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });

            // Commandes par statut
            const ordersStatusData = {
                labels: @json($ordersStatusChart->pluck('order_status')),
                datasets: [{
                    data: @json($ordersStatusChart->pluck('count')),
                    backgroundColor: [
                        'rgba(235, 119, 10, 0.7)',
                        'rgba(16, 185, 129, 0.7)',
                        'rgba(239, 68, 68, 0.7)',
                        'rgba(139, 92, 246, 0.7)'
                    ],
                    borderColor: [
                        'rgba(235, 119, 10, 1)',
                        'rgba(16, 185, 129, 1)',
                        'rgba(239, 68, 68, 1)',
                        'rgba(139, 92, 246, 1)'
                    ],
                    borderWidth: 1,
                    hoverOffset: 15
                }]
            };
            
            new Chart(document.getElementById('ordersStatusChart'), {
                type: 'doughnut',
                data: ordersStatusData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right'
                        }
                    },
                    cutout: '70%',
                    animation: {
                        animateScale: true,
                        animateRotate: true
                    }
                }
            });

            // Animation des lignes du tableau
            const tableRows = document.querySelectorAll('.data-table tr:not(:first-child)');
            tableRows.forEach((row, index) => {
                row.style.opacity = 0;
                row.style.transform = 'translateX(-20px)';
                row.style.transition = `opacity 0.5s ease ${index * 0.1}s, transform 0.5s ease ${index * 0.1}s`;
                
                setTimeout(() => {
                    row.style.opacity = 1;
                    row.style.transform = 'translateX(0)';
                }, 500 + (index * 100));
            });
        });
    </script>
</body>
</html>

@endsection
