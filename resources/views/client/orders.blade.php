@extends('layouts.clients.client')

@section('title', 'Mes Commandes')

@section('content')
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

            {{-- Pagination --}}
            <div class="mt-3">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
</div>

{{-- Modal détail --}}
<div class="modal fade" id="orderModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Détails de la commande</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" id="order-details">
        <p class="text-muted">Chargement...</p>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
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