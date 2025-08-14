@extends('layouts.apli')

@section('content')
<div class="container mt-4">
    <h2>📦 Mes Commandes</h2>

    @if($orders->isEmpty())
        <p>Vous n'avez encore passé aucune commande.</p>
    @else
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Numéro de commande</th>
                    <th>Date</th>
                    <th>Total</th>
                    <th>Statut commande</th>
                    <th>Statut paiement</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                    <tr class="viewOrderBtn" data-id="{{ $order->id }}" style="cursor:pointer;">
                        <td>{{ $order->order_number }}</td>
                        <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ number_format($order->total_amount, 0, ',', ' ') }} FCFA</td>
                        <td>
                            @php
                                $statusColor = match($order->order_status) {
                                    'pending' => 'warning',
                                    'processing' => 'info',
                                    'shipped' => 'primary',
                                    'delivered' => 'success',
                                    'cancelled' => 'danger',
                                    default => 'secondary'
                                };
                            @endphp
                            <span class="badge bg-{{ $statusColor }}">{{ ucfirst($order->order_status) }}</span>
                        </td>
                        <td>
                            @php
                                $paymentColor = match($order->payment_status) {
                                    'pending' => 'warning',
                                    'paid' => 'success',
                                    'failed' => 'danger',
                                    'refunded' => 'secondary',
                                    default => 'secondary'
                                };
                            @endphp
                            <span class="badge bg-{{ $paymentColor }}">{{ ucfirst($order->payment_status) }}</span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

<!-- Popup détails commande -->
<div id="orderDetailsPopup" class="modal" tabindex="-1" style="display:none; background: rgba(0,0,0,0.5);">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Détails de la commande</h5>
                <button type="button" class="btn-close" id="closeOrderPopup"></button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered" id="orderDetailsTable">
                    <thead>
                        <tr>
                            <th>Produit</th>
                            <th>Quantité</th>
                            <th>Prix unitaire</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <p><strong>Total général:</strong> <span id="orderTotal"></span> FCFA</p>
                <p><strong>Méthode de paiement:</strong> <span id="orderPayment"></span></p>
                <p><strong>Statut commande:</strong> <span id="orderStatus"></span></p>
                <p><strong>Statut paiement:</strong> <span id="paymentStatus"></span></p>
                <p><strong>Notes:</strong> <span id="orderNotes"></span></p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" id="closeOrderPopupFooter">Fermer</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const popup = document.getElementById('orderDetailsPopup');
    const tbody = document.querySelector('#orderDetailsTable tbody');

    function closePopup() {
        popup.style.display = 'none';
    }

    document.querySelectorAll('.viewOrderBtn').forEach(row => {
        row.addEventListener('click', function() {
            const orderId = this.dataset.id;

            fetch(`/orders/${orderId}`)
                .then(res => res.json())
                .then(order => {
                    tbody.innerHTML = '';
                    order.items.forEach(item => {
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                            <td>${item.product.name}</td>
                            <td>${item.quantity}</td>
                            <td>${parseFloat(item.price).toLocaleString('fr-FR')} FCFA</td>
                            <td>${parseFloat(item.total).toLocaleString('fr-FR')} FCFA</td>
                        `;
                        tbody.appendChild(tr);
                    });

                    document.getElementById('orderTotal').textContent = parseFloat(order.total_amount).toLocaleString('fr-FR');
                    document.getElementById('orderPayment').textContent = order.payment_method.replace('_', ' ');
                    document.getElementById('orderStatus').textContent = order.order_status;
                    document.getElementById('paymentStatus').textContent = order.payment_status;
                    document.getElementById('orderNotes').textContent = order.notes || '-';

                    popup.style.display = 'block';
                })
                .catch(err => console.error(err));
        });
    });

    document.getElementById('closeOrderPopup').addEventListener('click', closePopup);
    document.getElementById('closeOrderPopupFooter').addEventListener('click', closePopup);
});
</script>
@endsection
