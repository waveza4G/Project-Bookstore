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
            $table->enum('typebook_name', ['Caton','Manga','Novel','fiction','-'])->default('-');
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
