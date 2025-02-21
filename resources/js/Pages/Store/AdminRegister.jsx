    import { useForm } from '@inertiajs/react';
import React from 'react';

export default function AdminRegister() {
    const { data, setData, post, processing, errors, reset } = useForm({
        username: '',   // ชื่อ
        email: '',  // อีเมล
        password: '', // รหัสผ่าน
        password_confirmation: '', // ยืนยันรหัสผ่าน
    });

    // ฟังก์ชันการส่งฟอร์ม
    const submit = (e) => {
        e.preventDefault();

        // ส่งข้อมูลไปยัง backend
        post(route('admin.register'), data, {
            onFinish: () => {
                reset('password', 'password_confirmation');
            }
        });
    };

    return (
        <div className="min-h-screen flex items-center justify-center bg-gray-100">
            <div className="w-full max-w-md p-6 bg-white rounded-lg shadow-md">
                <h2 className="text-2xl font-bold text-center text-gray-700 mb-6">Admin Register</h2>
                <form onSubmit={submit}>
                    {/* ชื่อ */}
                    <div className="mb-4">
                        <label htmlFor="name" className="block text-gray-700">Name</label>
                        <input
                            type="text"
                            name="username"
                            id="username"
                            className="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600"
                            value={data.username}
                            onChange={(e) => setData('username', e.target.value)}  // Change 'name' to 'username'
                        />

                        {errors.username && <p className="text-red-500 text-sm">{errors.username}</p>}
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
