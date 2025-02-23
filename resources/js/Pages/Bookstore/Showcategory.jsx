import React from "react";
import { usePage, Link } from "@inertiajs/react";
import Sidebar from "./Sidebar"; // เรียกใช้ Sidebar

const Showcategory = () => {
  const { books = [], categoryName } = usePage().props; // รับข้อมูลจาก backend

  // กรองหนังสือตาม categoryName ที่ได้รับมา
  const filteredBooks = categoryName
    ? books.filter(book => book.category_name === categoryName) // กรองตาม categoryName ที่ส่งมา
    : books;  // ถ้าไม่มี categoryName ให้แสดงหนังสือทั้งหมด

  // ฟังก์ชัน limitText สำหรับจำกัดความยาวของข้อความ
  const limitText = (text, limit) => {
    if (!text) return "";
    return text.length > limit ? text.slice(0, limit) + "..." : text;
  };

  return (
    <div className="container p-6 flex">
      {/* Sidebar */}
      <Sidebar />

      {/* เนื้อหาหลัก */}
      <div className="w-full md:w-4/5 ml-4">
        {/* แสดงชื่อหมวดหมู่ */}
        <h1 className="text-3xl font-bold text-gray-800 mb-6">
          {categoryName || "หนังสือทั้งหมด"}
        </h1>

        {/* แสดงรายการหนังสือ */}
        <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
          {filteredBooks.length > 0 ? (
            filteredBooks.map((book) => (
              <div key={book.id} className="w-36">
                <Link href={route('books.show', book.id)} className="block">
                  <img
                    src={`/storage/${book.image}`}
                    alt={book.book_name}
                    className="w-full h-48 object-cover rounded-md"
                  />
                </Link>
                {/* รายละเอียดหนังสือ */}
                <div className="w-36 mt-2 text-left flex-grow">
                  {/* ชื่อหนังสือ */}
                  <h3 className="text-sm font-semibold text-gray-900 line-clamp-2 overflow-hidden">
                    {limitText(book.book_name, 30)} {/* จำกัดความยาวของชื่อหนังสือ */}
                  </h3>

                  {/* ผู้แต่ง */}
                  <p className="text-xs text-gray-500 truncate">{book.author || "ไม่ระบุผู้แต่ง"}</p>

                  {/* จำนวนคงเหลือ */}
                  <p className="text-xs text-gray-500 truncate">
                    จำนวนคงเหลือ: {book.remaining_quantity || "ไม่ระบุ"}
                  </p>
                </div>

                {/* ราคา */}
                <div className="mt-2 text-[#BA7D66] font-bold text-lg">
                  ฿{parseFloat(book.price).toFixed(2)}
                </div>
              </div>
            ))
          ) : (
            <p className="text-gray-500">ไม่มีหนังสือในหมวดนี้</p>
          )}
        </div>
      </div>
    </div>
  );
};

export default Showcategory;
