<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Admin;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
{
    // ตรวจสอบข้อมูลที่ส่งมา
    Log::info('Login attempt with credentials:', $request->only('email', 'password'));

    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $credentials = $request->only('email', 'password');

    // ลองล็อกอินใน Guard: customer
    if (Auth::guard('customer')->attempt($credentials)) {
        $request->session()->regenerate();

        $customer = Auth::guard('customer')->user();
        $token = $customer->createToken('Customer Access Token')->plainTextToken;

        // เก็บ email และ token ใน session แทน user_id
        $request->session()->put('email', $customer->email);

        return redirect('/dashboard')->with('success', 'Welcome, Customer!');
    }

    // ถ้าเป็นการเข้าสู่ระบบแอดมิน
    if (Auth::guard('admin')->attempt($credentials)) {
        $request->session()->regenerate();

        $admin = Auth::guard('admin')->user();
        $token = $admin->createToken('Admin Access Token')->plainTextToken;

        // เก็บ email และ token ใน session แทน user_id
        $request->session()->put('email', $admin->email);

        return redirect('/admin/dashboard')->with('success', 'Welcome, Admin!');
    }
    else {
        // การล็อกอินล้มเหลว
        Log::warning('Login failed: Invalid credentials', $credentials);
        return back()->withErrors(['error' => 'Invalid credentials'])->withInput();
    }
}


    public function AdminRegister(Request $request)
    {
        // ตรวจสอบข้อมูลที่ได้รับจากฟอร์ม
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255|unique:admins|unique:customers',
            'email' => 'required|string|email|max:255|unique:admins|unique:customers',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            Log::error('Admin registration validation failed', $validator->errors()->toArray());
            return back()->withErrors($validator)->withInput();
        }

        // สร้างแอดมินใหม่
        $admin = Admin::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // สร้าง token และเก็บ email และ token ใน session แทน user_id
        $token = $admin->createToken('Admin Access Token')->plainTextToken;
        $request->session()->put('email', $admin->email);

        // ล็อกอินแอดมิน
        Auth::guard('admin')->login($admin);
        return redirect('/admin/dashboard')->with('success', 'Admin registered successfully');
    }

    public function register(Request $request)
    {
        // ตรวจสอบข้อมูลที่ได้รับจากฟอร์ม
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:customers|unique:admins',
            'phone' => 'required|string|max:15|unique:customers',
            'email' => 'required|string|email|max:255|unique:customers|unique:admins',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            Log::error('Registration validation failed', $validator->errors()->toArray());
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

        // สร้าง token และเก็บ email และ token ใน session แทน user_id
        $token = $customer->createToken('Customer Access Token')->plainTextToken;
        $request->session()->put('email', $customer->email);

        // ล็อกอินลูกค้า
        Auth::guard('customer')->login($customer);
        return redirect('/dashboard')->with('success', 'Registration successful');
    }

    public function logout(Request $request)
    {
        // ตรวจสอบว่าเป็นลูกค้าหรือแอดมิน
        if (Auth::guard('customer')->check()) {
            $user = Auth::guard('customer')->user();
            Auth::guard('customer')->logout();
            Log::info('Customer logged out: ' . $user->email);
        }

        if (Auth::guard('admin')->check()) {
            $user = Auth::guard('admin')->user();
            Auth::guard('admin')->logout();
            Log::info('Admin logged out: ' . $user->email);
        }

        // ลบข้อมูลใน session
        $request->session()->forget('email');  // ลบแค่ email ที่ใช้กับ session
        $request->session()->invalidate();

        return redirect('/dashboard')->with('success', 'Logged out successfully');
    }

}
