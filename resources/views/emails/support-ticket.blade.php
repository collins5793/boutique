<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Nouveau Ticket Support - AkuesleyStore</title>
</head>
<body>
    <h2>Nouveau ticket de support livreur</h2>
    
    <p><strong>Livreur:</strong> {{ auth()->user()->name }} ({{ auth()->user()->email }})</p>
    <p><strong>Sujet:</strong> {{ $sujet }}</p>
    <p><strong>Catégorie:</strong> {{ $categorie }}</p>
    <p><strong>Urgence:</strong> {{ $urgence }}</p>
    
    <h3>Message:</h3>
    <p>{{ $message }}</p>
    
    <hr>
    <p><em>Email généré automatiquement - AkuesleyStore Support</em></p>
</body>
</html>