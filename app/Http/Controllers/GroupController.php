<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;
use Inertia\Inertia;
use Inertia\Response;

class GroupController extends Controller
{
    // ดึงข้อมูลกลุ่มทั้งหมด
    public function index()
    {
        $groups = Group::orderBy('id', 'asc')->get();

        return Inertia::render('Store/AddGroup', [
            'groups' => $groups
        ]);
    }

    // บันทึกข้อมูลใหม่
    public function store(Request $request)
    {
        $validated = $request->validate([
            'group_name' => 'required|string|max:255|unique:groups,group_name',
        ]);

        Group::create(['group_name' => $validated['group_name']]);

        return redirect()->route('admin.dashboard')->with('success', 'Group added successfully!');
    }

    // ลบกลุ่ม
    public function destroy($id)
    {
        $group = Group::findOrFail($id);
        $group->delete();

        return redirect()->back()->with('success', 'Group deleted successfully!');
    }
}
