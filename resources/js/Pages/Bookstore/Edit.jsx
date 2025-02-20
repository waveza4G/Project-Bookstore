import React from 'react';
import { useForm } from '@inertiajs/react';

export default function Edit({ data: initialData, table }) {
    // Initialize the form with the data passed from the controller
    const { data, setData, put, errors } = useForm({
        id: initialData.id,
        category_name: initialData.category_name || '',
        group_name: initialData.group_name || '',
        // Add other fields that you need here
    });

    const handleSubmit = (e) => {
        e.preventDefault();
        // PUT request to update the data in the database
        put(`/admin/dashboard/${table}/${data.id}`, {
            data: data,  // Send the data to be updated
        });
    };

    const handleInputChange = (e) => {
        const { name, value } = e.target;
        setData((prevData) => ({
            ...prevData,
            [name]: value,  // Update the form data
        }));
    };

    return (
        <div className="p-8 bg-gray-50 min-h-screen">
            <h1 className="text-3xl font-semibold text-gray-800">Edit Record</h1>
            <form onSubmit={handleSubmit} className="space-y-4 mt-8">
                {/* Show only the fields for the selected record */}
                <div>
                    <label htmlFor="category_name" className="block text-sm font-medium text-gray-700">Category Name</label>
                    <input
                        type="text"
                        id="category_name"
                        name="category_name"
                        value={data.category_name}
                        onChange={handleInputChange}
                        className="mt-1 p-2 w-full border border-gray-300 rounded-lg"
                    />
                    {errors.category_name && <p className="text-red-600 text-sm">{errors.category_name}</p>}
                </div>

                <div>
                    <label htmlFor="group_name" className="block text-sm font-medium text-gray-700">Group Name</label>
                    <input
                        type="text"
                        id="group_name"
                        name="group_name"
                        value={data.group_name}
                        onChange={handleInputChange}
                        className="mt-1 p-2 w-full border border-gray-300 rounded-lg"
                    />
                    {errors.group_name && <p className="text-red-600 text-sm">{errors.group_name}</p>}
                </div>

                {/* You can add more input fields based on the table selected */}

                <div className="flex justify-end space-x-4">
                    <button
                        type="submit"
                        className="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700"
                    >
                        Save Changes
                    </button>
                    <button
                        type="button"
                        className="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400"
                        onClick={() => window.history.back()}
                    >
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    );
}
