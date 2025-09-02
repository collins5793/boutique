@extends('layouts.admins.admin')

@section('content')
<div class="container mx-auto p-4">

    <!-- Header & Filtres -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">📊 Ventes & Commandes</h1>
        <form method="GET" class="flex gap-2">
            <select name="type" class="border rounded p-2">
                <option value="">Tous les types</option>
                <option value="direct">Ventes Directes</option>
                <option value="order">Commandes</option>
            </select>
            <select name="status" class="border rounded p-2">
                <option value="">Tous statuts</option>
                <option value="pending">En attente</option>
                <option value="paid">Payé</option>
                <option value="cancelled">Annulé</option>
            </select>
            <select name="payment_method" class="border rounded p-2">
                <option value="">Méthode de paiement</option>
                <option value="cash_on_delivery">Cash</option>
                <option value="mobile_money">Mobile Money</option>
                <option value="card">Carte</option>
            </select>
            <input type="date" name="start_date" class="border rounded p-2">
            <input type="date" name="end_date" class="border rounded p-2">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Filtrer</button>
        </form>
    </div>

    <!-- KPI Cards -->
    <div class="grid grid-cols-5 gap-4 mb-6">
        <div class="bg-white shadow rounded p-4 text-center">💵<br>Total Ventes Directes: {{ $kpi['total_direct_sales'] }} </div>
        <div class="bg-white shadow rounded p-4 text-center">🚚<br>Total Commandes: {{ $kpi['total_orders'] }} </div>
        <div class="bg-white shadow rounded p-4 text-center">⏳<br>Commandes en attente: {{ $kpi['pending_orders'] }} </div>
        <div class="bg-white shadow rounded p-4 text-center">❌<br>Commandes annulées: {{ $kpi['cancelled_orders'] }} </div>
        <div class="bg-white shadow rounded p-4 text-center">📦<br>Produits vendus: {{ $kpi['total_products_sold'] }} </div>
    </div>

    <!-- Graphiques -->
    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="bg-white shadow rounded p-4 h-64">
            <canvas id="salesChart"></canvas>
        </div>
        {{-- <div class="bg-white shadow rounded p-4 h-64 flex items-center justify-center">Répartition paiement</div>
        <div class="bg-white shadow rounded p-4 h-64 flex items-center justify-center">Autres KPIs</div> --}}
    </div>

    <!-- Tableau détaillé -->
    <div class="bg-white shadow rounded p-4">
        <table class="min-w-full table-auto">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-4 py-2">Type</th>
                    <th class="px-4 py-2">Numéro</th>
                    <th class="px-4 py-2">Client</th>
                    <th class="px-4 py-2">Produits</th>
                    <th class="px-4 py-2">Montant</th>
                    <th class="px-4 py-2">Méthode</th>
                    <th class="px-4 py-2">Statut</th>
                    <th class="px-4 py-2">Date</th>
                    <th class="px-4 py-2">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($directSales as $sale)
                <tr>
                    <td class="border px-4 py-2">Directe</td>
                    <td class="border px-4 py-2">{{ $sale->id }}</td>
                    <td class="border px-4 py-2">Vente Comptoir</td>
                    <td class="border px-4 py-2">
                        @foreach($sale->items as $item)
                            {{ $item->product->name }} ({{ $item->quantity }})<br>
                        @endforeach
                    </td>
                    <td class="border px-4 py-2">{{ $sale->total }}</td>
                    <td class="border px-4 py-2">{{ $sale->payment_method }}</td>
                    <td class="border px-4 py-2">Payé</td>
                    <td class="border px-4 py-2">{{ $sale->created_at->format('d/m/Y H:i') }}</td>
                    <td class="border px-4 py-2">
                        <a href="#" class="text-blue-500">Détails</a>
                    </td>
                </tr>
                @endforeach

                @foreach($orders as $order)
                <tr>
                    <td class="border px-4 py-2">Commande</td>
                    <td class="border px-4 py-2">{{ $order->order_number }}</td>
                    <td class="border px-4 py-2">{{ $order->user->name ?? 'N/A' }}</td>
                    <td class="border px-4 py-2">
                        @foreach($order->items as $item)
                            {{ $item->product->name }} ({{ $item->quantity }})<br>
                        @endforeach
                    </td>
                    <td class="border px-4 py-2">{{ $order->total_amount }}</td>
                    <td class="border px-4 py-2">{{ $order->payment_method }}</td>
                    <td class="border px-4 py-2">{{ ucfirst($order->order_status) }}</td>
                    <td class="border px-4 py-2">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                    <td class="border px-4 py-2">
                        <a href="#" class="text-blue-500">Détails</a>
                    </td>
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
const salesChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: {!! json_encode($salesEvolution['dates']) !!},
        datasets: [
            {
                label: 'Ventes Directes',
                data: {!! json_encode($salesEvolution['direct_sales']) !!},
                borderColor: 'rgba(75, 192, 192, 1)',
                fill: false
            },
            {
                label: 'Commandes',
                data: {!! json_encode($salesEvolution['orders']) !!},
                borderColor: 'rgba(255, 99, 132, 1)',
                fill: false
            }
        ]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'top' } }
    }
});
</script>
@endsection
