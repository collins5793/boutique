@extends('layouts.admins.admin')

@section('content')
<style>
:root {
    --primary: #eb770a;
    --primary-light: #ec8a2f;
    --primary-dark: #be6109;
    --secondary: #007bff;
    --accent: #ff7b00;
    --dark: #1e293b;
    --dark-light: #334155;
    --light: #ffffff;
    --sidebar-width: 280px;
    --sidebar-collapsed: 90px;
    --header-height: 80px;
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    --radius: 12px;
    --shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    --bg-light: #f8fafc;
    --text-muted: #64748b;
    --border-color: #e2e8f0;
}

.container {
    padding: 2rem;
    background: var(--bg-light);
    min-height: calc(100vh - var(--header-height));
}

/* Top Metrics */
.metrics-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2.5rem;
}

.metric-card {
    background: var(--light);
    border-radius: var(--radius);
    padding: 1.5rem;
    text-align: center;
    box-shadow: var(--shadow);
    transition: var(--transition);
    position: relative;
    overflow: hidden;
}

.metric-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--primary);
}

.metric-card:nth-child(1)::before { background: var(--primary); }
.metric-card:nth-child(2)::before { background: var(--accent); }
.metric-card:nth-child(3)::before { background: var(--secondary); }
.metric-card:nth-child(4)::before { background: #10b981; }
.metric-card:nth-child(5)::before { background: #8b5cf6; }

.metric-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.15);
}

.metric-card div:first-child {
    font-size: 0.9rem;
    color: var(--text-muted);
    font-weight: 500;
    margin-bottom: 0.5rem;
}

.metric-card .text-xl {
    font-size: 1.8rem;
    font-weight: 800;
    color: var(--dark);
    letter-spacing: -0.5px;
}

/* Filtres */
.filters-container {
    display: flex;
    align-items: center;
    gap: 1rem;
    flex-wrap: wrap;
    margin-bottom: 2rem;
    background: var(--light);
    padding: 1.5rem;
    border-radius: var(--radius);
    box-shadow: var(--shadow);
}

.filters-container input,
.filters-container select {
    padding: 0.75rem 1rem;
    border-radius: var(--radius);
    border: 1px solid var(--border-color);
    font-size: 0.95rem;
    transition: var(--transition);
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    min-width: 180px;
}

.filters-container input:focus,
.filters-container select:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(235, 119, 10, 0.15);
}

.filters-container a,
.filters-container button {
    padding: 0.75rem 1.5rem;
    border-radius: var(--radius);
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: var(--transition);
    cursor: pointer;
    border: none;
    text-decoration: none;
}

.filters-container a:nth-child(5) {
    background: var(--primary);
    color: white;
    box-shadow: 0 4px 6px rgba(235, 119, 10, 0.2);
}

.filters-container a:nth-child(5):hover {
    background: var(--primary-dark);
    transform: translateY(-2px);
    box-shadow: 0 7px 14px rgba(235, 119, 10, 0.3);
}

.filters-container a:nth-child(6) {
    background: #ef4444;
    color: white;
    box-shadow: 0 4px 6px rgba(239, 68, 68, 0.2);
}

.filters-container a:nth-child(6):hover {
    background: #dc2626;
    transform: translateY(-2px);
    box-shadow: 0 7px 14px rgba(239, 68, 68, 0.3);
}

.filters-container button {
    background: #10b981;
    color: white;
    box-shadow: 0 4px 6px rgba(16, 185, 129, 0.2);
}

.filters-container button:hover {
    background: #059669;
    transform: translateY(-2px);
    box-shadow: 0 7px 14px rgba(16, 185, 129, 0.3);
}

/* Tableau principal */
.table-container {
    background: var(--light);
    border-radius: var(--radius);
    padding: 1.5rem;
    box-shadow: var(--shadow);
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

.product-cell {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.product-image-placeholder {
    width: 50px;
    height: 50px;
    background: var(--bg-light);
    border-radius: var(--radius);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--text-muted);
    border: 1px dashed var(--border-color);
}

.product-image {
    width: 50px;
    height: 50px;
    object-fit: cover;
    border-radius: var(--radius);
    border: 1px solid var(--border-color);
}

/* Badges d'état */
.status-badge {
    padding: 0.5rem 1rem;
    border-radius: 50px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-rupture {
    background: rgba(239, 68, 68, 0.15);
    color: #dc2626;
}

.status-faible {
    background: rgba(245, 158, 11, 0.15);
    color: #d97706;
}

.status-ok {
    background: rgba(16, 185, 129, 0.15);
    color: #059669;
}

/* Boutons d'action */
.action-buttons {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.action-btn {
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
    text-decoration: none;
}

.btn-view {
    background: rgba(59, 130, 246, 0.1);
    color: #1d4ed8;
}

.btn-view:hover {
    background: rgba(59, 130, 246, 0.2);
}

.btn-edit {
    background: rgba(245, 158, 11, 0.1);
    color: #d97706;
}

.btn-edit:hover {
    background: rgba(245, 158, 11, 0.2);
}

.btn-delete {
    background: rgba(239, 68, 68, 0.1);
    color: #dc2626;
}

.btn-delete:hover {
    background: rgba(239, 68, 68, 0.2);
}

.btn-stock {
    background: rgba(16, 185, 129, 0.1);
    color: #059669;
}

.btn-stock:hover {
    background: rgba(16, 185, 129, 0.2);
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

/* Modal Produit */
#productModal {
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

#productModal:not(.hidden) {
    opacity: 1;
    visibility: visible;
}

.modal-content {
    background: var(--light);
    border-radius: var(--radius);
    padding: 0;
    max-width: 600px;
    width: 90%;
    max-height: 90vh;
    overflow-y: auto;
    position: relative;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    transform: scale(0.95) translateY(20px);
    transition: transform 0.4s cubic-bezier(0.18, 1.25, 0.4, 1), opacity 0.3s ease;
    opacity: 0;
}

#productModal:not(.hidden) .modal-content {
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
    color: #ef4444;
    background: rgba(239, 68, 68, 0.1);
    transform: rotate(90deg);
}

.modal-body {
    padding: 2rem;
}

.product-info-grid {
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

/* Responsive */
@media (max-width: 1024px) {
    .metrics-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media (max-width: 768px) {
    .container {
        padding: 1rem;
    }
    
    .metrics-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .filters-container {
        flex-direction: column;
        align-items: stretch;
    }
    
    .filters-container input,
    .filters-container select {
        min-width: auto;
        width: 100%;
    }
    
    .action-buttons {
        flex-direction: column;
    }
    
    .modal-content {
        width: 95%;
        margin: 1rem;
    }
    
    .product-info-grid {
        grid-template-columns: 1fr;
    }
    
    .modal-body {
        padding: 1.5rem;
    }
}

@media (max-width: 640px) {
    .metrics-grid {
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
    <!-- Top Metrics -->
    <div class="metrics-grid">
        <div class="metric-card">
            <div>Produits en stock</div>
            <div class="text-xl">{{ $totalStock }}</div>
        </div>
        <div class="metric-card">
            <div>Faible stock</div>
            <div class="text-xl">{{ $lowStock }}</div>
        </div>
        <div class="metric-card">
            <div>Produits épuisés</div>
            <div class="text-xl">{{ $outStock }}</div>
        </div>
        <div class="metric-card">
            <div>Valeur totale du stock</div>
            <div class="text-xl">{{ number_format($totalValue,2) }} FCFA</div>
        </div>
        <div class="metric-card">
            <div>Produits récents</div>
            <div class="text-xl">{{ $recentProducts->count() }}</div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="filters-container">
        <input type="text" id="search" placeholder="Rechercher produit..." class="border rounded p-2">
        <select id="categoryFilter" class="border rounded p-2">
            <option value="">Toutes catégories</option>
            @foreach($categories as $category)
            <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>
        <select id="stockFilter" class="border rounded p-2">
            <option value="">Tous stocks</option>
            <option value="low">Faible stock</option>
            <option value="out">Épuisé</option>
        </select>
    </div>

    <!-- Tableau principal -->
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Produit</th>
                    <th>Catégorie</th>
                    <th>Stock</th>
                    <th>Prix</th>
                    <th>Valeur totale</th>
                    <th>État</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                <tr>
                    <td>
                        <div class="product-cell">
                            @if($product->image && !empty(trim($product->image)))
                                <img src="{{ asset('storage/' . $product->image) }}" class="product-image" alt="{{ $product->name }}">
                            @else
                                <div class="product-image-placeholder">
                                    <i data-lucide="image"></i>
                                </div>
                            @endif
                            <span>{{ $product->name }}</span>
                        </div>
                    </td>
                    <td>{{ $product->category?->name ?? '-' }}</td>
                    <td>{{ $product->stock_quantity }}</td>
                    <td>{{ number_format($product->price,2) }} FCFA</td>
                    <td>{{ number_format($product->price * $product->stock_quantity,2) }} FCFA</td>
                    <td>
                        @if($product->stock_quantity == 0)
                        <span class="status-badge status-rupture">Rupture</span>
                        @elseif($product->stock_quantity <=5)
                        <span class="status-badge status-faible">Faible</span>
                        @else
                        <span class="status-badge status-ok">OK</span>
                        @endif
                    </td>
                    <td>
                        <div class="action-buttons">
                            <button class="action-btn btn-view btnView" data-id="{{ $product->id }}">Voir</button>
                            <button class="action-btn btn-edit">Modifier</button>
                            <button class="action-btn btn-delete">Supprimer</button>
                            <button class="action-btn btn-stock">Ajouter stock</button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="pagination">
            {{ $products->links() }}
        </div>
    </div>
</div>

<!-- Modal Produit -->
<div id="productModal" class="hidden fixed inset-0 bg-black bg-opacity-50">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Détails du Produit</h2>
            <button id="closeModal">✕</button>
        </div>
        <div class="modal-body">
            <div id="modalContent"></div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Recherche / Filtres
    document.getElementById('search').addEventListener('keyup', function() {
        let search = this.value;
        window.location.href = "{{ route('admin.inventory') }}" + "?search=" + search;
    });

    document.getElementById('categoryFilter').addEventListener('change', function() {
        let category = this.value;
        window.location.href = "{{ route('admin.inventory') }}" + "?category=" + category;
    });

    document.getElementById('stockFilter').addEventListener('change', function() {
        let stock_status = this.value;
        window.location.href = "{{ route('admin.inventory') }}" + "?stock_status=" + stock_status;
    });

    // Voir Produit - Version améliorée
    document.querySelectorAll('.btnView').forEach(btn => {
        btn.addEventListener('click', function(){
            let id = this.dataset.id;
            fetch("{{ url('admin/inventory') }}/" + id)
            .then(res => res.json())
            .then(data => {
                let html = `
                    <div class="product-info-grid">
                        <div class="info-card">
                            <h3>Nom du produit</h3>
                            <p>${data.name}</p>
                        </div>
                        <div class="info-card">
                            <h3>Catégorie</h3>
                            <p>${data.category ? data.category.name : '-'}</p>
                        </div>
                        <div class="info-card">
                            <h3>Stock actuel</h3>
                            <p>${data.stock_quantity} unités</p>
                        </div>
                        <div class="info-card">
                            <h3>Prix unitaire</h3>
                            <p>${data.price} FCFA</p>
                        </div>
                        <div class="info-card">
                            <h3>Valeur totale</h3>
                            <p>${(data.price * data.stock_quantity).toFixed(2)} FCFA</p>
                        </div>
                    </div>
                    
                    <div class="info-card">
                        <h3>Description</h3>
                        <p>${data.description || 'Aucune description disponible'}</p>
                    </div>
                `;
                
                document.getElementById('modalContent').innerHTML = html;
                document.getElementById('productModal').classList.remove('hidden');
            });
        });
    });

    document.getElementById('closeModal').addEventListener('click', function(){
        document.getElementById('productModal').classList.add('hidden');
    });
</script>
@endsection