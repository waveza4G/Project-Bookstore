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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name');
            $table->string('customer_code');
            $table->integer('age');
            $table->text('address');
            $table->string('phone')->unique();  // ห้ามเบอร์โทรศัพท์ซ้ำ
            $table->string('email')->unique();  // ห้ามอีเมลซ้ำ
            $table->string('password');
            $table->enum('status', ['borrowed', 'returned'])->default('borrowed');  // สถานะการยืม (กำลังยืม หรือ คืนแล้ว)
            $table->decimal('penalty', 8, 2)->default(0);  // ค่าปรับ (เริ่มต้นเป็น 0 หากยังไม่ได้ค่าปรับ)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
