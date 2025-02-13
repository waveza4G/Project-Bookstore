<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Customer extends Model
{
    use HasFactory, HasApiTokens;

    // กำหนดว่าคอลัมน์ไหนบ้างที่สามารถกรอกข้อมูลได้
    protected $fillable = ['customer_name', 'customer_code', 'age', 'address', 'phone', 'email', 'password', 'status', 'penalty'];

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