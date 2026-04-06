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
        Schema::create('23810310115_products', function (Blueprint $table) {
            $table->id();

            // khóa ngoại
            $table->foreignId('category_id')
                  ->constrained('23810310115_categories')
                  ->onDelete('cascade');

            $table->string('name');                 // tên sản phẩm
            $table->string('slug')->unique();       // slug
            $table->text('description')->nullable();// mô tả

            $table->decimal('price', 10, 2);        // giá
            $table->integer('stock_quantity');      // số lượng tồn

            $table->string('image_path')->nullable(); // ảnh

            $table->enum('status', [
                'draft',
                'published',
                'out_of_stock'
            ]);

            // trường sáng tạo (yêu cầu đề)
            $table->integer('discount_percent')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('23810310115_products');
    }
};