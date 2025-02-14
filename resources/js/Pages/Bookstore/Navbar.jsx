import React, { useEffect } from 'react';
import { Inertia } from '@inertiajs/inertia';

const Navbar = ({ customer, admin, token }) => {
  useEffect(() => {
    // เก็บ token ไว้ใน localStorage สำหรับการใช้งานต่อไป
    if (token) {
      localStorage.setItem('token', token); // เก็บใน localStorage
    }
  }, [token]);

  const handleLogout = () => {
    // ลบ token จาก localStorage เมื่อออกจากระบบ
    localStorage.removeItem('token');
    Inertia.get('/logout'); // ไปที่ route logout
  };

  return (
    <div className="bg-blue-600 shadow-md py-4">
      <div className="max-w-7xl mx-auto px-6 flex items-center justify-between">
        <div className="text-white text-2xl font-semibold">
          <a href="/" className="hover:text-gray-300 transition duration-300">
            MyApp
          </a>
        </div>

        <div className="space-x-6">
          {/* แสดงชื่อผู้ใช้ (customer หรือ admin) */}
          {customer ? (
            <span className="text-white text-lg font-medium">
              Welcome, {customer.username}!
            </span>
          ) : admin ? (
            <span className="text-white text-lg font-medium">
              Welcome, {admin.username}!
            </span>
          ) : (
            <>
              {/* ถ้าไม่มี customer หรือ admin จะแสดงปุ่ม Login และ Register */}
              <button
                onClick={() => Inertia.get('/login')} // ใช้ Inertia.get แทน Inertia.visit
                className="text-white text-lg font-medium hover:text-gray-200 transition duration-300"
              >
                Login
              </button>

              <button
                onClick={() => Inertia.get('/register')} // ใช้ Inertia.get แทน Inertia.visit
                className="text-white text-lg font-medium hover:text-gray-200 transition duration-300"
              >
                Register
              </button>
            </>
          )}

          {/* ปุ่ม Logout */}
          {token && (
            <button
              onClick={handleLogout}
              className="text-white text-lg font-medium hover:text-gray-200 transition duration-300"
            >
              Logout
            </button>
          )}
        </div>
      </div>
    </div>
  );
};

export default Navbar;
