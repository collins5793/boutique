@php
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\CartItem;

$user = Auth::user();

$pendingCount = Order::where('user_id', $user->id)
                     ->where('order_status', 'pending')
                     ->count();

$processingCount = Order::where('user_id', $user->id)
                        ->where('order_status', 'processing')
                        ->count();


    $cartCount = CartItem::where('user_id', $user->id)->sum('quantity');

@endphp

@php
    $unreadCount = \App\Models\Notification::where('user_id', Auth::id())
        ->whereNull('read_at')
        ->count();
@endphp


{{-- <div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-center shadow-sm">
            <div class="card-body">
                <h6 class="text-muted">En attente</h6>
                <span class="badge bg-warning fs-6">{{ $pendingCount }}</span>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-center shadow-sm">
            <div class="card-body">
                <h6 class="text-muted">En cours</h6>
                <span class="badge bg-info fs-6">{{ $processingCount }}</span>
            </div>
        </div>
    </div>
</div> --}}
<div class="sidebar" id="sidebar">
    <div class="logo-container">
        <div class="logo">A</div>
        <div class="logo-text">Boutique</div>
        


    </div>
    
    <div class="menu-container">
        <div class="menu-section">
            <div class="section-label">Principal</div>
            <ul class="menu">
                <li class="menu-item {{ request()->routeIs('client.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('client.dashboard') }}" class="menu-link">
                        <div class="menu-icon"><i class="fas fa-home"></i></div>
                        <div class="menu-text">Dashboard</div>
                        <div class="tooltip">Dashboard</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('client.shop') ? 'active' : '' }}">
                    <a href="{{ route('client.shop') }}" class="menu-link">
                        <div class="menu-icon"><i class="fas fa-box"></i></div>
                        <div class="menu-text">Produits</div>
                        <div class="tooltip">Produits</div>
                    </a>
                </li>
               <li class="menu-item {{ request()->routeIs('client.orders') ? 'active' : '' }}">
                    <a href="{{ route('client.orders') }}" class="menu-link">
                        <div class="menu-icon"><i class="fas fa-shopping-cart"></i></div>
                        <div class="menu-text">Commandes</div>
                        <div class="badge" id="pendingCount">0</div>
                        <div class="badgep" id="processingCount">0</div>
                        <div class="tooltip">Commandes</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('client.panier') ? 'active' : '' }}">
                    <a href="{{ route('client.panier') }}" class="menu-link">
                        <div class="menu-icon"><i class="fas fa-shopping-cart"></i></div>
                        <div class="menu-text">Panier</div>
                        <div class="badge" id="cartCount">0</div>
                        <div class="tooltip">Panier</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('client.loyalty.index') ? 'active' : '' }}">
                    <a href="{{ route('client.loyalty.index') }}" class="menu-link">
                        <div class="menu-icon"><i class="fas fa-shopping-cart"></i></div>
                        <div class="menu-text">Fidélité & Récompenses</div>
                        <div class="tooltip">Fidélité & Récompenses</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('messages.index', ['receiver_id' => 1]) ? 'active' : '' }}">
                    <a href="{{ route('messages.index', ['receiver_id' => 1]) }}" class="menu-link">
                        <div class="menu-icon"><i class="fas fa-bell"></i></div>
                        <div class="menu-text">Messages</div>
                        <div class="tooltip">Messages</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('notifications.index') ? 'active' : '' }}">
                    <a href="{{ route('notifications.index') }}" class="menu-link">
                        <div class="menu-icon"><i class="fas fa-bell"></i></div>
                        <div class="menu-text">Notifications</div>
@if($unreadCount > 0)
                        <div class="badge" id="cartCount">+</div>
            @endif                        <div class="tooltip">Notifications</div>
                    </a>
                </li>
            </ul>
        </div>
        
        
        <div class="menu-section">
            <div class="section-label">Préférences</div>
            <ul class="menu">
                <li class="menu-item {{ request()->routeIs('client.settings') ? 'active' : '' }}">
                    <a href="{{ route('client.settings') }}" class="menu-link">
                        <div class="menu-icon"><i class="fas fa-cog"></i></div>
                        <div class="menu-text">Paramètres</div>
                        <div class="tooltip">Paramètres</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('client.support') ? 'active' : '' }}">
                    <a href="{{ route('client.support') }}" class="menu-link">
                        <div class="menu-icon"><i class="fas fa-question-circle"></i></div>
                        <div class="menu-text">Support</div>
                        <div class="tooltip">Support</div>
                    </a>
                </li>
            </ul>
        </div>

        <div class="user-profile" id="userProfile">
            <div class="user-avatar">
                {{ strtoupper(substr(Auth::user()->email ?? '', 0, 1)) }}
                <span class="user-status"></span>
            </div>

            <div class="user-info">
                <div class="user-name">{{ Auth::user()->name ?? 'Client' }}</div>
                <div class="user-role">{{ Auth::user()->email ?? '' }}</div>
            </div>
        </div>


        <!-- Menu déroulant du profil -->
            <div class="profile-dropdown" id="profileDropdown">
                <div class="dropdown-header">
                    <div class="user-avatar">
                        {{ strtoupper(substr(Auth::user()->email ?? '', 0, 1)) }}
                        <span class="user-status"></span>
                    </div>
                    <div class="user-info">
                        <div class="user-name">{{ Auth::user()->name ?? 'Client' }}</div>
                        <div class="user-role">{{ Auth::user()->email ?? '' }}</div>
                    </div>
                </div>
                
                <div class="dropdown-menu">
                    <a href="{{ route('client.settings') }}" class="dropdown-item">
                        <i class="fas fa-user"></i>
                        <span>Voir le profil</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    
                    <form method="POST" action="{{ route('logout') }}" class="dropdown-item" id="logoutBtn">
                            @csrf

                            <a href="{{route('logout')}}"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>Se déconnecter</span>
                            </a>
                    </form>
                </div>
                
                <div class="dropdown-footer">
                    Version 2.4.1 • © 2023 Akuesleystore
                </div>
            </div>
            
            <!-- Overlay pour fermer le menu -->
            <div class="dropdown-overlay" id="dropdownOverlay"></div>
    </div>
</div>

<!-- Mobile menu button -->
<button class="mobile-menu-btn" id="mobileMenuButton" style="display: none;">
    <i class="fas fa-bars"></i>
</button>

<!-- Mobile overlay -->
<div class="mobile-overlay" id="mobileOverlay"></div>



<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('sidebar');
        const mobileMenuButton = document.getElementById('mobileMenuButton');
        const mobileOverlay = document.getElementById('mobileOverlay');
        
        // Handle menu item clicks
        const menuItems = document.querySelectorAll('.menu-item');
        menuItems.forEach(item => {
            item.addEventListener('click', function() {
                // Remove active class from all items
                menuItems.forEach(i => i.classList.remove('active'));
                // Add active class to clicked item
                this.classList.add('active');
                
                // On mobile, close sidebar after selection
                if (window.innerWidth < 1024) {
                    sidebar.classList.remove('mobile-open');
                    mobileOverlay.classList.remove('active');
                }
            });
        });
        
        // Mobile handling
        if (mobileMenuButton) {
            mobileMenuButton.addEventListener('click', function() {
                sidebar.classList.add('mobile-open');
                mobileOverlay.classList.add('active');
            });
        }
        
        if (mobileOverlay) {
            mobileOverlay.addEventListener('click', function() {
                sidebar.classList.remove('mobile-open');
                mobileOverlay.classList.remove('active');
            });
        }
        
        // Window resize handler
        window.addEventListener('resize', function() {
            if (window.innerWidth < 1024) {
                if (mobileMenuButton) mobileMenuButton.style.display = 'flex';
            } else {
                if (mobileMenuButton) mobileMenuButton.style.display = 'none';
                sidebar.classList.remove('mobile-open');
                mobileOverlay.classList.remove('active');
            }
        });
    });
</script>

<style>
    .sidebar {
        width: var(--sidebar-width);
        background: linear-gradient(to bottom, var(--dark), var(--dark-light));
        color: #fff;
        height: 100%;
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
        padding: 10px 10px 10px 10px;
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 18px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .logo-text {
        font-size: 1.4rem;
        font-weight: 700;
        margin-left: 12px;
        color: white;
        white-space: nowrap;
        transition: var(--transition);
        background: linear-gradient(to right, #fff, #e2e8f0);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .sidebar.collapsed .logo-text {
        opacity: 0;
        visibility: hidden;
    }

    .toggle-btn {
        position: absolute;
        top: 50%;
        right: 15px;
        transform: translateY(-50%);
        color: rgb(32, 32, 32);
        background: rgba(59, 57, 57, 0.1);
        border: none;
        cursor: pointer;
        font-size: 1rem;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: var(--transition);
        backdrop-filter: blur(10px);
    }

    .toggle-btn:hover {
        background: rgba(59, 57, 57, 0.1);
        transform: translateY(-50%) rotate(180deg);
    }

    .menu-container {
        flex: 1;
        padding: 16px 0;
        margin-top: 10px;
    }

    .menu-section {
        padding: 0 16px;
        margin-bottom: 24px;
    }

    .section-label {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #94a3b8;
        margin-bottom: 12px;
        padding: 0 16px;
        transition: var(--transition);
        white-space: nowrap;
    }

    .sidebar.collapsed .section-label {
        opacity: 0;
        visibility: hidden;
    }

    .menu {
        list-style: none;
    }
     .menu-link {
        display: flex;
        align-items: center;
        cursor: pointer;
        transition: background .2s ease;
        text-decoration: none;
        color: inherit;
        position: relative;
    }

    .menu-item {
        padding: 14px 16px;
        margin: 6px 8px;
        display: flex;
        align-items: center;
        cursor: pointer;
        transition: var(--transition);
        border-radius: var(--radius);
        position: relative;
        overflow: hidden;
    }

    .menu-item::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: var(--primary);
        opacity: 0;
        transition: var(--transition);
    }

    .menu-item:hover {
        background: rgba(255, 255, 255, 0.05);
    }

    .menu-item:hover::before {
        opacity: 1;
    }

    .menu-item.active {
        background: linear-gradient(135deg, rgba(124, 58, 237, 0.2), rgba(139, 92, 246, 0.1));
        box-shadow: 0 4px 12px rgba(124, 58, 237, 0.15);
    }

    .menu-item.active::before {
        opacity: 1;
    }

    .menu-item.active .menu-icon {
        color: var(--primary-light);
        transform: scale(1.1);
    }

    .menu-icon {
        width: 22px;
        height: 22px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        transition: var(--transition);
        color: #cbd5e1;
    }

    .menu-text {
        margin-left: 16px;
        white-space: nowrap;
        transition: var(--transition);
        font-weight: 500;
        font-size: 0.95rem;
        color: #e2e8f0;
    }

    .sidebar.collapsed .menu-text {
        opacity: 0;
        visibility: hidden;
    }

    .badge {
        margin-left: auto;
        background: var(--primary);
        color: white;
        padding: 2px 8px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        transition: var(--transition);
    }
    .badgep {
        margin-left: auto;
        background: var(--accent);
        color: white;
        padding: 2px 8px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        transition: var(--transition);
    }

    .sidebar.collapsed .badge .badgep {
        opacity: 0;
        visibility: hidden;
    }

    /* Tooltip for collapsed state */
    .menu-item .tooltip {
        position: absolute;
        left: 90px;
        background: var(--dark);
        padding: 8px 16px;
        border-radius: 6px;
        font-size: 0.85rem;
        opacity: 0;
        visibility: hidden;
        transition: var(--transition);
        z-index: 1000;
        white-space: nowrap;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        pointer-events: none;
        color: white;
    }

    .sidebar.collapsed .menu-item:hover .tooltip {
        opacity: 1;
        visibility: visible;
    }

    .sidebar-footer {
        padding: 20px;
        border-top: 1px solid rgba(255, 255, 255, 0.08);
        margin-top: auto;
    }

    .user-profile {
        display: flex;
        align-items: center;
        transition: var(--transition);
    }

    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--secondary), var(--primary-light));
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        flex-shrink: 0;
    }

    .user-info {
        margin-left: 12px;
        transition: var(--transition);
        overflow: hidden;
    }

    .user-name {
        font-weight: 600;
        font-size: 0.95rem;
        white-space: nowrap;
    }

    .user-role {
        font-size: 0.8rem;
        color: #94a3b8;
        white-space: nowrap;
    }

    .sidebar.collapsed .user-info {
        opacity: 0;
        visibility: hidden;
        width: 0;
    }

    /* Mobile menu button */
    .mobile-menu-btn {
        position: fixed;
        top: 20px;
        left: 20px;
        z-index: 998;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: var(--primary);
        color: white;
        display: none;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 12px rgba(124, 58, 237, 0.3);
        cursor: pointer;
        border: none;
    }

    /* Mobile overlay */
    .mobile-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 999;
        opacity: 0;
        visibility: hidden;
        transition: var(--transition);
    }
    
    .mobile-overlay.active {
        opacity: 1;
        visibility: visible;
    }

    /* Mobile responsiveness */
    @media (max-width: 1024px) {
       
        
       
        .mobile-menu-btn {
            display: flex;
        }
        
        .mobile-overlay.active {
            opacity: 1;
            visibility: visible;
        }
        .logo-container {
            padding: 20px;
            height: 70px;
        }
    }

    @media (max-width: 576px) {
        .sidebar {
            width: 100%;
        }
        
        .mobile-menu-btn {
            top: 15px;
            left: 15px;
            width: 35px;
            height: 35px;
        }
        
        .logo-container {
            padding: 20px;
            height: 70px;
        }
        
        .menu-container {
            padding: 10px 0;
        }
        
        .menu-item {
            padding: 12px 14px;
            margin: 4px 6px;
        }
        
        .sidebar-footer {
            padding: 15px;
        }
    }

    /* Scrollbar styling */
    .sidebar::-webkit-scrollbar {
        width: 4px;
    }

    .sidebar::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.05);
    }

    .sidebar::-webkit-scrollbar-thumb {
        background: var(--primary);
        border-radius: 4px;
    }
</style>

<style>
  
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

    

    .logo-text {
        font-size: 1.4rem;
        font-weight: 700;
        margin-left: 12px;
        color: white;
        white-space: nowrap;
        transition: var(--transition);
        background: linear-gradient(to right, #fff, #e2e8f0);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .sidebar.collapsed .logo-text {
        opacity: 0;
        visibility: hidden;
    }

    .toggle-btn {
        position: absolute;
        top: 50%;
        right: 15px;
        transform: translateY(-50%);
        color: rgb(32, 32, 32);
        background: rgba(59, 57, 57, 0.1);
        border: none;
        cursor: pointer;
        font-size: 1rem;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: var(--transition);
        backdrop-filter: blur(10px);
    }

    .toggle-btn:hover {
        background: rgba(59, 57, 57, 0.1);
        transform: translateY(-50%) rotate(180deg);
    }

    .menu-container {
        flex: 1;
        padding: 16px 0;
        margin-top: 10px;
    }

    .menu-section {
        padding: 0 16px;
        margin-bottom: 24px;
    }

    .section-label {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #94a3b8;
        margin-bottom: 12px;
        padding: 0 16px;
        transition: var(--transition);
        white-space: nowrap;
    }

    .sidebar.collapsed .section-label {
        opacity: 0;
        visibility: hidden;
    }

    .menu {
        list-style: none;
    }

    .menu-item {
        padding: 14px 16px;
        margin: 6px 8px;
        display: flex;
        align-items: center;
        cursor: pointer;
        transition: var(--transition);
        border-radius: var(--radius);
        position: relative;
        overflow: hidden;
    }

    .menu-item::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: var(--primary);
        opacity: 0;
        transition: var(--transition);
    }

    .menu-item:hover {
        background: rgba(255, 255, 255, 0.05);
    }

    .menu-item:hover::before {
        opacity: 1;
    }

    .menu-item.active {
        background: linear-gradient(135deg, rgba(124, 58, 237, 0.2), rgba(139, 92, 246, 0.1));
        box-shadow: 0 4px 12px rgba(124, 58, 237, 0.15);
    }

    .menu-item.active::before {
        opacity: 1;
    }

    .menu-item.active .menu-icon {
        color: var(--primary-light);
        transform: scale(1.1);
    }

    .menu-icon {
        width: 22px;
        height: 22px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        transition: var(--transition);
        color: #cbd5e1;
    }

    .menu-text {
        margin-left: 16px;
        white-space: nowrap;
        transition: var(--transition);
        font-weight: 500;
        font-size: 0.95rem;
        color: #e2e8f0;
    }

    .sidebar.collapsed .menu-text {
        opacity: 0;
        visibility: hidden;
    }

    .badge {
        margin-left: auto;
        background: var(--primary);
        color: white;
        padding: 2px 8px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        transition: var(--transition);
    }

    .sidebar.collapsed .badge .badgep {
        opacity: 0;
        visibility: hidden;
    }

    /* Tooltip for collapsed state */
    .menu-item .tooltip {
        position: absolute;
        left: 90px;
        background: var(--dark);
        padding: 8px 16px;
        border-radius: 6px;
        font-size: 0.85rem;
        opacity: 0;
        visibility: hidden;
        transition: var(--transition);
        z-index: 1000;
        white-space: nowrap;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        pointer-events: none;
        color: white;
    }

    .sidebar.collapsed .menu-item:hover .tooltip {
        opacity: 1;
        visibility: visible;
    }

    .sidebar-footer {
        padding: 20px;
        border-top: 1px solid rgba(255, 255, 255, 0.08);
        margin-top: auto;
    }

    .user-profile {
        display: flex;
        align-items: center;
        transition: var(--transition);
    }

    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--secondary), var(--primary-light));
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        flex-shrink: 0;
    }

    .user-info {
        margin-left: 12px;
        transition: var(--transition);
        overflow: hidden;
    }

    .user-name {
        font-weight: 600;
        font-size: 0.95rem;
        white-space: nowrap;
    }

    .user-role {
        font-size: 0.8rem;
        color: #94a3b8;
        white-space: nowrap;
    }

    .sidebar.collapsed .user-info {
        opacity: 0;
        visibility: hidden;
        width: 0;
    }

    /* Mobile responsiveness */
    @media (max-width: 1024px) {
        @media (max-width: 1024px) {
    .sidebar {
        position: fixed;
        top: 0;
        left: -100%;   /* ✅ totalement hors écran par défaut */
        width: var(--sidebar-width);
        height: 100%;
        transition: var(--transition);
        z-index: 1000;
    }

    .sidebar.mobile-open {
        left: 0;   /* ✅ revient à l’écran quand on clique sur le bouton */
    }

    /* Mobile menu button visible */
    .mobile-menu-btn {
        display: flex;
    }

    /* Contenu prend toute la largeur */
    .main-content {
        margin-left: 0 !important;
        width: 100% !important;
    }
}

        
        .sidebar.mobile-open {
            transform: translateX(0);
            width: var(--sidebar-width);
        }
        
        .sidebar .logo-text,
        .sidebar .menu-text,
        .sidebar .section-label,
        .sidebar .badge,
        .sidebar .badgep,
        .sidebar .user-info {
            opacity: 0;
            visibility: hidden;
        }
        
        .sidebar.mobile-open .logo-text,
        .sidebar.mobile-open .menu-text,
        .sidebar.mobile-open .section-label,
        .sidebar.mobile-open .badge,
        .sidebar.mobile-open .badgep,
        .sidebar.mobile-open .user-info {
            opacity: 1;
            visibility: visible;
        }
        
        .mobile-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            opacity: 0;
            visibility: hidden;
            transition: var(--transition);
        }
        
        .mobile-overlay.active {
            opacity: 1;
            visibility: visible;
        }
        
        .mobile-menu-btn {
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 998;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(124, 58, 237, 0.3);
            cursor: pointer;
            border: none;
        }
    }

    /* Scrollbar styling */
    .sidebar::-webkit-scrollbar {
        width: 4px;
    }

    .sidebar::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.05);
    }

    .sidebar::-webkit-scrollbar-thumb {
        background: var(--primary);
        border-radius: 4px;
    }
</style>



<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function updateMenuCounts() {
        $.get("{{ route('menu.counts') }}", function(data) {
            $('#pendingCount').text(data.pending);
            $('#processingCount').text(data.processing);
            $('#cartCount').text(data.cart);
        });
    }

    // Mise à jour initiale
    updateMenuCounts();

    // Actualisation toutes les 5 secondes (5000 ms)
    setInterval(updateMenuCounts, 1000);
</script>