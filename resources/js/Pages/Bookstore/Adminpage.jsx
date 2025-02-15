import { router, usePage } from '@inertiajs/react';  
import React, { useState, useEffect } from 'react';

export default function Index({ table, tableNo }) {
    const [selectedTable, setSelectedTable] = useState(tableNo || 1);
    const [showFlash, setShowFlash] = useState(false); 
    const { flash } = usePage().props;

    useEffect(() => {
        if (flash.error || flash.success) {
            setShowFlash(true);
            const timer = setTimeout(() => {
                setShowFlash(false);
            }, 3000);   

            return () => clearTimeout(timer);
        }
    }, [flash]);

    const columns = {
        1:[ 
            { label: 'ID', key: 'id' },
            { label: 'Student ID', key: 'student_id' },
            { label: 'Name Student', key: 'student.name' },
            { label: 'Course ID', key: 'course_id' },
            { label: 'Name Course', key: 'course.course_name' },
            { label: 'Teacher ID', key: 'teacher_id' },
            { label: 'Name Teacher', key: 'teacher.teacher_name' },
        ],
        2:[ 
            { label: 'ID', key: 'id' },
            { label: 'Course Name', key: 'course_name' },
            { label: 'Course Code', key: 'course_code' }
        ],
        3:[ 
            { label: 'ID', key: 'id' }, 
            { label: 'Name', key: 'name' },
            { label: 'Email', key: 'email' },
            { label: 'Phone', key: 'phone' },
            { label: 'Option', key: 'option'},
        ],
        4:[     
            { label: 'ID', key: 'id' }, 
            { label: 'Name', key: 'teacher_name' },
            { label: 'Email', key: 'email' }
        ]
    };

    const handlePageChange = (url, selectedTable) => {
        router.get(url, { selectedTable });
    };

    const handleTableChange = (newTable) => {
        setSelectedTable(newTable);
        handlePageChange('/reg', newTable);
    };

    const handleEdit = (studentId) => {
        router.get(`/reg/${studentId}/edit`, { selectedTable });
    };
    
    const handleDelete = (studentId) => {
        if (confirm("Are you sure you want to delete this student?")) {
            router.delete(`/reg/${studentId}`, {
                onSuccess: () => {
                    setShowFlash(true);
                    setTimeout(() => {
                        setShowFlash(false);
                        router.get('/reg');
                    }, 1500);
                }
            });
        }
    };

    const getValue = (obj, path) => path.split('.').reduce((o, key) => o?.[key], obj);

    return (
        <div className="p-8 bg-gray-50 min-h-screen">
            <div className="flex justify-between items-center mb-6">
                <h1 className="text-3xl font-semibold text-gray-800">REGISTER TABLE</h1>
                <div className="flex items-center space-x-2">
                    <label htmlFor="table-select" className="text-gray-700 font-medium">Select:</label>
                    <select 
                        id="table-select"
                        value={selectedTable} 
                        onChange={(e) => handleTableChange(e.target.value)} 
                        className="w-52 p-2 border border-gray-300 rounded-lg shadow-sm text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    >
                        <option value={1}>Registers</option>
                        <option value={2}>Courses</option>
                        <option value={3}>Students</option>
                        <option value={4}>Teachers</option>
                    </select>
                    <button 
                        onClick={() => router.get('/reg/create')} 
                        className="ml-4 bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700"
                    >
                        Add New Student
                    </button>
                </div>
            </div>

            <div className="overflow-x-auto rounded-lg shadow-md">
                <table className="min-w-full bg-white rounded-lg">
                    <thead>
                        <tr className="bg-indigo-600 text-white">
                            {columns[selectedTable]?.map((col) => (
                                <th key={col.key} className="px-6 py-3 text-left font-medium">{col.label}</th>
                            ))}
                        </tr>
                    </thead>
                    <tbody>
                        {table?.data?.map((row, index) => (
                            <tr key={row.id} className={`border-b ${index % 2 === 0 ? 'bg-gray-100' : 'bg-white'} hover:bg-gray-200`}>
                                {columns[selectedTable]?.map((col) => (
                                    <td key={col.key} className="px-6 py-4 text-gray-700">
                                        {col.key === 'option' ? (
                                            <div className="flex space-x-2">
                                                <button
                                                    onClick={() => handleEdit(row.id)}
                                                    className="text-indigo-600 hover:text-indigo-800"
                                                >
                                                    Edit
                                                </button>
                                                <button
                                                    onClick={() => handleDelete(row.id)}
                                                    className="text-red-600 hover:text-red-800"
                                                >
                                                    Delete
                                                </button>
                                            </div>
                                        ) : (
                                            getValue(row, col.key) || '-'
                                        )}
                                    </td>
                                ))}
                            </tr>
                        ))}
                    </tbody>
                </table>
            </div>

            <div className="mt-6 flex justify-between items-center">
                <p className="text-lg text-gray-700">{table?.total} records found</p>
                <div className="flex space-x-2">
                    <button
                        onClick={() => router.get(table?.prev_page_url, { selectedTable })}
                        disabled={!table?.prev_page_url}
                        className={`px-4 py-2 border rounded-lg ${table?.prev_page_url ? 'bg-gray-200 text-gray-600 hover:bg-indigo-500 hover:text-white' : 'bg-gray-300 text-gray-500 cursor-not-allowed'}`}
                    >
                        « Previous
                    </button>
                    <button
                        onClick={() => router.get(table?.next_page_url, { selectedTable })}
                        disabled={!table?.next_page_url}
                        className={`px-4 py-2 border rounded-lg ${table?.next_page_url ? 'bg-gray-200 text-gray-600 hover:bg-indigo-500 hover:text-white' : 'bg-gray-300 text-gray-500 cursor-not-allowed'}`}
                    >
                        Next »
                    </button>
                </div>
            </div>
        </div>
    );
}