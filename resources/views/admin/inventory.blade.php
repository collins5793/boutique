@extends('layouts.admins.admin')

@section('content')
<div class="container mx-auto p-4">

    <!-- Top Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-6">
        <div class="bg-blue-100 p-4 rounded shadow text-center">
            <div>Produits en stock</div>
            <div class="text-xl font-bold">{{ $totalStock }}</div>
        </div>
        <div class="bg-yellow-100 p-4 rounded shadow text-center">
            <div>Faible stock</div>
            <div class="text-xl font-bold">{{ $lowStock }}</div>
        </div>
        <div class="bg-red-100 p-4 rounded shadow text-center">
            <div>Produits épuisés</div>
            <div class="text-xl font-bold">{{ $outStock }}</div>
        </div>
        <div class="bg-green-100 p-4 rounded shadow text-center">
            <div>Valeur totale du stock</div>
            <div class="text-xl font-bold">{{ number_format($totalValue,2) }} FCFA</div>
        </div>
        <div class="bg-purple-100 p-4 rounded shadow text-center">
            <div>Produits récents</div>
            <div class="text-xl font-bold">{{ $recentProducts->count() }}</div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="flex items-center justify-between mb-4">
        <input type="text" id="search" placeholder="Rechercher produit" class="border rounded p-2">
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
        <a href="{{ route('admin.inventory.export.csv') }}" class="bg-blue-500 text-white p-2 rounded">Exporter CSV</a>
        <a href="{{ route('admin.inventory.export.pdf') }}" class="bg-red-500 text-white p-2 rounded">Exporter PDF</a>
        <button class="bg-green-500 text-white p-2 rounded">Ajouter produit</button>
    </div>

    <!-- Tableau principal -->
    <div class="bg-white p-4 rounded shadow overflow-x-auto">
        <table class="min-w-full border-collapse table-auto">
            <thead>
                <tr class="bg-gray-100">
                    <th class="p-2">Produit</th>
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
                <tr class="border-b">
                    <td class="flex items-center space-x-2">
                        <img src="{{ $product->image }}" class="w-10 h-10 object-cover rounded">
                        <span>{{ $product->name }}</span>
                    </td>
                    <td>{{ $product->category?->name ?? '-' }}</td>
                    <td>{{ $product->stock_quantity }}</td>
                    <td>{{ number_format($product->price,2) }} FCFA</td>
                    <td>{{ number_format($product->price * $product->stock_quantity,2) }} FCFA</td>
                    <td>
                        @if($product->stock_quantity == 0)
                        <span class="px-2 py-1 bg-red-200 rounded">Rupture</span>
                        @elseif($product->stock_quantity <=5)
                        <span class="px-2 py-1 bg-yellow-200 rounded">Faible</span>
                        @else
                        <span class="px-2 py-1 bg-green-200 rounded">OK</span>
                        @endif
                    </td>
                    <td class="space-x-2">
                        <button class="text-blue-500 btnView" data-id="{{ $product->id }}">Voir</button>
                        <button class="text-yellow-500">Modifier</button>
                        <button class="text-red-500">Supprimer</button>
                        <button class="text-green-500">Ajouter stock</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $products->links() }}
        </div>
    </div>
</div>

<!-- Modal Produit -->
<div id="productModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-white p-6 rounded w-11/12 md:w-3/4 lg:w-1/2">
        <button id="closeModal" class="float-right text-red-500">✖</button>
        <div id="modalContent">
            <!-- Contenu AJAX -->
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

    // Voir Produit
    document.querySelectorAll('.btnView').forEach(btn => {
        btn.addEventListener('click', function(){
            let id = this.dataset.id;
            fetch("{{ url('admin/inventory') }}/" + id)
            .then(res => res.json())
            .then(data => {
                let html = `
                    <h2 class="text-xl font-bold mb-2">${data.name}</h2>
                    <p>Catégorie: ${data.category ? data.category.name : '-'}</p>
                    <p>Stock: ${data.stock_quantity}</p>
                    <p>Prix: ${data.price} FCFA</p>
                    <p>Description: ${data.description ?? '-'}</p>
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
