@extends('layouts.sales.sale')

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
            line-height: 1.6;
            overflow-x: hidden;
        }

        /* Header */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem 2rem;
            background: var(--light);
            box-shadow: var(--shadow);
            border-radius: 0 0 var(--radius) var(--radius);
            margin-bottom: 2rem;
            animation: slideDown 0.5s ease-out;
        }

        .page-header h1 {
            color: var(--primary);
            font-size: 1.8rem;
            font-weight: 700;
        }

        /* Cards */
        .card {
            background: var(--light);
            border-radius: var(--radius);
            box-shadow: var(--card-shadow);
            transition: var(--transition);
            overflow: hidden;
            margin-bottom: 1.5rem;
            border: none;
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 0.6s forwards;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
        }

        .card-header {
            background: linear-gradient(120deg, var(--primary-light), var(--primary));
            color: var(--light);
            padding: 1rem 1.5rem;
            font-weight: 600;
            border-bottom: none;
        }

        .card-body {
            padding: 1.5rem;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            text-align: center;
            padding: 1.5rem;
            border-radius: var(--radius);
            color: white;
            position: relative;
            overflow: hidden;
            transition: var(--transition);
            opacity: 0;
            transform: scale(0.9);
            animation: scaleIn 0.5s forwards;
        }

        .stat-card:nth-child(1) { animation-delay: 0.1s; background: linear-gradient(135deg, var(--secondary), #0056b3); }
        .stat-card:nth-child(2) { animation-delay: 0.2s; background: linear-gradient(135deg, #28a745, #1e7e34); }
        .stat-card:nth-child(3) { animation-delay: 0.3s; background: linear-gradient(135deg, #dc3545, #bd2130); }

        .stat-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: rgba(255, 255, 255, 0.1);
            transform: rotate(45deg);
            transition: var(--transition);
            opacity: 0;
        }

        .stat-card:hover::before {
            opacity: 1;
            animation: shine 1.5s infinite;
        }

        .stat-card h5 {
            font-size: 1rem;
            margin-bottom: 0.5rem;
            opacity: 0.9;
        }

        .stat-card p {
            font-size: 1.8rem;
            font-weight: 700;
            margin: 0;
        }

        /* Top Products */
        .top-products {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .top-product-card {
            opacity: 0;
            transform: translateX(-20px);
            animation: slideInRight 0.5s forwards;
        }

        .top-product-card:nth-child(1) { animation-delay: 0.2s; }
        .top-product-card:nth-child(2) { animation-delay: 0.3s; }
        .top-product-card:nth-child(3) { animation-delay: 0.4s; }

        /* Table */
        .table-container {
            background: var(--light);
            border-radius: var(--radius);
            box-shadow: var(--card-shadow);
            overflow: hidden;
            margin-bottom: 2rem;
            opacity: 0;
            animation: fadeIn 0.8s forwards;
            animation-delay: 0.3s;
        }

        .table-header {
            background: linear-gradient(120deg, var(--primary-light), var(--primary));
            color: var(--light);
            padding: 1rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .table-responsive {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #e9ecef;
        }

        th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: var(--dark);
            position: sticky;
            top: 0;
        }

        tr {
            transition: var(--transition);
        }

        tr:hover {
            background-color: #f8f9fa;
            transform: scale(1.01);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.5rem 1rem;
            border-radius: var(--radius);
            font-weight: 500;
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

        .btn-sm {
            padding: 0.35rem 0.75rem;
            font-size: 0.875rem;
        }

        .btn-info {
            background: linear-gradient(135deg, #17a2b8, #138496);
            color: white;
        }

        .btn-warning {
            background: linear-gradient(135deg, #ffc107, #e0a800);
            color: #212529;
        }

        .btn-danger {
            background: linear-gradient(135deg, #dc3545, #c82333);
            color: white;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }

        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            padding: 1.5rem;
            list-style: none;
        }

        .pagination li {
            margin: 0 0.25rem;
        }

        .pagination a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--light);
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            box-shadow: var(--card-shadow);
            transition: var(--transition);
        }

        .pagination a:hover, .pagination .active a {
            background: var(--primary);
            color: var(--light);
            transform: translateY(-2px);
        }

        /* Animations */
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

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes scaleIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }
            to {
                opacity: 1;
                transform: scale(1);
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

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes shine {
            0% { transform: translateX(-100%) rotate(45deg); }
            100% { transform: translateX(100%) rotate(45deg); }
        }

        /* Responsive */
        @media (max-width: 992px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .top-products {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 576px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .page-header {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }
            
            .action-buttons {
                flex-wrap: wrap;
                justify-content: center;
            }
        }
    </style>

    <div class="container">
        <!-- Header -->
        <div class="page-header">
            <h1>Liste des Produits</h1>
            <a href="{{ route('products.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Ajouter
            </a>
        </div>

        <!-- Statistics Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <h5>Total Produits</h5>
                <p>{{ $stats['total'] }}</p>
            </div>
            <div class="stat-card">
                <h5>En Stock</h5>
                <p>{{ $stats['in_stock'] }}</p>
            </div>
            <div class="stat-card">
                <h5>Rupture de Stock</h5>
                <p>{{ $stats['out_of_stock'] }}</p>
            </div>
        </div>

        <!-- Top Products -->
        <div class="top-products">
            <div class="card top-product-card">
                <div class="card-header">Produit le plus vendu globalement</div>
                <div class="card-body">
                    @if($stats['top_product_overall'])
                        <p class="product-name">{{ $stats['top_product_overall']->name }}</p>
                        <p class="sales-count">Vendus : {{ $stats['top_product_overall']->total_sold }}</p>
                    @else
                        <p>Aucun produit vendu</p>
                    @endif
                </div>
            </div>
            
            <div class="card top-product-card">
                <div class="card-header">Produit le plus vendu sur commandes</div>
                <div class="card-body">
                    @if($stats['top_product_order'])
                        <p class="product-name">{{ $stats['top_product_order']->name }}</p>
                        <p class="sales-count">Vendus : {{ $stats['top_product_order']->order_items_sum_quantity ?? 0 }}</p>
                    @else
                        <p>Aucun produit vendu</p>
                    @endif
                </div>
            </div>
            
            <div class="card top-product-card">
                <div class="card-header">Produit le plus vendu directement</div>
                <div class="card-body">
                    @if($stats['top_product_direct'])
                        <p class="product-name">{{ $stats['top_product_direct']->name }}</p>
                        <p class="sales-count">Vendus : {{ $stats['top_product_direct']->direct_sale_items_sum_quantity ?? 0 }}</p>
                    @else
                        <p>Aucun produit vendu</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Products Table -->
        <div class="table-container">
            <div class="table-header">
                <h3>Liste des produits</h3>
            </div>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Catégorie</th>
                            <th>Prix</th>
                            <th>Prix Réduit</th>
                            <th>Stock</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                            <tr>
                                <td>{{ $product->id }}</td>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->category->name ?? '-' }}</td>
                                <td>{{ number_format($product->price, 2, ',', ' ') }} FCFA</td>
                                <td>{{ $product->discount_price ? number_format($product->discount_price, 2, ',', ' ') : '-' }} FCFA</td>
                                <td>{{ $product->stock_quantity }}</td>
                                <td>
                                    <span class="status-badge status-{{ $product->status }}">
                                        {{ ucfirst($product->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
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
        </div>

        <!-- Pagination -->
        <div class="pagination-container">
            {{ $products->links() }}
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Animation for table rows
            const tableRows = document.querySelectorAll('tbody tr');
            tableRows.forEach((row, index) => {
                row.style.opacity = '0';
                row.style.transform = 'translateX(-20px)';
                row.style.transition = 'all 0.5s ease';
                
                setTimeout(() => {
                    row.style.opacity = '1';
                    row.style.transform = 'translateX(0)';
                }, 100 + (index * 50));
            });

            // Add hover effect to cards
            const cards = document.querySelectorAll('.card');
            cards.forEach(card => {
                card.addEventListener('mouseenter', () => {
                    card.style.transform = 'translateY(-5px)';
                });
                
                card.addEventListener('mouseleave', () => {
                    card.style.transform = 'translateY(0)';
                });
            });

            // Add animation to pagination
            const paginationItems = document.querySelectorAll('.pagination li');
            paginationItems.forEach((item, index) => {
                item.style.opacity = '0';
                item.style.transform = 'translateY(10px)';
                item.style.transition = 'all 0.3s ease';
                
                setTimeout(() => {
                    item.style.opacity = '1';
                    item.style.transform = 'translateY(0)';
                }, 500 + (index * 100));
            });
        });
    </script>

@endsection