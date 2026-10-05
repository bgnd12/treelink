<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LinkIdController extends Controller
{
    public function discover(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));
        $type = trim((string) $request->query('type', ''));

        $profiles = Profile::query()
            ->with('user')
            ->where('is_linkid_active', true)
            ->where('user_id', '!=', $request->user()->id)
            ->whereHas('user', fn ($query) => $query->where('is_active', true))
            ->when($type !== '', fn ($query) => $query->whereJsonContains('linkid_types', $type))
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($profileQuery) use ($search) {
                    $profileQuery->where('display_name', 'like', '%'.$search.'%')
                        ->orWhere('linkid_description', 'like', '%'.$search.'%')
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery->where('name', 'like', '%'.$search.'%')
                                ->orWhere('username', 'like', '%'.$search.'%');
                        });
                });
            })
            ->latest('updated_at')
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

        return view('dashboard.linkid.discover', compact('profiles', 'types', 'search', 'type'));
    }
}
