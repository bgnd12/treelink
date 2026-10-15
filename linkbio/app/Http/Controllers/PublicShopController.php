<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PublicShopController extends Controller
{
    public function show(Request $request, string $username, Product $product): View
    {
        $seller = $this->resolveSeller($username);
        $this->authorizeProduct($seller, $product);

        // Record a product view (exclude owner and guests? spec says track; avoid double-counting owner self-preview).
        if (! $request->user() || $request->user()->id !== $seller->id) {
            $product->productViews()->create([
                'user_id' => $seller->id,
                'ip_address' => $request->ip(),
                'user_agent' => substr((string) $request->userAgent(), 0, 255),
                'referrer' => substr((string) $request->headers->get('referer'), 0, 255),
            ]);
        }

        $product->loadMissing([
            'variants' => fn ($query) => $query->where('is_active', true)->orderBy('position')->orderBy('id'),
            'reviews' => fn ($query) => $query->with(['user', 'order'])->where('is_visible', true)->latest(),
            'user',
        ]);

        $canReview = false;
        $orderItemToReview = null;
        $hasReviewedOrderItem = collect();

        if ($request->user()) {
            $reviewedOrderItemIds = Review::where('user_id', $request->user()->id)
                ->where('product_id', $product->id)
                ->pluck('order_item_id');

            $orderItem = OrderItem::query()
                ->whereHas('order', function ($query) use ($request) {
                    $query->where('buyer_id', $request->user()->id)
                        ->where('status', 'completed');
                })
                ->where('product_id', $product->id)
                ->whereNotNull('order_id')
                ->when($reviewedOrderItemIds->isNotEmpty(), function ($query) use ($reviewedOrderItemIds) {
                    $query->whereNotIn('id', $reviewedOrderItemIds->filter());
                })
                ->first();

            if ($orderItem) {
                $canReview = true;
                $orderItemToReview = $orderItem;
            }
        }

        return view('profile.product', [
            'profileUser' => $seller,
            'profile' => $seller->getOrCreateProfile(),
            'product' => $product,
            'isOwner' => $request->user()?->id === $seller->id,
            'canReview' => $canReview,
            'orderItemToReview' => $orderItemToReview,
        ]);
    }

    public function confirmation(Request $request, string $username, Order $order): View
    {
        abort_unless($order->seller && strtolower($order->seller->username) === strtolower($username), 404);

        $order->load(['items', 'seller']);

        return view('profile.order-confirmation', [
            'profileUser' => $order->seller,
            'profile' => $order->seller->getOrCreateProfile(),
            'order' => $order,
        ]);
    }

    public function uploadProof(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'payment_proof' => ['required', 'image', 'max:4096'],
        ]);

        if ($order->payment_proof) {
            Storage::disk(config('filesystems.default'))->delete($order->payment_proof);
        }

        $order->update([
            'payment_proof' => $request->file('payment_proof')->store('payment-proofs', config('filesystems.default')),
        ]);

        return back()->with('status', 'Bukti pembayaran berhasil diunggah. Menunggu konfirmasi penjual.');
    }

    private function resolveSeller(string $username): User
    {
        return User::where('username', strtolower($username))
            ->where('is_active', true)
            ->firstOrFail();
    }

    private function authorizeProduct(User $seller, Product $product): void
    {
        abort_unless($product->user_id === $seller->id && $product->is_active, 404);
    }
}
