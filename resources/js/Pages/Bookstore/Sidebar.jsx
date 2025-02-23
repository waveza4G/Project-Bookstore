import React from 'react';
import { Link, usePage } from '@inertiajs/react';

const Sidebar = () => {
  // รับข้อมูล categories และ groups จาก props
  const { categories = [], groups = [] } = usePage().props;

  return (
    <div className="w-1/4 p-4 border-r border-gray-200">
      {/* หมวดหมู่ */}
      <div>
        <h2 className="text-xl font-bold mb-4">หมวดหมู่</h2>
        <ul>
          {categories.map((cat) => (
            <li key={cat.id}>
              <Link
                href={route("Showcategory.index", { categoryName: cat.category_name })}
                className="block py-1 px-2 hover:bg-gray-100 rounded"
              >
                {cat.category_name}
              </Link>
            </li>
          ))}
          <li>
            <Link
              href={route("Showcategory.index")}
              className="block py-1 px-2 hover:bg-gray-100 rounded"
            >
              ทั้งหมด
            </Link>
          </li>
        </ul>
      </div>

      {/* ประเภท */}
      <div className="mt-6">
        <h2 className="text-xl font-bold mb-4">ประเภท</h2>
        <ul>
          {groups.map((group) => (
            <li key={group.id}>
              {/* ใช้ Link ไปที่ Showcategory พร้อมกับส่ง group_name */}
              <Link
                href={route("Showcategory.index", { group_name: group.group_name })}
                className="block py-1 px-2 hover:bg-gray-100 rounded"
              >
                {group.group_name} {/* แสดงชื่อกลุ่ม */}
              </Link>
            </li>
          ))}
        </ul>
      </div>
    </div>
  );
};

export default Sidebar;
