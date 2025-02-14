<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable; // เพิ่มการใช้ Authenticatable
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Admin extends Authenticatable  // เปลี่ยนจาก Model เป็น Authenticatable
{
    use HasFactory, HasApiTokens;

    protected $fillable = ['name', 'email', 'password'];

    // ฟังก์ชันที่ใช้ตรวจสอบว่าเป็น admin หรือไม่

    // ใช้ `getAuthIdentifierName()` จาก `Authenticatable`
    public function getAuthIdentifierName()
    {
        return 'email';  // ใช้ email เป็นตัวระบุ
    }
    protected $hidden = ['password'];  // ซ่อนข้อมูล password

}
