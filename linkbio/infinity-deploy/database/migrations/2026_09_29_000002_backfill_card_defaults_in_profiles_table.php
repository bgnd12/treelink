<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Backfill the card columns for rows that were created before they existed.
 *
 * MySQL does not always apply a new column's DEFAULT to rows that are already
 * in the table, so profiles created before this migration ended up with a
 * disabled card and no shadow.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('profiles', 'card_enabled')) {
            return;
        }

        DB::table('profiles')->whereNull('card_enabled')->update(['card_enabled' => true]);
        DB::table('profiles')->whereNull('card_style')->update(['card_style' => 'glass']);
        DB::table('profiles')->whereNull('card_border_width')->update(['card_border_width' => 1]);
        DB::table('profiles')->where('card_shadow', false)->update(['card_shadow' => true]);
    }

    public function down(): void
    {
        //
    }
};
