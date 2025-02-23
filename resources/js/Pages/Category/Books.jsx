import React, { useRef } from "react";
import { usePage, Link } from "@inertiajs/react";
import { ChevronLeft, ChevronRight } from "lucide-react"; // ไอคอนลูกศร

const Books = () => {
  const { books = [] } = usePage().props;
  const scrollRef = useRef(null); // ใช้อ้างอิง div ที่เลื่อน

  const scroll = (direction) => {
    if (scrollRef.current) {
      const { current } = scrollRef;
      const scrollAmount = 200; // ปรับค่านี้เพื่อกำหนดระยะการเลื่อน
      current.scrollBy({ left: direction === "left" ? -scrollAmount : scrollAmount, behavior: "smooth" });
    }
  };

  const limitText = (text, limit) => {
    return text.length > limit ? text.slice(0, limit) + "..." : text;
  };

  return (
    <div className="container mx-auto p-4 relative">
      <h2 className="text-2xl font-bold mb-4">หนังสือแนะนำ</h2>

      {/* ปุ่มเลื่อนซ้าย */}
      <button
        className="absolute left-0 top-1/2 transform -translate-y-1/2 bg-white shadow-md p-2 rounded-full hidden sm:block z-10"
        onClick={() => scroll("left")}
      >
        <ChevronLeft size={24} className="text-[#BA7D66]" />
      </button>

      {/* Scroll แนวนอน */}
      <div className="relative overflow-hidden">
        <div ref={scrollRef} className="overflow-x-auto py-3 scrollbar-hide">
          <div className="flex space-x-4">
            {books.length > 0 ? (
              books.map((book) => (
                <div key={book.id} className="w-44 min-w-[180px] flex flex-col justify-between">
                  {/* รูปภาพหนังสือ */}
                  <Link href={route('books.show', book.id)} className="text-[#BA7D66] text-sm font-semibold mt-2">
                    <img
                      src={`/storage/${book.image}`}
                      alt={book.book_name}
                      className="w-36 h-48 object-cover rounded-lg shadow-md"
                    />
                  </Link>

                  {/* รายละเอียดหนังสือ */}
                  <div className="w-36 mt-2 text-left flex-grow">
                    <h3 className="text-sm font-bold text-gray-900 block">
                      {limitText(book.book_name, 30)} {/* จำกัดความยาวของชื่อหนังสือ */}
                    </h3>
                    <p className="text-xs text-gray-500 truncate">{book.author || "ไม่ระบุผู้แต่ง"}</p>
                    <p className="text-xs text-gray-500 truncate">จำนวนคงเหลือ: {book.remaining_quantity || "ไม่ระบุ"}</p>
                  </div>

                  <div className="text-[#BA7D66] font-bold text-lg ">
                    ฿{parseFloat(book.price).toFixed(2)}
                  </div>

                </div>
              ))
            ) : (
              <p className="text-gray-500">ไม่มีข้อมูลหนังสือ</p>
            )}
          </div>
        </div>
      </div>

      {/* ปุ่มดูทั้งหมด */}
      <div className="absolute top-0 right-0 mr-4 mt-2">
        <Link href={route('Showcategory.index')} className="inline-flex items-center text-[#BA7D66] hover:text-[#9e6e51] text-sm font-semibold">
          ดูทั้งหมด
          <ChevronRight size={16} className="ml-1" />
        </Link>
      </div>

      {/* ปุ่มเลื่อนขวา */}
      <button
        className="absolute right-0 top-1/2 transform -translate-y-1/2 bg-white shadow-md p-2 rounded-full hidden sm:block z-10"
        onClick={() => scroll("right")}
      >
        <ChevronRight size={24} className="text-[#BA7D66]" />
      </button>
    </div>
  );
};

export default Books;
