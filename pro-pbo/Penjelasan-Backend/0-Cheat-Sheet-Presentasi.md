# 0. Cheat Sheet Presentasi (Ringkasan)

Dokumen ini berisi poin-poin kunci untuk presentasi Anda. Gunakan ini sebagai panduan utama saat menjelaskan sistem.

---

## 🚀 Poin Utama Presentasi

### 1. Deskripsi Sistem
"Aplikasi ini adalah Platform Lowongan Kerja yang dibangun menggunakan **Laravel**. Kami menggunakan arsitektur yang memisahkan **Logika Bisnis** (Services) dan **Penanganan Request** (Controllers) agar sistem lebih rapi, terstruktur, dan mudah dikembangkan."

### 2. Penjelasan Arsitektur (Tanpa Analogi)
Jelaskan pembagian tugas setiap komponen:
*   **Controller**: Menerima input dari pengguna dan memberikan respon. Tidak boleh ada logika rumit di sini.
*   **Service**: Tempat memproses data dan aturan bisnis (misalnya: hitung gaji, cek status, proses simpan data).
*   **Model**: Penghubung ke database (Tabel).

### 3. Keunggulan Teknis
*   **Terstruktur**: Kode fungsi bisnis tidak tercampur dengan kode pengaturan HTTP.
*   **Aman**: Menggunakan Token untuk login dan perlindungan file dokumen privat.
*   **Fleksibel**: Menggunakan format JSON untuk menyimpan persyaratan lowongan yang bervariasi.

---

## ❓ Pertanyaan yang Sering Diajukan (FAQ)

**Q: Mengapa file resume tidak bisa didownload langsung (link publik)?**
**A:** "Untuk keamanan data pribadi pengguna. Kami menggunakan sistem *Protected Serving*, di mana sistem akan mengecek dulu apakah si pendownload memiliki hak akses sebelum mengirimkan filenya."

**Q: Mengapa data profil dipisah dari tabel User?**
**A:** "Agar tabel User fokus hanya untuk login (Email & Password), sedangkan data detail seperti alamat dan pendidikan disimpan di tabel terpisah (`StudentProfile`) untuk kerapian database."

**Q: Mengapa kolom 'requirements' menggunakan JSON?**
**A:** "Karena persyaratan setiap lowongan berbeda-beda strukturnya. JSON memungkinkan kami menyimpan data yang bervariasi tanpa harus mengubah struktur kolom tabel database."

---

## 🗺️ Alur Penjelasan (Urutan Demo)
Saat mendemonstrasikan kode, ikuti urutan ini:
1.  **Arsitektur**: Jelaskan konsep pemisahan Controller dan Service.
2.  **Database**: Tunjukkan tabel `User` dan `Profile`.
3.  **Service**: Tunjukkan kode `ApplicationService` sebagai contoh logika utama.
4.  **Controller**: Tunjukkan kode Controller yang pendek dan bersih.
5.  **Routes**: Tunjukkan daftar URL API.
