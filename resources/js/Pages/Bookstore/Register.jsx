    import { useForm } from '@inertiajs/react';  
    import React from 'react';


    export default function Register() {

        const { data, setData, post, processing, errors, reset } = useForm({
            name: '',   // ชื่อ
            lastname: '',   // นามสกุล
            username: '',   // ชื่อผู้ใช้
            phone: '',   // เบอร์โทรศัพท์
            email: '',
            password: '',
            password_confirmation: '',
        });

        // ฟังก์ชันการส่งฟอร์ม
        const submit = (e) => {
            e.preventDefault();

            // // ตรวจสอบว่าฟิลด์ทั้งหมดกรอกข้อมูลครบหรือไม่
            // if (!data.name || !data.lastname || !data.username || !data.phone || !data.email || !data.password || !data.password_confirmation) {
            //     // ถ้ามีช่องไหนว่างให้แสดงข้อผิดพลาดและหยุดการส่งข้อมูล
            //     alert('กรุณากรอกข้อมูลให้ครบทุกช่อง');
            //     return; // หยุดการส่งฟอร์ม
            // }
            // // ตรวจสอบความถูกต้องของรหัสผ่านและยืนยันรหัสผ่าน
            // else if (data.password !== data.password_confirmation) {
            //     alert('รหัสผ่านและยืนยันรหัสผ่านไม่ตรงกัน');
            //     return; // หยุดการส่งฟอร์ม
            // }

            // else if (data.password.length < 8) {
            //     alert('รหัสผ่านต้องมีความยาวอย่างน้อย 8 ตัวอักษร');
            //     return; // หยุดการส่งฟอร์ม
            // }

            // else if (data.phone.integer) {
            //     alert('เบอร์โทรศัพท์ต้องเป็นตัวเลขเท่านั้น');
            //     return; // หยุดการส่งฟอร์ม
            // }

            // else if (data.phone.length < 10) {
            //     alert('เบอร์โทรศัพท์ต้องมีความยาวอย่างน้อย 10 ตัวอักษร');
            //     return; // หยุดการส่งฟอร์ม
            // }

            // ส่งข้อมูลไปยัง backend
            post(route('register'), data, {
                onFinish: () => {
                    reset('password', 'password_confirmation');
                    Inertia.visit(route('Bookstore/Navbar')); // เปลี่ยนหน้าไปที่ navbar
                }
            });
        };
        
        return (
            <div className="min-h-screen flex items-center justify-center bg-gray-100">
                <div className="w-full max-w-md p-6 bg-white rounded-lg shadow-md">
                    <h2 className="text-2xl font-bold text-center text-gray-700 mb-6">Register</h2>
                    {/* ฟอร์มการลงทะเบียน */}
                    <form onSubmit={submit}>
                        {/* ชื่อ */}
                        <div className="mb-4">
                            <label htmlFor="name" className="block text-gray-700">First Name</label>
                            <input
                                type="text"
                                name="name"
                                id="name"
                                className="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600"
                                value={data.name}
                                onChange={(e) => setData('name', e.target.value)}
                            />
                            {errors.name && <p className="text-red-500 text-sm">{errors.name}</p>}
                        </div>

                        {/* นามสกุล */}
                        <div className="mb-4">
                            <label htmlFor="lastname" className="block text-gray-700">Last Name</label>
                            <input
                                type="text"
                                name="lastname"
                                id="lastname"
                                className="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600"
                                value={data.lastname}
                                onChange={(e) => setData('lastname', e.target.value)}
                            />
                            {errors.lastname && <p className="text-red-500 text-sm">{errors.lastname}</p>}
                        </div>

                        {/* ชื่อผู้ใช้ */}
                        <div className="mb-4">
                            <label htmlFor="username" className="block text-gray-700">Username</label>
                            <input
                                type="text"
                                name="username"
                                id="username"
                                className="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600"
                                value={data.username}
                                onChange={(e) => setData('username', e.target.value)}
                            />
                            {errors.username && <p className="text-red-500 text-sm">{errors.username}</p>}
                        </div>

                        {/* เบอร์โทรศัพท์ */}
                        <div className="mb-4">
                            <label htmlFor="phone" className="block text-gray-700">Phone</label>
                            <input
                                type="text"
                                name="phone"
                                id="phone"
                                className="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600"
                                value={data.phone}
                                onChange={(e) => setData('phone', e.target.value)}
                            />
                            {errors.phone && <p className="text-red-500 text-sm">{errors.phone}</p>}
                        </div>

                        {/* อีเมล */}
                        <div className="mb-4">
                            <label htmlFor="email" className="block text-gray-700">Email</label>
                            <input
                                type="email"
                                name="email"
                                id="email"
                                className="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600"
                                value={data.email}
                                onChange={(e) => setData('email', e.target.value)}
                            />
                            {errors.email && <p className="text-red-500 text-sm">{errors.email}</p>}
                        </div>

                        {/* รหัสผ่าน */}
                        <div className="mb-4">
                            <label htmlFor="password" className="block text-gray-700">Password</label>
                            <input
                                type="password"
                                name="password"
                                id="password"
                                className="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600"
                                value={data.password}
                                onChange={(e) => setData('password', e.target.value)}
                            />
                            {errors.password && <p className="text-red-500 text-sm">{errors.password}</p>}
                        </div>

                        {/* ยืนยันรหัสผ่าน */}
                        <div className="mb-6">
                            <label htmlFor="password_confirmation" className="block text-gray-700">Confirm Password</label>
                            <input
                                type="password" 
                                name="password_confirmation"
                                id="password_confirmation"
                                className="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600"
                                value={data.password_confirmation}
                                onChange={(e) => setData('password_confirmation', e.target.value)}
                            />
                            {errors.password_confirmation && <p className="text-red-500 text-sm">{errors.password_confirmation}</p>}
                        </div>

                        {/* ปุ่มส่งฟอร์ม */}
                        <button
                            type="submit"
                            className="w-full px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-600"
                            disabled={processing}
                        >
                            {processing ? 'Processing...' : 'Register'}
                        </button>
                    </form>
                </div>
            </div>
        );
    }
