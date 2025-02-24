import { useState, useEffect } from "react";
import { Link, useForm } from "@inertiajs/react";

export default function Show({ book, auth, existingRental }) {
    const { post, data, setData } = useForm({ rental_days: 1 });
    const rentalRates = {
        1: 0.04, 2: 0.08, 3: 0.12, 4: 0.16,
        5: 0.20, 6: 0.24, 7: 0.30
    };

    const calculatePrice = (days) => {
        const rate = rentalRates[days] || 0.30;
        return Math.ceil(book.price * rate);
    };

    const [rentalPrice, setRentalPrice] = useState(calculatePrice(1));

    useEffect(() => {
        console.log("🔍 existingRental:", existingRental);
    }, [existingRental]);

    const handleDaysChange = (e) => {
        const days = parseInt(e.target.value, 10);
        setData("rental_days", days);
        setRentalPrice(calculatePrice(days));
    };

    const handleSubmit = (event) => {
        event.preventDefault();
        post(`/rent/${book.id}`, data);
    };

    return (
        <div className="container mx-auto p-4">
            <h1 className="text-2xl font-bold mb-4">{book.book_name}</h1>
            <img src={book.image} alt={book.book_name} className="w-1/3 mb-4" />
            <p><strong>ราคา:</strong> {book.price} บาท</p>

            {auth.customer ? (
                existingRental ? (
                    <div>
                        <p className="text-red-500 font-bold mt-4">📌 คุณเช่าหนังสือเล่มนี้แล้ว</p>

                        {/* แสดงสถานะการชำระเงิน */}
                        {existingRental.status === "pending" && (
                            <div>
                                <p className="text-yellow-500">⚠️ การเช่ายังรอการชำระเงิน</p>
                                <Link
                                    href={`/payments/create?rental_id=${existingRental.id}`} //---------------ปุ่มไปชำระเงิน-----------
                                    className="bg-orange-500 text-white px-4 py-2 rounded mt-4 inline-block"
                                >
                                    ไปที่หน้าชำระเงิน
                                </Link>
                            </div>
                        )}
                        {existingRental.status === "paid" && (
                            <p className="text-green-500">✅ การเช่าได้รับการชำระเงินแล้ว</p>
                        )}
                    </div>
                ) : (
                    <form onSubmit={handleSubmit} className="mt-4">
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
                            <strong>ราคาค่าเช่า:</strong> {rentalPrice} บาท
                        </p>

                        <button
                            type="submit"
                            className="bg-blue-500 text-white px-4 py-2 rounded mt-4"
                        >
                            เช่าหนังสือ
                        </button>
                    </form>
                )
            ) : (
                <Link href="/login" className="text-blue-500">กรุณาเข้าสู่ระบบ</Link>
            )}
        </div>
    );
}
