import { useForm, router, usePage } from '@inertiajs/react';
import { useState } from 'react';

const Createbook = ({ categories, groups }) => {
    const { data, setData, post, errors, reset } = useForm({
        book_name: '',
        category_id: '',
        group_id: '',
        quantity: '',
        price: '',
        publisher: '',
        author: '',
        description: '',
        image: null,
    });

    const [preview, setPreview] = useState(null);

    const handleImageChange = (e) => {
        const file = e.target.files[0];
        if (file) {
            setData('image', file);
            const reader = new FileReader();
            reader.onloadend = () => {
                setPreview(reader.result);
            };
            reader.readAsDataURL(file);
        } else {
            setPreview(null);
        }
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        console.log("Submitting form data:", data);

        post(route('books.store'), {
            onSuccess: () => {
                setShowFlash(true);
                reset();
                setPreview(null);
                setTimeout(() => {
                    setShowFlash(false);
                    // ✅ เปลี่ยนหน้าไปที่ `/admin/dashboard` หลังจากบันทึกสำเร็จ
                    router.visit('/admin/dashboard', { replace: true });
                }, 2000);
            },
        });
    };

    return (
        <div className="min-h-screen bg-gray-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
            <div className="bg-white border border-gray-300 rounded-lg shadow-lg p-10 max-w-3xl w-full">
                <h1 className="text-3xl font-extrabold text-gray-900 mb-6 text-center">Add New Book</h1>

                <form onSubmit={handleSubmit} encType="multipart/form-data" className="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label className="block text-gray-700">Book Name</label>
                        <input type="text" value={data.book_name} onChange={(e) => setData('book_name', e.target.value)}
                            className="w-full p-2 border border-gray-300 rounded-lg" required />
                    </div>

                    <div>
                        <label className="block text-gray-700">Author</label>
                        <input type="text" value={data.author} onChange={(e) => setData('author', e.target.value)}
                            className="w-full p-2 border border-gray-300 rounded-lg" required />
                    </div>

                    <div>
                        <label className="block text-gray-700">Category</label>
                        <select value={data.category_id} onChange={(e) => setData('category_id', e.target.value)}
                            className="w-full p-2 border border-gray-300 rounded-lg" required>
                            <option value="">Select Category</option>
                            {categories.map((cat) => (
                                <option key={cat.id} value={cat.id}>{cat.category_name}</option>
                            ))}
                        </select>
                    </div>

                    <div>
                        <label className="block text-gray-700">Group</label>
                        <select value={data.group_id} onChange={(e) => setData('group_id', e.target.value)}
                            className="w-full p-2 border border-gray-300 rounded-lg" required>
                            <option value="">Select Group</option>
                            {groups.map((grp) => (
                                <option key={grp.id} value={grp.id}>{grp.group_name}</option>
                            ))}
                        </select>
                    </div>

                    <div>
                        <label className="block text-gray-700">Quantity</label>
                        <input type="number" value={data.quantity} onChange={(e) => setData('quantity', e.target.value)}
                            className="w-full p-2 border border-gray-300 rounded-lg" required />
                    </div>

                    <div>
                        <label className="block text-gray-700">Price</label>
                        <input type="number" value={data.price} onChange={(e) => setData('price', e.target.value)}
                            className="w-full p-2 border border-gray-300 rounded-lg" required />
                    </div>

                    <div>
                        <label className="block text-gray-700">Publisher</label>
                        <input type="text" value={data.publisher} onChange={(e) => setData('publisher', e.target.value)}
                            className="w-full p-2 border border-gray-300 rounded-lg" required />
                    </div>

                    <div>
                        <label className="block text-gray-700">Description</label>
                        <textarea value={data.description} onChange={(e) => setData('description', e.target.value)}
                            className="w-full p-2 border border-gray-300 rounded-lg" required rows="3"></textarea>
                    </div>

                    <div>
                        <label className="block text-gray-700">Image</label>
                        <input type="file" onChange={handleImageChange} className="w-full p-2 border border-gray-300 rounded-lg" />
                        {preview && <img src={preview} alt="Preview" className="mt-4 max-w-full h-auto rounded-md" />}
                    </div>

                    <div className="md:col-span-2">
                        <button type="submit" className="w-full py-2 px-4 text-white bg-blue-600 rounded-md shadow-md hover:bg-blue-700">
                            Submit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    );
};

export default Createbook;
