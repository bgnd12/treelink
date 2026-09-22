@php
    $theme = \App\Models\Profile::AVAILABLE_THEMES[$profile->theme] ?? \App\Models\Profile::AVAILABLE_THEMES['aurora'];
    $btnClasses = match($profile->button_style) {
        'pill' => 'rounded-full',
        'square' => 'rounded-md',
        'outline' => 'rounded-xl border-2 border-white bg-transparent',
        default => 'rounded-xl',
    };
    $btnBg = $profile->button_style === 'outline' ? 'bg-transparent' : ($theme['text'] === 'text-white' ? 'bg-white/15 backdrop-blur' : 'bg-white');
    $featuredLink = $links->firstWhere('id', $profile->featured_link_id);
@endphp

<div class="mx-auto w-[280px]">
    <div class="rounded-[2.5rem] border-8 border-ink-900 bg-ink-900 shadow-2xl overflow-hidden">
        <div class="h-[520px] overflow-y-auto bg-gradient-to-b {{ $theme['from'] }} {{ $theme['to'] }} {{ $theme['text'] }} px-5 pt-9 pb-8 text-center">
            <img src="{{ $profile->avatar_url }}" class="w-20 h-20 rounded-full object-cover mx-auto border-4 {{ $theme['text'] === 'text-white' ? 'border-white/40' : 'border-black/10' }}" alt="Avatar">
            <p class="mt-3 font-bold">{{ $profile->display_name ?: $user->name }}</p>
            <p class="text-xs opacity-70">@{{ $user->username }}</p>
            @if ($profile->bio)
                <p class="text-xs opacity-80 mt-2 px-2">{{ $profile->bio }}</p>
            @endif

            @if (!empty(array_filter($profile->social_links ?? [])))
                <div class="flex justify-center flex-wrap gap-2 mt-4">
                    @foreach (array_filter($profile->social_links ?? []) as $platform => $url)
                        <span class="w-7 h-7 rounded-full {{ $btnBg }} flex items-center justify-center text-xs">
                            {{ ['instagram' => '◎', 'tiktok' => '♪', 'youtube' => '▶', 'facebook' => 'f', 'twitter' => '𝕏', 'whatsapp' => '◉'][$platform] ?? strtoupper(substr($platform, 0, 1)) }}
                        </span>
                    @endforeach
                </div>
            @endif

            <div class="mt-5 space-y-2.5">
                @forelse ($links->where('is_active', true) as $link)
                    <div class="w-full py-2.5 px-4 {{ $btnClasses }} {{ $btnBg }} text-xs font-semibold truncate {{ $profile->animations_enabled ? 'animate-fade-up' : '' }} {{ $featuredLink?->id === $link->id ? 'ring-2 ring-yellow-300 scale-[1.03]' : '' }}">
                        @if ($featuredLink?->id === $link->id)⭐ @endif
                        {{ $link->title }}
                    </div>
                @empty
                    <p class="text-xs opacity-60 mt-6">Belum ada link aktif</p>
                @endforelse
            </div>
        </div>
    </div>
    <p class="text-center text-xs text-ink-400 mt-3">Tampilan ini diperbarui otomatis</p>
</div>
