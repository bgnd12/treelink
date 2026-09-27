<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * The Enhance tab columns.
 *
 * Guarded with hasColumn() because the earlier
 * 2025_01_01_000005_add_editor_fields_to_profiles_table migration already
 * created seo_title / seo_description on some databases. Without the guard the
 * migration aborts on a duplicate column, which is what left the app stuck.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            if (! Schema::hasColumn('profiles', 'featured_link_id')) {
                $table->unsignedBigInteger('featured_link_id')->nullable()->after('social_links');
            }

            if (! Schema::hasColumn('profiles', 'animations_enabled')) {
                $table->boolean('animations_enabled')->default(false)->after('featured_link_id');
            }

            if (! Schema::hasColumn('profiles', 'seo_title')) {
                $table->string('seo_title', 60)->nullable()->after('animations_enabled');
            }

            if (! Schema::hasColumn('profiles', 'seo_description')) {
                $table->string('seo_description', 300)->nullable()->after('seo_title');
            }
        });
    }

    public function down(): void
    {
        $columns = array_values(array_filter(
            ['featured_link_id', 'animations_enabled', 'seo_title', 'seo_description'],
            fn (string $column): bool => Schema::hasColumn('profiles', $column),
        ));

        if ($columns !== []) {
            Schema::table('profiles', fn (Blueprint $table) => $table->dropColumn($columns));
        }
    }
};
