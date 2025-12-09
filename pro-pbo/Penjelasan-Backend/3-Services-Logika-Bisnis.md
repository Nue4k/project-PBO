# 3. Services (Algoritma Detail)

Dokumen ini menjelaskan **Algoritma** (langkah-langkah logika) untuk fitur utama aplikasi.

---

## A. AuthService (Otentikasi)

### 1. Algoritma Register (`register`)
Input: `email`, `password`, `role`
1.  **Enkripsi**: Hash password menggunakan algoritma `Bcrypt` agar aman.
2.  **Buat User**: Simpan data ke tabel `users`.
3.  **Cek Role**:
    *   Jika `role == 'student'`, buat data baru di tabel `student_profiles` (kosong).
    *   Jika `role == 'company'`, buat data baru di tabel `company_profiles` (kosong).
4.  **Token**: Buat Token API baru via Sanctum.
5.  **Return**: Kembalikan data User + Token.

### 2. Algoritma Login (`login`)
Input: `email`, `password`
1.  **Cari User**: Cari di tabel `users` berdasarkan email.
2.  **Verifikasi**: Cocokkan password input dengan password hash di database.
    *   Jika salah -> Lempar Error "Invalid Credentials".
3.  **Bersihkan Sesi**: Hapus token lama (opsional, untuk keamanan).
4.  **Buat Token**: Terbitkan token baru.
5.  **Return**: Data User + Token.

---

## B. JobService (Manajemen Lowongan)

### 1. Algoritma Buat Lowongan (`createJob`)
Input: `title`, `description`, `requirements` (checklist), `company_id`
1.  **Validasi Pemilik**: Pastikan user yang request adalah benar-benar dari perusahaan tersebut.
2.  **Format Data**: Ambil data `requirements` (bisa berupa array checklist skill), ubah menjadi string **JSON**.
3.  **Simpan**: Insert ke tabel `jobs`.

---

## C. ApplicationService (Sistem Lamaran)

### 1. Algoritma Melamar Kerja (`createApplication`)
Input: `job_id`, `resume_id`, `cover_letter`
1.  **Cek Data**: Ambil data Job dan Mahasiswa berdasarkan ID.
2.  **Validasi Duplikasi**:
    *   Query ke tabel `applications`: `WHERE job_id = X AND student_id = Y`.
    *   Jika hasil ditemukan (sudah pernah melamar) -> **STOP** dan Error.
3.  **Simpan**: Insert data baru dengan status awal `applied`.
4.  **Return**: Data aplikasi yang baru dibuat.

### 2. Algoritma Update Status (`updateStatus`)
Input: `application_id`, `status` (accepted/rejected)
1.  **Cek Hak Akses**: Pastikan yang mengubah status adalah Perusahaan pemilik Job tersebut.
2.  **Update**: Ubah kolom `status` di tabel `applications`.
3.  **Logika Tambahan** (Jika Accepted):
    *   Otomatis ubah status `status` di `StudentProfile` menjadi `hired` (Opsional/Bisa dikembangkan).

---

## D. DocumentService (File System)

### 1. Algoritma Download File (`serve`)
Input: `document_id`
1.  **Ambil Metadata**: Cari info file di database.
2.  **Cek Permission**:
    *   User = Pemilik File? -> OK.
    *   User = Perusahaan yang dilamar oleh Pemilik File? -> OK.
    *   Lainnya -> **FORBIDDEN (403)**.
3.  **Cek Fisik**: Apakah file di `storage/app/public/...` benar-benar ada?
4.  **Stream**: Kirim file ke browser sebagai attachment download.
