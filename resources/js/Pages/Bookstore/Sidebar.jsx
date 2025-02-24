import React from 'react';
import { Link, usePage } from '@inertiajs/react';

const Sidebar = () => {
  const { categories = [], groups = [] } = usePage().props;

  return (
    <div className="w-1/4 p-6 bg-gray-50 border-r border-gray-300">
      {/* หมวดหมู่ */}
      <div>
        <h2 className="text-xl font-bold mb-4 text-gray-700">หมวดหมู่</h2>
        <ul className="space-y-2">
          {categories.map((cat) => (
            <li key={cat.id}>
              <Link
                href={route("Showcategory.index", { categoryName: cat.category_name })}
                className="block py-2 px-4 rounded-md text-gray-700 hover:bg-[#BA7D66] hover:text-white transition duration-300"
              >
                {cat.category_name}
              </Link>
            </li>
          ))}
          <li>
            <Link
              href={route("Showcategory.index")}
              className="block py-2 px-4 rounded-md text-gray-700 hover:bg-[#BA7D66] hover:text-white transition duration-300"
            >
              ทั้งหมด
            </Link>
          </li>
        </ul>
      </div>

      {/* ประเภท */}
      <div className="mt-6">
        <h2 className="text-xl font-bold mb-4 text-gray-700">ประเภท</h2>
        <ul className="space-y-2">
          {groups.map((group) => (
            <li key={group.id}>
              <Link
                href={route("Showcategory.index", { group_name: group.group_name })}
                className="block py-2 px-4 rounded-md text-gray-700 hover:bg-[#BA7D66] hover:text-white transition duration-300"
              >
                {group.group_name}
              </Link>
            </li>
          ))}
        </ul>
      </div>
    </div>
  );
};

export default Sidebar;
