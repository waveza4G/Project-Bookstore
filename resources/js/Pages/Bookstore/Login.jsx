import { useForm } from '@inertiajs/react';  
import React from 'react';

export default function Login() {
    const { data, setData, post, processing, errors, reset } = useForm({
        email: '',
        password: '',
    });

    const submit = (e) => {
        e.preventDefault();
        // Use Inertia.post with the correct route for login
        post(route('login'), data, {
            onFinish: () => {
                reset('password');
                Inertia.visit(route('Bookstore/Navbar')); // เปลี่ยนหน้าไปที่ navbar
            }
        });
    };

    return (
        <div className="min-h-screen bg-gray-100 flex items-center justify-center">
            <div className="w-full max-w-md bg-white p-6 rounded-lg shadow-lg">
                <h2 className="text-2xl font-bold text-center text-gray-700 mb-6">Log In</h2>
                <form onSubmit={submit}>
                    {/* Email */}
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

                    {/* Password */}
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

                    {/* Remember me */}
                    <div className="mb-4 flex items-center">
                        <input
                            id="remember"
                            type="checkbox"
                            name="remember"
                            checked={data.remember}
                            onChange={(e) => setData('remember', e.target.checked)}
                            className="form-checkbox h-5 w-5 text-blue-600"
                        />
                        <label htmlFor="remember" className="ml-2 text-gray-600">Remember me</label>
                    </div>

                    {/* Login Button */}
                    <div className="mt-6">
                        <button
                            type="submit"
                            className="w-full bg-blue-600 text-white py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600 hover:bg-blue-700 disabled:bg-gray-300"
                            disabled={processing}
                        >
                            {processing ? 'Logging in...' : 'Log In'}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    );
}
