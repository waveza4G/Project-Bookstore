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
        Schema::create('rentals', function (Blueprint $table) {
            $table->id();  // รหัสการเช่า
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');  // รหัสลูกค้า (เชื่อมโยงกับตาราง customers)
            $table->foreignId('book_id')->constrained('books')->onDelete('cascade');  // รหัสหนังสือ (เชื่อมโยงกับตาราง books)
            $table->date('rental_date');  // วันที่เช่า
            $table->date('due_date');  // วันที่กำหนดคืน
            $table->date('return_date')->nullable();  // วันที่คืน (อาจเป็น null ถ้ายังไม่คืน)
            $table->timestamps();  // คอลัมน์ created_at และ updated_at

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rentals');
    }
};
