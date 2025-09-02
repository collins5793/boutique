@extends('layouts.admins.admin')

@section('content')
<style>
        :root {
            --primary: #eb770a;
            --primary-light: #ec8a2f;
            --primary-dark: #be6109;
            --secondary: #007bff;
            --accent: #ff7b00;
            --dark: #1e293b;
            --dark-light: #334155;
            --light: #ffffff;
            --sidebar-width: 280px;
            --sidebar-collapsed: 90px;
            --header-height: 80px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --radius: 12px;
            --shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            --card-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            --success: #28a745;
            --danger: #dc3545;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f5f7fa;
            color: var(--dark);
            line-height: 1.6;
            padding: 20px;
        }

        .form-container {
            max-width: 1200px;
            margin: 0 auto;
            background: var(--light);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            overflow: hidden;
            animation: fadeIn 0.6s ease-out;
        }

        .form-header {
            background: linear-gradient(120deg, var(--primary-light), var(--primary));
            color: var(--light);
            padding: 1.5rem 2rem;
            position: relative;
            overflow: hidden;
        }

        .form-header h1 {
            font-size: 1.8rem;
            font-weight: 700;
            position: relative;
            z-index: 2;
        }

        .form-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 200%;
            background: rgba(255, 255, 255, 0.1);
            transform: rotate(30deg);
            transition: var(--transition);
        }

        .form-header:hover::before {
            animation: shine 3s infinite;
        }

        .form-content {
            padding: 2rem;
        }

        .form-section {
            margin-bottom: 2rem;
            padding: 1.5rem;
            background: #f9fafb;
            border-radius: var(--radius);
            box-shadow: var(--card-shadow);
            transition: var(--transition);
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 0.5s forwards;
        }

        .form-section:nth-child(1) { animation-delay: 0.1s; }
        .form-section:nth-child(2) { animation-delay: 0.2s; }
        .form-section:nth-child(3) { animation-delay: 0.3s; }
        .form-section:nth-child(4) { animation-delay: 0.4s; }

        .form-section:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .section-title {
            font-size: 1.2rem;
            color: var(--primary);
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--primary-light);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .required::after {
            content: '*';
            color: var(--danger);
            margin-left: 0.25rem;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #e2e8f0;
            border-radius: var(--radius);
            font-size: 1rem;
            transition: var(--transition);
            background: var(--light);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(235, 119, 10, 0.2);
        }

        .form-check {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
        }

        .form-check-input {
            width: 18px;
            height: 18px;
            accent-color: var(--primary);
        }

        .image-preview {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-top: 1rem;
        }

        .preview-item {
            position: relative;
            width: 120px;
            height: 120px;
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: var(--card-shadow);
            transition: var(--transition);
        }

        .preview-item:hover {
            transform: scale(1.05);
        }

        .preview-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .remove-btn {
            position: absolute;
            top: 5px;
            right: 5px;
            width: 25px;
            height: 25px;
            border-radius: 50%;
            background: var(--danger);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            border: none;
            transition: var(--transition);
        }

        .remove-btn:hover {
            transform: scale(1.1);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.75rem 1.5rem;
            border-radius: var(--radius);
            font-weight: 600;
            transition: var(--transition);
            border: none;
            cursor: pointer;
            text-decoration: none;
            gap: 0.5rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            box-shadow: 0 4px 10px rgba(235, 119, 10, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(235, 119, 10, 0.4);
        }

        .btn-secondary {
            background: linear-gradient(135deg, #6c757d, #5a6268);
            color: white;
        }

        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(108, 117, 125, 0.3);
        }

        .btn-success {
            background: linear-gradient(135deg, var(--success), #218838);
            color: white;
        }

        .btn-danger {
            background: linear-gradient(135deg, var(--danger), #c82333);
            color: white;
        }

        .form-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e2e8f0;
        }

        .discount-row {
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
            align-items: center;
            padding: 1rem;
            background: white;
            border-radius: var(--radius);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            transition: var(--transition);
            animation: slideInRight 0.3s forwards;
            opacity: 0;
        }

        .discount-row:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .discount-input {
            flex: 1;
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes shine {
            0% { transform: translateX(-100%) rotate(30deg); }
            100% { transform: translateX(200%) rotate(30deg); }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
            
            .form-actions {
                flex-direction: column;
                gap: 1rem;
            }
            
            .discount-row {
                flex-direction: column;
                align-items: stretch;
            }
        }
    </style>

    <div class="form-container">
        <div class="form-header">
            <h1><i class="fas {{ isset($product) ? 'fa-edit' : 'fa-plus' }}"></i> {{ isset($product) ? 'Modifier le produit' : 'Ajouter un produit' }}</h1>
        </div>

        <form method="POST" action="{{ isset($product) ? route('products.update', $product) : route('products.store') }}" enctype="multipart/form-data" class="form-content">
            @csrf
            @if(isset($product)) @method('PUT') @endif

            <!-- Informations de base -->
            <div class="form-section">
                <h2 class="section-title"><i class="fas fa-info-circle"></i> Informations de base</h2>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="name" class="form-label required"><i class="fas fa-tag"></i> Nom</label>
                        <input type="text" class="form-control" id="name" name="name" 
                               value="{{ old('name', $product->name ?? '') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="category_id" class="form-label required"><i class="fas fa-folder"></i> Catégorie</label>
                        <select class="form-control" id="category_id" name="category_id" required>
                            <option value="">Sélectionner une catégorie</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ (isset($product) && $product->category_id == $category->id) ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="description" class="form-label"><i class="fas fa-align-left"></i> Description</label>
                        <textarea class="form-control" id="description" name="description" rows="4">{{ old('description', $product->description ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Prix et stock -->
            <div class="form-section">
                <h2 class="section-title"><i class="fas fa-money-bill-wave"></i> Prix et stock</h2>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="price" class="form-label required"><i class="fas fa-dollar-sign"></i> Prix</label>
                        <input type="number" step="0.01" min="0" class="form-control" id="price" name="price" 
                               value="{{ old('price', $product->price ?? '') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="discount_price" class="form-label"><i class="fas fa-percent"></i> Prix promotionnel</label>
                        <input type="number" step="0.01" min="0" class="form-control" id="discount_price" name="discount_price" 
                               value="{{ old('discount_price', $product->discount_price ?? '') }}">
                    </div>

                    <div class="form-group">
                        <label for="stock_quantity" class="form-label required"><i class="fas fa-cubes"></i> Quantité en stock</label>
                        <input type="number" min="0" class="form-control" id="stock_quantity" name="stock_quantity" 
                               value="{{ old('stock_quantity', $product->stock_quantity ?? 0) }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label required"><i class="fas fa-toggle-on"></i> Statut</label>
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

            <!-- Images -->
            <div class="form-section">
                <h2 class="section-title"><i class="fas fa-images"></i> Images</h2>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="image" class="form-label"><i class="fas fa-image"></i> Image principale</label>
                        <input type="file" class="form-control" id="image" name="image" accept="image/*">
                        
                        @if(isset($product) && $product->image)
                            <div class="image-preview">
                                <div class="preview-item">
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="Image principale">
                                    <button type="button" class="remove-btn" onclick="document.getElementById('remove_image').click()">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox" id="remove_image" name="remove_image">
                                <label class="form-check-label" for="remove_image">Supprimer l'image</label>
                            </div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="gallery" class="form-label"><i class="fas fa-images"></i> Galerie d'images</label>
                        <input type="file" class="form-control" id="gallery" name="gallery[]" multiple accept="image/*">
                        
                        @if(isset($product) && $product->gallery)
                            <div class="image-preview" id="gallery-preview">
                                @foreach(json_decode($product->gallery) as $index => $image)
                                    <div class="preview-item">
                                        <img src="{{ asset('storage/' . $image) }}" alt="Image gallery {{ $index }}">
                                        <button type="button" class="remove-btn" onclick="removeGalleryImage(this, '{{ $image }}')">
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

            <!-- Réductions -->
            <div class="form-section">
                <h2 class="section-title"><i class="fas fa-tags"></i> Réductions de quantité</h2>
                <button type="button" class="btn btn-success mb-3" onclick="addDiscountRow()">
                    <i class="fas fa-plus"></i> Ajouter une réduction
                </button>
                
                <div id="discounts_container">
                    @if(!empty($discounts))
                        @foreach($discounts as $index => $discount)
                            <div class="discount-row" data-index="{{ $index }}" style="animation-delay: {{ $index * 0.1 }}s">
                                <div class="discount-input">
                                    <input type="number" min="1" class="form-control" 
                                        name="discounts[{{ $index }}][min_quantity]" 
                                        value="{{ $discount['min_quantity'] }}" 
                                        placeholder="Quantité min" required>
                                </div>
                                <div class="discount-input">
                                    <input type="number" min="0" step="0.01" class="form-control" 
                                        name="discounts[{{ $index }}][price]" 
                                        value="{{ $discount['price'] }}" 
                                        placeholder="Prix réduit" required>
                                </div>
                                <button type="button" class="btn btn-danger" onclick="this.closest('.discount-row').remove()">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

            <!-- Actions -->
            <div class="form-actions">
                <a href="{{ route('products.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Retour
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Enregistrer
                </button>
            </div>
        </form>
    </div>

    <script>
        // Index pour les nouvelles lignes de réduction
        let discountIndex = {{ !empty($discounts) ? count($discounts) : 0 }};
        
        // Fonction pour ajouter une ligne de réduction
        function addDiscountRow() {
            const container = document.getElementById('discounts_container');
            const row = document.createElement('div');
            row.classList.add('discount-row');
            row.setAttribute('data-index', discountIndex);
            row.style.opacity = '0';
            
            row.innerHTML = `
                <div class="discount-input">
                    <input type="number" min="1" class="form-control" 
                        name="discounts[${discountIndex}][min_quantity]" 
                        placeholder="Quantité min" required>
                </div>
                <div class="discount-input">
                    <input type="number" min="0" step="0.01" class="form-control" 
                        name="discounts[${discountIndex}][price]" 
                        placeholder="Prix réduit" required>
                </div>
                <button type="button" class="btn btn-danger" onclick="this.closest('.discount-row').remove()">
                    <i class="fas fa-trash"></i>
                </button>
            `;
            
            container.appendChild(row);
            
            // Animation d'entrée
            setTimeout(() => {
                row.style.opacity = '1';
                row.style.transform = 'translateX(0)';
            }, 10);
            
            discountIndex++;
        }
        
        // Fonction pour supprimer une image de la galerie
        function removeGalleryImage(button, imagePath) {
            const hiddenField = document.getElementById('removed_gallery_images');
            let removedImages = hiddenField.value ? hiddenField.value.split(',') : [];
            removedImages.push(imagePath);
            hiddenField.value = removedImages.join(',');
            
            // Animation de suppression
            const previewItem = button.closest('.preview-item');
            previewItem.style.transform = 'scale(0)';
            previewItem.style.opacity = '0';
            
            setTimeout(() => {
                previewItem.remove();
            }, 300);
        }
        
        // Prévisualisation des images uploadées
        document.getElementById('gallery')?.addEventListener('change', function(e) {
            const previewContainer = document.getElementById('gallery-preview') || document.createElement('div');
            if (!document.getElementById('gallery-preview')) {
                previewContainer.id = 'gallery-preview';
                previewContainer.className = 'image-preview';
                this.parentNode.appendChild(previewContainer);
            }
            
            for (const file of e.target.files) {
                if (!file.type.match('image.*')) continue;
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    const previewItem = document.createElement('div');
                    previewItem.className = 'preview-item';
                    previewItem.innerHTML = `
                        <img src="${e.target.result}" alt="Preview">
                        <button type="button" class="remove-btn" onclick="this.parentElement.remove()">
                            <i class="fas fa-times"></i>
                        </button>
                    `;
                    previewContainer.appendChild(previewItem);
                };
                reader.readAsDataURL(file);
            }
        });
        
        // Animation des éléments au chargement de la page
        document.addEventListener('DOMContentLoaded', function() {
            const discountRows = document.querySelectorAll('.discount-row');
            discountRows.forEach((row, index) => {
                row.style.animationDelay = `${index * 0.1}s`;
            });
        });
    </script>


@endsection