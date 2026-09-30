<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->string('header_layout')->default('classic')->after('background_value');
            $table->boolean('animation_enabled')->default(true)->after('header_layout');
            $table->string('button_color')->nullable()->after('button_style');
            $table->unsignedInteger('button_radius')->nullable()->after('button_color');
            $table->unsignedInteger('button_border_width')->default(0)->after('button_radius');
            $table->boolean('button_shadow')->default(false)->after('button_border_width');
            $table->string('background_image_path')->nullable()->after('background_value');
            $table->string('seo_title')->nullable()->after('bio');
            $table->text('seo_description')->nullable()->after('seo_title');
        });
    }

    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn([
                'header_layout',
                'animation_enabled',
                'button_color',
                'button_radius',
                'button_border_width',
                'button_shadow',
                'background_image_path',
                'seo_title',
                'seo_description',
            ]);
        });
    }
};