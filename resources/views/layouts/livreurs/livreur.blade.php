<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard - Boutique')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css"/>
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

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background-color: #fff4f4; font-family: Arial, sans-serif; min-height: 100vh; }

        .dashboard-container { display: flex; width: 100%; min-height: 100vh; }

        /* Contenu principal : par défaut on laisse la place à la sidebar large */
        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            margin-left: var(--sidebar-width);
            transition: var(--transition);
            min-height: 100vh;
        }
        /* Quand sidebar est réduite (icônes seulement) */
        .main-content.expanded { margin-left: var(--sidebar-collapsed); }

        .dashboard-content { padding: 20px; flex: 1; margin-top: var(--header-height); }

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
            position: absolute; inset: 0;
            background-image: url('https://images.unsplash.com/photo-1497215842964-222b430dc094?ixlib=rb-4.0.3&auto=format&fit=crop&w=1500&q=80');
            background-size: cover; background-position: center;
            opacity: 0.15; z-index: -1;
        }
        .hero-content { position: relative; z-index: 1; text-align: center; color: #333; }
        .hero-content h1 { font-size: 2.5rem; margin-bottom: 20px; color: #d46a6a; }
        .hero-content p { font-size: 1.2rem; line-height: 1.6; margin-bottom: 25px; max-width: 800px; margin-inline: auto; }
        .btn { display:inline-block; padding:12px 30px; background:#d46a6a; color:#fff; text-decoration:none; border-radius:30px; font-weight:bold; transition: background .3s; }
        .btn:hover { background:#bf5a5a; }
        .cards-grid { display:grid; grid-template-columns:repeat(auto-fill, minmax(300px, 1fr)); gap:20px; margin-top:30px; }
        .card { background:#fff; border-radius:10px; padding:20px; box-shadow:0 4px 8px rgba(0,0,0,0.1); }
        .card h3 { color:#d46a6a; margin-bottom:15px; }

        /* Mobile / tablette : par défaut on réserve la colonne "icônes seulement" */
        @media (max-width:1024px) {
            .main-content { margin-left: var(--sidebar-collapsed) !important; }
            .dashboard-content { margin-top: 70px; }
        }

        @media (max-width:768px) {
            .hero-section { padding: 40px 20px; }
            .hero-content h1 { font-size: 2rem; }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        {{-- Sidebar --}}
        @include('layouts.livreurs.sidebar')

        <div class="main-content" id="mainContent">
            {{-- Header (3 barres uniquement) --}}
            @include('layouts.livreurs.header')

            {{-- Contenu page --}}
            <div class="dashboard-content">
                @yield('content')
            </div>
        </div>
    </div>

    <script>
    // Petite sécurité: harmoniser l'état au resize au niveau du layout
    document.addEventListener('DOMContentLoaded', function () {
        const sidebar = document.getElementById('sidebar');
        const mobileOverlay = document.getElementById('mobileOverlay');
        const mainContent = document.getElementById('mainContent');

        function syncByWidth() {
            if (window.innerWidth < 1024) {
                sidebar.classList.add('collapsed');
                mainContent.classList.add('expanded');
                sidebar.classList.remove('mobile-open');
                mobileOverlay?.classList.remove('active');
            } else {
                // On ne touche pas à l'état "collapsed" choisi par l'utilisateur
                mobileOverlay?.classList.remove('active');
                sidebar.classList.remove('mobile-open');
                if (!sidebar.classList.contains('collapsed')) {
                    mainContent.classList.remove('expanded');
                }
            }
        }

        syncByWidth();
        window.addEventListener('resize', syncByWidth);
    });
    </script>
</body>
</html>
