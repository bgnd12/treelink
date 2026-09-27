<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'url' => ['required', 'string', 'max:2048'],
            'price' => ['nullable', 'numeric', 'min:0', 'max:99999999999'],
            'image' => ['nullable', 'image', 'max:4096'],
        ]);

        $maxPosition = (int) $request->user()->products()->max('position');

        $product = $request->user()->products()->create([
            'name' => $validated['name'],
            'url' => $validated['url'],
            'price' => $validated['price'] ?? null,
            'image_path' => $this->storeImage($request),
            'position' => $maxPosition + 1,
            'is_active' => true,
        ]);

        return response()->json(['product' => $product->fresh()], 201);
    }

    public function update(Request $request, Product $product): JsonResponse
    {
        $this->authorizeOwnership($product);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'url' => ['required', 'string', 'max:2048'],
            'price' => ['nullable', 'numeric', 'min:0', 'max:99999999999'],
            'image' => ['nullable', 'image', 'max:4096'],
        ]);

        $attributes = [
            'name' => $validated['name'],
            'url' => $validated['url'],
            'price' => $validated['price'] ?? null,
        ];

        if ($request->hasFile('image')) {
            $this->deleteImage($product);
            $attributes['image_path'] = $this->storeImage($request);
        }

        $product->update($attributes);

        return response()->json(['product' => $product->fresh()]);
    }

    public function destroy(Product $product): JsonResponse
    {
        $this->authorizeOwnership($product);

        $this->deleteImage($product);
        $product->delete();

        return response()->json(['status' => 'ok']);
    }

    public function toggle(Product $product): JsonResponse
    {
        $this->authorizeOwnership($product);

        $product->update(['is_active' => ! $product->is_active]);

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

        return $request->file('image')->store('products', 'public');
    }

    private function deleteImage(Product $product): void
    {
        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }
    }

    private function authorizeOwnership(Product $product): void
    {
        abort_unless($product->user_id === Auth::id(), 403);
    }
}
