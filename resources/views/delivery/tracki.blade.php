@extends('layouts.livreurs.livreur')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #7c3aed;
            --primary-light: #8b5cf6;
            --secondary: #0ea5e9;
            --success: #10b981;
            --dark: #1e293b;
            --light-bg: #f8fafc;
            --radius: 12px;
            --shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        
        body {
            background-color: #f5f7fb;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #334155;
            padding: 20px;
        }
        
        .page-container {
            max-width: 800px;
            margin: 0 auto;
        }
        
        .page-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 25px;
        }
        
        .page-icon {
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.8rem;
        }
        
        .page-title {
            color: var(--dark);
            font-weight: 700;
            margin: 0;
        }
        
        .info-card {
            background: white;
            border-radius: var(--radius);
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: var(--shadow);
        }
        
        .info-card h4 {
            color: var(--primary);
            margin-bottom: 20px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .info-item {
            display: flex;
            margin-bottom: 15px;
        }
        
        .info-icon {
            width: 40px;
            height: 40px;
            background: var(--light-bg);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            color: var(--primary);
            flex-shrink: 0;
        }
        
        .info-content {
            flex: 1;
        }
        
        .info-label {
            font-size: 0.85rem;
            color: #64748b;
            margin-bottom: 5px;
        }
        
        .info-value {
            font-weight: 500;
            font-size: 1.05rem;
        }
        
        .action-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 25px;
        }
        
        .btn-deliver {
            background: linear-gradient(135deg, var(--success), #34d399);
            color: white;
            border: none;
            border-radius: 10px;
            padding: 15px 25px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
            flex: 1;
            justify-content: center;
            transition: all 0.3s;
            box-shadow: 0 4px 6px rgba(16, 185, 129, 0.3);
        }
        
        .btn-deliver:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(16, 185, 129, 0.4);
        }
        
        .btn-success {
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            color: white;
            border: none;
            border-radius: 10px;
            padding: 15px 25px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
            flex: 1;
            justify-content: center;
            transition: all 0.3s;
            box-shadow: 0 4px 6px rgba(124, 58, 237, 0.3);
        }
        
        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(124, 58, 237, 0.4);
        }
        
        .status-card {
            background: white;
            border-radius: var(--radius);
            padding: 25px;
            box-shadow: var(--shadow);
            text-align: center;
            margin-bottom: 25px;
        }
        
        .status-icon {
            font-size: 3rem;
            color: var(--primary);
            margin-bottom: 15px;
        }
        
        .status-title {
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 10px;
        }
        
        .status-description {
            color: #64748b;
            margin-bottom: 20px;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                text-align: center;
                gap: 10px;
            }
            
            .action-buttons {
                flex-direction: column;
            }
        }
        
        @media (max-width: 576px) {
            body {
                padding: 15px;
            }
            
            .info-card, .status-card {
                padding: 20px;
            }
            
            .info-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
            
            .info-icon {
                margin-right: 0;
            }
        }
    </style>
</head>
<body>
    <div class="page-container">
        <div class="page-header">
            <div class="page-icon">
                <i class="fas fa-truck"></i>
            </div>
            <div>
                <h1 class="page-title">Livraison en cours</h1>
            </div>
        </div>

        <div class="status-card">
            <div class="status-icon">
                <i class="fas fa-shipping-fast"></i>
            </div>
            <h3 class="status-title">Livraison en cours</h3>
            <p class="status-description">Vous êtes en train de livrer cette commande au client.</p>
        </div>

        <div class="info-card">
            <h4><i class="fas fa-user-circle"></i> Informations client</h4>
            
            <div class="info-item">
                <div class="info-icon">
                    <i class="fas fa-user"></i>
                </div>
                <div class="info-content">
                    <div class="info-label">Nom</div>
                    <div class="info-value">{{ $order->user->name }}</div>
                </div>
            </div>
            
            <div class="info-item">
                <div class="info-icon">
                    <i class="fas fa-phone"></i>
                </div>
                <div class="info-content">
                    <div class="info-label">Téléphone</div>
                    <div class="info-value">{{ $order->user->phone ?? 'Non renseigné' }}</div>
                </div>
            </div>
            
            <div class="info-item">
                <div class="info-icon">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <div class="info-content">
                    <div class="info-label">Adresse</div>
                    <div class="info-value">{{ $order->deliveryAddress->full_address ?? 'Adresse non précisée' }}</div>
                </div>
            </div>
        </div>

        <div class="action-buttons">
            <form action="{{ route('delive.fin', $order->id) }}" method="POST" class="d-flex flex-fill">
                @csrf
                <button type="submit" class="btn btn-deliver">
                    <i class="fas fa-check-circle"></i> Livrer cette commande
                </button>
            </form>

            <form action="{{ route('delivery.valide', $order->id) }}" method="POST" class="d-flex flex-fill">
                @csrf
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-clipboard-check"></i> Marquer comme terminée
                </button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


@endsection
