@extends('layouts.admins.admin')

@section('content')
<div class="container mx-auto p-4">

    <!-- Header -->
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-bold flex items-center"><i class="fas fa-users mr-2"></i> Clients</h1>
        <div class="flex space-x-2">
            <input type="text" id="search" placeholder="Rechercher par nom, email ou téléphone" class="border rounded p-2">
            <button id="btnAddClient" class="bg-blue-500 text-white p-2 rounded">Ajouter Client</button>
        </div>
    </div>

    <!-- KPI -->
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">
        <div class="bg-white p-4 rounded shadow text-center">
            <div class="text-gray-500">Total Clients</div>
            <div class="text-xl font-bold">{{ $totalClients }}</div>
        </div>
        <div class="bg-green-100 p-4 rounded shadow text-center">
            <div class="text-gray-500">Actifs</div>
            <div class="text-xl font-bold">{{ $activeClients }}</div>
        </div>
        <div class="bg-red-100 p-4 rounded shadow text-center">
            <div class="text-gray-500">Inactifs</div>
            <div class="text-xl font-bold">{{ $inactiveClients }}</div>
        </div>
        <div class="bg-yellow-100 p-4 rounded shadow text-center">
            <div class="text-gray-500">VIP</div>
            <div class="text-xl font-bold">{{ $vipClients }}</div>
        </div>
        <div class="bg-purple-100 p-4 rounded shadow text-center">
            <div class="text-gray-500">Nouveaux</div>
            <div class="text-xl font-bold">{{ $recentClients }}</div>
        </div>
        <div class="bg-orange-100 p-4 rounded shadow text-center">
            <div class="text-gray-500">Commandes en attente</div>
            <div class="text-xl font-bold">{{ $pendingOrders }}</div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white p-4 rounded shadow">
        <table class="min-w-full border-collapse table-auto">
            <thead>
                <tr class="bg-gray-100 text-left">
                    <th class="p-2">Nom</th>
                    <th>Email / Téléphone</th>
                    <th>Commandes</th>
                    <th>Total Dépensé</th>
                    <th>Inscription</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="clientsTable">
                @foreach($clients as $client)
                <tr class="border-b">
                    <td>{{ $client->name }}</td>
                    <td>{{ $client->email }}<br>{{ $client->phone }}</td>
                    <td>{{ $client->orders_count }}</td>
                    <td>
                        {{ number_format($client->orders->sum('total_amount'), 2) }} FCFA
                    </td>
                    <td>{{ $client->created_at->format('d/m/Y') }}</td>
                    <td>
                        <span class="px-2 py-1 rounded {{ $client->status === 'active' ? 'bg-green-200' : 'bg-red-200' }}">
                            {{ ucfirst($client->status) }}
                        </span>
                    </td>
                    <td class="space-x-2">
                        <button class="btnView text-blue-500" data-id="{{ $client->id }}">Voir</button>
                        <button class="btnToggleStatus text-yellow-500" data-id="{{ $client->id }}">
                            {{ $client->status === 'active' ? 'Désactiver' : 'Activer' }}
                        </button>
                        <button class="btnDelete text-red-500" data-id="{{ $client->id }}">Supprimer</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $clients->links() }}
        </div>
    </div>
</div>

<!-- Modal Profil Client -->
<div id="clientModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
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
    // Recherche AJAX
    document.getElementById('search').addEventListener('keyup', function(){
        let search = this.value;
        window.location.href = "{{ route('admin.clients') }}" + "?search=" + search;
    });

    // Voir Profil
    document.querySelectorAll('.btnView').forEach(btn => {
        btn.addEventListener('click', function(){
            let id = this.dataset.id;
            fetch("{{ url('admin/clients') }}/" + id)
            .then(res => res.json())
            .then(data => {
                let html = `
                    <h2 class="text-xl font-bold mb-2">${data.name}</h2>
                    <p>Email: ${data.email}</p>
                    <p>Téléphone: ${data.phone}</p>
                    <p>Statut: ${data.status}</p>
                    <h3 class="mt-4 font-bold">Commandes:</h3>
                    <ul>
                        ${data.orders.map(o => `<li>#${o.order_number} - ${o.total_amount} FCFA</li>`).join('')}
                    </ul>
                    <p class="mt-2">Total Dépensé: ${data.orders.reduce((sum,o)=>sum+parseFloat(o.total_amount),0)} FCFA</p>
                `;
                document.getElementById('modalContent').innerHTML = html;
                document.getElementById('clientModal').classList.remove('hidden');
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
