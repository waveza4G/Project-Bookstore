import React from "react";
import Navbar from "./Navbar";
import Swiper from "./Swiper";
import Category from "./Category";

const Dashboard = () => {
  return (
    <div>
      <Navbar/>
      <div className="container mx-auto px-4">
        <Swiper/>
        <Category/>
      </div>
    </div>
  );
};

export default Dashboard;
