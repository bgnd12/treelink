<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $connection === 'followers' ? 'Pengikut' : 'Mengikuti' }} · {{ $profileUser->name }} — TreeLink</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#f8f9f7] font-sans text-[#14213d]">
    <header class="border-b border-slate-200 bg-white">
        <div class="mx-auto flex h-16 max-w-5xl items-center justify-between px-4 sm:px-6">
            <a href="{{ route('public.profile', $profileUser->username) }}" class="flex items-center gap-2.5 font-extrabold text-slate-900">
                <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-[#c8ff4d] font-black text-[#1e2a5b]">T</span>
                TreeLink
            </a>
            <a href="{{ route('public.profile', $profileUser->username) }}" class="text-sm font-semibold text-slate-500 hover:text-slate-900">Kembali ke profil</a>
        </div>
    </header>

    <main class="mx-auto max-w-5xl px-4 py-8 sm:px-6">
        <div class="flex flex-col gap-5 border-b border-slate-200 pb-6 sm:flex-row sm:items-center">
            @if($profileUser->profile?->avatar_path)
                <img src="{{ $profileUser->profile->avatar_url }}" alt="{{ $profileUser->name }}" class="h-16 w-16 rounded-full object-cover">
            @else
                <span class="flex h-16 w-16 items-center justify-center rounded-full bg-[#f1f3e9] text-xl font-bold text-[#1e2a5b]">{{ strtoupper(substr($profileUser->name, 0, 1)) }}</span>
            @endif
            <div class="min-w-0 flex-1">
                <p class="truncate text-lg font-extrabold text-slate-900">{{ $profileUser->profile?->display_name ?: $profileUser->name }}</p>
                <p class="text-sm text-slate-500">{{ '@'.$profileUser->username }}</p>
            </div>
            <nav class="flex gap-2" aria-label="Daftar koneksi">
                <a href="{{ route('public.connections', ['username' => $profileUser->username, 'connection' => 'followers']) }}" class="rounded-full px-4 py-2 text-sm font-bold {{ $connection === 'followers' ? 'bg-[#1e2a5b] text-white' : 'border border-slate-200 bg-white text-slate-600' }}">Pengikut {{ number_format($profileUser->followers()->count()) }}</a>
                <a href="{{ route('public.connections', ['username' => $profileUser->username, 'connection' => 'following']) }}" class="rounded-full px-4 py-2 text-sm font-bold {{ $connection === 'following' ? 'bg-[#1e2a5b] text-white' : 'border border-slate-200 bg-white text-slate-600' }}">Mengikuti {{ number_format($profileUser->following()->count()) }}</a>
            </nav>
        </div>

        @if($accounts->isEmpty())
            <div class="py-20 text-center">
                <h1 class="text-lg font-bold text-slate-900">{{ $connection === 'followers' ? 'Belum ada pengikut' : 'Belum mengikuti akun lain' }}</h1>
                <p class="mt-1 text-sm text-slate-500">Daftar koneksi akan tampil di sini.</p>
            </div>
        @else
            <div class="divide-y divide-slate-200">
                @foreach($accounts as $account)
                    <div class="flex items-center gap-3 py-4">
                        @if($account->profile?->avatar_path)
                            <img src="{{ $account->profile->avatar_url }}" alt="" class="h-11 w-11 rounded-full object-cover">
                        @else
                            <span class="flex h-11 w-11 items-center justify-center rounded-full bg-[#f1f3e9] font-bold text-[#1e2a5b]">{{ strtoupper(substr($account->name, 0, 1)) }}</span>
                        @endif
                        <a href="{{ $account->publicUrl() }}" class="min-w-0 flex-1">
                            <span class="block truncate text-sm font-bold text-slate-900">{{ $account->profile?->display_name ?: $account->name }}</span>
                            <span class="block text-xs text-slate-500">{{ '@'.$account->username }} · {{ number_format($account->followers_count) }} pengikut</span>
                        </a>
                        @auth
                            @if(auth()->id() !== $account->id)
                                @php($isFollowingAccount = auth()->user()->following()->whereKey($account->id)->exists())
                                <form method="POST" action="{{ route('dashboard.accounts.follow.toggle', $account) }}">
                                    @csrf
                                    <button type="submit" class="rounded-xl border border-slate-200 px-3 py-2 text-xs font-bold text-slate-700 hover:bg-slate-50">{{ $isFollowingAccount ? 'Mengikuti' : 'Ikuti' }}</button>
                                </form>
                            @endif
                        @endauth
                    </div>
                @endforeach
            </div>
            <div class="mt-5">{{ $accounts->links() }}</div>
        @endif
    </main>
</body>
</html>
