import React, { useState, useEffect } from "react";
import { usePage, Link } from "@inertiajs/react";
import { Inertia } from "@inertiajs/inertia";
import Navbar from "./Navbar";

const Detail = () => {
  const { book, auth, rentals } = usePage().props; // ดึงข้อมูล rentals จาก share()

  // คำนวณค่าเช่าตามจำนวนวันที่เช่า
  const rentalRates = {
    1: 0.04, 2: 0.08, 3: 0.12, 4: 0.16,
    5: 0.20, 6: 0.24, 7: 0.30
  };

  const calculatePrice = (days) => {
    const rate = rentalRates[days] || rentalRates[7];
    return Math.ceil(book.price * rate); // คำนวณราคาและปัดเศษขึ้น
  };

  const [rentalPrice, setRentalPrice] = useState(() => calculatePrice(1));
  const [data, setData] = useState({ rental_days: 1 });

  const handleDaysChange = (e) => {
    const days = parseInt(e.target.value, 10);
    setData({ ...data, rental_days: days });
    setRentalPrice(calculatePrice(days));
  };

  // ตรวจสอบการเช่าของลูกค้า
  const existingRental = rentals ? rentals.find(rental => rental.book_id === book.id && rental.customer_id === auth.customer.id) : null;

  const handleRentalClick = () => {
    if (!auth.customer) {
      // ถ้าผู้ใช้ไม่ได้เข้าสู่ระบบ ให้ไปที่หน้า login
      Inertia.visit('/login');
    } else {
      // ตรวจสอบสถานะการเช่า
      if (existingRental) {
        if (existingRental.status === "waiting") {
          // หากสถานะการเช่าเป็น "waiting"
          alert("การเช่ายังรอการชำระเงิน");
        } else if (existingRental.status === "borrowed") {
          // หากสถานะการเช่าเป็น "borrowed"
          alert("การเช่าได้รับการชำระเงินแล้ว");
        } else if (existingRental.status === "-") {
          // หากสถานะเป็น "-"
          const customerId = auth.customer.id;
          const bookId = book.id;
          const rentalDays = data.rental_days;
          const rentalAmount = rentalPrice;

          // สั่งให้ทำการเช่าหนังสือ
          Inertia.post('/rental', {
            amount: rentalAmount,
            bookId: bookId,
            rental_days: rentalDays
          })
          .then(() => {
            console.log("การเช่าเสร็จสมบูรณ์");
          })
          .catch((error) => {
            console.error("Error during rental: ", error);
          });
        }
      } else {
        // ถ้ายังไม่ได้เช่า
        const customerId = auth.customer.id;
        const bookId = book.id;
        const rentalDays = data.rental_days;
        const rentalAmount = rentalPrice;

        // สั่งให้ทำการเช่าหนังสือ
        Inertia.post('/rental', {
          amount: rentalAmount,
          bookId: bookId,
          rental_days: rentalDays
        })
        .then(() => {
          console.log("การเช่าเสร็จสมบูรณ์");
        })
        .catch((error) => {
          console.error("Error during rental: ", error);
        });
      }
    }
  };

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

            {/* เช็คสถานะของการเช่า */}
            {existingRental ? (
              <div>
                {existingRental.status === "waiting" && (
                  <div>
                    <p className="text-yellow-500">⚠️ การเช่ายังรอการชำระเงิน</p>
                    <p className="mt-2"><strong>ราคาค่าเช่า:</strong> ฿{existingRental.amount}</p>
                    <p className="text-red-500 font-bold mt-4">📌 คุณเช่าหนังสือเล่มนี้แล้ว</p>
                  </div>
                )}

                {existingRental.status === "borrowed" && (
                  <p className="text-green-500">✅ การเช่าได้รับการชำระเงินแล้ว</p>
                )}

                {existingRental.status === '-' && (
                  <>
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
                    <p className="mt-2"><strong>ราคาค่าเช่า:</strong> ฿{rentalPrice}</p>
                  </>
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
                <p className="mt-2"><strong>ราคาค่าเช่า:</strong> ฿{rentalPrice}</p>
              </div>
            )}

            {/* ถ้าไม่มีการเช่าแล้ว ให้แสดงปุ่มเช่าหนังสือ */}
            <div className="mt-6 flex justify-between">
              <button
                  onClick={() => window.history.back()}
                  className="bg-[#BA7D66] hover:bg-[#9a5d4c] hover:bg-opacity-80 text-white font-semibold py-4 px-12 w-1/4 rounded-full text-2xl"
              >
                กลับ
              </button>
              <button
                  onClick={handleRentalClick}
                  className="bg-[#BA7D66] hover:bg-[#9a5d4c] hover:bg-opacity-80 text-white font-semibold py-4 px-12 w-1/4 rounded-full text-2xl"
              >
                เช่าหนังสือ
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default Detail;
