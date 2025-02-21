<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{
    use HasFactory;

    // กำหนดว่าคอลัมน์ไหนบ้างที่สามารถกรอกข้อมูลได้
    protected $fillable = ['customer_id', 'book_id', 'rental_date', 'due_date', 'return_date'];

    // ความสัมพันธ์: การยืมหนังสือหนึ่งเล่มจะเชื่อมโยงกับลูกค้าหนึ่งคน
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function rental()
    {
        return $this->belongsTo(Rental::class);
    }

}
