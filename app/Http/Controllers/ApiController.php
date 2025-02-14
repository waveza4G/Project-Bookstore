<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        try {
            $admin = Admin::where('email', $request->email)->first();
            $customer = Customer::where('email', $request->email)->first();

            // ถ้าเป็น Admin
            if ($admin && Hash::check($request->password, $admin->password)) {
                Auth::guard('admin')->login($admin);
                $token = $admin->createToken('Admin Access Token')->plainTextToken; // สร้าง API token
                return response()->json([
                    'message' => 'Admin logged in successfully.',
                    'user' => $admin,
                    'token' => $token,
                ], 200);
            }

            // ถ้าเป็น Customer
            if ($customer && Hash::check($request->password, $customer->password)) {
                Auth::guard('web')->login($customer);
                $token = $customer->createToken('Customer Access Token')->plainTextToken; // สร้าง API token
                return response()->json([
                    'message' => 'Customer logged in successfully.',
                    'user' => $customer,
                    'token' => $token,
                ], 200);
            }

            return response()->json([
                'message' => 'The provided credentials do not match our records.',
            ], 401);

        } catch (\Exception $e) {
            Log::error('Login Error: ' . $e->getMessage());  // ใช้ Log เพื่อบันทึกข้อผิดพลาด
            return response()->json([
                'message' => 'An error occurred while trying to login.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
        public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_name' => 'required|string|max:255',
            'customer_code' => 'required|string|max:255|unique:customers',
            'age' => 'required|integer|min:1',  // อายุจะต้องเป็นจำนวนเต็มมากกว่าหรือเท่ากับ 1
            'address' => 'required|string',
            'phone' => 'required|string|unique:customers',  // ห้ามเบอร์โทรศัพท์ซ้ำ
            'email' => 'required|string|email|max:255|unique:customers',  // ห้ามอีเมลซ้ำ
            'password' => 'required|string|min:8|confirmed',
        ]);

        // ถ้าข้อมูลไม่ถูกต้องให้ส่งกลับข้อผิดพลาด
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        // สร้าง Admin ใหม่
        $customer = Customer::create([
        'customer_name' => $request->customer_name,
        'customer_code' => $request->customer_code,
        'age' => $request->age,
        'address' => $request->address,
        'phone' => $request->phone,
        'email' => $request->email,
        'password' => Hash::make($request->password),  // เข้ารหัสรหัสผ่าน
        ]);

        // ส่งกลับข้อมูล Admin ที่ลงทะเบียนสำเร็จ
        return response()->json([
            'message' => 'Customer registered successfully.',
            'customer' => $customer,
            'token' => $customer->createToken('Customer Access Token')->plainTextToken,
        ], 201);
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
    

    
    public function logout(Request $request)
    {
        $request->user()->tokens->delete(); // ลบ token ทั้งหมดของผู้ใช้
        return response()->json(['message' => 'Logged out successfully.'], 200);
    }


}
