@extends('layouts.clients.client')

@section('title', 'Récompenses & Fidélité')

@section('content')
<div class="container py-4">

    <h2 class="mb-4">🎁 Mes récompenses & fidélité</h2>

    <!-- Total des points -->
    <div class="card mb-4 shadow-sm" style="border-radius: 12px;">
        <div class="card-body text-center">
            <h4>Total de points cumulés</h4>
            <h2 class="text-success fw-bold">{{ $totalPoints }} pts</h2>
        </div>
    </div>

    <!-- Graphique -->
    <div class="card mb-4 shadow-sm" style="border-radius: 12px;">
        <div class="card-body">
            <h5>Évolution de mes points</h5>
            <canvas id="pointsChart"></canvas>
        </div>
    </div>

    <!-- Historique -->
    <div class="card shadow-sm" style="border-radius: 12px;">
        <div class="card-body">
            <h5>Historique</h5>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Raison</th>
                        <th>Points</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($history as $item)
                        <tr>
                            <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $item->reason }}</td>
                            <td class="fw-bold text-success">+{{ $item->points }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Script Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('pointsChart').getContext('2d');
    const pointsChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json(array_keys($pointsByMonth->toArray())),
            datasets: [{
                label: 'Points par mois',
                data: @json(array_values($pointsByMonth->toArray())),
                borderColor: '#28a745',
                backgroundColor: 'rgba(40, 167, 69, 0.2)',
                borderWidth: 2,
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>
@endsection
