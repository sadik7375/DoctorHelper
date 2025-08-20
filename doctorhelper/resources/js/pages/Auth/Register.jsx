import React, { useState } from 'react';
import { register } from '@/api/auth';
import { toast } from 'react-toastify';
import { router } from '@inertiajs/react';

export default function Register() {
  const [form, setForm] = useState({
    name: '',
    email: '',
    password: '',
    role: 'doctor',
  });

  const handleChange = (e) => setForm({ ...form, [e.target.name]: e.target.value });

  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      await register(form);
      toast.success('Registered successfully');
      router.visit('/login');
    } catch (err) {
      toast.error('Register failed');
    }
  };

  return (
    <form onSubmit={handleSubmit} className="max-w-md mx-auto p-6 bg-white shadow rounded">
      <h2 className="text-xl font-bold mb-4">Register</h2>
      <input name="name" placeholder="Full Name" onChange={handleChange} className="w-full border px-3 py-2 mb-3" />
      <input name="email" placeholder="Email" onChange={handleChange} className="w-full border px-3 py-2 mb-3" />
      <input name="password" placeholder="Password" type="password" onChange={handleChange} className="w-full border px-3 py-2 mb-3" />
      <select name="role" value={form.role} onChange={handleChange} className="w-full border px-3 py-2 mb-3">
        <option value="doctor">Doctor</option>
        <option value="staff">Staff</option>
      </select>
      <button className="bg-blue-600 text-white px-4 py-2 rounded">Register</button>
    </form>
  );
}
