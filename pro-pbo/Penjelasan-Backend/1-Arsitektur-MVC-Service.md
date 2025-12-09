# 1. Arsitektur Sistem (Konsep Dasar)

Dokumen ini menjelaskan struktur dasar kode backend aplikasi.

---

## Konsep: MVC + Service Pattern
Aplikasi ini tidak menumpuk semua kode di satu tempat. Kami memecah kode menjadi 3 bagian utama agar lebih rapi dan mudah dirawat.

### Diagram Alur Data
Secara sederhana, inilah yang terjadi saat aplikasi berjalan:
1.  **Request**: Pengguna mengirim data.
2.  **Controller**: Menerima data tersebut.
3.  **Service**: Memproses data tersebut (Logika Utama).
4.  **Model/Database**: Menyimpan atau mengambil data.

### Penjelasan Komponen

#### 1. Controller
Berfungsi sebagai **Penerima Input**.
*   Tugasnya hanya menerima data dari aplikasi frontend (Web/HP).
*   Memastikan data yang dikirim lengkap (Validasi).
*   Meneruskan data ke **Service**.
*   Mengembalikan jawaban (Response) sukses atau gagal ke pengguna.

#### 2. Service
Berfungsi sebagai **Pusat Logika**.
*   Inilah inti dari aplikasi backend.
*   Melakukan semua perhitungan, pengecekan aturan, dan pengolahan data.
*   Contoh tugas: "Cek apakah stok tersedia", "Hitung total harga", "Simpan data lamaran".

#### 3. Model
Berfungsi sebagai **Penghubung Database**.
*   Representasi dari tabel di database.
*   Digunakan oleh Service untuk membaca atau menulis data ke tabel.

---

## Mengapa Memakai Cara Ini?
1.  **Kode Lebih Rapi**: Kita tahu persis di mana harus mencari kode. Kalau mau ubah tampilan respon ada di Controller, kalau mau ubah cara hitung ada di Service.
2.  **Mudah Diperbaiki**: Karena terpisah, perbaikan di satu bagian tidak mudah merusak bagian lain.
3.  **Keamanan**: Logika penting tidak terekspos langsung di bagian penerima input.
