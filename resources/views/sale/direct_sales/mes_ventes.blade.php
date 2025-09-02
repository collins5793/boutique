@extends('layouts.sales.sale')

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
        --sidebar-width: 280px;
        --sidebar-collapsed: 90px;
        --header-height: 80px;
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        --radius: 12px;
        --shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        --card-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    body {
        background-color: #f5f7fa;
        color: var(--dark);
        overflow-x: hidden;
    }

    .container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 20px;
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
        display: flex;
        align-items: center;
        gap: 10px;
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

    .stat-card h4 {
        font-size: 24px;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 8px;
    }

    .stat-card p {
        font-size: 14px;
        color: var(--dark-light);
        margin: 0;
    }

    .stat-card .icon {
        position: absolute;
        top: 20px;
        right: 20px;
        font-size: 24px;
        color: var(--primary-light);
        opacity: 0.3;
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

    .chart-card h5 {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 20px;
        color: var(--dark);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .chart-container {
        position: relative;
        height: 300px;
        width: 100%;
    }

    /* Tableau */
    .table-card {
        background: var(--light);
        border-radius: var(--radius);
        padding: 24px;
        box-shadow: var(--card-shadow);
        animation: fadeIn 0.8s ease-out;
        margin-bottom: 30px;
    }

    .table-card h5 {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 20px;
        color: var(--dark);
        display: flex;
        align-items: center;
        gap: 10px;
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

    .payment-badge {
        display: inline-block;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
    }

    .payment-cash {
        background-color: #ecfdf5;
        color: #10b981;
    }

    .payment-card {
        background-color: #eff6ff;
        color: #3b82f6;
    }

    .payment-mobile {
        background-color: #fef3c7;
        color: #f59e0b;
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
        .charts-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .container {
            padding: 15px;
        }
        
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .charts-grid {
            grid-template-columns: 1fr;
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
    
    .no-data-message {
        text-align: center;
        padding: 40px;
        color: #94a3b8;
        font-style: italic;
    }


        .today-summary {
            margin-bottom: 30px;
            
        }
        .container-summary {
            display: flex;
            align-items: center; /* aligne verticalement */
            gap: 10px; /* espace entre les éléments */
            margin-bottom: 2rem;
        }

        .today-summary .card {
            border: none;
            border-radius: var(--radius);
            box-shadow: var(--card-shadow);
            transition: var(--transition);
            overflow: hidden;
            background: var(--light);
            flex: 1;
            min-width: 250px;
            position: relative;
            animation: fadeInUp 0.6s ease-out forwards;
            opacity: 0;
        }

        .today-summary .card:nth-child(1) {
            animation-delay: 0.1s;
        }

        .today-summary .card:nth-child(2) {
            animation-delay: 0.2s;
        }

        .today-summary .card:nth-child(3) {
            animation-delay: 0.3s;
        }

        .today-summary .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
        }

        .today-summary .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--accent));
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.3s ease;
        }

        .today-summary .card:hover::before {
            transform: scaleX(1);
        }

        .today-summary .card h6 {
            color: var(--dark-light);
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            position: relative;
            display: inline-block;
        }

        .today-summary .card h6::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 30px;
            height: 2px;
            background-color: var(--primary);
            transition: width 0.3s ease;
        }

        .today-summary .card:hover h6::after {
            width: 100%;
        }

        .today-summary .card .h4 {
            color: var(--dark);
            font-weight: 700;
            margin-bottom: 10px;
            transition: var(--transition);
        }

        .today-summary .card:hover .h4 {
            color: var(--primary);
        }

        .today-summary .card small {
            color: var(--dark-light);
            font-size: 0.8rem;
        }

        .today-summary .card .text-danger {
            color: #e74c3c !important;
            position: relative;
        }

        .today-summary .card .text-danger::after {
            content: '⚠️';
            margin-left: 5px;
            animation: pulse 2s infinite;
        }

        .today-summary .card ul {
            margin-top: 10px;
        }

        .today-summary .card ul li {
            padding: 5px 0;
            border-bottom: 1px dashed #eee;
            font-size: 0.85rem;
            animation: slideInRight 0.5s ease-out forwards;
            opacity: 0;
        }

        .today-summary .card ul li:nth-child(1) {
            animation-delay: 0.4s;
        }

        .today-summary .card ul li:nth-child(2) {
            animation-delay: 0.5s;
        }

        .today-summary .card ul li:nth-child(3) {
            animation-delay: 0.6s;
        }

        .today-summary .card ul li:last-child {
            border-bottom: none;
        }

        .today-summary .card ul li strong {
            color: var(--dark);
        }

        /* Animations */
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

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(10px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.1);
            }
            100% {
                transform: scale(1);
            }
        }

        /* Responsive */
        @media (max-width: 992px) {
            .today-summary {
                flex-direction: column;
            }
            
            .today-summary .card {
                margin-bottom: 15px;
                min-width: auto;
            }
        }
    </style>

        

 


<div class="container">
    <div class="dashboard-header">
        <h1><i class="fas fa-chart-line"></i> Mes ventes</h1>
    </div>
<div class="today-summary d-flex gap-3">
    <div class="container-summary">
        <div class="card p-3">
                <h6>Aujourd'hui — Total</h6>
                <div class="h4">{{ number_format($todayTotal, 0, ',', ' ') }} FCFA</div>
                <small>{{ $todaySalesCount }} ventes • {{ $todayProductsSold }} articles</small>
            </div>

            <div class="card p-3">
                <h6>Montant cash à déposer</h6>
                    <div class="h4 text-danger">{{ number_format($cashToDeposit, 0, ',', ' ') }} FCFA</div>
                <small>Vérifier la caisse</small>
            </div>
    </div>
            

            <div class="card p-3">
                <h6>Répartition paiements (aujourd'hui)</h6>
                <ul class="list-unstyled mb-0">
                    @foreach($todayPaymentBreakdown ?? [] as $method => $row)
                        <li><strong>{{ $method }}</strong> : {{ number_format($row->total,0,',',' ') }} FCFA ({{ $row->count }} ventes)</li>
                    @endforeach
                </ul>
            </div>
        </div>
    <!-- Résumé rapide aujourd'hui -->

    <!-- 🔹 Résumé -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="icon"><i class="fas fa-money-bill-wave"></i></div>
            <h4>{{ number_format($totalSales, 2) }} CFA</h4>
            <p>Total des ventes</p>
        </div>
        <div class="stat-card">
            <div class="icon"><i class="fas fa-shopping-bag"></i></div>
            <h4>{{ $totalProducts }}</h4>
            <p>Produits vendus</p>
        </div>
        <div class="stat-card">
            <div class="icon"><i class="fas fa-star"></i></div>
            <h4>{{ $topProduct && $topProduct->product ? $topProduct->product->name : 'Aucun' }}</h4>
            <p>Top produit</p>
        </div>
    </div>


    <!-- 🔹 Graphiques -->
    <div class="charts-grid">
        <div class="chart-card">
            <h5><i class="fas fa-chart-line"></i> Évolution journalière</h5>
            <div class="chart-container">
                @if($salesByDay->count() > 0)
                    <canvas id="salesLineChart"></canvas>
                @else
                    <div class="no-data-message">Aucune donnée disponible pour cette période</div>
                @endif
            </div>
        </div>
        <div class="chart-card">
            <h5><i class="fas fa-chart-pie"></i> Répartition par catégorie</h5>
            <div class="chart-container">
                @if($salesByCategory->count() > 0)
                    <canvas id="salesPieChart"></canvas>
                @else
                    <div class="no-data-message">Aucune donnée disponible pour cette période</div>
                @endif
            </div>
        </div>
    </div>

    <!-- 🔹 Top produits -->
    <div class="chart-card">
        <h5><i class="fas fa-trophy"></i> Top 5 Produits</h5>
        <div class="chart-container">
            @if($topProducts->count() > 0)
                <canvas id="topProductsBarChart"></canvas>
            @else
                <div class="no-data-message">Aucune donnée disponible pour cette période</div>
            @endif
        </div>
    </div>

    <!-- 🔹 Tableau ventes récentes -->
    <div class="table-card">
        <h5><i class="fas fa-history"></i> Ventes récentes</h5>
        @if($recentSales->count() > 0)
            <table class="data-table">
                <thead>
                    <tr>
                        <th>#ID</th>
                        <th>Date</th>
                        <th>Qte Produits</th>
                        <th>Total</th>
                        <th>Paiement</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentSales as $sale)
                        <tr>
                            <td>{{ $sale->id }}</td>
                            <td>{{ $sale->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $sale->items->sum('quantity') }}</td>
                            <td>{{ number_format($sale->total, 2) }} CFA</td>
                            <td>
                                @php
                                    $paymentClass = '';
                                    if ($sale->payment_method == 'cash') {
                                        $paymentClass = 'payment-cash';
                                    } elseif ($sale->payment_method == 'card') {
                                        $paymentClass = 'payment-card';
                                    } elseif ($sale->payment_method == 'mobile') {
                                        $paymentClass = 'payment-mobile';
                                    }
                                @endphp
                                <span class="payment-badge {{ $paymentClass }}">{{ ucfirst($sale->payment_method) }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="no-data-message">Aucune vente récente</div>
        @endif
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

        // Données pour les graphiques
        const salesByDayLabels = {!! json_encode($salesByDay->keys()) !!};
        const salesByDayData = {!! json_encode($salesByDay->values()) !!};
        
        const salesByCategoryLabels = {!! json_encode($salesByCategory->keys()) !!};
        const salesByCategoryData = {!! json_encode($salesByCategory->values()) !!};
        
        const topProductsLabels = {!! json_encode($topProducts->pluck('product.name')) !!};
        const topProductsData = {!! json_encode($topProducts->pluck('qty')) !!};

        // Courbe évolution
        if (salesByDayData.length > 0) {
            new Chart(document.getElementById('salesLineChart'), {
                type: 'line',
                data: {
                    labels: salesByDayLabels,
                    datasets: [{
                        label: 'Ventes (CFA)',
                        data: salesByDayData,
                        borderColor: 'rgba(235, 119, 10, 1)',
                        backgroundColor: 'rgba(235, 119, 10, 0.1)',
                        tension: 0.3,
                        fill: true,
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                drawBorder: false
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
        }

        // Camembert
        if (salesByCategoryData.length > 0) {
            new Chart(document.getElementById('salesPieChart'), {
                type: 'doughnut',
                data: {
                    labels: salesByCategoryLabels,
                    datasets: [{
                        data: salesByCategoryData,
                        backgroundColor: [
                            'rgba(235, 119, 10, 0.7)',
                            'rgba(16, 185, 129, 0.7)',
                            'rgba(239, 68, 68, 0.7)',
                            'rgba(139, 92, 246, 0.7)',
                            'rgba(59, 130, 246, 0.7)'
                        ],
                        borderColor: [
                            'rgba(235, 119, 10, 1)',
                            'rgba(16, 185, 129, 1)',
                            'rgba(239, 68, 68, 1)',
                            'rgba(139, 92, 246, 1)',
                            'rgba(59, 130, 246, 1)'
                        ],
                        borderWidth: 1,
                        hoverOffset: 15
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right'
                        }
                    },
                    cutout: '60%'
                }
            });
        }

        // Top produits
        if (topProductsData.length > 0) {
            new Chart(document.getElementById('topProductsBarChart'), {
                type: 'bar',
                data: {
                    labels: topProductsLabels,
                    datasets: [{
                        label: 'Quantité vendue',
                        data: topProductsData,
                        backgroundColor: 'rgba(235, 119, 10, 0.7)',
                        borderColor: 'rgba(235, 119, 10, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                drawBorder: false
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
        }

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
   <script>
        // Réanimation au survol
        document.querySelectorAll('.card').forEach(card => {
            card.addEventListener('mouseenter', () => {
                card.style.transform = 'translateY(-5px)';
            });
            
            card.addEventListener('mouseleave', () => {
                card.style.transform = 'translateY(0)';
            });
        });
    </script>
@endsection