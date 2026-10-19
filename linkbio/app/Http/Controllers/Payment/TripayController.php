<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\TripayService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TripayController extends Controller
{
    public function __construct(protected TripayService $tripayService) {}

    /**
     * Callback / webhook dari Tripay (event: payment_status).
     */
    public function callback(Request $request): Response|JsonResponse
    {
        $rawBody = $request->getContent();
        $signature = (string) $request->header('X-Callback-Signature', '');
        $event = (string) $request->header('X-Callback-Event', '');

        if (! $this->tripayService->verifyCallbackSignature($rawBody, $signature)) {
            Log::warning('Tripay callback: invalid signature');

            return response()->json(['success' => false, 'message' => 'Invalid signature'], 400);
        }

        if ($event !== 'payment_status') {
            return response()->json(['success' => false, 'message' => 'Unrecognized callback event'], 400);
        }

        $data = json_decode($rawBody, true);
        if (! is_array($data) || json_last_error() !== JSON_ERROR_NONE) {
            return response()->json(['success' => false, 'message' => 'Invalid callback data'], 400);
        }

        $merchantRef = $data['merchant_ref'] ?? null;
        $tripayReference = $data['reference'] ?? null;
        $status = strtoupper((string) ($data['status'] ?? ''));
        $amount = $this->tripayService->amountInRupiah($data['total_amount'] ?? null);

        if (! is_string($merchantRef) || $merchantRef === '' || ! is_string($tripayReference) || $tripayReference === '') {
            return response()->json(['success' => false, 'message' => 'Missing transaction reference'], 400);
        }

        if ($amount === null || ! in_array($status, ['PAID', 'FAILED', 'EXPIRED', 'REFUND'], true)) {
            return response()->json(['success' => false, 'message' => 'Invalid transaction payload'], 400);
        }

        $processed = DB::transaction(function () use ($data, $merchantRef, $tripayReference, $status, $amount): bool {
            $order = Order::query()
                ->where('payment_reference', $merchantRef)
                ->where('payment_external_id', $tripayReference)
                ->lockForUpdate()
                ->first();

            if (! $order || (int) round((float) $order->total) !== $amount) {
                return false;
            }

            $alreadyPaid = $order->payment_status === 'paid';

            $order->forceFill([
                'payment_status_raw' => $status,
                'payment_metadata' => $data,
            ]);

            if ($status === 'PAID' && ! $alreadyPaid) {
                $order->payment_status = 'paid';
                $order->status = 'processing';
                $order->paid_at = now();
            } elseif (! $alreadyPaid && in_array($status, ['FAILED', 'EXPIRED'], true)) {
                $order->payment_status = 'unpaid';
                if ($order->status === 'awaiting_payment') {
                    $order->status = 'cancelled';
                }
            }

            $order->save();

            return true;
        });

        if (! $processed) {
            Log::warning('Tripay callback rejected: order reference or amount mismatch', [
                'merchant_ref' => $merchantRef,
                'reference' => $tripayReference,
                'amount' => $amount,
            ]);

            return response()->json(['success' => false, 'message' => 'Transaction does not match an order'], 422);
        }

        Log::info('Tripay callback processed', [
            'merchant_ref' => $merchantRef,
            'reference' => $tripayReference,
            'status' => $status,
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * Redirect balik setelah user menyelesaikan/menutup halaman pembayaran.
     */
    public function success(Request $request): RedirectResponse
    {
        $order = $this->resolveOrder($request);

        $orderIds = $order ? [$order->id] : [];

        return redirect()->route('public.checkout.success', [
            'orders' => implode(',', array_filter($orderIds)),
        ]);
    }

    public function failed(Request $request): RedirectResponse
    {
        $order = $this->resolveOrder($request);

        if (! $order) {
            return redirect()->route('cart.index');
        }

        return redirect()->route('public.checkout.success', ['orders' => $order->id]);
    }

    protected function resolveOrder(Request $request): ?Order
    {
        $merchantRef = $request->query('merchant_ref');
        $reference = $request->query('reference');

        if ($merchantRef) {
            return Order::where('payment_reference', $merchantRef)->first();
        }

        if ($reference) {
            return Order::where('payment_external_id', $reference)
                ->orWhere('payment_reference', $reference)
                ->first();
        }

        return null;
    }
}
