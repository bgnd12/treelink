<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->unsignedBigInteger('featured_link_id')->nullable()->after('social_links');
            $table->boolean('animations_enabled')->default(false)->after('featured_link_id');
            $table->string('seo_title', 60)->nullable()->after('animations_enabled');
            $table->string('seo_description', 300)->nullable()->after('seo_title');
        });
    }

    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn(['featured_link_id', 'animations_enabled', 'seo_title', 'seo_description']);
        });
    }
};