import React, { useState, useEffect } from 'react';
import Sidebar from '../../components/Sidebar';
import Header from '../../components/Header';
import { createPatient, updatePatient } from '@/api/patients';
import { toast } from 'react-toastify';
import { router } from '@inertiajs/react';

export default function Create({ patient = {}, isEdit = false }) {
  const [form, setForm] = useState({
    name: patient.name || '',
    email: patient.email || '',
    phone_number: patient.phone_number || '',
    age: patient.age || '',
    blood_group: patient.blood_group || '',
    weight: patient.weight || '',
    height: patient.height || '',
    gender: patient.gender || '',
    marital_status: patient.marital_status || '',
    address: patient.address || '',
  });

  const [errors, setErrors] = useState({});

  const handleChange = (e) => {
    const { name, value } = e.target;
    setForm((prev) => ({ ...prev, [name]: value }));
  };

  const handleSubmit = async (e) => {
    e.preventDefault();

    const token = localStorage.getItem('token'); // Get token from local storage
    try {
      if (isEdit) {
        // Update patient if editing
        const res = await updatePatient(patient.id, form, token);
        toast.success(res.data.message);
      } else {
        // Create new patient if not editing
        const res = await createPatient(form, token);
        toast.success(res.data.message);
      }
      setErrors({});
      router.visit('/patients/all'); // Redirect to patient list after success
    } catch (err) {
      if (err.response && err.response.status === 422) {
        setErrors(err.response.data.errors); // Set form validation errors
      } else if (err.response && err.response.status === 401) {
        toast.error('Unauthorized. Please log in again.');
      } else {
        toast.error('Something went wrong.');
      }
    }
  };

  return (
    <div className="flex min-h-screen bg-gray-100">
      <Sidebar />
      <main className="flex-1">
        <Header />
        <div className="max-w-4xl mx-auto p-8 bg-white rounded shadow">
          <h2 className="text-xl font-bold mb-6 text-gray-700">{isEdit ? 'Edit Patient' : 'Add New Patient'}</h2>

          <form className="space-y-6" onSubmit={handleSubmit}>
            {/* Name & Email */}
            <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
              <Field label="Name" name="name" value={form.name} onChange={handleChange} error={errors.name} />
              <Field label="Email" name="email" value={form.email} onChange={handleChange} error={errors.email} />
            </div>

            {/* Phone & Age */}
            <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
              <Field label="Phone Number" name="phone_number" value={form.phone_number} onChange={handleChange} error={errors.phone_number} />
              <Field label="Age" name="age" type="number" value={form.age} onChange={handleChange} error={errors.age} />
            </div>

            {/* Blood Group, Weight, Height */}
            <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
              <Field label="Blood Group" name="blood_group" value={form.blood_group} onChange={handleChange} />
              <Field label="Weight (kg)" name="weight" type="number" value={form.weight} onChange={handleChange} error={errors.weight} />
              <Field label="Height (cm)" name="height" type="number" value={form.height} onChange={handleChange} error={errors.height} />
            </div>

            {/* Gender & Marital Status */}
            <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
              <SelectField
                label="Gender"
                name="gender"
                value={form.gender}
                onChange={handleChange}
                options={['male', 'female', 'other']}
                error={errors.gender}
              />
              <SelectField
                label="Marital Status"
                name="marital_status"
                value={form.marital_status}
                onChange={handleChange}
                options={['single', 'married', 'divorced', 'widowed']}
                error={errors.marital_status}
              />
            </div>

            {/* Address */}
            <div>
              <label className="block mb-1 text-sm font-medium">Address</label>
              <textarea
                name="address"
                rows="3"
                value={form.address}
                onChange={handleChange}
                className="w-full border px-4 py-2 rounded"
              ></textarea>
              {errors.address && <p className="text-red-500 text-sm mt-1">{errors.address[0]}</p>}
            </div>

            {/* Submit Button */}
            <button
              type="submit"
              className="px-6 py-2 bg-indigo-600 text-white font-semibold rounded hover:bg-indigo-700 transition"
            >
              {isEdit ? 'Update Patient' : 'Create Patient'}
            </button>
          </form>
        </div>
      </main>
    </div>
  );
}

// Field Component for Input
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
      {error && <p className="text-red-500 text-sm mt-1">{error[0]}</p>}
    </div>
  );
}

// SelectField Component for Select Inputs
function SelectField({ label, name, value, onChange, options, error }) {
  return (
    <div className="w-full">
      <label className="block text-sm mb-1 font-medium text-gray-700">{label}</label>
      <select
        name={name}
        value={value}
        onChange={onChange}
        className="w-full border px-3 py-2 rounded"
      >
        <option value="">Select...</option>
        {options.map((opt) => (
          <option key={opt} value={opt}>{opt}</option>
        ))}
      </select>
      {error && <p className="text-red-500 text-sm mt-1">{error[0]}</p>}
    </div>
  );
}
