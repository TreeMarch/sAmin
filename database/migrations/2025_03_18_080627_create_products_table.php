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
        if (!Schema::hasTable('products')) {
            Schema::create('products', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->string('name');
                $table->string('image')->nullable()->change();
                $table->text('description')->nullable();
                $table->decimal('price', 10, 2); // Giá sản phẩm
                $table->decimal('discount', 5, 2)->default(0); // Giảm giá sản phẩm
                $table->boolean('isActive')->default(true); // Trạng thái hoạt động
                $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
                $table->foreignId('category_id')->constrained()->onDelete('cascade'); // Khóa ngoại cho category_id
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
