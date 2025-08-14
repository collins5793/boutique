<?php

// app/Http/Controllers/DeliveryAddressController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DeliveryAddress;
use Illuminate\Support\Facades\Auth;

class DeliveryAddressController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:current,manual,map',
            'full_address' => 'required|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'landmarks' => 'nullable|string'
        ]);

        $address = DeliveryAddress::create([
            'user_id' => Auth::id(),
            'order_id' => $request->order_id ?? null, // si tu passes order_id depuis le JS
            'address_type' => $request->type,
            'full_address' => $request->full_address,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'landmarks' => $request->landmarks,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Adresse enregistrée',
            'delivery_addresses_id' => $address->id
        ]);
    }


    public function map()
    {
        return view('shop.address_map'); // à créer avec Leaflet ou Google Maps
    }
}
