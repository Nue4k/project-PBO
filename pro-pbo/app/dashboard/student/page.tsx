// app/dashboard/student/page.tsx

'use client'; // Karena menggunakan hook useAuth dan ProtectedRoute

import { useAuth } from '../../lib/authContext'; // Impor useAuth untuk info user (naik 2 level dari dashboard/student ke app, lalu masuk lib)
import ProtectedRoute from '../../components/auth/ProtectedRoute'; // Impor komponen proteksi

const StudentDashboard = () => {
  const { user } = useAuth(); // Dapatkan data user dari context

  return (
    <ProtectedRoute allowedRoles={['student']}> {/* Hanya izinkan role 'student' */}
      <div className="container mx-auto p-4">
        <h1 className="text-3xl font-bold mb-6">Dashboard Mahasiswa</h1>
        <div className="mb-6">
          <h2 className="text-xl font-semibold">Selamat datang, {user?.email?.split('@')[0] || user?.email}!</h2>
          {/* Di sini bisa ditambahkan komponen-komponen khusus student */}
          {/* Misalnya: Daftar Lamaran, Dokumen Saya, Pencarian Lowongan */}
        </div>
        <div>
          <h3 className="text-lg font-medium mb-2">Fitur Mahasiswa:</h3>
          <ul className="list-disc pl-5 space-y-1">
            <li>Lihat Lowongan Magang</li>
            <li>Kelola Profil</li>
            <li>Unggah/Atur Dokumen (CV, Transkrip)</li>
            <li>Lacak Status Lamaran</li>
          </ul>
        </div>
      </div>
    </ProtectedRoute>
  );
};

export default StudentDashboard;