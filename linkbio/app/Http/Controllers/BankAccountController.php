<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BankAccountController extends Controller
{
    public function index(Request $request): View
    {
        return view('dashboard.bank-accounts', [
            'accounts' => $request->user()->bankAccounts()->latest()->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'bank_name' => ['required', 'string', 'max:100'],
            'account_name' => ['required', 'string', 'max:150'],
            'account_number' => ['required', 'string', 'max:50', 'regex:/^[0-9\s-]+$/'],
        ]);

        $request->user()->bankAccounts()->update(['is_primary' => false]);
        $request->user()->bankAccounts()->create($validated + ['is_primary' => true]);

        return redirect()->route('dashboard.accounts.index')->with('status', 'Rekening berhasil disambungkan.');
    }

    public function destroy(Request $request, int $bankAccount): RedirectResponse
    {
        $request->user()->bankAccounts()->whereKey($bankAccount)->firstOrFail()->delete();

        return redirect()->route('dashboard.accounts.index')->with('status', 'Rekening berhasil dihapus.');
    }
}
