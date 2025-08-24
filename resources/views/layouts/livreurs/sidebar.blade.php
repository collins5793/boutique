<div class="sidebar" id="sidebar">
    <div class="logo-container">
        <div class="logo">L</div>
        <div class="logo-text">Livraison</div>
    </div>

    <div class="menu-container">
        <div class="menu-section">
            <div class="section-label">livreur</div>
            <ul class="menu">
                <li class="menu-item active">
                    <a href="{{ route('delivery.dashboard') }}" class="menu-link">
                        <div class="menu-icon"><i class="fas fa-home"></i></div>
                        <div class="menu-text">Dashboard</div>
                        <div class="tooltip">Dashboard</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ url('/delivery/pending') }}" class="menu-link">
                        <div class="menu-icon"><i class="fas fa-shopping-cart"></i></div>
                        <div class="menu-text">Commandes en attente</div>
                        <div class="badge">3</div>
                        <div class="tooltip">Commandes en attente</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('delivery.delivered-orders') }}" class="menu-link">
                        <div class="menu-icon"><i class="fas fa-clipboard-check"></i></div>
                        <div class="menu-text">Mes Livraisons</div>
                        <div class="tooltip">Mes Livraisons Effectuées</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="#" class="menu-link">
                        <div class="menu-icon"><i class="fas fa-users"></i></div>
                        <div class="menu-text">Clients</div>
                        <div class="tooltip">Clients</div>
                    </a>
                </li>
            </ul>
        </div>

        <div class="menu-section">
            <div class="section-label">Préférences</div>
            <ul class="menu">
                <li class="menu-item">
                    <a href="#" class="menu-link">
                        <div class="menu-icon"><i class="fas fa-cog"></i></div>
                        <div class="menu-text">Paramètres</div>
                        <div class="tooltip">Paramètres</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="#" class="menu-link">
                        <div class="menu-icon"><i class="fas fa-question-circle"></i></div>
                        <div class="menu-text">Support</div>
                        <div class="tooltip">Support</div>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>

<!-- Overlay pour mobile -->
<div class="mobile-overlay" id="mobileOverlay"></div>

<style>
    :root {
        --primary: #7c3aed;
        --primary-light: #8b5cf6;
        --secondary: #0ea5e9;
        --dark: #1e293b;
        --dark-light: #334155;
        --sidebar-width: 280px;
        --sidebar-collapsed: 90px;
        --header-height: 80px;
        --radius: 12px;
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        --shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    .sidebar {
        width: var(--sidebar-width);
        background: linear-gradient(to bottom, var(--dark), var(--dark-light));
        color: #fff;
        height: 100vh;
        position: fixed;
        top: 0;
        left: 0;
        display: flex;
        flex-direction: column;
        transition: var(--transition);
        z-index: 1000;
        box-shadow: var(--shadow);
        overflow-y: auto;
        overflow-x: hidden;
    }

    /* Réduit = icônes seulement (desktop et mobile par défaut) */
    .sidebar.collapsed {
        width: var(--sidebar-collapsed);
    }

    .logo-container {
        display: flex;
        align-items: center;
        padding: 24px;
        height: 80px;
        position: relative;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    }

    .logo {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 18px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        flex-shrink: 0;
    }

    .logo-text {
        font-size: 1.4rem;
        font-weight: 700;
        margin-left: 12px;
        color: white;
        white-space: nowrap;
        transition: opacity .2s ease;
        background: linear-gradient(to right, #fff, #e2e8f0);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .menu-container { flex: 1; padding: 16px 0; margin-top: 10px; }
    .menu-section { padding: 0 16px; margin-bottom: 24px; }

    .section-label {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #94a3b8;
        margin-bottom: 12px;
        padding: 0 16px;
        white-space: nowrap;
        transition: opacity .2s ease;
    }

    .menu { list-style: none; padding: 0; margin: 0; }
    .menu-item { margin: 6px 8px; border-radius: var(--radius); position: relative; overflow: hidden; }
    .menu-link {
        padding: 14px 16px;
        display: flex;
        align-items: center;
        cursor: pointer;
        transition: background .2s ease;
        text-decoration: none;
        color: inherit;
        position: relative;
    }
    .menu-item:hover { background: rgba(255,255,255,0.05); }

    .menu-item.active {
        background: linear-gradient(135deg, rgba(124,58,237,.2), rgba(139,92,246,.1));
        box-shadow: 0 4px 12px rgba(124,58,237,.15);
    }

    .menu-icon { width: 22px; height: 22px; display: flex; align-items: center; justify-content: center; color: #cbd5e1; flex-shrink: 0; }
    .menu-text { margin-left: 16px; white-space: nowrap; font-weight: 500; font-size: 0.95rem; color: #e2e8f0; transition: opacity .2s ease; }
    .badge { margin-left: auto; background: var(--primary); color: white; padding: 2px 8px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; transition: opacity .2s ease; }

    /* Cacher les textes en mode "collapsed" (icônes seulement) */
    .sidebar.collapsed .logo-text,
    .sidebar.collapsed .menu-text,
    .sidebar.collapsed .section-label,
    .sidebar.collapsed .badge {
        opacity: 0;
        visibility: hidden;
    }

    /* Tooltip quand collapsed (desktop) */
    .menu-item .tooltip {
        position: absolute;
        left: 90px;
        background: var(--dark);
        padding: 8px 12px;
        border-radius: 6px;
        font-size: 0.85rem;
        opacity: 0;
        visibility: hidden;
        transition: opacity .2s ease;
        z-index: 1000;
        white-space: nowrap;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        pointer-events: none;
        color: white;
    }
    .sidebar.collapsed .menu-item:hover .tooltip { opacity: 1; visibility: visible; }

    /* --- Mobile --- */
    @media (max-width:1024px) {
        /* Par défaut sur mobile : sidebar réduit (icônes seulement) */
        .sidebar { width: var(--sidebar-collapsed); }

        /* Ouvert en slide-in (largeur complète) */
        .sidebar.mobile-open {
            width: var(--sidebar-width);
        }

        /* Overlay */
        .mobile-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.45);
            z-index: 999;
            opacity: 0;
            visibility: hidden;
            transition: opacity .25s ease, visibility .25s ease;
        }
        .mobile-overlay.active { opacity: 1; visibility: visible; }
    }

    /* Scrollbar */
    .sidebar::-webkit-scrollbar { width: 4px; }
    .sidebar::-webkit-scrollbar-track { background: rgba(255,255,255,0.05); }
    .sidebar::-webkit-scrollbar-thumb { background: var(--primary); border-radius: 4px; }
</style>
