<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard - Boutique')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --primary: #f506c4;
            --primary-light: #ff33d1;
            --primary-dark: #c0049b;
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
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            background-color: #fff4f4;
            font-family: Arial, sans-serif;
            min-height: 100vh;
        }
        
        /* Container principal */
        .dashboard-container {
            display: flex;
            width: 100%;
            min-height: 100vh;
        }
        
        /* Contenu principal */
        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            margin-left: var(--sidebar-width);
            transition: var(--transition);
            min-height: 100vh;
        }
        
        .main-content.expanded {
            margin-left: var(--sidebar-collapsed);
        }
        
        /* Section Dashboard */
        .dashboard-content {
            padding: 20px;
            flex: 1;
            margin-top: var(--header-height);
        }
        
        /* Section Hero avec image de fond */
        .hero-section {
            position: relative;
            padding: 60px 40px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            overflow: hidden;
            margin-bottom: 30px;
        }
        
        .hero-section::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('https://images.unsplash.com/photo-1497215842964-222b430dc094?ixlib=rb-4.0.3&auto=format&fit=crop&w=1500&q=80');
            background-size: cover;
            background-position: center;
            opacity: 0.15;
            z-index: -1;
        }
        
        .hero-content {
            position: relative;
            z-index: 1;
            text-align: center;
            color: #333;
        }
        
        .hero-content h1 {
            font-size: 2.5rem;
            margin-bottom: 20px;
            color: #d46a6a;
        }
        
        .hero-content p {
            font-size: 1.2rem;
            line-height: 1.6;
            margin-bottom: 25px;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .btn {
            display: inline-block;
            padding: 12px 30px;
            background-color: #d46a6a;
            color: white;
            text-decoration: none;
            border-radius: 30px;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        
        .btn:hover {
            background-color: #bf5a5a;
        }
        
        /* Grille de cartes */
        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }
        
        .card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        .card h3 {
            color: #d46a6a;
            margin-bottom: 15px;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .dashboard-container {
                flex-direction: column;
            }
            
            .main-content {
                margin-left: 0 !important;
            }
            
            .hero-section {
                padding: 40px 20px;
            }
            
            .hero-content h1 {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        {{-- Sidebar --}}
        @include('layouts.livreurs.sidebar')
        
        <div class="main-content" id="mainContent">
            {{-- Header --}}
            @include('layouts.livreurs.header')
            
            {{-- Section personnalisée --}}
            <div class="dashboard-content">
                <!-- Contenu spécifique à la page -->
                @yield('content')
            </div>
        </div>
    </div>

    <script>
        // Script pour gérer la communication entre les composants
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const header = document.querySelector('.header');
            const toggleBtn = document.getElementById('toggleSidebar');
            
            // Fonction pour basculer la sidebar
            function toggleSidebar() {
                sidebar.classList.toggle('collapsed');
                mainContent.classList.toggle('expanded');
                
                // Mettre à jour l'icône du bouton
                const icon = toggleBtn.querySelector('i');
                if (sidebar.classList.contains('collapsed')) {
                    icon.classList.remove('fa-chevron-left');
                    icon.classList.add('fa-chevron-right');
                } else {
                    icon.classList.remove('fa-chevron-right');
                    icon.classList.add('fa-chevron-left');
                }
            }
            
            // Écouter les clics sur le bouton de toggle
            if (toggleBtn) {
                toggleBtn.addEventListener('click', toggleSidebar);
            }
            
            // Gestion responsive
            function handleResize() {
                if (window.innerWidth < 1024) {
                    sidebar.classList.add('collapsed');
                    mainContent.classList.add('expanded');
                } else {
                    sidebar.classList.remove('collapsed');
                    mainContent.classList.remove('expanded');
                }
            }
            
            // Écouter les changements de taille de fenêtre
            window.addEventListener('resize', handleResize);
            handleResize(); // Appel initial
        });
    </script>
</body>
</html>