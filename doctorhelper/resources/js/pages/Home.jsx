import { useState } from 'react';
import { FaUserMd, FaUsers, FaPrescriptionBottle, FaPills, FaCalendarAlt, FaVials, FaChevronDown, FaChevronUp } from 'react-icons/fa';

export default function Home() {
  const [openMenus, setOpenMenus] = useState({});

  const toggleMenu = (key) => {
    setOpenMenus((prev) => ({ ...prev, [key]: !prev[key] }));
  };

  return (
    <div className="flex min-h-screen bg-gray-100">
      {/* Sidebar */}
      <aside className="w-64 bg-white shadow-md">
        <div className="p-6 text-2xl font-bold border-b text-blue-700">DoctorHelper</div>
        <nav className="p-4 text-sm text-gray-700">
          {/* Sidebar Menu Items */}
          <MenuItem icon={<FaUserMd />} label="Doctors" subItems={['List', 'Add New']} openMenus={openMenus} toggleMenu={toggleMenu} id="doctors" />
          <MenuItem icon={<FaUsers />} label="Patients" subItems={['List', 'Add New']} openMenus={openMenus} toggleMenu={toggleMenu} id="patients" />
          <MenuItem icon={<FaPrescriptionBottle />} label="Prescriptions" subItems={['All Prescriptions', 'Add Prescription']} openMenus={openMenus} toggleMenu={toggleMenu} id="prescriptions" />
          <MenuItem icon={<FaPills />} label="Drugs" subItems={['All Drugs', 'Add Drug', 'Categories']} openMenus={openMenus} toggleMenu={toggleMenu} id="drugs" />
          <MenuItem icon={<FaCalendarAlt />} label="Appointments" subItems={['Calendar', 'Add Appointment']} openMenus={openMenus} toggleMenu={toggleMenu} id="appointments" />
          <MenuItem icon={<FaVials />} label="Tests" subItems={['All Tests', 'Add Test']} openMenus={openMenus} toggleMenu={toggleMenu} id="tests" />
        </nav>
      </aside>

      {/* Main content */}
      <main className="flex-1">
        {/* Header */}
        <header className="flex justify-between items-center bg-white p-4 shadow-md">
          <h1 className="text-xl font-bold text-blue-800">Dashboard</h1>
          <div className="flex items-center space-x-4">
            <input type="text" placeholder="Search..." className="px-4 py-1 border rounded" />
            <div className="w-8 h-8 bg-blue-200 rounded-full"></div>
          </div>
        </header>

        {/* Dashboard Cards */}
        <div className="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
          <StatCard title="Total Patients" count="326" color="blue" />
          <StatCard title="Appointments Today" count="12" color="green" />
          <StatCard title="Prescriptions" count="78" color="purple" />
        </div>

        {/* Placeholder Section */}
        <div className="px-6 pb-6">
          <div className="bg-white rounded shadow p-6 h-64 text-gray-500 text-center flex items-center justify-center">
            Graphs, Tables, and Charts Section
          </div>
        </div>
      </main>
    </div>
  );
}

function MenuItem({ icon, label, subItems = [], openMenus, toggleMenu, id }) {
  const isOpen = openMenus[id];

  return (
    <div>
      <button onClick={() => toggleMenu(id)} className="w-full flex justify-between items-center p-2 hover:bg-blue-100 rounded">
        <span className="flex items-center gap-2">{icon} {label}</span>
        {isOpen ? <FaChevronUp className="text-xs" /> : <FaChevronDown className="text-xs" />}
      </button>
      {isOpen && (
        <ul className="pl-6 text-gray-600 space-y-1 mt-1">
          {subItems.map((item, i) => (
            <li key={i} className="hover:text-blue-700 cursor-pointer">{item}</li>
          ))}
        </ul>
      )}
    </div>
  );
}

function StatCard({ title, count, color = 'blue' }) {
  const colorMap = {
    blue: 'bg-blue-100 text-blue-700',
    green: 'bg-green-100 text-green-700',
    purple: 'bg-purple-100 text-purple-700',
  };

  return (
    <div className={`p-4 rounded shadow ${colorMap[color]}`}>
      <h2 className="text-sm font-semibold mb-2">{title}</h2>
      <p className="text-2xl font-bold">{count}</p>
    </div>
  );
}
