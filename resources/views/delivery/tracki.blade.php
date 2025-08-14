@extends('layouts.apli')

@section('content')
<h1>🚚 Livraison en cours</h1>

<div class="card p-3 mb-3">
    <h4>Informations client</h4>
    <p><strong>Nom :</strong> {{ $order->user->name }}</p>
    <p><strong>Téléphone :</strong> {{ $order->user->phone ?? 'Non renseigné' }}</p>
    <p><strong>Adresse :</strong> {{ $order->deliveryAddress->full_address ?? 'Adresse non précisée' }}</p>
</div>

<form action="{{ route('delivery.fin', $order->id) }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-success">🚚 Livrer cette commande</button>
</form>

    <form action="{{ route('delivery.valide', $order->id) }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-success">success</button>
    </form>


@endsection
