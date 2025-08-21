@extends('layouts.clients.client')

@section('title', 'Notification - ' . $notification->title)

@section('content')
<div class="notification-detail-container">
    <a href="{{ route('notifications.index') }}" class="back-button">
        <i class="fas fa-arrow-left"></i> Retour aux notifications
    </a>

    <div class="notification-card">
        <div class="notification-header">
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
            <div class="notification-title-section">
                <h1 class="notification-title">{{ $notification->title }}</h1>
                <div class="notification-meta">
                    <span class="notification-meta-item">
                        <i class="far fa-clock"></i> {{ $notification->created_at->diffForHumans() }}
                    </span>
                    <span class="notification-meta-item">
                        <i class="fas fa-tag"></i> 
                        @if($notification->type == 'promo')
                            Promotion
                        @elseif($notification->type == 'order')
                            Commande
                        @elseif($notification->type == 'system')
                            Système
                        @else
                            Notification
                        @endif
                    </span>
                    <span class="notification-status {{ is_null($notification->read_at) ? 'status-unread' : 'status-read' }}">
                        {{ is_null($notification->read_at) ? 'Non lue' : 'Lue' }}
                    </span>
                </div>
            </div>
        </div>

        <div class="notification-content">
            <div class="content-section">
                <h3>Message</h3>
                <div class="content-text">
                    {{ $notification->content }}
                </div>
            </div>

            @if(isset($notification->data) && !empty($notification->data))
            <div class="content-section">
                <h3>Détails supplémentaires</h3>
                <div class="content-text">
                    @if(is_array($notification->data) || is_object($notification->data))
                        @foreach($notification->data as $key => $value)
                            • {{ ucfirst($key) }}: {{ $value }}<br>
                        @endforeach
                    @else
                        {{ $notification->data }}
                    @endif
                </div>
            </div>
            @endif
        </div>

        <div class="notification-actions">
            @if(is_null($notification->read_at))
            <form action="{{ route('notifications.markAsRead', $notification->id) }}" method="POST">
                @csrf
                <button type="submit" class="action-btn btn-mark">
                    <i class="fas fa-check"></i> Marquer comme lue
                </button>
            </form>
            @endif
            
            {{-- <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="action-btn btn-delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette notification ?')">
                    <i class="far fa-trash-alt"></i> Supprimer
                </button>
            </form> --}}
        </div>
    </div>

    @if($notification->type == 'order' && isset($notification->data['order_id']))
    <div class="related-content">
        <h2 class="related-title">
            <i class="fas fa-link"></i> Commande associée
        </h2>
        <div class="related-items">
            <div class="related-item">
                <div class="related-item-title">Commande #{{ $notification->data['order_id'] }}</div>
                <div class="related-item-meta">Voir les détails de votre commande</div>
            </div>
        </div>
    </div>
    @endif
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

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f8fafc;
            color: var(--dark);
            line-height: 1.6;
        }

        .notification-detail-container {
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
        }

        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background-color: white;
            border: 1px solid var(--gray-300);
            border-radius: var(--radius);
            color: var(--gray-500);
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
            margin-bottom: 25px;
            box-shadow: var(--shadow);
        }

        .back-button:hover {
            background-color: var(--gray-100);
            color: var(--dark);
            transform: translateY(-2px);
        }

        .notification-card {
            background-color: white;
            border-radius: var(--radius);
            padding: 30px;
            box-shadow: var(--shadow);
            position: relative;
            overflow: hidden;
        }

        .notification-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--gray-200);
        }

        .notification-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            flex-shrink: 0;
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

        .notification-title-section {
            flex: 1;
        }

        .notification-title {
            font-size: 24px;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 5px;
        }

        .notification-meta {
            display: flex;
            align-items: center;
            gap: 15px;
            color: var(--gray-500);
            font-size: 14px;
        }

        .notification-meta-item {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .notification-status {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-unread {
            background-color: rgba(245, 6, 196, 0.1);
            color: var(--primary);
        }

        .status-read {
            background-color: var(--gray-200);
            color: var(--gray-500);
        }

        .notification-content {
            margin-bottom: 30px;
        }

        .content-section {
            margin-bottom: 25px;
        }

        .content-section h3 {
            font-size: 16px;
            color: var(--gray-500);
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .content-text {
            font-size: 16px;
            line-height: 1.7;
            color: var(--dark);
            background-color: var(--gray-100);
            padding: 20px;
            border-radius: var(--radius);
            white-space: pre-line;
        }

        .notification-actions {
            display: flex;
            gap: 15px;
            padding-top: 20px;
            border-top: 1px solid var(--gray-200);
        }

        .action-btn {
            padding: 12px 20px;
            border-radius: var(--radius);
            border: none;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-mark {
            background-color: var(--primary);
            color: white;
        }

        .btn-mark:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(245, 6, 196, 0.3);
        }

        .btn-delete {
            background-color: var(--gray-200);
            color: var(--gray-600);
        }

        .btn-delete:hover {
            background-color: var(--danger);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }

        .related-content {
            margin-top: 30px;
            background-color: white;
            border-radius: var(--radius);
            padding: 25px;
            box-shadow: var(--shadow);
        }

        .related-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 20px;
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .related-items {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 15px;
        }

        .related-item {
            padding: 15px;
            border: 1px solid var(--gray-200);
            border-radius: var(--radius);
            transition: var(--transition);
        }

        .related-item:hover {
            border-color: var(--primary);
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .related-item-title {
            font-weight: 600;
            margin-bottom: 5px;
            color: var(--dark);
        }

        .related-item-meta {
            font-size: 13px;
            color: var(--gray-500);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .notification-detail-container {
                margin: 20px auto;
                padding: 15px;
            }
            
            .notification-header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .notification-meta {
                flex-wrap: wrap;
            }
            
            .notification-actions {
                flex-direction: column;
            }
            
            .action-btn {
                justify-content: center;
            }
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .notification-card {
            animation: fadeIn 0.4s ease-out;
        }
    </style>

<script>
        document.addEventListener('DOMContentLoaded', function() {
            // Marquer comme lue
            const markAsReadBtn = document.querySelector('.btn-mark');
            if (markAsReadBtn) {
                markAsReadBtn.addEventListener('click', function() {
                    const statusElement = document.querySelector('.notification-status');
                    if (statusElement) {
                        statusElement.textContent = 'Lue';
                        statusElement.classList.remove('status-unread');
                        statusElement.classList.add('status-read');
                    }
                    
                    this.innerHTML = '<i class="fas fa-check-circle"></i> Marqué comme lue';
                    this.disabled = true;
                    
                    // Simuler un envoi au serveur
                    setTimeout(() => {
                        // Ici, vous ajouteriez la logique pour marquer la notification comme lue en base de données
                        console.log('Notification marquée comme lue');
                    }, 500);
                });
            }
            
            // Supprimer la notification
            const deleteBtn = document.querySelector('.btn-delete');
            if (deleteBtn) {
                deleteBtn.addEventListener('click', function() {
                    if (confirm('Êtes-vous sûr de vouloir supprimer cette notification ?')) {
                        // Animation de suppression
                        const card = document.querySelector('.notification-card');
                        card.style.opacity = '0';
                        card.style.transform = 'translateY(20px)';
                        
                        // Simuler un envoi au serveur
                        setTimeout(() => {
                            // Ici, vous ajouteriez la logique pour supprimer la notification de la base de données
                            window.location.href = "{{ route('notifications.index') }}";
                        }, 500);
                    }
                });
            }
        });
    </script>
@endsection
