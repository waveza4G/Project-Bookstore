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
use Inertia\Inertia;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $selectedTable = $request->input('selectedTable', 1); // ตารางที่เลือก
        $sortBy = $request->input('sortBy', 'id'); // เรียงตามคอลัมน์ (ค่าเริ่มต้นเป็น id)
        $sortDirection = $request->input('sortDirection', 'asc'); // เรียงน้อยไปมาก
        $query = $request->input('search'); // คำค้นหา

        // รายชื่อคอลัมน์ที่สามารถใช้เรียงได้ในแต่ละตาราง
        $sortableColumns = [
            1 => ['id', 'customer_id', 'book_id', 'rental_date', 'due_date', 'return_date'],
            2 => ['id', 'book_name','quantity', 'remaining_quantity', 'price','publisher','author','description', 'image','sold_quantity'],
            3 => ['id', 'name', 'username', 'email', 'phone', 'lastname', 'status', 'penalty', 'book_count'],
            4 => ['id', 'payment_amount', 'status', 'payment_date'],
            5 => ['id', 'username', 'email']
        ];

        // ตรวจสอบว่าคอลัมน์ที่เลือกเรียงได้จริงหรือไม่
        if (!isset($sortableColumns[$selectedTable]) || !in_array($sortBy, $sortableColumns[$selectedTable])) {
            $sortBy = 'id'; // ถ้าไม่อยู่ในรายการให้ใช้ค่าเริ่มต้นคือ 'id'
        }

        // คำสั่งดึงข้อมูลตามตารางที่เลือก
        if ($selectedTable == 1) {
            $table = Rental::with('book', 'customer')->when($query, function ($q) use ($query) {
                return $q->where('id', 'like', "%{$query}%")->orWhere('rental_date', 'like', "%{$query}%")
                ->orWhere('due_date', 'like', "%{$query}%")->orWhere('return_date', 'like', "%{$query}%");
            })
            ->orderBy($sortBy, $sortDirection)
            ->paginate(10);
        } elseif ($selectedTable == 2) {
            $table = Book::with('category', 'rentals', 'payments')->when($query, function ($q) use ($query) {
                return $q->where('book_name', 'like', "%{$query}%")
                        ->orWhere('id', 'like', "%{$query}%")->orWhere('quantity', 'like', "%{$query}%")->orWhere('remaining_quantity', 'like', "%{$query}%")
                        ->orWhere('price', 'like', "%{$query}%")->orWhere('publisher', 'like', "%{$query}%")->orWhere('author', 'like', "%{$query}%")
                        ->orWhere('description', 'like', "%{$query}%")->orWhere('sold_quantity', 'like', "%{$query}%");
            })
            ->orderBy($sortBy, $sortDirection)
            ->paginate(10);
        } elseif ($selectedTable == 3) {
            $table = Customer::with('rentals', 'payments')->when($query, function ($q) use ($query) {
                return $q->where('name', 'like', "%{$query}%")->orWhere('username', 'like', "%{$query}%")->orWhere('email', 'like', "%{$query}%")
                         ->orWhere('phone', 'like', "%{$query}%")->orWhere('lastname', 'like', "%{$query}%")->orWhere('status', 'like', "%{$query}%")
                         ->orWhere('penalty', 'like', "%{$query}%")->orWhere('book_count', 'like', "%{$query}%")->orWhere('id', 'like', "%{$query}%");
            })
            ->orderBy($sortBy, $sortDirection)
            ->paginate(10);
        } elseif ($selectedTable == 4) {
            $table = Payment::with('customer', 'book', 'rental')->when($query, function ($q) use ($query) {
                return $q->where('payment_amount', 'like', "%{$query}%")->orWhere('status', 'like', "%{$query}%")
                        ->orWhere('payment_date', 'like', "%{$query}%")->orWhere('id', 'like', "%{$query}%");
            })
            ->orderBy($sortBy, $sortDirection)
            ->paginate(10);
        } elseif ($selectedTable == 5) {
            $table = Admin::when($query, function ($q) use ($query) {
                return $q->where('username', 'like', "%{$query}%")->orWhere('email', 'like', "%{$query}%")->orWhere('id', 'like', "%{$query}%");
            })
            ->orderBy($sortBy, $sortDirection)
            ->paginate(10);
        }

        return Inertia::render('Bookstore/Adminpage', [
            'table' => $table,
            'tableNo' => $selectedTable,
            'sortBy' => $sortBy,
            'sortDirection' => $sortDirection,
            'query' => $query, // ส่งค่าค้นหาไปที่ Frontend
        ]);
    }



    // ฟังก์ชันสำหรับแสดงฟอร์มสร้างข้อมูลใหม่ (ในที่นี้ไม่ได้ใช้)
    public function create()
    {
        //
    }

    // ฟังก์ชันนี้จะถูกเรียกเมื่อมีการสร้างข้อมูลใหม่ (การลงทะเบียน Student)
    public function store(Request $request)
    {
        // กำหนด validation สำหรับข้อมูลที่ส่งเข้ามา
        $request->validate([
            'name' => 'required|string|max:255',        // ชื่อผู้เรียนต้องไม่เกิน 255 ตัวอักษร
            'email' => 'required|email|unique:students,email',  // อีเมลต้องไม่ซ้ำในฐานข้อมูล
            'phone' => 'required|string|max:15',        // หมายเลขโทรศัพท์ต้องไม่เกิน 15 ตัวอักษร
        ]);

        try {
            // สร้าง Student ใหม่โดยใช้ข้อมูลจากฟอร์ม
            $student = Student::create($request->only(['name', 'email', 'phone']));

            // เลือก Course และ Teacher แบบสุ่ม
            $course = Course::inRandomOrder()->first();
            $teacher = Teacher::inRandomOrder()->first();

            // สร้างการลงทะเบียนใหม่ในตาราง Register โดยเชื่อมโยง Student, Course, Teacher
            Register::create([
                'student_id' => $student->id,  // เชื่อมโยงกับ Student ที่สร้างใหม่
                'course_id' => $course->id,    // เชื่อมโยงกับ Course ที่เลือก
                'teacher_id' => $teacher->id,  // เชื่อมโยงกับ Teacher ที่เลือก
            ]);

            // หลังจากสร้างสำเร็จ ให้รีไดเรกต์ไปที่หน้าหลักและแสดงข้อความสำเร็จ
            return redirect()->route('register.index')->with('success', 'Student and registration created successfully');
        } catch (\Exception $e) {
            // ถ้ามีข้อผิดพลาดเกิดขึ้น ให้บันทึกข้อผิดพลาดใน log และรีไดเรกต์ไปที่หน้าหลัก
            Log::error($e->getMessage());
            return redirect()->route('register.index');
        }
    }


    // The update method handles saving the changes
    public function update(Request $request, $table, $id)
{
    if ($table == 1) {
        // สำหรับตาราง Rental หรือ Category
        $validated = $request->validate([
            'category_name' => 'required|string|max:255', // ตัวอย่าง validation
            // เพิ่มการตรวจสอบข้อมูลที่ต้องการ
        ]);
        $model = Category::findOrFail($id); // ตัวอย่างโมเดลสำหรับตาราง 1
    } elseif ($table == 2) {
        // สำหรับตาราง Group
        $validated = $request->validate([
            'group_name' => 'required|string|max:255', // ตัวอย่าง validation
        ]);
        $model = Group::findOrFail($id);
    } elseif ($table == 3) {
        // สำหรับตาราง Admin
        $validated = $request->validate([
            'username' => 'required|string|max:255', // ตัวอย่าง validation
        ]);
        $model = Admin::findOrFail($id);
    } else {
        abort(404); // ถ้าไม่พบตารางที่กำหนด
    }

    // อัพเดทข้อมูลในโมเดลที่เลือก
    $model->update($validated);

    // รีไดเรกต์กลับไปหน้า dashboard พร้อมข้อความสำเร็จ
    return redirect()->route('admin.dashboard')->with('success', 'Record updated successfully');
}

    // ฟังก์ชันนี้ใช้สำหรับลบข้อมูล Student ออกจากฐานข้อมูล
    public function destroy($table, $id)
    {
        try {
            // ตรวจสอบว่า table ไหนที่กำลังจะลบ
            switch ($table) {
                case 1:
                    // สำหรับตาราง Category
                    $model = Category::findOrFail($id); // ค้นหาข้อมูลตาม ID
                    break;

                case 2:
                    // สำหรับตาราง Group
                    $model = Group::findOrFail($id); // ค้นหาข้อมูลตาม ID
                    break;

                case 3:
                    // สำหรับตาราง Admin
                    $model = Admin::findOrFail($id); // ค้นหาข้อมูลตาม ID
                    break;

                default:
                    abort(404); // ถ้าไม่พบตารางที่กำหนด
            }

            // ลบข้อมูลจากฐานข้อมูล
            $model->delete();

            // รีไดเรกต์ไปที่หน้าหลักพร้อมข้อความสำเร็จ
            return redirect()->route('admin.dashboard')->with('success', 'Record deleted successfully');
        } catch (\Exception $e) {
            // ถ้ามีข้อผิดพลาดเกิดขึ้น ให้บันทึกข้อผิดพลาดใน log และรีไดเรกต์ไปที่หน้าหลักพร้อมข้อความผิดพลาด
            Log::error($e->getMessage());
            return redirect()->route('admin.dashboard')->with('error', 'Failed to delete record');
        }
    }
    }

