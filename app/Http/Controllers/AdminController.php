<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Course;
use App\Models\Student;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class AdminController extends Controller
{
   // ฟังก์ชันนี้ใช้เพื่อแสดงรายการข้อมูลในตารางต่างๆ (Register, Course, Student, Teacher)
   public function index(Request $request)
   {
       $selectedTable = $request->input('selectedTable', 1); // กำหนดค่าเริ่มต้นให้เลือกตาราง Register (ค่าพื้นฐานเป็น 1)

       // ตรวจสอบว่าเลือกตารางไหนและดึงข้อมูลจากตารางนั้นมาแสดง
       if ($selectedTable == 1) {
           $table = re::paginate(10); // แสดงรายการ Register พร้อมข้อมูล Student, Course, และ Teacher
       } else if ($selectedTable == 2) {
           $table = Course::paginate(10); // แสดงรายการ Course
       } else if ($selectedTable == 3) {
           $table = Student::paginate(10); // แสดงรายการ Student
       } else if ($selectedTable == 4) {
           $table = Teacher::paginate(10); // แสดงรายการ Teacher
       }

       // ส่งข้อมูลไปยัง View (ในที่นี้คือ Inertia.js)
       return Inertia::render('Register/Index', [
           'table' => $table,        // ข้อมูลที่ต้องการแสดง
           'tableNo' => $selectedTable, // เลขของตารางที่เลือก
       ]);
   }

   // ฟังก์ชันสำหรับแสดงฟอร์มสร้างข้อมูลใหม่ (ในที่นี้ไม่ได้ใช้)
   public function create()
   {
       //
   }

   // ฟังก์ชันนี้จะถูกเรียกเมื่อมีการสร้างข้อมูลใหม่ (การลงทะเบียน Student)
   public function store(Request $request)
   {
       // กำหนด validation สำหรับข้อมูลที่ส่งเข้ามา
       $request->validate([
           'name' => 'required|string|max:255',        // ชื่อผู้เรียนต้องไม่เกิน 255 ตัวอักษร
           'email' => 'required|email|unique:students,email',  // อีเมลต้องไม่ซ้ำในฐานข้อมูล
           'phone' => 'required|string|max:15',        // หมายเลขโทรศัพท์ต้องไม่เกิน 15 ตัวอักษร
       ]);

       try {
           // สร้าง Student ใหม่โดยใช้ข้อมูลจากฟอร์ม
           $student = Student::create($request->only(['name', 'email', 'phone']));

           // เลือก Course และ Teacher แบบสุ่ม
           $course = Course::inRandomOrder()->first();
           $teacher = Teacher::inRandomOrder()->first();
           
           // สร้างการลงทะเบียนใหม่ในตาราง Register โดยเชื่อมโยง Student, Course, Teacher
           Register::create([
               'student_id' => $student->id,  // เชื่อมโยงกับ Student ที่สร้างใหม่
               'course_id' => $course->id,    // เชื่อมโยงกับ Course ที่เลือก
               'teacher_id' => $teacher->id,  // เชื่อมโยงกับ Teacher ที่เลือก
           ]);
           
           // หลังจากสร้างสำเร็จ ให้รีไดเรกต์ไปที่หน้าหลักและแสดงข้อความสำเร็จ
           return redirect()->route('register.index')->with('success', 'Student and registration created successfully');
       } catch (\Exception $e) {
           // ถ้ามีข้อผิดพลาดเกิดขึ้น ให้บันทึกข้อผิดพลาดใน log และรีไดเรกต์ไปที่หน้าหลัก
           Log::error($e->getMessage());
           return redirect()->route('register.index');
       }
   }

   
   public function show(Register $register)
   {
       //
   }

   // ฟังก์ชันนี้ใช้สำหรับแสดงฟอร์มแก้ไขข้อมูลของ Student
   public function edit($student_id)
   {
       $student = Student::find($student_id);  // ดึงข้อมูล Student ตาม ID ที่ได้รับ
       return Inertia::render('Register/Edit', [
           'student' => $student, // ส่งข้อมูล Student ไปยังหน้า Edit
       ]);
   }

   // ฟังก์ชันนี้จะถูกเรียกเมื่อมีการอัพเดตข้อมูลของ Student
   public function update(Request $request, $student_id)
   {
       // กำหนด validation สำหรับข้อมูลที่ส่งเข้ามา
       $request->validate([
           'name' => 'required|string|max:255',      // ชื่อผู้เรียนต้องไม่เกิน 255 ตัวอักษร
           'email' => 'required|email|unique:students,email,' . $student_id,  // อีเมลต้องไม่ซ้ำในฐานข้อมูล
           'phone' => 'required|string|max:15',      // หมายเลขโทรศัพท์ต้องไม่เกิน 15 ตัวอักษร
       ]);

       try {
           // ดึงข้อมูล Student ที่ต้องการอัพเดต
           $student = Student::find($student_id);
           $student->update($request->only(['name', 'email', 'phone'])); // อัพเดตข้อมูล Student ที่ดึงมา

           // หลังจากอัพเดตสำเร็จ ให้รีไดเรกต์ไปที่หน้าหลักและแสดงข้อความสำเร็จ
           return redirect()->route('register.index')->with('success', 'Student updated successfully');
       } catch (\Exception $e) {
           // ถ้ามีข้อผิดพลาดเกิดขึ้น ให้บันทึกข้อผิดพลาดใน log และรีไดเรกต์ไปที่หน้าหลัก
           Log::error($e->getMessage());
           return redirect()->route('register.index');
       }
   }

   // ฟังก์ชันนี้ใช้สำหรับลบข้อมูล Student ออกจากฐานข้อมูล
   public function destroy($student_id)
   {
       try {
           // ดึงข้อมูล Student ที่ต้องการลบ
           $student = Student::find($student_id);
           $student->delete();  // ลบ Student ออกจากฐานข้อมูล

           // หลังจากลบสำเร็จ ให้รีไดเรกต์ไปที่หน้าหลักและแสดงข้อความสำเร็จ
           return redirect()->route('register.index')->with('success', 'Student deleted successfully');
       } catch (\Exception $e) {
           // ถ้ามีข้อผิดพลาดเกิดขึ้น ให้บันทึกข้อผิดพลาดใน log และรีไดเรกต์ไปที่หน้าหลักพร้อมข้อความผิดพลาด
           Log::error($e->getMessage());
           return redirect()->route('register.index')->with('error', 'Failed to delete student');
       }
   }
}

