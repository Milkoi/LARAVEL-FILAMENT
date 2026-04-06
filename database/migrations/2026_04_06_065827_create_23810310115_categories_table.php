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
        Schema::create('23810310115_categories', function (Blueprint $table) {
            $table->id();

            $table->string('name')->unique();       // tên danh mục
            $table->string('slug')->unique();       // slug
            $table->text('description')->nullable(); // mô tả
            $table->boolean('is_visible')->default(true); // hiển thị hay không

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('23810310115_categories');
    }
};