<table class="table table-hover">
    <thead>
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
                <td>{{ number_format($order->total_amount, 2) }} CFA</td>
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
                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                        data-bs-target="#orderModal" onclick="showOrder({{ $order->id }})">
                        🔍 Voir détails
                    </button>
                </td>
            </tr>
        @empty
            <tr><td colspan="6" class="text-center">Aucune commande trouvée</td></tr>
        @endforelse
    </tbody>
</table>

{{ $orders->links() }}