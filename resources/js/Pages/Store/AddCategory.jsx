import { useForm, router } from '@inertiajs/react';
import { useState } from 'react';
import FlashMessage from '@/Components/FlashMessage';

const AddCategory = ({ categories }) => {
    const { data, setData, post, errors, reset } = useForm({
        category_name: '',
    });

    const [showFlash, setShowFlash] = useState(false);

    const handleSubmit = (e) => {
        e.preventDefault();
        post(route('categories.store'), {
            onSuccess: () => {
                setShowFlash(true);
                reset();
                setTimeout(() => setShowFlash(false), 3000);
            },
        });
    };

    const handleDelete = (id) => {
        if (!confirm('Are you sure you want to delete this category?')) return;

        router.delete(route('categories.destroy', id), {
            onSuccess: () => {
                console.log("Category deleted successfully!");
            },
            onError: (errors) => {
                console.error("Error deleting category:", errors);
            },
            preserveScroll: true,
        });
    };

    return (
        <div className="min-h-screen bg-gray-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
            <div className="bg-white border border-gray-300 rounded-lg shadow-lg p-10 max-w-md w-full ">
                {showFlash && (
                    <div className="fixed top-20 left-1/2 transform -translate-x-1/2 w-72">
                        <FlashMessage flash={{ success: 'Category added successfully!' }} />
                    </div>
                )}
                <h1 className="text-3xl font-extrabold text-gray-900 mb-6 text-center">Manage Categories</h1>

                {/* ฟอร์มเพิ่มหมวดหมู่ */}
                <form onSubmit={handleSubmit} className="space-y-4">
                    <div>
                        <label className="block text-gray-700">Category Name</label>
                        <input
                            type="text"
                            name="category_name"
                            value={data.category_name}
                            onChange={(e) => setData('category_name', e.target.value)}
                            className="w-full p-2 border border-gray-300 rounded-lg shadow-sm"
                            required
                        />
                        {errors.category_name && <p className="text-red-500 text-sm">{errors.category_name}</p>}
                    </div>

                    <div>
                        <button
                            type="submit"
                            className="w-full py-2 px-4 text-white bg-[#BA7D66] rounded-md shadow-md hover:bg-[#9a5d4c] focus:outline-none"
                        >
                            Submit
                        </button>
                    </div>
                </form>

                {/* รายการหมวดหมู่ */}
                <div className="mt-6">
                    <h2 className="text-xl font-semibold text-gray-700 mb-2">Category List</h2>
                    <ul className="divide-y divide-gray-200">
                        {categories.length > 0 ? (
                            categories.map((category) => (
                                <li key={category.id} className="flex justify-between items-center py-2">
                                    <span className="text-gray-900">
                                        <strong>ID:</strong> {category.id} - {category.category_name}
                                    </span>
                                    <button
                                        onClick={() => handleDelete(category.id)}
                                        className="px-3 py-1 text-white bg-red-500 rounded-md hover:bg-red-700"
                                    >
                                        Delete
                                    </button>
                                </li>
                            ))
                        ) : (
                            <p className="text-gray-500">No categories found.</p>
                        )}
                    </ul>
                </div>
            </div>
        </div>
    );
};

export default AddCategory;
