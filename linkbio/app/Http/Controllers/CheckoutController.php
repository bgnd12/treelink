<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Order;
use App\Services\TreePayService;
use App\Support\Cart;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function __construct(
        private readonly Cart $cart,
        protected TreePayService $treePayService
    ) {}

    public function show(Request $request): View|RedirectResponse
    {
        $groups = $this->cart->groups();

        if ($groups->isEmpty()) {
            return redirect()->route('cart.index')->withErrors(['cart' => 'Keranjang kamu masih kosong.']);
        }

        $lastOrder = null;
        $addresses = collect();

        if ($request->user()) {
            $lastOrder = $request->user()->purchases()->whereNotNull('shipping_name')->first();
            $addresses = $request->user()->addresses()->get();
        }

        $defaultAddress = $addresses->firstWhere('is_default', true) ?? $addresses->first();

        return view('profile.checkout', [
            'groups' => $groups,
            'total' => $this->cart->total(),
            'lastOrder' => $lastOrder,
            'addresses' => $addresses,
            'defaultAddress' => $defaultAddress,
            'paymentMethods' => Order::PAYMENT_METHODS,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $groups = $this->cart->groups();

        if ($groups->isEmpty()) {
            return redirect()->route('cart.index')->withErrors(['cart' => 'Keranjang kamu masih kosong.']);
        }

        $validated = $request->validate([
            'shipping_name' => ['required', 'string', 'max:120'],
            'shipping_phone' => ['required', 'string', 'max:40'],
            'shipping_address' => ['required', 'string', 'max:500'],
            'shipping_city' => ['required', 'string', 'max:100'],
            'shipping_province' => ['nullable', 'string', 'max:100'],
            'shipping_postal_code' => ['nullable', 'string', 'max:12'],
            'payment_method' => ['required', 'string', 'in:'.implode(',', array_keys(Order::PAYMENT_METHODS))],
            'note' => ['nullable', 'string', 'max:500'],
            'address_label' => ['nullable', 'string', 'max:60'],
            'save_address' => ['nullable', 'boolean'],
        ]);

        $orderIds = DB::transaction(function () use ($groups, $validated, $request) {
            $orderIds = [];

            foreach ($groups as $group) {
                foreach ($group['items'] as $row) {
                    $product = $row['product'];
                    $variant = $row['variant'];
                    $quantity = $row['quantity'];

                    $stockTarget = $variant ? $variant->stock : $product->stock;
                    if ($stockTarget !== null && $stockTarget < $quantity) {
                        $name = $product->name.($variant ? ' ('.$variant->label.')' : '');
                        throw ValidationException::withMessages([
                            'cart' => "Stok \"{$name}\" tidak mencukupi. Sisa: {$stockTarget}.",
                        ]);
                    }
                }

                $total = $group['items']->sum('subtotal');

                $order = Order::create([
                    'seller_id' => $group['seller']->id,
                    'buyer_id' => $request->user()?->id,
                    'buyer_name' => $validated['shipping_name'],
                    'buyer_contact' => $validated['shipping_phone'],
                    'note' => $validated['note'] ?? null,
                    'total' => $total,
                    'status' => 'awaiting_payment',
                    'shipping_name' => $validated['shipping_name'],
                    'shipping_phone' => $validated['shipping_phone'],
                    'shipping_address' => $validated['shipping_address'],
                    'shipping_city' => $validated['shipping_city'],
                    'shipping_province' => $validated['shipping_province'] ?? null,
                    'shipping_postal_code' => $validated['shipping_postal_code'] ?? null,
                    'payment_method' => $validated['payment_method'],
                    'payment_status' => 'unpaid',
                ]);

                foreach ($group['items'] as $row) {
                    $product = $row['product'];
                    $variant = $row['variant'];
                    $unitPrice = $variant ? $variant->effectivePrice() : (float) ($product->price ?? 0);

                    $order->items()->create([
                        'product_id' => $product->id,
                        'variant_id' => $variant?->id,
                        'product_name' => $product->name,
                        'variant_name' => $variant?->label,
                        'price' => $unitPrice,
                        'quantity' => $row['quantity'],
                        'subtotal' => $unitPrice * $row['quantity'],
                    ]);

                    $stockTarget = $variant ? $variant : $product;
                    if ($stockTarget->stock !== null) {
                        $stockTarget->decrement('stock', $row['quantity']);
                    }
                }

                $orderIds[] = $order->id;
            }

            return $orderIds;
        });

        $this->cart->clear();

        $this->rememberAddress($request, $validated);

        return redirect()->route('public.checkout.success')
            ->with('checkout_success_orders', $orderIds);
    }

    /**
     * Persist the buyer's shipping details so they can be reused next time.
     */
    private function rememberAddress(Request $request, array $validated): void
    {
        $user = $request->user();

        if (! $user || ! $request->boolean('save_address')) {
            return;
        }

        $exists = $user->addresses()
            ->where('phone', $validated['shipping_phone'])
            ->where('address', $validated['shipping_address'])
            ->exists();

        if ($exists) {
            return;
        }

        $user->addresses()->create([
            'label' => $validated['address_label'] ?? null,
            'recipient_name' => $validated['shipping_name'],
            'phone' => $validated['shipping_phone'],
            'address' => $validated['shipping_address'],
            'city' => $validated['shipping_city'],
            'province' => $validated['shipping_province'] ?? null,
            'postal_code' => $validated['shipping_postal_code'] ?? null,
            'is_default' => $user->addresses()->count() === 0,
        ]);
    }

    public function success(Request $request): View
    {
        $ids = $request->session()->get('checkout_success_orders', []);

        if (empty($ids)) {
            // Allow refreshing the page using the previously stored order ids.
            $ids = collect(explode(',', (string) $request->query('orders')))
                ->filter(fn ($id) => ctype_digit($id))
                ->map(fn ($id) => (int) $id)
                ->all();
        }

        $orders = Order::with(['items', 'seller'])
            ->whereIn('id', $ids)
            ->orderBy('id')
            ->get();

        abort_if($orders->isEmpty(), 404);

        return view('profile.checkout-success', [
            'orders' => $orders,
            'grandTotal' => (float) $orders->sum('total'),
        ]);
    }
}
