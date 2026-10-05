<?php

namespace App\Http\Controllers;

use App\Models\LinkIdRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class LinkIdRequestController extends Controller
{
    public function store(Request $request, string $username): RedirectResponse
    {
        $recipient = User::where('username', strtolower($username))->where('is_active', true)->firstOrFail();
        abort_unless($recipient->getOrCreateProfile()->is_linkid_active, 404);

        $validated = $request->validate([
            'requester_name' => ['required', 'string', 'max:120'],
            'requester_email' => ['required', 'email', 'max:255'],
            'type' => ['required', 'string', 'max:80'],
            'message' => ['required', 'string', 'max:1000'],
        ]);

        LinkIdRequest::create([
            'recipient_user_id' => $recipient->id,
            'requester_name' => $validated['requester_name'],
            'requester_email' => $validated['requester_email'],
            'type' => $validated['type'],
            'message' => $validated['message'],
            'status' => 'pending',
        ]);

        return Redirect::back()->with('status', 'Permintaan kolaborasi berhasil dikirim.');
    }
}
