<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    /** @use HasFactory<\Database\Factories\GroupFactory> */
    use HasFactory;
    protected $fillable = ['group_name'];

    // ความสัมพันธ์: หมวดหมู่สามารถมีหลายหนังสือ
    public function books()
    {
        return $this->hasMany(Book::class); // เปลี่ยนจาก Book เป็น Books
    }
}
