<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (! Schema::hasColumn('orders', 'shipping_name')) {
                $table->string('shipping_name')->nullable()->after('buyer_contact');
            }
            if (! Schema::hasColumn('orders', 'shipping_phone')) {
                $table->string('shipping_phone')->nullable()->after('shipping_name');
            }
            if (! Schema::hasColumn('orders', 'shipping_address')) {
                $table->text('shipping_address')->nullable()->after('shipping_phone');
            }
            if (! Schema::hasColumn('orders', 'shipping_city')) {
                $table->string('shipping_city')->nullable()->after('shipping_address');
            }
            if (! Schema::hasColumn('orders', 'shipping_province')) {
                $table->string('shipping_province')->nullable()->after('shipping_city');
            }
            if (! Schema::hasColumn('orders', 'shipping_postal_code')) {
                $table->string('shipping_postal_code')->nullable()->after('shipping_province');
            }
            if (! Schema::hasColumn('orders', 'payment_method')) {
                $table->string('payment_method')->nullable()->after('shipping_postal_code');
            }
            if (! Schema::hasColumn('orders', 'payment_status')) {
                $table->string('payment_status')->default('unpaid')->after('payment_method');
            }
            if (! Schema::hasColumn('orders', 'payment_proof')) {
                $table->string('payment_proof')->nullable()->after('payment_status');
            }
            if (! Schema::hasColumn('orders', 'tracking_number')) {
                $table->string('tracking_number')->nullable()->after('payment_proof');
            }
            if (! Schema::hasColumn('orders', 'paid_at')) {
                $table->timestamp('paid_at')->nullable()->after('tracking_number');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'shipping_name', 'shipping_phone', 'shipping_address', 'shipping_city',
                'shipping_province', 'shipping_postal_code', 'payment_method',
                'payment_status', 'payment_proof', 'tracking_number', 'paid_at',
            ]);
        });
    }
};
