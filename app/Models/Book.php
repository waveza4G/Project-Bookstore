<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    // กำหนดว่าคอลัมน์ไหนบ้างที่สามารถกรอกข้อมูลได้
    protected $fillable = ['book_name', 'typebook_id', 'quantity', 'remaining_quantity', 'price', 'image'];

    // ความสัมพันธ์: หนังสือหนึ่งเล่มจะเชื่อมโยงกับหมวดหมู่เดียว
    public function typebook()
    {
        return $this->belongsTo(Typebook::class);
    }

    // ความสัมพันธ์: หนังสือหนึ่งเล่มสามารถมีหลายการยืม
    public function rentals()
    {
        return $this->hasMany(Rental::class);
    }

    // ความสัมพันธ์: หนังสือสามารถมีหลายการชำระเงิน (เชื่อมกับการยืม)
    public function payments()
    {
        return $this->hasMany(Payment::class); 
    }
}
