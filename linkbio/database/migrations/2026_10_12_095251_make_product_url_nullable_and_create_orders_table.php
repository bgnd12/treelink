<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('products', 'url')) {
            Schema::table('products', function (Blueprint $table) {
                $table->text('url')->nullable()->change();
            });
        }

        if (! Schema::hasTable('orders')) {
            Schema::create('orders', function (Blueprint $table) {
                $table->id();
                $table->foreignId('seller_id')->constrained('users')->cascadeOnDelete();
                $table->foreignId('buyer_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('buyer_name');
                $table->string('buyer_contact');
                $table->text('note')->nullable();
                $table->decimal('total', 14, 2)->default(0);
                $table->string('status')->default('pending');
                $table->timestamps();

                $table->index(['seller_id', 'status']);
            });
        }

        if (! Schema::hasTable('order_items')) {
            Schema::create('order_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('order_id')->constrained()->cascadeOnDelete();
                $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
                $table->string('product_name');
                $table->decimal('price', 14, 2)->default(0);
                $table->unsignedInteger('quantity')->default(1);
                $table->decimal('subtotal', 14, 2)->default(0);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');

        if (Schema::hasColumn('products', 'url')) {
            Schema::table('products', function (Blueprint $table) {
                $table->text('url')->nullable(false)->change();
            });
        }
    }
};
