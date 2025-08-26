@extends('layouts.clients.client')

@section('title', 'Mes Commandes')

@section('content')
<style>
/* ----------- VARIABLES ----------- */
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

/* ----------- STYLES DE BASE ----------- */
.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 1.5rem;
}

/* ----------- TITRE ----------- */
h2 {
    color: var(--dark);
    font-size: 2rem;
    margin-bottom: 1.5rem;
    font-weight: 700;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid var(--primary-light);
}

/* ----------- STATISTIQUES ----------- */
.row {
    display: flex;
    flex-wrap: wrap;
    margin: 0 -0.75rem 2rem;
    gap: 1rem;
}

.col-md-2 {
    flex: 0 0 calc(20% - 1rem);
    max-width: calc(20% - 1rem);
}

.card {
    background: var(--light);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    transition: var(--transition);
    border: none;
    overflow: hidden;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
}

.card-body {
    padding: 1.5rem;
    text-align: center;
}

.text-muted {
    color: var(--dark-light) !important;
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
    display: block;
}

.badge {
    display: inline-block;
    padding: 0.5rem 1rem;
    border-radius: 50px;
    font-weight: 600;
    font-size: 1.1rem;
}

.bg-primary {
    background: var(--primary) !important;
    color: var(--light);
}

/* ----------- FILTRES ----------- */
form {
    display: flex;
    gap: 1rem;
    margin-bottom: 2rem;
    flex-wrap: wrap;
    align-items: center;
}

.form-control, .form-select {
    padding: 0.75rem 1rem;
    border: 1px solid #ddd;
    border-radius: var(--radius);
    font-size: 1rem;
    outline: none;
    transition: var(--transition);
    flex: 1;
    min-width: 200px;
}

.form-control:focus, .form-select:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(245, 6, 196, 0.1);
}

.btn {
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: var(--radius);
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition);
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.btn-primary {
    background: var(--primary);
    color: var(--light);
}

.btn-primary:hover {
    background: var(--primary-dark);
}

/* ----------- TABLEAU COMMANDES ----------- */
.table-responsive {
    overflow-x: auto;
    border-radius: var(--radius);
}

table {
    width: 100%;
    border-collapse: collapse;
}

thead {
    background: var(--dark);
}

th {
    padding: 1rem;
    text-align: left;
    color: var(--light);
    font-weight: 600;
    border: none;
}

tbody tr {
    border-bottom: 1px solid #eee;
    transition: var(--transition);
}

tbody tr:hover {
    background: #f9f9f9;
}

td {
    padding: 1rem;
    vertical-align: middle;
}

/* ----------- BADGES DE STATUT ----------- */
.bg-warning {
    background: #f39c12 !important;
    color: white;
}

.bg-info {
    background: #3498db !important;
    color: white;
}

.bg-success {
    background: #27ae60 !important;
    color: white;
}

.bg-danger {
    background: #e74c3c !important;
    color: white;
}

/* ----------- BOUTONS D'ACTION ----------- */
.btn-sm {
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
}

.btn-outline-primary {
    background: transparent;
    border: 1px solid var(--primary);
    color: var(--primary);
}

.btn-outline-primary:hover {
    background: var(--primary);
    color: var(--light);
}

/* ----------- PAGINATION ----------- */
.mt-3 {
    margin-top: 1.5rem;
}

.pagination {
    display: flex;
    gap: 0.5rem;
    justify-content: center;
    list-style: none;
    padding: 0;
}

.page-item {
    display: flex;
}

.page-link {
    padding: 0.5rem 1rem;
    border-radius: var(--radius);
    border: 1px solid #ddd;
    color: var(--dark);
    text-decoration: none;
    transition: var(--transition);
}

.page-item.active .page-link,
.page-link:hover {
    background: var(--primary);
    color: var(--light);
    border-color: var(--primary);
}

.page-item.disabled .page-link {
    color: #aaa;
    cursor: not-allowed;
}

/* ----------- MODAL ----------- */
.modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.7);
    z-index: 1000;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1rem;
    opacity: 0;
    visibility: hidden;
    transition: var(--transition);
}

.modal.show {
    opacity: 1;
    visibility: visible;
}

.modal-dialog {
    width: 100%;
    max-width: 800px;
    background: var(--light);
    border-radius: var(--radius);
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    transform: translateY(-20px);
    transition: var(--transition);
}

.modal.show .modal-dialog {
    transform: translateY(0);
}

.modal-content {
    border: none;
    border-radius: var(--radius);
}

.modal-header {
    padding: 1.5rem;
    border-bottom: 1px solid #eee;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-title {
    margin: 0;
    color: var(--dark);
    font-weight: 600;
}

.btn-close {
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
    color: var(--dark-light);
    transition: var(--transition);
}

.btn-close:hover {
    color: var(--dark);
}

.modal-body {
    padding: 1.5rem;
    max-height: 70vh;
    overflow-y: auto;
}

/* ----------- RESPONSIVE ----------- */
@media (max-width: 1024px) {
    .col-md-2 {
        flex: 0 0 calc(25% - 1rem);
        max-width: calc(25% - 1rem);
    }
    
    form {
        flex-direction: column;
        align-items: stretch;
    }
    
    .form-control, .form-select {
        min-width: 100%;
    }
}

@media (max-width: 768px) {
    .container {
        padding: 1rem;
    }
    
    .col-md-2 {
        flex: 0 0 calc(33.333% - 1rem);
        max-width: calc(33.333% - 1rem);
    }
    
    h2 {
        font-size: 1.75rem;
    }
    
    table {
        font-size: 0.9rem;
    }
    
    th, td {
        padding: 0.75rem 0.5rem;
    }
    
    .badge {
        font-size: 0.9rem;
        padding: 0.4rem 0.8rem;
    }
}

@media (max-width: 576px) {
    .col-md-2 {
        flex: 0 0 calc(50% - 1rem);
        max-width: calc(50% - 1rem);
    }
    
    h2 {
        font-size: 1.5rem;
    }
    
    .card-body {
        padding: 1rem;
    }
    
    .modal-header, .modal-body {
        padding: 1rem;
    }
    
    table {
        font-size: 0.8rem;
    }
    
    th, td {
        padding: 0.5rem 0.25rem;
    }
    
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.8rem;
    }
}

/* ----------- ÉTATS VIDE ----------- */
.text-center {
    text-align: center;
}

.text-muted {
    color: var(--dark-light);
}
</style>
<div class="container">

    {{-- Statistiques --}}
    <h2 class="mb-4">🛍 Mes commndes</h2>
    <div class="row mb-4">
        @foreach($stats as $key => $value)
            <div class="col-md-2">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <h6 class="text-muted">{{ ucfirst($key) }}</h6>
                        <span class="badge bg-primary fs-6">{{ $value }}</span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Filtres --}}
    <form method="GET" class="mb-4 d-flex gap-2 flex-wrap">
        <input type="text" name="search" class="form-control" placeholder="🔍 Rechercher une commande..."
               value="{{ request('search') }}">

        <select name="status" class="form-select">
            <option value="">-- Statut --</option>
            <option value="pending">En attente</option>
            <option value="processing">En cours</option>
            <option value="shipped">Expédiée</option>
            <option value="delivered">Livrée</option>
            <option value="cancelled">Annulée</option>
        </select>

        <select name="date" class="form-select">
            <option value="">-- Date --</option>
            <option value="30days">30 derniers jours</option>
            <option value="month">Ce mois-ci</option>
            <option value="year">Cette année</option>
        </select>

        <button class="btn btn-primary">Filtrer</button>
    </form>

    {{-- Tableau commandes --}}
    <div class="card shadow-sm">
        <div class="card-body table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#Commande</th>
                        <th>Date</th>
                        <th>Total</th>
                        <th>Statut</th>
                        <th>Paiement</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td>{{ $order->order_number }}</td>
                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ number_format($order->total_amount, 0, ',', ' ') }} CFA</td>
                            <td>
                                <span class="badge bg-{{ $order->order_status == 'pending' ? 'warning' :
                                                         ($order->order_status == 'processing' ? 'info' :
                                                         ($order->order_status == 'shipped' ? 'primary' :
                                                         ($order->order_status == 'delivered' ? 'success' : 'danger'))) }}">
                                    {{ ucfirst($order->order_status) }}
                                </span>
                            </td>
                            <td>{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</td>
                            <td>
                                <td>
                                    <a href="{{ route('client.ordershow', $order->id) }}" class="btn btn-sm btn-outline-primary">
                                        🔍 Voir détail
                                    </a>
                                </td>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center">Aucune commande trouvée</td></tr>
                    @endforelse
                </tbody>
            </table>

            
        </div>
    </div>
</div>



<script>
function showOrder(id) {
    fetch(/dashboard/orders/${id})
        .then(res => res.json())
        .then(order => {
            let html = `
                <p><b>Numéro :</b> ${order.order_number}</p>
                <p><b>Date :</b> ${new Date(order.created_at).toLocaleString()}</p>
                <p><b>Statut :</b> ${order.order_status}</p>
                <p><b>Moyen de paiement :</b> ${order.payment_method}</p>
                <p><b>Adresse :</b> ${order.delivery_address?.address ?? 'Non renseignée'}</p>
                <hr>
                <h5>Produits</h5>
                <table class="table table-sm">
                    <thead class="table-light">
                        <tr><th>Produit</th><th>Qté</th><th>Prix</th><th>Total</th></tr>
                    </thead>
                    <tbody>
                        ${order.items.map(item => `
                            <tr>
                                <td>${item.product?.name ?? ''}</td>
                                <td>${item.quantity}</td>
                                <td>${item.price} CFA</td>
                                <td>${item.total} CFA</td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
                <h5 class="text-end text-primary">Total : ${order.total_amount} CFA</h5>
            `;
            document.getElementById('order-details').innerHTML = html;
        })
        .catch(err => {
            document.getElementById('order-details').innerHTML = 
              <p class="text-danger">Erreur lors du chargement de la commande.</p>;
        });
}
</script>
@endsection