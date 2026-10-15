<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (! Schema::hasColumn('orders', 'payment_gateway')) {
                $table->string('payment_gateway')->nullable()->after('payment_method');
            }
            if (! Schema::hasColumn('orders', 'payment_reference')) {
                $table->string('payment_reference')->nullable()->after('payment_gateway');
            }
            if (! Schema::hasColumn('orders', 'payment_external_id')) {
                $table->string('payment_external_id')->nullable()->after('payment_reference');
            }
            if (! Schema::hasColumn('orders', 'payment_url')) {
                $table->text('payment_url')->nullable()->after('payment_external_id');
            }
            if (! Schema::hasColumn('orders', 'payment_qr_url')) {
                $table->text('payment_qr_url')->nullable()->after('payment_url');
            }
            if (! Schema::hasColumn('orders', 'payment_va_number')) {
                $table->string('payment_va_number')->nullable()->after('payment_qr_url');
            }
            if (! Schema::hasColumn('orders', 'payment_merchant_id')) {
                $table->string('payment_merchant_id')->nullable()->after('payment_va_number');
            }
            if (! Schema::hasColumn('orders', 'payment_status_raw')) {
                $table->string('payment_status_raw')->nullable()->after('payment_merchant_id');
            }
            if (! Schema::hasColumn('orders', 'payment_metadata')) {
                $table->json('payment_metadata')->nullable()->after('payment_status_raw');
            }
            if (! Schema::hasColumn('orders', 'paid_at')) {
                $table->timestamp('paid_at')->nullable()->after('payment_metadata');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'payment_gateway',
                'payment_reference',
                'payment_external_id',
                'payment_url',
                'payment_qr_url',
                'payment_va_number',
                'payment_merchant_id',
                'payment_status_raw',
                'payment_metadata',
                'paid_at',
            ]);
        });
    }
};
