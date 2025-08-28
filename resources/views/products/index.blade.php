@extends('layouts.apli')

@section('title', 'Liste des Produits')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Liste des Produits</h1>
        <div>
            <a href="{{ route('products.create') }}" class="btn btn-primary me-2">
                <i class="fas fa-plus"></i> Ajouter
            </a>
            <form action="{{ route('products.index') }}" method="GET" class="d-inline">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Recherche..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-outline-secondary">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    @if($products->isEmpty())
        <div class="alert alert-info">Aucun produit trouvé</div>
    @else
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Nom</th>
                        <th>Catégorie</th>
                        <th>Prix</th>
                        <th>Stock</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                    <tr>
                        <td>{{ $product->id }}</td>
                        <td>
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" width="50" alt="{{ $product->name }}">
                            @else
                                <span class="text-muted">Aucune</span>
                            @endif
                        </td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->category->name }}</td>
                        <td>
                            @if($product->discount_price)
                                <span class="text-danger">{{ number_format($product->discount_price, 2) }} €</span>
                                <del class="text-muted small">{{ number_format($product->price, 2) }} €</del>
                            @else
                                {{ number_format($product->price, 2) }} €
                            @endif
                        </td>
                        <td @class(['text-danger' => $product->stock_quantity <= 0])>
                            {{ $product->stock_quantity }}
                        </td>
                        <td>
                            <span @class([
                                'badge',
                                'bg-success' => $product->status == 'active',
                                'bg-secondary' => $product->status == 'inactive'
                            ])>
                                {{ $product->status == 'active' ? 'Actif' : 'Inactif' }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('products.destroy', $product) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Confirmer la suppression ?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $products->appends(request()->query())->links() }}
    @endif
</div>
@endsection