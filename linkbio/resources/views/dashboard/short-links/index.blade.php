@extends('layouts.dashboard')

@section('title', __('Short Links'))
@section('page-title', __('Short Links'))

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="bg-white rounded-2xl shadow-sm border border-ink-100 p-6">
        <h2 class="text-xl font-bold mb-4">{{ __('Create New Short Link') }}</h2>

        {{-- Both forms post the same field names, so a rejection is tied back to the
             form it came from via `source`, and to the row it targeted via `slug_id`.
             That lets the failed form re-render with the user's own input still in
             place while the untouched parts of the page reset to their normal state. --}}
        @php
            $failedSource = old('source');
            $failedSlugId = $errors->get('slug_id')[0] ?? null;
            $failedSlugId = $failedSlugId !== null ? (int) $failedSlugId : null;
            $failedLink = $failedSlugId !== null ? $shortLinks->firstWhere('id', $failedSlugId) : null;

            $editingFailed = $failedSource === 'top' && $failedLink !== null;
            $failedSlugId = $failedLink !== null ? $failedSlugId : null;

            $rowFailed = $failedSource === 'row';
            $rowSlugValue = $rowFailed ? old('slug') : $failedLink?->slug;
            $rowDestValue = $rowFailed ? old('destination_url') : ($failedLink?->destination_url ?? '');
        @endphp

        <form action="{{ $editingFailed ? route('dashboard.short-links.update', $failedLink) : route('dashboard.short-links.store') }}"
              method="POST" class="space-y-4">
            @csrf
            @if ($editingFailed) @method('PUT') @endif

            <div>
                <label class="block text-sm font-semibold text-ink-700 mb-1">{{ __('Destination URL') }}</label>
                <input type="url" name="destination_url" required
                       value="{{ $rowFailed ? '' : old('destination_url', $failedLink?->destination_url) }}"
                       placeholder="https://example.com/very-long-url"
                       @class([
                           'w-full rounded-xl px-3 py-2.5 outline-none text-sm transition',
                           'border-rose-300 bg-rose-50/40 focus:border-rose-500 focus:ring-4 focus:ring-rose-50' => $errors->has('destination_url'),
                           'border-ink-200 focus:border-brand-500 focus:ring-4 focus:ring-brand-100' => ! $errors->has('destination_url'),
                       ])>
                @error('destination_url')
                    <p class="mt-2 flex items-start gap-1.5 text-xs font-semibold text-rose-600">
                        <span aria-hidden="true">⚠</span>
                        <span>{{ $message }}</span>
                    </p>
                @enderror
            </div>

            {{-- old('slug') falls back to the row being edited, so a rejected duplicate
                 address is handed back to the user instead of being silently cleared. --}}
            <x-short-link-slug-input
                :required="$editingFailed"
                :value="$rowFailed ? '' : old('slug', $failedLink?->slug)" />

            <input type="hidden" name="source" value="top">
            @if ($editingFailed)
                <input type="hidden" name="slug_id" value="{{ $failedSlugId }}">
            @endif

            <div class="flex items-center gap-3">
                <button type="submit" class="px-6 py-2.5 bg-brand-600 text-white font-semibold rounded-xl hover:bg-brand-700 transition">
                    {{ $editingFailed ? __('Save Changes') : __('Create Link') }}
                </button>
                @if ($editingFailed)
                    <a href="{{ route('dashboard.short-links.index') }}"
                       class="text-sm font-semibold text-ink-500 hover:text-ink-700">{{ __('Cancel') }}</a>
                @endif
            </div>
        </form>
    </div>

    <div class="space-y-4">
        <h3 class="text-lg font-bold text-ink-900">{{ __('Your Short Links') }}</h3>
        @forelse($shortLinks as $link)
            {{-- x-data owns the editing state, so the rejected input survives the round
                 trip while the rest of the list keeps rendering normally. --}}
            <div class="bg-white rounded-2xl shadow-sm border border-ink-100 p-5"
                 x-data="{
                     editing: @js($rowFailed && $failedSlugId === $link->id),
                     draft: {
                         slug: @js($rowSlugValue),
                         destination_url: @js($rowDestValue),
                     },
                 }">

                <div x-show="!editing" class="flex items-center justify-between">
                    <div class="flex-1 min-w-0 pr-4">
                        <a href="{{ url('/' . $link->slug) }}" target="_blank" rel="noopener"
                           class="font-bold text-brand-600 text-lg hover:underline block truncate">
                            {{ url('/' . $link->slug) }}
                        </a>
                        <p class="text-sm text-ink-500 truncate mt-1">➡ {{ $link->destination_url }}</p>
                        <div class="flex items-center gap-4 mt-3 text-xs font-semibold text-ink-400">
                            <span class="flex items-center gap-1"><span class="text-lg">📊</span> {{ number_format($link->clicks) }} {{ __('Clicks') }}</span>
                            <span class="flex items-center gap-1"><span class="text-lg">📅</span> {{ $link->created_at->format('M d, Y') }}</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="button" @click="editing = true"
                                class="p-2 rounded-xl bg-ink-50 text-ink-600 hover:bg-ink-100" title="{{ __('Edit') }}">
                            ✏️
                        </button>
                        <form action="{{ route('dashboard.short-links.toggle', $link) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="p-2 rounded-xl {{ $link->is_active ? 'bg-emerald-50 text-emerald-600 hover:bg-emerald-100' : 'bg-ink-50 text-ink-400 hover:bg-ink-100' }}" title="{{ __('Toggle Status') }}">
                                {{ $link->is_active ? '✅' : '❌' }}
                            </button>
                        </form>
                        <form action="{{ route('dashboard.short-links.destroy', $link) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure you want to delete this short link?') }}')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 rounded-xl bg-rose-50 text-rose-600 hover:bg-rose-100" title="{{ __('Delete') }}">
                                🗑️
                            </button>
                        </form>
                    </div>
                </div>

                <form x-show="editing" x-cloak method="POST"
                      action="{{ route('dashboard.short-links.update', $link) }}" class="space-y-3">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="source" value="row">
                    <input type="hidden" name="slug_id" value="{{ $link->id }}">

                    <div>
                        <label class="block text-sm font-semibold text-ink-700 mb-1">{{ __('Destination URL') }}</label>
                        <input type="url" name="destination_url" required x-model="draft.destination_url"
                               @class([
                                   'w-full rounded-xl px-3 py-2.5 outline-none text-sm transition',
                                   'border-rose-300 bg-rose-50/40 focus:border-rose-500 focus:ring-4 focus:ring-rose-50' => $errors->has('destination_url'),
                                   'border-ink-200 focus:border-brand-500 focus:ring-4 focus:ring-brand-100' => ! $errors->has('destination_url'),
                               ])>
                    </div>

                    <div class="flex rounded-xl border transition overflow-hidden focus-within:ring-4 focus-within:ring-brand-100
                                {{ $errors->has('slug') ? 'border-rose-300 focus-within:border-rose-500 focus-within:ring-rose-50' : 'border-ink-200 focus-within:border-brand-500' }}">
                        <span class="inline-flex items-center px-4 border-r bg-ink-50 text-ink-500 text-sm
                                     {{ $errors->has('slug') ? 'border-rose-300' : 'border-ink-200' }}">
                            {{ url('/') }}/
                        </span>
                        <input type="text" name="slug" x-model="draft.slug" maxlength="50" required
                               spellcheck="false" autocomplete="off" placeholder="my-event"
                               @class([
                                   'flex-1 px-3 py-2.5 outline-none text-sm',
                                   'border-rose-300 bg-rose-50/40' => $errors->has('slug'),
                                   'bg-white' => ! $errors->has('slug'),
                               ])>
                    </div>

                    @error('slug')
                        <p class="flex items-start gap-1.5 text-xs font-semibold text-rose-600">
                            <span aria-hidden="true">⚠</span>
                            <span>{{ $message }}</span>
                        </p>
                    @enderror

                    <div class="flex items-center gap-3 pt-1">
                        <button type="submit" class="px-5 py-2 bg-brand-600 text-white text-sm font-semibold rounded-xl hover:bg-brand-700 transition">
                            {{ __('Save Changes') }}
                        </button>
                        <button type="button" @click="editing = false"
                                class="text-sm font-semibold text-ink-500 hover:text-ink-700">{{ __('Cancel') }}</button>
                    </div>
                </form>
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
