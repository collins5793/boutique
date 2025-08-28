<?php

// app/Http/Controllers/DeliveryAddressController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DeliveryAddress;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DeliveryAddressController extends Controller
{
   public function store(Request $request)
{
    Log::info('Tentative d’enregistrement d’une adresse', [
        'user_id' => Auth::id(),
        'payload' => $request->all()
    ]);

    try {
        // Validation
        $validated = $request->validate([
            'type' => 'required|in:current,manual,map',
            'full_address' => 'required|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'landmarks' => 'nullable|string',
        ]);

        // Création
        $address = DeliveryAddress::create([
            'user_id' => Auth::id(),
            'order_id' => $request->order_id ?? null,
            'address_type' => $validated['type'],
            'full_address' => $validated['full_address'],
            'latitude' => $validated['latitude'] ?? null,
            'longitude' => $validated['longitude'] ?? null,
            'landmarks' => $validated['landmarks'] ?? null,
        ]);

        Log::info('Adresse enregistrée avec succès', ['id' => $address->id]);

        return response()->json([
            'success' => true,
            'message' => 'Adresse enregistrée',
            'delivery_addresses_id' => $address->id
        ]);
    } catch (\Throwable $e) {
        Log::error('Erreur lors de l’enregistrement de l’adresse', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Erreur serveur : '.$e->getMessage(),
        ], 500);
    }
}


    public function map()
    {
        return view('shop.address_map'); // à créer avec Leaflet ou Google Maps
    }
}
