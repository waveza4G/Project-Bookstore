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
        $book->remaining_quantity -= 1; // ลดจำนวนหนังสือที่เหลือ
        $book->sold_quantity += 1;
        $book->save();
        if (!$book) {
            return response()->json(['error' => 'ไม่พบข้อมูลหนังสือ'], 404);
        }

        $customer->book_count += 1;
        $customer->save();

        // สร้างข้อมูลการเช่าใหม่
        $rental = Rental::create([
            'customer_id' => $customer->id,
            'book_id' => $bookId,
            'rental_days' => $rentalDays,
            'status' => '-',
            'rental_date' => now(),
            'due_date' => now()->addDays($rentalDays),
            'amount' => $rentalAmount, // เก็บจำนวนเงินที่ต้องชำระ
        ]);

        // ส่งข้อมูลจำนวนเงินที่ต้องชำระไปกับข้อความหลังการเช่า
        return redirect('/dashboard')->with('message', 'การเช่าสำเร็จ')->with('rentalAmount', $rentalAmount);
    }


}
