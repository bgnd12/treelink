@props([
    'name' => 'slug',
    'value' => null,
    'label' => null,
    'required' => false,
    'placeholder' => 'my-event',
    'hint' => null,
])

@php
    $label ??= $required
        ? __('Custom Address')
        : __('Custom Address (Optional)');

    $hint ??= $required
        ? __('The address is unique across all of TreeLink.')
        : __('Leave blank to generate randomly.');
@endphp

<div>
    <label class="block text-sm font-semibold text-ink-700 mb-1">{{ $label }}</label>
    <div class="flex rounded-xl border transition overflow-hidden focus-within:ring-4 focus-within:ring-brand-100
                {{ $errors->has($name) ? 'border-rose-300 focus-within:border-rose-500 focus-within:ring-rose-50' : 'border-ink-200 focus-within:border-brand-500' }}">
        <span class="inline-flex items-center px-4 border-r bg-ink-50 text-ink-500 text-sm
                     {{ $errors->has($name) ? 'border-rose-300' : 'border-ink-200' }}">
            {{ url('/') }}/
        </span>
        <input type="text" name="{{ $name }}" value="{{ $value }}"
               placeholder="{{ $placeholder }}" maxlength="50" spellcheck="false" autocomplete="off"
               @if ($required) required @endif
               @class([
                   'flex-1 px-3 py-2.5 outline-none text-sm',
                   'border-rose-300 bg-rose-50/40' => $errors->has($name),
                   'bg-white' => ! $errors->has($name),
               ])>
    </div>

    @error($name)
        <p class="mt-2 flex items-start gap-1.5 text-xs font-semibold text-rose-600">
            <span aria-hidden="true">⚠</span>
            <span>{{ $message }}</span>
        </p>
    @enderror

    <p class="text-xs text-ink-400 mt-1">{{ $hint }}</p>
</div>
