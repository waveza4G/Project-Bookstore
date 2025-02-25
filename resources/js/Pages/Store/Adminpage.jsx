import { router } from '@inertiajs/react';
import { Inertia } from '@inertiajs/inertia';
import { useForm } from '@inertiajs/react';
import React, { useState } from 'react';
import Navbar from '../Bookstore/Navbar';

export default function Adminpage({ table, tableNo, sortBy, sortDirection, query }) {
    const [selectedTable, setSelectedTable] = useState(tableNo || 1);
    const [search, setSearch] = useState(query || '');

    const columns = {
        1: [
            { label: 'ID', key: 'id' },
            { label: 'รหัสลูกค้า', key: 'customer_id' },
            { label: 'ชื่อลูกค้า', key: 'customer.name' },
            { label: 'นามสกุล', key: 'customer.lastname' },
            { label: 'สถานะลูกค้า', key: 'status' },
            { label: 'รหัสหนังสือ', key: 'book_id' },
            { label: 'วันที่ยืมหนังสือ', key: 'rental_date' },
            { label: 'วันที่ครบกำหนด', key: 'due_date' },
            { label: 'วันที่คืนหนังสือ', key: 'return_date' },
            { label: 'จำนวนเงินที่ต้องชำระ', key: 'amount' },
            { label: 'เพิ่มเติม', key: 'option' },
            { label: 'สถานะการยืม', key: 'Payment' },
        ],
        2: [
            { label: 'ID', key: 'id' },
            { label: 'ชื่อหนังสือ', key: 'book_name' },
            { label: 'รหัสหมวดหมู่', key: 'category_id' },
            { label: 'หมวดหมู่', key: 'category.category_name' },
            { label: 'รหัสประเภท', key: 'group_id' },
            { label: 'ประเภท', key: 'group.group_name' },
            { label: 'จำนวนหนังสือ', key: 'quantity' },
            { label: 'จำนวนที่เหลือ', key: 'remaining_quantity' },
            { label: 'จำนวนที่ถูกเช่า', key: 'sold_quantity' },
            { label: 'ราคา', key: 'price' },
            { label: 'สำนักพิมพ์', key: 'publisher' },
            { label: 'นักเขียน', key: 'author' },
            { label: 'คำอธิบาย', key: 'description' },
            { label: 'รูปภาพ', key: 'image' },
            { label: 'เพิ่มเติม', key: 'option' },
        ],

        3: [
            { label: 'ID', key: 'id' },
            { label: 'ชื่อ', key: 'name' },
            { label: 'นามสกุล', key: 'lastname' },
            { label: 'ชื่อผู้ใช้', key: 'username' },
            { label: 'อีเมล', key: 'email' },
            { label: 'เบอร์', key: 'phone' },
            { label: 'จำนวนหนังสือที่ยืม', key: 'book_count' },
            { label: 'เพิ่มเติม', key: 'option' },
        ],

            4: [
                { label: 'ID', key: 'id' },
                { label: 'ยอดชำระเงิน', key: 'payment_amount' },
                { label: 'สถานะการคืน', key: 'status' },
                { label: 'วันที่ชำระเงิน', key: 'payment_date' },
                { label: 'ค่าปรับ', key: 'penalty' },
                { label: 'รหัสลูกค้า', key: 'customer_id' },
                { label: 'ชื่อลูกค้า', key: 'customer.name' },
                { label: 'รหัสหนังสือ', key: 'book_id' },
                { label: 'ชื่อหนังสือ', key: 'book.book_name' },
                { label: 'รหัสการยืม', key: 'rental_id' },
                { label: 'วันที่คืนหนังสือ', key: 'rental.return_date' },
                { label: 'เพิ่มเติม', key: 'option' },
            ],



        5: [
            { label: 'ID', key: 'id' },
            { label: 'ชื่อผู้ใช้', key: 'username' },
            { label: 'อีเมล', key: 'email' },
            { label: 'เพิ่มเติม', key: 'option' },
        ],
    };

    const getValue = (obj, path) => {
        if (!obj) return '-';

        // ✅ ตรวจสอบว่าเป็นรูปภาพหรือไม่
        if (path === 'image') {
            return obj.image ? (
                <img
                    src={`/storage/${obj.image}`} // ✅ เรียก path รูปภาพจาก public/storage
                    alt="Book"
                    className="h-16 w-16 object-cover rounded-md shadow-sm"
                />
            ) : '-';
        }

        return path.split('.').reduce((o, key) => o?.[key] ?? '-', obj);
    };

    const handleTableChange = (newTable) => {
        setSelectedTable(newTable);
        router.get('/admin/dashboard', {
            selectedTable: newTable,
            search,
            sortBy,
            sortDirection,
        }); // เราไม่จำเป็นต้องใช้ preserveState: true ถ้าทุกอย่างถูกเก็บใน URL
    };

    const handleSort = (columnKey) => {
        const newDirection = sortBy === columnKey && sortDirection === 'asc' ? 'desc' : 'asc';
        router.get('/admin/dashboard', {
            selectedTable,
            search,
            sortBy: columnKey,
            sortDirection: newDirection,
        }, {
            preserveState: true, // เก็บสถานะของหน้า
        });
    };

    const handleSearch = (e) => {
        e.preventDefault();
        router.get('/admin/dashboard', {
            selectedTable, // ✅ ส่ง selectedTable ไปด้วย
            search,
            sortBy,
            sortDirection
        }, { preserveState: true });
    };

    const handleDelete = (id) => {
        if (confirm('Are you sure you want to delete this record?')) {
            // ตรวจสอบว่าค่าของ selectedTable ถูกต้อง
            router.delete(`/admin/dashboard/${selectedTable}/${id}`, {
                onSuccess: () => {
                    // เมื่อการลบเสร็จสมบูรณ์, รีเฟรชหน้าพร้อมกับค่าปัจจุบันใน URL
                    router.get('/admin/dashboard', { selectedTable, search, sortBy, sortDirection }, { preserveState: true });
                },
            });
        }
    };

    return (
        <>
            <Navbar />
            <div className="p-8 bg-gray-50 min-h-screen">
                <div className="flex flex-col sm:flex-row justify-between items-center mb-6">
                    <div className="flex items-center space-x-4">
                        <h1 className="text-3xl font-semibold text-gray-800">Admin</h1>
                        <form onSubmit={handleSearch} className="flex items-center space-x-2">
                            <input
                                type="text"
                                placeholder="Search..."
                                value={search}
                                onChange={(e) => setSearch(e.target.value)}
                                className="w-64 p-2 border border-gray-300 rounded-lg shadow-sm text-sm"
                            />
                            <button
                                type="submit"
                                className="bg-[#BA7D66] text-white px-4 py-2 rounded-lg hover:bg-[#9a5d4c]"
                            >
                                Search
                            </button>
                        </form>
                    </div>

                    <div className="flex items-center space-x-2">
                        <label htmlFor="table-select" className="text-gray-700 font-medium">Select Table:</label>
                        <select
                            id="table-select"
                            value={selectedTable}
                            onChange={(e) => handleTableChange(e.target.value)}
                            className="w-52 p-2 border border-gray-300 rounded-lg shadow-sm text-sm"
                        >
                            <option value={1}>Rental</option>
                            <option value={2}>Books</option>
                            <option value={3}>Customer</option>
                            <option value={4}>Payment</option>
                            <option value={5}>Admins</option>
                        </select>
                        <button
                            onClick={() => router.get('/books/create')}
                            className="ml-4 bg-[#BA7D66] text-white px-4 py-2 rounded-lg hover:bg-[#9a5d4c]"
                        >
                            New Book +
                        </button>
                        <button
                            onClick={() => router.visit('/categories')}
                            className="ml-4 bg-[#BA7D66] text-white px-4 py-2 rounded-lg hover:bg-[#9a5d4c]"
                        >
                            Category +
                        </button>
                        <button
                            onClick={() => router.visit('/groups')}
                            className="ml-4 bg-[#BA7D66] text-white px-4 py-2 rounded-lg hover:bg-[#9a5d4c]"
                        >
                            Group +
                        </button>
                    </div>
                </div>
                <div className="overflow-x-auto rounded-lg shadow-md">
                    <table className="w-full bg-white rounded-lg">
                        <thead>
                        <tr className="bg-[#BA7D66] text-white">
                            {columns[selectedTable]?.map((col) => (
                                <th
                                    key={col.key}
                                    className="px-6 py-3 text-left font-medium whitespace-nowrap cursor-pointer hover:bg-[#9a5d4c]"
                                    onClick={() => handleSort(col.key)}
                                >
                                    <span className="flex items-center gap-2">
                                        {col.label}
                                        {sortBy === col.key && (
                                            <span className="text-black-300">
                                                {sortDirection === 'asc' ? '⇧' : '⇩'}
                                            </span>
                                        )}
                                    </span>
                                </th>
                            ))}
                        </tr>
                        </thead>
                        <tbody>
                            {table?.data?.length > 0 ? (
                                table.data.map((row, index) => (
                                    <tr
                                        key={row.id}
                                        className={`border-b ${index % 2 === 0 ? 'bg-gray-100' : 'bg-white'} hover:bg-gray-200`}
                                    >
                                        {columns[selectedTable]?.map((col) => (
                                            <td key={col.key} className="px-6 py-4 text-gray-700 whitespace-nowrap">
                                                {col.key !== 'option' && col.key !== 'Payment' ? (
                                                    getValue(row, col.key)
                                                ) : col.key === 'Payment' ? (
                                                    // เงื่อนไขการแสดงปุ่มตามสถานะของการเช่า
                                                    row.status === "-" ? (
                                                        <button
                                                            disabled
                                                            className="bg-gray-500 text-white px-4 py-2 rounded-lg cursor-not-allowed"
                                                        >
                                                            ไม่มีสถานะ
                                                        </button>
                                                    ) : row.status === "waiting" ? (
                                                        <button
                                                            onClick={() => {
                                                                if (confirm('Are you sure you want to payment this book?')) {
                                                                    const rentalId = row.id; // รหัสการเช่า
                                                                    const paymentAmount = row.amount; // จำนวนเงินที่ชำระ (จากจำนวนเงินที่ต้องชำระ)
                                                                    Inertia.post('/rental/complete', {
                                                                        rental_id: rentalId,
                                                                        payment_amount: paymentAmount,  // ส่งจำนวนเงินที่ชำระไป
                                                                        book_id: row.book_id,  // ส่งรหัสหนังสือ
                                                                    });
                                                                }
                                                            }}
                                                            className="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-700"
                                                        >
                                                            ยืนยันการชำระเงิน
                                                        </button>
                                                    ) : row.status === "borrowed" ? (
                                                        <button
                                                            onClick={() => {
                                                                if (confirm('Are you sure you want to return this book?')) {
                                                                    router.post('/rental/returnbook', {
                                                                        rental_id: row.id,  // ส่ง rental_id เพื่อให้ Controller ใช้
                                                                    });
                                                                }
                                                            }}
                                                            className="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-700"
                                                        >
                                                            ยืนยันการคืน
                                                        </button>
                                                    ) : null
                                                ) : (
                                                    <div className="flex space-x-2">
                                                        <button
                                                            onClick={() => router.visit(`/admin/edit/${selectedTable}/${row.id}`)}
                                                            className="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-700"
                                                        >
                                                            Edit
                                                        </button>
                                                        <button
                                                            onClick={() => handleDelete(row.id)}
                                                            className="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-700"
                                                        >
                                                            Delete
                                                        </button>
                                                    </div>
                                                )}
                                            </td>
                                        ))}
                                    </tr>
                                ))
                            ) : (
                                <tr>
                                    <td colSpan={columns[selectedTable]?.length} className="text-center py-4 text-gray-500">
                                        No data found
                                    </td>
                                </tr>
                            )}
                        </tbody>

                    </table>
                </div>

                <div className="mt-4 flex justify-center gap-2">
                    {table?.links?.map((link, index) => (
                        <button
                            key={index}
                            onClick={() =>
                                link.url && router.get(link.url, { selectedTable, search, sortBy, sortDirection }, { preserveState: true })
                            }
                            className={`mx-1 px-4 py-2 border rounded-lg transition-all ${link.active ? 'bg-[#BA7D66] text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'}`}
                            dangerouslySetInnerHTML={{ __html: link.label }}
                            disabled={!link.url}
                        />
                    ))}
                </div>
            </div>
        </>
    );
}
