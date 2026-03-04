<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $orders = Order::where('user_id', $userId)
            ->withCount('items')
            ->latest()
            ->paginate(10);

        $statusCounts = Order::where('user_id', $userId)
            ->selectRaw('status, COUNT(*) as aggregate')
            ->groupBy('status')
            ->pluck('aggregate', 'status');

        $totalOrders = (int) $statusCounts->sum();

        return view('orders.index', compact('orders', 'statusCounts', 'totalOrders'));
    }

    public function show(Order $order)
    {
        // Allow if the authenticated user owns the order or is admin,
        // or if a public token is provided as ?token=...
        $user = Auth::user();

        $allowed = false;
        if ($user) {
            if ($user->id === $order->user_id) {
                $allowed = true;
            }
            if (method_exists($user, 'isAdmin') && $user->isAdmin()) {
                $allowed = true;
            }
        }

        $token = (string) request()->query('token', '');
        if (! $allowed && $token !== '' && $order->public_token && hash_equals((string) $order->public_token, $token)) {
            $allowed = true;

            // If token link expired, transparently renew it so old links still work.
            if ($order->public_token_expires_at && $order->public_token_expires_at->isPast()) {
                $order->public_token_expires_at = now()->addDays(30);
                $order->save();
            }
        }

        if (! $allowed) {
            abort(403);
        }

        // Ensure the public tracking link always exists for invoice QR usage.
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

        $publicTrackingUrl = route('orders.show', ['order' => $order->id, 'token' => $order->public_token]);

        $order->load('items.product.images', 'items.variation');

        return view('orders.show', compact('order', 'publicTrackingUrl'));
    }
}
