@extends('layouts.shop')

@section('title', 'Checkout')

@section('content')
@php
    $prefill = [
        'name' => old('shipping_name', $defaultAddress?->recipient_name ?: ($lastOrder?->shipping_name ?: auth()->user()?->name)),
        'phone' => old('shipping_phone', $defaultAddress?->phone ?: $lastOrder?->shipping_phone),
        'address' => old('shipping_address', $defaultAddress?->address ?: $lastOrder?->shipping_address),
        'city' => old('shipping_city', $defaultAddress?->city ?: $lastOrder?->shipping_city),
        'province' => old('shipping_province', $defaultAddress?->province ?: $lastOrder?->shipping_province),
        'postal_code' => old('shipping_postal_code', $defaultAddress?->postal_code ?: $lastOrder?->postal_code),
    ];

    $addressesPayload = $addresses->map(fn ($address) => [
        'recipient_name' => $address->recipient_name,
        'phone' => $address->phone,
        'address' => $address->address,
        'city' => $address->city,
        'province' => $address->province,
        'postal_code' => $address->postal_code,
    ])->values();
@endphp

<h1 class="text-2xl font-extrabold tracking-tight text-slate-900">Checkout</h1>
<p class="mt-1 text-sm text-slate-500">Lengkapi alamat pengiriman dan pilih metode pembayaran.</p>

@auth
    <section class="mt-5 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm"
             x-data="{
                 addresses: @js($addressesPayload),
                 selectedIndex: {{ $addresses->search(fn($a) => $a->id === $defaultAddress?->id) !== false ? $addresses->search(fn($a) => $a->id === $defaultAddress?->id) : 0 }},
                 selectAddress(index) {
                     this.selectedIndex = index;
                     const addr = this.addresses[index];
                     if (addr) {
                         document.getElementById('shipping_name').value = addr.recipient_name || '';
                         document.getElementById('shipping_phone').value = addr.phone || '';
                         document.getElementById('shipping_address').value = addr.address || '';
                         document.getElementById('shipping_city').value = addr.city || '';
                         document.getElementById('shipping_province').value = addr.province || '';
                         document.getElementById('shipping_postal_code').value = addr.postal_code || '';
                     }
                 }
             }">
        <div class="flex items-center justify-between gap-3">
            <h2 class="flex items-center gap-2 text-sm font-extrabold uppercase tracking-[0.14em] text-slate-400">
                <span class="flex h-5 w-5 items-center justify-center rounded-full bg-[#1e2a5b] text-[10px] font-extrabold text-white">★</span>
                Alamat Tersimpan
            </h2>
            <a href="{{ route('addresses.create') }}" class="text-xs font-bold text-[#1e2a5b] underline underline-offset-4 hover:text-[#303e76]">+ Tambah alamat baru</a>
        </div>

        @if ($addresses->isNotEmpty())
            <div class="mt-3 grid gap-3 sm:grid-cols-2">
                @foreach ($addresses as $index => $address)
                    <div class="group relative flex flex-col rounded-xl border p-3.5 transition cursor-pointer"
                         :class="selectedIndex === {{ $index }} ? 'border-[#879366] bg-[#f6f8ee] ring-2 ring-[#c8ff4d]' : 'border-slate-200 hover:border-slate-300 hover:bg-slate-50'"
                         @click="selectAddress({{ $index }})">
                        <div class="text-left pr-6">
                            <div class="flex items-center gap-2">
                                <p class="truncate text-sm font-bold text-slate-900">{{ $address->label ?: $address->recipient_name }}</p>
                                @if ($address->is_default)
                                    <span class="rounded-full bg-[#c8ff4d] px-2 py-0.5 text-[10px] font-bold text-[#1e2a5b]">Utama</span>
                                @endif
                                <span x-show="selectedIndex === {{ $index }}" class="text-xs font-extrabold text-[#4d7c0f]">&check; Dipilih</span>
                            </div>
                            <p class="mt-1 text-xs text-slate-500">{{ $address->recipient_name }} &middot; {{ $address->phone }}</p>
                            <p class="mt-1 line-clamp-2 text-xs leading-5 text-slate-400">{{ $address->summary }}</p>
                        </div>

                        <form method="POST" action="{{ route('addresses.destroy', $address) }}" class="absolute right-2 top-2" @click.stop onsubmit="return confirm('Hapus alamat ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" aria-label="Hapus alamat" title="Hapus alamat" class="flex h-6 w-6 items-center justify-center rounded-full text-slate-300 transition hover:bg-rose-50 hover:text-rose-500">&times;</button>
                        </form>
                    </div>
                @endforeach
            </div>
            <p class="mt-3 text-xs text-slate-400">Pilih salah satu alamat tersimpan untuk pengiriman pesanan kamu.</p>
        @else
            <div class="mt-4 rounded-xl border border-dashed border-slate-200 p-6 text-center">
                <p class="text-sm font-semibold text-slate-600">Belum ada alamat tersimpan.</p>
                <p class="mt-1 text-xs text-slate-400">Silakan tambahkan alamat pengiriman baru terlebih dahulu sebelum membuat pesanan.</p>
                <a href="{{ route('addresses.create') }}" class="mt-3 inline-flex items-center gap-1.5 rounded-xl bg-[#c8ff4d] px-4 py-2 text-xs font-bold text-[#1e2a5b] shadow-sm hover:bg-[#ddff91]">
                    + Tambah Alamat Sekarang
                </a>
            </div>
        @endif
    </section>
@endauth

@guest
    <section class="mt-5 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <h2 class="flex items-center gap-2 text-sm font-extrabold uppercase tracking-[0.14em] text-slate-400">
            <span class="flex h-5 w-5 items-center justify-center rounded-full bg-[#1e2a5b] text-[10px] font-extrabold text-white">1</span>
            Data Pengiriman
        </h2>
        <div class="mt-4 grid gap-3 sm:grid-cols-2">
            <div>
                <label for="guest_shipping_name" class="mb-1.5 block text-xs font-bold text-slate-700">Nama penerima</label>
                <input id="guest_shipping_name" form="checkout-form" name="shipping_name" value="{{ old('shipping_name') }}" required maxlength="120" class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm outline-none focus:border-[#879366] focus:ring-4 focus:ring-[#edf0e7]" placeholder="Nama lengkap">
            </div>
            <div>
                <label for="guest_shipping_phone" class="mb-1.5 block text-xs font-bold text-slate-700">Nomor WhatsApp</label>
                <input id="guest_shipping_phone" form="checkout-form" name="shipping_phone" value="{{ old('shipping_phone') }}" required maxlength="40" class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm outline-none focus:border-[#879366] focus:ring-4 focus:ring-[#edf0e7]" placeholder="08xxxxxxxxxx">
            </div>
            <div class="sm:col-span-2">
                <label for="guest_shipping_address" class="mb-1.5 block text-xs font-bold text-slate-700">Alamat lengkap</label>
                <textarea id="guest_shipping_address" form="checkout-form" name="shipping_address" required maxlength="500" rows="2" class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm outline-none focus:border-[#879366] focus:ring-4 focus:ring-[#edf0e7]" placeholder="Nama jalan, nomor rumah, RT/RW, patokan">{{ old('shipping_address') }}</textarea>
            </div>
            <div>
                <label for="guest_shipping_city" class="mb-1.5 block text-xs font-bold text-slate-700">Kota/Kabupaten</label>
                <input id="guest_shipping_city" form="checkout-form" name="shipping_city" value="{{ old('shipping_city') }}" required maxlength="100" class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm outline-none focus:border-[#879366] focus:ring-4 focus:ring-[#edf0e7]" placeholder="Contoh: Bandung">
            </div>
            <div>
                <label for="guest_shipping_province" class="mb-1.5 block text-xs font-bold text-slate-700">Provinsi <span class="font-normal text-slate-400">(opsional)</span></label>
                <input id="guest_shipping_province" form="checkout-form" name="shipping_province" value="{{ old('shipping_province') }}" maxlength="100" class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm outline-none focus:border-[#879366] focus:ring-4 focus:ring-[#edf0e7]" placeholder="Contoh: Jawa Barat">
            </div>
        </div>
    </section>
@endguest

<form id="checkout-form" method="POST" action="{{ route('checkout.store') }}" class="mt-5 grid gap-5 lg:grid-cols-[1fr_340px]">
    @csrf

    {{-- Hidden shipping address inputs populated from selected saved address --}}
    @auth
        <input type="hidden" id="shipping_name" name="shipping_name" value="{{ $prefill['name'] }}">
        <input type="hidden" id="shipping_phone" name="shipping_phone" value="{{ $prefill['phone'] }}">
        <input type="hidden" id="shipping_address" name="shipping_address" value="{{ $prefill['address'] }}">
        <input type="hidden" id="shipping_city" name="shipping_city" value="{{ $prefill['city'] }}">
        <input type="hidden" id="shipping_province" name="shipping_province" value="{{ $prefill['province'] }}">
        <input type="hidden" id="shipping_postal_code" name="shipping_postal_code" value="{{ $prefill['postal_code'] }}">
    @endauth

    <div class="space-y-5">
        {{-- Optional Note --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <label for="note" class="mb-1.5 block text-xs font-bold text-slate-700">Catatan Pesanan <span class="font-normal text-slate-400">(opsional)</span></label>
            <input id="note" name="note" value="{{ old('note') }}" maxlength="500" placeholder="Patokan alamat, instruksi pengiriman, dll." class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm outline-none focus:border-[#879366] focus:ring-4 focus:ring-[#edf0e7]">
        </div>

        {{-- Payment --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <h2 class="flex items-center gap-2 text-sm font-extrabold uppercase tracking-[0.14em] text-slate-400">
                <span class="flex h-5 w-5 items-center justify-center rounded-full bg-[#c8ff4d] text-[10px] font-extrabold text-[#1e2a5b]">1</span>
                Metode Pembayaran
            </h2>
            @error('payment_method')<p class="mt-2 text-xs text-rose-600">{{ $message }}</p>@enderror

            @php
                $onlineMethods = collect($paymentMethods)->filter(fn ($label, $value) => str_starts_with($value, 'tripay_'));
                $paymentVisuals = [
                    'tripay_qris' => ['logo' => 'QRIS', 'description' => 'Scan & bayar', 'style' => 'background:#f1eaff;color:#6d28d9;'],
                    'tripay_briva' => ['logo' => 'BRI', 'description' => 'Virtual Account', 'style' => 'background:#e8f1ff;color:#1261b5;'],
                    'tripay_bniva' => ['logo' => 'BNI', 'description' => 'Virtual Account', 'style' => 'background:#fff0e5;color:#e05a13;'],
                    'tripay_bcava' => ['logo' => 'BCA', 'description' => 'Virtual Account', 'style' => 'background:#e8f4ff;color:#0876b9;'],
                    'tripay_mandiriva' => ['logo' => 'Mandiri', 'description' => 'Virtual Account', 'style' => 'background:#e8f0ff;color:#1e4aa8;'],
                    'tripay_bsiva' => ['logo' => 'BSI', 'description' => 'Virtual Account', 'style' => 'background:#e7f7ef;color:#087443;'],
                    'tripay_permata' => ['logo' => 'Permata', 'description' => 'Virtual Account', 'style' => 'background:#e9f5ff;color:#0876ae;'],
                    'tripay_ovo' => ['logo' => 'OVO', 'description' => 'Bayar dengan OVO', 'style' => 'background:#eee9ff;color:#5635b5;'],
                    'tripay_dana' => ['logo' => 'DANA', 'description' => 'Bayar dengan DANA', 'style' => 'background:#e5f3ff;color:#1674d1;'],
                    'tripay_shopeepay' => ['logo' => 'ShopeePay', 'description' => 'Bayar dengan ShopeePay', 'style' => 'background:#fff0eb;color:#ee4d2d;'],
                    'tripay_alfamart' => ['logo' => 'Alfa', 'description' => 'Bayar di Alfamart', 'style' => 'background:#fff0f1;color:#d71920;'],
                    'tripay_indomaret' => ['logo' => 'Indo', 'description' => 'Bayar di Indomaret', 'style' => 'background:#eaf4ff;color:#075ca8;'],
                ];
                $paymentLogoUrls = [
                    'tripay_qris' => 'https://cdn.simpleicons.org/qris/6d28d9',
                    'tripay_ovo' => 'https://cdn.simpleicons.org/ovo/5635b5',
                    'tripay_dana' => 'https://cdn.simpleicons.org/dana/1674d1',
                    'tripay_shopeepay' => 'https://cdn.simpleicons.org/shopeepay/ee4d2d',
                    'tripay_alfamart' => 'https://cdn.simpleicons.org/alfamart/d71920',
                    'tripay_indomaret' => 'https://cdn.simpleicons.org/indomaret/e31e24',
                ];
            @endphp

            <div class="mt-4 space-y-4">
                @if(false)
                <div>
                    <p class="mb-2 text-xs font-bold uppercase tracking-[0.12em] text-slate-400">Pembayaran Manual</p>
                    <div class="grid gap-3 sm:grid-cols-3">
                        @foreach ($manualMethods as $value => $label)
                            @php $visual = $paymentVisuals[$value] ?? ['logo' => 'PAY', 'description' => 'Pembayaran aman', 'style' => 'background:#f1f5f9;color:#475569;']; @endphp
                            <label class="group cursor-pointer">
                                <input type="radio" name="payment_method" value="{{ $value }}" class="peer sr-only" @checked(old('payment_method', 'transfer') === $value)>
                                <div class="relative flex min-h-[86px] items-center gap-3 rounded-2xl border border-slate-200 bg-white p-3.5 text-slate-700 transition hover:-translate-y-0.5 hover:border-slate-300 hover:shadow-sm peer-checked:border-[#9bc82f] peer-checked:bg-[#f7fbe9] peer-checked:text-[#1e2a5b] peer-checked:ring-2 peer-checked:ring-[#c8ff4d]">
                                    <span class="flex h-11 w-11 shrink-0 items-center justify-center overflow-hidden rounded-xl px-1 text-center text-[8px] font-black leading-[1.05] tracking-tight transition group-hover:brightness-95 peer-checked:ring-2 peer-checked:ring-[#c8ff4d]" style="{{ $visual['style'] }}">
                                        @if(isset($paymentLogoUrls[$value]))
                                            <img src="{{ $paymentLogoUrls[$value] }}" alt="{{ $label }}" class="max-h-7 max-w-10 object-contain" loading="lazy" onerror="this.style.display='none';this.nextElementSibling.style.display='inline'">
                                            <span class="hidden">{{ $visual['logo'] }}</span>
                                        @else
                                            {{ $visual['logo'] }}
                                        @endif
                                    </span>
                                    <span class="min-w-0"><span class="line-clamp-2 block text-xs font-extrabold leading-tight">{{ $label }}</span><span class="mt-1 line-clamp-2 block text-[10px] font-medium leading-tight text-slate-400">{{ $visual['description'] }}</span></span>
                                    <span class="absolute right-3 top-3 hidden h-4 w-4 items-center justify-center rounded-full bg-[#9bc82f] text-[10px] font-black text-white peer-checked:flex">✓</span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>
                </div>
                @endif
                <div>
                    <p class="mb-2 text-xs font-bold uppercase tracking-[0.12em] text-slate-400">Pembayaran Online (Otomatis)</p>
                    <div class="grid gap-3 sm:grid-cols-3">
                        @foreach ($onlineMethods as $value => $label)
                            @php $visual = $paymentVisuals[$value] ?? ['logo' => 'PAY', 'description' => 'Pembayaran otomatis', 'style' => 'background:#f1f5f9;color:#475569;']; @endphp
                            <label class="group cursor-pointer">
                                <input type="radio" name="payment_method" value="{{ $value }}" class="peer sr-only" @checked(old('payment_method') === $value)>
                                <div class="relative flex min-h-[86px] items-center gap-3 rounded-2xl border border-slate-200 bg-white p-3.5 text-slate-700 transition hover:-translate-y-0.5 hover:border-slate-300 hover:shadow-sm peer-checked:border-[#9bc82f] peer-checked:bg-[#f7fbe9] peer-checked:text-[#1e2a5b] peer-checked:ring-2 peer-checked:ring-[#c8ff4d]">
                                    <span class="flex h-11 w-11 shrink-0 items-center justify-center overflow-hidden rounded-xl px-1 text-center text-[8px] font-black leading-[1.05] tracking-tight transition group-hover:brightness-95 peer-checked:ring-2 peer-checked:ring-[#c8ff4d]" style="{{ $visual['style'] }}">
                                        @if(isset($paymentLogoUrls[$value]))
                                            <img src="{{ $paymentLogoUrls[$value] }}" alt="{{ $label }}" class="max-h-7 max-w-10 object-contain" loading="lazy" onerror="this.style.display='none';this.nextElementSibling.style.display='inline'">
                                            <span class="hidden">{{ $visual['logo'] }}</span>
                                        @else
                                            {{ $visual['logo'] }}
                                        @endif
                                    </span>
                                    <span class="min-w-0"><span class="line-clamp-2 block text-xs font-extrabold leading-tight">{{ $label }}</span><span class="mt-1 line-clamp-2 block text-[10px] font-medium leading-tight text-slate-400">{{ $visual['description'] }}</span></span>
                                    <span class="absolute right-3 top-3 hidden h-4 w-4 items-center justify-center rounded-full bg-[#9bc82f] text-[10px] font-black text-white peer-checked:flex">✓</span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>
            <p class="mt-3 text-xs text-slate-400">Pembayaran diproses otomatis melalui Tripay dan kamu akan diarahkan ke halaman pembayaran.</p>
        </div>
    </div>

    {{-- Summary --}}
    <aside class="lg:sticky lg:top-20 lg:h-fit">
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <h2 class="text-sm font-extrabold uppercase tracking-[0.14em] text-slate-400">Ringkasan Pesanan</h2>

            <div class="mt-3 space-y-3">
                @foreach ($groups as $group)
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-[0.12em] text-slate-400">@ {{ $group['seller']->username }}</p>
                        <div class="mt-1.5 space-y-1.5">
                            @foreach ($group['items'] as $row)
                                <div class="flex items-start justify-between gap-3 text-sm">
                                    <span class="min-w-0 flex-1 text-slate-600">{{ $row['quantity'] }} &times; <span class="font-semibold text-slate-800">{{ $row['product']->name }}</span></span>
                                    <span class="shrink-0 font-bold text-slate-800">Rp {{ number_format($row['subtotal'], 0, ',', '.') }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-4 flex items-center justify-between border-t border-slate-200 pt-3">
                <span class="text-sm font-bold text-slate-500">Total</span>
                <span class="text-lg font-extrabold text-[#14213d]">Rp {{ number_format($total, 0, ',', '.') }}</span>
            </div>

            <button type="submit" class="mt-4 w-full rounded-xl bg-[#c8ff4d] px-5 py-3 text-sm font-extrabold text-[#1e2a5b] shadow-sm transition hover:bg-[#ddff91]">Buat Pesanan</button>
            <a href="{{ route('cart.index') }}" class="mt-2 block w-full rounded-xl border border-slate-200 px-5 py-2.5 text-center text-sm font-semibold text-slate-600 hover:bg-slate-50">Kembali ke Keranjang</a>
        </div>
    </aside>
</form>
@endsection
