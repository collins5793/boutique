<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;


class SettingController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('client.settings', compact('user'));
    }

    // Mettre à jour profil
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'name' => 'required|string|max:191',
            'email' => 'required|email|max:191|unique:users,email,'.$user->id,
            'phone' => 'nullable|string|max:20',
        ]);

        $user->update($request->only('name','email','phone'));

        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('profile_photos','public');
            $user->profile_photo = $path;
            $user->save();
        }

        return back()->with('success', 'Profil mis à jour avec succès.');
    }

    // Mettre à jour mot de passe
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Le mot de passe actuel est incorrect.']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('success', 'Mot de passe mis à jour avec succès.');
    }

    // Mettre à jour préférences (exemple)
    public function updatePreferences(Request $request)
    {
        $user = Auth::user();
        $user->update([
            'language' => $request->language ?? 'fr',
            // ajouter d'autres préférences ici
        ]);

        return back()->with('success', 'Préférences mises à jour.');
    }
    public function indexl()
    {
        $user = Auth::user();
        return view('delivery.settings', compact('user'));
    }

    // Mettre à jour profil
    public function updateProfilel(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'name' => 'required|string|max:191',
            'email' => 'required|email|max:191|unique:users,email,'.$user->id,
            'phone' => 'nullable|string|max:20',
        ]);

        $user->update($request->only('name','email','phone'));

        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('profile_photos','public');
            $user->profile_photo = $path;
            $user->save();
        }

        return back()->with('success', 'Profil mis à jour avec succès.');
    }

    // Mettre à jour mot de passe
    public function updatePasswordl(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Le mot de passe actuel est incorrect.']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('success', 'Mot de passe mis à jour avec succès.');
    }

    // Mettre à jour préférences (exemple)
    public function updatePreferencesl(Request $request)
    {
        $user = Auth::user();
        $user->update([
            'language' => $request->language ?? 'fr',
            // ajouter d'autres préférences ici
        ]);

        return back()->with('success', 'Préférences mises à jour.');
    }


    public function indexs()
    {
        $user = Auth::user();
        return view('sale.settings', compact('user'));
    }

    // Mettre à jour profil
    public function updateProfiles(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'name' => 'required|string|max:191',
            'email' => 'required|email|max:191|unique:users,email,'.$user->id,
            'phone' => 'nullable|string|max:20',
        ]);

        $user->update($request->only('name','email','phone'));

        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('profile_photos','public');
            $user->profile_photo = $path;
            $user->save();
        }

        return back()->with('success', 'Profil mis à jour avec succès.');
    }

    // Mettre à jour mot de passe
    public function updatePasswords(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Le mot de passe actuel est incorrect.']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('success', 'Mot de passe mis à jour avec succès.');
    }

    // Mettre à jour préférences (exemple)
    public function updatePreferencess(Request $request)
    {
        $user = Auth::user();
        $user->update([
            'language' => $request->language ?? 'fr',
            // ajouter d'autres préférences ici
        ]);

        return back()->with('success', 'Préférences mises à jour.');
    }
}
