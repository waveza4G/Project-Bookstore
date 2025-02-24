import React from "react";
import Navbar from "./Navbar";
import Swiper from "./Swiper";
import Category from "./Category";

import Books from "../Category/Books";
import ComicsandManga from "../Category/ComicsandManga";
import Fiction from "../Category/Fiction";
import Novel from "../Category/Novel";
import Education from "../Category/Education";
import FoodAndHealth from "../Category/FoodAndHealth";
import Literature from "../Category/Literature";

const Dashboard = () => {
  return (
    <div>
      <Navbar />
      <div className="container mx-auto px-4 mt-8">
        <Swiper />
        {/* เพิ่ม margin-top ให้ div ครอบ Category */}
        <div className="mt-10">  {/* เพิ่ม margin-top ที่ div นี้ */}
          <Category />
        </div>
        <Books />
        <ComicsandManga />
        <Fiction />
        <Novel />
        <Education />
        <FoodAndHealth />
        <Literature />
      </div>
    </div>
  );
};

export default Dashboard;
