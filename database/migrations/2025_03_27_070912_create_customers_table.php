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
        if (!Schema::hasTable('customers')) {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255); // Tên khách hàng
            $table->string('email')->unique();
            $table->string('phone', 11)->unique(); // Số điện thoại (đảm bảo không trùng lặp)
            $table->decimal('total_spent', 15, 2)->default(0); // Tổng số tiền đã chi tiêu
            $table->timestamps(); // created_at & updated_at
        });
    }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
