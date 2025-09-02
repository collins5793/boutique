@extends('layouts.admins.admin')

@section('title','📊 Ventes & Commandes')

@section('content')
<style>
    :root {
        --primary: #eb770a;
        --primary-light: #ec8a2f;
        --primary-dark: #be6109;
        --secondary: #007bff;
        --light: #ffffff;
        --dark: #1e293b;
        --muted: #64748b;
        --radius: 12px;
        --shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        --transition: all 0.3s ease-in-out;
    }

    .page-container {
        padding: 1.5rem;
        background: #f8fafc;
        min-height: calc(100vh - 80px);
    }

    /* Header */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .page-header h1 {
        font-size: 1.6rem;
        font-weight: 700;
        color: var(--dark);
    }

    .filters-form {
        display: flex;
        flex-wrap: wrap;
        gap: 0.6rem;
    }

    .filters-form select,
    .filters-form input[type="date"] {
        border: 1px solid #e2e8f0;
        border-radius: var(--radius);
        padding: 0.5rem 0.75rem;
        font-size: 0.9rem;
    }

    .filters-form button {
        background: var(--primary);
        color: white;
        border: none;
        border-radius: var(--radius);
        padding: 0.6rem 1.2rem;
        cursor: pointer;
        font-weight: 600;
        transition: var(--transition);
    }

    .filters-form button:hover {
        background: var(--primary-dark);
    }

    /* KPI Cards */
    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .kpi-card {
        background: var(--light);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        padding: 1.5rem;
        text-align: center;
        font-size: 0.95rem;
        font-weight: 500;
        color: var(--dark);
        transition: var(--transition);
    }

    .kpi-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    .kpi-card span {
        display: block;
        font-size: 1.6rem;
        font-weight: 700;
        margin-top: 0.5rem;
        color: var(--primary);
    }

    /* Graphiques */
    .charts-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .chart-card {
        background: var(--light);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        padding: 1rem;
    }

    /* Tableau */
    .table-container {
        background: var(--light);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        padding: 1rem;
        overflow-x: auto;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.9rem;
    }

    .data-table thead {
        background: #f1f5f9;
    }

    .data-table thead th {
        text-align: left;
        padding: 0.8rem;
        font-weight: 600;
        color: var(--muted);
    }

    .data-table tbody td {
        padding: 0.8rem;
        border-bottom: 1px solid #e2e8f0;
        color: var(--dark);
    }

    .data-table tbody tr:hover {
        background: #f9fafb;
    }

    .badge {
        display: inline-block;
        padding: 0.35rem 0.75rem;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .badge-paid { background: #16a34a; color: white; }
    .badge-pending { background: #facc15; color: #1e293b; }
    .badge-cancelled { background: #dc2626; color: white; }

    .link-details {
        color: var(--secondary);
        font-weight: 600;
        text-decoration: none;
    }

    .link-details:hover {
        text-decoration: underline;
    }
</style>

<div class="page-container">
    <!-- Header & Filtres -->
    <div class="page-header">
        <h1>📊 Ventes & Commandes</h1>
        <form method="GET" class="filters-form">
            <select name="type">
                <option value="">Tous les types</option>
                <option value="direct">Ventes Directes</option>
                <option value="order">Commandes</option>
            </select>
            <select name="status">
                <option value="">Tous statuts</option>
                <option value="pending">En attente</option>
                <option value="paid">Payé</option>
                <option value="cancelled">Annulé</option>
            </select>
            <select name="payment_method">
                <option value="">Méthode de paiement</option>
                <option value="cash_on_delivery">Cash</option>
                <option value="mobile_money">Mobile Money</option>
                <option value="card">Carte</option>
            </select>
            <input type="date" name="start_date">
            <input type="date" name="end_date">
            <button type="submit">Filtrer</button>
        </form>
    </div>

    <!-- KPI Cards -->
    <div class="kpi-grid">
        <div class="kpi-card">💵 Total Ventes Directes <span>{{ $kpi['total_direct_sales'] }}</span></div>
        <div class="kpi-card">🚚 Total Commandes <span>{{ $kpi['total_orders'] }}</span></div>
        <div class="kpi-card">⏳ En attente <span>{{ $kpi['pending_orders'] }}</span></div>
        <div class="kpi-card">❌ Annulées <span>{{ $kpi['cancelled_orders'] }}</span></div>
        <div class="kpi-card">📦 Produits vendus <span>{{ $kpi['total_products_sold'] }}</span></div>
    </div>

    <!-- Graphiques -->
    <div class="charts-grid">
        <div class="chart-card">
            <canvas id="salesChart"></canvas>
        </div>
    </div>

    <!-- Tableau détaillé -->
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Numéro</th>
                    <th>Client</th>
                    <th>Produits</th>
                    <th>Montant</th>
                    <th>Méthode</th>
                    <th>Statut</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($directSales as $sale)
                <tr>
                    <td>Directe</td>
                    <td>{{ $sale->id }}</td>
                    <td>Vente Comptoir</td>
                    <td>
                        @foreach($sale->items as $item)
                            {{ $item->product->name }} ({{ $item->quantity }})<br>
                        @endforeach
                    </td>
                    <td>{{ $sale->total }}</td>
                    <td>{{ $sale->payment_method }}</td>
                    <td><span class="badge badge-paid">Payé</span></td>
                    <td>{{ $sale->created_at->format('d/m/Y H:i') }}</td>
                    <td><a href="#" class="link-details">Détails</a></td>
                </tr>
                @endforeach

                @foreach($orders as $order)
                <tr>
                    <td>Commande</td>
                    <td>{{ $order->order_number }}</td>
                    <td>{{ $order->user->name ?? 'N/A' }}</td>
                    <td>
                        @foreach($order->items as $item)
                            {{ $item->product->name }} ({{ $item->quantity }})<br>
                        @endforeach
                    </td>
                    <td>{{ $order->total_amount }}</td>
                    <td>{{ $order->payment_method }}</td>
                    <td>
                        @if($order->order_status === 'pending')
                            <span class="badge badge-pending">En attente</span>
                        @elseif($order->order_status === 'cancelled')
                            <span class="badge badge-cancelled">Annulé</span>
                        @else
                            <span class="badge badge-paid">{{ ucfirst($order->order_status) }}</span>
                        @endif
                    </td>
                    <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                    <td><a href="#" class="link-details">Détails</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('salesChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: {!! json_encode($salesEvolution['dates']) !!},
        datasets: [
            {
                label: 'Ventes Directes',
                data: {!! json_encode($salesEvolution['direct_sales']) !!},
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.3,
                fill: true
            },
            {
                label: 'Commandes',
                data: {!! json_encode($salesEvolution['orders']) !!},
                borderColor: 'rgba(255, 99, 132, 1)',
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                tension: 0.3,
                fill: true
            }
        ]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'top' } },
        interaction: { mode: 'index', intersect: false },
        scales: {
            x: { ticks: { color: '#64748b' } },
            y: { ticks: { color: '#64748b' } }
        }
    }
});
</script>
@endsection
