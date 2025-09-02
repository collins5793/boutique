<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;
use App\Models\DirectSale;
use App\Models\LoyaltyPoint;
use PDF; // Pour export PDF
use Maatwebsite\Excel\Facades\Excel; // Pour export CSV

class ClientController extends Controller
{
    public function index(Request $request)
    {
        // Filtres
        $query = User::where('role_id', 1); // role_id 1 = client

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%")
                  ->orWhere('phone', 'like', "%$search%");
            });
        }

        $clients = $query->withCount(['orders', 'directSales'])->paginate(15);

        // KPI
        $totalClients = User::where('role_id', 1)->count();
        $activeClients = User::where('role_id', 1)->where('status', 'active')->count();
        $inactiveClients = $totalClients - $activeClients;
        $vipClients = User::where('role_id',1)
            ->has('orders', '>=', 5) // Plus de 5 commandes = VIP
            ->count();
        $recentClients = User::where('role_id',1)
            ->where('created_at', '>=', now()->subDays(7))
            ->count();
        $pendingOrders = Order::where('payment_status','pending')->count();

        return view('admin.clients.index', compact(
            'clients', 'totalClients', 'activeClients', 'inactiveClients',
            'vipClients', 'recentClients', 'pendingOrders'
        ));
    }

    public function show($id)
    {
        $client = User::with(['orders.items.product', 'directSales.items.product', 'loyaltyPoints'])->findOrFail($id);
        return response()->json($client);
    }

    public function toggleStatus($id)
    {
        $client = User::findOrFail($id);
        $client->status = $client->status === 'active' ? 'inactive' : 'active';
        $client->save();
        return response()->json(['status' => $client->status]);
    }

    public function exportCSV()
    {
        $clients = User::where('role_id', 1)->get();
        $filename = 'clients_' . now()->format('Ymd_His') . '.csv';
        $headers = ['Content-Type' => 'text/csv'];
        
        $callback = function() use ($clients) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Nom', 'Email', 'Téléphone', 'Statut', 'Total Commandes']);
            foreach($clients as $client){
                fputcsv($file, [
                    $client->name,
                    $client->email,
                    $client->phone,
                    $client->status,
                    $client->orders->count()
                ]);
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers)->header('Content-Disposition', "attachment; filename=$filename");
    }

    public function exportPDF()
    {
        $clients = User::where('role_id', 1)->get();
        $pdf = PDF::loadView('admin.clients.pdf', compact('clients'));
        return $pdf->download('clients_' . now()->format('Ymd_His') . '.pdf');
    }
}
