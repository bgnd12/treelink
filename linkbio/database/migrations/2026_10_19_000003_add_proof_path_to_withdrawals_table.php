<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('withdrawals', 'proof_path')) {
            Schema::table('withdrawals', function (Blueprint $table) {
                $table->string('proof_path')->nullable()->after('processed_at');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('withdrawals', 'proof_path')) {
            Schema::table('withdrawals', function (Blueprint $table) {
                $table->dropColumn('proof_path');
            });
        }
    }
};
