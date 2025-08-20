import React, { useState } from 'react';
import AuthLayout from '@/layouts/AuthLayout';
import Sidebar from '../components/Sidebar';
import Header from '../components/Header';
import Dashboard from '../components/Dashboard';

export default function Home() {
  const [openMenus, setOpenMenus] = useState({});

  const toggleMenu = (key) => {
    setOpenMenus((prev) => ({ ...prev, [key]: !prev[key] }));
  };

  return (
     <AuthLayout>
    <div className="flex min-h-screen bg-gray-100">
      <Sidebar openMenus={openMenus} toggleMenu={toggleMenu} />
      <main className="flex-1">
        <Header />
        <Dashboard />
      </main>
    </div>
    </AuthLayout>
  );
}
