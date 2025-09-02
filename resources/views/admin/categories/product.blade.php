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

        /* En-tête */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding: 1.5rem 2rem;
            background: var(--light);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            animation: slideDown 0.5s ease-out;
        }

        .page-header h1 {
            font-size: 1.8rem;
            color: var(--dark);
            font-weight: 700;
            margin: 0;
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

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            box-shadow: 0 4px 10px rgba(235, 119, 10, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(235, 119, 10, 0.4);
        }

        .btn-outline-primary {
            background: transparent;
            color: var(--primary);
            border: 2px solid var(--primary);
        }

        .btn-outline-primary:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-2px);
        }

        .btn-sm {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
        }

        /* Grille de produits */
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        /* Cartes de produits */
        .product-card {
            background: var(--light);
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: var(--card-shadow);
            transition: var(--transition);
            animation: fadeIn 0.6s ease-out;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.15);
        }

        .product-image {
            height: 200px;
            overflow: hidden;
            position: relative;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: var(--transition);
        }

        .product-card:hover .product-image img {
            transform: scale(1.05);
        }

        .product-image::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 30%;
            background: linear-gradient(to top, rgba(0,0,0,0.5), transparent);
            opacity: 0;
            transition: var(--transition);
        }

        .product-card:hover .product-image::after {
            opacity: 1;
        }

        .product-body {
            padding: 1.5rem;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .product-title {
            font-size: 1.2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: var(--dark);
        }

        .product-description {
            color: var(--dark-light);
            margin-bottom: 1rem;
            flex-grow: 1;
        }

        .product-price {
            font-size: 1.3rem;
            font-weight: 800;
            color: var(--primary);
            margin-bottom: 1rem;
        }

        .product-footer {
            padding: 1rem 1.5rem;
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
            display: flex;
            justify-content: center;
        }

        /* Alert */
        .alert {
            padding: 1.5rem;
            border-radius: var(--radius);
            margin-bottom: 1.5rem;
            animation: slideInLeft 0.5s ease-out;
            background: #d1ecf1;
            border-left: 4px solid #0c5460;
            color: #0c5460;
        }

        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 2rem;
            animation: fadeIn 0.8s ease-out;
        }

        .pagination .page-item {
            margin: 0 0.25rem;
        }

        .pagination .page-link {
            padding: 0.5rem 1rem;
            border-radius: var(--radius);
            border: 1px solid #dee2e6;
            color: var(--primary);
            text-decoration: none;
            transition: var(--transition);
        }

        .pagination .page-link:hover {
            background-color: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        .pagination .active .page-link {
            background-color: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        /* Badge de catégorie */
        .category-badge {
            position: absolute;
            top: 1rem;
            left: 1rem;
            background: var(--primary);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            z-index: 10;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
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

        @keyframes slideInLeft {
            from { opacity: 0; transform: translateX(-30px); }
            to { opacity: 1; transform: translateX(0); }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }
            
            .products-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="container">
        <div class="page-header">
            <h1>Produits dans {{ $category->name }}</h1>
            <a href="{{ route('products.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Ajouter produit
            </a>
        </div>

        @if($products->isEmpty())
            <div class="alert">
                <i class="fas fa-info-circle"></i> Aucun produit dans cette catégorie
            </div>
        @else
            <div class="products-grid">
                @foreach($products as $product)
                <div class="product-card">
                    @if($product->image)
                    <div class="product-image">
                        <span class="category-badge">{{ $category->name }}</span>
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                    </div>
                    @else
                    <div class="product-image" style="background: linear-gradient(135deg, #e2e8f0, #cbd5e0); display: flex; align-items: center; justify-content: center;">
                        <span class="category-badge">{{ $category->name }}</span>
                        <i class="fas fa-image" style="font-size: 3rem; color: #94a3b8;"></i>
                    </div>
                    @endif
                    
                    <div class="product-body">
                        <h3 class="product-title">{{ $product->name }}</h3>
                        <p class="product-description">{{ Str::limit($product->description, 100) }}</p>
                        <div class="product-price">{{ number_format($product->finalPrice, 2) }} €</div>
                    </div>
                    
                    <div class="product-footer">
                        <a href="{{ route('admin.categories.showproduct', $product) }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-eye"></i> Voir détails
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
            
            <div class="pagination">
                {{ $products->links() }}
            </div>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Animation d'apparition séquentielle des produits
            const productCards = document.querySelectorAll('.product-card');
            
            productCards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
            });
            
            // Effet de survol amélioré pour les boutons
            const buttons = document.querySelectorAll('.btn');
            buttons.forEach(button => {
                button.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-2px)';
                });
                
                button.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });
            
            // Animation pour la pagination
            const paginationLinks = document.querySelectorAll('.pagination .page-link');
            paginationLinks.forEach(link => {
                link.addEventListener('mouseenter', function() {
                    this.style.transform = 'scale(1.1)';
                });
                
                link.addEventListener('mouseleave', function() {
                    this.style.transform = 'scale(1)';
                });
            });
        });
    </script>

@endsection