<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $status = $request->query('status', 'all');
        $status = in_array($status, array_merge(['all'], array_keys(Order::STATUSES)), true) ? $status : 'all';

        $orders = $user->orders()->with('items.product');

        if ($status !== 'all') {
            $orders->where('status', $status);
        }

        $paidOrders = $user->orders()->where('payment_status', 'paid');

        return view('dashboard.orders', [
            'orders' => $orders->paginate(15)->withQueryString(),
            'status' => $status,
            'totalOrders' => $user->orders()->count(),
            'pendingOrders' => $user->orders()->whereIn('status', array_merge(['awaiting_payment', 'awaiting_confirmation', 'pending']))->count(),
            'completedOrders' => $user->orders()->where('status', 'completed')->count(),
            'revenue' => (float) $user->orders()->where('status', 'completed')->sum('total'),
            'paidOrderCount' => (clone $paidOrders)->count(),
            'recordedRevenue' => (clone $paidOrders)->sum('total'),
            'completedRevenue' => (clone $paidOrders)->where('status', 'completed')->sum('total'),
            'pendingRevenue' => (clone $paidOrders)->whereNotIn('status', ['completed', 'cancelled'])->sum('total'),
        ]);
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        abort_unless($order->seller_id === Auth::id(), 403);

        $validated = $request->validate([
            'status' => ['required', 'string', 'in:'.implode(',', array_keys(Order::STATUSES))],
            'tracking_number' => ['nullable', 'string', 'max:100'],
        ]);

        $attributes = ['status' => $validated['status']];

        if ($request->has('tracking_number')) {
            $attributes['tracking_number'] = $validated['tracking_number'] ?: null;
        }

        $order->update($attributes);

        return back()->with('status', 'Status pesanan '.$order->code.' diperbarui.');
    }

}
