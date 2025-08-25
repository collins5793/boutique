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
@endsection