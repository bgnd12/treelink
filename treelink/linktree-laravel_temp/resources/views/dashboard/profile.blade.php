@extends('layouts.dashboard')

@section('title', 'Profile')
@section('page-title', 'Profile')

@section('content')
<div class="grid lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2">
        <form method="POST" action="{{ route('dashboard.profile.update') }}" enctype="multipart/form-data" class="space-y-6" x-data="{ avatarPreview: null }">
            @csrf
            @method('PUT')

            {{-- Avatar --}}
            <div class="bg-white rounded-2xl border border-ink-100 p-6 shadow-card">
                <h2 class="font-bold text-ink-900 mb-5">Foto Profil</h2>
                <div class="flex items-center gap-5">
                    <img :src="avatarPreview || '{{ $profile->avatar_url }}'" class="w-20 h-20 rounded-full object-cover border-4 border-ink-100" alt="Avatar">
                    <div>
                        <label class="inline-block px-4 py-2.5 rounded-xl border border-ink-200 text-sm font-semibold cursor-pointer hover:border-ink-400 transition">
                            Ganti Foto
                            <input type="file" name="avatar" accept="image/*" class="hidden"
                                   @change="avatarPreview = URL.createObjectURL($event.target.files[0])">
                        </label>
                        <p class="text-xs text-ink-400 mt-2">JPG, PNG. Maksimal 2MB.</p>
                        @error('avatar')<p class="text-xs text-rose-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            {{-- Basic info --}}
            <div class="bg-white rounded-2xl border border-ink-100 p-6 shadow-card space-y-5">
                <h2 class="font-bold text-ink-900">Informasi Dasar</h2>

                <x-input label="Nama Lengkap" name="name" :value="$user->name" required />

                <div>
                    <label class="block text-sm font-semibold text-ink-800 mb-1.5">Username</label>
                    <div class="flex rounded-xl border border-ink-200 focus-within:border-brand-500 focus-within:ring-4 focus-within:ring-brand-100 overflow-hidden transition">
                        <span class="px-4 flex items-center bg-ink-50 text-ink-400 text-sm border-r border-ink-200">{{ request()->getHost() }}/</span>
                        <input type="text" name="username" value="{{ old('username', $user->username) }}" required class="w-full px-3 py-3 outline-none text-sm">
                    </div>
                    @error('username')<p class="text-xs text-rose-600 mt-1">{{ $message }}</p>@enderror
                </div>

                <x-input label="Nama Tampilan (opsional)" name="display_name" :value="$profile->display_name" placeholder="Ditampilkan di halaman publik" />

                <div>
                    <label class="block text-sm font-semibold text-ink-800 mb-1.5">Bio</label>
                    <textarea name="bio" rows="3" maxlength="280" placeholder="Ceritakan sedikit tentang dirimu..."
                              class="w-full px-4 py-3 rounded-xl border border-ink-200 focus:border-brand-500 focus:ring-4 focus:ring-brand-100 outline-none transition text-sm">{{ old('bio', $profile->bio) }}</textarea>
                    @error('bio')<p class="text-xs text-rose-600 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- Social links --}}
            <div class="bg-white rounded-2xl border border-ink-100 p-6 shadow-card">
                <h2 class="font-bold text-ink-900 mb-1">Social Media</h2>
                <p class="text-sm text-ink-500 mb-5">Tautan ini akan tampil sebagai ikon di halaman publikmu.</p>

                <div class="grid sm:grid-cols-2 gap-4">
                    @foreach ($socialPlatforms as $key => $platform)
                        <div>
                            <label class="block text-sm font-semibold text-ink-800 mb-1.5">{{ $platform['label'] }}</label>
                            <input type="text" name="social_links[{{ $key }}]" value="{{ old('social_links.'.$key, $profile->social_links[$key] ?? '') }}"
                                   placeholder="{{ $platform['placeholder'] }}" class="w-full px-4 py-3 rounded-xl border border-ink-200 focus:border-brand-500 focus:ring-4 focus:ring-brand-100 outline-none transition text-sm">
                        </div>
                    @endforeach
                </div>
            </div>

            <button type="submit" class="px-8 py-3.5 rounded-xl bg-brand-600 text-white font-semibold hover:bg-brand-700 transition shadow-soft">
                Simpan Profil
            </button>
        </form>
    </div>

    <div>
        <div class="sticky top-24">
            <p class="text-sm font-semibold text-ink-500 mb-3 text-center">Live Preview</p>
            @include('components.phone-preview', ['profile' => $profile, 'links' => $user->links, 'user' => $user])
        </div>
    </div>
</div>
@endsection