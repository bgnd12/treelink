<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Support\UnreadMessages;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LinkIdMessageController extends Controller
{
    public function index(Request $request): View
    {
        return view('dashboard.linkid.messages', [
            'conversations' => $this->conversations($request),
            'selectedConversation' => null,
            'otherParticipant' => null,
        ]);
    }

    public function unread(Request $request, UnreadMessages $unreadMessages): JsonResponse
    {
        return response()->json($unreadMessages->for($request->user()));
    }

    public function show(Request $request, Conversation $conversation): View
    {
        $this->authorizeParticipant($request, $conversation);
        $user = $request->user();
        $conversation->participants()->updateExistingPivot($user->id, ['last_read_at' => now()]);
        $conversation->load(['participants.profile', 'messages.user.profile', 'product']);

        return view('dashboard.linkid.messages', [
            'conversations' => $this->conversations($request),
            'selectedConversation' => $conversation,
            'otherParticipant' => $conversation->participants->firstWhere('id', '!=', $user->id),
        ]);
    }

    public function store(Request $request, Conversation $conversation): RedirectResponse
    {
        $this->authorizeParticipant($request, $conversation);

        $validated = $request->validate([
            'body' => ['required', 'string', 'max:4000'],
        ]);

        $conversation->messages()->create([
            'user_id' => $request->user()->id,
            'body' => trim($validated['body']),
        ]);
        $conversation->touch();
        $conversation->participants()->updateExistingPivot($request->user()->id, ['last_read_at' => now()]);

        return redirect()->route('dashboard.linkid.messages.show', $conversation)
            ->withFragment('latest-message');
    }

    private function conversations(Request $request)
    {
        $conversations = $request->user()->conversations()
            ->with(['participants.profile', 'lastMessage.user', 'product'])
            ->get();

        $unreadCounts = \Illuminate\Support\Facades\DB::table('messages')
            ->join('conversation_user', 'conversation_user.conversation_id', '=', 'messages.conversation_id')
            ->where('conversation_user.user_id', $request->user()->id)
            ->where('messages.user_id', '<>', $request->user()->id)
            ->where(function ($query) {
                $query->whereNull('conversation_user.last_read_at')
                    ->orWhereColumn('messages.created_at', '>', 'conversation_user.last_read_at');
            })
            ->select('messages.conversation_id', \Illuminate\Support\Facades\DB::raw('count(*) as count'))
            ->groupBy('messages.conversation_id')
            ->pluck('count', 'conversation_id');

        $conversations->each(function ($conv) use ($unreadCounts) {
            $conv->unread_count = $unreadCounts[$conv->id] ?? 0;
        });

        return $conversations;
    }

    private function authorizeParticipant(Request $request, Conversation $conversation): void
    {
        abort_unless($conversation->participants()->whereKey($request->user()->id)->exists(), 403);
    }
}