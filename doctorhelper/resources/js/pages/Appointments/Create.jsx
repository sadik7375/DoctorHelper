import React, { useEffect, useMemo, useState } from 'react';
import Sidebar from '../../components/Sidebar';
import Header from '../../components/Header';
import { toast } from 'react-toastify';
import { router } from '@inertiajs/react';
import { createPatient, searchPatientsByPhone } from '@/api/patients';
import { createAppointment } from '@/api/appointments';

// ---- helpers --------------------------------------------------
const normalizeCreatedPatient = (data) => {
  // Works with common API shapes: {patient:{...}}, {data:{patient:{...}}}, {data:{...}}, {...}
  return data?.patient ?? data?.data?.patient ?? data?.data ?? data;
};

const toNumber = (v, fallback = 0) => {
  const n = Number(v);
  return Number.isFinite(n) ? n : fallback;
};
// ---------------------------------------------------------------

export default function AppointmentCreate() {
const [token, setToken] = useState(null);
  const [mode, setMode] = useState('new'); // 'new' | 'old'

  // step 1 (new): patient form
  const [patientForm, setPatientForm] = useState({
    name: '',
    email: '',
    phone_number: '',
    age: '',
    weight: '',
    gender: 'male',
    address: '',
  });
  const [patientErrors, setPatientErrors] = useState({});
  const [savingPatient, setSavingPatient] = useState(false);

  // step 1 (old): search + pick
  const [phoneQuery, setPhoneQuery] = useState('');
  const [foundPatients, setFoundPatients] = useState([]);
  const [selectedPatient, setSelectedPatient] = useState(null);
  const [searching, setSearching] = useState(false);

  // step 2: appointment form
  const [appointmentForm, setAppointmentForm] = useState({
    appointment_date: '',
    appointment_time: '',
    fee: '',
    discount: 0,
    discount_reason: '',
    payment_method: 'cash',
    notes: '',
  });
  const [apptErrors, setApptErrors] = useState({});
  const [savingAppointment, setSavingAppointment] = useState(false);





useEffect(() => {
  const t = localStorage.getItem('token');
  if (!t) {
    console.log('Unauthorized. Please log in.');
    router.visit('/login'); // redirect to login
  } else {
    setToken(t);
  }
}, []);

  // debounce search for old patient
  useEffect(() => {
    if (mode !== 'old') return;
    if (!phoneQuery || phoneQuery.length < 3) {
      setFoundPatients([]);
      return;
    }
    const t = setTimeout(async () => {
      try {
        setSearching(true);
        const res = await searchPatientsByPhone(phoneQuery, token);
        setFoundPatients(res?.data?.data || []);
      } catch (e) {
        console.error(e);
        toast.error('Search failed');
      } finally {
        setSearching(false);
      }
    }, 400);
    return () => clearTimeout(t);
  }, [phoneQuery, mode, token]);

  const handlePatientChange = (e) => {
    const { name, value } = e.target;
    setPatientForm((p) => ({ ...p, [name]: value }));
  };
  const handleApptChange = (e) => {
    const { name, value } = e.target;
    setAppointmentForm((p) => ({ ...p, [name]: value }));
  };

  // Step 1 submit (when new)
  const submitNewPatient = async (e) => {
    e.preventDefault();
    try {
      setSavingPatient(true);
      const res = await createPatient(patientForm, token);
      const created = normalizeCreatedPatient(res.data);

      if (!created?.id) {
        console.warn('Unexpected createPatient payload:', res.data);
        // toast.error('Could not detect created patient ID.');
        console.log('Could not detect created patient ID.');
        return;
      }

      setSelectedPatient(created);
      setPatientErrors({});
      // toast.success('Patient created. Now add the appointment below.');
       console.log('Patient created. Now add the appointment below.');
      
    } catch (err) {
      console.error(err);
      if (err.response?.status === 422) {
        setPatientErrors(err.response.data.errors || {});
        toast.error('Please fix the highlighted patient fields.');
      } else if (err.response?.status === 401) {
        toast.error('Unauthorized. Please log in again.');
      } else {
        toast.error(err.response?.data?.message || 'Failed to create patient');
      }
    } finally {
      setSavingPatient(false);
    }
  };

  // Final submit: create appointment with selected/created patient
  const submitAppointment = async (e) => {
    e.preventDefault();

    if (!selectedPatient?.id) {
      toast.error('Please select or create a patient first');
      return;
    }
    if (!appointmentForm.appointment_date || !appointmentForm.appointment_time || !appointmentForm.fee) {
      toast.error('Date, time and fee are required.');
      return;
    }

    try {
      setSavingAppointment(true);

      const payload = {
        patient_id: selectedPatient.id,
        appointment_date: appointmentForm.appointment_date,
        appointment_time: appointmentForm.appointment_time,
        fee: toNumber(appointmentForm.fee),
        discount: toNumber(appointmentForm.discount || 0),
        discount_reason: appointmentForm.discount_reason || null,
        payment_method: appointmentForm.payment_method,
        notes: appointmentForm.notes || null,
      };

      const res = await createAppointment(payload, token);

      setApptErrors({});
      toast.success(res?.data?.message || 'Appointment created');
      console.log(res?.data?.message);
      // redirect if you want
      // router.visit('/patients/all');
    } catch (err) {
      console.error(err);
      if (err.response?.status === 422) {
        setApptErrors(err.response.data.errors || {});
        toast.error('Please fix the highlighted appointment fields.');
      } else if (err.response?.status === 401) {
        toast.error('Unauthorized. Please log in again.');
      } else {
        toast.error(err.response?.data?.message || 'Failed to create appointment');
      }
    } finally {
      setSavingAppointment(false);
    }
  };

  return (
    <div className="flex min-h-screen bg-gray-100">
      <Sidebar />
      <main className="flex-1">
        <Header />

        <div className="max-w-4xl mx-auto p-8 bg-white rounded shadow">
          <h2 className="text-xl font-bold mb-6 text-gray-700">Add Serial</h2>

          {/* Toggle New / Old */}
          <div className="mb-6 flex items-center gap-6">
            <label className="flex items-center gap-2">
              <input
                type="radio"
                name="patientMode"
                checked={mode === 'old'}
                onChange={() => { setMode('old'); setSelectedPatient(null); }}
              />
              <span>Old Patient</span>
            </label>
            <label className="flex items-center gap-2">
              <input
                type="radio"
                name="patientMode"
                checked={mode === 'new'}
                onChange={() => { setMode('new'); setSelectedPatient(null); }}
              />
              <span>New Patient</span>
            </label>
          </div>

          {/* STEP 1 */}
          {mode === 'new' ? (
            <form className="space-y-4 mb-8" onSubmit={submitNewPatient}>
              <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                <Field label="Name *" name="name" value={patientForm.name} onChange={handlePatientChange} error={patientErrors.name} />
                <Field label="Email *" name="email" value={patientForm.email} onChange={handlePatientChange} error={patientErrors.email} />
                <Field label="Phone *" name="phone_number" value={patientForm.phone_number} onChange={handlePatientChange} error={patientErrors.phone_number} />
                <Field label="Age" name="age" type="number" value={patientForm.age} onChange={handlePatientChange} error={patientErrors.age} />
                <Field label="Weight" name="weight" type="number" value={patientForm.weight} onChange={handlePatientChange} error={patientErrors.weight} />
              </div>

              <div className="flex items-center gap-6">
                <label className="flex items-center gap-2">
                  <input type="radio" name="gender" value="male" checked={patientForm.gender === 'male'} onChange={handlePatientChange} />
                  <span>Male</span>
                </label>
                <label className="flex items-center gap-2">
                  <input type="radio" name="gender" value="female" checked={patientForm.gender === 'female'} onChange={handlePatientChange} />
                  <span>Female</span>
                </label>
              </div>

              <button
                type="submit"
                className="px-4 py-2 bg-indigo-600 text-white rounded disabled:opacity-60"
                disabled={savingPatient}
              >
                {savingPatient ? 'Saving…' : 'Save Patient'}
              </button>
            </form>
          ) : (
            <div className="mb-8">
              <label className="block text-sm mb-1 font-medium text-gray-700">Search by Phone</label>
              <input
                type="text"
                className="w-full border px-3 py-2 rounded"
                placeholder="Type phone number..."
                value={phoneQuery}
                onChange={(e) => setPhoneQuery(e.target.value)}
              />
              {searching && <p className="text-sm text-gray-500 mt-2">Searching…</p>}

              <div className="mt-3 space-y-2">
                {foundPatients.map((p) => (
                  <button
                    type="button"
                    key={p.id}
                    onClick={() => setSelectedPatient(p)}
                    className={`w-full text-left px-3 py-2 border rounded hover:bg-gray-50 ${selectedPatient?.id === p.id ? 'ring-2 ring-indigo-400' : ''}`}
                  >
                    <div className="font-medium">{p.name}</div>
                    <div className="text-sm text-gray-600">{p.phone_number} · {p.email}</div>
                  </button>
                ))}
                {!searching && phoneQuery.length >= 3 && foundPatients.length === 0 && (
                  <p className="text-sm text-gray-500">No patients found.</p>
                )}
              </div>
            </div>
          )}

          {/* Selected patient preview */}
          {selectedPatient && (
            <div className="mb-6 p-3 border rounded bg-gray-50">
              <div className="font-semibold">Selected Patient</div>
              <div className="text-sm text-gray-700">
                {selectedPatient.name} — {selectedPatient.phone_number}
              </div>
            </div>
          )}

          {/* STEP 2: Appointment form */}
          <form className="space-y-4" onSubmit={submitAppointment}>
            <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
              <Field label="Appointment Date *" name="appointment_date" type="date" value={appointmentForm.appointment_date} onChange={handleApptChange} error={apptErrors.appointment_date} />
              <Field label="Appointment Time *" name="appointment_time" type="time" value={appointmentForm.appointment_time} onChange={handleApptChange} error={apptErrors.appointment_time} />
              <Field label="Fee *" name="fee" type="number" value={appointmentForm.fee} onChange={handleApptChange} error={apptErrors.fee} />
              <Field label="Discount" name="discount" type="number" value={appointmentForm.discount} onChange={handleApptChange} error={apptErrors.discount} />
            </div>

            <Field label="Discount Reason" name="discount_reason" value={appointmentForm.discount_reason} onChange={handleApptChange} error={apptErrors.discount_reason} />
            <SelectField
              label="Payment Method *"
              name="payment_method"
              value={appointmentForm.payment_method}
              onChange={handleApptChange}
              options={['cash','card','bkash','rocket','nagad']}
              error={apptErrors.payment_method}
            />
            <div>
              <label className="block text-sm mb-1 font-medium">Notes</label>
              <textarea name="notes" rows="3" value={appointmentForm.notes} onChange={handleApptChange} className="w-full border px-3 py-2 rounded" />
              {apptErrors.notes && <p className="text-red-500 text-sm mt-1">{apptErrors.notes[0]}</p>}
            </div>

            <button
              type="submit"
              className="px-5 py-2 bg-blue-600 text-white rounded disabled:opacity-60"
              disabled={savingAppointment}
            >
              {savingAppointment ? 'Saving…' : '✓ Add Serial'}
            </button>
          </form>
        </div>
      </main>
    </div>
  );
}

// Reusable fields
function Field({ label, name, value, onChange, type = 'text', error }) {
  return (
    <div className="w-full">
      <label className="block text-sm mb-1 font-medium text-gray-700">{label}</label>
      <input
        type={type}
        name={name}
        value={value}
        onChange={onChange}
        className="w-full border px-3 py-2 rounded"
      />
      {error && <p className="text-red-500 text-sm mt-1">{Array.isArray(error) ? error[0] : error}</p>}
    </div>
  );
}

function SelectField({ label, name, value, onChange, options, error }) {
  return (
    <div className="w-full">
      <label className="block text-sm mb-1 font-medium text-gray-700">{label}</label>
      <select name={name} value={value} onChange={onChange} className="w-full border px-3 py-2 rounded">
        <option value="">Select…</option>
        {options.map(o => <option key={o} value={o}>{o}</option>)}
      </select>
      {error && <p className="text-red-500 text-sm mt-1">{Array.isArray(error) ? error[0] : error}</p>}
    </div>
  );
}
