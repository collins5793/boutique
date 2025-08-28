@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Ajouter un rôle</h2>
    <form action="{{ route('roles.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label>Nom</label>
            <input type="text" name="name" class="form-control" required maxlength="50">
        </div>
        <div class="form-group">
            <label>Description</label>
            <textarea name="description" class="form-control"></textarea>
        </div>
        <button type="submit" class="btn btn-success mt-2">Enregistrer</button>
    </form>
</div>
@endsection
edit.blade.php