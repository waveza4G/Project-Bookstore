<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Inertia\Inertia;
use Inertia\Response;

class CategoryController extends Controller
{
    // ดึงข้อมูลหมวดหมู่ทั้งหมด
    public function index()
    {
        $categories = Category::orderBy('id', 'asc')->get();

        return Inertia::render('Store/AddCategory', [
            'categories' => $categories
        ]);
    }

    // บันทึกข้อมูลใหม่
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_name' => 'required|string|max:255|unique:categories,category_name',
        ]);

        Category::create(['category_name' => $validated['category_name']]);

        return redirect()->route('admin.dashboard')->with('success', 'Category added successfully!');
    }

    // ลบหมวดหมู่
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->back()->with('success', 'Category deleted successfully!');
    }
}
