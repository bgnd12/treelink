<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Normalise the legacy "pending" order status into the richer status set
     * used by the shop (awaiting payment / awaiting confirmation). This only
     * relabels existing rows, it never deletes or resets user data.
     */
    public function up(): void
    {
        DB::table('orders')
            ->where('status', 'pending')
            ->whereNotNull('payment_proof')
            ->update(['status' => 'awaiting_confirmation']);

        DB::table('orders')
            ->where('status', 'pending')
            ->update(['status' => 'awaiting_payment']);
    }

    public function down(): void
    {
        DB::table('orders')
            ->whereIn('status', ['awaiting_payment', 'awaiting_confirmation'])
            ->update(['status' => 'pending']);
    }
};
