@extends('layouts.sales.sale')

@section('content')
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une Catégorie</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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

        /* En-tête */
        .page-header {
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

        /* Formulaire */
        .form-container {
            background: var(--light);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            padding: 2rem;
            animation: fadeIn 0.6s ease-out;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
        }

        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--dark);
        }

        .form-control {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 2px solid #e2e8f0;
            border-radius: var(--radius);
            font-size: 1rem;
            transition: var(--transition);
            background-color: #f8fafc;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(235, 119, 10, 0.2);
            background-color: var(--light);
        }

        .form-select {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 2px solid #e2e8f0;
            border-radius: var(--radius);
            font-size: 1rem;
            transition: var(--transition);
            background-color: #f8fafc;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%234b5563' viewBox='0 0 16 16'%3E%3Cpath d='M8 12L2 6h12L8 12z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 16px;
        }

        .form-select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(235, 119, 10, 0.2);
            background-color: var(--light);
        }

        textarea.form-control {
            min-height: 120px;
            resize: vertical;
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

        .btn-secondary {
            background: linear-gradient(135deg, #6b7280, #4b5563);
            color: white;
            box-shadow: 0 4px 10px rgba(107, 114, 128, 0.3);
        }

        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(107, 114, 128, 0.4);
        }

        .form-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e2e8f0;
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

        /* Indicateur de champ obligatoire */
        .required::after {
            content: '*';
            color: #e53e3e;
            margin-left: 0.25rem;
        }

        /* Effet de focus pour les labels */
        .form-group:focus-within .form-label {
            color: var(--primary);
        }

        /* Message de validation */
        .form-control:valid {
            border-color: #38a169;
        }

        .form-control:invalid:not(:focus):not(:placeholder-shown) {
            border-color: #e53e3e;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="page-header">
            <h1>Ajouter une catégorie</h1>
        </div>

        <div class="form-container">
            <form method="POST" action="{{ route('categories.store') }}" id="categoryForm">
                @csrf
                @if(isset($category)) @method('PUT') @endif

                <div class="form-grid">
                    <div class="form-column">
                        <div class="form-group">
                            <label for="name" class="form-label required">Nom</label>
                            <input type="text" class="form-control" id="name" name="name" 
                                   value="{{ old('name', $category->name ?? '') }}" required
                                   placeholder="Entrez le nom de la catégorie">
                        </div>

                        <div class="form-group">
                            <label for="parent_id" class="form-label">Catégorie parente</label>
                            <select class="form-select" id="parent_id" name="parent_id">
                                <option value="">Aucune (catégorie principale)</option>
                                @foreach($parentCategories as $parent)
                                    <option value="{{ $parent->id }}"
                                        {{ (isset($category) && $category->parent_id == $parent->id) ? 'selected' : '' }}>
                                        {{ $parent->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-column">
                        <div class="form-group">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" 
                                      rows="5" placeholder="Décrivez brièvement cette catégorie">{{ old('description', $category->description ?? '') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="{{ route('categories.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Retour à la liste
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('categoryForm');
            const inputs = form.querySelectorAll('input, textarea, select');
            
            // Animation pour les éléments du formulaire
            inputs.forEach((input, index) => {
                input.style.opacity = '0';
                input.style.transform = 'translateX(-20px)';
                input.style.transition = 'all 0.5s ease-out';
                
                setTimeout(() => {
                    input.style.opacity = '1';
                    input.style.transform = 'translateX(0)';
                }, 100 + (index * 100));
            });
            
            // Effet de focus amélioré
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('focused');
                });
                
                input.addEventListener('blur', function() {
                    this.parentElement.classList.remove('focused');
                });
            });
            
            // Validation basique
            form.addEventListener('submit', function(e) {
                let valid = true;
                const nameInput = document.getElementById('name');
                
                if (!nameInput.value.trim()) {
                    valid = false;
                    nameInput.style.borderColor = '#e53e3e';
                    nameInput.focus();
                    
                    // Animation d'erreur
                    nameInput.animate([
                        { transform: 'translateX(0)', backgroundColor: '#f8fafc' },
                        { transform: 'translateX(-5px)', backgroundColor: '#fed7d7' },
                        { transform: 'translateX(5px)', backgroundColor: '#fed7d7' },
                        { transform: 'translateX(0)', backgroundColor: '#fed7d7' }
                    ], {
                        duration: 600,
                        iterations: 1
                    });
                }
                
                if (!valid) {
                    e.preventDefault();
                }
            });
        });
    </script>
</body>
</html>
@endsection