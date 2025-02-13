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
        Schema::create('typebooks', function (Blueprint $table) {
            $table->id();
            $table->string('typebook_name'); // เพิ่มคอลัมน์สำหรับชื่อหมวดหมู่
            $table->timestamps();  // เพิ่มคอลัมน์ created_at และ updated_at

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('typebooks');
    }
};
