@extends('layouts.admin')

@section('title', 'Semua User')
@section('page-title', 'Semua User')

@section('content')
    <div class="bg-white rounded-2xl border border-ink-100 p-6 shadow-card">
        <form method="GET" action="{{ route('admin.users') }}" class="mb-6">
            <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama, username, atau email..."
                   class="w-full sm:w-96 px-4 py-3 rounded-xl border border-ink-200 focus:border-brand-500 focus:ring-4 focus:ring-brand-100 outline-none transition text-sm">
        </form>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-ink-400 border-b border-ink-100">
                        <th class="pb-3 font-semibold">User</th>
                        <th class="pb-3 font-semibold">Email</th>
                        <th class="pb-3 font-semibold text-center">Link</th>
                        <th class="pb-3 font-semibold text-center">Kunjungan</th>
                        <th class="pb-3 font-semibold text-center">Status</th>
                        <th class="pb-3 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $u)
                        <tr class="border-b border-ink-50 last:border-0">
                            <td class="py-3.5">
                                <p class="font-semibold text-ink-900">{{ $u->name }}</p>
                                <p class="text-xs text-ink-400">/{{ $u->username }}</p>
                            </td>
                            <td class="py-3.5 text-ink-600">{{ $u->email }}</td>
                            <td class="py-3.5 text-center font-semibold text-ink-700">{{ $u->links_count }}</td>
                            <td class="py-3.5 text-center font-semibold text-ink-700">{{ $u->profile_views_count }}</td>
                            <td class="py-3.5 text-center">
                                @if ($u->is_admin)
                                    <span class="px-2.5 py-1 rounded-full bg-indigo-50 text-indigo-600 text-xs font-semibold">Admin</span>
                                @elseif ($u->is_active)
                                    <span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-600 text-xs font-semibold">Aktif</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full bg-rose-50 text-rose-600 text-xs font-semibold">Nonaktif</span>
                                @endif
                            </td>
                            <td class="py-3.5">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.users.show', $u) }}" class="px-3 py-1.5 rounded-lg border border-ink-200 text-xs font-semibold hover:border-ink-400 transition">Detail</a>
                                    @unless ($u->is_admin)
                                        <form method="POST" action="{{ route('admin.users.toggle', $u) }}">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="px-3 py-1.5 rounded-lg border text-xs font-semibold transition {{ $u->is_active ? 'border-rose-200 text-rose-600 hover:bg-rose-50' : 'border-emerald-200 text-emerald-600 hover:bg-emerald-50' }}">
                                                {{ $u->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.users.destroy', $u) }}" onsubmit="return confirm('Hapus user {{ $u->name }} beserta seluruh datanya?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="px-3 py-1.5 rounded-lg border border-ink-200 text-xs font-semibold text-rose-600 hover:bg-rose-50 transition">Hapus</button>
                                        </form>
                                    @endunless
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="py-10 text-center text-ink-400">Tidak ada user ditemukan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $users->links() }}
        </div>
    </div>
@endsection
