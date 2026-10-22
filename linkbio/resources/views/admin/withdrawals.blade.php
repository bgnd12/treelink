@extends('layouts.admin')

@section('title', 'Pengajuan Dana')

@section('content')
<div class="space-y-8">
    @if(session('status'))
        <div class="rounded-2xl border border-emerald-100 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-700">{{ session('status') }}</div>
    @endif

    @if($errors->any())
        <div class="rounded-2xl border border-rose-100 bg-rose-50 px-5 py-4 text-sm font-semibold text-rose-700">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-extrabold tracking-tight text-ink-900 sm:text-3xl">Pengajuan Dana</h1>
            <p class="mt-1 font-medium text-ink-500">Periksa pengajuan pencairan user, lalu terima atau tolak secara manual.</p>
        </div>
        <div class="flex flex-wrap gap-2">
            @foreach(['pending' => 'Menunggu', 'paid' => 'Diterima', 'rejected' => 'Ditolak', 'all' => 'Semua'] as $value => $label)
                <a href="{{ route('admin.withdrawals', ['status' => $value]) }}" class="rounded-full px-3.5 py-2 text-xs font-bold {{ $status === $value ? 'bg-brand-600 text-white' : 'border border-ink-200 bg-white text-ink-600 hover:bg-ink-50' }}">{{ $label }}</a>
            @endforeach
        </div>
    </div>

    <div class="overflow-hidden rounded-[20px] border border-ink-100 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[1000px] text-left text-sm">
                <thead class="bg-ink-50/60 text-[11px] font-bold uppercase tracking-wider text-ink-500">
                    <tr>
                        <th class="px-6 py-4">Pengaju</th>
                        <th class="px-6 py-4">Rekening tujuan</th>
                        <th class="px-6 py-4">Nominal</th>
                        <th class="px-6 py-4">Diajukan</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-ink-50">
                    @forelse($withdrawals as $withdrawal)
                        <tr class="align-top transition-colors hover:bg-ink-50/30">
                            <td class="px-6 py-4">
                                <p class="font-extrabold text-ink-900">{{ $withdrawal->user?->name ?: '-' }}</p>
                                <p class="mt-1 text-xs text-ink-400">{{ $withdrawal->user?->email }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-semibold text-ink-700">{{ $withdrawal->bankAccount?->bank_name ?: '-' }}</p>
                                {{-- Nomor rekening PENUH (tidak disensor) --}}
                                <p class="mt-1 text-xs text-ink-400">
                                    {{ $withdrawal->bankAccount?->account_name }}
                                    &middot;
                                    <span class="font-mono font-semibold text-ink-700 select-all">{{ $withdrawal->bankAccount?->account_number }}</span>
                                </p>
                            </td>
                            <td class="px-6 py-4 font-extrabold text-ink-900">Rp {{ number_format((float) $withdrawal->amount, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-xs text-ink-500">{{ $withdrawal->created_at->translatedFormat('d M Y, H:i') }}</td>
                            <td class="px-6 py-4">
                                <span class="rounded-full px-2.5 py-1 text-[10px] font-bold {{ $withdrawal->status === 'paid' ? 'bg-emerald-50 text-emerald-700' : ($withdrawal->status === 'rejected' ? 'bg-rose-50 text-rose-700' : 'bg-amber-50 text-amber-700') }}">{{ $withdrawal->status_label }}</span>
                                @if($withdrawal->note)
                                    <p class="mt-2 max-w-[190px] text-[11px] leading-4 text-ink-400">{{ $withdrawal->note }}</p>
                                @endif
                                @if($withdrawal->proof_url)
                                    <a href="{{ $withdrawal->proof_url }}" target="_blank" rel="noopener" class="mt-2 block text-[11px] font-bold text-indigo-600 hover:underline">Lihat bukti transfer</a>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                @if($withdrawal->status === 'pending')
                                    <div class="flex justify-end gap-2">
                                        <button
                                            type="button"
                                            onclick="openApproveModal('{{ route('admin.withdrawals.approve', $withdrawal) }}')"
                                            class="rounded-xl bg-emerald-500 px-3 py-2 text-xs font-bold text-white transition hover:bg-emerald-600">
                                            Terima
                                        </button>
                                        <form method="POST" action="{{ route('admin.withdrawals.reject', $withdrawal) }}" onsubmit="return confirm('Tolak pengajuan ini?')">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="note" value="Pengajuan ditolak oleh super admin.">
                                            <button class="rounded-xl border border-rose-200 px-3 py-2 text-xs font-bold text-rose-600 transition hover:bg-rose-50">Tolak</button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-xs text-ink-400">Sudah diproses</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-6 py-14 text-center text-sm text-ink-500">Belum ada pengajuan dana.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($withdrawals->hasPages())
            <div class="border-t border-ink-100 px-6 py-4">{{ $withdrawals->links() }}</div>
        @endif
    </div>
</div>

{{-- MODAL UPLOAD BUKTI TRANSFER --}}
<div id="approve-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm">
    <div class="relative w-full max-w-md rounded-2xl bg-white p-8 shadow-2xl mx-4">
        <button type="button" onclick="closeApproveModal()" class="absolute right-4 top-4 text-ink-400 hover:text-ink-700 text-xl font-bold">&times;</button>

        <div class="mb-6">
            <div class="mb-3 flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-100">
                    <svg class="h-5 w-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h2 class="text-lg font-extrabold text-ink-900">Upload Bukti Transfer</h2>
            </div>
            <p class="text-sm text-ink-500">Upload foto/file bukti transfer yang sudah dikirim ke rekening user. Format: JPG, PNG, atau PDF (maks. 5MB).</p>
        </div>

        <form id="approve-form" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-5">
                {{-- Drop zone: tampil saat belum ada file --}}
                <div id="upload-zone">
                    <label class="mb-2 block text-sm font-bold text-ink-700">Bukti Transfer <span class="text-rose-500">*</span></label>
                    <label for="proof-input"
                        class="flex flex-col items-center justify-center gap-2 rounded-xl border-2 border-dashed border-ink-200 bg-ink-50 px-4 py-8 cursor-pointer transition hover:border-emerald-400 hover:bg-emerald-50">
                        <svg class="h-9 w-9 text-ink-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/>
                        </svg>
                        <p class="text-center text-sm text-ink-500">Klik atau seret file ke sini</p>
                        <p class="text-[11px] text-ink-400">JPG, PNG, PDF &middot; Maks 5MB</p>
                    </label>
                </div>
                <input type="file" id="proof-input" name="proof" accept=".jpg,.jpeg,.png,.pdf" class="hidden" onchange="handleFileChange(this)">

                {{-- Info file + preview: tampil setelah file dipilih --}}
                <div id="file-selected" class="hidden">
                    <div id="preview-container" class="mb-3">
                        <img id="preview-img" src="" alt="Preview" class="w-full rounded-xl border border-ink-100 object-contain max-h-36">
                    </div>
                    {{-- Info nama file (PDF) --}}
                    <div id="pdf-info" class="hidden mb-3 flex items-center gap-3 rounded-xl border border-ink-200 bg-ink-50 px-4 py-3">
                        <svg class="h-7 w-7 shrink-0 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                        </svg>
                        <span id="pdf-name" class="text-sm font-semibold text-ink-700 truncate"></span>
                    </div>
                    <button type="button" onclick="changeFile()" class="text-xs font-semibold text-ink-400 hover:text-ink-700 underline underline-offset-2 transition">
                        Ganti file
                    </button>
                </div>
            </div>

            <div class="flex gap-3">
                <button type="button" onclick="closeApproveModal()" class="flex-1 rounded-xl border border-ink-200 px-4 py-2.5 text-sm font-bold text-ink-600 hover:bg-ink-50 transition">Batal</button>
                <button type="submit" id="submit-btn" class="flex-1 rounded-xl bg-emerald-500 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-emerald-600 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                    Konfirmasi &amp; Terima
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openApproveModal(actionUrl) {
        var modal = document.getElementById('approve-modal');
        var form  = document.getElementById('approve-form');
        form.action = actionUrl;
        form.reset();

        // Reset ke kondisi awal: tampilkan upload zone, sembunyikan file-selected
        document.getElementById('upload-zone').classList.remove('hidden');
        document.getElementById('file-selected').classList.add('hidden');
        document.getElementById('preview-container').classList.add('hidden');
        document.getElementById('pdf-info').classList.add('hidden');
        document.getElementById('preview-img').src = '';
        document.getElementById('pdf-name').textContent = '';
        document.getElementById('submit-btn').disabled = true;

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeApproveModal() {
        var modal = document.getElementById('approve-modal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function changeFile() {
        // Kembali tampilkan drop zone, sembunyikan file-selected
        document.getElementById('upload-zone').classList.remove('hidden');
        document.getElementById('file-selected').classList.add('hidden');
        document.getElementById('preview-img').src = '';
        document.getElementById('pdf-name').textContent = '';
        document.getElementById('submit-btn').disabled = true;
        // Trigger ulang file picker
        document.getElementById('proof-input').click();
    }

    function handleFileChange(input) {
        var file = input.files[0];
        if (!file) return;

        var uploadZone   = document.getElementById('upload-zone');
        var fileSelected = document.getElementById('file-selected');
        var preview      = document.getElementById('preview-container');
        var previewImg   = document.getElementById('preview-img');
        var pdfInfo      = document.getElementById('pdf-info');
        var pdfName      = document.getElementById('pdf-name');
        var btn          = document.getElementById('submit-btn');

        // Sembunyikan drop zone, tampilkan area file-selected
        uploadZone.classList.add('hidden');
        fileSelected.classList.remove('hidden');
        btn.disabled = false;

        if (file.type.startsWith('image/')) {
            // Gambar: tampilkan preview, sembunyikan info PDF
            pdfInfo.classList.add('hidden');
            preview.classList.remove('hidden');
            var reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
            };
            reader.readAsDataURL(file);
        } else {
            // PDF: tampilkan nama file, sembunyikan preview gambar
            preview.classList.add('hidden');
            previewImg.src = '';
            pdfName.textContent = file.name;
            pdfInfo.classList.remove('hidden');
        }
    }

    document.getElementById('approve-modal').addEventListener('click', function(e) {
        if (e.target === this) closeApproveModal();
    });
</script>
@endsection
