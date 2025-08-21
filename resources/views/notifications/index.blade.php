@extends('layouts.clients.client')

@section('title', 'Notifications')

@section('content')
<div class="notifications-container">
    <div class="notifications-header">
        <div class="notifications-actions">
            <button class="btn btn-outline">
                <i class="fas fa-filter"></i> Filtrer
            </button>
            {{-- <form action="{{ route('notifications.markAllAsRead') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-check-double"></i> Tout marquer comme lu
                </button>
            </form> --}}
        </div>
    </div>

    <div class="notifications-filters">
        <button class="filter-btn active">Toutes</button>
        <button class="filter-btn">Non lues</button>
        <button class="filter-btn">Promotions</button>
        <button class="filter-btn">Commandes</button>
        <button class="filter-btn">Système</button>
    </div> 

    <div class="notification-list">
        @if($notifications->count())
            @foreach($notifications as $notification)
                <a href="{{ route('notifications.show', $notification->id) }}" 
                   class="notification-item {{ is_null($notification->read_at) ? 'unread' : '' }}">
                    <div class="notification-icon {{ $notification->type }}">
                        @if($notification->type == 'promo')
                            <i class="fas fa-percent"></i>
                        @elseif($notification->type == 'order')
                            <i class="fas fa-shopping-cart"></i>
                        @elseif($notification->type == 'system')
                            <i class="fas fa-cog"></i>
                        @else
                            <i class="fas fa-bell"></i>
                        @endif
                    </div>
                    <div class="notification-content">
                        <h3 class="notification-title">{{ $notification->title }}</h3>
                        <p class="notification-message">{{ $notification->message ?? Str::limit($notification->data, 100) }}</p>
                        <div class="notification-meta">
                            <span class="notification-time">
                                <i class="far fa-clock"></i> {{ $notification->created_at->diffForHumans() }}
                            </span>
                        </div>
                    </div>
                    <div class="notification-actions">
                        {{-- <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="action-btn" onclick="return confirm('Supprimer cette notification ?')">
                                <i class="far fa-trash-alt"></i>
                            </button>
                        </form> --}}
                    </div>
                    @if(is_null($notification->read_at))
                        <span class="notification-badge"></span>
                    @endif
                </a>
            @endforeach
        @else
            <div class="empty-state">
                <i class="far fa-bell-slash"></i>
                <h3>Aucune notification</h3>
                <p>Vous n'avez aucune notification pour le moment.</p>
            </div>
        @endif
    </div>
</div>

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
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #3b82f6;
            --gray-100: #f8fafc;
            --gray-200: #e2e8f0;
            --gray-300: #cbd5e1;
            --gray-400: #94a3b8;
            --gray-500: #64748b;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --radius: 12px;
            --shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

       

        .notifications-container {
            max-width: 800px;
            padding: 20px;
        }

        .notifications-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--gray-200);
        }

        .notifications-header h1 {
            font-size: 28px;
            font-weight: 700;
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .notifications-header h1 i {
            color: var(--primary);
            font-size: 32px;
        }

        .notifications-actions {
            display: flex;
            gap: 10px;
        }

        .btn {
            padding: 10px 18px;
            border-radius: var(--radius);
            border: none;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background-color: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(245, 6, 196, 0.3);
        }

        .btn-outline {
            background-color: transparent;
            border: 1px solid var(--gray-300);
            color: var(--gray-500);
        }

        .btn-outline:hover {
            background-color: var(--gray-100);
            color: var(--dark);
        }

        .notifications-filters {
            display: flex;
            gap: 15px;
            margin-bottom: 25px;
            flex-wrap: wrap;
        }

        .filter-btn {
            padding: 8px 16px;
            border-radius: 20px;
            border: 1px solid var(--gray-300);
            background-color: white;
            color: var(--gray-500);
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
        }

        .filter-btn.active, .filter-btn:hover {
            background-color: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        .notification-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .notification-item {
            background-color: white;
            border-radius: var(--radius);
            padding: 20px;
            box-shadow: var(--shadow);
            display: flex;
            gap: 15px;
            transition: var(--transition);
            position: relative;
            border-left: 4px solid transparent;
            text-decoration: none;
            color: inherit;
        }

        .notification-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }

        .notification-item.unread {
            border-left-color: var(--primary);
            background-color: rgba(245, 6, 196, 0.03);
        }

        .notification-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 20px;
        }

        .notification-icon.promo {
            background-color: rgba(245, 6, 196, 0.1);
            color: var(--primary);
        }

        .notification-icon.order {
            background-color: rgba(59, 130, 246, 0.1);
            color: var(--info);
        }

        .notification-icon.system {
            background-color: rgba(16, 185, 129, 0.1);
            color: var(--success);
        }

        .notification-icon.alert {
            background-color: rgba(245, 158, 11, 0.1);
            color: var(--warning);
        }

        .notification-content {
            flex: 1;
        }

        .notification-title {
            font-weight: 600;
            margin-bottom: 5px;
            color: var(--dark);
        }

        .notification-item.unread .notification-title {
            color: var(--primary-dark);
        }

        .notification-message {
            color: var(--gray-500);
            margin-bottom: 10px;
        }

        .notification-meta {
            display: flex;
            align-items: center;
            gap: 15px;
            font-size: 13px;
            color: var(--gray-400);
        }

        .notification-time {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .notification-actions {
            display: flex;
            gap: 10px;
        }

        .notification-badge {
            position: absolute;
            top: 20px;
            right: 20px;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background-color: var(--primary);
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            background-color: white;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
        }

        .empty-state i {
            font-size: 60px;
            color: var(--gray-300);
            margin-bottom: 20px;
        }

        .empty-state h3 {
            font-size: 22px;
            color: var(--gray-400);
            margin-bottom: 10px;
        }

        .empty-state p {
            color: var(--gray-400);
            max-width: 400px;
            margin: 0 auto 25px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .notifications-container {
                margin: 20px auto;
                padding: 15px;
            }
            
            .notifications-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
            
            .notifications-actions {
                width: 100%;
                justify-content: space-between;
            }
            
            .notification-item {
                flex-direction: column;
            }
            
            .notification-icon {
                align-self: flex-start;
            }
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .notification-item {
            animation: fadeIn 0.4s ease-out;
        }

        .notification-item:nth-child(1) { animation-delay: 0.05s; }
        .notification-item:nth-child(2) { animation-delay: 0.1s; }
        .notification-item:nth-child(3) { animation-delay: 0.15s; }
        .notification-item:nth-child(4) { animation-delay: 0.2s; }
        .notification-item:nth-child(5) { animation-delay: 0.25s; }
    </style>

<script>
        document.addEventListener('DOMContentLoaded', function() {
            // Filtrer les notifications
            const filterButtons = document.querySelectorAll('.filter-btn');
            const notificationItems = document.querySelectorAll('.notification-item');
            
            filterButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Retirer la classe active de tous les boutons
                    filterButtons.forEach(btn => btn.classList.remove('active'));
                    // Ajouter la classe active au bouton cliqué
                    this.classList.add('active');
                    
                    const filter = this.textContent.toLowerCase();
                    
                    // Filtrer les notifications
                    notificationItems.forEach(item => {
                        if (filter === 'toutes') {
                            item.style.display = 'flex';
                        } else if (filter === 'non lues') {
                            if (item.classList.contains('unread')) {
                                item.style.display = 'flex';
                            } else {
                                item.style.display = 'none';
                            }
                        } else if (filter === 'promotions') {
                            if (item.querySelector('.notification-icon.promo')) {
                                item.style.display = 'flex';
                            } else {
                                item.style.display = 'none';
                            }
                        } else if (filter === 'commandes') {
                            if (item.querySelector('.notification-icon.order')) {
                                item.style.display = 'flex';
                            } else {
                                item.style.display = 'none';
                            }
                        } else if (filter === 'système') {
                            if (item.querySelector('.notification-icon.system')) {
                                item.style.display = 'flex';
                            } else {
                                item.style.display = 'none';
                            }
                        }
                    });
                });
            });
            
            // Marquer toutes les notifications comme lues
            const markAllReadBtn = document.querySelector('.btn-primary');
            if (markAllReadBtn) {
                markAllReadBtn.addEventListener('click', function() {
                    document.querySelectorAll('.notification-item.unread').forEach(item => {
                        item.classList.remove('unread');
                        const badge = item.querySelector('.notification-badge');
                        if (badge) {
                            badge.remove();
                        }
                    });
                    
                    // Afficher une confirmation
                    const originalText = this.innerHTML;
                    this.innerHTML = '<i class="fas fa-check"></i> Marqué comme lu';
                    
                    setTimeout(() => {
                        this.innerHTML = originalText;
                    }, 2000);
                });
            }
            
            // Supprimer une notification
            const deleteButtons = document.querySelectorAll('.action-btn');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    const notification = this.closest('.notification-item');
                    notification.style.opacity = '0';
                    notification.style.transform = 'translateX(100px)';
                    
                    setTimeout(() => {
                        notification.remove();
                        
                        // Si plus de notifications, afficher l'état vide
                        if (document.querySelectorAll('.notification-item').length === 0) {
                            const emptyState = document.createElement('div');
                            emptyState.className = 'empty-state';
                            emptyState.innerHTML = `
                                <i class="far fa-bell-slash"></i>
                                <h3>Aucune notification</h3>
                                <p>Vous n'avez aucune notification pour le moment.</p>
                            `;
                            document.querySelector('.notification-list').appendChild(emptyState);
                        }
                    }, 300);
                });
            });
        });
    </script>


@endsection