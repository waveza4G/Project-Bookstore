<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Category;
use App\Models\Group;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
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
    public function show(Book $book)
    {
        //
    }

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
