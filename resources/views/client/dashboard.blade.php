@extends('layouts.clients.client')

@section('title', 'Dashboard')

@section('content')
<div class="dashboard-content">
    <div class="dashboard-wrapper">

        {{-- 🖼 En-tête / Bienvenue + raccourcis --}}
        <div class="welcome-section">
            <div class="welcome-content">
                <h2 class="welcome-title">Bienvenue, {{ $user->name }} 👋</h2>
                <p class="welcome-date">{{ now()->translatedFormat('l d F Y') }}</p>
            </div>
            <div class="welcome-actions">
                <a href="" class="action-btn primary">
                    <i class="fas fa-shopping-cart"></i>
                    {{-- Passer une commande --}}
                </a>
                <a href="" class="action-btn secondary">
                    <i class="fas fa-list"></i>
                    {{-- Voir mes commandes --}}
                </a>
                <a href="" class="action-btn tertiary">
                    <i class="fas fa-headset"></i>
                    {{-- Support --}}
                </a>
            </div>
        </div>

        {{-- ⚡ Widgets rapides --}}
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-shopping-bag"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $totalOrders }}</div>
                    <div class="stat-label">Commandes totales</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon in-progress">
                    <i class="fas fa-truck"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $ordersInProgress }}</div>
                    <div class="stat-label">En cours</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon revenue">
                    <i class="fas fa-credit-card"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ number_format($totalSpent, 0, ',', ' ') }} <span class="currency">FCFA</span></div>
                    <div class="stat-label">Total dépensé</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon loyalty">
                    <i class="fas fa-gift"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $loyaltyPoints }}</div>
                    <div class="stat-label">Points fidélité</div>
                </div>
            </div>
        </div>

        {{-- 📈 Graphiques principaux --}}
        <div class="charts-grid">
            <div class="chart-container">
                <div class="chart-header">
                    <h3>Statut des commandes</h3>
                </div>
                <div class="chart-wrapper">
                    <canvas id="ordersByStatusChart"></canvas>
                </div>
            </div>
            
            <div class="chart-container">
                <div class="chart-header">
                    <h3>Historique des commandes & dépenses</h3>
                </div>
                <div class="chart-wrapper">
                    <canvas id="monthlyStatsChart"></canvas>
                </div>
            </div>
            
            <div class="chart-container">
                <div class="chart-header">
                    <h3>Produits les plus commandés</h3>
                </div>
                <div class="chart-wrapper">
                    <canvas id="topProductsChart"></canvas>
                </div>
            </div>
        </div>

        {{-- 💰 Dépenses annuelles --}}
        <div class="revenue-section">
            <div class="revenue-card">
                <div class="revenue-icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div class="revenue-content">
                    <div class="revenue-label">Dépenses {{ now()->year }}</div>
                    <div class="revenue-value">{{ number_format($spentThisYear, 0, ',', ' ') }} <span class="currency">FCFA</span></div>
                </div>
            </div>
            
            <div class="revenue-card">
                <div class="revenue-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="revenue-content">
                    <div class="revenue-label">Moyenne mensuelle</div>
                    <div class="revenue-value">{{ number_format($avgPerMonth, 0, ',', ' ') }} <span class="currency">FCFA</span></div>
                </div>
            </div>
        </div>

        {{-- 📑 Commandes récentes et Top produits --}}
        <div class="data-grid">
            <div class="data-section">
                <div class="section-header">
                    <h3><i class="fas fa-history"></i> Commandes récentes</h3>
                    <a href="#" class="view-all">Voir tout <i class="fas fa-arrow-right"></i></a>
                </div>
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>N° Commande</th>
                                <th>Date</th>
                                <th>Montant</th>
                                <th>Statut</th>
                                <th>Paiement</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders as $o)
                                <tr>
                                    <td class="order-number">#{{ $o->order_number }}</td>
                                    <td class="order-date">{{ \Carbon\Carbon::parse($o->created_at)->format('d M Y') }}</td>
                                    <td class="order-amount">{{ number_format($o->total_amount, 0, ',', ' ') }} FCFA</td>
                                    <td class="order-status">
                                        <span class="status-badge status-{{ $o->order_status }}">{{ ucfirst($o->order_status) }}</span>
                                    </td>
                                    <td class="order-payment">{{ str_replace('_',' ', $o->payment_method) }}</td>
                                    <td class="order-actions">
                                        <button class="action-icon" title="Voir détails">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="no-data">
                                        <i class="fas fa-inbox"></i>
                                        Aucune commande récente
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="data-section">
                <div class="section-header">
                    <h3><i class="fas fa-star"></i> Produits populaires</h3>
                </div>
                <div class="products-list">
                    @if($topProducts->count())
                        @foreach($topProducts as $p)
                            <div class="product-item">
                                <div class="product-image">
                                    @if(!empty($p->image))
                                        <img src="{{ asset($p->image) }}" alt="{{ $p->name }}">
                                    @else
                                        <div class="product-placeholder">
                                            <i class="fas fa-box"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="product-info">
                                    <h4 class="product-name">{{ $p->name }}</h4>
                                    <div class="product-stats">
                                        <span class="product-quantity">
                                            <i class="fas fa-shopping-basket"></i>
                                            {{ (int)$p->qty }} acheté(s)
                                        </span>
                                        <span class="product-spent">
                                            <i class="fas fa-money-bill-wave"></i>
                                            {{ number_format($p->spent, 0, ',', ' ') }} FCFA
                                        </span>
                                    </div>
                                </div>
                                <button class="product-action">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        @endforeach
                    @else
                        <div class="no-products">
                            <i class="fas fa-box-open"></i>
                            <p>Pas encore assez d'achats pour établir un classement</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>

<style>
    /* Styles pour le dashboard avec vos couleurs */
    .dashboard-wrapper {
        padding: 20px;
        max-width: 1400px;
        margin: 0 auto;
    }

    /* Section de bienvenue */
    .welcome-section {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        flex-wrap: wrap;
        gap: 20px;
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        padding: 25px;
        border-radius: var(--radius);
        color: white;
        box-shadow: var(--shadow);
    }

    .welcome-title {
        font-size: 28px;
        font-weight: 700;
        margin: 0 0 5px 0;
    }

    .welcome-date {
        font-size: 16px;
        opacity: 0.9;
        margin: 0;
    }

    .welcome-actions {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
    }

    .action-btn {
        padding: 12px 20px;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: var(--transition);
    }

    .action-btn.primary {
        background: white;
        color: var(--primary);
        box-shadow: 0 4px 15px rgba(255, 255, 255, 0.3);
    }

    .action-btn.primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(255, 255, 255, 0.4);
    }

    .action-btn.secondary {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .action-btn.secondary:hover {
        background: rgba(255, 255, 255, 0.3);
    }

    .action-btn.tertiary {
        color: white;
        background: transparent;
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .action-btn.tertiary:hover {
        background: rgba(255, 255, 255, 0.1);
    }

    /* Grille de statistiques */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        border-radius: var(--radius);
        padding: 24px;
        display: flex;
        align-items: center;
        gap: 20px;
        box-shadow: var(--shadow);
        transition: var(--transition);
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: var(--radius);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: white;
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
    }

    .stat-icon.in-progress {
        background: linear-gradient(135deg, var(--accent), #ff5722);
    }

    .stat-icon.revenue {
        background: linear-gradient(135deg, #4CAF50, #8BC34A);
    }

    .stat-icon.loyalty {
        background: linear-gradient(135deg, var(--secondary), #5B86E5);
    }

    .stat-value {
        font-size: 28px;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 5px;
    }

    .stat-label {
        font-size: 14px;
        color: var(--dark-light);
        font-weight: 500;
    }

    .currency {
        font-size: 16px;
        font-weight: 600;
    }

    /* Grille de graphiques */
    .charts-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
        gap: 25px;
        margin-bottom: 30px;
    }

    .chart-container {
        background: white;
        border-radius: var(--radius);
        padding: 24px;
        box-shadow: var(--shadow);
    }

    .chart-header {
        margin-bottom: 20px;
    }

    .chart-header h3 {
        font-size: 18px;
        font-weight: 600;
        color: var(--dark);
        margin: 0;
    }

    .chart-wrapper {
        position: relative;
        height: 300px;
    }

    /* Section revenu */
    .revenue-section {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .revenue-card {
        background: white;
        border-radius: var(--radius);
        padding: 24px;
        display: flex;
        align-items: center;
        gap: 20px;
        box-shadow: var(--shadow);
    }

    .revenue-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        color: white;
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
    }

    .revenue-label {
        font-size: 14px;
        color: var(--dark-light);
        margin-bottom: 5px;
    }

    .revenue-value {
        font-size: 24px;
        font-weight: 700;
        color: var(--dark);
    }

    /* Grille de données */
    .data-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(500px, 1fr));
        gap: 25px;
    }

    .data-section {
        background: white;
        border-radius: var(--radius);
        overflow: hidden;
        box-shadow: var(--shadow);
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px 24px;
        border-bottom: 1px solid #e2e8f0;
    }

    .section-header h3 {
        font-size: 18px;
        font-weight: 600;
        color: var(--dark);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .view-all {
        color: var(--primary);
        text-decoration: none;
        font-weight: 500;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .view-all:hover {
        text-decoration: underline;
    }

    /* Table */
    .table-container {
        overflow-x: auto;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
    }

    .data-table th {
        background: #f7fafc;
        padding: 12px 16px;
        text-align: left;
        font-weight: 600;
        color: var(--dark-light);
        font-size: 14px;
    }

    .data-table td {
        padding: 16px;
        border-bottom: 1px solid #e2e8f0;
    }

    .order-number {
        font-weight: 600;
        color: var(--primary);
    }

    .order-date {
        color: var(--dark-light);
    }

    .order-amount {
        font-weight: 600;
        color: var(--dark);
    }

    .status-badge {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
    }

    .status-delivered {
        background: #c6f6d5;
        color: #22543d;
    }

    .status-processing {
        background: #feebc8;
        color: #744210;
    }

    .status-pending {
        background: #e9d8fd;
        color: #44337a;
    }

    .status-cancelled {
        background: #fed7d7;
        color: #822727;
    }

    .order-actions {
        text-align: center;
    }

    .action-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        border: none;
        background: #f7fafc;
        color: var(--dark-light);
        cursor: pointer;
        transition: var(--transition);
    }

    .action-icon:hover {
        background: var(--primary);
        color: white;
    }

    .no-data, .no-products {
        text-align: center;
        padding: 40px 20px;
        color: #a0aec0;
    }

    .no-data i, .no-products i {
        font-size: 40px;
        margin-bottom: 15px;
        display: block;
    }

    /* Liste de produits */
    .products-list {
        padding: 0;
    }

    .product-item {
        display: flex;
        align-items: center;
        padding: 16px 24px;
        border-bottom: 1px solid #e2e8f0;
        transition: background 0.2s ease;
    }

    .product-item:last-child {
        border-bottom: none;
    }

    .product-item:hover {
        background: #f7fafc;
    }

    .product-image {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        overflow: hidden;
        margin-right: 16px;
        flex-shrink: 0;
        background: #f7fafc;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .product-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .product-placeholder {
        color: #a0aec0;
        font-size: 20px;
    }

    .product-info {
        flex: 1;
    }

    .product-name {
        font-weight: 600;
        margin: 0 0 8px 0;
        color: var(--dark);
        font-size: 15px;
    }

    .product-stats {
        display: flex;
        gap: 15px;
    }

    .product-quantity, .product-spent {
        font-size: 13px;
        color: var(--dark-light);
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .product-action {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        background: white;
        color: var(--primary);
        cursor: pointer;
        transition: var(--transition);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .product-action:hover {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .charts-grid,
        .data-grid {
            grid-template-columns: 1fr;
        }
        
        .welcome-section {
            flex-direction: column;
            text-align: center;
        }
        
        .welcome-actions {
            justify-content: center;
        }
    }

    @media (max-width: 768px) {
        .dashboard-wrapper {
            padding: 15px;
        }
        
        .stats-grid {
            grid-template-columns: 1fr 1fr;
        }
        
        .revenue-section {
            grid-template-columns: 1fr;
        }
        
        .stat-card,
        .chart-container,
        .revenue-card,
        .data-section {
            padding: 20px;
        }
    }

    @media (max-width: 576px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
        
        .welcome-actions {
            flex-direction: column;
        }
        
        .action-btn {
            justify-content: center;
        }
        
        .product-stats {
            flex-direction: column;
            gap: 5px;
        }
    }
</style>

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Attendre que le DOM soit chargé
    document.addEventListener('DOMContentLoaded', function() {
        // Données PHP -> JS
        const donutLabels = @json($ordersByStatus['labels']);
        const donutValues = @json($ordersByStatus['values']);

        const monthlyStatsData = @json($monthlyStats);
        const months = monthlyStatsData.map(item => item.ym);
        const monthLabels = months.map(m => {
            const [y,mo] = m.split('-'); 
            const d = new Date(parseInt(y), parseInt(mo)-1, 1);
            return d.toLocaleDateString('fr-FR', { month:'short', year:'numeric' });
        });
        const monthOrders = monthlyStatsData.map(item => parseInt(item.total_orders));
        const monthSpent  = monthlyStatsData.map(item => parseFloat(item.total_spent));

        // Données pour les produits les plus commandés
        const topProducts = @json($topProducts);
        const productNames = topProducts.map(p => p.name.length > 20 ? p.name.substring(0, 20) + '...' : p.name);
        const productQuantities = topProducts.map(p => parseInt(p.qty));
        const productSpent = topProducts.map(p => parseFloat(p.spent));

        // Couleurs pour les graphiques utilisant votre palette
        const chartColors = {
            primary: ['#f506c4', '#ff33d1', '#c0049b', '#ff7b00', '#007bff'],
            secondary: ['#6a11cb', '#2575fc', '#4A00E0', '#8E2DE2', '#00B4DB'],
            status: ['#10B981', '#FBBF24', '#3B82F6', '#EF4444', '#A78BFA', '#F472B6', '#60A5FA']
        };

        // 📦 Donut "Commandes par statut"
        const statusCtx = document.getElementById('ordersByStatusChart');
        if (statusCtx) {
            const statusChart = new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: donutLabels,
                    datasets: [{
                        data: donutValues,
                        backgroundColor: chartColors.primary,
                        borderWidth: 0,
                        hoverOffset: 12
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true,
                                pointStyle: 'circle',
                                color: '#1e293b'
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.raw || 0;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = Math.round((value / total) * 100);
                                    return label + ': ' + value + ' (' + percentage + '%)';
                                }
                            }
                        }
                    },
                    cutout: '70%',
                    animation: {
                        animateScale: true,
                        animateRotate: true
                    }
                }
            });
        }

        // ⏱ Historique (barres + ligne)
        const monthlyCtx = document.getElementById('monthlyStatsChart');
        if (monthlyCtx) {
            const monthlyChart = new Chart(monthlyCtx, {
                type: 'bar',
                data: {
                    labels: monthLabels,
                    datasets: [
                        {
                            type: 'bar',
                            label: 'Commandes',
                            data: monthOrders,
                            backgroundColor: chartColors.primary[0],
                            borderColor: chartColors.primary[0],
                            borderWidth: 0,
                            borderRadius: 6,
                            barPercentage: 0.6,
                            categoryPercentage: 0.7
                        },
                        {
                            type: 'line',
                            label: 'Dépenses (FCFA)',
                            data: monthSpent,
                            borderColor: chartColors.primary[4],
                            backgroundColor: 'rgba(0, 123, 255, 0.1)',
                            tension: 0.4,
                            fill: true,
                            pointBackgroundColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            yAxisID: 'y1'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                drawBorder: false
                            },
                            title: {
                                display: true,
                                text: 'Nombre de commandes',
                                color: '#334155'
                            },
                            ticks: {
                                color: '#334155'
                            }
                        },
                        y1: {
                            beginAtZero: true,
                            position: 'right',
                            grid: {
                                drawOnChartArea: false
                            },
                            title: {
                                display: true,
                                text: 'Dépenses (FCFA)',
                                color: '#334155'
                            },
                            ticks: {
                                color: '#334155',
                                callback: function(value) {
                                    return new Intl.NumberFormat('fr-FR').format(value);
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: '#334155'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                color: '#1e293b',
                                usePointStyle: true,
                                pointStyle: 'circle'
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(255, 255, 255, 0.95)',
                            titleColor: '#1e293b',
                            bodyColor: '#334155',
                            borderColor: '#e2e8f0',
                            borderWidth: 1,
                            cornerRadius: 8,
                            usePointStyle: true,
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed.y !== null) {
                                        if (context.dataset.yAxisID === 'y1') {
                                            label += new Intl.NumberFormat('fr-FR').format(context.parsed.y) + ' FCFA';
                                        } else {
                                            label += context.parsed.y;
                                        }
                                    }
                                    return label;
                                }
                            }
                        }
                    },
                    animation: {
                        duration: 1000,
                        easing: 'easeOutQuart'
                    }
                }
            });
        }

        // 📊 Graphique des produits les plus commandés
        const topProductsCtx = document.getElementById('topProductsChart');
        if (topProductsCtx) {
            const topProductsChart = new Chart(topProductsCtx, {
                type: 'bar',
                data: {
                    labels: productNames,
                    datasets: [
                        {
                            label: 'Quantité commandée',
                            data: productQuantities,
                            backgroundColor: chartColors.primary[0],
                            borderColor: chartColors.primary[0],
                            borderWidth: 0,
                            borderRadius: 6,
                            barPercentage: 0.7
                        },
                        {
                            label: 'Montant dépensé (FCFA)',
                            data: productSpent,
                            backgroundColor: chartColors.primary[4],
                            borderColor: chartColors.primary[4],
                            borderWidth: 0,
                            borderRadius: 6,
                            barPercentage: 0.7
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    indexAxis: 'y',
                    scales: {
                        x: {
                            beginAtZero: true,
                            grid: {
                                drawBorder: false
                            },
                            title: {
                                display: true,
                                text: 'Quantité / Montant',
                                color: '#334155'
                            },
                            ticks: {
                                color: '#334155',
                                callback: function(value) {
                                    return new Intl.NumberFormat('fr-FR').format(value);
                                }
                            }
                        },
                        y: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: '#334155'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                color: '#1e293b',
                                usePointStyle: true,
                                pointStyle: 'circle'
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(255, 255, 255, 0.95)',
                            titleColor: '#1e293b',
                            bodyColor: '#334155',
                            borderColor: '#e2e8f0',
                            borderWidth: 1,
                            cornerRadius: 8,
                            usePointStyle: true,
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed.x !== null) {
                                        label += new Intl.NumberFormat('fr-FR').format(context.parsed.x);
                                        if (context.datasetIndex === 1) {
                                            label += ' FCFA';
                                        }
                                    }
                                    return label;
                                }
                            }
                        }
                    },
                    animation: {
                        duration: 1000,
                        easing: 'easeOutQuart'
                    }
                }
            });
        }
    });
</script>
@endsection