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
            --warning: #ffc107;
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

        .product-container {
            max-width: 1200px;
            margin: 0 auto;
            animation: fadeIn 0.6s ease-out;
        }

        .product-card {
            background: var(--light);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            overflow: hidden;
            margin-bottom: 2rem;
        }

        .card-header {
            background: linear-gradient(120deg, var(--primary-light), var(--primary));
            color: var(--light);
            padding: 1.5rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        .card-header::before {
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

        .card-header:hover::before {
            animation: shine 3s infinite;
        }

        .card-title {
            font-size: 1.8rem;
            font-weight: 700;
            margin: 0;
            position: relative;
            z-index: 2;
        }

        .header-actions {
            display: flex;
            gap: 0.75rem;
            position: relative;
            z-index: 2;
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

        .btn-warning {
            background: linear-gradient(135deg, var(--warning), #e0a800);
            color: #212529;
            box-shadow: 0 4px 10px rgba(255, 193, 7, 0.3);
        }

        .btn-warning:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(255, 193, 7, 0.4);
        }

        .btn-secondary {
            background: linear-gradient(135deg, #6c757d, #5a6268);
            color: white;
        }

        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(108, 117, 125, 0.3);
        }

        .card-body {
            padding: 2rem;
        }

        .product-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
        }

        @media (max-width: 992px) {
            .product-grid {
                grid-template-columns: 1fr;
            }
        }

        .image-section {
            opacity: 0;
            animation: slideInLeft 0.6s forwards;
        }

        .main-image {
            width: 100%;
            height: 400px;
            object-fit: contain;
            border-radius: var(--radius);
            box-shadow: var(--card-shadow);
            margin-bottom: 1.5rem;
            transition: var(--transition);
            background: #f8f9fa;
            padding: 1rem;
        }

        .main-image:hover {
            transform: scale(1.02);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .no-image {
            width: 100%;
            height: 400px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8f9fa;
            border-radius: var(--radius);
            color: #6c757d;
            font-style: italic;
        }

        .gallery-section {
            margin-top: 1.5rem;
        }

        .gallery-title {
            font-size: 1.2rem;
            color: var(--primary);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
        }

        .gallery-item {
            width: 100%;
            height: 120px;
            object-fit: cover;
            border-radius: var(--radius);
            box-shadow: var(--card-shadow);
            transition: var(--transition);
            cursor: pointer;
        }

        .gallery-item:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
        }

        .info-section {
            opacity: 0;
            animation: slideInRight 0.6s forwards;
            animation-delay: 0.2s;
        }

        .product-title {
            font-size: 2rem;
            color: var(--dark);
            margin-bottom: 0.5rem;
        }

        .product-sku {
            color: #6c757d;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
        }

        .price-section {
            margin-bottom: 2rem;
            padding: 1.5rem;
            background: #f8f9fa;
            border-radius: var(--radius);
            box-shadow: var(--card-shadow);
        }

        .current-price {
            font-size: 2.2rem;
            color: var(--danger);
            font-weight: 700;
            margin-right: 0.75rem;
        }

        .original-price {
            font-size: 1.5rem;
            color: #6c757d;
            text-decoration: line-through;
            margin-right: 0.75rem;
        }

        .discount-badge {
            background: linear-gradient(135deg, var(--danger), #c82333);
            color: white;
            padding: 0.35rem 0.75rem;
            border-radius: var(--radius);
            font-weight: 600;
            font-size: 0.9rem;
        }

        .regular-price {
            font-size: 2.2rem;
            color: var(--dark);
            font-weight: 700;
        }

        .section-title {
            font-size: 1.3rem;
            color: var(--primary);
            margin: 2rem 0 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--primary-light);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .discounts-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1.5rem;
            box-shadow: var(--card-shadow);
            border-radius: var(--radius);
            overflow: hidden;
        }

        .discounts-table th {
            background: linear-gradient(135deg, var(--primary-light), var(--primary));
            color: white;
            padding: 1rem;
            text-align: left;
        }

        .discounts-table td {
            padding: 1rem;
            border-bottom: 1px solid #e9ecef;
        }

        .discounts-table tr:last-child td {
            border-bottom: none;
        }

        .discounts-table tr {
            transition: var(--transition);
        }

        .discounts-table tr:hover {
            background-color: #f8f9fa;
        }

        .variants-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1.5rem;
            box-shadow: var(--card-shadow);
            border-radius: var(--radius);
            overflow: hidden;
        }

        .variants-table th {
            background: linear-gradient(135deg, var(--primary-light), var(--primary));
            color: white;
            padding: 1rem;
            text-align: left;
        }

        .variants-table td {
            padding: 1rem;
            border-bottom: 1px solid #e9ecef;
        }

        .variants-table tr:last-child td {
            border-bottom: none;
        }

        .variants-table tr {
            transition: var(--transition);
        }

        .variants-table tr:hover {
            background-color: #f8f9fa;
        }

        .stock-low {
            color: var(--danger);
            font-weight: 600;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .info-item {
            padding: 1rem;
            background: #f8f9fa;
            border-radius: var(--radius);
            box-shadow: var(--card-shadow);
        }

        .info-label {
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .info-value {
            color: var(--dark-light);
        }

        .status-badge {
            display: inline-block;
            padding: 0.35rem 0.75rem;
            border-radius: var(--radius);
            font-weight: 600;
            font-size: 0.9rem;
        }

        .status-active {
            background: linear-gradient(135deg, var(--success), #218838);
            color: white;
        }

        .status-inactive {
            background: linear-gradient(135deg, #6c757d, #5a6268);
            color: white;
        }

        .description-box {
            padding: 1.5rem;
            background: #f8f9fa;
            border-radius: var(--radius);
            box-shadow: var(--card-shadow);
            margin-bottom: 1.5rem;
            line-height: 1.8;
        }

        .dates-section {
            display: flex;
            justify-content: space-between;
            color: #6c757d;
            font-size: 0.9rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e9ecef;
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(30px);
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
            .card-header {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }
            
            .header-actions {
                flex-wrap: wrap;
                justify-content: center;
            }
            
            .info-grid {
                grid-template-columns: 1fr;
            }
            
            .gallery-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .dates-section {
                flex-direction: column;
                gap: 0.5rem;
            }
        }
    </style>

    <div class="product-container">
        <div class="product-card">
            <div class="card-header">
                <h1 class="card-title"><i class="fas fa-cube"></i> Détails du produit</h1>
                <div class="header-actions">
                    <a href="{{ route('products.edit', $product) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Modifier
                    </a>
                    <a href="{{ route('products.index') }}" class="btn btn-secondary">
                        <i class="fas fa-list"></i> Retour à la liste
                    </a>
                </div>
            </div>

            <div class="card-body">
                <div class="product-grid">
                    <!-- Colonne image -->
                    <div class="image-section">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" class="main-image" alt="{{ $product->name }}">
                        @else
                            <div class="no-image">
                                <i class="fas fa-image fa-3x"></i>
                                <p class="mt-2">Aucune image principale</p>
                            </div>
                        @endif

                        @if($product->gallery)
                            <div class="gallery-section">
                                <h3 class="gallery-title"><i class="fas fa-images"></i> Galerie d'images</h3>
                                <div class="gallery-grid">
                                    @foreach(json_decode($product->gallery) as $image)
                                        <img src="{{ asset('storage/' . $image) }}" class="gallery-item" alt="Image gallery">
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Colonne informations -->
                    <div class="info-section">
                        <h2 class="product-title">{{ $product->name }}</h2>
                        <p class="product-sku"><i class="fas fa-barcode"></i> SKU: {{ $product->sku ?? 'Non défini' }}</p>

                        <!-- Prix -->
                        <div class="price-section">
                            @if($product->discount_price)
                                <div>
                                    <span class="current-price">{{ number_format($product->discount_price, 0, ',', ' ') }} FCFA</span>
                                    <span class="original-price">{{ number_format($product->price, 0, ',', ' ') }} FCFA</span>
                                    <span class="discount-badge">PROMO</span>
                                </div>
                            @else
                                <span class="regular-price">{{ number_format($product->price, 0, ',', ' ') }} FCFA</span>
                            @endif
                        </div>

                        <!-- Réductions par quantité -->
                        @if($product->discounts && $product->discounts->count() > 0)
                            <h3 class="section-title"><i class="fas fa-tags"></i> Réductions par quantité</h3>
                            <table class="discounts-table">
                                <thead>
                                    <tr>
                                        <th>Quantité minimale</th>
                                        <th>Prix unitaire</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($product->discounts as $discount)
                                        <tr>
                                            <td>{{ $discount->min_quantity }} unités</td>
                                            <td>{{ number_format($discount->price, 0, ',', ' ') }} FCFA</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif

                        <!-- Variantes -->
                        @if($product->variants && $product->variants->count() > 0)
                            <h3 class="section-title"><i class="fas fa-layer-group"></i> Variantes disponibles</h3>
                            <table class="variants-table">
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
                                            <td class="{{ $variant->stock_quantity <= 0 ? 'stock-low' : '' }}">
                                                {{ $variant->stock_quantity }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif

                        <!-- Informations générales -->
                        <h3 class="section-title"><i class="fas fa-info-circle"></i> Informations générales</h3>
                        <div class="info-grid">
                            <div class="info-item">
                                <div class="info-label"><i class="fas fa-folder"></i> Catégorie</div>
                                <div class="info-value">{{ $product->category->name ?? 'Non renseignée' }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label"><i class="fas fa-barcode"></i> Code-barres</div>
                                <div class="info-value">{{ $product->barcode ?? 'Non renseigné' }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label"><i class="fas fa-cubes"></i> Stock</div>
                                <div class="info-value {{ $product->stock_quantity <= 0 ? 'stock-low' : '' }}">
                                    {{ $product->stock_quantity }}
                                </div>
                            </div>
                            <div class="info-item">
                                <div class="info-label"><i class="fas fa-toggle-on"></i> Statut</div>
                                <div class="info-value">
                                    <span class="status-badge status-{{ $product->status }}">
                                        {{ $product->status == 'active' ? 'Actif' : 'Inactif' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <h3 class="section-title"><i class="fas fa-align-left"></i> Description</h3>
                        <div class="description-box">
                            {{ $product->description ?? 'Aucune description disponible.' }}
                        </div>

                        <!-- Dates -->
                        <div class="dates-section">
                            <div><i class="fas fa-calendar-plus"></i> Créé le : {{ $product->created_at ? $product->created_at->format('d/m/Y H:i') : '-' }}</div>
                            <div><i class="fas fa-calendar-check"></i> Modifié le : {{ $product->updated_at ? $product->updated_at->format('d/m/Y H:i') : '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Animation des lignes des tableaux
            const tableRows = document.querySelectorAll('tbody tr');
            tableRows.forEach((row, index) => {
                row.style.opacity = '0';
                row.style.transform = 'translateX(-20px)';
                row.style.transition = 'all 0.5s ease';
                
                setTimeout(() => {
                    row.style.opacity = '1';
                    row.style.transform = 'translateX(0)';
                }, 300 + (index * 100));
            });

            // Effet de zoom sur les images au survol
            const images = document.querySelectorAll('.gallery-item');
            images.forEach(img => {
                img.addEventListener('mouseenter', () => {
                    img.style.transform = 'scale(1.05)';
                });
                
                img.addEventListener('mouseleave', () => {
                    img.style.transform = 'scale(1)';
                });
            });
        });
    </script>

@endsection