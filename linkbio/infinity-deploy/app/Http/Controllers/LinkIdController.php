<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LinkIdController extends Controller
{
    public function discover(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));
        $type = trim((string) $request->query('type', ''));

        $accounts = User::query()
            ->with('profile')
            ->withCount('followers')
            ->withExists([
                'followers as is_followed_by_me' => fn ($query) => $query->where('users.id', $request->user()->id),
            ])
            ->where('is_active', true)
            ->where('id', '<>', $request->user()->id)
            ->when($type !== '', fn ($query) => $query->whereHas('profile', fn ($profileQuery) => $profileQuery->where('is_linkid_active', true)->whereJsonContains('linkid_types', $type)))
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($userQuery) use ($search) {
                    $userQuery->where('name', 'like', '%'.$search.'%')
                        ->orWhere('username', 'like', '%'.$search.'%')
                        ->orWhereHas('profile', function ($profileQuery) use ($search) {
                            $profileQuery->where('display_name', 'like', '%'.$search.'%')
                                ->orWhere('linkid_description', 'like', '%'.$search.'%')
                                ->orWhere('bio', 'like', '%'.$search.'%');
                        });
                });
            })
            ->latest('users.created_at')
            ->paginate(12)
            ->withQueryString();

        $types = Profile::query()
            ->where('is_linkid_active', true)
            ->whereNotNull('linkid_types')
            ->pluck('linkid_types')
            ->flatten()
            ->filter(fn ($value) => is_string($value) && trim($value) !== '')
            ->unique()
            ->sort()
            ->values();

        return view('dashboard.linkid.discover', compact('accounts', 'types', 'search', 'type'));
    }
}
