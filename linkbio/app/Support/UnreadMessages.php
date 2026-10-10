<?php

namespace App\Support;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UnreadMessages
{
    public function for(User $user): array
    {
        $unread = DB::table('messages')
            ->join('conversation_user', 'conversation_user.conversation_id', '=', 'messages.conversation_id')
            ->where('conversation_user.user_id', $user->id)
            ->where('messages.user_id', '<>', $user->id)
            ->where(function ($query) {
                $query->whereNull('conversation_user.last_read_at')
                    ->orWhereColumn('messages.created_at', '>', 'conversation_user.last_read_at');
            });

        $count = (clone $unread)->count();
        $latest = (clone $unread)
            ->join('users as sender', 'sender.id', '=', 'messages.user_id')
            ->orderByDesc('messages.id')
            ->first([
                'messages.id',
                'messages.conversation_id',
                'messages.body',
                'sender.name as sender_name',
            ]);

        return [
            'count' => $count,
            'latest' => $latest ? [
                'id' => $latest->id,
                'conversation_id' => $latest->conversation_id,
                'sender_name' => $latest->sender_name,
                'preview' => Str::limit(strip_tags($latest->body), 100),
            ] : null,
        ];
    }
}
