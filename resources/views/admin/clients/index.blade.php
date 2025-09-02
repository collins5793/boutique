@extends('layouts.admins.admin')

@section('content')
<!-- Lucide Icons -->
<script src="https://unpkg.com/lucide@latest"></script>

<style>
/* :root {
    --primary: #4361ee;
    --primary-light: #4895ef;
    --primary-dark: #3f37c9;
    --secondary: #7209b7;
    --accent: #f72585;
    --success: #4cc9f0;
    --warning: #f8961e;
    --danger: #f94144;
    --info: #560bad;
    --dark: #1e293b;
    --dark-light: #334155;
    --light: #ffffff;
    --bg-light: #f8fafc;
    --text-muted: #64748b;
    --border-color: #e2e8f0;
    --radius: 12px;
    --card-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
    --transition: all 0.3s ease;
} */

.container {
    padding: 2rem;
    background: var(--bg-light);
    min-height: 100vh;
}

/* Header */
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1.5rem;
    margin-bottom: 2rem;
    background: var(--light);
    padding: 1.5rem;
    border-radius: var(--radius);
    box-shadow: var(--card-shadow);
}

.page-header h1 {
    font-size: 1.9rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    color: var(--dark);
    margin: 0;
}

.page-header .flex {
    display: flex;
    align-items: center;
    gap: 1rem;
    flex-wrap: wrap;
}

.page-header input {
    padding: 0.75rem 1rem;
    border-radius: var(--radius);
    border: 1px solid var(--border-color);
    font-size: 0.95rem;
    min-width: 280px;
    transition: var(--transition);
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
}

.page-header input:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
}

.page-header button {
    background: var(--primary);
    color: white;
    border-radius: var(--radius);
    padding: 0.75rem 1.5rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    transition: var(--transition);
    cursor: pointer;
    border: none;
    box-shadow: 0 4px 6px rgba(67, 97, 238, 0.2);
}

.page-header button:hover {
    background: var(--primary-dark);
    transform: translateY(-2px);
    box-shadow: 0 7px 14px rgba(67, 97, 238, 0.3);
}

/* KPI Cards */
.kpi-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2.5rem;
}

.kpi-card {
    background: var(--light);
    border-radius: var(--radius);
    padding: 1.5rem;
    text-align: center;
    box-shadow: var(--card-shadow);
    transition: var(--transition);
    position: relative;
    overflow: hidden;
}

.kpi-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--primary);
}

.kpi-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.15);
}

.kpi-card:nth-child(2)::before { background: var(--success); }
.kpi-card:nth-child(3)::before { background: var(--danger); }
.kpi-card:nth-child(4)::before { background: var(--warning); }
.kpi-card:nth-child(5)::before { background: var(--info); }
.kpi-card:nth-child(6)::before { background: var(--secondary); }

.kpi-card i {
    width: 32px;
    height: 32px;
    stroke-width: 1.5;
    margin-bottom: 0.75rem;
    color: var(--primary);
}

.kpi-card:nth-child(2) i { color: var(--success); }
.kpi-card:nth-child(3) i { color: var(--danger); }
.kpi-card:nth-child(4) i { color: var(--warning); }
.kpi-card:nth-child(5) i { color: var(--info); }
.kpi-card:nth-child(6) i { color: var(--secondary); }

.kpi-card span {
    display: block;
    font-size: 1.8rem;
    font-weight: 800;
    margin: 0.5rem 0;
    color: var(--dark);
    letter-spacing: -0.5px;
}

.kpi-label {
    font-size: 0.9rem;
    color: var(--text-muted);
    font-weight: 500;
}

/* Table */
.table-container {
    background: var(--light);
    border-radius: var(--radius);
    padding: 1.5rem;
    box-shadow: var(--card-shadow);
    overflow-x: auto;
}

.data-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    font-size: 0.95rem;
}

.data-table th {
    background: var(--bg-light);
    padding: 1rem 1.25rem;
    text-align: left;
    font-weight: 600;
    color: var(--dark);
    border-bottom: 2px solid var(--border-color);
    position: sticky;
    top: 0;
}

.data-table td {
    padding: 1.25rem;
    border-bottom: 1px solid var(--border-color);
    vertical-align: middle;
}

.data-table tr:last-child td {
    border-bottom: none;
}

.data-table tr {
    transition: var(--transition);
}

.data-table tr:hover {
    background: #f1f5f9;
}

/* Badges */
.badge {
    padding: 0.5rem 1rem;
    border-radius: 50px;
    font-size: 0.8rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.badge-active {
    background: rgba(76, 201, 240, 0.15);
    color: #0e7490;
}

.badge-inactive {
    background: rgba(249, 65, 68, 0.15);
    color: #b91c1c;
}

/* Action buttons */
.action-buttons {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.btnView, .btnToggleStatus, .btnDelete {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    font-weight: 500;
    padding: 0.5rem 0.75rem;
    border-radius: var(--radius);
    transition: var(--transition);
    cursor: pointer;
    border: none;
    font-size: 0.85rem;
}

.btnView {
    background: rgba(59, 130, 246, 0.1);
    color: #1d4ed8;
}

.btnView:hover {
    background: rgba(59, 130, 246, 0.2);
}

.btnToggleStatus {
    background: rgba(248, 150, 30, 0.1);
    color: #d97706;
}

.btnToggleStatus:hover {
    background: rgba(248, 150, 30, 0.2);
}

.btnDelete {
    background: rgba(239, 68, 68, 0.1);
    color: #dc2626;
}

.btnDelete:hover {
    background: rgba(239, 68, 68, 0.2);
}

/* Pagination */
.pagination {
    display: flex;
    justify-content: center;
    margin-top: 2rem;
    gap: 0.5rem;
}

.pagination li {
    display: inline-flex;
}

.pagination a, .pagination span {
    padding: 0.5rem 1rem;
    border-radius: var(--radius);
    border: 1px solid var(--border-color);
    color: var(--dark);
    font-weight: 500;
    transition: var(--transition);
}

.pagination a:hover {
    background: var(--primary);
    color: white;
    border-color: var(--primary);
}

.pagination .active span {
    background: var(--primary);
    color: white;
    border-color: var(--primary);
}

/* Modal amélioré */
#clientModal {
    display: flex;
    align-items: center;
    justify-content: center;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.7);
    z-index: 1000;
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.3s ease, visibility 0.3s ease;
    backdrop-filter: blur(5px);
}

#clientModal:not(.hidden) {
    opacity: 1;
    visibility: visible;
}

.modal-content {
    background: var(--light);
    border-radius: var(--radius);
    padding: 0;
    max-width: 700px;
    width: 90%;
    max-height: 90vh;
    overflow-y: auto;
    position: relative;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    transform: scale(0.95) translateY(20px);
    transition: transform 0.4s cubic-bezier(0.18, 1.25, 0.4, 1), opacity 0.3s ease;
    opacity: 0;
}

#clientModal:not(.hidden) .modal-content {
    transform: scale(1) translateY(0);
    opacity: 1;
}

.modal-header {
    padding: 1.5rem 2rem;
    border-bottom: 1px solid var(--border-color);
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: var(--bg-light);
    border-radius: var(--radius) var(--radius) 0 0;
}

.modal-header h2 {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--dark);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.modal-header h2 i {
    color: var(--primary);
}

#closeModal {
    background: transparent;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
    color: var(--text-muted);
    transition: var(--transition);
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

#closeModal:hover {
    color: var(--danger);
    background: rgba(249, 65, 68, 0.1);
    transform: rotate(90deg);
}

.modal-body {
    padding: 2rem;
}

.client-info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.info-card {
    background: var(--bg-light);
    padding: 1.5rem;
    border-radius: var(--radius);
    border-left: 4px solid var(--primary);
}

.info-card h3 {
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--text-muted);
    margin: 0 0 0.5rem 0;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.info-card p {
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--dark);
    margin: 0;
}

.orders-section {
    margin-top: 2rem;
}

.orders-section h3 {
    font-size: 1.2rem;
    font-weight: 600;
    color: var(--dark);
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.orders-section h3 i {
    color: var(--primary);
}

.orders-list {
    background: var(--bg-light);
    border-radius: var(--radius);
    overflow: hidden;
}

.order-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 1.5rem;
    border-bottom: 1px solid var(--border-color);
    transition: var(--transition);
}

.order-item:last-child {
    border-bottom: none;
}

.order-item:hover {
    background: rgba(67, 97, 238, 0.05);
}

.order-number {
    font-weight: 600;
    color: var(--dark);
}

.order-amount {
    font-weight: 700;
    color: var(--primary);
}

.total-amount {
    background: var(--primary);
    color: white;
    padding: 1rem 1.5rem;
    font-weight: 700;
    text-align: right;
    border-radius: 0 0 var(--radius) var(--radius);
}

/* Responsive */
@media (max-width: 1024px) {
    .kpi-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media (max-width: 768px) {
    .container {
        padding: 1rem;
    }
    
    .page-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .page-header .flex {
        width: 100%;
    }
    
    .page-header input {
        min-width: auto;
        flex: 1;
    }
    
    .kpi-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .action-buttons {
        flex-direction: column;
    }
    
    .modal-content {
        width: 95%;
        margin: 1rem;
    }
    
    .client-info-grid {
        grid-template-columns: 1fr;
    }
    
    .modal-body {
        padding: 1.5rem;
    }
}

@media (max-width: 640px) {
    .kpi-grid {
        grid-template-columns: 1fr;
    }
    
    .data-table {
        font-size: 0.85rem;
    }
    
    .data-table th, 
    .data-table td {
        padding: 0.75rem;
    }
    
    .modal-header {
        padding: 1rem 1.5rem;
    }
    
    .modal-header h2 {
        font-size: 1.25rem;
    }
}
</style>

<div class="container">
    <!-- Header -->
    <div class="page-header">
        <h1><i data-lucide="users"></i> Gestion des Clients</h1>
        <div class="flex">
            <input type="text" id="search" placeholder="Rechercher par nom, email ou téléphone...">
            <button id="btnAddClient"><i data-lucide="plus"></i> Nouveau Client</button>
        </div>
    </div>

    <!-- KPI -->
    <div class="kpi-grid">
        <div class="kpi-card">
            <i data-lucide="users"></i>
            <span>{{ $totalClients }}</span>
            <div class="kpi-label">Total Clients</div>
        </div>
        <div class="kpi-card">
            <i data-lucide="user-check"></i>
            <span>{{ $activeClients }}</span>
            <div class="kpi-label">Clients Actifs</div>
        </div>
        <div class="kpi-card">
            <i data-lucide="user-x"></i>
            <span>{{ $inactiveClients }}</span>
            <div class="kpi-label">Clients Inactifs</div>
        </div>
        <div class="kpi-card">
            <i data-lucide="star"></i>
            <span>{{ $vipClients }}</span>
            <div class="kpi-label">Clients VIP</div>
        </div>
        <div class="kpi-card">
            <i data-lucide="user-plus"></i>
            <span>{{ $recentClients }}</span>
            <div class="kpi-label">Nouveaux Clients</div>
        </div>
        <div class="kpi-card">
            <i data-lucide="clock"></i>
            <span>{{ $pendingOrders }}</span>
            <div class="kpi-label">Commandes en attente</div>
        </div>
    </div>

    <!-- Table -->
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Contact</th>
                    <th>Commandes</th>
                    <th>Total Dépensé</th>
                    <th>Inscription</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="clientsTable">
                @foreach($clients as $client)
                <tr>
                    <td>
                        <div class="font-weight-600">{{ $client->name }}</div>
                    </td>
                    <td>
                        <div>{{ $client->email }}</div>
                        <div class="text-muted">{{ $client->phone }}</div>
                    </td>
                    <td>
                        <div class="text-center">{{ $client->orders_count }}</div>
                    </td>
                    <td>
                        <div class="font-weight-600">{{ number_format($client->orders->sum('total_amount'), 2) }} FCFA</div>
                    </td>
                    <td>{{ $client->created_at->format('d/m/Y') }}</td>
                    <td>
                        <span class="badge {{ $client->status === 'active' ? 'badge-active' : 'badge-inactive' }}">
                            <i data-lucide="{{ $client->status === 'active' ? 'check-circle' : 'x-circle' }}"></i>
                            {{ ucfirst($client->status) }}
                        </span>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <button class="btnView" data-id="{{ $client->id }}"><i data-lucide="eye"></i> Voir</button>
                            <button class="btnToggleStatus" data-id="{{ $client->id }}"><i data-lucide="{{ $client->status === 'active' ? 'toggle-left' : 'toggle-right' }}"></i> {{ $client->status === 'active' ? 'Désactiver' : 'Activer' }}</button>
                            <button class="btnDelete" data-id="{{ $client->id }}"><i data-lucide="trash-2"></i> Supprimer</button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="mt-4">{{ $clients->links() }}</div>
    </div>
</div>

<!-- Modal amélioré -->
<div id="clientModal" class="hidden fixed inset-0 bg-black bg-opacity-50">
    <div class="modal-content">
        <div class="modal-header">
            <h2><i data-lucide="user"></i> Détails du Client</h2>
            <button id="closeModal">✕</button>
        </div>
        <div class="modal-body">
            <div id="modalContent"></div>
        </div>
    </div>
</div>

<script>
    lucide.createIcons(); // active toutes les icônes

    // Recherche AJAX
    document.getElementById('search').addEventListener('keyup', function(){
        let search = this.value;
        window.location.href = "{{ route('admin.clients') }}" + "?search=" + search;
    });

    // Voir Profil - Version améliorée
    document.querySelectorAll('.btnView').forEach(btn => {
        btn.addEventListener('click', function(){
            let id = this.dataset.id;
            fetch("{{ url('admin/clients') }}/" + id)
            .then(res => res.json())
            .then(data => {
                let html = `
                    <div class="client-info-grid">
                        <div class="info-card">
                            <h3>Nom complet</h3>
                            <p>${data.name}</p>
                        </div>
                        <div class="info-card">
                            <h3>Email</h3>
                            <p>${data.email || 'Non renseigné'}</p>
                        </div>
                        <div class="info-card">
                            <h3>Téléphone</h3>
                            <p>${data.phone || 'Non renseigné'}</p>
                        </div>
                        <div class="info-card">
                            <h3>Statut</h3>
                            <p>${data.status === 'active' ? 'Actif' : 'Inactif'}</p>
                        </div>
                    </div>
                    
                    <div class="orders-section">
                        <h3><i data-lucide="shopping-bag"></i> Commandes (${data.orders.length})</h3>
                        <div class="orders-list">
                `;
                
                data.orders.forEach(order => {
                    html += `
                        <div class="order-item">
                            <span class="order-number">#${order.order_number}</span>
                            <span class="order-amount">${order.total_amount} FCFA</span>
                        </div>
                    `;
                });
                
                const totalAmount = data.orders.reduce((sum, o) => sum + parseFloat(o.total_amount), 0);
                
                html += `
                            <div class="total-amount">
                                Total dépensé: ${totalAmount.toFixed(2)} FCFA
                            </div>
                        </div>
                    </div>
                `;
                
                document.getElementById('modalContent').innerHTML = html;
                document.getElementById('clientModal').classList.remove('hidden');
                lucide.createIcons(); // Re-créer les icônes dans le modal
            });
        });
    });

    document.getElementById('closeModal').addEventListener('click', function(){
        document.getElementById('clientModal').classList.add('hidden');
    });

    // Activer / Désactiver
    document.querySelectorAll('.btnToggleStatus').forEach(btn => {
        btn.addEventListener('click', function(){
            let id = this.dataset.id;
            fetch("{{ url('admin/clients') }}/" + id + "/toggle-status", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            }).then(res => res.json()).then(data => {
                location.reload();
            });
        });
    });
</script>
@endsection