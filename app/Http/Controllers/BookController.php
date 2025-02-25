<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Category;
use App\Models\Group;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Auth;  
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
class BookController extends Controller
{
    public function Showcategory(Request $request)
    {
        // รับค่าพารามิเตอร์ categoryName หรือ group_name ที่ส่งมาจาก query string
        $categoryName = $request->input('categoryName');
        $groupName = $request->input('group_name');

        // กรองหนังสือตาม categoryName หรือ groupName
        if ($categoryName) {
            // ถ้ามี categoryName ให้กรองหนังสือตามหมวดหมู่
            $books = DB::table('books')
                ->join('categories', 'books.category_id', '=', 'categories.id')
                ->select(
                    'books.id', 'books.book_name', 'books.image', 'books.price', 'books.author',
                    'books.description', 'books.publisher', 'books.remaining_quantity',
                    'categories.category_name'
                )
                ->where('categories.category_name', $categoryName) // กรองตาม categoryName
                ->get();
        } elseif ($groupName) {
            // ถ้ามี groupName ให้กรองหนังสือตามกลุ่ม
            $books = DB::table('books')
                ->join('categories', 'books.category_id', '=', 'categories.id')
                ->join('groups', 'books.group_id', '=', 'groups.id') // เชื่อมกับตาราง groups
                ->select(
                    'books.id', 'books.book_name', 'books.image', 'books.price', 'books.author',
                    'books.description', 'books.publisher', 'books.remaining_quantity',
                    'categories.category_name', 'groups.group_name'
                )
                ->where('groups.group_name', $groupName) // กรองตาม groupName
                ->get();
        } else {
            // ถ้าไม่มีทั้ง categoryName และ groupName, ให้แสดงหนังสือทั้งหมด
            $books = DB::table('books')
                ->join('categories', 'books.category_id', '=', 'categories.id')
                ->select(
                    'books.id', 'books.book_name', 'books.image', 'books.price', 'books.author',
                    'books.description', 'books.publisher', 'books.remaining_quantity',
                    'categories.category_name'
                )
                ->get();
        }

        // ส่งข้อมูลไปยัง view หรือ inertia
        return inertia('Bookstore/Showcategory', [
            'books' => $books,
            'categoryName' => $categoryName,
            'groupName' => $groupName
        ]);
    }


    public function show(Book $book)
    {
        // ตรวจสอบว่า customer ได้เข้าสู่ระบบหรือไม่
        if (!Auth::guard('customer')->check()) {
            // ถ้ายังไม่ล็อกอินให้ไปที่หน้า login
            return redirect()->route('login'); // หรือใช้ Inertia::visit('/login')
        }
        Auth::guard('admin')->check();

        // ส่งข้อมูล book ไปยังหน้า Detail
        return inertia('Bookstore/Detail', [
            'book' => $book
        ]);
    }






    public function create()
    {
        return Inertia::render('Store/Createbook', [
            'categories' => Category::orderBy('category_name')->get(),
            'groups' => Group::orderBy('group_name')->get(),
        ]);
    }

    // บันทึกข้อมูลหนังสือ
// บันทึกข้อมูลหนังสือ
    public function store(Request $request)
    {
        \Log::info('Received data:', $request->all());

        // ตรวจสอบข้อมูลที่กรอก
        $validated = $request->validate([
            'book_name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'group_id' => 'required|exists:groups,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'publisher' => 'nullable|string|max:255',
            'author' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // ตั้งค่า remaining_quantity ให้เท่ากับ quantity
        $validated['remaining_quantity'] = $validated['quantity'];

        // อัปโหลดรูปภาพ
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('book_images', 'public');
        }

        // บันทึกข้อมูลลงฐานข้อมูล
        $book = Book::create($validated);

        \Log::info('📚 Book created successfully:', $book->toArray());

        // ✅ เปลี่ยน Redirect ไปที่หน้า `/admin/dashboard`
        return redirect()->route('admin.dashboard')->with('success', 'Book added successfully!');
    }



    /**
     * Display the specified resource.
     */

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $book)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Book $book)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        //
    }
}
