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
    @if ($addresses->isNotEmpty())
        <section class="mt-5 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm"
                 x-data="{
                     addresses: @js($addressesPayload),
                     fill(address) {
                         document.getElementById('shipping_name').value = address.recipient_name || '';
                         document.getElementById('shipping_phone').value = address.phone || '';
                         document.getElementById('shipping_address').value = address.address || '';
                         document.getElementById('shipping_city').value = address.city || '';
                         document.getElementById('shipping_province').value = address.province || '';
                         document.getElementById('shipping_postal_code').value = address.postal_code || '';
                         document.getElementById('checkout-form')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
                     },
                     clear() {
                         ['shipping_name', 'shipping_phone', 'shipping_address', 'shipping_city', 'shipping_province', 'shipping_postal_code']
                             .forEach((id) => { document.getElementById(id).value = ''; });
                         document.getElementById('shipping_name').focus();
                     },
                 }">
            <div class="flex items-center justify-between gap-3">
                <h2 class="flex items-center gap-2 text-sm font-extrabold uppercase tracking-[0.14em] text-slate-400">
                    <span class="flex h-5 w-5 items-center justify-center rounded-full bg-[#1e2a5b] text-[10px] font-extrabold text-white">★</span>
                    Alamat Tersimpan
                </h2>
                <button type="button" @click="clear()" class="text-xs font-bold text-[#1e2a5b] underline underline-offset-4 hover:text-[#303e76]">+ Tambah alamat baru</button>
            </div>

            <div class="mt-3 grid gap-3 sm:grid-cols-2">
                @foreach ($addresses as $index => $address)
                    <div class="group relative flex flex-col rounded-xl border border-slate-200 p-3.5 transition hover:border-[#879366] hover:bg-[#f6f8ee]">
                        <button type="button" @click="fill(addresses[{{ $index }}])" class="text-left">
                            <div class="flex items-center gap-2">
                                <p class="truncate text-sm font-bold text-slate-900">{{ $address->label ?: $address->recipient_name }}</p>
                                @if ($address->is_default)
                                    <span class="rounded-full bg-[#c8ff4d] px-2 py-0.5 text-[10px] font-bold text-[#1e2a5b]">Utama</span>
                                @endif
                            </div>
                            <p class="mt-1 text-xs text-slate-500">{{ $address->recipient_name }} &middot; {{ $address->phone }}</p>
                            <p class="mt-1 line-clamp-2 text-xs leading-5 text-slate-400">{{ $address->summary }}</p>
                        </button>

                        <form method="POST" action="{{ route('addresses.destroy', $address) }}" class="absolute right-2 top-2" onsubmit="return confirm('Hapus alamat ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" aria-label="Hapus alamat" title="Hapus alamat" class="flex h-6 w-6 items-center justify-center rounded-full text-slate-300 transition hover:bg-rose-50 hover:text-rose-500">&times;</button>
                        </form>
                    </div>
                @endforeach
            </div>
            <p class="mt-3 text-xs text-slate-400">Klik salah satu alamat untuk memakainya, atau tambah alamat baru di bawah.</p>
        </section>
    @endif
@endauth

<form id="checkout-form" method="POST" action="{{ route('checkout.store') }}" class="mt-5 grid gap-5 lg:grid-cols-[1fr_340px]">
    @csrf

    <div class="space-y-5">
        {{-- Shipping --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <h2 class="flex items-center gap-2 text-sm font-extrabold uppercase tracking-[0.14em] text-slate-400">
                <span class="flex h-5 w-5 items-center justify-center rounded-full bg-[#c8ff4d] text-[10px] font-extrabold text-[#1e2a5b]">1</span>
                Alamat Pengiriman
            </h2>

            <div class="mt-4 grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="shipping_name" class="mb-1.5 block text-xs font-bold text-slate-700">Nama Penerima <span class="text-rose-500">*</span></label>
                    <input id="shipping_name" name="shipping_name" value="{{ $prefill['name'] }}" required maxlength="120" class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm outline-none focus:border-[#879366] focus:ring-4 focus:ring-[#edf0e7]">
                    @error('shipping_name')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="shipping_phone" class="mb-1.5 block text-xs font-bold text-slate-700">No. HP / WhatsApp <span class="text-rose-500">*</span></label>
                    <input id="shipping_phone" name="shipping_phone" value="{{ $prefill['phone'] }}" required maxlength="40" placeholder="08xxxxxxxxxx" class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm outline-none focus:border-[#879366] focus:ring-4 focus:ring-[#edf0e7]">
                    @error('shipping_phone')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>
                <div class="sm:col-span-2">
                    <label for="shipping_address" class="mb-1.5 block text-xs font-bold text-slate-700">Alamat Lengkap <span class="text-rose-500">*</span></label>
                    <textarea id="shipping_address" name="shipping_address" rows="3" required maxlength="500" placeholder="Jalan, nomor rumah, RT/RW, kelurahan, kecamatan" class="w-full resize-y rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm outline-none focus:border-[#879366] focus:ring-4 focus:ring-[#edf0e7]">{{ $prefill['address'] }}</textarea>
                    @error('shipping_address')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="shipping_city" class="mb-1.5 block text-xs font-bold text-slate-700">Kota/Kabupaten <span class="text-rose-500">*</span></label>
                    <input id="shipping_city" name="shipping_city" value="{{ $prefill['city'] }}" required maxlength="100" class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm outline-none focus:border-[#879366] focus:ring-4 focus:ring-[#edf0e7]">
                    @error('shipping_city')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="shipping_province" class="mb-1.5 block text-xs font-bold text-slate-700">Provinsi</label>
                    <input id="shipping_province" name="shipping_province" value="{{ $prefill['province'] }}" maxlength="100" class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm outline-none focus:border-[#879366] focus:ring-4 focus:ring-[#edf0e7]">
                    @error('shipping_province')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="shipping_postal_code" class="mb-1.5 block text-xs font-bold text-slate-700">Kode Pos</label>
                    <input id="shipping_postal_code" name="shipping_postal_code" value="{{ $prefill['postal_code'] }}" maxlength="12" class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm outline-none focus:border-[#879366] focus:ring-4 focus:ring-[#edf0e7]">
                    @error('shipping_postal_code')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>
                <div class="sm:col-span-2">
                    <label for="note" class="mb-1.5 block text-xs font-bold text-slate-700">Catatan <span class="font-normal text-slate-400">(opsional)</span></label>
                    <input id="note" name="note" value="{{ old('note') }}" maxlength="500" placeholder="Patokan alamat, warna/ukuran, dll." class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm outline-none focus:border-[#879366] focus:ring-4 focus:ring-[#edf0e7]">
                </div>
            </div>

            @auth
                <div class="mt-4 flex flex-col gap-2 rounded-xl bg-[#f6f8ee] p-3.5 sm:flex-row sm:items-center">
                    <label class="flex items-center gap-2 text-xs font-semibold text-slate-700">
                        <input type="checkbox" name="save_address" value="1" @checked(old('save_address', true)) class="rounded border-slate-300 text-[#4d7c0f] focus:ring-[#c8ff4d]">
                        Simpan alamat ini untuk dipakai lagi
                    </label>
                    <input type="text" name="address_label" value="{{ old('address_label') }}" maxlength="60" placeholder="Label (mis. Rumah)" class="w-full rounded-lg border border-slate-200 px-3 py-1.5 text-xs outline-none focus:border-[#879366] focus:ring-2 focus:ring-[#edf0e7] sm:ml-auto sm:w-44">
                </div>
            @endauth
        </div>

        {{-- Payment --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <h2 class="flex items-center gap-2 text-sm font-extrabold uppercase tracking-[0.14em] text-slate-400">
                <span class="flex h-5 w-5 items-center justify-center rounded-full bg-[#c8ff4d] text-[10px] font-extrabold text-[#1e2a5b]">2</span>
                Metode Pembayaran
            </h2>
            @error('payment_method')<p class="mt-2 text-xs text-rose-600">{{ $message }}</p>@enderror

            <div class="mt-4 space-y-4">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.12em] text-slate-400 mb-2">Pembayaran Manual</p>
                    <div class="grid gap-3 sm:grid-cols-3">
                        @foreach ([
                            'transfer' => 'Transfer Bank',
                            'ewallet' => 'E-Wallet',
                            'cod' => 'Bayar di Tempat (COD)',
                        ] as $value => $label)
                            <label class="cursor-pointer">
                                <input type="radio" name="payment_method" value="{{ $value }}" class="peer sr-only" @checked(old('payment_method', 'transfer') === $value)>
                                <div class="rounded-xl border border-slate-200 px-4 py-3 text-sm font-bold text-slate-700 transition peer-checked:border-[#879366] peer-checked:bg-[#f6f8ee] peer-checked:text-[#1e2a5b] peer-checked:ring-2 peer-checked:ring-[#c8ff4d]">
                                    {{ $label }}
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.12em] text-slate-400 mb-2">Pembayaran Online (TreePay)</p>
                    <div class="grid gap-3 sm:grid-cols-2">
                        @foreach ([
                            'treepay_qris' => 'QRIS',
                            'treepay_va' => 'Virtual Account',
                            'treepay_ewallet' => 'E-Wallet',
                            'treepay_cc' => 'Kartu Kredit/Debit',
                        ] as $value => $label)
                            <label class="cursor-pointer">
                                <input type="radio" name="payment_method" value="{{ $value }}" class="peer sr-only" @checked(old('payment_method') === $value)>
                                <div class="rounded-xl border border-slate-200 px-4 py-3 text-sm font-bold text-slate-700 transition peer-checked:border-[#879366] peer-checked:bg-[#f6f8ee] peer-checked:text-[#1e2a5b] peer-checked:ring-2 peer-checked:ring-[#c8ff4d]">
                                    {{ $label }}
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>
            <p class="mt-3 text-xs text-slate-400">Pembayaran dikonfirmasi manual oleh penjual. Untuk transfer/e-wallet, kamu bisa mengunggah bukti bayar setelah pesanan dibuat.</p>
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
