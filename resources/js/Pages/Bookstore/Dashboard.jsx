import React from 'react';
import Navbar from './Navbar';
import Swiper from './swiper';

const Dashboard = () => {
  const customer = { username: ''}; // หรือ null ถ้าไม่มีข้อมูลผู้ใช้

  return (
    <div>
        <Navbar customer={customer} />
        <div className="container mx-auto px-4">
         <Swiper />


    </div>
    </div>
  );
};

export default Dashboard;