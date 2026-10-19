<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Link;
use App\Models\LinkClick;
use App\Models\Order;
use App\Models\ProfileView;
use App\Models\User;
use App\Models\Withdrawal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_users' => User::count(),
            'active_users' => User::where('is_active', true)->count(),
            'inactive_users' => User::where('is_active', false)->count(),
            'total_links' => Link::count(),
            'total_views' => ProfileView::count(),
            'total_clicks' => LinkClick::count(),
            'new_users_7d' => User::where('created_at', '>=', now()->subDays(7))->count(),
        ];

        $latestUsers = User::withCount('links')
            ->latest()
            ->take(8)
            ->get();

        $mostViewedUsers = User::withCount('profileViews')
            ->orderByDesc('profile_views_count')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'latestUsers', 'mostViewedUsers'));
    }

    public function users(Request $request): View
    {
        $search = $request->string('search')->trim()->toString();

        $users = User::withCount(['links', 'profileViews'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('username', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.users', compact('users', 'search'));
    }

    public function transactions(Request $request): View
    {
        $status = $request->query('status', 'all');
        $status = in_array($status, ['all', 'unpaid', 'paid'], true) ? $status : 'all';

        $transactions = Order::with(['seller', 'buyer', 'items'])
            ->when($status !== 'all', fn ($query) => $query->where('payment_status', $status))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.transactions', compact('transactions', 'status'));
    }

    public function withdrawals(Request $request): View
    {
        $status = $request->query('status', 'pending');
        $allowedStatuses = array_merge(['all'], array_keys(Withdrawal::STATUSES));
        $status = in_array($status, $allowedStatuses, true) ? $status : 'pending';

        $withdrawals = Withdrawal::with(['user', 'bankAccount'])
            ->when($status !== 'all', fn ($query) => $query->where('status', $status))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.withdrawals', compact('withdrawals', 'status'));
    }

    public function approveWithdrawal(Withdrawal $withdrawal): RedirectResponse
    {
        DB::transaction(function () use ($withdrawal): void {
            $withdrawal = Withdrawal::query()->with('user', 'bankAccount')->lockForUpdate()->findOrFail($withdrawal->id);

            abort_if($withdrawal->status !== 'pending', 422, 'Pengajuan ini sudah diproses.');

            $withdrawal->forceFill([
                'status' => 'paid',
                'note' => 'Pengajuan diterima oleh super admin.',
                'processed_at' => now(),
            ])->save();

            $withdrawal->forceFill(['proof_path' => $this->makeWithdrawalProof($withdrawal)])->save();
        });

        return back()->with('status', 'Pengajuan pencairan diterima dan bukti struk berhasil dibuat.');
    }

    public function rejectWithdrawal(Request $request, Withdrawal $withdrawal): RedirectResponse
    {
        $validated = $request->validate([
            'note' => ['nullable', 'string', 'max:500'],
        ]);

        $updated = Withdrawal::query()
            ->whereKey($withdrawal->id)
            ->where('status', 'pending')
            ->update([
                'status' => 'rejected',
                'note' => $validated['note'] ?? 'Pengajuan ditolak oleh super admin.',
                'processed_at' => now(),
            ]);

        abort_if($updated === 0, 422, 'Pengajuan ini sudah diproses.');

        return back()->with('status', 'Pengajuan pencairan ditolak.');
    }

    private function makeWithdrawalProof(Withdrawal $withdrawal): string
    {
        $path = 'withdrawal-proofs/'.$withdrawal->transfer_reference.'.svg';
        $escape = static fn ($value): string => htmlspecialchars((string) $value, ENT_QUOTES | ENT_XML1, 'UTF-8');
        $amount = 'Rp '.number_format((float) $withdrawal->amount, 0, ',', '.');
        $bank = $withdrawal->bankAccount?->bank_name ?: 'Rekening bank';
        $account = $withdrawal->bankAccount?->masked_account_number ?: '-';
        $date = optional($withdrawal->processed_at)->format('d/m/Y H:i') ?: now()->format('d/m/Y H:i');

        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="720" height="920" viewBox="0 0 720 920">'
            .'<rect width="720" height="920" fill="#f4f6f8"/>'
            .'<rect x="72" y="48" width="576" height="824" rx="18" fill="#ffffff" stroke="#d9dee7" stroke-width="2"/>'
            .'<rect x="72" y="48" width="576" height="150" rx="18" fill="#1e2a5b"/>'
            .'<text x="360" y="106" text-anchor="middle" fill="#c8ff4d" font-family="Arial,sans-serif" font-size="34" font-weight="700">TreeLink</text>'
            .'<text x="360" y="150" text-anchor="middle" fill="#ffffff" font-family="Arial,sans-serif" font-size="20">BUKTI PENCAIRAN DANA</text>'
            .'<text x="120" y="250" fill="#718096" font-family="Arial,sans-serif" font-size="18">STATUS</text>'
            .'<text x="600" y="250" text-anchor="end" fill="#16803c" font-family="Arial,sans-serif" font-size="22" font-weight="700">DITERIMA</text>'
            .'<line x1="120" y1="278" x2="600" y2="278" stroke="#e2e8f0"/>'
            .'<text x="120" y="330" fill="#718096" font-family="Arial,sans-serif" font-size="18">NOMOR REFERENSI</text>'
            .'<text x="600" y="330" text-anchor="end" fill="#1a202c" font-family="Arial,sans-serif" font-size="20" font-weight="700">'.$escape($withdrawal->transfer_reference).'</text>'
            .'<text x="120" y="400" fill="#718096" font-family="Arial,sans-serif" font-size="18">PENERIMA</text>'
            .'<text x="600" y="400" text-anchor="end" fill="#1a202c" font-family="Arial,sans-serif" font-size="20" font-weight="700">'.$escape($withdrawal->user?->name ?: '-').'</text>'
            .'<text x="120" y="470" fill="#718096" font-family="Arial,sans-serif" font-size="18">REKENING TUJUAN</text>'
            .'<text x="600" y="470" text-anchor="end" fill="#1a202c" font-family="Arial,sans-serif" font-size="20" font-weight="700">'.$escape($bank).' · '.$escape($account).'</text>'
            .'<line x1="120" y1="520" x2="600" y2="520" stroke="#e2e8f0"/>'
            .'<text x="120" y="580" fill="#718096" font-family="Arial,sans-serif" font-size="18">TOTAL DANA</text>'
            .'<text x="600" y="580" text-anchor="end" fill="#1e2a5b" font-family="Arial,sans-serif" font-size="30" font-weight="700">'.$escape($amount).'</text>'
            .'<text x="120" y="650" fill="#718096" font-family="Arial,sans-serif" font-size="18">WAKTU DIPROSES</text>'
            .'<text x="600" y="650" text-anchor="end" fill="#1a202c" font-family="Arial,sans-serif" font-size="20">'.$escape($date).'</text>'
            .'<text x="360" y="770" text-anchor="middle" fill="#718096" font-family="Arial,sans-serif" font-size="17">Bukti ini dibuat otomatis oleh TreeLink.</text>'
            .'<text x="360" y="805" text-anchor="middle" fill="#a0aec0" font-family="Arial,sans-serif" font-size="15">Dokumen simulasi lokal, bukan konfirmasi bank.</text>'
            .'</svg>';

        Storage::disk('public')->put($path, $svg);

        return $path;
    }

    public function confirmPayment(Order $order): RedirectResponse
    {
        $order->forceFill([
            'payment_status' => 'paid',
            'payment_status_raw' => 'PAID_MANUAL_ADMIN',
            'status' => $order->status === 'awaiting_payment' ? 'processing' : $order->status,
            'paid_at' => $order->paid_at ?: now(),
        ])->save();

        return back()->with('status', 'Pembayaran '.$order->code.' dikonfirmasi oleh admin.');
    }

    public function rejectPayment(Order $order): RedirectResponse
    {
        $order->forceFill([
            'payment_status' => 'unpaid',
            'payment_status_raw' => 'REJECTED_MANUAL_ADMIN',
        ])->save();

        return back()->with('status', 'Pembayaran '.$order->code.' ditandai belum dibayar.');
    }

    public function show(User $user): View
    {
        $user->load('profile', 'links.clicks');

        $totalClicks = $user->links->sum(fn ($link) => $link->clicks->count());

        return view('admin.user-show', [
            'targetUser' => $user,
            'totalClicks' => $totalClicks,
            'totalViews' => $user->profileViews()->count(),
        ]);
    }

    public function toggleActive(User $user): RedirectResponse
    {
        abort_if($user->is_admin, 403, 'Tidak dapat menonaktifkan akun admin.');

        $user->update(['is_active' => ! $user->is_active]);

        return back()->with('status', $user->is_active ? 'Akun diaktifkan kembali.' : 'Akun dinonaktifkan.');
    }

    public function destroy(User $user): RedirectResponse
    {
        abort_if($user->is_admin, 403, 'Tidak dapat menghapus akun admin.');

        $user->delete();

        return redirect()->route('admin.users')->with('status', 'Akun pengguna berhasil dihapus.');
    }
}
