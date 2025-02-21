import React from "react";
import { usePage } from "@inertiajs/react";
import { FaGraduationCap, FaBook, FaUserFriends, FaBookOpen, FaAppleAlt, FaLaughBeam } from "react-icons/fa";

// กำหนดไอคอนเป็น array และใช้ index ใน loop
const icons = [FaGraduationCap, FaBook, FaUserFriends, FaBookOpen, FaAppleAlt, FaLaughBeam];

const Category = () => {
  try {
    const { categories = [] } = usePage().props;
    return (
      <div className="p-5">
        <h2 className="text-2xl font-bold mb-4">หมวดหมู่</h2>
        <div className="flex flex-wrap gap-6 justify-start">
          {categories.map((category, index) => {
            const IconComponent = icons[index % icons.length]; // ใช้ไอคอนวนซ้ำ
            return (
              <div key={category.id} className="flex flex-col items-center">
                <div className="flex items-center justify-center p-8 bg-[#BA7D66] text-white rounded-full w-28 h-28">
                  <IconComponent className="text-5xl text-white" />
                </div>
                <span className="text-sm text-center mt-2">{category.category_name}</span>
              </div>
            );
          })}
        </div>
      </div>
    );
  } catch (error) {
    return <div>Loading categories...</div>;
  }
};

export default Category;
