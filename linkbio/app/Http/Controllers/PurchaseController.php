<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PurchaseController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $orders = $user->purchases()->with(['items', 'seller']);

        return view('dashboard.purchases', [
            'orders' => $orders->paginate(10),
            'totalOrders' => $user->purchases()->count(),
            'activeOrders' => $user->purchases()->whereIn('status', array_merge(['awaiting_payment', 'awaiting_confirmation', 'processing', 'shipped', 'pending']))->count(),
            'completedOrders' => $user->purchases()->where('status', 'completed')->count(),
            'spent' => (float) $user->purchases()->where('payment_status', 'paid')->sum('total'),
        ]);
    }

    public function show(Request $request, Order $order): View
    {
        abort_unless($order->buyer_id === Auth::id(), 403);

        $order->load(['items', 'seller']);

        return view('dashboard.purchase-show', [
            'order' => $order,
        ]);
    }
}
