@props(['label', 'name', 'type' => 'text', 'value' => '', 'placeholder' => '', 'required' => false])

<div>
    <label for="{{ $name }}" class="block text-sm font-semibold text-ink-800 mb-1.5">{{ $label }}</label>
    <input
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $name }}"
        value="{{ old($name, $value) }}"
        placeholder="{{ $placeholder }}"
        @if($required) required @endif
        {{ $attributes->merge(['class' => 'w-full px-4 py-3 rounded-xl border border-ink-200 focus:border-brand-500 focus:ring-4 focus:ring-brand-100 outline-none transition text-sm placeholder:text-ink-300']) }}
    >
    @error($name)
        <p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p>
    @enderror
</div>
