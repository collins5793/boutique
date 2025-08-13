@extends('layouts.apli')

@section('title', 'Détails Catégorie')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2 class="mb-0">Détails de la catégorie</h2>
            <div>
                <a href="{{ route('categories.edit', $category) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Modifier
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>ID:</strong> {{ $category->id }}</p>
                    <p><strong>Nom:</strong> {{ $category->name }}</p>
                    <p><strong>Slug:</strong> {{ $category->slug }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Parent:</strong> {{ $category->parent->name ?? 'Aucun' }}</p>
                    <p><strong>Description:</strong> {{ $category->description ?? 'Non renseignée' }}</p>
                </div>
            </div>

            <hr>

            <h4 class="mt-4">Sous-catégories</h4>
            @if($category->children->isEmpty())
                <p class="text-muted">Aucune sous-catégorie</p>
            @else
                <ul class="list-group">
                    @foreach($category->children as $child)
                        <li class="list-group-item">
                            <a href="{{ route('categories.show', $child) }}">{{ $child->name }}</a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</div>
@endsection