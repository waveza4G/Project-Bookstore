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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();  // รหัสการชำระเงิน
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');  // รหัสลูกค้า (เชื่อมโยงกับตาราง customers)
            $table->foreignId('book_id')->constrained('books')->onDelete('cascade');  // รหัสหนังสือ (เชื่อมโยงกับตาราง books)
            $table->foreignId('rental_id')->constrained('rentals')->onDelete('cascade');  // รหัสการเช่า (เชื่อมโยงกับตาราง rentals)
            $table->decimal('payment_amount', 8, 2);  // จำนวนเงินที่ชำระ
            $table->enum('status', ['on_time', 'late'])->nullable();  // สถานะการชำระเงิน (ไม่มีค่าถ้ายังไม่คืน, 'on_time' หรือ 'late')
            $table->timestamp('payment_date')->nullable();  // วันที่ชำระเงิน (สามารถเก็บเป็น NULL ได้ในกรณีที่ยังไม่ชำระ)
            $table->timestamps();  // คอลัมน์ created_at และ updated_at

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
