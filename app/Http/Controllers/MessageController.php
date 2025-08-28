<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MessageController extends Controller
{
    // 📩 Liste des messages entre 2 users
    public function index($receiver_id)
    {
        $messages = Message::where(function ($q) use ($receiver_id) {
                $q->where('sender_id', Auth::id())
                  ->where('receiver_id', $receiver_id);
            })
            ->orWhere(function ($q) use ($receiver_id) {
                $q->where('sender_id', $receiver_id)
                  ->where('receiver_id', Auth::id());
            })
            ->orderBy('created_at', 'asc')
            ->get();

        return view('messages.index', compact('messages', 'receiver_id'));
    }

    // 📝 Envoyer un message
    public function store(Request $request, $receiver_id)
    {
        $request->validate([
            'message' => 'nullable|string|max:1000',
            'file' => 'nullable|file|max:10240',
        ]);

        $filePath = null;
        $fileType = null;

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileType = $file->getClientMimeType();

            $filePath = $file->store('uploads/messages', 'public');
        }

        if (!$request->message && !$filePath) {
            return response()->json(['success' => false, 'error' => 'Message vide']);
        }

        $msg = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $receiver_id,
            'message' => $request->message,
            'file' => $filePath,
            'file_type' => $fileType,
            'status' => 'sent',
        ]);

        return response()->json([
            'success' => true,
            'message' => [
                'id' => $msg->id,
                'message' => $msg->message,
                'file' => $msg->file ? asset('storage/' . $msg->file) : null,
                'file_type' => $msg->file_type,
                'time' => $msg->created_at?->format('H:i'),
            ]
        ]);
    }

    // ✏️ Modifier un message (si pas encore lu)
    public function update(Request $request, Message $message)
{
    try {
        // Vérifier autorisation
        if ($message->status !== 'sent' || $message->sender_id !== Auth::id()) {
            Log::warning("Utilisateur ".Auth::id()." tente de modifier un message non autorisé ID ".$message->id);
            return response()->json([
                'success' => false,
                'message' => 'Impossible de modifier ce message.'
            ], 403);
        }

        // Validation
        $request->validate(['message' => 'required|string|max:1000']);

        // Mise à jour
        $message->update([
            'message' => $request->message,
        ]);

        Log::info("Message ID ".$message->id." modifié par utilisateur ".Auth::id());

        return response()->json([
            'success' => true,
            'message' => 'Message modifié avec succès.',
            'updated_message' => $message->message,
        ]);

    } catch (\Exception $e) {
        Log::error("Erreur modification message ID ".$message->id." : ".$e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de la modification du message.'
        ], 500);
    }
}

    // 🗑 Supprimer un message (status = deleted)
    public function destroy(Message $message)
    {
        if ($message->status !== 'sent' || $message->sender_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Impossible de supprimer ce message.'
            ], 403);
        }

        $message->update(['status' => 'deleted']);

        return response()->json([
            'success' => true,
            'message' => 'Message supprimé avec succès.'
        ]);
    }


    // ✅ Marquer comme lu
    public function markAsRead(Message $message)
    {
        if ($message->receiver_id === Auth::id()) {
            $message->update(['status' => 'read']);
        }

        return response()->json(['success' => true]);
    }

    



    public function inbox()
{
    // Récupérer les utilisateurs qui ont envoyé des messages à l'admin
    $clients = User::whereHas('messages', function($q) {
        $q->where('receiver_id', Auth::id());
    })->withCount(['messages as unread_count' => function($q){
        $q->where('receiver_id', Auth::id())->where('status', 'sent');
    }])->get();

    return view('messages.inbox', compact('clients'));
}

public function conversation(User $client)
{
    // Récupérer tous les messages entre admin et ce client
    $messages = Message::where(function($q) use ($client){
        $q->where('sender_id', $client->id)
          ->where('receiver_id', 1);
    })->orWhere(function($q) use ($client){
        $q->where('sender_id', Auth::id())
          ->where('receiver_id', $client->id);
    })->orderBy('created_at', 'asc')->get();

    // Marquer les messages reçus comme lus
    Message::where('sender_id', $client->id)
        ->where('receiver_id', Auth::id())
        ->where('status', 'sent')
        ->update(['status' => 'read']);

    return view('messages.conversation', compact('client', 'messages'));
}

public function fetchs($receiver, Request $request)
{
    $after = $request->query('after', 0);
    $messages = Message::where('sender_id', $receiver)
                       ->orWhere('receiver_id', $receiver)
                       ->where('id', '>', $after)
                       ->orderBy('created_at')
                       ->get();

    return response()->json(['messages' => $messages->map(function($msg){
        return [
            'id' => $msg->id,
            'sender_id' => $msg->sender_id,
            'message' => $msg->message,
            'file' => $msg->file ? asset('storage/'.$msg->file) : null,
            'file_type' => $msg->file_type,
            'time' => optional($msg->created_at)->format('H:i')
        ];
    })]);
}

public function markAsReads($receiver)
{
    Message::where('sender_id', $receiver)
           ->where('receiver_id', Auth::id())
           ->where('status', '!=', 'read')
           ->update(['status' => 'read']);

    return response()->json(['success' => true]);
}

public function fetch($receiver_id)
    {
        $messages = Message::where(function($q) use ($receiver_id){
                $q->where('sender_id', Auth::id())
                ->where('receiver_id', $receiver_id);
            })
            ->orWhere(function($q) use ($receiver_id){
                $q->where('sender_id', $receiver_id)
                ->where('receiver_id', Auth::id());
            })
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(fn($m)=>[
                'id' => $m->id,
                'sender_id' => $m->sender_id,
                'message' => $m->status === 'deleted' ? 'Message supprimé 🗑' : $m->message,
                'file' => $m->file ? asset('storage/' . $m->file) : null,
                'file_type' => $m->file_type,
                'time' => $m->created_at?->format('H:i'),
            ]);


        return response()->json(['messages'=>$messages]);
    }



}
