@extends('layouts.clients.client')

@section('title', "Commande #{$order->order_number}")

@section('content')
<div class="container">
    <h2 class="mb-4">🧾 Détail de la commande #{{ $order->order_number }}</h2>

    {{-- Infos principales --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-body row">
            <div class="col-md-6">
                <p><b>Date :</b> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                <p><b>Statut :</b>
                    <span class="badge bg-{{ $order->order_status == 'pending' ? 'warning' :
                                             ($order->order_status == 'processing' ? 'info' :
                                             ($order->order_status == 'shipped' ? 'primary' :
                                             ($order->order_status == 'delivered' ? 'success' : 'danger'))) }}">
                        {{ ucfirst($order->order_status) }}
                    </span>
                </p>
            </div>
            <div class="col-md-6">
                <p><b>Paiement :</b> {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</p>
                
                {{-- Adresse de livraison --}}
                @if($order->deliveryAddress)
                    <p><b>Type d'adresse :</b> {{ ucfirst($order->deliveryAddress->address_type) }}</p>
                    <p><b>Adresse :</b> {{ $order->deliveryAddress->full_address }}</p>
                    @if($order->deliveryAddress->landmarks)
                        <p><b>Repères :</b> {{ $order->deliveryAddress->landmarks }}</p>
                    @endif
                    @if($order->deliveryAddress->latitude && $order->deliveryAddress->longitude)
                        <p>
                            <b>Coordonnées :</b> 
                            {{ $order->deliveryAddress->latitude }}, {{ $order->deliveryAddress->longitude }}
                        </p>
                    @endif
                @else
                    <p><b>Adresse :</b> Non renseignée</p>
                @endif
            </div>
        </div>
    </div>

    {{-- Produits --}}
    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="mb-3">Produits commandés</h5>
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Produit</th>
                            <th>Qté</th>
                            <th>Prix unitaire</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td>{{ $item->product->name ?? 'Produit supprimé' }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ number_format($item->price, 0, ',', ' ') }} CFA</td>
                                <td>{{ number_format($item->total, 0, ',', ' ') }} CFA</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <h4 class="text-end text-primary mt-3">
                Total : {{ number_format($order->total_amount, 0, ',', ' ') }} CFA
            </h4>
        </div>
    </div>

    {{-- Bouton retour --}}
    <div class="mt-4">
        <a href="{{ route('client.orders') }}" class="btn btn-secondary">⬅ Retour aux commandes</a>
    </div>
</div>

<style>
/* ----------- VARIABLES ----------- */
:root {
    --primary: #f506c4;
    --primary-light: #ff33d1;
    --primary-dark: #c0049b;
    --secondary: #007bff;
    --accent: #ff7b00;
    --dark: #1e293b;
    --dark-light: #334155;
    --light: #ffffff;
    --sidebar-width: 280px;
    --sidebar-collapsed: 85px;
    --header-height: 80px;
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    --radius: 12px;
    --shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    --mobile-breakpoint: 1024px;
    --tablet-breakpoint: 768px;
    --phone-breakpoint: 576px;
}

/* ----------- STYLES DE BASE ----------- */
.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 1.5rem;
}

/* ----------- TITRE ----------- */
h2 {
    color: var(--dark);
    font-size: 2rem;
    margin-bottom: 1.5rem;
    font-weight: 700;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid var(--primary-light);
}

h5 {
    color: var(--dark);
    font-size: 1.25rem;
    margin-bottom: 1rem;
    font-weight: 600;
}

/* ----------- CARTES ----------- */
.card {
    background: var(--light);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    transition: var(--transition);
    border: none;
    overflow: hidden;
    margin-bottom: 1.5rem;
}

.card:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
}

.card-body {
    padding: 1.5rem;
}

.row {
    display: flex;
    flex-wrap: wrap;
    margin: 0 -0.75rem;
}

.col-md-6 {
    flex: 0 0 50%;
    max-width: 50%;
    padding: 0 0.75rem;
}

/* ----------- INFORMATIONS ----------- */
p {
    margin-bottom: 0.75rem;
    line-height: 1.5;
}

b {
    color: var(--dark);
    font-weight: 600;
}

/* ----------- BADGES DE STATUT ----------- */
.badge {
    display: inline-block;
    padding: 0.5rem 1rem;
    border-radius: 50px;
    font-weight: 600;
    font-size: 0.875rem;
}

.bg-warning {
    background: #f39c12 !important;
    color: white;
}

.bg-info {
    background: #3498db !important;
    color: white;
}

.bg-primary {
    background: var(--primary) !important;
    color: white;
}

.bg-success {
    background: #27ae60 !important;
    color: white;
}

.bg-danger {
    background: #e74c3c !important;
    color: white;
}

/* ----------- TABLEAU PRODUITS ----------- */
.table-responsive {
    overflow-x: auto;
    border-radius: var(--radius);
    margin-bottom: 1.5rem;
}

table {
    width: 100%;
    border-collapse: collapse;
}

.table-bordered {
    border: 1px solid #eee;
}

.table-bordered th,
.table-bordered td {
    border: 1px solid #eee;
}

thead {
    background: var(--dark);
}

th {
    padding: 1rem;
    text-align: left;
    color: var(--light);
    font-weight: 600;
}

tbody tr {
    border-bottom: 1px solid #eee;
    transition: var(--transition);
}

tbody tr:hover {
    background: #f9f9f9;
}

td {
    padding: 1rem;
    vertical-align: middle;
}

/* ----------- TOTAL ----------- */
.text-end {
    text-align: right;
}

.text-primary {
    color: var(--primary) !important;
}

.mt-3 {
    margin-top: 1rem;
}

h4 {
    font-size: 1.5rem;
    font-weight: 700;
}

/* ----------- BOUTONS ----------- */
.mt-4 {
    margin-top: 1.5rem;
}

.btn {
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: var(--radius);
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition);
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    text-decoration: none;
}

.btn-secondary {
    background: var(--dark-light);
    color: var(--light);
}

.btn-secondary:hover {
    background: var(--dark);
    color: var(--light);
}

/* ----------- RESPONSIVE ----------- */
@media (max-width: 1024px) {
    .container {
        padding: 1.25rem;
    }
    
    h2 {
        font-size: 1.75rem;
    }
}

@media (max-width: 768px) {
    .container {
        padding: 1rem;
    }
    
    .col-md-6 {
        flex: 0 0 100%;
        max-width: 100%;
        margin-bottom: 1rem;
    }
    
    h2 {
        font-size: 1.5rem;
    }
    
    h4 {
        font-size: 1.25rem;
    }
    
    .card-body {
        padding: 1rem;
    }
    
    table {
        font-size: 0.9rem;
    }
    
    th, td {
        padding: 0.75rem 0.5rem;
    }
}

@media (max-width: 576px) {
    .container {
        padding: 0.75rem;
    }
    
    h2 {
        font-size: 1.25rem;
    }
    
    h5 {
        font-size: 1.1rem;
    }
    
    table {
        font-size: 0.8rem;
    }
    
    th, td {
        padding: 0.5rem 0.25rem;
    }
    
    .btn {
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
    }
    
    .badge {
        font-size: 0.8rem;
        padding: 0.4rem 0.8rem;
    }
}

/* ----------- AMÉLIORATIONS VISUELLES ----------- */
.table-light {
    background: #f8f9fa !important;
}

.text-muted {
    color: var(--dark-light) !important;
}

.align-middle {
    vertical-align: middle !important;
}

/* ----------- EFFETS DE SURVOL ----------- */
.btn {
    transition: var(--transition);
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

/* ----------- SÉPARATEURS ----------- */
hr {
    border: none;
    height: 1px;
    background: #eee;
    margin: 1.5rem 0;
}
</style>
@endsection