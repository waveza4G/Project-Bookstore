<?php

namespace App\Http\Controllers;

use App\Models\Rental;
use App\Models\Book;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;  // นำเข้า Auth


class RentalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function complete(Request $request)
    {
        // ตรวจสอบการเข้าสู่ระบบ
        $customer = Auth::guard('customer')->user();

        if (!$customer) {
            return response()->json(['error' => 'กรุณาเข้าสู่ระบบก่อน'], 401); // หากไม่มีข้อมูลลูกค้า
        }

        // รับข้อมูลจาก request
        $amount = $request->input('amount');
        $bookId = $request->input('bookId');
        $rentalDays = $request->input('rental_days'); // จำนวนวันที่เช่า

        // ค้นหาข้อมูลหนังสือ
        $book = Book::find($bookId);

        if (!$book) {
            return response()->json(['error' => 'ไม่พบข้อมูลหนังสือ'], 404);
        }

        // คำนวณจำนวนเงินที่ต้องชำระ
        $calculatedAmount = ceil($book->price * $rentalDays * 0.04); // คำนวณจำนวนเงินที่ต้องชำระ

        // สร้างข้อมูลการเช่าใหม่
        $rental = Rental::create([
            'customer_id' => $customer->id, // ใช้ id ของลูกค้า
            'book_id' => $bookId,
            'rental_days' => $rentalDays,
            'status' => '-', // เปลี่ยนสถานะเป็น 'pending' สำหรับการเช่าที่รอการชำระเงิน
            'rental_date' => now(), // กำหนดวันที่เช่า
            'due_date' => now()->addDays($rentalDays), // คำนวณวันที่คืนตามจำนวนวันที่เช่า
        ]);

        // อัปเดตข้อมูลของลูกค้า
        $customer->book_count += 1; // เพิ่มจำนวนหนังสือที่ยืม
        $customer->save();

        // อัปเดตข้อมูลของหนังสือ
        $book->remaining_quantity -= 1; // ลดจำนวนหนังสือที่เหลือ
        $book->sold_quantity += 1; // เพิ่มจำนวนหนังสือที่ถูกขาย
        $book->save();

        // ส่งกลับผลการดำเนินการและเปลี่ยนหน้าไปที่ dashboard
        return redirect('/dashboard')->with('message', 'การเช่าสำเร็จ');
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($customerId, $bookId)
    {
        // ตรวจสอบว่าเป็นลูกค้าที่กำลังล็อกอินหรือเป็นผู้ดูแลระบบ
        if (Auth::guard('customer')->check()) {
            if (auth()->guard('customer')->user()->id != $customerId) {
                abort(403, 'คุณไม่มีสิทธิ์เข้าถึงข้อมูลนี้');
            }

            // ดึงข้อมูลการยืมหนังสือล่าสุดที่ลูกค้าเลือก
            $rental = auth()->guard('customer')->user()->rentals()->where('book_id', $bookId)->latest()->first();
        } elseif (Auth::guard('admin')->check()) {
            // หากเป็น admin ให้ดึงข้อมูลการยืมหนังสือทั้งหมด
            $rental = Rental::where('book_id', $bookId)->latest()->first();
        } else {
            abort(403, 'คุณต้องเข้าสู่ระบบก่อน');
        }
        // คำนวณจำนวนเงินที่ต้องชำระ
        $rentalAmount = ceil($book->price * $rental->rental_days * 0.04); // คำนวณจากอัตราที่คุณตั้งไว้

        // สร้าง URL สำหรับการชำระเงิน
        $paymentData = route('payment.complete', ['rental_id' => $rental->id, 'amount' => $rentalAmount]);

        // ส่งข้อมูลไปยังหน้า QR Code ใน Bookstore/QrCode
        return Inertia::render('Bookstore/QrCode', ['paymentData' => $paymentData, 'rentalAmount' => $rentalAmount]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Rental $rental)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Rental $rental)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rental $rental)
    {
        //
    }
}
