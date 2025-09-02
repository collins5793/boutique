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
            max-width: 1000px;
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

        /* Grille d'informations */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .info-section {
            animation: fadeIn 0.6s ease-out;
        }

        .info-item {
            margin-bottom: 1.2rem;
            padding: 1rem;
            background: #f8fafc;
            border-radius: var(--radius);
            border-left: 4px solid var(--primary);
            transition: var(--transition);
        }

        .info-item:hover {
            transform: translateX(5px);
            background: #f1f5f9;
        }

        .info-item strong {
            display: block;
            color: var(--dark-light);
            font-size: 0.9rem;
            margin-bottom: 0.3rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-item p {
            margin: 0;
            font-size: 1.1rem;
            color: var(--dark);
            font-weight: 500;
        }

        /* Séparateur */
        .divider {
            height: 2px;
            background: linear-gradient(to right, transparent, #e2e8f0, transparent);
            margin: 2rem 0;
            border: none;
        }

        /* Sous-catégories */
        .subcategories-section {
            animation: fadeIn 0.8s ease-out;
        }

        .section-title {
            font-size: 1.4rem;
            margin-bottom: 1.5rem;
            color: var(--dark);
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .section-title i {
            color: var(--primary);
        }

        .empty-state {
            text-align: center;
            padding: 2rem;
            color: var(--dark-light);
            background: #f8fafc;
            border-radius: var(--radius);
            border: 2px dashed #e2e8f0;
        }

        .empty-state i {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: #cbd5e1;
        }

        /* Liste des sous-catégories */
        .subcategories-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1rem;
        }

        .subcategory-item {
            background: var(--light);
            border-radius: var(--radius);
            padding: 1.2rem;
            box-shadow: var(--card-shadow);
            transition: var(--transition);
            border-left: 4px solid var(--primary-light);
            animation: fadeIn 0.6s ease-out;
        }

        .subcategory-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            border-left-color: var(--primary);
        }

        .subcategory-link {
            display: flex;
            align-items: center;
            justify-content: space-between;
            text-decoration: none;
            color: var(--dark);
            font-weight: 500;
            transition: var(--transition);
        }

        .subcategory-link:hover {
            color: var(--primary);
        }

        .subcategory-link i {
            color: var(--primary-light);
            transition: var(--transition);
        }

        .subcategory-link:hover i {
            transform: translateX(5px);
            color: var(--primary);
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

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .card-header {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }
            
            .info-grid {
                grid-template-columns: 1fr;
            }
            
            .subcategories-list {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="container">
        <div class="main-card">
            <div class="card-header">
                <h2>Détails de la catégorie</h2>
                <div>
                    <a href="{{ route('categories.edit', $category) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Modifier
                    </a>
                </div>
            </div>
            
            <div class="card-body">
                <div class="info-grid">
                    <div class="info-section">
                        <div class="info-item">
                            <strong>ID</strong>
                            <p>{{ $category->id }}</p>
                        </div>
                        
                        <div class="info-item">
                            <strong>Nom</strong>
                            <p>{{ $category->name }}</p>
                        </div>
                        
                        <div class="info-item">
                            <strong>Slug</strong>
                            <p>{{ $category->slug }}</p>
                        </div>
                    </div>
                    
                    <div class="info-section">
                        <div class="info-item">
                            <strong>Catégorie Parente</strong>
                            <p>{{ $category->parent->name ?? 'Aucune' }}</p>
                        </div>
                        
                        <div class="info-item">
                            <strong>Description</strong>
                            <p>{{ $category->description ?? 'Non renseignée' }}</p>
                        </div>
                    </div>
                </div>

                <hr class="divider">

                <div class="subcategories-section">
                    <h4 class="section-title">
                        <i class="fas fa-folder-tree"></i>
                        Sous-catégories
                    </h4>
                    
                    @if($category->children->isEmpty())
                        <div class="empty-state">
                            <i class="fas fa-inbox"></i>
                            <p>Aucune sous-catégorie</p>
                        </div>
                    @else
                        <div class="subcategories-list">
                            @foreach($category->children as $child)
                                <div class="subcategory-item">
                                    <a href="{{ route('categories.show', $child) }}" class="subcategory-link">
                                        <span>{{ $child->name }}</span>
                                        <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Animation séquentielle des éléments d'information
            const infoItems = document.querySelectorAll('.info-item');
            infoItems.forEach((item, index) => {
                item.style.animationDelay = `${index * 0.1}s`;
            });
            
            // Animation des sous-catégories
            const subcategoryItems = document.querySelectorAll('.subcategory-item');
            subcategoryItems.forEach((item, index) => {
                item.style.animationDelay = `${index * 0.1}s`;
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
            
            // Animation d'apparition progressive
            const animatedElements = document.querySelectorAll('.info-section, .subcategories-section');
            animatedElements.forEach((element, index) => {
                element.style.opacity = '0';
                element.style.transform = 'translateY(20px)';
                element.style.transition = 'all 0.6s ease-out';
                
                setTimeout(() => {
                    element.style.opacity = '1';
                    element.style.transform = 'translateY(0)';
                }, 300 + (index * 200));
            });
        });
    </script>

@endsection