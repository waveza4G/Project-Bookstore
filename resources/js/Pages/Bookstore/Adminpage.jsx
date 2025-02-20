import { router } from '@inertiajs/react';
import React, { useState } from 'react';
import Navbar from './Navbar';

export default function Adminpage({ table, tableNo, sortBy, sortDirection, query }) {
    const [selectedTable, setSelectedTable] = useState(tableNo || 1);
    const [search, setSearch] = useState(query || '');
    const [editingRow, setEditingRow] = useState(null);
    const [editedData, setEditedData] = useState({});

    const columns = {

        1: [
            { label: 'ID', key: 'id' },
            { label: 'รหัสลูกค้า', key: 'customer_id' },
            { label: 'ชื่อลูกค้า', key: 'customer.name' },
            { label: 'รหัสหนังสือ', key: 'book_id' },
            { label: 'วันที่ยืมหนังสือ', key: 'rental_date' },
            { label: 'วันที่ครบกำหนด', key: 'due_date' },
            { label: 'วันที่คืนหนังสือ', key: 'return_date' },
        ],
        2: [
            { label: 'ID', key: 'id' },
            { label: 'Book Name', key: 'book_name' },
            { label: 'Category', key: 'category_id' },
            { label: 'Group', key: 'group_id' },
            { label: 'Quantity', key: 'quantity' },
            { label: 'Remaining Quantity', key: 'remaining_quantity' },
            { label: 'sold_quantity', key: 'sold_quantity' },
            { label: 'price', key: 'price' },
            { label: 'publisher', key: 'publisher' },
            { label: 'author', key: 'author' },
            { label: 'description', key: 'description' },
            { label: 'Quantity', key: 'quantity' },
            { label: 'image', key: 'image' },
        ],
        3: [
            { label: 'ID', key: 'id' },
            { label: 'รหัสลูกค้า', key: 'name' },
            { label: 'ชื่อลูกค้า', key: 'username' },
            { label: 'รหัสหนังสือ', key: 'phone' },
            { label: 'วันที่ยืมหนังสือ', key: 'email' },
            { label: 'วันที่ครบกำหนด', key: 'password' },
            { label: 'วันที่คืนหนังสือ', key: 'book_count' },
            { label: 'วันที่ยืมหนังสือ', key: 'status' },
            { label: 'วันที่ครบกำหนด', key: 'penalty' },
        ],
        4: [
            { label: 'ID', key: 'id' },
            { label: 'Book Name', key: 'customer_id' },
            { label: 'Category', key: 'book_id' },
            { label: 'Group', key: 'rental_id' },
            { label: 'Quantity', key: 'payment_amount' },
            { label: 'Remaining', key: 'status' },
            { label: 'sold_', key: 'payment_date' },
        ],
        5: [
            { label: 'ID', key: 'id' },
            { label: 'Book Name', key: 'username' },
            { label: 'Book Name', key: 'email' },
            { label: 'Option', key: 'option' },
        ],
    };

    const getValue = (obj, path) => {
        if (!obj) return '-';

        if (path === 'customer.name') {
            return `${obj.customer?.name ?? ''} ${obj.customer?.lastname ?? ''}`.trim() || '-';
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
        router.get('/admin/dashboard', { search, sortBy, sortDirection }, { preserveState: true });
    };

    const handleEdit = (id, columnKey, value) => {
        setEditingRow(id);
        setEditedData({
            ...editedData,
            [id]: { ...editedData[id], [columnKey]: value },
        });
    };

    const handleChange = (e, columnKey) => {
        const value = e.target.value;
        setEditedData({
            ...editedData,
            [editingRow]: { ...editedData[editingRow], [columnKey]: value },
        });
    };

    const handleSave = (id) => {
        const updatedRow = editedData[id];

        // ส่งข้อมูลไปที่ backend โดยไม่รีเฟรชหน้า
        router.put(`/admin/dashboard/${selectedTable}/${id}`, updatedRow, {
            onSuccess: () => {
                setEditingRow(null); // หลังจากบันทึกแล้วให้หยุดการแก้ไข
                setEditedData({}); // ล้างข้อมูลที่แก้ไข
                // เก็บสถานะใหม่ใน URL
                router.get('/admin/dashboard', {
                    selectedTable,
                    search,
                    sortBy,
                    sortDirection,
                }, {
                    preserveState: true, // ทำให้ข้อมูลในหน้าไม่หายไป
                });
            },
        });
    };

    const handleCancel = () => {
        setEditingRow(null); // Stop editing
    };

    const handleDelete = (id) => {
        if (confirm('Are you sure you want to delete this record?')) {
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
                <div className="flex justify-between items-center mb-6">
                    <h1 className="text-3xl font-semibold text-gray-800">Admin Dashboard</h1>
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
                            <option value={3}>Admins</option>
                        </select>
                        <button
                            onClick={() => router.get('/admin/create')}
                            className="ml-4 bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700"
                        >
                            Add New Record
                        </button>
                    </div>
                </div>

                {/* 🔎 ฟอร์มค้นหา */}
                <div className="flex justify-end mb-6">
                    <form onSubmit={handleSearch} className="flex space-x-2">
                        <input
                            type="text"
                            placeholder="Search..."
                            value={search}
                            onChange={(e) => setSearch(e.target.value)}
                            className="w-64 p-2 border border-gray-300 rounded-lg shadow-sm text-sm"
                        />
                        <button
                            type="submit"
                            className="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-700"
                        >
                            Search
                        </button>
                    </form>
                </div>

                <div className="overflow-x-auto rounded-lg shadow-md">
                    <table className="w-full bg-white rounded-lg">
                        <thead>
                            <tr className="bg-indigo-600 text-white">
                                {columns[selectedTable]?.map((col) => (
                                    <th
                                        key={col.key}
                                        className="px-6 py-3 text-left font-medium whitespace-nowrap cursor-pointer hover:bg-indigo-700"
                                        onClick={() => handleSort(col.key)}
                                    >
                                        <span className="flex items-center gap-2">
                                            {col.label}
                                            {sortBy === col.key && (
                                                <span className="text-gray-300">
                                                    {sortDirection === 'asc' ? '⬆️' : '⬇️'}
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
                                                {col.key !== 'option' ? (
                                                    editingRow === row.id ? (
                                                        <input
                                                            type="text"
                                                            value={editedData[row.id]?.[col.key] || getValue(row, col.key)}
                                                            onChange={(e) => handleChange(e, col.key)}
                                                            className="w-full p-2 border border-gray-300 rounded-lg"
                                                        />
                                                    ) : (
                                                        getValue(row, col.key) // ใช้ getValue แทนการเข้าถึงข้อมูลโดยตรง
                                                    )
                                                ) : (
                                                    <div className="flex space-x-2">
                                                        {editingRow === row.id ? (
                                                            <>
                                                                <button
                                                                    onClick={() => handleSave(row.id)}
                                                                    className="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-700"
                                                                >
                                                                    Save
                                                                </button>
                                                                <button
                                                                    onClick={handleCancel}
                                                                    className="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-700"
                                                                >
                                                                    Cancel
                                                                </button>
                                                            </>
                                                        ) : (
                                                            <>
                                                                <button
                                                                    onClick={() => handleEdit(row.id, 'category_name', row.category_name)}
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
                                                            </>
                                                        )}
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

                {/* Pagination */}
                <div className="mt-4 flex justify-center gap-2">
                    {table?.links?.map((link, index) => (
                        <button
                            key={index}
                            onClick={() =>
                                link.url && router.get(link.url, { selectedTable, search, sortBy, sortDirection }, { preserveState: true })
                            }
                            className={`mx-1 px-4 py-2 border rounded-lg transition-all ${link.active ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'}`}
                            dangerouslySetInnerHTML={{ __html: link.label }}
                            disabled={!link.url}
                        />
                    ))}
                </div>
            </div>
        </>
    );
}
