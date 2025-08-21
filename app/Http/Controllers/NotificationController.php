<?php

// app/Http/Controllers/NotificationController.php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()->notifications()->orderBy('created_at', 'desc')->get();

        return view('notifications.index', compact('notifications'));
    }

    public function create()
    {
        $users = User::all();
        return view('notifications.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:191',
            'content' => 'required|string',
            'type' => 'required|in:system,promo,order',
        ]);

        Notification::create($request->all());

        return redirect()->route('notifications.index')
            ->with('success', 'Notification créée avec succès.');
    }

    public function show(Notification $notification)
{
    // Si la notification n'est pas encore lue, on met à jour
    if (is_null($notification->read_at)) {
        $notification->update([
            'read_at' => now(),
        ]);
    }

    return view('notifications.show', compact('notification'));
}


    public function edit(Notification $notification)
    {
        $users = User::all();
        return view('notifications.edit', compact('notification', 'users'));
    }

    public function update(Request $request, Notification $notification)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:191',
            'content' => 'required|string',
            'type' => 'required|in:system,promo,order',
        ]);

        $notification->update($request->all());

        return redirect()->route('notifications.index')
            ->with('success', 'Notification mise à jour avec succès.');
    }

    public function destroy(Notification $notification)
    {
        $notification->delete();
        return redirect()->route('notifications.index')
            ->with('success', 'Notification supprimée.');
    }
}
