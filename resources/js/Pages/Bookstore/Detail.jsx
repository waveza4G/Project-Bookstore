import React from "react";
import { usePage } from "@inertiajs/react";
import Navbar from "./Navbar"; // เรียกใช้ Navbar

const Detail = () => {
  const { book } = usePage().props;

  return (
    <div className="bg-gray-100 min-h-screen">
      {/* Navbar */}
      <Navbar />

      <div className="container mx-auto p-6">
        {/* กล่องหลัก */}
        <div className="flex flex-col md:flex-row bg-white shadow-2xl rounded-lg p-6">
          {/* รูปภาพหนังสือ */}
          <div className="w-full md:w-1/4 flex justify-center items-center">
            <img
              src={`/storage/${book.image}`}
              alt={book.book_name}
              className="w-150 h-auto object-cover rounded-lg shadow-lg"
            />
          </div>

          {/* รายละเอียดหนังสือ */}
          <div className="w-full md:w-3/4 md:pl-8 mt-6 md:mt-0">
            <h2 className="text-2xl font-bold text-gray-800">{book.book_name}</h2>
            <p className="text-md text-gray-700 mt-1">{book.author || "ไม่ระบุผู้แต่ง"}</p>
            <p className="text-gray-500 text-sm mt-3">{book.description || "ไม่มีคำอธิบาย"}</p>

            {/* เพิ่มข้อมูลสำนักพิมพ์ และจำนวนคงเหลือ */}
            <p className="text-md text-gray-700 mt-3">{book.publisher || "ไม่ระบุสำนักพิมพ์"}</p>
            <p className="text-md text-gray-700 mt-3">จำนวนคงเหลือ: {book.remaining_quantity || "ไม่ระบุ"}</p>

            {/* เส้นแบ่ง */}
            <div className="border-t border-gray-300 mt-4 pt-4">
              {/* ราคา */}
              <span className="text-2xl font-bold text-[#BA7D66]">
                ฿{parseFloat(book.price).toFixed(2)}
              </span>
            </div>

            {/* ปุ่มเช่า */}
            <div className="mt-6 flex flex-col items-start space-y-4">
              <button
                className="bg-[#BA7D66] hover:bg-[#9a5d4c] hover:bg-opacity-80 text-white font-semibold py-3 px-8 rounded-full shadow-md transition duration-300 transform hover:scale-105"
              >
                เช่า
              </button>
            </div>
          </div>
        </div>

        {/* ปุ่มกลับ */}
        <div className="flex justify-center mt-8">
          <button
            onClick={() => window.history.back()}
            className="bg-[#BA7D66] hover:bg-[#9a5d4c] hover:bg-opacity-80 text-white font-semibold py-3 px-8 rounded-full shadow-md transition duration-300 transform hover:scale-105"
          >
            กลับ
          </button>
        </div>
      </div>
    </div>
  );
};

export default Detail;
