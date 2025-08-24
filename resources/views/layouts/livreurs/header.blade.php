<div class="header">
    <div class="header-left"> <!-- SEULEMENT les 3 barres --> 
        <button class="toggle-sidebar-btn" id="toggleSidebar"> 
            <i class="fas fa-bars"></i> 
        </button>
    </div>
    <div> 
        <a href="#" class="logo-container"> 
            <div class="logo">A</div> 
            <div class="logo-text">Aku<span>eleystore</span></div> 
        </a> 
    </div>
    
    <div class="search-box" id="searchBox">
        <i class="fas fa-search"></i>
        <input type="text" placeholder="Rechercher produits, commandes, clients...">
        <div class="search-categories">
            <span>Tout</span>
            <i class="fas fa-chevron-down"></i>
        </div>
    </div>
    
    <div class="header-actions">
        <button class="action-btn">
            <i class="far fa-bell"></i>
            <span class="notification-dot"></span>
        </button>
        
        <button class="action-btn">
            <i class="far fa-comment"></i>
        </button>
        
        <div class="user-profile">
            <div class="user-avatar">
                {{ strtoupper(substr(Auth::user()->email ?? '', 0, 1)) }}
                <span class="user-status"></span>
            </div>
            <div class="header-left">
                <div class="user-info">
                    <div class="user-name">{{ Auth::user()->name ?? 'Client' }}</div>
                    <div class="user-role">{{ Auth::user()->email ?? '' }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* ================= HEADER ================= */
    .header {
        position: fixed;
        top: 0;
        left: var(--sidebar-width, 280px);
        height: var(--header-height, 80px);
        width: calc(100% - var(--sidebar-width, 280px));
        background:#e0e0e0;
        box-shadow: var(--shadow, 0 10px 30px rgba(0,0,0,0.1));
        display: flex;
        align-items: center;
        padding: 0 16px;
        z-index: 900;
        transition: left 0.3s ease, width 0.3s ease;
    }
    .sidebar.collapsed ~ .main-content .header {
        left: var(--sidebar-collapsed, 90px);
        width: calc(100% - var(--sidebar-collapsed, 90px));
    }

    /* Mobile : header réduit */
    @media (max-width:1024px) {
        .header {
            left: var(--sidebar-collapsed, 90px);
            width: calc(100% - var(--sidebar-collapsed, 90px));
            height: 70px;
        }
    }

    /* ================= TOGGLE BUTTON ================= */
    .toggle-sidebar-btn {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background: #fff;
        border: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: transform .2s ease, box-shadow .2s ease, color .2s ease, background .2s ease;
        color: #64748b;
        font-size: 1.2rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    .toggle-sidebar-btn:hover {
        background: #f8fafc;
        color: var(--primary, #7c3aed);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    /* ================= LOGO ================= */
    .logo-container {
        display: flex;
        align-items: center;
        gap: 14px;
        text-decoration: none;
    }
    .logo {
        width: 45px;
        height: 45px;
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 20px;
        box-shadow: 0 4px 12px rgba(124, 58, 237, 0.3);
    }
    .logo-text {
        font-size: 1.6rem;
        font-weight: 800;
        color: var(--dark);
        letter-spacing: -0.5px;
    }
    .logo-text span {
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    /* ================= SEARCH BOX ================= */
    .search-box {
        display: flex;
        align-items: center;
        background: #f8fafc;
        border-radius: var(--radius);
        padding: 0 20px;
        height: 50px;
        width: 450px;
        border: 1.5px solid #e2e8f0;
        transition: var(--transition);
        position: relative;
    }
    .search-box:focus-within {
        border-color: var(--primary-light);
        box-shadow: 0 0 0 4px rgba(139, 92, 246, 0.15);
        background: white;
    }
    .search-box i {
        color: #94a3b8;
        margin-right: 14px;
        font-size: 1.1rem;
        transition: var(--transition);
    }
    .search-box:focus-within i { color: var(--primary); }
    .search-box input {
        border: none;
        background: transparent;
        width: 100%;
        outline: none;
        font-size: 0.95rem;
        color: var(--dark);
        font-weight: 500;
    }
    .search-box input::placeholder { color: #94a3b8; font-weight: 500; }

    .search-categories {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        display: flex;
        align-items: center;
        background: #f1f5f9;
        border-radius: 8px;
        padding: 4px 8px;
        cursor: pointer;
        transition: var(--transition);
    }
    .search-categories:hover { background: #e2e8f0; }
    .search-categories span { font-size: 0.8rem; color: #64748b; margin-right: 6px; font-weight: 500; }
    .search-categories i { font-size: 0.8rem; color: #64748b; }

    /* ================= ACTIONS ================= */
    .header-actions { display: flex; align-items: center; gap: 20px; }
    .action-btn {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background: white;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        position: relative;
        color: #64748b;
        font-size: 1.2rem;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        transition: var(--transition);
    }
    .action-btn:hover {
        background: #f8fafc;
        color: var(--primary);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .notification-dot {
        position: absolute;
        top: 10px;
        right: 10px;
        width: 10px;
        height: 10px;
        background: #ef4444;
        border-radius: 50%;
        border: 2px solid white;
        animation: pulse 2s infinite;
    }
    @keyframes pulse {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(239,68,68,0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(239,68,68,0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(239,68,68,0); }
    }

    /* ================= USER PROFILE ================= */
    .user-profile {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 6px 14px 6px 6px;
        border-radius: 30px;
        cursor: pointer;
        background: #f8fafc;
        border: 1px solid #f1f5f9;
        transition: var(--transition);
    }
    .user-profile:hover { background: #f1f5f9; box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
    .user-avatar {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--secondary), var(--primary-light));
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 1.1rem;
        box-shadow: 0 4px 10px rgba(14, 165, 233, 0.25);
        position: relative;
    }
    .user-status {
        position: absolute;
        bottom: 0;
        right: 0;
        width: 12px;
        height: 12px;
        background: #22c55e;
        border-radius: 50%;
        border: 2px solid white;
    }
    .user-info { display: flex; flex-direction: column; }
    .user-name { font-weight: 600; color: var(--dark); font-size: 0.95rem; white-space: nowrap; }
    .user-role { font-size: 0.8rem; color: #64748b; font-weight: 500; }

    /* ================= RESPONSIVE ================= */
    @media (max-width: 1024px) { .search-box { width: 350px; } }
    @media (max-width: 768px) {
        .logo-text { font-size: 1.4rem; }
        .search-box {
            width: 45px; overflow: hidden; padding: 0;
            justify-content: center; cursor: pointer;
        }
        .search-box.expanded { width: 250px; padding: 0 20px; justify-content: flex-start; }
        .search-box.expanded input, .search-box.expanded .search-categories { display: flex; }
        .search-box input, .search-categories { display: none; }
        .user-info { display: none; }
    }
    @media (max-width: 576px) {
        .header { padding: 0 15px; }
        .logo-text { display: none; }
        .search-box.expanded { width: 200px; }
        .action-btn, .user-avatar { width: 40px; height: 40px; }
    }
</style>


<script>
document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.getElementById('sidebar');
    const mobileOverlay = document.getElementById('mobileOverlay');
    const toggleBtn = document.getElementById('toggleSidebar');
    const mainContent = document.getElementById('mainContent');

    // État initial : mobile => réduit (icônes seulement), desktop => normal
    function applyInitialState() {
        if (window.innerWidth < 1024) {
            sidebar.classList.add('collapsed');
            mainContent.classList.add('expanded'); // main à gauche = largeur réduite
            sidebar.classList.remove('mobile-open');
            mobileOverlay?.classList.remove('active');
        } else {
            sidebar.classList.remove('mobile-open');
            mobileOverlay?.classList.remove('active');
            // Laisse l'état "collapsed" si l'utilisateur l'avait choisi
            if (!sidebar.classList.contains('collapsed')) {
                mainContent.classList.remove('expanded');
            }
        }
    }

    applyInitialState();
    window.addEventListener('resize', applyInitialState);

    // Toggle au clic
    toggleBtn?.addEventListener('click', function () {
        if (window.innerWidth < 1024) {
            // MOBILE : slide-in / slide-out
            const isOpen = sidebar.classList.toggle('mobile-open');
            if (isOpen) {
                mobileOverlay?.classList.add('active');
            } else {
                mobileOverlay?.classList.remove('active');
            }
            // On ne change PAS l'icône (3 barres restent 3 barres)
        } else {
            // DESKTOP : élargir/réduire (icônes seulement)
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');
            // On ne change PAS l'icône (3 barres restent 3 barres)
        }
    });

    // Fermer en cliquant sur l'overlay (mobile)
    mobileOverlay?.addEventListener('click', function () {
        // sidebar.classList.remove('mobile-open');
        // mobileOverlay.classList.remove('active');
    });
});
</script>
