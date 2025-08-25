@php
    $unreadCount = \App\Models\Notification::where('user_id', Auth::id())
        ->whereNull('read_at')
        ->count();
@endphp

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Profil Animé</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        
       
        .header {
            position: fixed;
            top: 0;
            left: var(--sidebar-width);
            height: var(--header-height);
            width: calc(100% - var(--sidebar-width));
            background: #e0e0e0;
            box-shadow: var(--shadow);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 30px;
            z-index: 900;
            transition: var(--transition);
        }
        
        /* Quand la sidebar est réduite */
        .main-content.expanded .header {
            left: var(--sidebar-collapsed);
            width: calc(100% - var(--sidebar-collapsed));
        }
        
        .logo-container {
            display: flex;
            align-items: center;
            gap: 14px;
            text-decoration: none;
            transition: var(--transition);
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
        
        .search-box {
            display: flex;
            align-items: center;
            background: #f8fafc;
            border-radius: var(--radius);
            padding: 0 20px;
            height: 50px;
            width: 450px;
            transition: var(--transition);
            border: 1.5px solid #e2e8f0;
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
        
        .search-box:focus-within i {
            color: var(--primary);
        }
        
        .search-box input {
            border: none;
            background: transparent;
            padding: 0;
            width: 100%;
            outline: none;
            font-size: 0.95rem;
            color: var(--dark);
            font-weight: 500;
        }
        
        .search-box input::placeholder {
            color: #94a3b8;
            font-weight: 500;
        }
        
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
        
        .search-categories:hover {
            background: #e2e8f0;
        }
        
        .search-categories span {
            font-size: 0.8rem;
            color: #64748b;
            margin-right: 6px;
            font-weight: 500;
        }
        
        .search-categories i {
            margin-right: 0;
            font-size: 0.8rem;
            color: #64748b;
        }
        
        .header-actions {
            display: flex;
            align-items: center;
            gap: 20px;
            position: relative;
        }
        
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
            transition: var(--transition);
            color: #64748b;
            font-size: 1.2rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
        
        .action-btn:hover {
            background: #f8fafc;
            color: var(--primary);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
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
            0% {
                transform: scale(0.95);
                box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7);
            }
            
            70% {
                transform: scale(1);
                box-shadow: 0 0 0 6px rgba(239, 68, 68, 0);
            }
            
            100% {
                transform: scale(0.95);
                box-shadow: 0 0 0 0 rgba(239, 68, 68, 0);
            }
        }
        
        .user-profile {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 6px 14px 6px 6px;
            border-radius: 30px;
            transition: var(--transition);
            cursor: pointer;
            background: #f8fafc;
            border: 1px solid #f1f5f9;
            position: relative;
        }
        
        .user-profile:hover {
            background: #f1f5f9;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }
        
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
        
        .user-info {
            display: flex;
            flex-direction: column;
        }
        
        .user-name {
            font-weight: 600;
            color: var(--dark);
            font-size: 0.95rem;
            white-space: nowrap;
        }
        
        .user-role {
            font-size: 0.8rem;
            color: #64748b;
            font-weight: 500;
        }
        
        /* Menu déroulant du profil */
        .profile-dropdown {
            position: absolute;
            top: calc(100% + 10px);
            right: 0;
            width: 280px;
            background: white;
            border-radius: var(--radius);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: var(--transition);
            z-index: 1000;
        }
        
        .profile-dropdown.active {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        
        .dropdown-header {
            padding: 20px;
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            color: white;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .dropdown-header .user-avatar {
            width: 50px;
            height: 50px;
            font-size: 1.3rem;
        }
        
        .dropdown-header .user-info {
            color: white;
        }
        
        .dropdown-header .user-name {
            color: white;
            font-size: 1.1rem;
        }
        
        .dropdown-header .user-role {
            color: rgba(255, 255, 255, 0.8);
        }
        
        .dropdown-menu {
            padding: 10px 0;
        }
        
        .dropdown-item {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: #64748b;
            text-decoration: none;
            transition: var(--transition);
            gap: 12px;
        }
        
        .dropdown-item i {
            width: 20px;
            text-align: center;
        }
        
        .dropdown-item:hover {
            background: #f1f5f9;
            color: var(--primary);
        }
        
        .dropdown-divider {
            height: 1px;
            background: #e2e8f0;
            margin: 5px 0;
        }
        
        .dropdown-footer {
            padding: 15px 20px;
            background: #f8fafc;
            text-align: center;
            font-size: 0.8rem;
            color: #94a3b8;
        }
        
        /* Overlay pour fermer le menu en cliquant à l'extérieur */
        .dropdown-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: transparent;
            z-index: 999;
            display: none;
        }
        
        /* Mobile responsiveness */
        @media (max-width: 1024px) {
            .search-box {
                width: 350px;
            }
        }
        
        @media (max-width: 768px) {
            .toggle-btn {
                /* display: none */
            }
            .header {
                padding: 0 20px;
                height: 70px;
            }
            
            .logo-text {
                font-size: 1.4rem;
            }
            
            .search-box {
                width: 45px;
                overflow: hidden;
                transition: var(--transition);
                padding: 0;
                justify-content: center;
                cursor: pointer;
            }
            
            .search-box.expanded {
                width: 250px;
                padding: 0 20px;
                justify-content: flex-start;
            }
            
            .search-box.expanded .search-categories,
            .search-box.expanded input {
                display: flex;
            }
            
            .search-box i {
                margin-right: 0;
            }
            
            .search-categories,
            .search-box input {
                display: none;
            }
            
            .search-box.expanded i {
                margin-right: 14px;
            }
            
            .user-info {
                display: none;
            }
            
            .profile-dropdown {
                width: 250px;
                right: -10px;
            }
        }
        
        @media (max-width: 576px) {
            .header {
                padding: 0 15px;
            }
            
            .logo-text {
                display: ;
            }
            
            .search-box.expanded {
                width: 200px;
            }
            
            .action-btn {
                width: 40px;
                height: 40px;
            }
            
            .user-avatar {
                width: 40px;
                height: 40px;
            }
        }
    </style>
</head>
<body>
    <div class="header">
    <div class="logo-container"><button class="toggle-btn" id="toggleSidebar">
            <i class="fas fa-chevron-left"></i>
        </button></div>
    <a href="#" class="logo-container">
        <div class="logo">A</div>
        <div class="logo-text">Aku<span>esleystore</span></div>
    </a>

    <div class="search-box" id="searchBox">
        <i class="fas fa-search"></i>
        <input type="text" placeholder="Rechercher produits, commandes, clients...">
        <div class="search-categories">
            <span>Tout</span>
            <i class="fas fa-chevron-down"></i>
        </div>
    </div>
    
    <div class="header-actions">
        <a href="{{ route('notifications.index') }}" class="action-btn">
            <i class="far fa-bell"></i>
            @if($unreadCount > 0)
                <span class="notification-dot"></span>
            @endif
        </a>

        
        <a href="{{ route('messages.index', ['receiver_id' => 1]) }}" class="action-btn">
            <i class="far fa-comment"></i>
        </a>

        
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
                    <a href="{{route('profile.edit')}}" class="dropdown-item">
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const userProfile = document.getElementById('userProfile');
            const profileDropdown = document.getElementById('profileDropdown');
            const dropdownOverlay = document.getElementById('dropdownOverlay');
            const logoutBtn = document.getElementById('logoutBtn');
            const searchBox = document.getElementById('searchBox');
            
            // Ouvrir/fermer le menu profil
            userProfile.addEventListener('click', function(e) {
                e.stopPropagation();
                profileDropdown.classList.toggle('active');
                dropdownOverlay.style.display = profileDropdown.classList.contains('active') ? 'block' : 'none';
            });
            
            // Fermer le menu en cliquant à l'extérieur
            dropdownOverlay.addEventListener('click', function() {
                profileDropdown.classList.remove('active');
                dropdownOverlay.style.display = 'none';
            });
            
            // Animation de déconnexion
            logoutBtn.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Ajouter une animation de chargement
                logoutBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Déconnexion...</span>';
                
                // Simuler une déconnexion
                setTimeout(() => {
                    alert('Déconnexion réussie!');
                    profileDropdown.classList.remove('active');
                    dropdownOverlay.style.display = 'none';
                    logoutBtn.innerHTML = '<i class="fas fa-sign-out-alt"></i><span>Se déconnecter</span>';
                }, 1500);
            });
            
            // Search box expansion on mobile
            if (window.innerWidth < 768) {
                searchBox.addEventListener('click', function() {
                    this.classList.toggle('expanded');
                    if (this.classList.contains('expanded')) {
                        this.querySelector('input').focus();
                    }
                });
                
                // Close search when clicking outside
                document.addEventListener('click', function(e) {
                    if (!searchBox.contains(e.target) && searchBox.classList.contains('expanded')) {
                        searchBox.classList.remove('expanded');
                    }
                });
            }
        });
    </script>
</body>
</html>