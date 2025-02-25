import { useForm } from '@inertiajs/react';
import React from 'react';

export default function Login() {
    const { data, setData, post, processing, errors, reset } = useForm({
        email: '',
        password: '',
    });

    const submit = (e) => {
        e.preventDefault();

        if (!data.email || !data.password) {
            alert('กรุณากรอกอีเมลและรหัสผ่าน');
            return;
        }

        post(route('login'), data, {
            onFinish: () => {
                reset('password');
            },
            onError: (errors) => {
                if (errors.email || errors.password) {
                    alert('อีเมลหรือรหัสผ่านไม่ถูกต้อง');
                }
            }
        });
    };

    return (
        <div className="min-h-screen bg-[#FFFBF4] flex items-center justify-center">
            <div className="w-full max-w-md bg-white p-6 rounded-lg shadow-lg">
               {/* เพิ่มรูปโลโก้ */}
               <div className="flex justify-center mb-6">
                    <img
                        src="storage/image/Logo-Photoroom.png"  // ใช้ URL ตรงจาก public/storage
                        alt="Logo"
                        className="w-32 h-auto"
                    />
                </div>
                <h2 className="text-2xl font-bold text-center text-gray-700 mb-6">Log In</h2>
                <form onSubmit={submit}>
                    {/* ฟอร์มกรอกข้อมูล */}
                    <div className="mb-4">
                        <label htmlFor="email" className="block text-gray-700">Email</label>
                        <input
                            id="email"
                            type="email"
                            name="email"
                            value={data.email}
                            onChange={(e) => setData('email', e.target.value)}
                            className="mt-1 block w-full px-4 py-2 border rounded-md focus:ring-2 focus:ring-blue-600 focus:outline-none"
                            autoComplete="username"
                        />
                        {errors.email && <p className="text-red-500 text-sm mt-2">{errors.email}</p>}
                    </div>

                    <div className="mb-4">
                        <label htmlFor="password" className="block text-gray-700">Password</label>
                        <input
                            id="password"
                            type="password"
                            name="password"
                            value={data.password}
                            onChange={(e) => setData('password', e.target.value)}
                            className="mt-1 block w-full px-4 py-2 border rounded-md focus:ring-2 focus:ring-blue-600 focus:outline-none"
                            autoComplete="current-password"
                        />
                        {errors.password && <p className="text-red-500 text-sm mt-2">{errors.password}</p>}
                    </div>

                    <button
                        type="submit"
                        className="w-full bg-[#BA7D66] text-white py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600 hover:bg-[#9a5d4c] disabled:bg-gray-300"
                        disabled={processing}
                    >
                        {processing ? 'Logging in...' : 'Log In'}
                    </button>
                </form>

                {/* ลิงก์ไปที่หน้า Register */}
                <div className="mt-4 text-center">
                    <p className="text-gray-600">ยังไม่มีบัญชี? <a href={route('register')} className="text-blue-600 hover:underline">สมัครสมาชิก</a></p>
                </div>
            </div>
        </div>
    );
}
