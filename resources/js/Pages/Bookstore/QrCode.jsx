import React, { useState } from 'react';
import { usePage } from '@inertiajs/react'; // นำเข้า usePage จาก @inertiajs/react
import { Inertia } from '@inertiajs/inertia'; // นำเข้า Inertia จาก @inertiajs/inertia

const QrCode = () => {
  const { rentalAmount, book, rentalDays } = usePage().props; // รับข้อมูลจาก props

  const [paymentCompleted, setPaymentCompleted] = useState(false);
  const [image, setImage] = useState(null); // สถานะสำหรับไฟล์รูปภาพ
  const [imagePreview, setImagePreview] = useState(null); // สถานะสำหรับแสดงตัวอย่างรูปภาพ

  // ฟังก์ชันสำหรับการเลือกไฟล์
  const handleFileChange = (e) => {
    const file = e.target.files[0]; // รับไฟล์ที่เลือก
    setImage(file); // ตั้งค่ารูปภาพที่เลือก

    // สร้าง URL สำหรับแสดงตัวอย่างรูปภาพ
    const previewUrl = URL.createObjectURL(file);
    setImagePreview(previewUrl); // ตั้งค่าตัวอย่างรูปภาพ
  };

  // ฟังก์ชันสำหรับการยืนยันการชำระเงิน
  const handleConfirmPayment = () => {
    const formData = new FormData();
    formData.append('image', image); // เพิ่มไฟล์รูปภาพใน FormData
    formData.append('bookId', book.id); // ส่งข้อมูล bookId
    formData.append('rentalDays', rentalDays); // ส่งข้อมูล rentalDays

    Inertia.post('/payment/confirm', formData, {
      onSuccess: () => {
        // ทำอะไรเมื่อสำเร็จ เช่น เปลี่ยนสถานะ
      },
      headers: {
        'Content-Type': 'multipart/form-data', // ระบุว่าเป็นการส่งไฟล์
      },
    });
  };

  return (
    <div className="container mx-auto p-6">
      <h2 className="text-3xl font-bold text-gray-800 mb-4">QR Code สำหรับการชำระเงิน</h2>

      <div className="flex justify-center mb-4">
        <img
          src="/storage/image/QRcode.jpeg"
          alt="QR Code"
          className="w-64 h-64 object-cover rounded-lg shadow-lg"
        />
      </div>

      <p className="text-xl text-gray-700">จำนวนเงินที่ต้องชำระ: ฿{rentalAmount}</p>
      <p className="text-lg text-gray-700 mt-4">หนังสือที่เช่า: {book.book_name}</p>
      <p className="text-lg text-gray-700 mt-2">จำนวนวันที่เช่า: {rentalDays} วัน</p>

      {/* เพิ่มฟอร์มเลือกไฟล์พร้อมข้อความ */}
      <div className="mt-4">
        <input
          type="file"
          id="file-input"
          onChange={handleFileChange}
          className="hidden" // ซ่อน input ปกติ
        />
        <label
          htmlFor="file-input"
          className="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded cursor-pointer"
        >
          ใส่รูปภาพคิวอาโค้ดที่ชำระเงิน
        </label>
      </div>

      {/* แสดงตัวอย่างรูปภาพถ้ามีการเลือก */}
      {imagePreview && (
        <div className="mt-4">
          <h3 className="text-xl text-gray-700 mb-2">รูปภาพ:</h3>
          <img
            src={imagePreview}
            alt="Preview"
            className="w-64 h-64 object-cover rounded-lg shadow-lg"
          />
        </div>
      )}

      {/* ถ้าการชำระเงินสำเร็จ จะให้แสดงปุ่มยืนยัน */}
        <div className="mt-4">
          <button
            onClick={handleConfirmPayment}
            className="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded"
          >
            ยืนยันการชำระเงิน
          </button>
        </div>
    </div>
  );
};

export default QrCode;
