@extends('layouts.dashboard')

@section('title', 'Shop')
@section('page-title', 'Shop')

@section('content')
    @php
        $products = [
            ['name' => 'Story Kit', 'price' => 'Rp299.000', 'stock' => '12 pcs', 'status' => 'Live', 'image' => 'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?auto=format&fit=crop&w=900&q=80'],
            ['name' => 'Brand Mentoring', 'price' => 'Rp550.000', 'stock' => '7 pcs', 'status' => 'Live', 'image' => 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=900&q=80'],
            ['name' => 'Social Pack', 'price' => 'Rp180.000', 'stock' => '26 pcs', 'status' => 'Draft', 'image' => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=900&q=80'],
            ['name' => 'Creator Bundle', 'price' => 'Rp890.000', 'stock' => '5 pcs', 'status' => 'Live', 'image' => 'https://images.unsplash.com/photo-1524758631624-e2822e304c36?auto=format&fit=crop&w=900&q=80'],
        ];
    @endphp

    <div class="space-y-6">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-violet-500">Shop</p>
                <h2 class="mt-2 text-3xl font-extrabold tracking-[-0.06em] text-slate-900">Sell products and services with style.</h2>
            </div>
            <button class="rounded-2xl bg-gradient-to-r from-violet-600 to-purple-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-violet-200">+ Add Product</button>
        </div>

        <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-4">
            @foreach ($products as $product)
                <article class="overflow-hidden rounded-[28px] border border-[#ece6ff] bg-white shadow-[0_12px_30px_rgba(124,58,237,0.05)]">
                    <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}" class="h-48 w-full object-cover" />
                    <div class="space-y-3 p-4">
                        <div class="flex items-center justify-between">
                            <span class="rounded-full {{ $product['status'] === 'Live' ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }} px-2 py-1 text-[10px] font-bold uppercase tracking-[0.18em]">{{ $product['status'] }}</span>
                            <span class="text-[11px] text-slate-500">{{ $product['stock'] }}</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-extrabold tracking-[-0.04em] text-slate-900">{{ $product['name'] }}</h3>
                            <p class="mt-2 text-xl font-extrabold text-violet-600">{{ $product['price'] }}</p>
                        </div>
                        <div class="flex gap-2 pt-2">
                            <button class="flex-1 rounded-2xl border border-[#ece6ff] bg-white px-3 py-2.5 text-sm font-semibold text-slate-700">Edit</button>
                            <button class="flex-1 rounded-2xl bg-slate-900 px-3 py-2.5 text-sm font-semibold text-white">View</button>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
@endsection
