<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        foreach (['profiles', 'links', 'products', 'short_links', 'profile_views'] as $table) {
            Schema::table($table, function (Blueprint $blueprint) {
                $blueprint->foreignId('page_id')->nullable()->after('user_id')->constrained('link_pages')->nullOnDelete();
                $blueprint->index(['page_id']);
            });
        }

        $users = DB::table('users')->select('id', 'name', 'username')->get();
        foreach ($users as $user) {
            $pageId = DB::table('link_pages')->where('user_id', $user->id)->value('id');
            if (! $pageId) {
                $pageId = DB::table('link_pages')->insertGetId([
                    'user_id' => $user->id,
                    'name' => $user->name,
                    'username' => $user->username,
                    'is_default' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            foreach (['profiles', 'links', 'products', 'short_links', 'profile_views'] as $table) {
                DB::table($table)->where('user_id', $user->id)->update(['page_id' => $pageId]);
            }
        }
    }

    public function down(): void
    {
        foreach (['profiles', 'links', 'products', 'short_links', 'profile_views'] as $table) {
            Schema::table($table, function (Blueprint $blueprint) {
                $blueprint->dropConstrainedForeignId('page_id');
            });
        }
    }
};
