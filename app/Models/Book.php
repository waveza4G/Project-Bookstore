<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    // กำหนดว่าคอลัมน์ไหนบ้างที่สามารถกรอกข้อมูลได้
    protected $fillable = ['book_name', 'category_id','group_id' ,'quantity', 'remaining_quantity', 'price','publisher','author','description', 'image','sold_quantity'];

    // ความสัมพันธ์: หนังสือหนึ่งเล่มจะเชื่อมโยงกับหมวดหมู่เดียว
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function rentals()
    {
        return $this->hasMany(Rental::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

}
