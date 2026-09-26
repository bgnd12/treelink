@extends('layouts.dashboard')

@section('title', __('Short Links'))
@section('page-title', __('Short Links'))

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="bg-white rounded-2xl shadow-sm border border-ink-100 p-6">
        <h2 class="text-xl font-bold mb-4">{{ __('Create New Short Link') }}</h2>
        <form action="{{ route('short-links.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-ink-700 mb-1">{{ __('Destination URL') }}</label>
                <input type="url" name="destination_url" required placeholder="https://example.com/very-long-url" class="w-full rounded-xl border-ink-200 focus:border-brand-500 focus:ring-brand-500">
            </div>
            <div>
                <label class="block text-sm font-semibold text-ink-700 mb-1">{{ __('Custom Slug (Optional)') }}</label>
                <div class="flex">
                    <span class="inline-flex items-center px-4 rounded-l-xl border border-r-0 border-ink-200 bg-ink-50 text-ink-500 text-sm">
                        {{ url('/') }}/
                    </span>
                    <input type="text" name="slug" placeholder="my-event" class="flex-1 rounded-r-xl border-ink-200 focus:border-brand-500 focus:ring-brand-500">
                </div>
                <p class="text-xs text-ink-400 mt-1">{{ __('Leave blank to generate randomly.') }}</p>
            </div>
            <button type="submit" class="px-6 py-2.5 bg-brand-600 text-white font-semibold rounded-xl hover:bg-brand-700 transition">
                {{ __('Create Link') }}
            </button>
        </form>
    </div>

    <div class="space-y-4">
        <h3 class="text-lg font-bold text-ink-900">{{ __('Your Short Links') }}</h3>
        @forelse($shortLinks as $link)
            <div class="bg-white rounded-2xl shadow-sm border border-ink-100 p-5 flex items-center justify-between">
                <div class="flex-1 min-w-0 pr-4">
                    <a href="{{ url('/' . $link->slug) }}" target="_blank" class="font-bold text-brand-600 text-lg hover:underline block truncate">
                        {{ url('/' . $link->slug) }}
                    </a>
                    <p class="text-sm text-ink-500 truncate mt-1">➡ {{ $link->destination_url }}</p>
                    <div class="flex items-center gap-4 mt-3 text-xs font-semibold text-ink-400">
                        <span class="flex items-center gap-1"><span class="text-lg">📊</span> {{ number_format($link->clicks) }} {{ __('Clicks') }}</span>
                        <span class="flex items-center gap-1"><span class="text-lg">📅</span> {{ $link->created_at->format('M d, Y') }}</span>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <form action="{{ route('short-links.toggle', $link) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="p-2 rounded-xl {{ $link->is_active ? 'bg-emerald-50 text-emerald-600 hover:bg-emerald-100' : 'bg-ink-50 text-ink-400 hover:bg-ink-100' }}" title="{{ __('Toggle Status') }}">
                            {{ $link->is_active ? '✅' : '❌' }}
                        </button>
                    </form>
                    <form action="{{ route('short-links.destroy', $link) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure you want to delete this short link?') }}')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-2 rounded-xl bg-rose-50 text-rose-600 hover:bg-rose-100" title="{{ __('Delete') }}">
                            🗑️
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="text-center py-12 bg-white rounded-2xl border border-ink-100 border-dashed">
                <div class="text-4xl mb-3">🔗</div>
                <h4 class="text-lg font-bold text-ink-900">{{ __('No short links yet') }}</h4>
                <p class="text-sm text-ink-500 mt-1">{{ __('Create your first short link above.') }}</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
