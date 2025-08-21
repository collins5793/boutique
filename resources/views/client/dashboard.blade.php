@extends('layouts.clients.client')

@section('title', 'Dashboard')

@section('content')
    <div class="welcome-banner">
        <div class="welcome-text">
            <h1>Bonjour, {{ Auth::user()->name ?? 'Invité' }} !</h1>
            <p>Voici un résumé de l'activité de votre boutique.</p>
        </div>
    </div>
@endsection
