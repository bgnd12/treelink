@extends('layouts.dashboard')
@section('title', 'Messages')
@section('page-title', 'Messages')

@section('content')
<div class="overflow-hidden rounded-2xl border border-ink-100 bg-white shadow-card">
    <div class="grid min-h-[620px] lg:grid-cols-[310px_minmax(0,1fr)]">
        <aside class="border-b border-ink-100 bg-ink-50/40 lg:border-b-0 lg:border-r">
            <div class="border-b border-ink-100 p-5">
                <h2 class="text-lg font-extrabold text-ink-900">Messages</h2>
                <p class="mt-1 text-xs text-ink-500">{{ $conversations->count() }} percakapan</p>
            </div>

            <div class="max-h-[260px] space-y-1 overflow-y-auto p-2 lg:max-h-[620px]">
                @forelse($conversations as $conversation)
                    @php
                        $contact = $conversation->participants->firstWhere('id', '!=', auth()->id());
                        $lastMessage = $conversation->lastMessage;
                    @endphp
                    @if($contact)
                        <a href="{{ route('dashboard.linkid.messages.show', $conversation) }}" class="flex items-center gap-3 rounded-xl p-3 transition {{ $selectedConversation?->id === $conversation->id ? 'bg-[#f1f3e9]' : 'hover:bg-white' }}">
                            @if($contact->profile?->avatar_path)
                                <img src="{{ $contact->profile->avatar_url }}" alt="" class="h-10 w-10 shrink-0 rounded-full object-cover">
                            @else
                                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-[#f1f3e9] text-sm font-bold text-[#1e2a5b]">{{ strtoupper(substr($contact->name, 0, 1)) }}</span>
                            @endif
                            <span class="min-w-0 flex-1">
                                <span class="block truncate text-sm font-bold text-ink-900">{{ $contact->name }}</span>
                                <span class="mt-0.5 block truncate text-xs text-ink-500">{{ $conversation->product ? 'Produk: '.$conversation->product->name : ($lastMessage?->body ?? 'Percakapan dimulai') }}</span>
                            </span>
                            @if($lastMessage)<span class="shrink-0 text-[10px] text-ink-400">{{ $lastMessage->created_at->format('H:i') }}</span>@endif
                        </a>
                    @endif
                @empty
                    <div class="px-4 py-10 text-center">
                        <p class="text-sm font-semibold text-ink-700">Belum ada percakapan</p>
                        <p class="mt-1 text-xs leading-5 text-ink-500">Terima request kolaborasi untuk memulai chat dengan akun TreeLink lain.</p>
                    </div>
                @endforelse
            </div>
        </aside>

        <section class="flex min-h-[500px] flex-col bg-white">
            @if($selectedConversation && $otherParticipant)
                <header class="flex items-center gap-3 border-b border-ink-100 px-5 py-4">
                    @if($otherParticipant->profile?->avatar_path)
                        <img src="{{ $otherParticipant->profile->avatar_url }}" alt="" class="h-10 w-10 rounded-full object-cover">
                    @else
                        <span class="flex h-10 w-10 items-center justify-center rounded-full bg-[#f1f3e9] font-bold text-[#1e2a5b]">{{ strtoupper(substr($otherParticipant->name, 0, 1)) }}</span>
                    @endif
                    <div class="min-w-0">
                        <h3 class="truncate text-sm font-bold text-ink-900">{{ $otherParticipant->name }}</h3>
                        <p class="text-xs text-ink-500">{{ '@'.$otherParticipant->username }}</p>
                        @if($selectedConversation->product)
                            <p class="mt-1 truncate text-xs font-semibold text-[#536239]">Tentang: {{ $selectedConversation->product->name }}</p>
                        @endif
                    </div>
                    <a href="{{ $otherParticipant->publicUrl() }}" target="_blank" rel="noopener" class="ml-auto text-xs font-semibold text-brand-700 hover:underline">Lihat profil</a>
                </header>

                <div class="flex-1 space-y-4 overflow-y-auto bg-[#fbfcfa] p-4 sm:p-6">
                    @forelse($selectedConversation->messages as $message)
                        <div class="flex {{ $message->user_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                            <div class="max-w-[85%] rounded-2xl px-4 py-3 sm:max-w-[72%] {{ $message->user_id === auth()->id() ? 'rounded-br-md bg-[#1e2a5b] text-white' : 'rounded-bl-md border border-ink-100 bg-white text-ink-800' }}">
                                <p class="whitespace-pre-wrap break-words text-sm leading-6">{{ $message->body }}</p>
                                <time class="mt-1 block text-right text-[10px] {{ $message->user_id === auth()->id() ? 'text-white/60' : 'text-ink-400' }}">{{ $message->created_at->format('d M, H:i') }}</time>
                            </div>
                        </div>
                    @empty
                        <div class="flex h-full min-h-48 items-center justify-center text-center text-sm text-ink-500">Kirim pesan pertama untuk memulai percakapan.</div>
                    @endforelse
                    <span id="latest-message"></span>
                </div>

                <form method="POST" action="{{ route('dashboard.linkid.messages.store', $selectedConversation) }}" class="border-t border-ink-100 p-4 sm:p-5">
                    @csrf
                    <label for="message-body" class="sr-only">Tulis pesan</label>
                    <div class="flex items-end gap-2">
                        <textarea id="message-body" name="body" rows="2" maxlength="4000" required placeholder="Tulis pesan..." class="max-h-36 min-h-12 flex-1 resize-y rounded-xl border border-ink-200 px-4 py-3 text-sm outline-none transition focus:border-[#879366] focus:ring-4 focus:ring-[#edf0e7]">{{ old('body') }}</textarea>
                        <button type="submit" aria-label="Kirim pesan" title="Kirim pesan" class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-[#c8ff4d] text-lg font-bold text-[#1e2a5b] transition hover:bg-[#ddff91]">➤</button>
                    </div>
                    @error('body')<p class="mt-2 text-xs text-rose-600">{{ $message }}</p>@enderror
                </form>
            @else
                <div class="flex flex-1 items-center justify-center p-8">
                    <div class="max-w-sm text-center">
                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-[#f1f3e9] text-2xl text-[#1e2a5b]">✉</div>
                        <h3 class="mt-4 text-lg font-extrabold text-ink-900">Pilih percakapan</h3>
                        <p class="mt-1 text-sm leading-6 text-ink-500">Terima request kolaborasi atau chat penjual dari Jelajahi Shop. Percakapanmu akan tampil di sini.</p>
                        <a href="{{ route('dashboard.marketplace') }}" class="mt-4 inline-flex rounded-xl bg-[#c8ff4d] px-4 py-2.5 text-sm font-bold text-[#1e2a5b] hover:bg-[#ddff91]">Jelajahi Shop</a>
                    </div>
                </div>
            @endif
        </section>
    </div>
</div>
@endsection
