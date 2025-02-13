<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    // ฟังก์ชันสำหรับการลงทะเบียน Admin ใหม่
    public function register(Request $request)
    {
        // กำหนดกฎการตรวจสอบข้อมูล
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // ถ้าข้อมูลไม่ถูกต้องให้ส่งกลับข้อผิดพลาด
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        // สร้าง Admin ใหม่
        $admin = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // เข้ารหัสรหัสผ่าน
            'role' => 'admin', // กำหนด role เป็น 'admin'
        ]);

        // ส่งกลับข้อมูล Admin ที่ลงทะเบียนสำเร็จ
        return response()->json([
            'message' => 'Admin registered successfully.',
            'admin' => $admin,
            'token' => $admin->createToken('Admin Access Token')->plainTextToken,
        ], 201);
    }
}

