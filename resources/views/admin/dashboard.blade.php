@extends('layouts.admins.admin')

@section('title', 'Dashboard')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Tableau de bord du vendeur</h1>

    <!-- Statistiques rapides -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white shadow p-4 rounded">
            <h2 class="font-bold">Total Commandes</h2>
            <p class="text-xl">{{ $totalOrders }}</p>
        </div>
        <div class="bg-white shadow p-4 rounded">
            <h2 class="font-bold">Commandes en attente</h2>
            <p class="text-xl">{{ $pendingOrders }}</p>
        </div>
        <div class="bg-white shadow p-4 rounded">
            <h2 class="font-bold">Chiffre d'affaires</h2>
            <p class="text-xl">{{ number_format($totalRevenue, 2) }} FCFA</p>
        </div>
        <div class="bg-white shadow p-4 rounded">
            <h2 class="font-bold">Produits faibles en stock</h2>
            <p class="text-xl">{{ $lowStockProducts }}</p>
        </div>
    </div>

    <!-- Graphiques -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <div class="bg-white shadow p-4 rounded">
            <h2 class="font-bold mb-2">Ventes des 7 derniers jours</h2>
            <canvas id="salesChart"></canvas>
        </div>
        <div class="bg-white shadow p-4 rounded">
            <h2 class="font-bold mb-2">Commandes par statut</h2>
            <canvas id="ordersStatusChart"></canvas>
        </div>
    </div>

    <!-- Dernières commandes -->
    <div class="bg-white shadow p-4 rounded mb-6">
        <h2 class="font-bold mb-2">Dernières commandes</h2>
        <table class="w-full text-left border">
            <thead>
                <tr class="border-b">
                    <th class="p-2">Numéro</th>
                    <th class="p-2">Client</th>
                    <th class="p-2">Montant</th>
                    <th class="p-2">Statut</th>
                    <th class="p-2">Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentOrders as $order)
                    <tr class="border-b">
                        <td class="p-2">{{ $order->order_number }}</td>
                        <td class="p-2">{{ $order->user->name ?? 'Utilisateur supprimé' }}</td>
                        <td class="p-2">{{ number_format($order->total_amount, 2) }} FCFA</td>
                        <td class="p-2">{{ ucfirst($order->order_status) }}</td>
                        <td class="p-2">{{ $order->created_at->format('d/m/Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Produits faibles en stock -->
    <div class="bg-white shadow p-4 rounded">
        <h2 class="font-bold mb-2">Produits faibles en stock</h2>
        <ul>
            @foreach($lowStockItems as $product)
                <li>{{ $product->name }} - Stock: {{ $product->stock_quantity }}</li>
            @endforeach
        </ul>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Ventes 7 derniers jours
    const salesData = {
        labels: @json($salesChart->pluck('date')),
        datasets: [{
            label: 'Ventes (FCFA)',
            data: @json($salesChart->pluck('total')),
            backgroundColor: 'rgba(54, 162, 235, 0.5)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    };
    new Chart(document.getElementById('salesChart'), {
        type: 'bar',
        data: salesData,
        options: {
            responsive: true,
            plugins: { legend: { display: false } }
        }
    });

    // Commandes par statut
    const ordersStatusData = {
        labels: @json($ordersStatusChart->pluck('order_status')),
        datasets: [{
            label: 'Commandes',
            data: @json($ordersStatusChart->pluck('count')),
            backgroundColor: [
                'rgba(255, 206, 86, 0.5)',
                'rgba(54, 162, 235, 0.5)',
                'rgba(75, 192, 192, 0.5)',
                'rgba(153, 102, 255, 0.5)'
            ],
            borderColor: [
                'rgba(255, 206, 86, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)'
            ],
            borderWidth: 1
        }]
    };
    new Chart(document.getElementById('ordersStatusChart'), {
        type: 'pie',
        data: ordersStatusData,
        options: { responsive: true }
    });
</script>
@endsection
