<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    // กำหนดว่าคอลัมน์ไหนบ้างที่สามารถกรอกข้อมูลได้
    protected $fillable = ['customer_id', 'book_id', 'rental_id', 'payment_amount', 'status', 'payment_date'];

    // ความสัมพันธ์: การชำระเงินเชื่อมโยงกับลูกค้า
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // ความสัมพันธ์: การชำระเงินเชื่อมโยงกับหนังสือ
    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    // ความสัมพันธ์: การชำระเงินเชื่อมโยงกับการยืม
    public function rental()
    {
        return $this->belongsTo(Rental::class);
    }
}
