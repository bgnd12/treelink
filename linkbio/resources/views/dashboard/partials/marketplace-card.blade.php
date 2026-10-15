@php
    $seller = $product->user;
    $sellerProfile = $seller?->profile;
@endphp
<article class="group overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
    <div class="relative aspect-[1.35] overflow-hidden bg-gradient-to-br from-[#eef2dc] via-white to-[#dfe5f4]">
        @if($product->image_url)
            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="h-full w-full object-cover transition duration-300 group-hover:scale-[1.03]">
        @else
            <div class="flex h-full items-center justify-center text-4xl font-extrabold text-[#1e2a5b]">{{ strtoupper(substr($product->name, 0, 1)) }}</div>
        @endif
        @if($product->stock === 0)
            <span class="absolute left-3 top-3 rounded-full bg-rose-50 px-2.5 py-1 text-[10px] font-bold text-rose-700">Stok habis</span>
        @endif
    </div>

    <div class="space-y-3 p-4">
        <div class="flex items-start justify-between gap-3">
            <div class="min-w-0">
                @if($product->category)<p class="text-[10px] font-bold uppercase tracking-[0.14em] text-slate-400">{{ $product->category }}</p>@endif
                <h3 class="mt-1 line-clamp-2 min-h-10 text-sm font-bold leading-5 text-slate-900">{{ $product->name }}</h3>
            </div>
            <span class="shrink-0 text-sm font-extrabold text-slate-900">{{ $product->price_label ?? 'Gratis' }}</span>
        </div>

        @if($product->description)
            <p class="line-clamp-2 min-h-9 text-xs leading-5 text-slate-500">{{ $product->description }}</p>
        @else
            <p class="min-h-9 text-xs leading-5 text-slate-400">Tidak ada deskripsi produk.</p>
        @endif

        <div class="flex items-center gap-2 border-t border-slate-100 pt-3 text-xs text-slate-500">
            <a href="{{ $seller->publicUrl() }}" target="_blank" rel="noopener" class="flex min-w-0 flex-1 items-center gap-2 hover:text-[#1e2a5b]">
                @if($sellerProfile?->avatar_path)
                    <img src="{{ $sellerProfile->avatar_url }}" alt="" class="h-7 w-7 rounded-full object-cover">
                @else
                    <span class="flex h-7 w-7 items-center justify-center rounded-full bg-[#f1f3e9] text-[10px] font-bold text-[#1e2a5b]">{{ strtoupper(substr($seller->name, 0, 1)) }}</span>
                @endif
                <span class="min-w-0 flex-1 truncate">{{ $sellerProfile?->display_name ?: $seller->name }} <span class="text-slate-400">· {{ '@'.$seller->username }}</span></span>
            </a>
            @if($product->stock !== null && $product->stock > 0)<span class="shrink-0 text-[10px]">Stok {{ $product->stock }}</span>@endif
        </div>
        @if($seller->id !== auth()->id())
            <form method="POST" action="{{ route('dashboard.accounts.follow.toggle', $seller) }}" class="mt-2">
                @csrf
                <button type="submit" class="text-xs font-bold text-slate-500 underline underline-offset-2 hover:text-[#1e2a5b]">{{ $seller->is_followed_by_me ? 'Mengikuti penjual' : 'Ikuti penjual' }}</button>
            </form>
        @endif

        <div class="grid grid-cols-2 gap-2 pt-1">
            @if($seller->id === auth()->id())
                <a href="{{ route('dashboard.products.edit', $product) }}" class="col-span-2 rounded-xl border border-slate-200 py-2.5 text-center text-xs font-bold text-slate-700 transition hover:bg-slate-50">Kelola produk saya</a>
            @else
                <form method="POST" action="{{ route('dashboard.marketplace.products.chat', $product) }}">
                    @csrf
                    <button type="submit" class="w-full rounded-xl bg-[#1e2a5b] px-3 py-2.5 text-xs font-bold text-white transition hover:bg-[#303e76]">Chat Penjual</button>
                </form>
                <a href="{{ route('public.product.show', ['username' => $seller->username, 'product' => $product->id]) }}" target="_blank" rel="noopener noreferrer" class="rounded-xl bg-[#c8ff4d] px-3 py-2.5 text-center text-xs font-bold text-[#1e2a5b] transition hover:bg-[#ddff91]">Beli Produk</a>
            @endif
        </div>
    </div>
</article>
