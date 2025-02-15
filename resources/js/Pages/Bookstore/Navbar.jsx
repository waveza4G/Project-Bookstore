import React from 'react';
import { Inertia } from '@inertiajs/inertia';

const Navbar = ({ customer}) => {

  // ฟังก์ชัน logout
  const logout = () => {
    Inertia.post(route('logout'), {}, {
      onFinish: () => {
        
        Inertia.get('/'); // ไปยังหน้าแรกหลังจาก logout

      }
    });
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
          {/* แสดงชื่อผู้ใช้ (customer.username) */}
          {customer && customer.username ? (
            <span className="text-white text-lg font-medium">
              {customer.username}

              {/* เพิ่มระยะห่างระหว่างชื่อผู้ใช้และปุ่ม Logout */}
              <button
                onClick={logout}
                className="ml-20 text-white text-lg font-medium hover:text-gray-200 transition duration-300"
              >
                Logout
              </button>
            </span>

          ) : (
            <>
              {/* ถ้าไม่มี customer.username จะแสดงปุ่ม Login และ Register */}
              <button
                onClick={() => Inertia.get('/login')}
                className="text-white text-lg font-medium hover:text-gray-200 transition duration-300"
              >
                Login
              </button>
              
              <button
                onClick={() => Inertia.get('/register')}
                className="text-white text-lg font-medium hover:text-gray-200 transition duration-300"
              >
                Register
              </button>
            </>
          )}
        </div>
      </div>
    </div>
  );
};

export default Navbar;
