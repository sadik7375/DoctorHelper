import React, { useEffect, useState } from 'react';
import Sidebar from '../../components/Sidebar';
import Header from '../../components/Header';
import { fetchPatients, deletePatient } from '@/api/patients';
import DataTable from 'react-data-table-component';
import { Link } from '@inertiajs/react';
import { toast } from 'react-toastify';

export default function Index() {
  const [patients, setPatients] = useState([]);
  const [selectedPatient, setSelectedPatient] = useState(null); // To hold the selected patient details for modal
  const [isModalOpen, setIsModalOpen] = useState(false); // Modal visibility state

  const loadPatients = () => {
    const token = localStorage.getItem('token');
    fetchPatients(token)
      .then((res) => setPatients(res.data.data))
      .catch(() => toast.error('Failed to load patients'));
  };

  useEffect(() => {
    loadPatients();
  }, []);

  // Filter patients based on the searchText input
  const handleDelete = async (id) => {
    const token = localStorage.getItem('token');
    if (!window.confirm('Are you sure you want to delete this patient?')) return;

    try {
      await deletePatient(id, token);
      toast.success('Patient deleted');
      setPatients((prev) => prev.filter((p) => p.id !== id));
    } catch (err) {
      toast.error('Failed to delete');
    }
  };

  const handleDetailsClick = (patient) => {
    setSelectedPatient(patient); // Set the selected patient for the modal
    setIsModalOpen(true); // Open the modal
  };

  const columns = [
    {
      name: 'ID',
      selector: (row) => row.id,
      width: '60px',
    },
    {
      name: 'Name',
      selector: (row) => row.name,
    },
    {
      name: 'Email',
      selector: (row) => row.email,
    },
    {
      name: 'Phone',
      selector: (row) => row.phone_number,
    },
    {
      name: 'Age',
      selector: (row) => row.age,
    },
    {
      name: 'Actions',
      cell: (row) => (
        <div className="flex gap-2">
          <Link
            href={`/patients/${row.id}/edit`}
            className="px-2 py-1 bg-yellow-400 text-sm rounded text-white"
          >
            Edit
          </Link>
          <button
            onClick={() => handleDelete(row.id)}
            className="px-2 py-1 bg-red-600 text-sm rounded text-white"
          >
            Delete
          </button>
          <button
            onClick={() => handleDetailsClick(row)}
            className="px-2 py-1 bg-blue-600 text-sm rounded text-white"
          >
            Details
          </button>
        </div>
      ),
    },
  ];

  return (
    <div className="flex min-h-screen bg-gray-100">
      <Sidebar />
      <main className="flex-1">
        <Header />
        <div className="p-6 bg-white shadow rounded">
          <div className="flex justify-between mb-4">
            <h1 className="text-xl font-bold">Patient List</h1>
            <Link
              href="/patients/create"
              className="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700"
            >
              + Add Patient
            </Link>
          </div>
          <DataTable
            columns={columns}
            data={patients}
            pagination
            highlightOnHover
            customStyles={{
              headCells: {
                style: {
                  fontWeight: 'bold',
                  fontSize: '14px',
                  backgroundColor: '#f9fafb',
                },
              },
            }}
          />
        </div>
      </main>

      {/* Modal for Patient Details */}
      {isModalOpen && (
        <div className="fixed inset-0 flex items-center justify-center bg-gray-500 bg-opacity-75 z-50">
          <div className="bg-white p-6 rounded shadow-lg w-96">
            <h2 className="text-xl font-semibold">Patient Details</h2>
            <div className="mt-4">
              <p><strong>Name:</strong> {selectedPatient.name}</p>
              <p><strong>Email:</strong> {selectedPatient.email}</p>
              <p><strong>Phone:</strong> {selectedPatient.phone_number}</p>
              <p><strong>Age:</strong> {selectedPatient.age}</p>
              <p><strong>Blood Group:</strong> {selectedPatient.blood_group}</p>
              <p><strong>Weight:</strong> {selectedPatient.weight} kg</p>
              <p><strong>Height:</strong> {selectedPatient.height} cm</p>
              <p><strong>Gender:</strong> {selectedPatient.gender}</p>
              <p><strong>Marital Status:</strong> {selectedPatient.marital_status}</p>
              <p><strong>Address:</strong> {selectedPatient.address}</p>
            </div>
            <div className="mt-4 flex justify-end gap-2">
              <button
                onClick={() => setIsModalOpen(false)}
                className="px-4 py-2 bg-gray-400 text-white rounded"
              >
                Close
              </button>
            </div>
          </div>
        </div>
      )}
    </div>
  );
}
