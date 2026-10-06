<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $products = $user->products();
        $filter = $request->query('filter', 'all');
        $filter = in_array($filter, ['all', 'active', 'draft', 'out-of-stock'], true) ? $filter : 'all';

        if ($filter === 'active') {
            $products->where('is_active', true);
        } elseif ($filter === 'draft') {
            $products->where('is_active', false);
        } elseif ($filter === 'out-of-stock') {
            $products->where('stock', 0);
        }

        if ($search = trim((string) $request->query('q'))) {
            $products->where(function ($query) use ($search) {
                $query->where('name', 'like', '%'.$search.'%')
                    ->orWhere('category', 'like', '%'.$search.'%');
            });
        }

        return view('dashboard.shop', [
            'products' => $products->get(),
            'totalProducts' => $user->products()->count(),
            'activeProducts' => $user->products()->where('is_active', true)->count(),
            'draftProducts' => $user->products()->where('is_active', false)->count(),
            'outOfStockProducts' => $user->products()->where('stock', 0)->count(),
            'filter' => $filter,
            'search' => $search ?? '',
        ]);
    }

    public function create(): View
    {
        return view('dashboard.shop-form', ['product' => null]);
    }

    public function edit(Product $product): View
    {
        $this->authorizeOwnership($product);

        return view('dashboard.shop-form', compact('product'));
    }

    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'url' => ['required', 'string', 'max:2048'],
            'price' => ['nullable', 'numeric', 'min:0', 'max:99999999999'],
            'description' => ['nullable', 'string', 'max:500'],
            'category' => ['nullable', 'string', 'max:100'],
            'stock' => ['nullable', 'integer', 'min:0', 'max:10000000'],
            'is_active' => ['nullable', 'boolean'],
            'image' => ['nullable', 'image', 'max:4096'],
        ]);

        $maxPosition = (int) $request->user()->products()->max('position');

        $product = $request->user()->products()->create([
            'name' => $validated['name'],
            'url' => $validated['url'],
            'price' => $validated['price'] ?? null,
            'description' => $validated['description'] ?? null,
            'category' => $validated['category'] ?? null,
            'stock' => $validated['stock'] ?? null,
            'image_path' => $this->storeImage($request),
            'position' => $maxPosition + 1,
            'is_active' => $request->boolean('is_active', true),
        ]);

        if (! $request->expectsJson()) {
            return redirect()->route('dashboard.shop')->with('status', 'Produk berhasil ditambahkan ke My Shop.');
        }

        return response()->json(['product' => $product->fresh()], 201);
    }

    public function update(Request $request, Product $product): JsonResponse|RedirectResponse
    {
        $this->authorizeOwnership($product);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'url' => ['required', 'string', 'max:2048'],
            'price' => ['nullable', 'numeric', 'min:0', 'max:99999999999'],
            'description' => ['nullable', 'string', 'max:500'],
            'category' => ['nullable', 'string', 'max:100'],
            'stock' => ['nullable', 'integer', 'min:0', 'max:10000000'],
            'is_active' => ['sometimes', 'boolean'],
            'image' => ['nullable', 'image', 'max:4096'],
        ]);

        $attributes = [
            'name' => $validated['name'],
            'url' => $validated['url'],
            'price' => $validated['price'] ?? null,
        ];

        foreach (['description', 'category', 'stock'] as $optionalField) {
            if ($request->exists($optionalField)) {
                $attributes[$optionalField] = $validated[$optionalField] ?? null;
            }
        }

        if ($request->hasFile('image')) {
            $this->deleteImage($product);
            $attributes['image_path'] = $this->storeImage($request);
        }

        if (! $request->expectsJson() || $request->has('is_active')) {
            $attributes['is_active'] = $request->boolean('is_active');
        }

        $product->update($attributes);

        if (! $request->expectsJson()) {
            return redirect()->route('dashboard.shop')->with('status', 'Produk berhasil diperbarui.');
        }

        return response()->json(['product' => $product->fresh()]);
    }

    public function destroy(Request $request, Product $product): JsonResponse|RedirectResponse
    {
        $this->authorizeOwnership($product);

        $this->deleteImage($product);
        $product->delete();

        if (! $request->expectsJson()) {
            return redirect()->route('dashboard.shop')->with('status', 'Produk berhasil dihapus.');
        }

        return response()->json(['status' => 'ok']);
    }

    public function toggle(Request $request, Product $product): JsonResponse|RedirectResponse
    {
        $this->authorizeOwnership($product);

        $product->update(['is_active' => ! $product->is_active]);

        if (! $request->expectsJson()) {
            return redirect()->route('dashboard.shop')->with('status', 'Status produk berhasil diperbarui.');
        }

        return response()->json(['product' => $product->fresh()]);
    }

    /**
     * Persist the new product order sent from the drag-and-drop UI.
     */
    public function reorder(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'order' => ['required', 'array'],
            'order.*' => ['integer', 'exists:products,id'],
        ]);

        $ownedIds = $request->user()->products()->pluck('id')->all();

        foreach ($validated['order'] as $index => $productId) {
            if (! in_array((int) $productId, $ownedIds, true)) {
                continue;
            }

            Product::where('id', $productId)->update(['position' => $index]);
        }

        return response()->json(['status' => 'ok']);
    }

    private function storeImage(Request $request): ?string
    {
        if (! $request->hasFile('image')) {
            return null;
        }

        return $request->file('image')->store('products', config('filesystems.default'));
    }

    private function deleteImage(Product $product): void
    {
        if ($product->image_path) {
            Storage::disk(config('filesystems.default'))->delete($product->image_path);
        }
    }

    private function authorizeOwnership(Product $product): void
    {
        abort_unless($product->user_id === Auth::id(), 403);
    }
}
