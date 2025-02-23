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
            1 => ['id', 'customer_id', 'book_id', 'rental_date', 'due_date', 'return_date', 'customer.name', 'customer.lastname','customer.status'],
            2 => ['id', 'book_name','category_id','category.category_name','group_id','group.group_name', 'quantity', 'remaining_quantity','price', 'publisher', 'author', 'description','image','sold_quantity'],
            3 => ['id', 'name', 'username', 'email', 'phone', 'lastname', 'status', 'penalty', 'book_count','rentals.rental_date','payments.status'],
            4 => ['id', 'payment_amount', 'status', 'payment_date','customer_id','book_id','rental_id'],
            5 => ['id', 'username', 'email']
        ];

        // ตรวจสอบว่าคอลัมน์ที่เลือกเรียงได้จริงหรือไม่
        if (!isset($sortableColumns[$selectedTable]) || !in_array($sortBy, $sortableColumns[$selectedTable])) {
            $sortBy = 'id'; // ถ้าไม่อยู่ในรายการให้ใช้ค่าเริ่มต้นคือ 'id'
        }

        // คำสั่งดึงข้อมูลตามตารางที่เลือก
        if ($selectedTable == 1) {
            $table = Rental::selectRaw('rentals.*, customers.name as customer_name, customers.lastname as customer_lastname, customers.status as customer_status')
                ->join('customers', 'rentals.customer_id', '=', 'customers.id')
                ->with('book', 'customer')
                ->when($query, function ($q) use ($query) {
                    return $q->where('rentals.id', 'like', "%{$query}%")
                        ->orWhere('rentals.rental_date', 'like', "%{$query}%")
                        ->orWhere('rentals.due_date', 'like', "%{$query}%")
                        ->orWhere('rentals.return_date', 'like', "%{$query}%")
                        ->orWhere('customers.name', 'like', "%{$query}%")
                        ->orWhere('customers.lastname', 'like', "%{$query}%")
                        ->orWhere('customers.status', 'like', "%{$query}%");
                })
                ->orderBy(
                    match ($sortBy) {
                        'customer.name' => 'customer_name',
                        'customer.lastname' => 'customer_lastname',
                        'customer.status' => 'customer_status',
                        default => "rentals.{$sortBy}"
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
                (SELECT rental_date FROM rentals WHERE rentals.customer_id = customers.id ORDER BY rental_date DESC LIMIT 1) as last_rental_date,
                (SELECT status FROM payments WHERE payments.customer_id = customers.id ORDER BY payment_date DESC LIMIT 1) as last_payment_status'
            )
            ->with('rentals', 'payments')
            ->when($query, function ($q) use ($query) {
                return $q->where('name', 'like', "%{$query}%")
                    ->orWhere('username', 'like', "%{$query}%")
                    ->orWhere('email', 'like', "%{$query}%")
                    ->orWhere('phone', 'like', "%{$query}%")
                    ->orWhere('lastname', 'like', "%{$query}%")
                    ->orWhere('status', 'like', "%{$query}%")
                    ->orWhere('penalty', 'like', "%{$query}%")
                    ->orWhere('book_count', 'like', "%{$query}%")
                    ->orWhere('id', 'like', "%{$query}%");
            });

            // จัดการการเรียงลำดับ
            if ($sortBy === 'rentals.rental_date') {
                $table = $table->orderBy('last_rental_date', $sortDirection);
            } elseif ($sortBy === 'payments.status') {
                $table = $table->orderBy('last_payment_status', $sortDirection);
            } else {
                $table = $table->orderBy("customers.{$sortBy}", $sortDirection);
            }

            $table = $table->paginate(10);
        }

        elseif ($selectedTable == 4) {
            $table = Payment::with('customer', 'book', 'rental')
                ->when($query, function ($q) use ($query) {
                    return $q->where('payment_amount', 'like', "%{$query}%")
                            ->orWhere('status', 'like', "%{$query}%")
                            ->orWhere('payment_date', 'like', "%{$query}%")
                            ->orWhere('id', 'like', "%{$query}%")
                            ->orWhere('customer_id', 'like', "%{$query}%")
                            ->orWhere('book_id', 'like', "%{$query}%")
                            ->orWhere('rental_id', 'like', "%{$query}%");
                })
                ->orderBy("payments.{$sortBy}", $sortDirection)
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
            $record = Rental::select('id', 'customer_id', 'book_id', 'rental_date', 'due_date', 'return_date')
                ->with('customer:id,name,lastname,status', 'book:id,book_name')
                ->findOrFail($id);
        } elseif ($table == 2) {
            $record = Book::select('id', 'book_name', 'category_id', 'group_id', 'quantity', 'remaining_quantity', 'sold_quantity', 'price', 'publisher', 'author', 'description', 'image')
                ->with('category:id,category_name', 'group:id,group_name')
                ->findOrFail($id);

            // ✅ เพิ่มเพื่อส่งข้อมูล categories และ groups ไปยัง Frontend
            $categories = Category::select('id', 'category_name')->get();
            $groups = Group::select('id', 'group_name')->get();
        } elseif ($table == 3) {
            $record = Customer::select('id', 'name', 'lastname', 'username', 'email', 'phone', 'status', 'penalty', 'book_count')
                ->findOrFail($id);
        } elseif ($table == 4) {
            $record = Payment::select('id', 'payment_amount', 'status', 'payment_date', 'customer_id', 'book_id', 'rental_id')
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
                'status' => 'required|string',
            ]);
            Customer::where('id', $id)->update($validated);
        } elseif ($table == 4) {
            $validated = $request->validate([
                'payment_amount' => 'required|numeric|min:0',
                'status' => 'required|string',
                'payment_date' => 'nullable|date_format:Y-m-d',
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
                // ลบข้อมูลจากตาราง Rental
                $model = Rental::findOrFail($id);
            } elseif ($table == 2) {
                // ลบข้อมูลจากตาราง Group
                $model = Book::findOrFail($id);
            } elseif ($table == 3) {
                // ลบข้อมูลจากตาราง Admin
                $model = Customer::findOrFail($id);
            } elseif ($table == 4) {
                // ลบข้อมูลจากตาราง Group
                $model = Payment::findOrFail($id);
            } elseif ($table == 5) {
                // ลบข้อมูลจากตาราง Admin
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

}
