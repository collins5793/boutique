@extends('layouts.apli')

@section('title', 'Produits de ' . $category->name)

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Produits dans {{ $category->name }}</h1>
        <a href="{{ route('products.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Ajouter produit
        </a>
    </div>

    @if($products->isEmpty())
        <div class="alert alert-info">Aucun produit dans cette catégorie</div>
    @else
        <div class="row">
            @foreach($products as $product)
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $product->name }}</h5>
                        <p class="card-text">{{ Str::limit($product->description, 100) }}</p>
                        <p class="h5">{{ number_format($product->finalPrice, 2) }} €</p>
                    </div>
                    <div class="card-footer bg-white">
                        <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-outline-primary">
                            Voir détails
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        {{ $products->links() }}
    @endif
</div>
@endsection