<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TrackController extends Controller
{
    public function form()
    {
        return view('track.form');
    }

    public function lookup(Request $request)
    {
        $request->validate([
            // Le client saisit son numero de commande (qui sert aussi de numero de suivi).
            'tracking_number' => 'required|string',
        ]);

        // Recherche par numero de commande / suivi.
        $order = Order::where('order_number', $request->tracking_number)->first();

        if (! $order) {
            return back()->withErrors(['not_found' => 'Aucune commande trouvee avec ces informations.']);
        }

        // Ensure a usable public token exists and remains valid.
        $needsSave = false;
        if (! $order->public_token) {
            $order->public_token = Str::upper(Str::random(20));
            $needsSave = true;
        }

        if (! $order->public_token_expires_at || $order->public_token_expires_at->isPast()) {
            $order->public_token_expires_at = now()->addDays(30);
            $needsSave = true;
        }

        if ($needsSave) {
            $order->save();
        }

        return redirect()->route('orders.show', [$order, 'token' => $order->public_token]);
    }
}
