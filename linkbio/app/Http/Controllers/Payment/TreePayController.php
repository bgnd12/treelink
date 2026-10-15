<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\TreePayService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TreePayController extends Controller
{
    public function __construct(protected TreePayService $treePayService)
    {
    }

    public function callback(Request $request): \Illuminate\Http\Response
    {
        $payload = $request->all();

        Log::info('TreePay callback received', $payload);

        $isValid = $this->treePayService->verifyCallback($payload);

        if (! $isValid) {
            Log::warning('TreePay callback signature invalid', $payload);
            return response('Invalid signature', 400);
        }

        $reference = $payload['reference'] ?? $payload['order_id'] ?? null;
        $status = strtolower($payload['status'] ?? '');

        if ($reference) {
            $order = Order::where('payment_reference', $reference)
                ->orWhere('payment_external_id', $reference)
                ->first();

            if ($order) {
                $order->payment_status_raw = $payload['status'] ?? null;
                $order->payment_metadata = array_merge($order->payment_metadata ?? [], $payload);

                if (in_array($status, ['success', 'paid', 'settlement', 'completed'])) {
                    if ($order->payment_status !== 'paid') {
                        $order->payment_status = 'paid';
                        $order->status = 'processing';
                        $order->paid_at = now();
                    }
                } elseif (in_array($status, ['failed', 'cancelled', 'expire', 'expired', 'deny'])) {
                    if ($order->payment_status !== 'paid') {
                        $order->payment_status = 'unpaid';
                        if ($order->status === 'awaiting_payment') {
                            $order->status = 'cancelled';
                        }
                    }
                }

                $order->save();
            }
        }

        return response('OK', 200);
    }

    public function success(Request $request): RedirectResponse
    {
        $reference = $request->query('reference') ?? $request->query('order_id');

        $order = Order::where('payment_reference', $reference)
            ->orWhere('payment_external_id', $reference)
            ->first();

        $orderIds = $order ? [$order->id] : collect($request->query('orders', ''))->explode(',')->filter()->values()->toArray();

        return redirect()->route('public.checkout.success', ['orders' => implode(',', array_filter($orderIds))]);
    }

    public function failed(Request $request): RedirectResponse
    {
        $reference = $request->query('reference') ?? $request->query('order_id');

        $order = Order::where('payment_reference', $reference)
            ->orWhere('payment_external_id', $reference)
            ->first();

        if ($order) {
            return redirect()->route('public.checkout.success', ['orders' => $order->id]);
        }

        return redirect()->route('cart.index');
    }
}
