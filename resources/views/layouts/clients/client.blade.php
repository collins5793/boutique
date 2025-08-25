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
            --sidebar-collapsed: 85px;
            --header-height: 80px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --radius: 12px;
            --shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            --mobile-breakpoint: 1024px;
            --tablet-breakpoint: 768px;
            --phone-breakpoint: 576px;
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
            overflow-x: hidden;
        }
        
        /* Container principal */
        .dashboard-container {
            display: flex;
            width: 100%;
            min-height: 100vh;
            position: relative;
        }
        
        /* Contenu principal */
        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            margin-left: var(--sidebar-width);
            transition: var(--transition);
            min-height: 100vh;
            position: relative;
        }
        
        .main-content.expanded {
            margin-left: var(--sidebar-collapsed);
        }
        
        /* Section Dashboard */
        .dashboard-content {
            padding: 20px;
            flex: 1;
            margin-top: var(--header-height);
            transition: var(--transition);
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
        @media (max-width: 1024px) {
            .main-content {
                margin-left: var(--sidebar-collapsed) !important;
            }
            
            .hero-section {
                padding: 40px 30px;
            }
        }
        
        @media (max-width: 768px) {
            .dashboard-content {
                padding: 15px;
            }
            
            .hero-section {
                padding: 30px 20px;
                margin-bottom: 20px;
            }
            
            .hero-content h1 {
                font-size: 2rem;
            }
            
            .hero-content p {
                font-size: 1rem;
            }
            
            .cards-grid {
                grid-template-columns: 1fr;
                gap: 15px;
            }
        }
        
        @media (max-width: 576px) {
            .dashboard-content {
                padding: 10px;
            }
            
            .hero-section {
                padding: 20px 15px;
                border-radius: 8px;
            }
            
            .hero-content h1 {
                font-size: 1.75rem;
            }
            
            .btn {
                padding: 10px 20px;
                font-size: 0.9rem;
            }
        }
    </style>
    @yield('styles')
</head>
<body>
    <div class="dashboard-container">
        {{-- Sidebar --}}
        @include('layouts.clients.sidebar')
        
        <div class="main-content" id="mainContent">
            {{-- Header --}}
            @include('layouts.clients.header')
            
            {{-- Section personnalisée --}}
            <div class="dashboard-content">
                <!-- Contenu spécifique à la page -->
                @yield('content')
            </div>
        </div>
    </div>
@yield('scripts')
    <script>
        // Script pour gérer la communication entre les composants
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const header = document.querySelector('.header');
            const toggleBtn = document.getElementById('toggleSidebar');
            const mobileMenuBtn = document.getElementById('mobileMenuButton');
            const mobileOverlay = document.getElementById('mobileOverlay');
            
            // Fonction pour basculer la sidebar
            function toggleSidebar() {
                if (window.innerWidth < 1024) {
                    // Sur mobile, on utilise l'overlay
                    sidebar.classList.toggle('mobile-open');
                    mobileOverlay.classList.toggle('active');
                } else {
                    // Sur desktop, on réduit/étend normalement
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
            }
            
            // Écouter les clics sur le bouton de toggle
            if (toggleBtn) {
                toggleBtn.addEventListener('click', toggleSidebar);
            }
            
            // Mobile menu button
            if (mobileMenuBtn) {
                mobileMenuBtn.addEventListener('click', function() {
                    sidebar.classList.add('mobile-open');
                    mobileOverlay.classList.add('active');
                });
            }
            
            // Mobile overlay
            if (mobileOverlay) {
                mobileOverlay.addEventListener('click', function() {
                    sidebar.classList.remove('mobile-open');
                    mobileOverlay.classList.remove('active');
                });
            }
            
            // Gestion responsive
            function handleResize() {
                if (window.innerWidth < 1024) {
                    // Mode mobile/tablette
                    sidebar.classList.add('collapsed');
                    mainContent.classList.add('expanded');
                    
                    // Afficher le bouton menu mobile
                    if (mobileMenuBtn) mobileMenuBtn.style.display = 'flex';
                } else {
                    // Mode desktop
                    sidebar.classList.remove('collapsed', 'mobile-open');
                    mainContent.classList.remove('expanded');
                    
                    // Cacher le bouton menu mobile et l'overlay
                    if (mobileMenuBtn) mobileMenuBtn.style.display = 'none';
                    if (mobileOverlay) mobileOverlay.classList.remove('active');
                }
            }
            
            // Écouter les changements de taille de fenêtre
            window.addEventListener('resize', handleResize);
            handleResize(); // Appel initial
            
            // Fermer le sidebar mobile quand on clique sur un élément de menu
            const menuItems = document.querySelectorAll('.menu-item');
            menuItems.forEach(item => {
                item.addEventListener('click', function() {
                    if (window.innerWidth < 1024) {
                        sidebar.classList.remove('mobile-open');
                        mobileOverlay.classList.remove('active');
                    }
                });
            });
        });
    </script>


<script>
document.addEventListener('DOMContentLoaded', function () {
    const csrfToken = '{{ csrf_token() }}';

    // ✅ Mise à jour quantité
    document.querySelectorAll('.item').forEach(item => {
        const id = item.dataset.id;
        const countEl = item.querySelector('.count');
        const decreaseBtn = item.querySelector('.decrease');
        const increaseBtn = item.querySelector('.increase');

        function updateQuantity(newQty){
            fetch(/cart/${id}, {
                method: 'PUT',
                headers: { 'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken },
                body: JSON.stringify({ quantity:newQty })
            })
            .then(res => res.json())
            .then(() => {
                countEl.textContent = newQty;
                refreshTotals();
            });
        }

        decreaseBtn.addEventListener('click', () => {
            let qty = parseInt(countEl.textContent);
            if(qty > 1) updateQuantity(qty - 1);
        });
        increaseBtn.addEventListener('click', () => {
            let qty = parseInt(countEl.textContent);
            updateQuantity(qty + 1);
        });
    });

    // ✅ Supprimer produit
    document.querySelectorAll('.remove-item').forEach(btn => {
        btn.addEventListener('click', function(){
            if(!confirm("Supprimer ce produit ?")) return;
            const article = btn.closest('.item');
            const id = article.dataset.id;
            fetch(/cart/${id}, {
                method:'DELETE',
                headers:{ 'X-CSRF-TOKEN':csrfToken }
            })
            .then(res => res.json())
            .then(() => {
                article.remove();
                refreshTotals();
            });
        });
    });

    // ✅ Vider panier
    document.getElementById('clearCartBtn')?.addEventListener('click', () => {
        if(!confirm("Voulez-vous vider le panier ?")) return;
        fetch(/cart/clear, { method:'DELETE', headers:{ 'X-CSRF-TOKEN':csrfToken } })
        .then(res => res.json())
        .then(() => location.reload());
    });

    // 🔄 Recalcul totals (simple côté client)
    function refreshTotals(){
        let subtotal = 0;
        document.querySelectorAll('.item').forEach(item => {
            let qty = parseInt(item.querySelector('.count').textContent);
            let unitPrice = parseInt(item.querySelector('.price').textContent.replace(/\D/g,'')) || 0;
            subtotal += qty * unitPrice;
        });
        document.getElementById('subtotal').textContent = subtotal.toLocaleString() + " FCFA";
        document.getElementById('grandTotal').textContent = (subtotal + 13).toLocaleString() + " FCFA";
    }
});
</script>
</body>
</html>