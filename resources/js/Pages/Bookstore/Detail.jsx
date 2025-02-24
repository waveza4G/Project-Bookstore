import React, { useState, useEffect } from "react";
import { usePage, Link } from "@inertiajs/react";
import { Inertia } from "@inertiajs/inertia";
import Navbar from "./Navbar";

const Detail = () => {
  const { book, auth, rentals, rentalAmount } = usePage().props; // ดึงข้อมูล rentals และ rentalAmount จาก share()

  // อัตราค่าเช่าตามจำนวนวันที่เช่า (1-7 วัน)
  const rentalRates = {
    1: 0.04, 2: 0.08, 3: 0.12, 4: 0.16,
    5: 0.20, 6: 0.24, 7: 0.30
  };

  // ฟังก์ชันคำนวณราคา
  const calculatePrice = (days) => {
    const rate = rentalRates[days] || rentalRates[7]; // ใช้อัตราของ 7 วันถ้าไม่ตรงกับ 1-6 วัน
    return Math.ceil(book.price * rate); // คำนวณราคาและปัดเศษขึ้น
  };

  // เริ่มต้นค่า rentalPrice และ data
  const [rentalPrice, setRentalPrice] = useState(() => calculatePrice(1)); // เริ่มต้นที่ 1 วัน
  const [data, setData] = useState({
    rental_days: 1, // กำหนดเริ่มต้นเป็น 1 วัน
  });

  // ฟังก์ชัน handleChange สำหรับการเปลี่ยนแปลงจำนวนวันเช่า
  const handleDaysChange = (e) => {
    const days = parseInt(e.target.value, 10);
    setData({ ...data, rental_days: days });
    setRentalPrice(calculatePrice(days)); // คำนวณราคาหลังจากการเลือกจำนวนวัน
  };

  // ฟังก์ชันคลิกเช่าหนังสือ
  const handleRentalClick = () => {
    if (!auth || !auth.customer) {
      console.error("User is not authenticated or customer data is missing");
      return;
    }

    const customerId = auth.customer.id; // กำหนด customerId
    const bookId = book.id; // กำหนด bookId
    const rentalDays = data.rental_days; // กำหนด rentalDays
    const rentalAmount = rentalPrice; // กำหนดราคาค่าเช่า

    // ใช้ Inertia.post แทน Inertia.visit
    Inertia.post('/rental', {
      amount: rentalAmount,
      bookId: bookId,
      rental_days: rentalDays
    })
    .then(() => {
      // หากการเช่าสำเร็จ
      console.log("การเช่าเสร็จสมบูรณ์");
    })
    .catch((error) => {
      console.error("Error during rental: ", error);
    });
  };

  const existingRental = rentals.find(rental => rental.book_id === book.id && rental.customer_id === auth.customer.id);

  return (
    <div className="bg-gray-100 min-h-screen">
      <Navbar />

      <div className="container mx-auto p-6">
        <div className="flex flex-col md:flex-row p-6">
          <div className="w-full md:w-1/3 flex justify-center items-center mb-6 md:mb-0">
            <img
              src={`/storage/${book.image}`}
              alt={book.book_name}
              className="w-full h-auto max-w-[400px] object-cover rounded-lg shadow-lg"
            />
          </div>

          <div className="w-full md:w-2/3 md:pl-8 mt-6 md:mt-0">
            <h2 className="text-3xl font-bold text-gray-800">{book.book_name}</h2>
            <p className="text-lg text-gray-700 mt-1">{book.author || "ไม่ระบุผู้แต่ง"}</p>
            <p className="text-gray-500 text-xl mt-3">{book.description || "ไม่มีคำอธิบาย"}</p>

            <p className="text-lg text-gray-700 mt-3">{book.publisher || "ไม่ระบุสำนักพิมพ์"}</p>
            <p className="text-lg text-gray-700 mt-3">จำนวนคงเหลือ: {book.remaining_quantity || "ไม่ระบุ"}</p>

            <div className="mt-4 pt-4">
              <span className="text-3xl font-bold text-[#BA7D66]">
                ฿{parseFloat(book.price).toFixed(2)}
              </span>
            </div>

            {/* เช็คว่าเช่าหนังสือแล้วหรือยัง */}
            {(auth.customer || auth.admin) ? (
              existingRental ? (
                <div>
                  <p className="mt-2">
                    <strong>ราคาค่าเช่า:</strong> ฿{existingRental.amount} {/* แสดงจำนวนเงินที่ต้องชำระ */}
                  </p>
                  <p className="text-red-500 font-bold mt-4">📌 คุณเช่าหนังสือเล่มนี้แล้ว</p>

                  {existingRental.status === "-" && (
                    <div>
                      <p className="text-yellow-500">⚠️ การเช่ายังรอการชำระเงิน</p>
                    </div>
                  )}

                  {existingRental.status === "borrowed" && (
                    <p className="text-green-500">✅ การเช่าได้รับการชำระเงินแล้ว</p>
                  )}
                </div>
              ) : (
                // ถ้ายังไม่ได้เช่า
                <div className="mt-4">
                  <label>เลือกจำนวนวันที่ต้องการเช่า (1-7 วัน):</label>
                  <input
                    type="number"
                    name="rental_days"
                    min="1"
                    max="7"
                    value={data.rental_days}
                    onChange={handleDaysChange}
                    required
                    className="border p-2 ml-2"
                  />
                  <p className="mt-2">
                    <strong>ราคาค่าเช่า:</strong> ฿{rentalPrice} {/* แสดงราคาค่าเช่าที่คำนวณ */}
                  </p>
                </div>
              )
            ) : (
              <p className="text-red-500 mt-4">⚠️ คุณต้องเข้าสู่ระบบก่อนทำการเช่าหนังสือ</p>
            )}

            {/* ถ้าไม่มีการเช่าแล้ว ให้แสดงปุ่มเช่าหนังสือ */}
            <div className="mt-6 flex justify-between">
              <button
                onClick={() => window.history.back()}
                className="bg-[#BA7D66] hover:bg-[#9a5d4c] hover:bg-opacity-80 text-white font-semibold py-4 px-12 w-1/4 rounded-full text-2xl"
              >
                กลับ
              </button>

              {(auth.customer || auth.admin) && !existingRental && (
                <button
                  onClick={handleRentalClick}
                  className="bg-[#BA7D66] hover:bg-[#9a5d4c] hover:bg-opacity-80 text-white font-semibold py-4 px-12 w-1/4 rounded-full text-2xl"
                >
                  เช่าหนังสือ
                </button>
              )}
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default Detail;
    