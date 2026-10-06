<?php

namespace App\Http\Controllers;

use App\Models\LinkIdRequest;
use App\Models\Conversation;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class LinkIdRequestController extends Controller
{
    public function store(Request $request, string $username): RedirectResponse
    {
        $recipient = User::where('username', strtolower($username))->where('is_active', true)->firstOrFail();
        abort_unless($recipient->getOrCreateProfile()->is_linkid_active, 404);
        abort_if($request->user()?->is($recipient), 422, 'Kamu tidak bisa mengajak kolaborasi dengan akun sendiri.');

        $validated = $request->validate([
            'requester_name' => ['required', 'string', 'max:120'],
            'requester_email' => ['required', 'email', 'max:255'],
            'type' => ['required', 'string', 'max:80'],
            'message' => ['required', 'string', 'max:1000'],
        ]);

        LinkIdRequest::create([
            'recipient_user_id' => $recipient->id,
            'requester_user_id' => $request->user()?->id,
            'requester_name' => $validated['requester_name'],
            'requester_email' => $validated['requester_email'],
            'type' => $validated['type'],
            'message' => $validated['message'],
            'status' => 'pending',
        ]);

        return Redirect::back()->with('status', 'Permintaan kolaborasi berhasil dikirim.');
    }

    public function index(Request $request): View
    {
        $requests = $request->user()->linkIdRequests()->with(['requester', 'conversation'])->get();

        return view('dashboard.linkid.requests', compact('requests'));
    }

    public function accept(Request $request, LinkIdRequest $linkIdRequest): RedirectResponse
    {
        abort_unless($linkIdRequest->recipient_user_id === $request->user()->id, 403);

        if (! $linkIdRequest->requester_user_id) {
            return back()->with('status', 'Pengirim belum masuk ke akun TreeLink, jadi chat belum bisa dimulai. Minta mereka login lalu kirim request baru.');
        }

        $conversation = DB::transaction(function () use ($request, $linkIdRequest) {
            $linkIdRequest = LinkIdRequest::query()->lockForUpdate()->findOrFail($linkIdRequest->id);
            abort_unless($linkIdRequest->recipient_user_id === $request->user()->id, 403);

            if ($linkIdRequest->status !== 'pending') {
                $existingConversation = $linkIdRequest->conversation;
                abort_unless($existingConversation, 409, 'Request ini sudah tidak menunggu persetujuan.');

                return $existingConversation;
            }

            $conversation = Conversation::firstOrCreate([
                'link_id_request_id' => $linkIdRequest->id,
            ]);

            $conversation->participants()->syncWithoutDetaching([
                $linkIdRequest->requester_user_id => ['last_read_at' => now()],
                $linkIdRequest->recipient_user_id => ['last_read_at' => now()],
            ]);

            if ($conversation->messages()->doesntExist() && filled($linkIdRequest->message)) {
                $conversation->messages()->create([
                    'user_id' => $linkIdRequest->requester_user_id,
                    'body' => $linkIdRequest->message,
                ]);
                $conversation->touch();
            }

            $linkIdRequest->update(['status' => 'accepted']);

            return $conversation;
        });

        return redirect()->route('dashboard.linkid.messages.show', $conversation)
            ->with('status', 'Kolaborasi diterima. Kamu sekarang bisa mulai chat.');
    }

    public function decline(Request $request, LinkIdRequest $linkIdRequest): RedirectResponse
    {
        abort_unless($linkIdRequest->recipient_user_id === $request->user()->id, 403);

        if ($linkIdRequest->status === 'pending') {
            $linkIdRequest->update(['status' => 'declined']);
        }

        return back()->with('status', 'Request kolaborasi ditolak.');
    }

    public function myCollaborations(Request $request): View
    {
        $userId = $request->user()->id;
        $collaborations = LinkIdRequest::query()
            ->where('status', 'accepted')
            ->where(function ($query) use ($userId) {
                $query->where('recipient_user_id', $userId)
                    ->orWhere('requester_user_id', $userId);
            })
            ->with(['requester', 'recipient', 'conversation'])
            ->latest()
            ->get();

        return view('dashboard.linkid.my-collaboration', compact('collaborations'));
    }
}
