@extends('layouts.dashboard')

@section('title', __('Buat TreeLink baru'))
@section('page-title', __('Buat Treelink baru'))

@section('content')
<div class="mx-auto max-w-2xl">
    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
        <p class="text-xs font-extrabold uppercase tracking-[0.2em] text-[#879366]">TreeLink</p>
        <h2 class="mt-2 text-2xl font-extrabold text-[#14213d]">{{ __('Buat TreeLink baru') }}</h2>
        <p class="mt-2 text-sm leading-6 text-slate-500">{{ __('Buat profil publik lain dalam satu akun. Setiap Treelink memiliki username, link, dan desain sendiri.') }}</p>

        <form method="POST" action="{{ route('dashboard.profile-pages.store') }}" class="mt-7 space-y-5">
            @csrf
            <div>
                <label for="name" class="mb-1.5 block text-sm font-bold text-slate-700">{{ __('Nama treelink') }}</label>
                <input id="name" name="name" value="{{ old('name') }}" required maxlength="80" placeholder="Contoh: Portofolio, Bisnis, Komunitas" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-[#879366] focus:ring-4 focus:ring-[#edf0e7]">
                @error('name')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="username" class="mb-1.5 block text-sm font-bold text-slate-700">{{ __('Username publik') }}</label>
                <div class="flex items-center rounded-xl border border-slate-200 px-4 focus-within:border-[#879366] focus-within:ring-4 focus-within:ring-[#edf0e7]">
                    <span class="text-sm text-slate-400">treelink/</span>
                    <input id="username" name="username" value="{{ old('username') }}" required maxlength="60" placeholder="username-baru" class="min-w-0 flex-1 border-0 px-1 py-3 text-sm outline-none focus:ring-0">
                </div>
            </div>
            <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                <a href="{{ route('dashboard.index') }}" class="rounded-xl border border-slate-200 px-5 py-3 text-center text-sm font-bold text-slate-600 hover:bg-slate-50">{{ __('Batal') }}</a>
                <button type="submit" class="rounded-xl bg-[#c8ff4d] px-5 py-3 text-sm font-extrabold text-[#1e2a5b] hover:bg-[#ddff91]">{{ __('Buat treelink') }}</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if ($errors->has('username'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                Swal.fire({
                    icon: 'warning',
                    title: 'URL sudah digunakan',
                    text: @json($errors->first('username')),
                    confirmButtonText: 'Pilih URL lain',
                    confirmButtonColor: '#1e2a5b',
                    customClass: {
                        popup: 'rounded-3xl',
                        confirmButton: 'rounded-xl px-5 py-3 font-bold',
                    },
                }).then(() => {
                    document.getElementById('username')?.focus();
                });
            });
        </script>
    @endif
@endsection
