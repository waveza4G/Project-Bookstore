import React from "react";
import Navbar from "./Navbar";
import Swiper from "./Swiper";
import Category from "./Category";

import Books from "../Category/Books";
import Books2 from "../Category/Books2";
import ComicsandManga from "../Category/ComicsandManga";
import Fiction from "../Category/Fiction";
import Novel from "../Category/Novel";
import Education from "../Category/Education";
import FoodAndHealth from "../Category/FoodAndHealth";
import Literature from "../Category/Literature";

const Dashboard = () => {
  return (
    <div className="bg-[#FFFBF4] min-h-screen">
      <Navbar />
      <div className="container mx-auto px-4 mt-8">
        <Swiper />
        <div className="mt-10">
          <Category />
        </div>
        <Books />
        <Books2/>


      </div>
    </div>
  );
};

export default Dashboard;
