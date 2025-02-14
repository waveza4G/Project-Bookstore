<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Admin;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
    
        $admin = Admin::where('email', $request->email)->first();
        $customer = Customer::where('email', $request->email)->first();
    
        // ถ้าเป็น Admin
        if ($admin && Hash::check($request->password, $admin->password)) {
            Auth::guard('admin')->login($admin);
            $token = $admin->createToken('Admin Access Token')->plainTextToken;
            return Inertia::render('Bookstore/Navbar', [
                'admin' => $admin,
                'token' => $token,
            ]);
        }
    
        // ถ้าเป็น Customer
        if ($customer && Hash::check($request->password, $customer->password)) {
            Auth::guard('customer')->login($customer);
            $token = $customer->createToken('Customer Access Token')->plainTextToken;
            return Inertia::render('Bookstore/Navbar', [
                'customer' => $customer,
                'token' => $token,
            ]);
        }
    
        // Log failed login attempt
        log::error('Login failed: ' . $request->email);
        log::error('Login failed: ' . $request->password);
    }
    
    

    public function addadmin(Request $request)
    {
        // กำหนดกฎการตรวจสอบข้อมูล
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins',
            'password' => 'required|string|min:8',
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
            'password' => Hash::make($request->password), // เข้ารหัสรหัสผ่าน'
        ]);

        // ส่งกลับข้อมูล Admin ที่ลงทะเบียนสำเร็จ
        return response()->json([
            'message' => 'Admin registered successfully.',
            'admin' => $admin,
            'token' => $admin->createToken('Admin Access Token')->plainTextToken,
        ], 201);
    }

    public function register(Request $request)
{
    // ตรวจสอบข้อมูลที่ได้รับจากฟอร์ม
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'lastname' => 'required|string|max:255',
        'username' => 'required|string|max:255|unique:customers',
        'phone' => 'required|string|unique:customers',
        'email' => 'required|string|email|max:255|unique:customers',
        'password' => 'required|string|min:8|confirmed',
    ]);

    if ($validator->fails()) {
        // บันทึกข้อผิดพลาดใน log
        Log::error('Validation Error: ' . $validator->errors());  

        // ส่งข้อผิดพลาดกลับไปยัง frontend (React)
        return redirect()->back()->withErrors($validator)->withInput();
    }

    // สร้างลูกค้าใหม่
    $customer = Customer::create([
        'name' => $request->name,
        'lastname' => $request->lastname,
        'username' => $request->username,
        'phone' => $request->phone,
        'email' => $request->email,
        'password' => Hash::make($request->password),
    ]);

    // สร้าง Token สำหรับ API
    $token = $customer->createToken('Customer Access Token')->plainTextToken;

    // Log ข้อมูล
    Log::info('Customer created successfully: ' . $customer->id);

    // ล็อกอินลูกค้า
    Auth::guard('customer')->login($customer); // เข้าสู่ระบบอัตโนมัติหลังจากลงทะเบียน

    // ส่งข้อมูลกลับไปยัง frontend (React) ผ่าน Inertia
    return Inertia::render('Bookstore/Navbar', [
        'customer' => $customer,  // ส่งข้อมูลลูกค้าไปยังหน้า Navbar
        'token' => $token, // ส่ง Token ไปใช้
    ]);
}



    
    public function logout(Request $request)
    {
        $request->user()->tokens->delete(); // ลบ token ทั้งหมดของผู้ใช้
        return response()->json(['message' => 'Logged out successfully.'], 200);
    }
}
