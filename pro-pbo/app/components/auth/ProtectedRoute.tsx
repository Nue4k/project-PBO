// app/components/auth/ProtectedRoute.tsx

import { useAuth } from '../../lib/authContext'; // Impor useAuth
import { useRouter } from 'next/navigation'; // Impor useRouter
import { ReactNode, useEffect } from 'react';

interface ProtectedRouteProps {
  children: ReactNode;
  allowedRoles?: string[]; // Array role yang diizinkan, misalnya ['student', 'company']. Jika undefined, semua role diperbolehkan setelah login.
}

const ProtectedRoute = ({ children, allowedRoles }: ProtectedRouteProps) => {
  const { user, isLoading } = useAuth();
  const router = useRouter();

  // Tunggu status auth dimuat
  if (isLoading) {
    return <div className="container mx-auto p-4">Memuat...</div>; // Atau komponen loading lain
  }

  // Jika tidak login, redirect ke login
  if (!user) {
    router.push('/login');
    return null; // Penting: kembalikan null setelah redirect untuk mencegah render komponen anak
  }

  // Jika role tidak diizinkan, redirect atau tampilkan error
  if (allowedRoles && !allowedRoles.includes(user.role)) {
    // Contoh: redirect ke halaman home atau tampilkan pesan
    router.push('/'); // Atau halaman error khusus
    return <div className="container mx-auto p-4">Akses ditolak.</div>;
  }

  // Jika lolos semua pengecekan, render komponen anak
  return <>{children}</>;
};

export default ProtectedRoute;