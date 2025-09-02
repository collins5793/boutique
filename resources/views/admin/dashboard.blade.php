@extends('layouts.admins.admin') {{-- ou ton layout propriétaire --}}

@section('title','Dashboard propriétaire')

@section('content')
<div class="container py-4">
    {{-- Header / résumé rapide --}}
    <div class="row g-3 mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h1 class="h4 mb-0">Tableau de bord - Boutique</h1>
            <div>
                <small class="text-muted">Période : {{ $start->format('d/m/Y') }} → {{ $end->format('d/m/Y') }}</small>
            </div>
        </div>
    </div>

    <div class="row gy-3">
        <div class="col-lg-3 col-md-6">
            <div class="card p-3 shadow-sm">
                <small class="text-muted">Chiffre d’affaires (période)</small>
                <h3 class="mt-2">{{ number_format($totalRevenue, 0, ',', ' ') }} FCFA</h3>
                <small class="text-muted">Direct: {{ number_format($directTotal,0,',',' ') }} • Commandes: {{ number_format($ordersTotal,0,',',' ') }}</small>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card p-3 shadow-sm">
                <small class="text-muted">Nombre de ventes</small>
                <h3 class="mt-2">{{ $totalSalesCount }}</h3>
                <small class="text-muted">Direct: {{ $directCount }} • Commandes: {{ $ordersCount }}</small>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card p-3 shadow-sm">
                <small class="text-muted">Produits vendus</small>
                <h3 class="mt-2">{{ $totalProductsSold }}</h3>
                <small class="text-muted">Clients (distincts) : {{ $customersCount }}</small>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card p-3 shadow-sm">
                <small class="text-muted">Top produit</small>
                @if($topProduct && $topProduct['product'])
                    <h5 class="mt-2 mb-0">{{ $topProduct['product']->name }}</h5>
                    <small class="text-muted">{{ $topProduct['quantity'] }} unités</small>
                @else
                    <div class="text-muted">Aucun produit</div>
                @endif
            </div>
        </div>
    </div>

    {{-- Graphiques --}}
    <div class="row mt-4 g-3">
        <div class="col-lg-8">
            <div class="card p-3 shadow-sm">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <strong>Évolution des ventes (Direct vs Commandes)</strong>
                    <small class="text-muted">Sommes journalières</small>
                </div>
                <canvas id="salesLineChart" height="120"></canvas>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card p-3 shadow-sm mb-3">
                <strong>Répartition CA par catégorie</strong>
                <canvas id="categoryPieChart" height="220"></canvas>
            </div>

            <div class="card p-3 shadow-sm">
                <strong>Top 5 produits</strong>
                <ul class="list-group list-group-flush mt-2">
                    @forelse($topFive as $t)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ optional($t['product'])->name ?? 'Produit #' . ($t['product']->id ?? '') }}</strong>
                                <div class="small text-muted">{{ optional($t['product'])->category->name ?? '' }}</div>
                            </div>
                            <span class="badge bg-primary rounded-pill">{{ $t['quantity'] }}</span>
                        </li>
                    @empty
                        <li class="list-group-item text-muted">Aucun produit vendu</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

    {{-- Ventes récentes --}}
    <div class="row mt-4 g-3">
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header">
                    <strong>Ventes directes récentes</strong>
                </div>
                <div class="card-body p-0">
                    <table class="table mb-0">
                        <thead class="table-light">
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
                                    <td>{{ $s->items->sum('quantity') ?? '-' }}</td>
                                </tr>
                            @endforeach
                            @if($recentDirectSales->isEmpty())
                                <tr><td colspan="4" class="text-muted text-center">Aucune vente directe</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header">
                    <strong>Commandes récentes</strong>
                </div>
                <div class="card-body p-0">
                    <table class="table mb-0">
                        <thead class="table-light">
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
                                    <td>{{ $o->items->sum('quantity') ?? '-' }}</td>
                                </tr>
                            @endforeach
                            @if($recentOrders->isEmpty())
                                <tr><td colspan="4" class="text-muted text-center">Aucune commande</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
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
                    borderWidth: 2,
                    tension: 0.3,
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37,99,235,0.08)',
                    fill: true,
                },
                {
                    label: 'Commandes',
                    data: seriesOrders,
                    borderWidth: 2,
                    tension: 0.3,
                    borderColor: '#16a34a',
                    backgroundColor: 'rgba(16,163,74,0.06)',
                    fill: true,
                }
            ]
        },
        options: {
            responsive: true,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { position: 'top' }
            },
            scales: {
                x: { display: true, title: { display: false } },
                y: { display: true, ticks: { callback: val => val.toLocaleString() } }
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
            plugins: { legend: { position: 'bottom' } }
        }
    });
</script>

@endsection
