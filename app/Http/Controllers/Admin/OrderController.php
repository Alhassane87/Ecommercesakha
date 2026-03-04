<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('user')->latest();

        // Filtrage par statut
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filtrage par téléphone
        if ($request->filled('phone')) {
            $query->where('customer_phone', 'like', '%' . $request->phone . '%');
        }

        // Filtrage par email
        if ($request->filled('email')) {
            $query->where('customer_email', 'like', '%' . $request->email . '%')
                  ->orWhereHas('user', function($q) use ($request) {
                      $q->where('email', 'like', '%' . $request->email . '%');
                  });
        }

        // Filtrage par numéro de commande
        if ($request->filled('order_number')) {
            $query->where('order_number', 'like', '%' . $request->order_number . '%');
        }

        // Filtrage par date
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->paginate(20)->withQueryString();
        
        return view('admin.orders.index', compact('orders'));
    }

    public function edit(Order $order)
    {
        $order->load('items.product.images', 'items.variation', 'user');

        return view('admin.orders.edit', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $data = $request->validate([
            'status' => 'required|string',
            'tracking_number' => 'nullable|string|max:255',
        ]);

        $order->update($data);

        return redirect()->route('admin.orders.index')->with('status', 'Commande mise à jour.');
    }

    public function destroy(Order $order)
    {
        $order->delete();

        return redirect()->route('admin.orders.index')->with('status', 'Commande supprimée.');
    }
}
