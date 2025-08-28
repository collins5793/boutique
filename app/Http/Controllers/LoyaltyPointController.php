<?php

namespace App\Http\Controllers;

use App\Models\LoyaltyPoint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoyaltyPointController extends Controller
{
   public function index()
    {
        $user = Auth::user();

        // Total des points
        $totalPoints = LoyaltyPoint::where('user_id', $user->id)->sum('points');

        // Historique des points
        $history = LoyaltyPoint::where('user_id', $user->id)
                    ->orderBy('created_at', 'desc')
                    ->get();

        // Graphique : points par mois
        $pointsByMonth = LoyaltyPoint::selectRaw('MONTH(created_at) as month, SUM(points) as total')
            ->where('user_id', $user->id)
            ->groupBy('month')
            ->pluck('total', 'month');

        return view('client.loyalty.index', compact('totalPoints', 'history', 'pointsByMonth'));
    }
}
