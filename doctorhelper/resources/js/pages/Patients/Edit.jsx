import React, { useEffect, useState } from 'react';
import Create from './Create';
import { router, usePage } from '@inertiajs/react';
import { fetchPatientById } from '@/api/patients';

export default function Edit({ id }) {
  const [patient, setPatient] = useState(null);

  useEffect(() => {
    const token = localStorage.getItem('token');
    fetchPatientById(id, token).then((res) => {
      setPatient(res.data.data);
    });
  }, [id]);

  if (!patient) return <div>Loading...</div>;

  return <Create patient={patient} isEdit />;
}
