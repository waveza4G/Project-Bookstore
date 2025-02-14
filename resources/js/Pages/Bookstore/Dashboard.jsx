import React from 'react';
import Navbar from './Navbar';

const Dashboard = () => {
  const customer = { username: ''}; // หรือ null ถ้าไม่มีข้อมูลผู้ใช้

  return (
    <div>
      {/* Navbar จะแสดงตลอดเวลา */}
      <Navbar customer={customer} />
      
 

    </div>
  );
};

export default Dashboard;