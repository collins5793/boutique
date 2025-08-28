
<div class="container">
    <h2 class="mb-4">Liste des rôles</h2>

    {{-- Bouton pour ouvrir le modal Create --}}
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createRoleModal">
        Ajouter un rôle
    </button>

    {{-- Message de succès --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Table --}}
    <table class="table table-bordered table-striped align-middle">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Description</th>
                <th style="width: 200px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($roles as $role)
            <tr>
                <td>{{ $role->id }}</td>
                <td>{{ $role->name }}</td>
                <td>{{ $role->description }}</td>
                <td>
                    <button 
                        class="btn btn-warning btn-sm"
                        data-bs-toggle="modal" 
                        data-bs-target="#editRoleModal{{ $role->id }}">
                        Modifier
                    </button>

                    <form action="{{ route('roles.destroy', $role->id) }}" method="POST" style="display:inline-block;">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Supprimer ce rôle ?')">
                            Supprimer
                        </button>
                    </form>
                </td>
            </tr>

            {{-- Modal Edit --}}
            <div class="modal fade" id="editRoleModal{{ $role->id }}" tabindex="-1" aria-labelledby="editRoleModalLabel{{ $role->id }}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="{{ route('roles.update', $role->id) }}" method="POST">
                            @csrf @method('PUT')
                            <div class="modal-header bg-warning">
                                <h5 class="modal-title" id="editRoleModalLabel{{ $role->id }}">Modifier un rôle</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group mb-3">
                                    <label>Nom</label>
                                    <input type="text" name="name" value="{{ $role->name }}" class="form-control" required maxlength="50">
                                </div>
                                <div class="form-group mb-3">
                                    <label>Description</label>
                                    <textarea name="description" class="form-control">{{ $role->description }}</textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                <button type="submit" class="btn btn-primary">Mettre à jour</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            @endforeach
        </tbody>
    </table>
</div>

{{-- Modal Create --}}
<div class="modal fade" id="createRoleModal" tabindex="-1" aria-labelledby="createRoleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('roles.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="createRoleModalLabel">Ajouter un rôle</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label>Nom</label>
                        <input type="text" name="name" class="form-control" required maxlength="50">
                    </div>
                    <div class="form-group mb-3">
                        <label>Description</label>
                        <textarea name="description" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>
