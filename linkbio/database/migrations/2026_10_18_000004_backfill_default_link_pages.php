<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $users = DB::table('users')->select('id', 'name', 'username')->get();

        foreach ($users as $user) {
            $page = DB::table('link_pages')->where('user_id', $user->id)->first();

            if (! $page) {
                $pageId = DB::table('link_pages')->insertGetId([
                    'user_id' => $user->id,
                    'name' => $user->name,
                    'username' => $user->username,
                    'is_default' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                $pageId = $page->id;
            }

            foreach (['profiles', 'links', 'products', 'short_links', 'profile_views'] as $table) {
                DB::table($table)->where('user_id', $user->id)->whereNull('page_id')->update(['page_id' => $pageId]);
            }
        }
    }

    public function down(): void
    {
        // Keep the migrated ownership data intact on rollback.
    }
};
