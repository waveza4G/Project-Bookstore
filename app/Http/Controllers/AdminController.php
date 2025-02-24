<?php

namespace App\Http\Controllers;

use App\Models\Rental;
use App\Models\Book;
use App\Models\Admin;
use App\Models\Category;
use App\Models\Group;
use App\Models\Payment;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;


class AdminController extends Controller
{
    public function index(Request $request)
    {
        $selectedTable = $request->input('selectedTable', 1); // ตารางที่เลือก
        $sortBy = $request->input('sortBy', 'id'); // เรียงตามคอลัมน์ (ค่าเริ่มต้นเป็น id)
        $sortDirection = $request->input('sortDirection', 'asc'); // เรียงลำดับ
        $query = $request->input('search'); // คำค้นหา

        // รายชื่อคอลัมน์ที่สามารถใช้เรียงได้ในแต่ละตาราง
        $sortableColumns = [
            1 => ['id', 'customer_id', 'book_id', 'rental_date', 'due_date', 'return_date', 'customer.name', 'customer.lastname', 'status', 'amount'],
            2 => ['id', 'book_name', 'category_id', 'category.category_name', 'group_id', 'group.group_name', 'quantity', 'remaining_quantity', 'price', 'publisher', 'author', 'description', 'sold_quantity'],
            3 => ['id', 'name', 'username', 'email', 'phone', 'lastname', 'book_count'],
            4 => ['id', 'payment_amount', 'status', 'payment_date', 'penalty', 'customer_id', 'customer.name', 'book_id', 'book.book_name', 'rentals.rental_date', 'rental_id'],
            5 => ['id', 'username', 'email']
        ];

        // ตรวจสอบว่าคอลัมน์ที่เลือกเรียงได้จริงหรือไม่
        if (!isset($sortableColumns[$selectedTable]) || !in_array($sortBy, $sortableColumns[$selectedTable])) {
            $sortBy = 'id'; // ถ้าไม่อยู่ในรายการให้ใช้ค่าเริ่มต้นคือ 'id'
        }

        // คำสั่งดึงข้อมูลตามตารางที่เลือก
        if ($selectedTable == 1) {
            // เปลี่ยนการดึงข้อมูล status จาก customers ไปที่ rentals
            $table = Rental::selectRaw('rentals.*, customers.name as customer_name, customers.lastname as customer_lastname, rentals.amount, rentals.status') // เพิ่ม 'status' จาก rentals
                ->join('customers', 'rentals.customer_id', '=', 'customers.id')
                ->with('book', 'customer') // ใช้กับ Eloquent relationship เพื่อดึงข้อมูลหนังสือและลูกค้า
                ->when($query, function ($q) use ($query) {
                    return $q->where('rentals.id', 'like', "%{$query}%")
                        ->orWhere('rentals.rental_date', 'like', "%{$query}%")
                        ->orWhere('rentals.due_date', 'like', "%{$query}%")
                        ->orWhere('rentals.return_date', 'like', "%{$query}%")
                        ->orWhere('customers.name', 'like', "%{$query}%")
                        ->orWhere('customers.lastname', 'like', "%{$query}%")
                        ->orWhere('rentals.status', 'like', "%{$query}%")
                        ->orWhere('rentals.amount', 'like', "%{$query}%");
                })
                ->orderBy(
                    match ($sortBy) {
                        'customer.name' => 'customer_name',
                        'customer.lastname' => 'customer_lastname',
                        default => "rentals.{$sortBy}",
                    },
                    $sortDirection
                )
                ->paginate(10);
        }
        elseif ($selectedTable == 2) {
            $table = Book::selectRaw('books.*, categories.category_name, groups.group_name')
                ->leftJoin('categories', 'books.category_id', '=', 'categories.id')
                ->leftJoin('groups', 'books.group_id', '=', 'groups.id')
                ->with('category', 'group', 'rentals', 'payments')
                ->when($query, function ($q) use ($query) {
                    return $q->where('books.book_name', 'like', "%{$query}%")
                            ->orWhere('books.id', 'like', "%{$query}%")
                            ->orWhere('books.quantity', 'like', "%{$query}%")
                            ->orWhere('books.remaining_quantity', 'like', "%{$query}%")
                            ->orWhere('books.price', 'like', "%{$query}%")
                            ->orWhere('books.publisher', 'like', "%{$query}%")
                            ->orWhere('books.author', 'like', "%{$query}%")
                            ->orWhere('books.description', 'like', "%{$query}%")
                            ->orWhere('books.sold_quantity', 'like', "%{$query}%")
                            ->orWhere('categories.category_name', 'like', "%{$query}%")
                            ->orWhere('groups.group_name', 'like', "%{$query}%");
                })
                ->orderBy(
                    match ($sortBy) {
                        'category.category_name' => 'categories.category_name',
                        'group.group_name' => 'groups.group_name',
                        default => "books.{$sortBy}"
                    },
                    $sortDirection
                )
                ->paginate(10);
        }

        elseif ($selectedTable == 3) {
            $table = Customer::selectRaw('customers.*,
                (SELECT rental_date FROM rentals WHERE rentals.customer_id = customers.id ORDER BY rental_date DESC LIMIT 1) as last_rental_date')
                ->with('rentals', 'payments')
                ->when($query, function ($q) use ($query) {
                    return $q->where('name', 'like', "%{$query}%")
                        ->orWhere('username', 'like', "%{$query}%")
                        ->orWhere('email', 'like', "%{$query}%")
                        ->orWhere('phone', 'like', "%{$query}%")
                        ->orWhere('lastname', 'like', "%{$query}%")
                        ->orWhere('book_count', 'like', "%{$query}%")
                        ->orWhere('id', 'like', "%{$query}%");
                })
                ->orderBy("customers.{$sortBy}", $sortDirection)
                ->paginate(10);
        }
        elseif ($selectedTable == 4) {
            $table = Payment::selectRaw('payments.*,
                    customers.name as customer_name,
                    books.book_name,
                    rentals.rental_date')
                ->leftJoin('customers', 'payments.customer_id', '=', 'customers.id')
                ->leftJoin('books', 'payments.book_id', '=', 'books.id')
                ->leftJoin('rentals', 'payments.rental_id', '=', 'rentals.id')
                ->with('customer', 'book', 'rental') // แทนการใช้ selectRaw กับ relation
                ->when($query, function ($q) use ($query) {
                    return $q->where('payments.payment_amount', 'like', "%{$query}%")
                            ->orWhere('payments.status', 'like', "%{$query}%")
                            ->orWhere('payments.payment_date', 'like', "%{$query}%")
                            ->orWhere('payments.id', 'like', "%{$query}%")
                            ->orWhere('payments.penalty', 'like', "%{$query}%")
                            ->orWhere('payments.customer_id', 'like', "%{$query}%")
                            ->orWhere('customers.name', 'like', "%{$query}%")
                            ->orWhere('payments.book_id', 'like', "%{$query}%")
                            ->orwhere('books.book_name', 'like', "%{$query}%")
                            ->orWhere('payments.rental_id', 'like', "%{$query}%")
                            ->orWhere('rentals.rental_date', 'like', "%{$query}%");
                })
                ->orderBy(
                    match ($sortBy) {
                        'customer.name' => 'customers.name',
                        'book.book_name' => 'books.book_name',
                        'rental.rental_date' => 'rentals.rental_date',
                        default => "payments.{$sortBy}",
                    },
                    $sortDirection
                )
                ->paginate(10);
        }

        elseif ($selectedTable == 5) {
            $table = Admin::when($query, function ($q) use ($query) {
                return $q->where('username', 'like', "%{$query}%")
                        ->orWhere('email', 'like', "%{$query}%")
                        ->orWhere('id', 'like', "%{$query}%");
            })
            ->orderBy("admins.{$sortBy}", $sortDirection)
            ->paginate(10);
        }

        else {
            return abort(404); // ถ้าไม่มีตารางที่เลือก ให้แสดง error 404
        }

        // ✅ ปรับ path รูปภาพให้ใช้ asset()
        $table->getCollection()->transform(function ($book) {
            if ($book->image) {
                $book->image_url = asset("storage/" . $book->image);
            } else {
                $book->image_url = null;
            }
            return $book;
        });

        return Inertia::render('Store/Adminpage', [
            'table' => $table,
            'tableNo' => $selectedTable,
            'sortBy' => $sortBy,
            'sortDirection' => $sortDirection,
            'query' => $query, // ส่งค่าค้นหาไปที่ Frontend
        ]);
    }

    public function edit($table, $id)
    {
        if ($table == 1) {
            $record = Rental::select('id', 'customer_id', 'book_id', 'rental_date', 'due_date','status',  'return_date','amount')
                ->with('customer:id,name,lastname', 'book:id,book_name')
                ->findOrFail($id);
        } elseif ($table == 2) {
            $record = Book::select('id', 'book_name', 'category_id', 'group_id', 'quantity', 'remaining_quantity', 'sold_quantity', 'price', 'publisher', 'author', 'description')
                ->with('category:id,category_name', 'group:id,group_name')
                ->findOrFail($id);

            // ✅ เพิ่มเพื่อส่งข้อมูล categories และ groups ไปยัง Frontend
            $categories = Category::select('id', 'category_name')->get();
            $groups = Group::select('id', 'group_name')->get();
        } elseif ($table == 3) {
            $record = Customer::select('id', 'name', 'lastname', 'username', 'email', 'phone', 'book_count')
                ->findOrFail($id);
        } elseif ($table == 4) {
            $record = Payment::select('id', 'payment_amount', 'status', 'payment_date', 'penalty', 'customer_id', 'book_id', 'rental_id')
                ->with('customer:id,name', 'book:id,book_name', 'rental:id,rental_date')
                ->findOrFail($id);
        } elseif ($table == 5) {
            $record = Admin::select('id', 'username', 'email')->findOrFail($id);
        } else {
            abort(404);
        }

        return Inertia::render('Store/Edit', [
            'table' => $table,
            'record' => $record,
            'categories' => $categories ?? [], // ✅ ใช้ `?? []` เพื่อป้องกัน error
            'groups' => $groups ?? [],
        ]);
    }
    public function update(Request $request, $table, $id)
    {
        if ($table == 1) {
            $validated = $request->validate([
                'rental_date' => 'nullable|date_format:Y-m-d',
                'due_date' => 'nullable|date_format:Y-m-d',
                'return_date' => 'nullable|date_format:Y-m-d',
                'status' => 'required|string',
                'amount' => 'required|numeric|min:0',
            ]);
            Rental::where('id', $id)->update($validated);
        } elseif ($table == 2) {
            $validated = $request->validate([
                'book_name' => 'required|string|max:255',
                'quantity' => 'required|integer|min:1',
                'remaining_quantity' => 'required|integer|min:0',
                'sold_quantity' => 'nullable|integer|min:0',
                'price' => 'required|numeric|min:0',
                'publisher' => 'nullable|string|max:255',
                'author' => 'required|string|max:255',
                'description' => 'required|string',
            ]);

            Book::where('id', $id)->update($validated);

            return redirect()->route('admin.dashboard')->with('success', 'บันทึกข้อมูลสำเร็จ!');
        } elseif ($table == 3) {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'lastname' => 'required|string|max:255',
                'username' => 'required|string|max:255',
                'email' => 'required|string|email',
                'phone' => 'nullable|string|max:20',

            ]);
            Customer::where('id', $id)->update($validated);

        } elseif ($table == 4) {
            $validated = $request->validate([
                'payment_amount' => 'required|numeric|min:0',
                'status' => 'required|string',
                'payment_date' => 'nullable|date_format:Y-m-d',
                'penalty'=> 'required|numeric|min:0'
            ]);
            Payment::where('id', $id)->update($validated);
        } elseif ($table == 5) {
            $validated = $request->validate([
                'username' => 'required|string|max:255',
                'email' => 'required|string|email',
            ]);
            Admin::where('id', $id)->update($validated);
        } else {
            abort(404);
        }

        return redirect()->route('admin.dashboard')->with('success', 'Record updated successfully');
    }



    // ฟังก์ชันนี้ใช้สำหรับลบข้อมูล Student ออกจากฐานข้อมูล
    public function destroy($table, $id)
    {
        try {
            $model = null;

            // ตรวจสอบว่ากำลังลบข้อมูลจากตารางไหน
            if ($table == 1) {
                $model = Rental::findOrFail($id);
            } elseif ($table == 2) {
                $model = Book::findOrFail($id);
            } elseif ($table == 3) {
                $model = Customer::findOrFail($id);
            } elseif ($table == 4) {
                $model = Payment::findOrFail($id);
            } elseif ($table == 5) {
                $model = Admin::findOrFail($id);
            } else {
                abort(404); // ถ้าไม่มีตารางที่ตรงกันให้แสดง 404
            }

            $model->delete();

            // รีไดเรกต์ไปที่หน้าหลักพร้อมข้อความสำเร็จ
            return redirect()->route('admin.dashboard')->with('success', 'Record deleted successfully');

        } catch (\Exception $e) {
            // ถ้ามีข้อผิดพลาดเกิดขึ้น ให้บันทึกข้อผิดพลาดใน log และรีไดเรกต์ไปที่หน้าหลักพร้อมข้อความผิดพลาด
            Log::error($e->getMessage());
            return redirect()->route('admin.dashboard')->with('error', 'Failed to delete record');
        }
    }

    public function showUploadImage($id)
    {
        $book = Book::findOrFail($id);
        return Inertia::render('Store/UpImage', [
            'book' => $book
        ]);
    }
    public function uploadImage(Request $request, $id)
    {
        $book = Book::findOrFail($id);


        $validated = $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // ลบรูปภาพเก่า
        if ($book->image) {
            Storage::disk('public')->delete($book->image);
        }

        // อัปโหลดรูปใหม่
        $imagePath = $request->file('image')->store('book_images', 'public');

        // บันทึกลงฐานข้อมูล
        $book->update(['image' => $imagePath]);

        return redirect()->route('admin.edit', ['table' => 2, 'id' => $id])->with('success', 'อัปโหลดรูปภาพสำเร็จ!');
    }

    // RentalController.php
// RentalController.php

public function showUploadQRImage($id)
{
    $rental = Rental::findOrFail($id);
    return Inertia::render('Store/UpdateQR', [
        'rental' => $rental, // ส่งข้อมูล rental ไปยังหน้า UpdateQR
    ]);
}


public function complete(Request $request)
{
    $admin = Auth::guard('admin')->user();  // ตรวจสอบ admin

    if (!$admin) {
        return response()->json(['error' => 'กรุณาเข้าสู่ระบบก่อน'], 401); // ถ้าไม่ใช่ admin
    }

    // รับข้อมูลจาก request
    $rentalId = $request->input('rental_id'); // รับรหัสการเช่า
    $paymentAmount = $request->input('payment_amount'); // จำนวนเงินที่ชำระ
    $bookId = $request->input('book_id'); // รหัสหนังสือ

    // ค้นหาข้อมูลการเช่า
    $rental = Rental::findOrFail($rentalId);

    // อัปเดตสถานะการเช่าให้เป็น 'borrowed'
    $rental->status = 'borrowed';
    $rental->save();

    // สร้างการชำระเงินใหม่
    $payment = Payment::create([
        'customer_id' => $rental->customer_id, // ใช้ customer_id จากข้อมูลการเช่า
        'book_id' => $bookId,
        'rental_id' => $rental->id,
        'payment_amount' => $paymentAmount, // จำนวนเงินที่ชำระ
        'status' => '-',  // สถานะการชำระเงิน
        'penalty' => 0,  // ไม่มีค่าปรับ
        'payment_date' => now(), // วันที่ชำระเงิน
    ]);

    // อัปเดตจำนวนเงินที่ต้องชำระในตาราง rentals (หากไม่มีก็สามารถตั้งเป็น null ได้)
    $rental->amount = $paymentAmount;
    $rental->save();

    return redirect('/admin/dashboard')->with('message', 'การชำระเงินได้รับการยืนยัน');
}



public function returnbook(Request $request)
{
    // ค้นหาการเช่าจาก rental_id
    $rental = Rental::findOrFail($request->rental_id);

    // ตรวจสอบสถานะการเช่าเป็น 'borrowed'
    if ($rental->status !== 'borrowed') {
        return response()->json(['error' => 'ไม่พบการเช่าที่ต้องการคืน'], 400);
    }

    // คำนวณค่าปรับ (ถ้ามี)
    $penalty = 0;
    $dueDate = $rental->due_date;
    $returnDate = now(); // วันที่คืนหนังสือ

    // ถ้าคืนหนังสือหลังจาก due_date คำนวณค่าปรับ
    if ($returnDate > $dueDate) {
        // คำนวณค่าปรับ (ตัวอย่าง: 0.05 บาทต่อวันเกินกำหนด)
        $penalty = $returnDate->diffInDays($dueDate) * 0.05; // ค่าปรับ 0.05 บาทต่อวัน
    }

    // อัปเดตสถานะการเช่าเป็น 'return' และบันทึกวันที่คืน
    $rental->return_date = $returnDate;
    $rental->status = '-'; // เปลี่ยนสถานะเป็น 'return'
    $rental->save();

    // เพิ่มจำนวนหนังสือที่เหลือ
    $book = $rental->book;
    $book->remaining_quantity += 1;
    $book->save();

    // ค้นหาการชำระเงินที่มีอยู่
    $payment = Payment::where('rental_id', $rental->id)->where('book_id', $rental->book_id)->first();


        $payment->payment_amount += $rental->amount + $penalty;
        $payment->penalty += $penalty; // เพิ่มค่าปรับที่คำนวณ
        $payment->status = 'return'; // เปลี่ยนสถานะการชำระเงินเป็น 'paid'
        $payment->payment_date = now(); // วันที่ชำระเงิน
        $payment->save(); // บันทึกการอัปเดต


    // ส่งผลลัพธ์กลับ
    return redirect()->route('admin.dashboard')->with('message', 'หนังสือถูกคืนแล้วและค่าปรับ (ถ้ามี) ถูกคำนวณ');
}





}
