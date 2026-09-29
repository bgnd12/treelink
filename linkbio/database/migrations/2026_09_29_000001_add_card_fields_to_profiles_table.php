<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Card container options for the public page.
 *
 * Same hasColumn() guard as the other additive profile migrations so this can
 * safely re-run on databases that already carry some of the columns.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            if (! Schema::hasColumn('profiles', 'card_enabled')) {
                $table->boolean('card_enabled')->default(true)->after('background_image_path');
            }

            if (! Schema::hasColumn('profiles', 'card_style')) {
                $table->string('card_style', 20)->default('glass')->after('card_enabled');
            }

            if (! Schema::hasColumn('profiles', 'card_color')) {
                $table->string('card_color', 20)->nullable()->after('card_style');
            }

            if (! Schema::hasColumn('profiles', 'card_opacity')) {
                $table->unsignedTinyInteger('card_opacity')->nullable()->after('card_color');
            }

            if (! Schema::hasColumn('profiles', 'card_radius')) {
                $table->unsignedSmallInteger('card_radius')->nullable()->after('card_opacity');
            }

            if (! Schema::hasColumn('profiles', 'card_border_width')) {
                $table->unsignedTinyInteger('card_border_width')->default(1)->after('card_radius');
            }

            if (! Schema::hasColumn('profiles', 'card_shadow')) {
                $table->boolean('card_shadow')->default(true)->after('card_border_width');
            }
        });
    }

    public function down(): void
    {
        $columns = array_values(array_filter(
            ['card_enabled', 'card_style', 'card_color', 'card_opacity', 'card_radius', 'card_border_width', 'card_shadow'],
            fn (string $column): bool => Schema::hasColumn('profiles', $column),
        ));

        if ($columns !== []) {
            Schema::table('profiles', fn (Blueprint $table) => $table->dropColumn($columns));
        }
    }
};
