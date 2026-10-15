<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Support\Cart;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(private readonly Cart $cart) {}

    public function index(): View
    {
        return view('profile.cart', [
            'groups' => $this->cart->groups(),
            'total' => $this->cart->total(),
        ]);
    }

    public function add(Request $request, Product $product): RedirectResponse
    {
        abort_unless($product->is_active && $product->user?->is_active, 404);

        $validated = $request->validate([
            'quantity' => ['nullable', 'integer', 'min:1', 'max:100000'],
            'variant_id' => ['nullable', 'integer', 'exists:product_variants,id'],
            'action' => ['nullable', 'string', 'in:add,buy_now'],
        ]);

        $quantity = $validated['quantity'] ?? 1;
        $variant = $this->resolveVariant($product, $validated['variant_id'] ?? null);

        if ($variant && $variant->stock !== null && $variant->stock < 1) {
            return back()->withErrors(['cart' => 'Varian ini sedang habis stok.']);
        }

        if (! $variant && $product->stock === 0) {
            return back()->withErrors(['cart' => 'Produk ini sedang habis stok.']);
        }

        $variantId = $variant?->id ?? 0;

        if (($validated['action'] ?? 'add') === 'buy_now') {
            $this->cart->clear();
            $this->cart->add($product->id, $quantity, $variantId);

            return redirect()->route('checkout.show');
        }

        $this->cart->add($product->id, $quantity, $variantId);

        return back()->with('status', 'Produk ditambahkan ke keranjang.');
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'key' => ['required', 'string', 'regex:/^\d+:\d+$/'],
            'quantity' => ['required', 'integer', 'min:0', 'max:100000'],
        ]);

        $this->cart->set($validated['key'], $validated['quantity']);

        return back()->with('status', 'Keranjang diperbarui.');
    }

    public function remove(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'key' => ['required', 'string', 'regex:/^\d+:\d+$/'],
        ]);

        $this->cart->remove($validated['key']);

        return back()->with('status', 'Produk dihapus dari keranjang.');
    }

    public function clear(): RedirectResponse
    {
        $this->cart->clear();

        return back()->with('status', 'Keranjang dikosongkan.');
    }

    /**
     * Resolve the selected variant and make sure it belongs to the product.
     */
    private function resolveVariant(Product $product, ?int $variantId): ?ProductVariant
    {
        if (! $variantId) {
            return null;
        }

        $variant = $product->variants()->whereKey($variantId)->first();

        if (! $variant || ! $variant->is_active) {
            throw ValidationException::withMessages([
                'cart' => 'Varian yang kamu pilih tidak tersedia lagi.',
            ]);
        }

        return $variant;
    }
}
