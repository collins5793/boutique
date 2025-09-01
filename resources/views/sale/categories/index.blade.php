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
            overflow-x: hidden;
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

        .btn-sm {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
        }

        .btn-info { background-color: #17a2b8; color: white; }
        .btn-warning { background-color: #ffc107; color: black; }
        .btn-danger { background-color: #dc3545; color: white; }
        .btn-secondary { background-color: #6c757d; color: white; }

        /* Cartes de statistiques */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: var(--light);
            border-radius: var(--radius);
            padding: 1.5rem;
            box-shadow: var(--card-shadow);
            transition: var(--transition);
            animation: fadeIn 0.6s ease-out;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 5px;
            height: 100%;
            background: linear-gradient(to bottom, var(--primary), var(--primary-light));
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .stat-card .card-title {
            font-size: 0.9rem;
            color: var(--dark-light);
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .stat-card .card-text {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--dark);
        }

        .stat-card.bg-primary { background: linear-gradient(135deg, #007bff, #0056b3); color: white; }
        .stat-card.bg-success { background: linear-gradient(135deg, #28a745, #1e7e34); color: white; }
        .stat-card.bg-warning { background: linear-gradient(135deg, #ffc107, #e0a800); color: black; }
        .stat-card.bg-info { background: linear-gradient(135deg, #17a2b8, #138496); color: white; }

        /* Tableau */
        .table-container {
            background: var(--light);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            overflow: hidden;
            animation: fadeIn 0.7s ease-out;
        }

        .table-responsive {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        thead {
            background: linear-gradient(to right, var(--primary), var(--primary-light));
            color: white;
        }

        th {
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            position: relative;
        }

        th::after {
            content: '';
            position: absolute;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
            height: 60%;
            width: 1px;
            background: rgba(255, 255, 255, 0.2);
        }

        th:last-child::after {
            display: none;
        }

        tbody tr {
            transition: var(--transition);
            background: var(--light);
        }

        tbody tr:nth-child(even) {
            background: #f8f9fa;
        }

        tbody tr:hover {
            background: rgba(235, 119, 10, 0.05);
            transform: scale(1.01);
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
        }

        td {
            padding: 1rem;
            border-bottom: 1px solid #e9ecef;
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

        /* Alert */
        .alert {
            padding: 1rem 1.5rem;
            border-radius: var(--radius);
            margin-bottom: 1.5rem;
            animation: slideInLeft 0.5s ease-out;
        }

        .alert-info {
            background-color: #d1ecf1;
            border-color: #bee5eb;
            color: #0c5460;
        }

        /* Actions */
        .actions-container {
            display: flex;
            gap: 0.5rem;
        }

        .actions-container .btn {
            opacity: 0.85;
            transition: var(--transition);
        }

        .actions-container .btn:hover {
            opacity: 1;
            transform: scale(1.1);
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
        @media (max-width: 992px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .page-header {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }
            
            .actions-container {
                flex-wrap: wrap;
                justify-content: center;
            }
        }
    </style>

    <div class="container">
        {{-- En-tête avec le titre et bouton Ajouter --}}
        <div class="page-header">
            <h1>Liste des Catégories</h1>
            <a href="{{ route('categories.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Ajouter
            </a>
        </div>

        {{-- Statistiques globales --}}
        <div class="stats-grid">
            <div class="stat-card bg-primary">
                <h5 class="card-title">Total Catégories</h5>
                <p class="card-text">{{ $categories->total() }}</p>
            </div>
            <div class="stat-card bg-success">
                <h5 class="card-title">Total Produits</h5>
                <p class="card-text">{{ $totalProducts }}</p>
            </div>
            <div class="stat-card bg-warning">
                <h5 class="card-title">Produits Actifs</h5>
                <p class="card-text">{{ $totalActiveProducts }}</p>
            </div>
            <div class="stat-card bg-info">
                <h5 class="card-title">Revenu Total</h5>
                <p class="card-text">{{ number_format($totalRevenue, 2, ',', ' ') }} FCFA</p>
            </div>
        </div>

        {{-- Vérifier si la liste est vide --}}
        @if($categories->isEmpty())
            <div class="alert alert-info">Aucune catégorie trouvée</div>
        @else
            <div class="table-container">
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nom</th>
                                <th>Slug</th>
                                <th>Parent</th>
                                <th>Nb Produits</th>
                                <th>Stock Total</th>
                                <th>Chiffre d'Affaires</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categories as $category)
                                <tr>
                                    <td>{{ $category->id }}</td>
                                    <td>{{ $category->name }}</td>
                                    <td>{{ $category->slug }}</td>
                                    <td>{{ $category->parent->name ?? '-' }}</td>
                                    <td>{{ $category->products_count }}</td>
                                    <td>{{ $category->total_stock }}</td>
                                    <td>{{ number_format($category->total_revenue, 2, ',', ' ') }} FCFA</td>
                                    <td>
                                        <div class="actions-container">
                                            <a href="{{ route('categories.show', $category) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('categories.edit', $category) }}" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('categories.destroy', $category) }}" method="POST">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Confirmer la suppression ?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                            <a href="{{ route('categories.products', $category) }}" class="btn btn-sm btn-secondary">
                                                <i class="fas fa-boxes"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="pagination">
                {{ $categories->links() }}
            </div>
        @endif
    </div>

    <script>
        // Animation pour les lignes du tableau
        document.addEventListener('DOMContentLoaded', function() {
            const tableRows = document.querySelectorAll('tbody tr');
            
            tableRows.forEach((row, index) => {
                // Délai progressif pour l'animation
                row.style.animationDelay = `${index * 0.05}s`;
                row.style.animation = 'fadeIn 0.5s ease-out forwards';
                row.style.opacity = '0';
            });
            
            // Animation des cartes de statistiques avec un délai
            const statCards = document.querySelectorAll('.stat-card');
            statCards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.15}s`;
            });
            
            // Effet de survol amélioré pour les boutons d'action
            const actionButtons = document.querySelectorAll('.actions-container .btn');
            actionButtons.forEach(button => {
                button.addEventListener('mouseenter', function() {
                    this.style.transform = 'scale(1.15)';
                    this.style.zIndex = '10';
                });
                
                button.addEventListener('mouseleave', function() {
                    this.style.transform = 'scale(1)';
                    this.style.zIndex = '1';
                });
            });
        });
    </script>


@endsection