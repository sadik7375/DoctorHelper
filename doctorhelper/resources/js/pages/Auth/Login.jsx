import React, { useState } from 'react';
import { login } from '@/api/auth';
import { toast } from 'react-toastify';
import { router } from '@inertiajs/react';

export default function Login() {
  const [form, setForm] = useState({ email: '', password: '' });

  const handleChange = (e) =>
    setForm({ ...form, [e.target.name]: e.target.value });

  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      const token = await login(form);
      localStorage.setItem('token', token);
      toast.success('Login successful');
      router.visit('/'); // ✅ redirect after login
    } catch (err) {
      toast.error('Login failed');
    }
  };

  return (
    <form onSubmit={handleSubmit} className="max-w-md mx-auto p-6 bg-white shadow">
      <h2 className="text-xl font-bold mb-4">Login</h2>
      <input name="email" placeholder="Email" onChange={handleChange} className="w-full border px-3 py-2 mb-3" />
      <input name="password" placeholder="Password" type="password" onChange={handleChange} className="w-full border px-3 py-2 mb-3" />
      <button className="bg-blue-600 text-white px-4 py-2 rounded">Login</button>
    </form>
  );
}
