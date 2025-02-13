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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('book_name');  // ชื่อหนังสือ
            $table->foreignId('typebook_id')->constrained('typebooks')->onDelete('cascade');  // เชื่อมโยงกับ categories
            $table->integer('quantity');  // จำนวนหนังสือ
            $table->integer('remaining_quantity');  // จำนวนหนังสือที่เหลือ
            $table->decimal('price', 8, 2);  // ราคาหนังสือ
            $table->string('image')->nullable();  // ที่เก็บที่อยู่ของรูปภาพ (path หรือ URL)
            $table->timestamps();  // คอลัมน์ created_at และ updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
