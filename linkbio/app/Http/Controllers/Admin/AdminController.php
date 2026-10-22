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

    public function approveWithdrawal(Request $request, Withdrawal $withdrawal): RedirectResponse
    {
        $request->validate([
            'proof' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ], [
            'proof.required' => 'Bukti transfer wajib diupload.',
            'proof.mimes'    => 'Format file harus jpg, png, atau pdf.',
            'proof.max'      => 'Ukuran file maksimal 5MB.',
        ]);

        DB::transaction(function () use ($request, $withdrawal): void {
            $withdrawal = Withdrawal::query()->with('user', 'bankAccount')->lockForUpdate()->findOrFail($withdrawal->id);

            abort_if($withdrawal->status !== 'pending', 422, 'Pengajuan ini sudah diproses.');

            $proofPath = $request->file('proof')->store('withdrawal-proofs', 'public');

            $withdrawal->forceFill([
                'status'       => 'paid',
                'note'         => 'Pengajuan diterima oleh super admin.',
                'processed_at' => now(),
                'proof_path'   => $proofPath,
            ])->save();
        });

        return back()->with('status', 'Pengajuan pencairan diterima dan bukti transfer berhasil disimpan.');
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
