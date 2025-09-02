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
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Carte principale */
        .main-card {
            background: var(--light);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            overflow: hidden;
            animation: slideDown 0.5s ease-out;
        }

        /* En-tête de carte */
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem 2rem;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
        }

        .card-header h2 {
            margin: 0;
            font-size: 1.8rem;
            font-weight: 700;
        }

        /* Corps de carte */
        .card-body {
            padding: 2rem;
        }

        /* Boutons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.75rem 1.5rem;
            border-radius: var(--radius);
            font-weight: 600;
            text-decoration: none;
            transition: var(--transition);
            border: none;
            cursor: pointer;
            gap: 0.5rem;
        }

        .btn-warning {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
            box-shadow: 0 4px 10px rgba(245, 158, 11, 0.3);
        }

        .btn-warning:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(245, 158, 11, 0.4);
        }

        .btn-secondary {
            background: linear-gradient(135deg, #6b7280, #4b5563);
            color: white;
            box-shadow: 0 4px 10px rgba(107, 114, 128, 0.3);
        }

        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(107, 114, 128, 0.4);
        }

        /* Layout principal */
        .product-layout {
            display: grid;
            grid-template-columns: 1fr 1.5fr;
            gap: 2rem;
        }

        @media (max-width: 992px) {
            .product-layout {
                grid-template-columns: 1fr;
            }
        }

        /* Section image */
        .image-section {
            animation: fadeIn 0.6s ease-out;
        }

        .main-image {
            width: 100%;
            border-radius: var(--radius);
            box-shadow: var(--card-shadow);
            margin-bottom: 1.5rem;
            overflow: hidden;
            position: relative;
        }

        .main-image img {
            width: 100%;
            height: 300px;
            object-fit: cover;
            transition: var(--transition);
        }

        .main-image:hover img {
            transform: scale(1.05);
        }

        .no-image {
            height: 300px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #e2e8f0, #cbd5e0);
            border-radius: var(--radius);
            color: #64748b;
        }

        .no-image i {
            font-size: 3rem;
        }

        /* Galerie */
        .gallery-section {
            margin-top: 1.5rem;
        }

        .gallery-title {
            font-size: 1.1rem;
            margin-bottom: 1rem;
            color: var(--dark);
            font-weight: 600;
        }

        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0.75rem;
        }

        .gallery-item {
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: var(--card-shadow);
            transition: var(--transition);
            aspect-ratio: 1/1;
        }

        .gallery-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Section informations */
        .info-section {
            animation: fadeIn 0.8s ease-out;
        }

        .product-title {
            font-size: 2rem;
            font-weight: 800;
            color: var(--dark);
            margin-bottom: 0.5rem;
        }

        .product-sku {
            color: var(--dark-light);
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
        }

        /* Prix */
        .price-section {
            margin-bottom: 2rem;
            padding: 1.5rem;
            background: #f8fafc;
            border-radius: var(--radius);
            border-left: 4px solid var(--primary);
        }

        .current-price {
            font-size: 2.2rem;
            font-weight: 800;
            color: var(--primary);
            margin-right: 1rem;
        }

        .original-price {
            font-size: 1.5rem;
            color: var(--dark-light);
            text-decoration: line-through;
        }

        .discount-badge {
            display: inline-block;
            padding: 0.35rem 0.75rem;
            background: #dc2626;
            color: white;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 700;
            margin-left: 1rem;
            animation: pulse 2s infinite;
        }

        /* Tableaux */
        .table-container {
            margin: 1.5rem 0;
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: var(--card-shadow);
        }

        .table-title {
            font-size: 1.2rem;
            margin: 1.5rem 0 1rem;
            color: var(--dark);
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .table-title i {
            color: var(--primary);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: var(--light);
        }

        th {
            background: linear-gradient(135deg, var(--primary-light), var(--primary));
            color: white;
            padding: 1rem;
            text-align: left;
            font-weight: 600;
        }

        td {
            padding: 1rem;
            border-bottom: 1px solid #e2e8f0;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tr:hover {
            background: #f1f5f9;
        }

        /* Badges */
        .badge {
            display: inline-block;
            padding: 0.35rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 700;
        }

        .bg-success {
            background: #10b981;
            color: white;
        }

        .bg-secondary {
            background: #6b7280;
            color: white;
        }

        .bg-danger {
            background: #ef4444;
            color: white;
        }

        /* Informations générales */
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            margin: 1.5rem 0;
        }

        @media (max-width: 576px) {
            .info-grid {
                grid-template-columns: 1fr;
            }
        }

        .info-item {
            padding: 1rem;
            background: #f8fafc;
            border-radius: var(--radius);
            border-left: 4px solid var(--primary-light);
        }

        .info-item strong {
            display: block;
            color: var(--dark-light);
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        /* Description */
        .description-section {
            margin: 1.5rem 0;
            padding: 1.5rem;
            background: #f8fafc;
            border-radius: var(--radius);
            border-left: 4px solid var(--primary);
        }

        .description-title {
            font-size: 1.2rem;
            margin-bottom: 1rem;
            color: var(--dark);
            font-weight: 600;
        }

        /* Dates */
        .dates-section {
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px dashed #e2e8f0;
            color: var(--dark-light);
            font-size: 0.9rem;
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .card-header {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }
            
            .gallery-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>

    <div class="container">
        <div class="main-card">
            <div class="card-header">
                <h2>Détails du produit</h2>
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
                <div class="product-layout">
                    <!-- Colonne image -->
                    <div class="image-section">
                        @if($product->image)
                            <div class="main-image">
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                            </div>
                        @else
                            <div class="no-image">
                                <i class="fas fa-image"></i>
                            </div>
                        @endif

                        @if($product->gallery)
                            <div class="gallery-section">
                                <h4 class="gallery-title">Galerie d'images</h4>
                                <div class="gallery-grid">
                                    @foreach(json_decode($product->gallery) as $image)
                                        <div class="gallery-item">
                                            <img src="{{ asset('storage/' . $image) }}" alt="Galerie produit">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Colonne informations -->
                    <div class="info-section">
                        <h1 class="product-title">{{ $product->name }}</h1>
                        <p class="product-sku">SKU: {{ $product->sku ?? 'Non défini' }}</p>

                        <!-- Prix -->
                        <div class="price-section">
                            @if($product->discount_price)
                                <div>
                                    <span class="current-price">{{ number_format($product->discount_price, 0, ',', ' ') }} FCFA</span>
                                    <span class="original-price">{{ number_format($product->price, 0, ',', ' ') }} FCFA</span>
                                    <span class="discount-badge">PROMO</span>
                                </div>
                            @else
                                <span class="current-price">{{ number_format($product->price, 0, ',', ' ') }} FCFA</span>
                            @endif
                        </div>

                        <!-- Réductions par quantité -->
                        @if($product->discounts && $product->discounts->count() > 0)
                            <h4 class="table-title">
                                <i class="fas fa-tags"></i>
                                Réductions par quantité
                            </h4>
                            <div class="table-container">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Quantité min.</th>
                                            <th>Prix unitaire</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($product->discounts as $discount)
                                            <tr>
                                                <td>{{ $discount->min_quantity }}</td>
                                                <td>{{ number_format($discount->price, 0, ',', ' ') }} FCFA</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif

                        <!-- Variantes -->
                        @if($product->variants && $product->variants->count() > 0)
                            <h4 class="table-title">
                                <i class="fas fa-layer-group"></i>
                                Variantes disponibles
                            </h4>
                            <div class="table-container">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Attribut</th>
                                            <th>Valeur</th>
                                            <th>Prix</th>
                                            <th>Stock</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($product->variants as $variant)
                                            <tr>
                                                <td>{{ $variant->attribute_name }}</td>
                                                <td>{{ $variant->attribute_value }}</td>
                                                <td>
                                                    @if($variant->price)
                                                        {{ number_format($variant->price, 0, ',', ' ') }} FCFA
                                                    @else
                                                        {{ number_format($product->price, 0, ',', ' ') }} FCFA
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="{{ $variant->stock_quantity <= 0 ? 'bg-danger badge' : '' }}">
                                                        {{ $variant->stock_quantity }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif

                        <!-- Informations générales -->
                        <div class="info-grid">
                            <div class="info-item">
                                <strong>Catégorie</strong>
                                <span>{{ $product->category->name ?? 'Non renseignée' }}</span>
                            </div>
                            
                            <div class="info-item">
                                <strong>Code-barres</strong>
                                <span>{{ $product->barcode ?? 'Non renseigné' }}</span>
                            </div>
                            
                            <div class="info-item">
                                <strong>Stock</strong>
                                <span class="{{ $product->stock_quantity <= 0 ? 'bg-danger badge' : '' }}">
                                    {{ $product->stock_quantity }}
                                </span>
                            </div>
                            
                            <div class="info-item">
                                <strong>Statut</strong>
                                <span class="badge {{ $product->status == 'active' ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $product->status == 'active' ? 'Actif' : 'Inactif' }}
                                </span>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="description-section">
                            <h4 class="description-title">Description</h4>
                            <p>{{ $product->description ?? 'Aucune description disponible.' }}</p>
                        </div>

                        <!-- Dates -->
                        <div class="dates-section">
                            <p>Créé le : {{ $product->created_at ? $product->created_at->format('d/m/Y H:i') : '-' }}</p>
                            <p>Dernière mise à jour : {{ $product->updated_at ? $product->updated_at->format('d/m/Y H:i') : '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Animation séquentielle des éléments
            const animatedSections = document.querySelectorAll('.image-section, .info-section, .table-container');
            animatedSections.forEach((section, index) => {
                section.style.animationDelay = `${index * 0.2}s`;
            });
            
            // Effet de survol pour les boutons
            const buttons = document.querySelectorAll('.btn');
            buttons.forEach(button => {
                button.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-2px)';
                });
                
                button.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });
            
            // Animation des images de galerie
            const galleryItems = document.querySelectorAll('.gallery-item');
            galleryItems.forEach((item, index) => {
                item.style.animationDelay = `${index * 0.1}s`;
            });
            
            // Animation des lignes des tableaux
            const tableRows = document.querySelectorAll('tbody tr');
            tableRows.forEach((row, index) => {
                row.style.opacity = '0';
                row.style.transform = 'translateX(-20px)';
                row.style.transition = 'all 0.5s ease-out';
                
                setTimeout(() => {
                    row.style.opacity = '1';
                    row.style.transform = 'translateX(0)';
                }, 300 + (index * 100));
            });
        });
    </script>

@endsection