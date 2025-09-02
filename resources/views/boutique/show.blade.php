@extends('layouts.apli')

@section('content')
<div class="product-detail">
    <h2>{{ $product->name }}</h2>
    <img src="{{ $product->image ? asset('storage/'.$product->image) : 'https://via.placeholder.com/300' }}" alt="{{ $product->name }}">
    <p>{{ $product->description }}</p>
    <p>Prix : 
        @if($product->discount_price)
            <del>{{ $product->price }} F</del> {{ $product->discount_price }} F
        @else
            {{ $product->price }} F
        @endif
    </p>
    <p>Stock : {{ $product->stock_quantity }}</p>

    <a href="{{ route('shop.order', $product->id) }}" class="btn btn-primary">Commander</a>
</div>
@endsection