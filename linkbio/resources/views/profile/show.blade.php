<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $profile->seo_title ?: ($profile->display_name ?: $profileUser->name).' — Semua link saya' }}</title>
    <meta name="description" content="{{ $profile->seo_description ?: ($profile->bio ?: 'Lihat semua link '.$profileUser->name.' di satu halaman.') }}">

    {{-- Open Graph for nice link previews when shared --}}
    <meta property="og:title" content="{{ $profile->seo_title ?: ($profile->display_name ?: $profileUser->name) }}">
    <meta property="og:description" content="{{ $profile->seo_description ?: $profile->bio }}">
    <meta property="og:image" content="{{ $profile->avatar_url }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Playfair+Display:wght@600;700&family=JetBrains+Mono:wght@500&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @php
        $theme = $profile->themeConfig();
        $isLight = $theme['text'] !== 'text-white';
        $fontFamily = \App\Models\Profile::FONT_FAMILIES[$profile->font] ?? \App\Models\Profile::FONT_FAMILIES['sans'];

        // Background ------------------------------------------------------------------
        $bgType = $profile->background_type;
        $bgClass = '';
        $bgStyle = '';
        $bgOverlay = false;
        if ($bgType === 'gradient') {
            $bgClass = 'bg-gradient-to-b '.$theme['from'].' '.$theme['to'];
        } elseif ($bgType === 'solid') {
            $bgStyle = 'background-color:'.($profile->background_value ?: $theme['pattern_bg'] ?: '#7c4dff').';';
        } elseif ($bgType === 'pattern') {
            $bgClass = 'pattern-'.($profile->background_value ?: 'dots');
            $bgStyle = 'background-color:'.($theme['pattern_bg'] ?: '#3f1c99').';';
        } elseif ($bgType === 'image' && $profile->background_image_url) {
            $bgStyle = "background-image:url('".$profile->background_image_url."');background-size:cover;background-position:center;";
            $bgOverlay = true;
        }

        // Buttons ----------------------------------------------------------------------
        $btnRadius = $profile->buttonRadiusPx();
        $btnBorder = $profile->button_style === 'outline' ? max((int) $profile->button_border_width, 2) : (int) $profile->button_border_width;
        $btnColor = $profile->button_color ?: $theme['accent'];
        $btnText = $profile->buttonTextColor();
        $btnStyle = $profile->button_style === 'outline'
            ? 'background:transparent;color:'.($isLight ? '#111827' : $theme['accent']).';'
            : "background:{$btnColor};color:{$btnText};";
        $btnStyle .= "border-radius:{$btnRadius}px;border:{$btnBorder}px solid ".($isLight ? 'rgba(0,0,0,.35)' : 'rgba(255,255,255,.45)').';';
        $btnStyle .= $profile->button_shadow ? 'box-shadow:0 10px 28px -10px rgba(0,0,0,.45);' : '';

        $socials = array_filter($profile->social_links ?? []);
        $layout = $profile->header_layout ?? 'classic';
        $animate = (bool) $profile->animation_enabled;
        $hoverClass = 'transition-all duration-200 hover:-translate-y-0.5';
        $shadowClass = $profile->button_shadow ? ' hover:shadow-lg' : '';
    @endphp
</head>
<body class="min-h-screen {{ $theme['text'] }} {{ $bgClass }}" style="{{ $bgStyle }} font-family:{{ $fontFamily }}">

    @if ($bgOverlay)
        <div class="fixed inset-0 bg-black/35"></div>
    @endif

    <div class="relative min-h-screen flex flex-col items-center px-5 py-14 sm:py-20 {{ $animate ? 'animate-fade-up' : '' }}">
        <div class="w-full max-w-md mx-auto text-center">

            {{-- ============ HEADER LAYOUT ============ --}}
            @if ($layout === 'banner')
                <div class="-mt-8">
                    <div class="h-28 rounded-b-3xl {{ $isLight ? 'bg-black/10' : 'bg-white/15 backdrop-blur' }}"></div>
                    <img src="{{ $profile->avatar_url }}" alt="{{ $profileUser->name }}"
                         class="w-24 h-24 rounded-full object-cover mx-auto -mt-12 border-4 {{ $isLight ? 'border-gray-200 shadow-xl' : 'border-white/80 shadow-xl' }}">
                    <h1 class="mt-3 text-xl font-bold">{{ $profile->display_name ?: $profileUser->name }}</h1>
                    <p class="text-sm opacity-70">{{ $profileUser->username }}</p>
                    @if ($profile->bio)<p class="mt-2 text-sm opacity-90 leading-relaxed px-2">{{ $profile->bio }}</p>@endif
                </div>
            @elseif ($layout === 'cutout')
                <div class="relative mx-auto mt-2 w-24 h-24">
                    <div class="absolute inset-2 rotate-45 rounded-3xl {{ $isLight ? 'bg-white/80' : 'bg-white/20' }}"></div>
                    <img src="{{ $profile->avatar_url }}" alt="{{ $profileUser->name }}"
                         class="absolute inset-0 w-full h-full rounded-full object-cover border-4 {{ $isLight ? 'border-white' : 'border-white/80' }}">
                </div>
                <h1 class="mt-5 text-xl font-bold">{{ $profile->display_name ?: $profileUser->name }}</h1>
                <p class="text-sm opacity-70">{{ $profileUser->username }}</p>
                @if ($profile->bio)<p class="mt-2 text-sm opacity-90 leading-relaxed px-2">{{ $profile->bio }}</p>@endif
            @elseif ($layout === 'shape')
                <div class="mx-auto mt-2 w-24 h-24 rounded-[36%] p-1.5 {{ $isLight ? 'bg-white shadow-xl' : 'bg-white/25' }}">
                    <img src="{{ $profile->avatar_url }}" alt="{{ $profileUser->name }}" class="w-full h-full rounded-[34%] object-cover">
                </div>
                <h1 class="mt-5 text-xl font-bold">{{ $profile->display_name ?: $profileUser->name }}</h1>
                <p class="text-sm opacity-70">{{ $profileUser->username }}</p>
                @if ($profile->bio)<p class="mt-2 text-sm opacity-90 leading-relaxed px-2">{{ $profile->bio }}</p>@endif
            @elseif ($layout === 'hero')
                <div class="mx-auto mt-2 w-28 h-28 rounded-[1.75rem] p-1 {{ $isLight ? 'bg-white shadow-xl' : 'bg-white/90' }}">
                    <img src="{{ $profile->avatar_url }}" alt="{{ $profileUser->name }}" class="w-full h-full rounded-[1.5rem] object-cover">
                </div>
                <h1 class="mt-5 text-2xl font-extrabold">{{ $profile->display_name ?: $profileUser->name }}</h1>
                <p class="text-sm opacity-70 mt-0.5">{{ $profileUser->username }}</p>
                @if ($profile->bio)<p class="mt-2 text-sm opacity-90 leading-relaxed px-2">{{ $profile->bio }}</p>@endif
            @else
                {{-- classic --}}
                <img src="{{ $profile->avatar_url }}" alt="{{ $profileUser->name }}"
                     class="w-24 h-24 rounded-full object-cover mx-auto border-4 {{ $isLight ? 'border-black/10' : 'border-white/40' }} shadow-lg">
                <h1 class="mt-4 text-xl font-bold">{{ $profile->display_name ?: $profileUser->name }}</h1>
                <p class="text-sm opacity-70">{{ $profileUser->username }}</p>
                @if ($profile->bio)<p class="mt-3 text-sm opacity-90 leading-relaxed px-2">{{ $profile->bio }}</p>@endif
            @endif

            {{-- Social icons --}}
            @if (count($socials))
                <div class="flex justify-center flex-wrap gap-3 mt-5">
                    @foreach ($socials as $platform => $url)
                        <a href="{{ str_starts_with($platform, 'email') ? 'mailto:'.$url : $url }}" target="_blank" rel="noopener"
                           class="w-10 h-10 rounded-full {{ $hoverClass }} flex items-center justify-center text-sm font-bold {{ $hoverClass }}"
                           style="{{ $isLight ? 'background:rgba(0,0,0,.12);color:#111827' : 'background:rgba(255,255,255,.22);color:#fff' }}"
                           title="{{ \App\Models\Profile::SOCIAL_PLATFORMS[$platform]['label'] ?? ucfirst($platform) }}">
                            {{ strtoupper(substr($platform, 0, 1)) }}
                        </a>
                    @endforeach
                </div>
            @endif

            {{-- Links --}}
            <div class="mt-8 space-y-3.5">
                @forelse ($links as $link)
                    <a href="{{ route('public.link.redirect', ['username' => $profileUser->username, 'link' => $link->id]) }}"
                       target="_blank" rel="noopener"
                       class="group flex items-center justify-center gap-2 w-full py-3.5 px-5 font-semibold text-sm {{ $hoverClass }}{{ $shadowClass }}"
                       style="{{ $btnStyle }}">
                        @if ($link->is_featured)<span>⭐</span>@endif
                        {{ $link->title }}
                        <span class="opacity-0 group-hover:opacity-60 transition-opacity">&rarr;</span>
                    </a>
                @empty
                    <p class="opacity-60 text-sm py-8">Belum ada link yang tersedia.</p>
                @endforelse
            </div>

            {{-- Shop / Products --}}
            @if ($products->count())
                <div class="mt-10">
                    <p class="text-[11px] font-bold uppercase tracking-[0.25em] opacity-70 mb-4">Shop</p>
                    <div class="space-y-3.5">
                        @foreach ($products as $product)
                            <a href="{{ $product->url }}" target="_blank" rel="noopener"
                               class="flex items-center gap-4 w-full p-3 font-semibold {{ $hoverClass }}{{ $shadowClass }}"
                               style="{{ $btnStyle }}">
                                @if ($product->image_url)
                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                                         class="w-12 h-12 rounded-xl object-cover flex-shrink-0">
                                @else
                                    <span class="w-12 h-12 rounded-xl flex items-center justify-center text-xl flex-shrink-0"
                                          style="{{ $isLight ? 'background:rgba(0,0,0,.10)' : 'background:rgba(0,0,0,.22)' }}">🛍️</span>
                                @endif
                                <span class="flex-1 min-w-0 text-left">
                                    <span class="block text-sm truncate">{{ $product->name }}</span>
                                    @if ($product->price_label)
                                        <span class="block text-xs opacity-75">{{ $product->price_label }}</span>
                                    @endif
                                </span>
                                <span class="opacity-70">&rarr;</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Share button --}}
            <div class="mt-10 flex items-center justify-center gap-3">
                <button type="button" onclick="shareProfile()"
                        class="flex items-center gap-2 px-5 py-2.5 rounded-full {{ $hoverClass }}"
                        style="{{ $btnStyle }}">
                    <span>🔗</span> Bagikan Halaman Ini
                </button>
            </div>

            <p class="mt-10 text-xs opacity-50">
                Dibuat dengan <a href="{{ route('home') }}" class="underline hover:opacity-80">TreeLink</a>
            </p>
        </div>
    </div>

    <script>
        function shareProfile() {
            const url = window.location.href;
            const title = document.title;
            if (navigator.share) {
                navigator.share({ title, url }).catch(() => {});
            } else {
                navigator.clipboard.writeText(url).then(() => {
                    alert('URL profil disalin ke clipboard!');
                });
            }
        }
    </script>
</body>
</html>