import React from "react";
import { usePage, Link } from "@inertiajs/react";  // เพิ่ม Link จาก Inertia
import { FaGraduationCap, FaBook, FaUserFriends, FaBookOpen, FaAppleAlt, FaLaughBeam } from "react-icons/fa";

const Category = () => {
  const { categories = [] } = usePage().props;

  return (
    <div className="p-5">
      <h2 className="text-2xl font-bold mb-4">หมวดหมู่</h2>
      <div className="flex flex-wrap gap-6 justify-start">
        {/* ใช้ Link แทน onClick เพื่อเปลี่ยนหน้า */}
        {categories[0] && (
          <Link
            key={categories[0].id}
            href={route("Showcategory.index", { categoryName: categories[0].category_name })}
            className="flex flex-col items-center"
          >
            <div className="flex items-center justify-center p-8 bg-[#BA7D66] text-white rounded-full w-28 h-28">
              <FaLaughBeam  className="text-5xl text-white" />
            </div>
            <span className="text-sm text-center mt-2">{categories[0].category_name}</span>
          </Link>
        )}

        {categories[1] && (
          <Link
            key={categories[1].id}
            href={route("Showcategory.index", { categoryName: categories[1].category_name })}
            className="flex flex-col items-center"
          >
            <div className="flex items-center justify-center p-8 bg-[#BA7D66] text-white rounded-full w-28 h-28">
              <FaBook className="text-5xl text-white" />
            </div>
            <span className="text-sm text-center mt-2">{categories[1].category_name}</span>
          </Link>
        )}

        {categories[2] && (
          <Link
            key={categories[2].id}
            href={route("Showcategory.index", { categoryName: categories[2].category_name })}
            className="flex flex-col items-center"
          >
            <div className="flex items-center justify-center p-8 bg-[#BA7D66] text-white rounded-full w-28 h-28">
              <FaUserFriends className="text-5xl text-white" />
            </div>
            <span className="text-sm text-center mt-2">{categories[2].category_name}</span>
          </Link>
        )}

        {categories[3] && (
          <Link
            key={categories[3].id}
            href={route("Showcategory.index", { categoryName: categories[3].category_name })}
            className="flex flex-col items-center"
          >
            <div className="flex items-center justify-center p-8 bg-[#BA7D66] text-white rounded-full w-28 h-28">
              <FaGraduationCap className="text-5xl text-white" />
            </div>
            <span className="text-sm text-center mt-2">{categories[3].category_name}</span>
          </Link>
        )}

        {categories[4] && (
          <Link
            key={categories[4].id}
            href={route("Showcategory.index", { categoryName: categories[4].category_name })}
            className="flex flex-col items-center"
          >
            <div className="flex items-center justify-center p-8 bg-[#BA7D66] text-white rounded-full w-28 h-28">
              <FaAppleAlt className="text-5xl text-white" />
            </div>
            <span className="text-sm text-center mt-2">{categories[4].category_name}</span>
          </Link>
        )}

        {categories[5] && (
          <Link
            key={categories[5].id}
            href={route("Showcategory.index", { categoryName: categories[5].category_name })}
            className="flex flex-col items-center"
          >
            <div className="flex items-center justify-center p-8 bg-[#BA7D66] text-white rounded-full w-28 h-28">
              <FaBookOpen className="text-5xl text-white" />
            </div>
            <span className="text-sm text-center mt-2">{categories[5].category_name}</span>
          </Link>
        )}
      </div>
    </div>
  );
};

export default Category;
