<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private array $contentTables = [
        'profiles',
        'links',
        'products',
        'short_links',
        'profile_views',
    ];

    public function up(): void
    {
        foreach ($this->contentTables as $tableName) {
            if (! Schema::hasColumn($tableName, 'page_id')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->foreignId('page_id')
                        ->nullable()
                        ->after('user_id')
                        ->constrained('link_pages')
                        ->nullOnDelete();
                    $table->index('page_id');
                });
            }
        }

        $users = DB::table('users')->select('id', 'name', 'username')->get();

        foreach ($users as $user) {
            $pageId = DB::table('link_pages')
                ->where('user_id', $user->id)
                ->where('is_default', true)
                ->value('id');

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

            foreach ($this->contentTables as $tableName) {
                DB::table($tableName)
                    ->where('user_id', $user->id)
                    ->whereNull('page_id')
                    ->update(['page_id' => $pageId]);
            }
        }
    }

    public function down(): void
    {
        // This repair migration intentionally keeps recovered ownership data.
    }
};
