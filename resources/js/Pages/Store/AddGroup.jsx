import { useForm, router } from '@inertiajs/react';
import { useState } from 'react';
import FlashMessage from '@/Components/FlashMessage';

const AddGroup = ({ groups }) => {
    const { data, setData, post, errors, reset } = useForm({
        group_name: '',
    });

    const [showFlash, setShowFlash] = useState(false);

    const handleSubmit = (e) => {
        e.preventDefault();
        post(route('groups.store'), {
            onSuccess: () => {
                setShowFlash(true);
                reset();
                setTimeout(() => setShowFlash(false), 3000);
            },
        });
    };

    const handleDelete = (id) => {
        if (!confirm('Are you sure you want to delete this group?')) return;

        router.delete(route('groups.destroy', id), {
            onSuccess: () => {
                console.log("Group deleted successfully!");
            },
            onError: (errors) => {
                console.error("Error deleting group:", errors);
            },
            preserveScroll: true,
        });
    };

    return (
        <div className="min-h-screen bg-gray-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
            <div className="bg-white border border-gray-300 rounded-lg shadow-lg p-10 max-w-md w-full">
                {showFlash && (
                    <div className="fixed top-20 left-1/2 transform -translate-x-1/2 w-72">
                        <FlashMessage flash={{ success: 'Group added successfully!' }} />
                    </div>
                )}
                <h1 className="text-3xl font-extrabold text-gray-900 mb-6 text-center">Manage Groups</h1>

                {/* ฟอร์มเพิ่มกลุ่ม */}
                <form onSubmit={handleSubmit} className="space-y-4">
                    <div>
                        <label className="block text-gray-700">Group Name</label>
                        <input
                            type="text"
                            name="group_name"
                            value={data.group_name}
                            onChange={(e) => setData('group_name', e.target.value)}
                            className="w-full p-2 border border-gray-300 rounded-lg shadow-sm"
                            required
                        />
                        {errors.group_name && <p className="text-red-500 text-sm">{errors.group_name}</p>}
                    </div>

                    <div>
                        <button
                            type="submit"
                            className="w-full py-2 px-4 text-white bg-blue-600 rounded-md shadow-md hover:bg-blue-700 focus:outline-none"
                        >
                            Submit
                        </button>
                    </div>
                </form>

                {/* รายการกลุ่ม */}
                <div className="mt-6">
                    <h2 className="text-xl font-semibold text-gray-700 mb-2">Group List</h2>
                    <ul className="divide-y divide-gray-200">
                        {groups.length > 0 ? (
                            groups.map((group) => (
                                <li key={group.id} className="flex justify-between items-center py-2">
                                    <span className="text-gray-900">
                                        <strong>ID:</strong> {group.id} - {group.group_name}
                                    </span>
                                    <button
                                        onClick={() => handleDelete(group.id)}
                                        className="px-3 py-1 text-white bg-red-500 rounded-md hover:bg-red-700"
                                    >
                                        Delete
                                    </button>
                                </li>
                            ))
                        ) : (
                            <p className="text-gray-500">No groups found.</p>
                        )}
                    </ul>
                </div>
            </div>
        </div>
    );
};

export default AddGroup;
