// app/dashboard/company/page.tsx

'use client'; // Karena menggunakan hook useAuth dan ProtectedRoute

import { useAuth } from '../../lib/authContext'; // Impor useAuth untuk info user (naik 2 level dari dashboard/company ke app, lalu masuk lib)
import ProtectedRoute from '../../components/auth/ProtectedRoute'; // Impor komponen proteksi

const CompanyDashboard = () => {
  const { user } = useAuth(); // Dapatkan data user dari context

  return (
    <ProtectedRoute allowedRoles={['company']}> {/* Hanya izinkan role 'company' */}
      <div className="container mx-auto p-4">
        <h1 className="text-3xl font-bold mb-6">Dashboard Perusahaan</h1>
        <div className="mb-6">
          <h2 className="text-xl font-semibold">Selamat datang, {user?.company_name || user?.email}!</h2>
          {/* Di sini bisa ditambahkan komponen-komponen khusus company */}
          {/* Misalnya: Daftar Lowongan, Pelamar Saya, Buat Lowongan Baru */}
        </div>
        <div>
          <h3 className="text-lg font-medium mb-2">Fitur Perusahaan:</h3>
          <ul className="list-disc pl-5 space-y-1">
            <li>Buat Lowongan Magang Baru</li>
            <li>Kelola Profil Perusahaan</li>
            <li>Lihat dan Kelola Pelamar</li>
            <li>Posting dan Update Status Lamaran</li>
          </ul>
        </div>
      </div>
    </ProtectedRoute>
  );
};

export default CompanyDashboard;