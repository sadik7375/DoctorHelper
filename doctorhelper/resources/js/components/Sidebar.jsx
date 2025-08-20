import { FaUserMd, FaUsers, FaPrescriptionBottle, FaPills, FaCalendarAlt, FaVials, FaChevronDown, FaChevronUp } from 'react-icons/fa';
import { useState } from 'react';

const MenuItem = ({ icon, label, subItems = [], openMenus, toggleMenu, id }) => {
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
};

export default function Sidebar() {
  const [openMenus, setOpenMenus] = useState({});

  const toggleMenu = (key) => {
    setOpenMenus(prev => ({ ...prev, [key]: !prev[key] }));
  };

  return (
    <aside className="w-64 bg-white shadow-md">
      <div className="p-6 text-2xl font-bold border-b text-blue-700">DoctorHelper</div>
      <nav className="p-4 text-sm text-gray-700">
        <MenuItem icon={<FaUserMd />} label="Doctors" subItems={['List', 'Add New']} openMenus={openMenus} toggleMenu={toggleMenu} id="doctors" />
        <MenuItem icon={<FaUsers />} label="Patients" subItems={['List', 'Add New']} openMenus={openMenus} toggleMenu={toggleMenu} id="patients" />
        <MenuItem icon={<FaPrescriptionBottle />} label="Prescriptions" subItems={['All Prescriptions', 'Add Prescription']} openMenus={openMenus} toggleMenu={toggleMenu} id="prescriptions" />
        <MenuItem icon={<FaPills />} label="Drugs" subItems={['All Drugs', 'Add Drug', 'Categories']} openMenus={openMenus} toggleMenu={toggleMenu} id="drugs" />
        <MenuItem icon={<FaCalendarAlt />} label="Appointments" subItems={['Calendar', 'Add Appointment']} openMenus={openMenus} toggleMenu={toggleMenu} id="appointments" />
        <MenuItem icon={<FaVials />} label="Tests" subItems={['All Tests', 'Add Test']} openMenus={openMenus} toggleMenu={toggleMenu} id="tests" />
      </nav>
    </aside>
  );
}
