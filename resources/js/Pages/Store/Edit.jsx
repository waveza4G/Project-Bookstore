import { useForm, router } from '@inertiajs/react';
import { useState } from 'react';
import Navbar from '../Bookstore/Navbar';

export default function Edit({ table, record, categories = [], groups = [] }) {
    const { data, setData, put, errors } = useForm({
        ...record,
        image: null,
    });

    const [loading, setLoading] = useState(false);

    const handleChange = (e) => {
        setData(e.target.name, e.target.value);
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        setLoading(true);

        const formData = new FormData();
        Object.keys(data).forEach((key) => {
            if (data[key] instanceof File) {
                formData.append(key, data[key]);
            } else {
                formData.append(key, data[key] ?? '');
            }
        });

        put(`/admin/update/${table}/${record.id}`, formData, {
            forceFormData: true,
            onSuccess: () => {
                setLoading(false);
                alert("บันทึกสำเร็จ!");
                router.visit('/admin/dashboard');
            },
            onError: (errors) => {
                console.error("Update Error:", errors);
                setLoading(false);
            },
        });
    };

    return (
        <div className="min-h-screen flex items-center justify-center bg-gray-50">
            <div className="bg-white p-6 rounded-lg shadow-lg w-full max-w-2xl">
                <h2 className="text-2xl font-semibold mb-4">Edit Record</h2>
                <form onSubmit={handleSubmit} className="space-y-4">

                    {/* Table 1: Rentals */}
                    {table == 1 && (
                        <>
                            <label>Rental Date:</label>
                            <input type="date" name="rental_date" value={data.rental_date || ''} onChange={handleChange} className="border p-2 w-full" />

                            <label>Due Date:</label>
                            <input type="date" name="due_date" value={data.due_date || ''} onChange={handleChange} className="border p-2 w-full" />

                            <label>Return Date:</label>
                            <input type="date" name="return_date" value={data.return_date || ''} onChange={handleChange} className="border p-2 w-full" />

                            <label>Amount:</label>
                            <input type="number" name="amount" value={data.amount || ''} onChange={handleChange} className="border p-2 w-full" />

                            <label>Status:</label>
                            <select name="status" value={data.status} onChange={handleChange} className="border p-2 w-full">
                                <option value="-">-</option>
                                <option value="borrowed">Borrowed</option>
                                <option value="waiting">waiting</option>

                            </select>

                        </>
                    )}
{/* Table 2: Books */}
{table == 2 && (
    <>
        <label>Book Name:</label>
        <input type="text" name="book_name" value={data.book_name} onChange={handleChange} className="border p-2 w-full" />

        <label>Category:</label>
        <select name="category_id" value={data.category_id || ''} onChange={handleChange} className="border p-2 w-full">
            <option value="">Select Category</option>
            {categories.map(cat => (
                <option key={cat.id} value={cat.id}>{cat.category_name}</option>
            ))}
        </select>

        <label>Group:</label>
        <select name="group_id" value={data.group_id || ''} onChange={handleChange} className="border p-2 w-full">
            <option value="">Select Group</option>
            {groups.map(grp => (
                <option key={grp.id} value={grp.id}>{grp.group_name}</option>
            ))}
        </select>

        <label>Quantity:</label>
        <input type="number" name="quantity" value={data.quantity} onChange={handleChange} className="border p-2 w-full" />

        <label>Remaining Quantity:</label>
        <input type="number" name="remaining_quantity" value={data.remaining_quantity} onChange={handleChange} className="border p-2 w-full" />

        <label>Sold Quantity:</label>
        <input type="number" name="sold_quantity" value={data.sold_quantity} onChange={handleChange} className="border p-2 w-full" />

        <label>Price:</label>
        <input type="number" name="price" value={data.price} onChange={handleChange} className="border p-2 w-full" />

        <label>Publisher:</label>
        <input type="text" name="publisher" value={data.publisher} onChange={handleChange} className="border p-2 w-full" />

        <label>Author:</label>
        <input type="text" name="author" value={data.author} onChange={handleChange} className="border p-2 w-full" />

        <label>Description:</label>
        <textarea name="description" value={data.description} onChange={handleChange} className="border p-2 w-full"></textarea>

        {table == 2 && (
            <div className="mb-4 text-center">
                <button
                    type="button"
                    onClick={() => router.visit(`/admin/upload-image/${record.id}`)}
                    className="bg-green-500 text-white px-4 py-2 rounded-md"
                >
                    อัปโหลดรูปภาพ
                </button>
            </div>
        )}
    </>
)}

                    {/* Table 3: Customers */}
                    {table == 3 && (
                        <>
                            <label>Name:</label>
                            <input type="text" name="name" value={data.name} onChange={handleChange} className="border p-2 w-full" />

                            <label>Lastname:</label>
                            <input type="text" name="lastname" value={data.lastname} onChange={handleChange} className="border p-2 w-full" />

                            <label>Username:</label>
                            <input type="text" name="username" value={data.username} onChange={handleChange} className="border p-2 w-full" />

                            <label>Email:</label>
                            <input type="email" name="email" value={data.email} onChange={handleChange} className="border p-2 w-full" />

                            <label>Phone:</label>
                            <input type="text" name="phone" value={data.phone} onChange={handleChange} className="border p-2 w-full" />
                        </>
                    )}

                    {/* Table 4: Payments */}
                    {table == 4 && (
                        <>
                            <label>Payment Amount:</label>
                            <input type="number" name="payment_amount" value={data.payment_amount} onChange={handleChange} className="border p-2 w-full" />

                            <label>Status:</label>
                            <select name="status" value={data.status ?? ''} onChange={handleChange} className="border p-2 w-full">
                                <option value="">Select Status</option>
                                <option value="-">-</option>
                                <option value="return">Return</option>  {/* เพิ่มตัวเลือก 'return' */}
                            </select>

                            <label>Payment Date:</label>
                            <input type="date" name="payment_date" value={data.payment_date ?? ''} onChange={handleChange} className="border p-2 w-full" />
                        </>
                    )}
                    {table == 5 && (
                        <>
                            <label>Username:</label>
                            <input type="text" name="username" value={data.username} onChange={handleChange} className="border p-2 w-full" />

                            <label>Email:</label>
                            <input type="email" name="email" value={data.email} onChange={handleChange} className="border p-2 w-full" />
                        </>
                    )}

                    {/* Action Buttons */}
                    <div className="flex justify-between">
                        <button type="button" onClick={() => router.visit('/admin/dashboard')} className="bg-gray-500 text-white px-4 py-2 rounded-md">
                            Cancel
                        </button>
                        <button type="submit" disabled={loading} className={`px-4 py-2 rounded-md ${loading ? "bg-gray-400" : "bg-blue-500 text-white"}`}>
                            {loading ? 'Saving...' : 'Save'}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    );
}
