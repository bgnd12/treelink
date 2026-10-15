<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('product_variants')) {
            Schema::create('product_variants', function (Blueprint $table) {
                $table->id();
                $table->foreignId('product_id')->constrained()->cascadeOnDelete();
                $table->string('color')->nullable();
                $table->string('size')->nullable();
                $table->string('sku', 100)->nullable();
                $table->decimal('price', 14, 2)->nullable();
                $table->unsignedInteger('stock')->nullable();
                $table->string('image_path')->nullable();
                $table->unsignedInteger('position')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();

                $table->index(['product_id', 'position']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
