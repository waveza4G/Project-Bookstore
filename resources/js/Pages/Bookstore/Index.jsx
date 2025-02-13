import React from 'react';
import { Inertia } from '@inertiajs/inertia';

const Login = () => {
  const handleLogin = () => {
    // เมื่อกดปุ่ม จะทำการเปลี่ยนเส้นทางไปยังหน้า 'dashboard'
    Inertia.visit('login');
  };

  return (
    <div className="flex items-center justify-center min-h-screen bg-gray-100">
      <button
        onClick={handleLogin}
        className="px-6 py-3 text-lg font-semibold text-white bg-blue-600 rounded-lg shadow-md hover:bg-blue-700 transition duration-300"
      >
        Login
      </button>
    </div>
  );
};

export default Login;
