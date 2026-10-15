<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $profile->seo_title ?: ($profile->display_name ?: $profileUser->name).' (@'.$profileUser->username.')' }}</title>
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

        // Card ----------------------------------------------------------------------
        // The whole profile can live inside one card that floats over the page
        // background. When the card is on, the readable text colour is derived
        // from the card itself so a light card on a dark page still reads well.
        $themeIsLight = $theme['text'] !== 'text-white';
        $cardOn = (bool) $profile->card_enabled;
        $isLight = $cardOn ? $profile->cardTextColor($themeIsLight) === '#111827' : $themeIsLight;

        $cardStyle = 'background:'.$profile->cardBackgroundCss($themeIsLight).';'
            .'color:'.$profile->cardTextColor($themeIsLight).';'
            .'border:'.$profile->cardBorderCss($themeIsLight).';'
            .'box-shadow:'.$profile->cardShadowCss().';'
            .'border-radius:'.$profile->cardRadiusPx().'px;';

        if ($profile->cardStyle() === 'glass') {
            $cardStyle .= 'backdrop-filter:blur(16px);-webkit-backdrop-filter:blur(16px);';
        }
    @endphp
</head>
<body class="min-h-screen {{ $theme['text'] }} {{ $bgClass }}" style="{{ $bgStyle }} font-family:{{ $fontFamily }}">

    @if ($bgOverlay)
        <div class="fixed inset-0 bg-black/35"></div>
    @endif

    <div class="relative min-h-screen flex flex-col items-center px-4 py-8 sm:py-12 {{ $animate ? 'animate-fade-up' : '' }}">
        <div class="w-full max-w-md sm:max-w-lg mx-auto text-center">
            @if ($cardOn)
                <div class="overflow-hidden px-5 sm:px-6 pt-9 pb-8" style="{{ $cardStyle }}">
            @else
                <div class="-mx-4 -my-8 sm:-my-12 px-4 py-8 sm:py-12">
            @endif

            @if(session('status'))
                <p class="mb-5 rounded-xl bg-emerald-50 px-4 py-3 text-left text-sm font-semibold text-emerald-800">{{ session('status') }}</p>
            @endif

            {{-- ============ HEADER LAYOUT ============ --}}
            @if ($layout === 'banner')
                <div class="{{ $cardOn ? '-mx-6 -mt-9' : '-mt-8' }}">
                    <div class="h-28 {{ $cardOn ? '' : 'rounded-b-3xl' }} {{ $isLight ? 'bg-black/10' : 'bg-white/15 backdrop-blur' }}"></div>
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

            {{-- Follower and following counts --}}
            <div class="mt-5 flex flex-wrap items-center justify-center gap-x-5 gap-y-3">
                <a href="{{ route('public.connections', ['username' => $profileUser->username, 'connection' => 'followers']) }}" class="text-sm opacity-80 transition hover:opacity-100">
                    <strong class="font-extrabold">{{ number_format($profileUser->followers_count) }}</strong> Pengikut
                </a>
                <a href="{{ route('public.connections', ['username' => $profileUser->username, 'connection' => 'following']) }}" class="text-sm opacity-80 transition hover:opacity-100">
                    <strong class="font-extrabold">{{ number_format($profileUser->following_count) }}</strong> Mengikuti
                </a>
                @auth
                    @if(auth()->id() !== $profileUser->id)
                        @php
                            $isFollowingProfile = auth()->user()->following()->whereKey($profileUser->id)->exists();
                        @endphp
                        <form method="POST" action="{{ route('dashboard.accounts.follow.toggle', $profileUser) }}">
                            @csrf
                            <button type="submit" class="px-4 py-2 text-xs font-bold transition {{ $hoverClass }}" style="{{ $btnStyle }}">{{ $isFollowingProfile ? 'Mengikuti' : 'Ikuti' }}</button>
                        </form>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 text-xs font-bold transition {{ $hoverClass }}" style="{{ $btnStyle }}">Masuk untuk mengikuti</a>
                @endauth
            </div>

            {{-- Social icons --}}
            @if (count($socials))
                <div class="flex justify-center flex-wrap gap-2.5 mt-5">
                    @foreach ($socials as $platform => $url)
                        <a href="{{ str_starts_with($platform, 'email') ? 'mailto:'.$url : $url }}" target="_blank" rel="noopener"
                           class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold {{ $hoverClass }}"
                           style="{{ $isLight ? 'background:rgba(0,0,0,.12);color:#111827' : 'background:rgba(255,255,255,.22);color:#fff' }}"
                           title="{{ \App\Models\Profile::SOCIAL_PLATFORMS[$platform]['label'] ?? ucfirst($platform) }}">
                            {!! \App\Support\Brands::markup($platform === 'website' ? 'globe' : ($platform === 'email' ? 'mail' : $platform), 'w-4 h-4') !!}
                        </a>
                    @endforeach
                </div>
            @endif

            {{-- Links Section --}}
            <div class="mt-6 space-y-3 text-left">
                @forelse ($links as $link)
                    @php
                        $resolvedBrand = \App\Support\Brands::resolve($link->url, $link->icon);
                        $brandStyle = \App\Support\Brands::brandStyle($resolvedBrand['slug']);
                        $linkRadius = $profile->button_style === 'pill' ? 'border-radius:9999px;' : ($profile->button_style === 'square' ? 'border-radius:0.75rem;' : 'border-radius:1rem;');
                    @endphp
                    <a href="{{ route('public.link.redirect', ['username' => $profileUser->username, 'link' => $link->id]) }}"
                       target="_blank" rel="noopener"
                       class="group flex items-center gap-3.5 w-full py-3.5 px-4 font-bold text-sm bg-white text-[#14213d] shadow-sm {{ $hoverClass }} {{ $link->is_featured ? 'ring-2 ring-blue-500' : '' }}"
                       style="{{ $linkRadius }}">
                        
                        {{-- Brand badge container on left --}}
                        <div class="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0 shadow-xs overflow-hidden"
                             style="{{ $brandStyle }}">
                            {!! \App\Support\Brands::render($link->url, $link->icon, 'w-4 h-4') !!}
                        </div>

                        {{-- Link Title --}}
                        <span class="flex-1 min-w-0 text-left truncate text-slate-800 font-bold text-[14px]">{{ $link->title }}</span>

                        @if ($link->is_featured)
                            <span class="text-xs flex-shrink-0">⭐</span>
                        @endif
                        <span class="text-slate-400 font-bold text-base flex-shrink-0 group-hover:translate-x-0.5 transition-transform">›</span>
                    </a>
                @empty
                    <p class="opacity-60 text-sm py-6 text-center">Belum ada link yang tersedia.</p>
                @endforelse
            </div>

            {{-- Shop / Products Section (White Card Container with 2-Column Grid) --}}
            @if ($products->count())
                <div class="mt-4 rounded-3xl bg-white p-4 text-left shadow-sm">
                    {{-- Shop Card Header --}}
                    <div class="flex items-center justify-between mb-3.5">
                        <div class="flex items-center gap-1.5 font-bold text-xs text-[#14213d]">
                            <svg class="w-4 h-4 text-[#14213d]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                            <span>Shop</span>
                        </div>
                        <span class="text-[11px] font-semibold text-slate-400 flex items-center gap-0.5">
                            {{ $products->count() }} Produk
                        </span>
                    </div>

                    {{-- 2-Column Product Grid --}}
                    <div class="grid grid-cols-2 gap-3">
                        @foreach ($products as $product)
                            <a href="{{ route('public.product.show', ['username' => $profileUser->username, 'product' => $product->id]) }}"
                               class="group flex flex-col cursor-pointer transition-transform duration-150 hover:-translate-y-0.5">
                                <div class="aspect-square w-full rounded-2xl bg-[#f3f4f6] overflow-hidden flex items-center justify-center relative p-2 shadow-2xs">
                                    @if ($product->image_url)
                                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-contain">
                                    @else
                                        <span class="text-2xl">🛍️</span>
                                    @endif
                                    @if ($product->stock === 0)
                                        <span class="absolute left-1.5 top-1.5 rounded-full bg-rose-600 px-2 py-0.5 text-[9px] font-bold text-white">Habis</span>
                                    @endif
                                </div>
                                <p class="font-bold text-xs text-[#14213d] truncate mt-2">{{ $product->name }}</p>
                                @if ($product->price_label)
                                    <p class="text-[11px] text-slate-500 font-semibold">{{ $product->price_label }}</p>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Share button --}}
            <div class="mt-8 flex items-center justify-center gap-3">
                <button type="button" onclick="shareProfile()"
                        class="flex items-center gap-2 px-6 py-2.5 rounded-full bg-white text-[#14213d] shadow-sm font-bold text-xs {{ $hoverClass }} border border-slate-100 hover:shadow-md">
                    <svg class="w-3.5 h-3.5 text-[#14213d]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                    </svg>
                    <span>Bagikan Halaman Ini</span>
                </button>
            </div>

            <p class="mt-8 text-xs opacity-60 flex items-center justify-center gap-1 font-medium">
                Dibuat dengan <a href="{{ route('home') }}" class="font-bold underline hover:opacity-80">TreeLink</a>
            </p>
            </div>
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