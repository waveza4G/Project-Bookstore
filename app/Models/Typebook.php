<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Typebook extends Model
{
    use HasFactory;

    // กำหนดว่าคอลัมน์ไหนบ้างที่สามารถกรอกข้อมูลได้
    protected $fillable = ['typebook_name'];

    // ความสัมพันธ์: หมวดหมู่สามารถมีหลายหนังสือ
    public function books()
    {
        return $this->hasMany(Book::class); // เปลี่ยนจาก Book เป็น Books
    }
}