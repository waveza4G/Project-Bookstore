<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
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
                    'role' => 'admin',
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
                    'role' => 'customer',
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

    public function logout(Request $request)
    {
        $request->user()->tokens->delete(); // ลบ token ทั้งหมดของผู้ใช้
        return response()->json(['message' => 'Logged out successfully.'], 200);
    }
}
