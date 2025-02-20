<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // สร้างตาราง groups
        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->string('group_name'); // เปลี่ยนจาก name() เป็น string()
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('groups');
    }
};
