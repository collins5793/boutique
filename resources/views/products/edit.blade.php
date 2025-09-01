@extends('layouts.apli')

@section('title', 'Modifier le produit')

@section('content')
<div class="container">
    <h1 class="mb-4">Modifier le produit : {{ $product->name }}</h1>

    <form method="POST" action="{{ route('products.update', $product) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-6">
                <!-- Nom -->
                <div class="mb-3">
                    <label for="name" class="form-label">Nom *</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                           id="name" name="name" value="{{ old('name', $product->name) }}" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <!-- Catégorie -->
                <div class="mb-3">
                    <label for="category_id" class="form-label">Catégorie *</label>
                    <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <!-- Description -->
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" 
                              id="description" name="description" rows="3">{{ old('description', $product->description) }}</textarea>
                    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="col-md-6">
                <!-- Prix -->
                <div class="mb-3">
                    <label for="price" class="form-label">Prix *</label>
                    <input type="number" step="0.01" min="0" 
                           class="form-control @error('price') is-invalid @enderror" 
                           id="price" name="price" value="{{ old('price', $product->price) }}" required>
                    @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <!-- Prix promotionnel -->
                <div class="mb-3">
                    <label for="discount_price" class="form-label">Prix promotionnel</label>
                    <input type="number" step="0.01" min="0" 
                           class="form-control @error('discount_price') is-invalid @enderror" 
                           id="discount_price" name="discount_price" value="{{ old('discount_price', $product->discount_price) }}">
                    @error('discount_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <!-- Stock -->
                <div class="mb-3">
                    <label for="stock_quantity" class="form-label">Quantité en stock *</label>
                    <input type="number" min="0" 
                           class="form-control @error('stock_quantity') is-invalid @enderror" 
                           id="stock_quantity" name="stock_quantity" value="{{ old('stock_quantity', $product->stock_quantity) }}" required>
                    @error('stock_quantity') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>

        <!-- Statut -->
        <div class="row">
            <div class="col-md-6">
                <label class="form-label">Statut *</label>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="status" id="status_active" value="active" 
                           {{ $product->status == 'active' ? 'checked' : (old('status', 'active') == 'active' ? 'checked' : '') }}>
                    <label class="form-check-label" for="status_active">Actif</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="status" id="status_inactive" value="inactive" 
                           {{ $product->status == 'inactive' ? 'checked' : (old('status') == 'inactive' ? 'checked' : '') }}>
                    <label class="form-check-label" for="status_inactive">Inactif</label>
                </div>
            </div>
        </div>

        <!-- Image principale -->
        <div class="row mt-3">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="image" class="form-label">Image principale</label>
                    <input type="file" class="form-control" id="image" name="image">
                    @if($product->image)
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

            <!-- Galerie -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="gallery" class="form-label">Galerie d'images</label>
                    <input type="file" class="form-control" id="gallery" name="gallery[]" multiple>
                    @if($product->gallery)
                        <div class="row mt-2">
                            @foreach(json_decode($product->gallery) as $image)
                                <div class="col-3 position-relative mb-2">
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

        <!-- Réductions dynamiques -->
        <div class="row mt-3">
            <div class="col-12">
                <h4>Réductions (optionnel)</h4>
                <button type="button" class="btn btn-sm btn-success mb-2" onclick="addDiscountRow()">Ajouter une réduction</button>
                <div id="discounts_container">
                    @if(!empty($discounts))
                        @foreach($discounts as $index => $d)
                            <div class="discount-row mb-2 row" data-index="{{ $index }}">
                                <div class="col-md-5">
                                    <input type="number" min="1" class="form-control" 
                                           name="discounts[{{ $index }}][min_quantity]" 
                                           placeholder="Quantité min" value="{{ $d->min_quantity }}">
                                </div>
                                <div class="col-md-5">
                                    <input type="number" min="0" step="0.01" class="form-control" 
                                           name="discounts[{{ $index }}][price]" 
                                           placeholder="Prix réduit" value="{{ $d->price }}">
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-danger" onclick="this.closest('.discount-row').remove()">Supprimer</button>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('products.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Retour</a>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Enregistrer</button>
        </div>
    </form>
</div>

@push('scripts')
<script>
function removeGalleryImage(button, imagePath) {
    const hiddenField = document.getElementById('removed_gallery_images');
    let removedImages = hiddenField.value ? hiddenField.value.split(',') : [];
    removedImages.push(imagePath);
    hiddenField.value = removedImages.join(',');
    button.closest('.col-3').remove();
}

let discountIndex = {{ count($discounts ?? []) }};

function addDiscountRow() {
    const container = document.getElementById('discounts_container');
    const row = document.createElement('div');
    row.classList.add('discount-row', 'mb-2', 'row');
    row.setAttribute('data-index', discountIndex);
    row.innerHTML = `
        <div class="col-md-5">
            <input type="number" min="1" class="form-control" name="discounts[${discountIndex}][min_quantity]" placeholder="Quantité min">
        </div>
        <div class="col-md-5">
            <input type="number" min="0" step="0.01" class="form-control" name="discounts[${discountIndex}][price]" placeholder="Prix réduit">
        </div>
        <div class="col-md-2">
            <button type="button" class="btn btn-danger" onclick="this.closest('.discount-row').remove()">Supprimer</button>
        </div>
    `;
    container.appendChild(row);
    discountIndex++;
}
</script>
@endpush

@endsection