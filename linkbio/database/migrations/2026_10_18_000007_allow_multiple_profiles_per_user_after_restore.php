<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('profiles', 'page_id')) {
            return;
        }

        $indexNames = collect(Schema::getIndexes('profiles'))
            ->pluck('name')
            ->all();

        // Fresh databases already receive this change from the original
        // profile/page migration. This repair is only needed when the old
        // restored schema still has the user_id unique index.
        if (! in_array('profiles_user_id_unique', $indexNames, true)) {
            return;
        }

        Schema::table('profiles', function (Blueprint $table) {
            // MySQL may use the unique index to enforce the foreign key,
            // so remove and recreate the FK around the index change.
            $table->dropForeign(['user_id']);
            $table->dropUnique('profiles_user_id_unique');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();
            $table->unique('page_id');
        });
    }

    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropUnique('profiles_page_id_unique');
            $table->unique('user_id');
        });
    }
};
