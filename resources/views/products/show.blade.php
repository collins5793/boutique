@extends('layouts.apli')

@section('title', 'Détails Produit')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2 class="mb-0">Détails du produit</h2>
            <div>
                <a href="{{ route('products.edit', $product) }}" class="btn btn-warning me-2">
                    <i class="fas fa-edit"></i> Modifier
                </a>
                <a href="{{ route('products.index') }}" class="btn btn-secondary">
                    <i class="fas fa-list"></i> Retour
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid rounded mb-3" alt="{{ $product->name }}">
                    @endif

                    @if($product->gallery)
                        <div class="row g-2">
                            @foreach(json_decode($product->gallery) as $image)
                                <div class="col-4">
                                    <img src="{{ asset('storage/' . $image) }}" class="img-thumbnail">
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="col-md-8">
                    <h3>{{ $product->name }}</h3>
                    <p class="text-muted">SKU: {{ $product->sku }}</p>

                    <div class="mb-4">
                        @if($product->discount_price)
                            <span class="h3 text-danger">{{ number_format($product->discount_price, 2) }} €</span>
                            <del class="text-muted">{{ number_format($product->price, 2) }} €</del>
                            <span class="badge bg-danger ms-2">Promo</span>
                        @else
                            <span class="h3">{{ number_format($product->price, 2) }} €</span>
                        @endif
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p><strong>Catégorie:</strong> {{ $product->category->name }}</p>
                            <p><strong>Code-barres:</strong> {{ $product->barcode ?? 'Non renseigné' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p>
                                <strong>Stock:</strong> 
                                <span @class(['text-danger' => $product->stock_quantity <= 0])>
                                    {{ $product->stock_quantity }}
                                </span>
                            </p>
                            <p>
                                <strong>Statut:</strong> 
                                <span @class([
                                    'badge',
                                    'bg-success' => $product->status == 'active',
                                    'bg-secondary' => $product->status == 'inactive'
                                ])>
                                    {{ $product->status == 'active' ? 'Actif' : 'Inactif' }}
                                </span>
                            </p>
                        </div>
                    </div>

                    <h5>Description</h5>
                    <p>{{ $product->description ?? 'Aucune description' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection