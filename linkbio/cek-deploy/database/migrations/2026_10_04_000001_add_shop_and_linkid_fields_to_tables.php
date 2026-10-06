<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (! Schema::hasColumn('products', 'description')) {
                $table->text('description')->nullable()->after('url');
            }
            if (! Schema::hasColumn('products', 'category')) {
                $table->string('category')->nullable()->after('description');
            }
        });

        Schema::table('profiles', function (Blueprint $table) {
            if (! Schema::hasColumn('profiles', 'is_linkid_active')) {
                $table->boolean('is_linkid_active')->default(false)->after('social_links');
            }
            if (! Schema::hasColumn('profiles', 'linkid_types')) {
                $table->json('linkid_types')->nullable()->after('is_linkid_active');
            }
            if (! Schema::hasColumn('profiles', 'linkid_description')) {
                $table->text('linkid_description')->nullable()->after('linkid_types');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'description')) {
                $table->dropColumn('description');
            }
            if (Schema::hasColumn('products', 'category')) {
                $table->dropColumn('category');
            }
        });

        Schema::table('profiles', function (Blueprint $table) {
            if (Schema::hasColumn('profiles', 'is_linkid_active')) {
                $table->dropColumn('is_linkid_active');
            }
            if (Schema::hasColumn('profiles', 'linkid_types')) {
                $table->dropColumn('linkid_types');
            }
            if (Schema::hasColumn('profiles', 'linkid_description')) {
                $table->dropColumn('linkid_description');
            }
        });
    }
};
