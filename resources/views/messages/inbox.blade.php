@extends('layouts.sales.sale')
@section('content')
<div class="container py-4">
    <h3>📨 Boîte de réception</h3>
    <ul class="list-group mt-3">
        @foreach($clients as $client)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <a href="{{ route('admin.messages.conversation', $client->id) }}">
                    {{ $client->name }}
                </a>
                @if($client->unread_count > 0)
                    <span class="badge bg-danger">{{ $client->unread_count }}</span>
                @endif
            </li>
        @endforeach
    </ul>
</div>
@endsection
