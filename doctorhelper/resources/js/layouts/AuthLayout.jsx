import React, { useEffect } from 'react';
import { usePage, router } from '@inertiajs/react';

export default function AuthLayout({ children }) {
  useEffect(() => {
    const token = localStorage.getItem('token');
    if (!token) {
      router.visit('/login'); // Redirect if token not found
    }
  }, []);

  return (
    <div>
      {children}
    </div>
  );
}
