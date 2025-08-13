<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('role')->get();
        $roles = Role::all();
        return view('users.index', compact('users', 'roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:191',
            'email'     => 'required|email|unique:users,email',
            'phone'     => 'nullable|string|max:20',
            'password'  => 'required|string|min:6',
            'role_id'   => 'nullable|exists:roles,id',
            'status'    => 'required|in:active,inactive',
        ]);

        $roleId = $request->role_id ?? 1; // rôle par défaut

        User::create([
            'name'          => $request->name,
            'email'         => $request->email,
            'phone'         => $request->phone,
            'password'      => Hash::make($request->password),
            'role_id'       => $roleId,
            'loyalty_points'=> 0,
            'language'      => 'fr',
            'status'        => $request->status,
        ]);

        return redirect()->route('users.index')->with('success', 'Utilisateur ajouté avec succès.');
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'      => 'required|string|max:191',
            'email'     => 'required|email|unique:users,email,'.$user->id,
            'phone'     => 'nullable|string|max:20',
            'role_id'   => 'nullable|exists:roles,id',
            'status'    => 'required|in:active,inactive',
        ]);

        $roleId = $request->role_id ?? 1;

        $data = $request->only(['name', 'email', 'phone', 'role_id', 'status']);
        $data['role_id'] = $roleId;

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'Utilisateur mis à jour avec succès.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'Utilisateur supprimé avec succès.');
    }
}
