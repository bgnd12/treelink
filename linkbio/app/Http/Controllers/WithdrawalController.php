<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Withdrawal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class WithdrawalController extends Controller
{
    public function create(Request $request): View
    {
        $user = $request->user();
        $paidRevenue = (float) $user->orders()
            ->where('payment_status', 'paid')
            ->whereNotIn('status', ['cancelled'])
            ->sum('total');
        $reserved = (float) $user->withdrawals()->whereIn('status', ['pending', 'processing', 'paid'])->sum('amount');

        return view('dashboard.withdrawals.create', [
            'accounts' => $user->bankAccounts()->latest()->get(),
            'availableBalance' => max(0, $paidRevenue - $reserved),
            'withdrawals' => $user->withdrawals()->with('bankAccount')->latest()->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();
        $paidRevenue = (float) $user->orders()
            ->where('payment_status', 'paid')
            ->whereNotIn('status', ['cancelled'])
            ->sum('total');
        $reserved = (float) $user->withdrawals()->whereIn('status', ['pending', 'processing', 'paid'])->sum('amount');
        $availableBalance = max(0, $paidRevenue - $reserved);

        if ($availableBalance <= 0) {
            return back()->withErrors(['amount' => 'Saldo Anda masih nol, belum ada dana yang bisa dicairkan.'])->withInput();
        }

        $validated = $request->validate([
            'bank_account_id' => ['required', 'integer', 'exists:bank_accounts,id'],
            'amount' => ['required', 'numeric', 'min:1', 'max:'.$availableBalance],
        ], [
            'amount.min' => 'Nominal pencairan harus lebih dari Rp 0.',
            'amount.max' => 'Nominal melebihi saldo yang bisa dicairkan.',
        ]);

        $accountBelongsToUser = $user->bankAccounts()->whereKey($validated['bank_account_id'])->exists();
        abort_unless($accountBelongsToUser, 403);

        $withdrawal = DB::transaction(function () use ($user, $validated) {
            return $user->withdrawals()->create([
                'bank_account_id' => $validated['bank_account_id'],
                'amount' => $validated['amount'],
                'status' => 'pending',
                'note' => 'Menunggu persetujuan super admin.',
            ]);
        });

        return redirect()->route('dashboard.withdrawals.create')->with('status', 'Pengajuan pencairan berhasil dikirim dan sedang menunggu persetujuan super admin.');
    }
}
