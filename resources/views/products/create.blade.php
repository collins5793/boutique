@extends('layouts.apli')


@section('title', 'Ajouter un produit')

@section('content')
<div class="container">
    <h1 class="mb-4">Ajouter un produit</h1>

    <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
        @csrf
        @if(isset($product)) @method('PUT') @endif

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="name" class="form-label">Nom *</label>
                    <input type="text" class="form-control" id="name" name="name" 
                           value="{{ old('name', $product->name ?? '') }}" required>
                </div>

                <div class="mb-3">
                    <label for="category_id" class="form-label">Catégorie *</label>
                    <select class="form-select" id="category_id" name="category_id" required>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ (isset($product) && $product->category_id == $category->id) ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $product->description ?? '') }}</textarea>
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label for="price" class="form-label">Prix *</label>
                    <input type="number" step="0.01" min="0" class="form-control" id="price" name="price" 
                           value="{{ old('price', $product->price ?? '') }}" required>
                </div>

                <div class="mb-3">
                    <label for="discount_price" class="form-label">Prix promotionnel</label>
                    <input type="number" step="0.01" min="0" class="form-control" id="discount_price" name="discount_price" 
                           value="{{ old('discount_price', $product->discount_price ?? '') }}">
                </div>

                <div class="mb-3">
                    <label for="stock_quantity" class="form-label">Quantité en stock *</label>
                    <input type="number" min="0" class="form-control" id="stock_quantity" name="stock_quantity" 
                           value="{{ old('stock_quantity', $product->stock_quantity ?? 0) }}" required>
                </div>
            </div>
        </div>

        <div class="row">

            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Statut *</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="status" id="status_active" value="active"
                            {{ (isset($product) && $product->status == 'active') ? 'checked' : (old('status', 'active') == 'active' ? 'checked' : '') }}>
                        <label class="form-check-label" for="status_active">Actif</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="status" id="status_inactive" value="inactive"
                            {{ (isset($product) && $product->status == 'inactive') ? 'checked' : (old('status') == 'inactive' ? 'checked' : '') }}>
                        <label class="form-check-label" for="status_inactive">Inactif</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="image" class="form-label">Image principale</label>
                    <input type="file" class="form-control" id="image" name="image">
                    @if(isset($product) && $product->image)
                        <div class="mt-2">
                            <img src="{{ asset('storage/' . $product->image) }}" width="100" class="img-thumbnail">
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox" id="remove_image" name="remove_image">
                                <label class="form-check-label" for="remove_image">Supprimer l'image</label>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label for="gallery" class="form-label">Galerie d'images</label>
                    <input type="file" class="form-control" id="gallery" name="gallery[]" multiple>
                    @if(isset($product) && $product->gallery)
                        <div class="row mt-2">
                            @foreach(json_decode($product->gallery) as $image)
                                <div class="col-3 position-relative">
                                    <img src="{{ asset('storage/' . $image) }}" class="img-thumbnail w-100">
                                    <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0" 
                                            onclick="removeGalleryImage(this, '{{ $image }}')">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                        <input type="hidden" id="removed_gallery_images" name="removed_gallery_images">
                    @endif
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('products.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Enregistrer
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
function removeGalleryImage(button, imagePath) {
    // Ajouter l'image à supprimer dans le champ caché
    const hiddenField = document.getElementById('removed_gallery_images');
    let removedImages = hiddenField.value ? hiddenField.value.split(',') : [];
    removedImages.push(imagePath);
    hiddenField.value = removedImages.join(',');

    // Supprimer l'élément visuel
    button.closest('.col-3').remove();
}
</script>
@endpush
@endsection