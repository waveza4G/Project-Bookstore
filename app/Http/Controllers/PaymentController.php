<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    /**
     * Show the form for creating a new resource.
     */


        // ฟังก์ชัน create ที่คาดหวังรับ 3 พารามิเตอร์
        public function confirmPayment(Request $request)
        {
            // ตรวจสอบการอัปโหลดรูปภาพ
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // ตรวจสอบการอัปโหลดรูปภาพ
            ]);

            // เก็บไฟล์ใน storage
            $imagePath = $request->file('image')->store('public/images'); // เก็บไฟล์ในโฟลเดอร์ public/images

            // ดึงข้อมูลหนังสือที่เกี่ยวข้อง
            $book = Book::findOrFail($request->input('bookId'));

            // คำนวณจำนวนวันที่เช่า
            $rentalDays = $request->input('rentalDays', 7); // หากไม่ได้ส่ง rentalDays ให้ใช้ 7 วันเป็นค่าเริ่มต้น

            // คำนวณวันที่กำหนดคืน (due date) โดยเพิ่มจำนวนวันที่เช่า
            $dueDate = now()->addDays($rentalDays);

            // สร้างการเช่าหนังสือในฐานข้อมูล
            $rental = Rental::create([
                'customer_id' => Auth::guard('customer')->id(),
                'book_id' => $request->input('bookId'),
                'rental_date' => now(), // กำหนดวันที่เช่าตามวันที่ฟังก์ชันทำงาน
                'due_date' => $dueDate, // กำหนดวันที่คืน
                'image' => $imagePath, // เก็บ path ของไฟล์ที่อัปโหลด
            ]);

            // อัปเดตจำนวนการยืมของลูกค้า
            $customer = $rental->customer;
            $customer->increment('book_count'); // เพิ่ม 1 ในจำนวนหนังสือที่ยืม
            $customer->update(['status' => 'borrowed']); // อัปเดตสถานะเป็น "borrowed"

            // อัปเดตข้อมูลหนังสือ
            $book->decrement('remaining_quantity'); // ลดจำนวนหนังสือที่เหลือ
            $book->increment('sold_quantity'); // เพิ่มจำนวนหนังสือที่ขาย

            // ส่งข้อมูลไปยังหน้า 'Detail.jsx' พร้อมกับข้อมูลการเช่าและหนังสือ
            return Inertia::visit("/book/{$book->id}", [
                'rental' => $rental,
                'book' => $book,  // ส่งข้อมูลหนังสือไปยังหน้า Detail.jsx
                'customer' => $customer,  // ส่งข้อมูลลูกค้าไปยังหน้า Detail.jsx
            ])->with('success', 'การชำระเงินสำเร็จ และการยืมหนังสือได้ถูกบันทึก');
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
    public function show(Payment $payment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payment $payment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Payment $payment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        //
    }
}
