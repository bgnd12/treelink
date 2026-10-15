<?php

namespace App\Support;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Support\Collection;

class Cart
{
    private const KEY = 'shop_cart';

    /**
     * Build the composite cart key for a product (and optional variant).
     */
    public static function key(int $productId, int $variantId = 0): string
    {
        return $productId.':'.max(0, $variantId);
    }

    /**
     * Raw cart data as ["productId:variantId" => quantity].
     *
     * Legacy carts that only stored [productId => quantity] are transparently
     * upgraded to the composite key with a "0" variant.
     *
     * @return array<string, int>
     */
    public function items(): array
    {
        $items = session()->get(self::KEY, []);

        if (! is_array($items)) {
            return [];
        }

        $normalized = [];

        foreach ($items as $key => $quantity) {
            $quantity = (int) $quantity;

            if ($quantity < 1) {
                continue;
            }

            $key = (string) $key;

            if (! str_contains($key, ':')) {
                $key .= ':0';
            }

            $normalized[$key] = $quantity;
        }

        return $normalized;
    }

    public function add(int $productId, int $quantity = 1, int $variantId = 0): void
    {
        $items = $this->items();
        $key = self::key($productId, $variantId);

        $items[$key] = max(1, ($items[$key] ?? 0) + max(1, $quantity));

        session()->put(self::KEY, $items);
    }

    public function set(string $key, int $quantity): void
    {
        $items = $this->items();

        if ($quantity < 1) {
            unset($items[$key]);
        } else {
            $items[$key] = $quantity;
        }

        session()->put(self::KEY, $items);
    }

    public function remove(string $key): void
    {
        $items = $this->items();
        unset($items[$key]);
        session()->put(self::KEY, $items);
    }

    public function clear(): void
    {
        session()->forget(self::KEY);
    }

    public function count(): int
    {
        return array_sum($this->items());
    }

    public function isEmpty(): bool
    {
        return $this->count() === 0;
    }

    /**
     * Cart contents joined with active products (and variants), grouped by seller.
     *
     * @return Collection<int, array{seller: User, items: Collection<int, array{key: string, product: Product, variant: ?ProductVariant, quantity: int, subtotal: float}>}>
     */
    public function groups(): Collection
    {
        $items = $this->items();

        if (empty($items)) {
            return collect();
        }

        $productIds = array_unique(array_map(fn ($key) => (int) explode(':', $key)[0], array_keys($items)));

        $products = Product::with(['user', 'variants'])
            ->whereIn('id', $productIds)
            ->where('is_active', true)
            ->orderBy('position')
            ->get()
            ->keyBy('id');

        return collect($items)
            ->map(function (int $quantity, string $key) use ($products) {
                [$productId, $variantId] = array_map('intval', explode(':', $key));
                $product = $products->get($productId);

                if (! $product || ! $product->user) {
                    return null;
                }

                $variant = null;
                if ($variantId > 0) {
                    $variant = $product->variants->firstWhere('id', $variantId);

                    // A variant that was removed or disabled invalidates the row.
                    if (! $variant || ! $variant->is_active) {
                        return null;
                    }
                }

                $max = $this->maxQuantity($product, $variant);
                $quantity = min($quantity, $max);
                $unitPrice = $variant ? $variant->effectivePrice() : (float) ($product->price ?? 0);

                if ($quantity < 1) {
                    return null;
                }

                return [
                    'key' => $key,
                    'product' => $product,
                    'variant' => $variant,
                    'quantity' => $quantity,
                    'subtotal' => $unitPrice * $quantity,
                ];
            })
            ->filter()
            ->groupBy(fn (array $row) => $row['product']->user_id)
            ->map(fn (Collection $rows) => [
                'seller' => $rows->first()['product']->user,
                'items' => $rows->values(),
            ])
            ->values();
    }

    public function total(): float
    {
        return (float) $this->groups()->sum(fn ($group) => $group['items']->sum('subtotal'));
    }

    private function maxQuantity(Product $product, ?ProductVariant $variant): int
    {
        $stock = $variant ? $variant->stock : $product->stock;

        return $stock === null ? 100000 : max(0, $stock);
    }
}
