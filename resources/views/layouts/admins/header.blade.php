<div class="header">
    <div class="logo-container"><button class="toggle-btn" id="toggleSidebar">
            <i class="fas fa-chevron-left"></i>
        </button></div>
    <a href="#" class="logo-container">
        <div class="logo">A</div>
        <div class="logo-text">Aku<span>eleystore</span></div>
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

            <div class="user-info">
                <div class="user-name">{{ Auth::user()->name ?? 'Client' }}</div>
                <div class="user-role">{{ Auth::user()->email ?? '' }}</div>
            </div>
        </div>
    </div>
</div>

<style>
    .header {
        position: fixed;
        top: 0;
        left: var(--sidebar-width);
        height: var(--header-height);
        width: calc(100% - var(--sidebar-width));
        background:#e0e0e0;
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

    /* Mobile responsiveness */
    @media (max-width: 1024px) {
        .search-box {
            width: 350px;
        }
    }

    @media (max-width: 768px) {
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
    }

    @media (max-width: 576px) {
        .header {
            padding: 0 15px;
        }
        
        .logo-text {
            display: none;
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchBox = document.getElementById('searchBox');
        const notificationBtn = document.querySelector('.action-btn');
        
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
        
        // Notification button animation
        if (notificationBtn) {
            notificationBtn.addEventListener('click', function() {
                this.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    this.style.transform = '';
                }, 150);
                
                // Simuler la lecture des notifications
                const dot = document.querySelector('.notification-dot');
                if (dot) {
                    dot.style.animation = 'none';
                    setTimeout(() => {
                        dot.style.display = 'none';
                    }, 300);
                }
            });
        }
        
        // User profile dropdown simulation
        const userProfile = document.querySelector('.user-profile');
        if (userProfile) {
            userProfile.addEventListener('click', function() {
                this.style.transform = 'scale(0.98)';
                setTimeout(() => {
                    this.style.transform = '';
                }, 150);
            });
        }
    });
</script>