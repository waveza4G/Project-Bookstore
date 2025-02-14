<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;  // เพิ่มบรรทัดนี้เพื่อให้ `Customer` เป็น Authenticatable
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Customer extends Authenticatable 
{
    use HasFactory, HasApiTokens;

    // กำหนดว่าคอลัมน์ไหนบ้างที่สามารถกรอกข้อมูลได้
    protected $fillable = ['name', 'lastname', 'username', 'phone', 'email', 'password', 'status', 'penalty', 'book_count'];

    // ความสัมพันธ์: ลูกค้าสามารถยืมหนังสือหลายเล่ม
    public function rentals()
    {
        return $this->hasMany(Rental::class);
    }

    // ความสัมพันธ์: ลูกค้าสามารถทำการชำระเงินหลายครั้ง
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    // เก็บค่ารหัสผ่านโดยการเข้ารหัส
    protected $hidden = ['password'];

    // ใช้ `getAuthIdentifierName()` จาก `Authenticatable`
    public function getAuthIdentifierName()
    {
        return 'email';  // ใช้ email เป็นตัวระบุ
    }
}