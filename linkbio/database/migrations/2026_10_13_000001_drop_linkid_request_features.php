<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('conversations', 'link_id_request_id')) {
            Schema::table('conversations', function (Blueprint $table) {
                // The original column was also declared unique. SQLite cannot
                // rebuild the table while that index still references a
                // column being dropped.
                $table->dropUnique(['link_id_request_id']);
                $table->dropConstrainedForeignId('link_id_request_id');
            });
        }

        Schema::table('profiles', function (Blueprint $table) {
            $columns = array_values(array_filter(
                ['is_linkid_active', 'linkid_types', 'linkid_description'],
                fn (string $column) => Schema::hasColumn('profiles', $column),
            ));

            if ($columns !== []) {
                $table->dropColumn($columns);
            }
        });

        Schema::dropIfExists('link_id_requests');
    }

    public function down(): void
    {
        Schema::create('link_id_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recipient_user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('requester_user_id')->nullable()->after('recipient_user_id')->constrained('users')->nullOnDelete();
            $table->string('requester_name');
            $table->string('requester_email');
            $table->string('type')->default('general');
            $table->text('message')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();
        });

        if (! Schema::hasColumn('conversations', 'link_id_request_id')) {
            Schema::table('conversations', function (Blueprint $table) {
                $table->foreignId('link_id_request_id')->nullable()->unique()->constrained('link_id_requests')->nullOnDelete();
            });
        }

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
};
