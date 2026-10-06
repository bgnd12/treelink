<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class MarketplaceController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));
        $category = trim((string) $request->query('category', ''));
        $sort = $request->query('sort', 'newest');
        $sort = in_array($sort, ['newest', 'price-asc', 'price-desc'], true) ? $sort : 'newest';

        $products = Product::query()
            ->with([
                'user.profile',
                'user' => fn ($query) => $query
                    ->withCount('followers')
                    ->withExists(['followers as is_followed_by_me' => fn ($followerQuery) => $followerQuery->where('users.id', $request->user()->id)]),
            ])
            ->where('is_active', true)
            ->whereHas('user', fn ($query) => $query->where('is_active', true))
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($productQuery) use ($search) {
                    $productQuery->where('name', 'like', '%'.$search.'%')
                        ->orWhere('description', 'like', '%'.$search.'%')
                        ->orWhere('category', 'like', '%'.$search.'%')
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery->where('name', 'like', '%'.$search.'%')
                                ->orWhere('username', 'like', '%'.$search.'%');
                        });
                });
            })
            ->when($category !== '', fn ($query) => $query->where('category', $category));

        match ($sort) {
            'price-asc' => $products->orderByRaw('price IS NULL')->orderBy('price'),
            'price-desc' => $products->orderByRaw('price IS NULL')->orderByDesc('price'),
            default => $products->latest(),
        };

        $categories = Product::query()
            ->where('is_active', true)
            ->whereNotNull('category')
            ->where('category', '<>', '')
            ->whereHas('user', fn ($query) => $query->where('is_active', true))
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return view('dashboard.marketplace', [
            'products' => $products->paginate(16)->withQueryString(),
            'categories' => $categories,
            'search' => $search,
            'category' => $category,
            'sort' => $sort,
        ]);
    }

    public function startChat(Request $request, Product $product): RedirectResponse
    {
        abort_unless($product->is_active && $product->user?->is_active, 404);
        abort_if($product->user_id === $request->user()->id, 422, 'Kamu tidak bisa chat dengan akun sendiri.');

        $buyer = $request->user();
        $sellerId = $product->user_id;

        $conversation = $buyer->conversations()
            ->where('conversations.product_id', $product->id)
            ->whereHas('participants', fn ($query) => $query->whereKey($sellerId))
            ->first();

        if (! $conversation) {
            $conversation = DB::transaction(function () use ($buyer, $sellerId, $product) {
                $conversation = Conversation::create(['product_id' => $product->id]);
                $conversation->participants()->attach([
                    $buyer->id => ['last_read_at' => now()],
                    $sellerId => ['last_read_at' => now()],
                ]);
                $conversation->messages()->create([
                    'user_id' => $buyer->id,
                    'body' => 'Halo, saya tertarik dengan produk '.$product->name.'. Apakah masih tersedia?',
                ]);
                $conversation->touch();

                return $conversation;
            });
        }

        return redirect()->route('dashboard.linkid.messages.show', $conversation)
            ->with('status', 'Chat dengan penjual siap. Tulis pesanmu di bawah.');
    }
}
