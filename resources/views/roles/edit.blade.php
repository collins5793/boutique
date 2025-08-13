@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Modifier un rôle</h2>
    <form action="{{ route('roles.update', $role->id) }}" method="POST">
        @csrf @method('PUT')
        <div class="form-group">
            <label>Nom</label>
            <input type="text" name="name" value="{{ $role->name }}" class="form-control" required maxlength="50">
        </div>
        <div class="form-group">
            <label>Description</label>
            <textarea name="description" class="form-control">{{ $role->description }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary mt-2">Mettre à jour</button>
    </form>
</div>
@endsection
