@extends('layouts.admins.admin')

@section('title','Dashboard propriétaire')

@section('content')
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
        --header-height: 80px;
        --radius: 12px;
        --card-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        --bg-light: #f8fafc;
        --text-muted: #64748b;
        --transition: all 0.3s ease-in-out;
    }

    /* Conteneur global */
    .dashboard-containerr {
        padding: 1.5rem;
        background-color: var(--bg-light);
        min-height: calc(100vh - var(--header-height));
        transition: var(--transition);
    }

    /* Header du dashboard */
    .dashboard-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .dashboard-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--dark);
    }

    .period-selector {
        background: var(--light);
        padding: 0.6rem 1.2rem;
        border-radius: var(--radius);
        box-shadow: var(--card-shadow);
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.9rem;
        color: var(--text-muted);
    }

    /* Cartes stats */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .dashboard-card {
        background: var(--light);
        border-radius: var(--radius);
        box-shadow: var(--card-shadow);
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        transition: var(--transition);
    }

    .dashboard-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
        font-size: 1.2rem;
        background: rgba(235, 119, 10, 0.1);
        color: var(--primary);
    }

    .card-title {
        font-size: 0.9rem;
        font-weight: 500;
        color: var(--text-muted);
        margin-bottom: 0.5rem;
        text-transform: uppercase;
    }

    .card-value {
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 0.5rem;
    }

    .card-detail {
        font-size: 0.85rem;
        color: var(--text-muted);
        border-top: 1px solid rgba(0, 0, 0, 0.05);
        padding-top: 0.5rem;
        margin-top: auto;
    }

    /* Graphiques */
    .charts-section {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    @media (max-width: 992px) {
        .charts-section {
            grid-template-columns: 1fr;
        }
    }

    .chart-container {
        background: var(--light);
        border-radius: var(--radius);
        box-shadow: var(--card-shadow);
        padding: 1.5rem;
    }

    .chart-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.2rem;
    }

    .chart-header strong {
        color: var(--dark);
        font-weight: 600;
    }

    .chart-header .text-muted {
        font-size: 0.85rem;
        color: var(--text-muted);
    }

    /* Produits */
    .top-products-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .top-product-item {
        display: flex;
        justify-content: space-between;
        padding: 0.8rem 0;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }

    .product-name {
        font-weight: 600;
        color: var(--dark);
    }

    .product-category {
        font-size: 0.8rem;
        color: var(--text-muted);
    }

    .product-sales {
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--primary);
        background: rgba(235, 119, 10, 0.1);
        padding: 0.35rem 0.75rem;
        border-radius: 50px;
    }

    /* Ventes récentes */
    .recent-sales {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    @media (max-width: 768px) {
        .recent-sales {
            grid-template-columns: 1fr;
        }
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
    }

    .data-table thead th {
        padding: 1rem;
        background: #f8fafc;
        color: var(--text-muted);
        font-size: 0.85rem;
        font-weight: 500;
        border-bottom: 2px solid #e2e8f0;
        text-align: left;
    }

    .data-table tbody td {
        padding: 0.9rem 1rem;
        color: var(--dark-light);
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }

    .data-table tbody tr:hover {
        background: #f1f5f9;
    }

    .sales-badge {
        background-color: var(--primary);
        color: white;
        padding: 0.3rem 0.65rem;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    /* Animation */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .dashboard-card, .chart-container {
        animation: fadeIn 0.5s ease-in-out;
    }
</style>
    <div class="dashboard-containerr">
        {{-- Header --}}
        <div class="dashboard-header">
            <h1 class="dashboard-title">Tableau de bord - Admin</h1>
            <div class="period-selector">
                <i class="fas fa-calendar-alt"></i>
                <span>Période : {{ $start->format('d/m/Y') }} → {{ $end->format('d/m/Y') }}</span>
            </div>
        </div>

        <div class="stats-grid">
            <div class="dashboard-card">
                <div class="stat-icon">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <div class="card-title">Chiffre d’affaires (période)</div>
                <div class="card-value">{{ number_format($totalRevenue, 0, ',', ' ') }} FCFA</div>
                <p class="card-detail">Direct: {{ number_format($directTotal,0,',',' ') }} • Commandes: {{ number_format($ordersTotal,0,',',' ') }}</p>
            </div>

            <div class="dashboard-card">
                <div class="stat-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="card-title">Nombre de ventes</div>
                <div class="card-value">{{ $totalSalesCount }}</div>
                <p class="card-detail">Direct: {{ $directCount }} • Commandes: {{ $ordersCount }}</p>
            </div>

            <div class="dashboard-card">
                <div class="stat-icon">
                    <i class="fas fa-box"></i>
                </div>
                <div class="card-title">Produits vendus</div>
                <div class="card-value">{{ $totalProductsSold }}</div>
                <p class="card-detail">Clients (distincts) : {{ $customersCount }}</p>
            </div>

            <div class="dashboard-card">
                <div class="stat-icon">
                    <i class="fas fa-star"></i>
                </div>
                <div class="card-title">Top produit</div>
                @if($topProduct && $topProduct['product'])
                    <div class="card-value" style="font-size: 1.2rem;">{{ $topProduct['product']->name }}</div>
                    <p class="card-detail">{{ $topProduct['quantity'] }} unités vendues</p>
                @else
                    <div class="text-muted">Aucun produit</div>
                @endif
            </div>
        </div>

        {{-- Graphiques --}}
        <div class="charts-section">
            <div class="chart-container">
                <div class="chart-header">
                    <strong>Évolution des ventes (Direct vs Commandes)</strong>
                    <small class="text-muted">Sommes journalières</small>
                </div>
                <canvas id="salesLineChart" height="250"></canvas>
            </div>

            <div>
                <div class="chart-container">
                    <div class="chart-header">
                        <strong>Répartition CA par catégorie</strong>
                    </div>
                    <canvas id="categoryPieChart" height="220"></canvas>
                </div>

                <div class="chart-container">
                    <div class="chart-header">
                        <strong>Top 5 produits</strong>
                    </div>
                    <ul class="top-products-list">
                        @forelse($topFive as $t)
                            <li class="top-product-item">
                                <div class="product-info">
                                    <div class="product-name">{{ optional($t['product'])->name ?? 'Produit #' . ($t['product']->id ?? '') }}</div>
                                    <div class="product-category">{{ optional($t['product'])->category->name ?? '' }}</div>
                                </div>
                                <span class="product-sales">{{ $t['quantity'] }} vendus</span>
                            </li>
                        @empty
                            <li class="text-muted">Aucun produit vendu</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        {{-- Ventes récentes --}}
        <div class="recent-sales">
            <div class="chart-container">
                <div class="chart-header">
                    <strong>Ventes directes récentes</strong>
                </div>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Montant</th>
                            <th>Articles</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentDirectSales as $s)
                            <tr>
                                <td>{{ $s->id }}</td>
                                <td>{{ $s->created_at->format('d/m H:i') }}</td>
                                <td>{{ number_format($s->total,0,',',' ') }} FCFA</td>
                                <td><span class="sales-badge">{{ $s->items->sum('quantity') ?? '-' }}</span></td>
                            </tr>
                        @endforeach
                        @if($recentDirectSales->isEmpty())
                            <tr><td colspan="4" class="text-muted text-center py-3">Aucune vente directe</td></tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <div class="chart-container">
                <div class="chart-header">
                    <strong>Commandes récentes</strong>
                </div>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Montant</th>
                            <th>Articles</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentOrders as $o)
                            <tr>
                                <td>{{ $o->order_number ?? $o->id }}</td>
                                <td>{{ $o->created_at->format('d/m H:i') }}</td>
                                <td>{{ number_format($o->total_amount,0,',',' ') }} FCFA</td>
                                <td><span class="sales-badge">{{ $o->items->sum('quantity') ?? '-' }}</span></td>
                            </tr>
                        @endforeach
                        @if($recentOrders->isEmpty())
                            <tr><td colspan="4" class="text-muted text-center py-3">Aucune commande</td></tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Chart.js CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const labels = {!! json_encode($labels) !!};
        const seriesDirect = {!! json_encode($seriesDirect) !!};
        const seriesOrders = {!! json_encode($seriesOrders) !!};

        // Line chart
        const ctxLine = document.getElementById('salesLineChart').getContext('2d');
        new Chart(ctxLine, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Direct',
                        data: seriesDirect,
                        borderWidth: 3,
                        tension: 0.3,
                        borderColor: '#eb770a',
                        backgroundColor: 'rgba(235, 119, 10, 0.1)',
                        fill: true,
                    },
                    {
                        label: 'Commandes',
                        data: seriesOrders,
                        borderWidth: 3,
                        tension: 0.3,
                        borderColor: '#007bff',
                        backgroundColor: 'rgba(0, 123, 255, 0.1)',
                        fill: true,
                    }
                ]
            },
            options: {
                responsive: true,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { 
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            padding: 20
                        }
                    }
                },
                scales: {
                    x: { 
                        display: true, 
                        title: { display: false },
                        grid: {
                            display: false
                        }
                    },
                    y: { 
                        display: true, 
                        ticks: { 
                            callback: val => val.toLocaleString() + ' FCFA' 
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    }
                }
            }
        });

        // Pie chart categories
        const catLabels = {!! json_encode($categoryLabels) !!};
        const catValues = {!! json_encode($categoryValues) !!};
        const ctxPie = document.getElementById('categoryPieChart').getContext('2d');
        new Chart(ctxPie, {
            type: 'doughnut',
            data: {
                labels: catLabels,
                datasets: [{
                    data: catValues,
                    backgroundColor: [
                        '#0ea5e9','#f97316','#10b981','#8b5cf6','#ef4444','#f59e0b','#3b82f6'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: { 
                    legend: { 
                        position: 'bottom',
                        labels: {
                            padding: 15,
                            usePointStyle: true,
                        }
                    } 
                }
            }
        });
    </script>
@endsection