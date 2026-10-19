<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EarningsController extends Controller
{
    public function index(Request $request): View
    {
        $sellerId = $request->user()->id;
        $paidOrders = Order::query()
            ->where('seller_id', $sellerId)
            ->where('payment_status', 'paid');

        $orders = (clone $paidOrders)
            ->with('items')
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('dashboard.earnings', [
            'orders' => $orders,
            'paidOrderCount' => (clone $paidOrders)->count(),
            'recordedRevenue' => (clone $paidOrders)->sum('total'),
            'completedRevenue' => (clone $paidOrders)
                ->where('status', 'completed')
                ->sum('total'),
            'pendingRevenue' => (clone $paidOrders)
                ->whereNotIn('status', ['completed', 'cancelled'])
                ->sum('total'),
            'cancelledRevenue' => (clone $paidOrders)
                ->where('status', 'cancelled')
                ->sum('total'),
        ]);
    }
}
