@extends('layouts.admins.admin')

@section('content')
<h2>Vente directe</h2>

<form action="{{ route('direct_sales.store') }}" method="POST">
    @csrf
    <div id="itemsContainer">
        <div class="sale-item">
            <select name="items[0][product_id]" required>
                <option value="">Choisir un produit</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}">{{ $product->name }} - {{ $product->price }} F</option>
                @endforeach
            </select>
            <input type="number" name="items[0][quantity]" min="1" value="1" required>
        </div>
    </div>

    <button type="button" onclick="addItem()">+ Ajouter produit</button>

    <div>
        <label>Méthode de paiement :</label>
        <select name="payment_method" required>
            <option value="cash">Espèces</option>
            <option value="mobile_money">Mobile Money</option>
            <option value="card">Carte bancaire</option>
        </select>
    </div>

    <button type="submit">Enregistrer la vente</button>
</form>

<script>
let count = 1;
function addItem() {
    const container = document.getElementById('itemsContainer');
    const div = document.createElement('div');
    div.classList.add('sale-item');
    div.innerHTML = `
        <select name="items[${count}][product_id]" required>
            <option value="">Choisir un produit</option>
            @foreach($products as $product)
                <option value="{{ $product->id }}">{{ $product->name }} - {{ $product->price }} F</option>
            @endforeach
        </select>
        <input type="number" name="items[${count}][quantity]" min="1" value="1" required>
    `;
    container.appendChild(div);
    count++;
}
</script>
@endsection