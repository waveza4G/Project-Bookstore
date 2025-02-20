import React from "react";
import Navbar from "./Navbar";

const Dashboard = () => {
  return (
    <div>
      <Navbar />
      <div className="container mx-auto px-4">
        <h1 className="text-3xl font-bold">Welcome to Dashboard</h1>
      </div>
    </div>
  );
};

export default Dashboard;
