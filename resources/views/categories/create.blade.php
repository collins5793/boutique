@extends('layouts.apli')

@section('title', 'Ajouter une categorie')

@section('content')
<div class="container">
    <h1 class="mb-4">Ajouter une categorie </h1>

    <form method="POST" action="{{ route('categories.store') }}">
        @csrf
        @if(isset($category)) @method('PUT') @endif

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="name" class="form-label">Nom *</label>
                    <input type="text" class="form-control" id="name" name="name" 
                           value="{{ old('name', $category->name ?? '') }}" required>
                </div>

                <div class="mb-3">
                    <label for="parent_id" class="form-label">Catégorie parente</label>
                    <select class="form-select" id="parent_id" name="parent_id">
                        <option value="">Aucune</option>
                        @foreach($parentCategories as $parent)
                            <option value="{{ $parent->id }}"
                                {{ (isset($category) && $category->parent_id == $parent->id) ? 'selected' : '' }}>
                                {{ $parent->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" 
                              rows="3">{{ old('description', $category->description ?? '') }}</textarea>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('categories.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Enregistrer
            </button>
        </div>
    </form>
</div>
@endsection