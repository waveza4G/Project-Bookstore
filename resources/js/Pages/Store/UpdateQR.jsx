// resources/js/Components/UpdateQR.jsx

import { useForm, router } from '@inertiajs/react';
import { useState } from 'react';

export default function UpdateQR({ rental }) {
    const { data, setData, post, errors } = useForm({
        image: '', // กำหนดค่าเริ่มต้นเป็น string เปล่า
    });

    const [preview, setPreview] = useState(null);
    const [loading, setLoading] = useState(false);

    const handleImageChange = (e) => {
        const file = e.target.files[0];
        if (file) {
            setData('image', file);
            const reader = new FileReader();
            reader.onloadend = () => {
                setPreview(reader.result);
            };
            reader.readAsDataURL(file);
        }
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        setLoading(true);

        const formData = new FormData();
        formData.append('image', data.image);

        const uploadPath = `/admin/upload-image/${rental.id}`;
        const redirectPath = `/admin/edit/1/${rental.id}`;

        post(uploadPath, {
            body: formData,
            forceFormData: true,
            onSuccess: () => {
                alert('อัปโหลดรูปภาพสำเร็จ!');
                router.visit(redirectPath);
            },
            onError: (errors) => {
                console.error("Upload Error:", errors);
                setLoading(false);
            },
        });
    };

    return (
        <div className="min-h-screen flex items-center justify-center bg-gray-50">
            <div className="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
                <h2 className="text-2xl font-semibold mb-4 text-center">อัปโหลดรูปภาพสำหรับ Rental</h2>
                <form onSubmit={handleSubmit} className="space-y-4">
                    <label className="block text-center">เลือกไฟล์รูปภาพ:</label>
                    <input type="file" onChange={handleImageChange} className="border p-2 w-full" />
                    {preview && (
                        <div className="text-center">
                            <img src={preview} alt="Preview" className="mt-2 w-32 h-32 object-cover mx-auto" />
                        </div>
                    )}
                    <div className="flex justify-between mt-4">
                        <button
                            type="button"
                            onClick={() => router.visit(`/admin/edit/1/${rental.id}`)}
                            className="bg-gray-500 text-white px-4 py-2 rounded-md"
                        >
                            ย้อนกลับ
                        </button>
                        <button
                            type="submit"
                            disabled={loading}
                            className="bg-blue-500 text-white px-4 py-2 rounded-md"
                        >
                            {loading ? 'กำลังอัปโหลด...' : 'อัปโหลด'}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    );
}
