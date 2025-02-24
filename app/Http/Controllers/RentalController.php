<?php

namespace App\Http\Controllers;

use App\Models\Rental;
use App\Models\Book;
use App\Models\Payment; // เพิ่มการนำเข้า Payment model
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;  // นำเข้า Auth


class RentalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function rental(Request $request)
    {
        $customer = Auth::guard('customer')->user();
        if (!$customer) {
            return response()->json(['error' => 'กรุณาเข้าสู่ระบบก่อน'], 401);
        }

        // รับข้อมูลจาก request
        $rentalAmount = $request->input('amount');
        $bookId = $request->input('bookId');
        $rentalDays = $request->input('rental_days');

        // ค้นหาหนังสือ
        $book = Book::find($bookId);
        if (!$book) {
            return response()->json(['error' => 'ไม่พบข้อมูลหนังสือ'], 404);
        }

        // ลดจำนวนหนังสือที่เหลือ
        $book->remaining_quantity -= 1;
        $book->sold_quantity += 1;
        $book->save();

        // อัปเดตจำนวนหนังสือที่ลูกค้าเช่า
        $customer->book_count += 1;
        $customer->save();

        // ตรวจสอบว่ามีการเช่าหนังสือเล่มนี้อยู่แล้วหรือไม่
        $existingRental = Rental::where('customer_id', $customer->id)
                                ->where('book_id', $bookId)
                                ->first();  // ค้นหาการเช่าหนังสือของลูกค้า

        if ($existingRental) {
            // ถ้ามีการเช่าอยู่แล้ว
            $existingRental->amount = $rentalAmount; // เพิ่มจำนวนเงินที่ต้องชำระ
            $existingRental->due_date = now()->addDays($rentalDays); // อัปเดตวันครบกำหนด
            $existingRental->status = 'waiting'; // เปลี่ยนสถานะเป็น 'waiting'
            $existingRental->save(); // บันทึกการอัปเดต
        } else {
            // ถ้ายังไม่มีการเช่าให้สร้างข้อมูลใหม่
            Rental::create([
                'customer_id' => $customer->id,
                'book_id' => $bookId,
                'status' => 'waiting',  // สถานะรอการชำระเงิน
                'rental_date' => now(),
                'due_date' => now()->addDays($rentalDays), // เก็บวันครบกำหนดตามที่ได้รับจาก request
                'amount' => $rentalAmount, // จำนวนเงินที่ต้องชำระ
            ]);
        }

        // ส่งข้อมูลจำนวนเงินที่ต้องชำระไปกับข้อความหลังการเช่า
        return redirect('/dashboard')->with('message', 'การเช่าสำเร็จ')->with('rentalAmount', $rentalAmount);
    }



}
